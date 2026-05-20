<?php

namespace App\Filament\Admin\Resources\Remissions\Pages;

use App\Filament\Admin\Resources\Remissions\RemissionResource;
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
