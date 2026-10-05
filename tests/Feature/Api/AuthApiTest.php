<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_returns_token_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'vendedor@svd.test',
            'password' => bcrypt('secret123'),
        ]);

        $response = $this->postJson('/api/v1/login', [
            'email' => $user->email,
            'password' => 'secret123',
            'device_name' => 'phpunit',
        ]);

        $response->assertOk()
            ->assertJsonStructure(['token', 'user' => ['id', 'name', 'email', 'roles']]);
    }

    public function test_login_fails_with_invalid_credentials(): void
    {
        User::factory()->create([
            'email' => 'vendedor@svd.test',
            'password' => bcrypt('secret123'),
        ]);

        $response = $this->postJson('/api/v1/login', [
            'email' => 'vendedor@svd.test',
            'password' => 'wrong',
            'device_name' => 'phpunit',
        ]);

        $response->assertStatus(422);
    }

    public function test_me_requires_authentication(): void
    {
        $response = $this->getJson('/api/v1/me');
        $response->assertStatus(401);
    }

    public function test_logout_revokes_current_token(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('phpunit')->plainTextToken;

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/v1/logout')
            ->assertNoContent();

        $this->assertSame(0, $user->fresh()->tokens()->count(), 'el token debe haber sido revocado en la base de datos');
    }
}
