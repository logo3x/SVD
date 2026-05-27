<?php

declare(strict_types=1);

namespace App\Filament\Admin\Widgets;

use App\Enums\DeliveryRoute;
use App\Models\Remission;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class VentasPorRutaChart extends ChartWidget
{
    protected ?string $heading = 'Ventas por ruta de entrega (90 días)';

    protected ?string $description = 'Monto total despachado en cada ruta.';

    protected int|string|array $columnSpan = 1;

    protected static bool $isLazy = true;

    protected function getData(): array
    {
        $rows = Cache::remember('svd.dashboard.ventas-por-ruta', now()->addSeconds(300), function (): array {
            return Remission::query()
                ->where('issued_at', '>=', Carbon::now()->subDays(90))
                ->selectRaw('route, SUM(total_amount) AS total')
                ->groupBy('route')
                ->orderByDesc('total')
                ->pluck('total', 'route')
                ->all();
        });

        $labels = [];
        $data = [];
        foreach ($rows as $value => $total) {
            $labels[] = DeliveryRoute::tryFrom((string) $value)?->getLabel() ?? (string) $value;
            $data[] = (int) $total;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Ventas (COP)',
                    'data' => $data,
                    'backgroundColor' => 'rgba(245, 158, 11, 0.7)',
                    'borderColor' => 'rgb(245, 158, 11)',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $labels,
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
            'plugins' => [
                'legend' => ['display' => false],
            ],
        ];
    }
}
