<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum DeliveryRoute: string implements HasLabel
{
    case Route01 = 'route_01';
    case Route02 = 'route_02';
    case Route03 = 'route_03';
    case Route04 = 'route_04';
    case Route05 = 'route_05';
    case Route06 = 'route_06';
    case Route07 = 'route_07';
    case Route08 = 'route_08';
    case Route09 = 'route_09';
    case Route10 = 'route_10';
    case Route11 = 'route_11';
    case Route12 = 'route_12';
    case Route13 = 'route_13';
    case Route14 = 'route_14';
    case Route15 = 'route_15';
    case Route16 = 'route_16';

    public function getLabel(): string
    {
        return 'Ruta '.((int) substr($this->value, 6));
    }
}
