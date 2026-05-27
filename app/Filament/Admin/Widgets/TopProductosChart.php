<?php

declare(strict_types=1);

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class TopProductosChart extends ChartWidget
{
    protected ?string $heading = 'Productos más vendidos (90 días)';

    protected ?string $description = 'Top 8 por unidades despachadas.';

    protected int|string|array $columnSpan = 'full';

    protected static bool $isLazy = true;

    protected function getData(): array
    {
        $rows = Cache::remember('svd.dashboard.top-productos', now()->addSeconds(300), function (): array {
            return DB::table('remission_product')
                ->join('remissions', 'remissions.id', '=', 'remission_product.remission_id')
                ->join('products', 'products.id', '=', 'remission_product.product_id')
                ->where('remissions.issued_at', '>=', Carbon::now()->subDays(90))
                ->selectRaw('products.name AS producto, SUM(remission_product.quantity) AS unidades')
                ->groupBy('products.id', 'products.name')
                ->orderByDesc('unidades')
                ->limit(8)
                ->pluck('unidades', 'producto')
                ->all();
        });

        $palette = [
            'rgba(14, 165, 233, 0.8)', 'rgba(16, 185, 129, 0.8)', 'rgba(99, 102, 241, 0.8)',
            'rgba(245, 158, 11, 0.8)', 'rgba(239, 68, 68, 0.8)', 'rgba(168, 85, 247, 0.8)',
            'rgba(236, 72, 153, 0.8)', 'rgba(20, 184, 166, 0.8)',
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Unidades',
                    'data' => array_map('intval', array_values($rows)),
                    'backgroundColor' => array_slice($palette, 0, count($rows)),
                ],
            ],
            'labels' => array_keys($rows),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    /**
     * @return array<string, mixed>
     */
    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => ['position' => 'right'],
            ],
        ];
    }
}
