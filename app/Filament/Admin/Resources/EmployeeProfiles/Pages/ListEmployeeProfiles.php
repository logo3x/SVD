<?php

namespace App\Filament\Admin\Resources\EmployeeProfiles\Pages;

use App\Filament\Admin\Resources\EmployeeProfiles\EmployeeProfileResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEmployeeProfiles extends ListRecords
{
    protected static string $resource = EmployeeProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
