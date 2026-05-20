<?php

namespace Tests\Feature;

use App\Enums\PaymentType;
use App\Enums\RemissionStatus;
use App\Models\Client;
use App\Models\Product;
use App\Models\Remission;
use App\Models\User;
use App\Services\RemissionEmailRouter;
use Database\Seeders\MasterProductCatalogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RemissionCreationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(MasterProductCatalogSeeder::class);
    }

    public function test_remission_persists_snapshot_price_independent_of_master_changes(): void
    {
        $client = Client::factory()->create();
        $product = Product::where('sku', 'H-5000')->firstOrFail();
        $client->products()->updateExistingPivot($product->id, ['custom_price' => 7800]);

        $user = User::factory()->create();
        $price = $product->priceFor($client);

        $remission = Remission::create([
            'client_id' => $client->id,
            'user_id' => $user->id,
            'issued_at' => now(),
            'route' => 'route_01',
            'payment_type' => PaymentType::Credit->value,
            'status' => RemissionStatus::Confirmed->value,
            'total_amount' => 0,
        ]);

        $remission->products()->attach($product->id, [
            'quantity' => 5,
            'unit_price_snapshot' => $price,
            'subtotal' => $price * 5,
        ]);

        $product->update(['default_price' => 9999]);
        $client->products()->updateExistingPivot($product->id, ['custom_price' => 12000]);

        $line = $remission->fresh()->products()->first()->pivot;
        $this->assertSame(7800, (int) $line->unit_price_snapshot, 'el snapshot no debe cambiar al modificar override o catálogo');
        $this->assertSame(39000, (int) $line->subtotal);
    }

    public function test_email_router_resolves_recipients_per_payment_type(): void
    {
        $client = Client::factory()->create(['email' => 'client@test.com']);
        $user = User::factory()->create();

        $remission = Remission::create([
            'client_id' => $client->id,
            'user_id' => $user->id,
            'issued_at' => now(),
            'route' => 'route_01',
            'payment_type' => PaymentType::Credit->value,
            'status' => RemissionStatus::Confirmed->value,
            'total_amount' => 0,
        ])->fresh(['client']);

        $router = app(RemissionEmailRouter::class);

        $recipients = $router->recipientsFor($remission);
        $this->assertContains('client@test.com', $recipients);
        $this->assertGreaterThanOrEqual(2, count($recipients));

        $remission->payment_type = PaymentType::Gift;
        $giftRecipients = $router->recipientsFor($remission);
        $this->assertContains('client@test.com', $giftRecipients);

        $extra = $router->recipientsFor($remission, 'extra@test.com');
        $this->assertContains('extra@test.com', $extra);
    }
}
