<?php

namespace Database\Seeders;

use App\Enums\PaymentType;
use App\Enums\RemissionStatus;
use App\Models\Client;
use App\Models\Product;
use App\Models\Remission;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DemoDataSeeder extends Seeder
{
    /**
     * Datos de demostración (no se ejecuta por defecto en producción).
     * Crea 5 clientes con algunos overrides de precio, 2 vendedores y ~20 remisiones de muestra.
     */
    public function run(): void
    {
        $sellers = User::factory()->count(2)->create()->each(
            fn (User $u) => $u->assignRole('seller'),
        );

        $clients = Client::factory()->count(5)->create();

        $product = Product::where('sku', 'H-5000')->first();
        if ($product) {
            $clients->first()->products()->updateExistingPivot($product->id, ['custom_price' => 7500]);
            $clients->skip(1)->first()->products()->updateExistingPivot($product->id, ['custom_price' => 8000]);
        }

        $paymentTypes = PaymentType::cases();

        for ($i = 0; $i < 20; $i++) {
            $client = $clients->random();
            $seller = $sellers->random();
            $when = Carbon::now()->subDays(rand(0, 25))->subHours(rand(0, 23));

            $remission = Remission::create([
                'client_id' => $client->id,
                'user_id' => $seller->id,
                'issued_at' => $when,
                'route' => 'route_'.str_pad((string) rand(1, 16), 2, '0', STR_PAD_LEFT),
                'payment_type' => $paymentTypes[array_rand($paymentTypes)]->value,
                'status' => RemissionStatus::Confirmed->value,
                'observations' => null,
                'total_amount' => 0,
            ]);

            $products = $client->products()->wherePivot('is_available', true)->inRandomOrder()->take(rand(2, 5))->get();
            $total = 0;

            foreach ($products as $p) {
                $qty = rand(1, 10);
                $price = (int) ($p->pivot->custom_price ?? $p->default_price);
                $subtotal = $qty * $price;
                $total += $subtotal;

                $remission->products()->attach($p->id, [
                    'quantity' => $qty,
                    'unit_price_snapshot' => $price,
                    'subtotal' => $subtotal,
                ]);
            }

            $remission->update(['total_amount' => $total]);
        }
    }
}
