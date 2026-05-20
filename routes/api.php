<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CatalogController;
use App\Http\Controllers\Api\V1\ClientController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\RemissionController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function (): void {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);

    Route::get('payment-types', [CatalogController::class, 'paymentTypes']);
    Route::get('routes', [CatalogController::class, 'routes']);

    Route::get('clients', [ClientController::class, 'index']);
    Route::get('clients/{client}', [ClientController::class, 'show']);
    Route::get('clients/{client}/products', [ProductController::class, 'byClient']);

    Route::get('remissions', [RemissionController::class, 'index']);
    Route::post('remissions', [RemissionController::class, 'store']);
    Route::get('remissions/{remission}', [RemissionController::class, 'show']);
    Route::post('remissions/{remission}/signature', [RemissionController::class, 'signature']);
});
