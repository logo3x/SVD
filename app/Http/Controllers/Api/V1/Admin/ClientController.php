<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Actions\AttachDefaultProductsAction;
use App\Enums\DeliveryPoint;
use App\Enums\PaymentType;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\ClientResource;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
    public function store(Request $request, AttachDefaultProductsAction $attachDefaults): ClientResource
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:128'],
            'nit' => ['required', 'string', 'max:64'],
            'manager_name' => ['nullable', 'string', 'max:128'],
            'whatsapp' => ['required', 'string', 'max:32'],
            'phone' => ['nullable', 'string', 'max:32'],
            'email' => ['required', 'email', 'max:128'],
            'delivery_point' => ['required', Rule::enum(DeliveryPoint::class)],
            'payment_type' => ['required', Rule::enum(PaymentType::class)],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:128'],
            'description' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        $client = DB::transaction(function () use ($data, $attachDefaults) {
            $client = Client::create($data);
            $attachDefaults->execute($client);

            return $client;
        });

        return ClientResource::make($client->load('products'));
    }

    public function update(Request $request, Client $client): ClientResource
    {
        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:128'],
            'nit' => ['sometimes', 'string', 'max:64'],
            'manager_name' => ['sometimes', 'nullable', 'string', 'max:128'],
            'whatsapp' => ['sometimes', 'string', 'max:32'],
            'phone' => ['sometimes', 'nullable', 'string', 'max:32'],
            'email' => ['sometimes', 'email', 'max:128'],
            'delivery_point' => ['sometimes', Rule::enum(DeliveryPoint::class)],
            'payment_type' => ['sometimes', Rule::enum(PaymentType::class)],
            'address' => ['sometimes', 'nullable', 'string', 'max:255'],
            'city' => ['sometimes', 'nullable', 'string', 'max:128'],
            'description' => ['sometimes', 'nullable', 'string', 'max:255'],
            'notes' => ['sometimes', 'nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $client->update($data);

        return ClientResource::make($client->fresh()->load('products'));
    }

    public function destroy(Client $client): Response
    {
        $client->update(['is_active' => false]);
        $client->delete();

        return response()->noContent();
    }
}
