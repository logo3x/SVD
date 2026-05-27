<?php

declare(strict_types=1);

namespace App\Filament\Admin\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    /**
     * Grid de 3 columnas: permite alinear las gráficas top tres por fila.
     * Responsive: 1 columna en móvil, 2 en tablet, 3 en escritorio.
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
