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

    protected ?string $description = 'Top 6 por unidades despachadas.';

    protected int|string|array $columnSpan = 1;

    protected ?string $maxHeight = '320px';

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
                ->limit(6)
                ->pluck('unidades', 'producto')
                ->all();
        });

        return [
            'datasets' => [
                [
                    'label' => 'Unidades',
                    'data' => array_map('intval', array_values($rows)),
                    'backgroundColor' => 'rgba(14, 165, 233, 0.7)',
                    'borderColor' => 'rgb(14, 165, 233)',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => array_keys($rows),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    /**
     * @return array<string, mixed>
     */
    protected function getOptions(): array
    {
        return [
            'indexAxis' => 'y',
            'plugins' => [
                'legend' => ['display' => false],
            ],
        ];
    }
}
