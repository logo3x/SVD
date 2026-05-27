<?php

declare(strict_types=1);

namespace App\Filament\Admin\Widgets;

use App\Models\Remission;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class TopClientesChart extends ChartWidget
{
    protected ?string $heading = 'Empresas que más compran (90 días)';

    protected ?string $description = 'Top 8 clientes por monto total facturado.';

    protected int|string|array $columnSpan = 1;

    protected static bool $isLazy = true;

    protected function getData(): array
    {
        $rows = Cache::remember('svd.dashboard.top-clientes', now()->addSeconds(300), function (): array {
            return Remission::query()
                ->where('remissions.issued_at', '>=', Carbon::now()->subDays(90))
                ->join('clients', 'clients.id', '=', 'remissions.client_id')
                ->selectRaw('clients.name AS cliente, SUM(remissions.total_amount) AS total')
                ->groupBy('clients.id', 'clients.name')
                ->orderByDesc('total')
                ->limit(8)
                ->pluck('total', 'cliente')
                ->all();
        });

        return [
            'datasets' => [
                [
                    'label' => 'Compras (COP)',
                    'data' => array_map('intval', array_values($rows)),
                    'backgroundColor' => 'rgba(16, 185, 129, 0.7)',
                    'borderColor' => 'rgb(16, 185, 129)',
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
