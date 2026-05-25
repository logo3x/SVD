<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\Admin\MobileDeviceController;
use App\Http\Controllers\Api\V1\Admin\MobileSettingsController;
use App\Http\Controllers\Api\V1\Admin\UserController as AdminUserController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CatalogController;
use App\Http\Controllers\Api\V1\ClientController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\RemissionController;
use App\Http\Middleware\EnforceMobileSettings;
use Illuminate\Support\Facades\Route;

// Todas las requests pasan por EnforceMobileSettings:
//  - 503 si maintenance_mode está activo
//  - 426 si X-App-Version < min_app_version
//  - Inyecta X-SVD-Announcement en la respuesta si hay anuncio activo
Route::middleware(EnforceMobileSettings::class)->group(function (): void {

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
        // Export XLSX de remisiones (scoped al vendedor del token).
        Route::get('remissions/export', [RemissionController::class, 'export']);
        // Crear remisiones tiene un throttle más estricto que el resto.
        Route::middleware('throttle:api-write')
            ->post('remissions', [RemissionController::class, 'store']);
        Route::get('remissions/{remission}', [RemissionController::class, 'show']);
        // Descarga PDF del comprobante (binario application/pdf).
        Route::get('remissions/{remission}/pdf', [RemissionController::class, 'pdf']);
        Route::middleware('throttle:api-write')
            ->post('remissions/{remission}/signature', [RemissionController::class, 'signature']);

        // Endpoints administrativos — requieren rol admin o super_admin.
        // 403 con {"message":"Acceso restringido a administradores"} si no aplica.
        Route::middleware('admin')->prefix('admin')->name('admin.')->group(function (): void {
            // P1.A — Gestión de dispositivos móviles (PersonalAccessTokens)
            Route::get('mobile-devices', [MobileDeviceController::class, 'index']);
            Route::post('mobile-devices/revoke-all', [MobileDeviceController::class, 'revokeAll']);
            Route::delete('mobile-devices/{mobileDevice}', [MobileDeviceController::class, 'destroy']);

            // P1.B — Mobile settings
            Route::get('mobile-settings', [MobileSettingsController::class, 'show']);
            Route::put('mobile-settings', [MobileSettingsController::class, 'update']);

            // P2 — Vendedores con stats
            Route::get('users', [AdminUserController::class, 'index']);
        });
    });
});
