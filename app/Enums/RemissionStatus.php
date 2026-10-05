<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum RemissionStatus: string implements HasColor, HasLabel
{
    case Draft = 'draft';
    case Confirmed = 'confirmed';
    case Delivered = 'delivered';
    case Cancelled = 'cancelled';

    public function getLabel(): string
    {
        return match ($this) {
            self::Draft => 'Borrador',
            self::Confirmed => 'Confirmada',
            self::Delivered => 'Entregada',
            self::Cancelled => 'Anulada',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Draft => 'gray',
            self::Confirmed => 'info',
            self::Delivered => 'success',
            self::Cancelled => 'danger',
        };
    }
}
