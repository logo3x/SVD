<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\MobileDevices;

use App\Filament\Admin\Resources\MobileDevices\Pages\ListMobileDevices;
use App\Filament\Admin\Resources\MobileDevices\Tables\MobileDevicesTable;
use App\Models\MobileDevice;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MobileDeviceResource extends Resource
{
    protected static ?string $model = MobileDevice::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDevicePhoneMobile;

    protected static ?string $navigationLabel = 'Dispositivos móviles';

    protected static ?string $modelLabel = 'Dispositivo';

    protected static ?string $pluralModelLabel = 'Dispositivos';

    protected static string|\UnitEnum|null $navigationGroup = 'App móvil';

    protected static ?int $navigationSort = 60;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('tokenable_type', User::class)
            ->with('tokenable:id,name,email');
    }

    public static function table(Table $table): Table
    {
        return MobileDevicesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMobileDevices::route('/'),
        ];
    }

    /** No se crean dispositivos desde el panel — sólo se revocan. */
    public static function canCreate(): bool
    {
        return false;
    }
}
