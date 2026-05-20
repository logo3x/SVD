<?php

namespace Tests\Feature\Api;

use App\Enums\PaymentType;
use App\Mail\RemisionCreada;
use App\Models\Client;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\MasterProductCatalogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RemissionApiTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(MasterProductCatalogSeeder::class);
        $this->user = User::factory()->create();
    }

    public function test_list_client_products_includes_effective_price_from_override(): void
    {
        $client = Client::factory()->create();
        $product = Product::where('sku', 'H-5000')->firstOrFail();
        $client->products()->updateExistingPivot($product->id, ['custom_price' => 7800]);

        $token = $this->user->createToken('phpunit')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson("/api/v1/clients/{$client->id}/products");

        $response->assertOk();
        $hielo = collect($response->json('data'))->firstWhere('sku', 'H-5000');
        $this->assertNotNull($hielo);
        $this->assertSame(7800, $hielo['custom_price']);
        $this->assertSame(7800, $hielo['effective_price']);
        $this->assertSame((int) $product->default_price, $hielo['default_price']);
    }

    public function test_create_remission_recalculates_subtotal_and_total_server_side(): void
    {
        Mail::fake();

        $client = Client::factory()->create();
        $product = Product::where('sku', 'H-5000')->firstOrFail();
        $token = $this->user->createToken('phpunit')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/v1/remissions', [
                'client_id' => $client->id,
                'route' => 'route_03',
                'payment_type' => PaymentType::Credit->value,
                'items' => [
                    ['product_id' => $product->id, 'quantity' => 3, 'unit_price_snapshot' => 8500],
                ],
            ]);

        $response->assertCreated()
            ->assertJsonPath('data.total_amount', 25500)
            ->assertJsonPath('data.items.0.subtotal', 25500);

        Mail::assertQueued(RemisionCreada::class);
    }

    public function test_create_remission_validates_payload(): void
    {
        $token = $this->user->createToken('phpunit')->plainTextToken;

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/v1/remissions', [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['client_id', 'route', 'payment_type', 'items']);
    }

    public function test_signature_upload_attaches_media(): void
    {
        Mail::fake();

        $client = Client::factory()->create();
        $product = Product::where('sku', 'H-5000')->firstOrFail();
        $token = $this->user->createToken('phpunit')->plainTextToken;

        $create = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/v1/remissions', [
                'client_id' => $client->id,
                'route' => 'route_01',
                'payment_type' => PaymentType::Cash->value,
                'items' => [
                    ['product_id' => $product->id, 'quantity' => 1, 'unit_price_snapshot' => 8500],
                ],
            ]);

        $remissionId = $create->json('data.id');

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->post("/api/v1/remissions/{$remissionId}/signature", [
                'signature' => UploadedFile::fake()->image('sign.png'),
            ], ['Accept' => 'application/json']);

        $response->assertOk()->assertJsonPath('data.has_signature', true);
    }
}
