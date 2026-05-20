<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Client;
use App\Models\Remission;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class VentasOverview extends StatsOverviewWidget
{
    protected ?string $heading = 'Resumen';

    protected function getStats(): array
    {
        $today = Remission::query()->whereDate('issued_at', today());
        $month = Remission::query()->whereYear('issued_at', now()->year)->whereMonth('issued_at', now()->month);

        return [
            Stat::make('Ventas hoy', Number::currency((int) $today->sum('total_amount'), 'COP'))
                ->description($today->count().' remisiones')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('success'),
            Stat::make('Ventas del mes', Number::currency((int) $month->sum('total_amount'), 'COP'))
                ->description($month->count().' remisiones')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('info'),
            Stat::make('Clientes activos', Client::where('is_active', true)->count())
                ->description('Total registrados: '.Client::count())
                ->descriptionIcon('heroicon-m-building-office-2')
                ->color('primary'),
            Stat::make('Vendedores', User::role('seller')->count() + User::role('super_admin')->count())
                ->description('Usuarios con acceso a ventas')
                ->descriptionIcon('heroicon-m-users')
                ->color('gray'),
        ];
    }
}
