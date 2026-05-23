<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\MobileDevices\Pages;

use App\Filament\Admin\Resources\MobileDevices\MobileDeviceResource;
use Filament\Resources\Pages\ListRecords;

class ListMobileDevices extends ListRecords
{
    protected static string $resource = MobileDeviceResource::class;

    protected function getHeaderActions(): array
    {
        // El "Revocar todos" lo añade la tabla via headerActions().
        return [];
    }
}
