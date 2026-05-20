<?php

declare(strict_types=1);

namespace App\Filament\Vendedor\Resources\Remissions;

use App\Filament\Admin\Resources\Remissions\Schemas\RemissionForm;
use App\Filament\Admin\Resources\Remissions\Schemas\RemissionInfolist;
use App\Filament\Vendedor\Resources\Remissions\Pages\CreateRemission;
use App\Filament\Vendedor\Resources\Remissions\Pages\ListRemissions;
use App\Filament\Vendedor\Resources\Remissions\Pages\ViewRemission;
use App\Filament\Vendedor\Resources\Remissions\Tables\RemissionsTable;
use App\Models\Remission;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class RemissionResource extends Resource
{
    protected static ?string $model = Remission::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static ?string $navigationLabel = 'Mis Remisiones';

    protected static ?string $modelLabel = 'Remisión';

    protected static ?string $pluralModelLabel = 'Remisiones';

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return RemissionForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return RemissionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RemissionsTable::configure($table);
    }

    /**
     * Restringe el listado a las remisiones del vendedor logueado.
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', Auth::id());
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRemissions::route('/'),
            'create' => CreateRemission::route('/create'),
            'view' => ViewRemission::route('/{record}'),
        ];
    }
}
