<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ProductUnit: string implements HasLabel
{
    case Unit = 'unit';
    case Pack = 'pack';
    case Box = 'box';
    case Bag = 'bag';

    public function getLabel(): string
    {
        return match ($this) {
            self::Unit => 'Unidad',
            self::Pack => 'Paquete',
            self::Box => 'Caja',
            self::Bag => 'Bolsa',
        };
    }
}
