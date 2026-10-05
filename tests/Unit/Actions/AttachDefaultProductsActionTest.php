<?php

declare(strict_types=1);

namespace Tests\Unit\Actions;

use App\Actions\AttachDefaultProductsAction;
use App\Models\Client;
use App\Models\Product;
use Database\Seeders\MasterProductCatalogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttachDefaultProductsActionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(MasterProductCatalogSeeder::class);
    }

    public function test_action_returns_zero_when_no_default_products_exist(): void
    {
        Product::query()->update(['is_default_for_new_clients' => false]);

        $client = Client::withoutEvents(fn () => Client::factory()->create());
        $attached = (new AttachDefaultProductsAction)->execute($client);

        $this->assertSame(0, $attached);
        $this->assertSame(0, $client->products()->count());
    }

    public function test_action_skips_inactive_default_products(): void
    {
        Product::where('sku', 'H-5000')->update(['is_active' => false]);
        $expected = Product::defaultsForNewClients()->count();

        $client = Client::withoutEvents(fn () => Client::factory()->create());
        $attached = (new AttachDefaultProductsAction)->execute($client);

        $this->assertSame($expected, $attached);
        $this->assertSame(0, $client->products()->where('sku', 'H-5000')->count());
    }

    public function test_action_is_idempotent_via_sync_without_detaching(): void
    {
        $client = Client::withoutEvents(fn () => Client::factory()->create());

        $first = (new AttachDefaultProductsAction)->execute($client);
        $second = (new AttachDefaultProductsAction)->execute($client);

        $this->assertGreaterThan(0, $first);
        $this->assertSame($first, $client->products()->count(), 'segundo execute no debe duplicar pivots');
    }
}
