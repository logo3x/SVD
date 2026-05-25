<?php

declare(strict_types=1);

namespace App\Filament\Vendedor\Widgets;

use App\Models\Remission;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Number;

/**
 * Stats compactas en el header de "Mis Remisiones" del panel vendedor.
 * UNA sola query agregada por vendedor + caché 60s.
 */
class MisVentasOverview extends StatsOverviewWidget
{
    protected function getColumns(): int
    {
        return 4;
    }

    protected function getStats(): array
    {
        $userId = (int) Auth::id();
        if ($userId === 0) {
            return [];
        }

        $data = Cache::remember("svd.vendedor.{$userId}.overview", now()->addSeconds(60), function () use ($userId): array {
            $stats = Remission::query()
                ->where('user_id', $userId)
                ->selectRaw('
                    SUM(CASE WHEN DATE(issued_at) = CURDATE() THEN 1 ELSE 0 END) AS today_count,
                    SUM(CASE WHEN DATE(issued_at) = CURDATE() THEN total_amount ELSE 0 END) AS today_sum,
                    SUM(CASE WHEN YEAR(issued_at) = YEAR(CURDATE()) AND MONTH(issued_at) = MONTH(CURDATE()) THEN 1 ELSE 0 END) AS month_count,
                    SUM(CASE WHEN YEAR(issued_at) = YEAR(CURDATE()) AND MONTH(issued_at) = MONTH(CURDATE()) THEN total_amount ELSE 0 END) AS month_sum,
                    SUM(CASE WHEN issued_at >= (CURDATE() - INTERVAL 30 DAY) THEN 1 ELSE 0 END) AS last30_count,
                    SUM(CASE WHEN issued_at >= (CURDATE() - INTERVAL 30 DAY) THEN total_amount ELSE 0 END) AS last30_sum,
                    COUNT(*) AS total_count,
                    SUM(total_amount) AS total_sum
                ')
                ->first();

            return [
                'today_count' => (int) ($stats?->today_count ?? 0),
                'today_sum' => (int) ($stats?->today_sum ?? 0),
                'month_count' => (int) ($stats?->month_count ?? 0),
                'month_sum' => (int) ($stats?->month_sum ?? 0),
                'last30_count' => (int) ($stats?->last30_count ?? 0),
                'last30_sum' => (int) ($stats?->last30_sum ?? 0),
                'total_count' => (int) ($stats?->total_count ?? 0),
                'total_sum' => (int) ($stats?->total_sum ?? 0),
            ];
        });

        return [
            Stat::make('Ventas hoy', Number::currency($data['today_sum'], 'COP'))
                ->description($data['today_count'].' '.($data['today_count'] === 1 ? 'remisión' : 'remisiones'))
                ->descriptionIcon('heroicon-m-sun')
                ->color('success'),

            Stat::make('Ventas este mes', Number::currency($data['month_sum'], 'COP'))
                ->description($data['month_count'].' '.($data['month_count'] === 1 ? 'remisión' : 'remisiones'))
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('primary'),

            Stat::make('Últimos 30 días', Number::currency($data['last30_sum'], 'COP'))
                ->description($data['last30_count'].' '.($data['last30_count'] === 1 ? 'remisión' : 'remisiones'))
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('info'),

            Stat::make('Total acumulado', Number::currency($data['total_sum'], 'COP'))
                ->description($data['total_count'].' '.($data['total_count'] === 1 ? 'remisión histórica' : 'remisiones históricas'))
                ->descriptionIcon('heroicon-m-document-text')
                ->color('gray'),
        ];
    }
}
