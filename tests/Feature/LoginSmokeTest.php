<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class LoginSmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_autenticado_accede_al_panel_admin(): void
    {
        Role::findOrCreate('admin', 'web');
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $this->actingAs($admin)->get('/admin')->assertSuccessful();
    }

    public function test_vendedor_accede_a_su_panel_pero_no_al_admin(): void
    {
        Role::findOrCreate('seller', 'web');
        $vendedor = User::factory()->create();
        $vendedor->assignRole('seller');

        // El vendedor puede entrar a su panel (200 o redirect interno, no 403).
        $this->actingAs($vendedor)->get('/vendedor')->assertStatus(302)->assertRedirectContains('/vendedor');
        // Pero NO al panel admin.
        $this->actingAs($vendedor)->get('/admin')->assertForbidden();
    }
}
