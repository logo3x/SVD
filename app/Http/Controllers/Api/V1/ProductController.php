<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\ClientProductResource;
use App\Models\Client;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    /**
     * Devuelve los productos disponibles para un cliente con precios resueltos.
     */
    public function byClient(Client $client): AnonymousResourceCollection
    {
        abort_unless($client->is_active, 404);

        $products = $client->products()
            ->wherePivot('is_available', true)
            ->where('products.is_active', true)
            ->orderBy('products.name')
            ->get();

        return ClientProductResource::collection($products);
    }
}
