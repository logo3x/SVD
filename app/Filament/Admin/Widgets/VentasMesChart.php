<?php

declare(strict_types=1);

namespace App\Filament\Admin\Widgets;

use App\Models\Remission;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class VentasMesChart extends ChartWidget
{
    protected ?string $heading = 'Ventas últimos 30 días';

    protected int|string|array $columnSpan = 'full';

    /**
     * Lazy load: el chart NO bloquea el primer render del dashboard,
     * llega después via Livewire. Mejora drásticamente el TTFB inicial.
     */
    protected static bool $isLazy = true;

    protected function getData(): array
    {
        $rows = Cache::remember('svd.dashboard.chart.30d', now()->addSeconds(120), function (): array {
            $start = Carbon::now()->subDays(29)->startOfDay();

            return Remission::query()
                ->where('issued_at', '>=', $start)
                ->selectRaw('DATE(issued_at) AS day, SUM(total_amount) AS total')
                ->groupBy('day')
                ->pluck('total', 'day')
                ->all();
        });

        $start = Carbon::now()->subDays(29)->startOfDay();
        $labels = [];
        $data = [];

        for ($i = 0; $i < 30; $i++) {
            $date = $start->copy()->addDays($i);
            $labels[] = $date->format('d/m');
            $data[] = (int) ($rows[$date->format('Y-m-d')] ?? 0);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Ventas (COP)',
                    'data' => $data,
                    'borderColor' => 'rgb(14, 165, 233)',
                    'backgroundColor' => 'rgba(14, 165, 233, 0.15)',
                    'fill' => true,
                    'tension' => 0.3,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
