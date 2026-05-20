<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum PaymentType: string implements HasLabel, HasColor
{
    case Cash = 'cash';
    case CashForBilling = 'cash_for_billing';
    case Credit = 'credit';
    case Gift = 'gift';
    case Other = 'other';

    public function getLabel(): string
    {
        return match ($this) {
            self::Cash => 'Contado',
            self::CashForBilling => 'Contado para Facturar',
            self::Credit => 'Crédito',
            self::Gift => 'Obsequio',
            self::Other => 'Otro',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Cash => 'success',
            self::CashForBilling => 'info',
            self::Credit => 'warning',
            self::Gift => 'gray',
            self::Other => 'gray',
        };
    }
}
