<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CatalogController;
use App\Http\Controllers\Api\V1\ClientController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\RemissionController;
use Illuminate\Support\Facades\Route;

// 6 intentos/min/IP+email para mitigar fuerza bruta sobre el login.
Route::middleware('throttle:api-login')
    ->post('login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum', 'throttle:api'])->group(function (): void {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);

    Route::get('payment-types', [CatalogController::class, 'paymentTypes']);
    Route::get('routes', [CatalogController::class, 'routes']);

    Route::get('clients', [ClientController::class, 'index']);
    Route::get('clients/{client}', [ClientController::class, 'show']);
    Route::get('clients/{client}/products', [ProductController::class, 'byClient']);

    Route::get('remissions', [RemissionController::class, 'index']);
    // Crear remisiones tiene un throttle más estricto que el resto.
    Route::middleware('throttle:api-write')
        ->post('remissions', [RemissionController::class, 'store']);
    Route::get('remissions/{remission}', [RemissionController::class, 'show']);
    Route::middleware('throttle:api-write')
        ->post('remissions/{remission}/signature', [RemissionController::class, 'signature']);
});
