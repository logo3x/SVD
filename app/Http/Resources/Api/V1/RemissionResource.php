<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Remission */
class RemissionResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $signature = $this->getFirstMedia('signature');

        return [
            'id' => $this->id,
            'issued_at' => $this->issued_at?->toIso8601String(),
            'status' => $this->status?->value,
            'route' => [
                'value' => $this->route?->value,
                'label' => $this->route?->getLabel(),
            ],
            'payment_type' => [
                'value' => $this->payment_type?->value,
                'label' => $this->payment_type?->getLabel(),
            ],
            'observations' => $this->observations,
            'gps_location' => $this->gps_location,
            'total_amount' => (int) $this->total_amount,
            'client' => $this->whenLoaded('client', fn () => [
                'id' => $this->client->id,
                'name' => $this->client->name,
                'nit' => $this->client->nit,
            ]),
            'user' => $this->whenLoaded('user', fn () => [
                'id' => $this->user?->id,
                'name' => $this->user?->name,
            ]),
            'items' => $this->whenLoaded('products', fn () => $this->products->map(fn ($product) => [
                'product_id' => $product->id,
                'sku' => $product->sku,
                'name' => $product->name,
                'quantity' => (int) $product->pivot->quantity,
                'unit_price_snapshot' => (int) $product->pivot->unit_price_snapshot,
                'subtotal' => (int) $product->pivot->subtotal,
            ])->all()),
            'has_signature' => $signature !== null,
        ];
    }
}
