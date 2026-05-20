<?php

namespace App\Actions;

use App\Models\Client;
use App\Models\Product;

class AttachDefaultProductsAction
{
    public function execute(Client $client): int
    {
        $defaults = Product::defaultsForNewClients()->pluck('id');

        if ($defaults->isEmpty()) {
            return 0;
        }

        $payload = $defaults->mapWithKeys(fn (int $id) => [
            $id => [
                'custom_price' => null,
                'custom_alias' => null,
                'is_available' => true,
                'notes' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ])->all();

        $client->products()->syncWithoutDetaching($payload);

        return count($payload);
    }
}
