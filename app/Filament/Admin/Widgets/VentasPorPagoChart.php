<?php

declare(strict_types=1);

namespace App\Filament\Admin\Widgets;

use App\Enums\PaymentType;
use App\Models\Remission;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class VentasPorPagoChart extends ChartWidget
{
    protected ?string $heading = 'Ventas por tipo de pago (90 días)';

    protected ?string $description = 'Distribución del monto vendido según forma de pago.';

    protected int|string|array $columnSpan = 1;

    protected static bool $isLazy = true;

    protected function getData(): array
    {
        $rows = Cache::remember('svd.dashboard.ventas-por-pago', now()->addSeconds(300), function (): array {
            return Remission::query()
                ->where('issued_at', '>=', Carbon::now()->subDays(90))
                ->selectRaw('payment_type, SUM(total_amount) AS total')
                ->groupBy('payment_type')
                ->pluck('total', 'payment_type')
                ->all();
        });

        $labels = [];
        $data = [];
        foreach ($rows as $value => $total) {
            $labels[] = PaymentType::tryFrom((string) $value)?->getLabel() ?? (string) $value;
            $data[] = (int) $total;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Ventas (COP)',
                    'data' => $data,
                    'backgroundColor' => [
                        'rgba(16, 185, 129, 0.8)', 'rgba(14, 165, 233, 0.8)',
                        'rgba(245, 158, 11, 0.8)', 'rgba(168, 85, 247, 0.8)',
                        'rgba(107, 114, 128, 0.8)',
                    ],
                ],
            ],
            'labels' => $labels,
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
