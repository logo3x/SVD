<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Client */
class ClientResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'nit' => $this->nit,
            'manager_name' => $this->manager_name,
            'whatsapp' => $this->whatsapp,
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
            'city' => $this->city,
            'delivery_point' => $this->delivery_point?->value,
            'payment_type' => $this->payment_type?->value,
            'is_active' => $this->is_active,
        ];
    }
}
