<?php

declare(strict_types=1);

namespace Tests\Feature\Performance;

use App\Enums\PaymentType;
use App\Enums\RemissionStatus;
use App\Models\Client;
use App\Models\Product;
use App\Models\Remission;
use App\Models\User;
use Database\Seeders\MasterProductCatalogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Performance assertions sobre el listado y eager-loading de remisiones.
 *
 * El sistema legacy sufría N+1 al renderizar listados de remisiones
 * (1 query por cliente, 1 por usuario, 1 por línea de producto).
 * Estas pruebas blindan que la versión nueva use eager loading.
 */
class RemissionListingPerformanceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(MasterProductCatalogSeeder::class);
    }

    public function test_listing_remissions_with_eager_loading_avoids_n_plus_one(): void
    {
        $this->seedRemissions(15);

        DB::enableQueryLog();
        $list = Remission::with(['client', 'user', 'products'])->get();
        $queries = DB::getQueryLog();
        DB::disableQueryLog();

        $this->assertCount(15, $list);

        $this->assertLessThanOrEqual(
            5,
            count($queries),
            sprintf(
                'Listar 15 remisiones con eager loading debe usar ≤ 5 queries; usó %d.',
                count($queries)
            )
        );
    }

    public function test_listing_remissions_without_eager_loading_triggers_n_plus_one(): void
    {
        $this->seedRemissions(10);

        DB::enableQueryLog();
        $list = Remission::all();
        foreach ($list as $remission) {
            $remission->client;
            $remission->user;
        }
        $queries = DB::getQueryLog();
        DB::disableQueryLog();

        $this->assertGreaterThan(
            15,
            count($queries),
            'Sin eager loading, accesos client/user por iteración debe generar N+1.'
        );
    }

    public function test_remission_query_time_stays_under_budget(): void
    {
        $this->seedRemissions(50);

        $start = microtime(true);
        $rows = Remission::with(['client', 'user', 'products'])->get();
        $duration = microtime(true) - $start;

        $this->assertCount(50, $rows);
        $this->assertLessThan(
            2.0,
            $duration,
            sprintf('Listar 50 remisiones (incluido eager) debe tardar < 2s (tomó %.3fs).', $duration)
        );
    }

    private function seedRemissions(int $count): void
    {
        $client = Client::factory()->create();
        $user = User::factory()->create();
        $product = Product::where('sku', 'H-5000')->firstOrFail();

        for ($i = 0; $i < $count; $i++) {
            $remission = Remission::create([
                'client_id' => $client->id,
                'user_id' => $user->id,
                'issued_at' => now()->subDays($i),
                'route' => 'route_01',
                'payment_type' => PaymentType::Credit->value,
                'status' => RemissionStatus::Confirmed->value,
                'total_amount' => 0,
            ]);

            $remission->products()->attach($product->id, [
                'quantity' => 5,
                'unit_price_snapshot' => 8500,
                'subtotal' => 42500,
            ]);
        }
    }
}
