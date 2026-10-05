<?php

namespace App\Filament\Admin\Resources\EmployeeProfiles\Pages;

use App\Filament\Admin\Resources\EmployeeProfiles\EmployeeProfileResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEmployeeProfile extends CreateRecord
{
    protected static string $resource = EmployeeProfileResource::class;
}
