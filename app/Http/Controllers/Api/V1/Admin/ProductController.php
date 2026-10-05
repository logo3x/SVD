<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Enums\ProductCategory;
use App\Enums\ProductUnit;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\ProductResource;
use App\Models\Client;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function store(Request $request): ProductResource
    {
        $data = $request->validate([
            'sku' => ['required', 'string', 'max:64', 'unique:products,sku'],
            'name' => ['required', 'string', 'max:128'],
            'description' => ['nullable', 'string', 'max:500'],
            'category' => ['required', Rule::enum(ProductCategory::class)],
            'unit' => ['required', Rule::enum(ProductUnit::class)],
            'default_price' => ['required', 'integer', 'min:0'],
            'is_default_for_new_clients' => ['sometimes', 'boolean'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $product = Product::create($data);

        return ProductResource::make($product);
    }

    public function update(Request $request, Product $product): ProductResource
    {
        $data = $request->validate([
            'sku' => ['sometimes', 'string', 'max:64', Rule::unique('products', 'sku')->ignore($product->id)],
            'name' => ['sometimes', 'string', 'max:128'],
            'description' => ['sometimes', 'nullable', 'string', 'max:500'],
            'category' => ['sometimes', Rule::enum(ProductCategory::class)],
            'unit' => ['sometimes', Rule::enum(ProductUnit::class)],
            'default_price' => ['sometimes', 'integer', 'min:0'],
            'is_default_for_new_clients' => ['sometimes', 'boolean'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $product->update($data);

        return ProductResource::make($product->fresh());
    }

    public function destroy(Product $product): Response
    {
        $product->update(['is_active' => false]);
        $product->delete();

        return response()->noContent();
    }

    /**
     * Actualiza el override de un producto para un cliente específico
     * (pivot client_product).
     */
    public function updateClientPivot(Request $request, Client $client, Product $product): JsonResponse
    {
        $data = $request->validate([
            'custom_price' => ['sometimes', 'nullable', 'integer', 'min:0'],
            'custom_alias' => ['sometimes', 'nullable', 'string', 'max:128'],
            'is_available' => ['sometimes', 'boolean'],
            'notes' => ['sometimes', 'nullable', 'string'],
        ]);

        if (! $client->products()->where('products.id', $product->id)->exists()) {
            $client->products()->attach($product->id, $data + ['is_available' => true]);
        } else {
            $client->products()->updateExistingPivot($product->id, $data);
        }

        $fresh = $client->products()->where('products.id', $product->id)->first();

        return response()->json([
            'data' => [
                'client_id' => $client->id,
                'product_id' => $product->id,
                'custom_price' => $fresh->pivot->custom_price,
                'custom_alias' => $fresh->pivot->custom_alias,
                'is_available' => (bool) $fresh->pivot->is_available,
                'notes' => $fresh->pivot->notes,
                'effective_price' => $fresh->pivot->custom_price ?? $product->default_price,
            ],
        ]);
    }
}
