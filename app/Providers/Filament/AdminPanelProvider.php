<?php

namespace App\Providers\Filament;

use App\Filament\Admin\Pages\Dashboard;
use App\Filament\Admin\Widgets\AdminWelcome;
use App\Filament\Admin\Widgets\TopClientesChart;
use App\Filament\Admin\Widgets\TopProductosChart;
use App\Filament\Admin\Widgets\TopVendedoresChart;
use App\Filament\Admin\Widgets\UltimasRemisionesTable;
use App\Filament\Admin\Widgets\VentasMesChart;
use App\Filament\Admin\Widgets\VentasOverview;
use App\Filament\Admin\Widgets\VentasPorPagoChart;
use App\Filament\Admin\Widgets\VentasPorRutaChart;
use App\Filament\Auth\Login as SvdLogin;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
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

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('admin')
            ->path('admin')
            ->brandName('SVD · Admin')
            ->spa()
            ->login(SvdLogin::class)
            ->passwordReset()
            ->profile()
            ->colors([
                'primary' => Color::Sky,
            ])
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\Filament\Admin\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\Filament\Admin\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'App\Filament\Admin\Widgets')
            ->widgets([
                AdminWelcome::class,
                VentasOverview::class,
                VentasMesChart::class,
                TopVendedoresChart::class,
                TopClientesChart::class,
                TopProductosChart::class,
                VentasPorPagoChart::class,
                VentasPorRutaChart::class,
                UltimasRemisionesTable::class,
            ])
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
            ->plugins([
                FilamentShieldPlugin::make()
                    ->navigationGroup('Configuración')
                    ->navigationSort(99),
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
