<?php

namespace App\Http\Requests\Api\V1;

use App\Enums\DeliveryRoute;
use App\Enums\PaymentType;
use App\Enums\RemissionStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRemissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'client_id' => ['required', Rule::exists('clients', 'id')->where('is_active', true)],
            'issued_at' => ['nullable', 'date'],
            'route' => ['required', Rule::enum(DeliveryRoute::class)],
            'payment_type' => ['required', Rule::enum(PaymentType::class)],
            'status' => ['nullable', Rule::enum(RemissionStatus::class)],
            'observations' => ['nullable', 'string', 'max:2000'],
            'gps_lat' => ['nullable', 'numeric', 'between:-90,90'],
            'gps_lng' => ['nullable', 'numeric', 'between:-180,180'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => [
                'required',
                Rule::exists('products', 'id')->where('is_active', true),
            ],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit_price_snapshot' => ['required', 'integer', 'min:0'],
        ];
    }
}
