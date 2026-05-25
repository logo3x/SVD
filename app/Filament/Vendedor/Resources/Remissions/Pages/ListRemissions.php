<?php

declare(strict_types=1);

namespace App\Filament\Vendedor\Resources\Remissions\Pages;

use App\Filament\Vendedor\Resources\Remissions\RemissionResource;
use App\Filament\Vendedor\Widgets\MisVentasOverview;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRemissions extends ListRecords
{
    protected static string $resource = RemissionResource::class;

    public function getTitle(): string
    {
        return 'Mis Remisiones';
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Nueva Remisión')
                ->icon('heroicon-o-plus'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            MisVentasOverview::class,
        ];
    }
}
