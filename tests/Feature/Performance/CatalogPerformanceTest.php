<?php

declare(strict_types=1);

namespace Tests\Feature\Performance;

use App\Models\Client;
use App\Models\Product;
use Database\Seeders\MasterProductCatalogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Performance assertions sobre el catálogo maestro.
 *
 * Estas pruebas garantizan que la mejora arquitectónica clave del sistema
 * (catálogo maestro + pivot de overrides en vez de N×31 filas duplicadas)
 * se mantiene durante refactors futuros.
 */
class CatalogPerformanceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(MasterProductCatalogSeeder::class);
    }

    public function test_master_catalog_remains_a_single_row_per_product(): void
    {
        $duplicates = DB::table('products')
            ->select('sku', DB::raw('COUNT(*) as total'))
            ->groupBy('sku')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        $this->assertCount(0, $duplicates, 'El catálogo maestro nunca debe duplicar filas por SKU.');
    }

    public function test_attaching_defaults_to_new_client_avoids_n_plus_one_selects(): void
    {
        $catalogSize = Product::defaultsForNewClients()->count();
        $this->assertGreaterThan(0, $catalogSize);

        DB::enableQueryLog();
        $client = Client::factory()->create();
        $queries = DB::getQueryLog();
        DB::disableQueryLog();

        $selectsTouchingProducts = collect($queries)->filter(
            fn (array $q) => str_contains(strtolower($q['query']), 'select')
                && str_contains(strtolower($q['query']), 'products')
        );

        $this->assertLessThanOrEqual(
            3,
            $selectsTouchingProducts->count(),
            sprintf(
                'Attach de defaults debe ejecutar ≤ 3 SELECTs sobre products (sin N+1); ejecutó %d.',
                $selectsTouchingProducts->count()
            )
        );

        $this->assertSame($catalogSize, $client->products()->count());
    }

    public function test_bulk_client_creation_scales_linearly(): void
    {
        $start = microtime(true);

        $clients = Client::factory()->count(20)->create();

        $duration = microtime(true) - $start;

        $this->assertCount(20, $clients);
        $this->assertLessThan(
            10.0,
            $duration,
            sprintf('Crear 20 clientes con catálogo no debe tardar > 10s (tomó %.2fs).', $duration)
        );

        $expectedPivot = $clients->count() * Product::defaultsForNewClients()->count();
        $this->assertSame($expectedPivot, DB::table('client_product')->count());
    }

    public function test_price_resolution_is_consistent_across_overrides(): void
    {
        $clients = Client::factory()->count(10)->create();
        $product = Product::where('sku', 'H-5000')->firstOrFail();

        foreach ($clients as $i => $client) {
            if ($i % 2 === 0) {
                $client->products()->updateExistingPivot($product->id, [
                    'custom_price' => 1000 + ($i * 100),
                ]);
            }
        }

        foreach ($clients as $i => $client) {
            $expected = $i % 2 === 0
                ? 1000 + ($i * 100)
                : (int) $product->default_price;

            $this->assertSame($expected, $product->priceFor($client));
        }
    }
}
