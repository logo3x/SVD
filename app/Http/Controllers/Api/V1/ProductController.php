<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\ClientProductResource;
use App\Http\Resources\Api\V1\ProductResource;
use App\Models\Client;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    /**
     * Lista el catálogo maestro de productos (sin contexto de cliente).
     * Soporta ?is_active=1 y ?category=ice|water|... para filtrar.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $products = Product::query()
            ->when($request->boolean('is_active'), fn ($q) => $q->where('is_active', true))
            ->when($request->string('category')->toString(), fn ($q, $v) => $q->where('category', $v))
            ->orderBy('name')
            ->get();

        return ProductResource::collection($products);
    }

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
