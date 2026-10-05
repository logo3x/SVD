<?php

namespace Database\Seeders;

use App\Actions\AttachDefaultProductsAction;
use App\Enums\DeliveryPoint;
use App\Enums\PaymentType;
use App\Enums\RemissionStatus;
use App\Models\Client;
use App\Models\Product;
use App\Models\Remission;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class DemoDataSeeder extends Seeder
{
    /**
     * Datos de demostración para visualizar el funcionamiento (gráficas,
     * reportes, dashboard). NO usa factories/faker, por lo que funciona en
     * producción (composer --no-dev) sin dependencias de desarrollo.
     *
     * Genera vendedores, clientes y ~220 remisiones repartidas en 90 días.
     */
    public function run(): void
    {
        $remissionCount = 220;
        $daysBack = 90;

        Role::findOrCreate('seller', 'web');

        $sellers = $this->createSellers();
        $clients = $this->createClients(app(AttachDefaultProductsAction::class));

        // Overrides de precio para mostrar la cascada de precios por cliente.
        $product = Product::where('sku', 'H-5000')->first();
        if ($product && $clients->count() >= 2) {
            $clients[0]->products()->updateExistingPivot($product->id, ['custom_price' => 7500]);
            $clients[1]->products()->updateExistingPivot($product->id, ['custom_price' => 8000]);
        }

        $paymentTypes = PaymentType::cases();

        // Pre-carga productos disponibles por cliente (evita N+1).
        $availableByClient = [];
        foreach ($clients as $client) {
            $availableByClient[$client->id] = $client->products()
                ->wherePivot('is_available', true)
                ->get();
        }

        for ($i = 0; $i < $remissionCount; $i++) {
            $client = $clients->random();
            $seller = $sellers->random();
            $when = Carbon::now()
                ->subDays(rand(0, $daysBack))
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

    /**
     * @return Collection<int, User>
     */
    private function createSellers(): Collection
    {
        $nombres = [
            'Carlos Ramírez', 'Ana Gómez', 'Luis Torres', 'María Fernanda Ruiz',
            'Jorge Castaño', 'Diana Patiño', 'Andrés Mejía', 'Paola Restrepo',
        ];

        return collect($nombres)->map(function (string $nombre, int $i): User {
            $slug = 'vendedor'.($i + 1);
            $user = User::firstOrCreate(
                ['email' => $slug.'@svd.demo'],
                ['name' => $nombre, 'password' => Hash::make('demo1234'), 'email_verified_at' => now()],
            );
            $user->syncRoles(['seller']);

            return $user;
        });
    }

    /**
     * @return Collection<int, Client>
     */
    private function createClients(AttachDefaultProductsAction $attachDefaults): Collection
    {
        $empresas = [
            'Distribuidora La Sabana', 'Comercial El Roble', 'Hielos del Norte',
            'Supermercados Andinos', 'Tienda Don Pepe', 'Abarrotes La Esquina',
            'Restaurante El Fogón', 'Hotel Mirador', 'Bar La Terraza',
            'Heladería Polo Sur', 'Pescadería El Puerto', 'Cafetería Central',
            'Minimercado La 80', 'Eventos y Banquetes SAS', 'Club Social Campestre',
            'Frigorífico La Vega', 'Licorería La Estrella', 'Panadería Trigo de Oro',
        ];

        $puntos = DeliveryPoint::cases();
        $pagos = PaymentType::cases();

        return collect($empresas)->map(function (string $empresa, int $i) use ($attachDefaults, $puntos, $pagos): Client {
            $client = Client::create([
                'name' => $empresa,
                'nit' => '9'.str_pad((string) (10000000 + $i * 137), 8, '0', STR_PAD_LEFT).'-'.($i % 10),
                'manager_name' => 'Encargado '.($i + 1),
                'address' => 'Calle '.rand(1, 120).' # '.rand(1, 80).'-'.rand(1, 99),
                'city' => 'Bogotá',
                'phone' => '60'.rand(1000000, 9999999),
                'whatsapp' => '3'.rand(100000000, 999999999),
                'email' => 'contacto'.($i + 1).'@'.Str::slug($empresa, '').'.demo',
                'delivery_point' => $puntos[$i % count($puntos)]->value,
                'payment_type' => $pagos[$i % count($pagos)]->value,
                'is_active' => true,
            ]);
            $attachDefaults->execute($client);

            return $client;
        });
    }
}
