<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum ProductCategory: string implements HasLabel, HasColor
{
    case Ice = 'ice';
    case Water = 'water';
    case EmptyContainer = 'empty_container';
    case Cooler = 'cooler';
    case Other = 'other';

    public function getLabel(): string
    {
        return match ($this) {
            self::Ice => 'Hielo',
            self::Water => 'Agua',
            self::EmptyContainer => 'Envase Vacío',
            self::Cooler => 'Nevera',
            self::Other => 'Otro',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Ice => 'info',
            self::Water => 'primary',
            self::EmptyContainer => 'gray',
            self::Cooler => 'warning',
            self::Other => 'gray',
        };
    }
}
