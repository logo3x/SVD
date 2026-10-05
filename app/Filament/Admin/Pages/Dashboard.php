<?php

declare(strict_types=1);

namespace App\Filament\Admin\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    /**
     * Grid responsive: 1 columna en móvil, 2 en tablet, 3 en escritorio.
     * Permite alinear las gráficas top tres por fila.
     *
     * @return int|array<string, int|null>
     */
    public function getColumns(): int|array
    {
        return [
            'sm' => 1,
            'md' => 2,
            'xl' => 3,
        ];
    }
}
