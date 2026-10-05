<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Producto resuelto en el contexto de un cliente: incluye override de precio/alias.
 *
 * @mixin \App\Models\Product
 */
class ClientProductResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var \Illuminate\Database\Eloquent\Relations\Pivot|null $pivot */
        $pivot = $this->pivot;
        $custom = $pivot?->custom_price !== null ? (int) $pivot->custom_price : null;

        return [
            'id' => $this->id,
            'sku' => $this->sku,
            'name' => $this->name,
            'description' => $this->description,
            'category' => $this->category?->value,
            'unit' => $this->unit?->value,
            'alias' => $pivot?->custom_alias,
            'default_price' => (int) $this->default_price,
            'custom_price' => $custom,
            'effective_price' => $custom ?? (int) $this->default_price,
            'is_available' => (bool) ($pivot?->is_available ?? true),
        ];
    }
}
