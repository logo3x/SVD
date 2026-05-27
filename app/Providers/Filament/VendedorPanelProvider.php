<?php

namespace App\Providers\Filament;

use App\Filament\Auth\Login as SvdLogin;
use App\Filament\Vendedor\Resources\Remissions\RemissionResource;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class VendedorPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('vendedor')
            ->path('vendedor')
            ->brandName('SVD · Vendedor')
            ->spa()
            ->login(SvdLogin::class)
            ->passwordReset()
            ->profile()
            // Al iniciar sesión se va directo al listado "Mis Remisiones".
            ->homeUrl(fn () => RemissionResource::getUrl('index'))
            ->colors([
                'primary' => Color::Emerald,
            ])
            ->discoverResources(in: app_path('Filament/Vendedor/Resources'), for: 'App\Filament\Vendedor\Resources')
            ->discoverPages(in: app_path('Filament/Vendedor/Pages'), for: 'App\Filament\Vendedor\Pages')
            ->discoverWidgets(in: app_path('Filament/Vendedor/Widgets'), for: 'App\Filament\Vendedor\Widgets')
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
