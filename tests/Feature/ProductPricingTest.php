<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Product;
use Database\Seeders\MasterProductCatalogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductPricingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(MasterProductCatalogSeeder::class);
    }

    public function test_price_for_falls_back_to_default_when_no_override(): void
    {
        $client = Client::factory()->create();
        $product = Product::where('sku', 'H-5000')->firstOrFail();

        $this->assertSame((int) $product->default_price, $product->priceFor($client));
    }

    public function test_price_for_uses_custom_price_when_override_exists(): void
    {
        $client = Client::factory()->create();
        $product = Product::where('sku', 'H-5000')->firstOrFail();
        $custom = 7800;

        $client->products()->updateExistingPivot($product->id, ['custom_price' => $custom]);

        $this->assertSame($custom, $product->priceFor($client));
    }

    public function test_changing_master_price_does_not_affect_clients_with_override(): void
    {
        $clientWithOverride = Client::factory()->create();
        $clientWithoutOverride = Client::factory()->create();

        $product = Product::where('sku', 'H-5000')->firstOrFail();
        $clientWithOverride->products()->updateExistingPivot($product->id, ['custom_price' => 7800]);

        $product->update(['default_price' => 9000]);

        $this->assertSame(7800, $product->fresh()->priceFor($clientWithOverride), 'Cliente con override conserva su precio');
        $this->assertSame(9000, $product->fresh()->priceFor($clientWithoutOverride), 'Cliente sin override recibe nuevo default');
    }
}
