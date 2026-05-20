<?php

namespace App\Filament\Vendedor\Resources\Remissions\Pages;

use App\Filament\Vendedor\Resources\Remissions\RemissionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRemissions extends ListRecords
{
    protected static string $resource = RemissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
