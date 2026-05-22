<?php

declare(strict_types=1);

namespace App\Filament\Admin\Widgets;

use App\Models\Client;
use App\Models\Remission;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Number;

class VentasOverview extends StatsOverviewWidget
{
    protected ?string $heading = 'Resumen';

    protected function getStats(): array
    {
        $data = Cache::remember('svd.dashboard.overview', now()->addSeconds(60), function (): array {
            // Una sola query agregada: count + sum por bucket (hoy + mes).
            $stats = Remission::query()
                ->selectRaw(
                    '
                    SUM(CASE WHEN DATE(issued_at) = CURDATE() THEN 1 ELSE 0 END) AS today_count,
                    SUM(CASE WHEN DATE(issued_at) = CURDATE() THEN total_amount ELSE 0 END) AS today_sum,
                    SUM(CASE WHEN YEAR(issued_at) = YEAR(CURDATE()) AND MONTH(issued_at) = MONTH(CURDATE()) THEN 1 ELSE 0 END) AS month_count,
                    SUM(CASE WHEN YEAR(issued_at) = YEAR(CURDATE()) AND MONTH(issued_at) = MONTH(CURDATE()) THEN total_amount ELSE 0 END) AS month_sum
                    '
                )
                ->first();

            $clients = Client::query()
                ->selectRaw('COUNT(*) AS total, SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) AS active')
                ->first();

            $sellers = User::query()
                ->whereHas('roles', fn ($q) => $q->whereIn('name', ['seller', 'super_admin']))
                ->count();

            return [
                'today_sum' => (int) ($stats?->today_sum ?? 0),
                'today_count' => (int) ($stats?->today_count ?? 0),
                'month_sum' => (int) ($stats?->month_sum ?? 0),
                'month_count' => (int) ($stats?->month_count ?? 0),
                'clients_active' => (int) ($clients?->active ?? 0),
                'clients_total' => (int) ($clients?->total ?? 0),
                'sellers' => $sellers,
            ];
        });

        return [
            Stat::make('Ventas hoy', Number::currency($data['today_sum'], 'COP'))
                ->description($data['today_count'].' remisiones')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('success'),
            Stat::make('Ventas del mes', Number::currency($data['month_sum'], 'COP'))
                ->description($data['month_count'].' remisiones')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('info'),
            Stat::make('Clientes activos', $data['clients_active'])
                ->description('Total registrados: '.$data['clients_total'])
                ->descriptionIcon('heroicon-m-building-office-2')
                ->color('primary'),
            Stat::make('Vendedores', $data['sellers'])
                ->description('Usuarios con acceso a ventas')
                ->descriptionIcon('heroicon-m-users')
                ->color('gray'),
        ];
    }
}
