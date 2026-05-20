<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\DeliveryRoute;
use App\Enums\PaymentType;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class CatalogController extends Controller
{
    public function paymentTypes(): JsonResponse
    {
        $data = collect(PaymentType::cases())
            ->map(fn (PaymentType $type) => [
                'value' => $type->value,
                'label' => $type->getLabel(),
            ])
            ->all();

        return response()->json(['data' => $data]);
    }

    public function routes(): JsonResponse
    {
        $data = collect(DeliveryRoute::cases())
            ->map(fn (DeliveryRoute $route) => [
                'value' => $route->value,
                'label' => $route->getLabel(),
            ])
            ->all();

        return response()->json(['data' => $data]);
    }
}
