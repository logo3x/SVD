<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Remission;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class VentasMesChart extends ChartWidget
{
    protected ?string $heading = 'Ventas últimos 30 días';

    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $start = Carbon::now()->subDays(29)->startOfDay();
        $rows = Remission::query()
            ->where('issued_at', '>=', $start)
            ->selectRaw('DATE(issued_at) AS day, SUM(total_amount) AS total')
            ->groupBy('day')
            ->pluck('total', 'day');

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
