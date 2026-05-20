<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Product;
use Database\Seeders\MasterProductCatalogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientObserverTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(MasterProductCatalogSeeder::class);
    }

    public function test_creating_a_client_auto_attaches_default_master_products(): void
    {
        $defaults = Product::defaultsForNewClients()->count();
        $this->assertGreaterThan(0, $defaults, 'El seeder debe haber creado productos por defecto');

        $client = Client::factory()->create();

        $this->assertSame($defaults, $client->products()->count());
        $this->assertDatabaseCount('products', $defaults);
        $this->assertNull($client->products->first()->pivot->custom_price, 'custom_price debe ser NULL al adjuntar por defecto');
    }

    public function test_creating_multiple_clients_does_not_duplicate_master_products(): void
    {
        $countBefore = Product::count();

        Client::factory()->count(3)->create();

        $this->assertSame($countBefore, Product::count(), 'El catálogo maestro no debe duplicarse al crear clientes');
    }
}
