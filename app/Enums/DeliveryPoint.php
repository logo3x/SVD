<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum DeliveryPoint: string implements HasLabel
{
    case External = 'external';
    case Warehouse = 'warehouse';
    case Impala = 'impala';
    case Refinery = 'refinery';
    case TravelerRoute = 'traveler_route';
    case Other = 'other';

    public function getLabel(): string
    {
        return match ($this) {
            self::External => 'Externo',
            self::Warehouse => 'Bodega',
            self::Impala => 'Impala',
            self::Refinery => 'Refinería',
            self::TravelerRoute => 'Ruta Viajera',
            self::Other => 'Otro',
        };
    }
}
