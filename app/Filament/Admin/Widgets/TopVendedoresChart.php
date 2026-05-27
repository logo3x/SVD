<?php

declare(strict_types=1);

namespace App\Filament\Admin\Widgets;

use App\Models\Remission;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class TopVendedoresChart extends ChartWidget
{
    protected ?string $heading = 'Vendedores con más ventas (90 días)';

    protected ?string $description = 'Top 6 por monto total vendido.';

    protected int|string|array $columnSpan = 1;

    protected ?string $maxHeight = '320px';

    protected static bool $isLazy = true;

    protected function getData(): array
    {
        $rows = Cache::remember('svd.dashboard.top-vendedores', now()->addSeconds(300), function (): array {
            return Remission::query()
                ->where('remissions.issued_at', '>=', Carbon::now()->subDays(90))
                ->join('users', 'users.id', '=', 'remissions.user_id')
                ->selectRaw('users.name AS vendedor, SUM(remissions.total_amount) AS total')
                ->groupBy('users.id', 'users.name')
                ->orderByDesc('total')
                ->limit(6)
                ->pluck('total', 'vendedor')
                ->all();
        });

        return [
            'datasets' => [
                [
                    'label' => 'Ventas (COP)',
                    'data' => array_map('intval', array_values($rows)),
                    'backgroundColor' => 'rgba(99, 102, 241, 0.7)',
                    'borderColor' => 'rgb(99, 102, 241)',
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
     * Barras horizontales: los nombres de vendedor se leen mejor.
     *
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
