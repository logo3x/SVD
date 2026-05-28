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
     * Datos de demostración para visualizar el funcionamiento (gráficas,
     * reportes, dashboard). Volumen ampliado y repartido en 90 días —que es
     * la ventana de las gráficas del dashboard— para que se vea poblado.
     *
     * Volúmenes configurables (ajustables abajo):
     *   8 vendedores, 18 clientes, ~220 remisiones en los últimos 90 días.
     */
    public function run(): void
    {
        $sellerCount = 8;
        $clientCount = 18;
        $remissionCount = 220;
        $daysBack = 90;

        $sellers = User::factory()->count($sellerCount)->create()->each(
            fn (User $u) => $u->assignRole('seller'),
        );

        $clients = Client::factory()->count($clientCount)->create();

        // Algunos overrides de precio para mostrar la cascada de precios.
        $product = Product::where('sku', 'H-5000')->first();
        if ($product) {
            $clients->first()->products()->updateExistingPivot($product->id, ['custom_price' => 7500]);
            $clients->skip(1)->first()->products()->updateExistingPivot($product->id, ['custom_price' => 8000]);
        }

        $paymentTypes = PaymentType::cases();

        // Pre-cargamos los productos disponibles por cliente para no consultar
        // en cada iteración (evita N+1 en un seeder de cientos de remisiones).
        $availableByClient = [];
        foreach ($clients as $client) {
            $availableByClient[$client->id] = $client->products()
                ->wherePivot('is_available', true)
                ->get();
        }

        for ($i = 0; $i < $remissionCount; $i++) {
            $client = $clients->random();
            $seller = $sellers->random();
            // Reparte las remisiones a lo largo de los últimos 90 días, con
            // un leve sesgo hacia fechas recientes para que se vea natural.
            $when = Carbon::now()
                ->subDays((int) round((rand(0, $daysBack) ** 1.2) / ($daysBack ** 0.2)))
                ->subHours(rand(0, 23))
                ->subMinutes(rand(0, 59));

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

            $available = $availableByClient[$client->id];
            if ($available->isEmpty()) {
                continue;
            }

            $products = $available->random(min($available->count(), rand(2, 5)));
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
