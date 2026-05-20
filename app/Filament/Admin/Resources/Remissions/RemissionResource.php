<?php

namespace App\Filament\Admin\Resources\Remissions;

use App\Filament\Admin\Resources\Remissions\Pages\CreateRemission;
use App\Filament\Admin\Resources\Remissions\Pages\EditRemission;
use App\Filament\Admin\Resources\Remissions\Pages\ListRemissions;
use App\Filament\Admin\Resources\Remissions\Pages\ViewRemission;
use App\Filament\Admin\Resources\Remissions\Schemas\RemissionForm;
use App\Filament\Admin\Resources\Remissions\Schemas\RemissionInfolist;
use App\Filament\Admin\Resources\Remissions\Tables\RemissionsTable;
use App\Models\Remission;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RemissionResource extends Resource
{
    protected static ?string $model = Remission::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static ?string $navigationLabel = 'Remisiones';

    protected static ?string $modelLabel = 'Remisión';

    protected static ?string $pluralModelLabel = 'Remisiones';

    protected static string|\UnitEnum|null $navigationGroup = 'Ventas';

    protected static ?int $navigationSort = 5;

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

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRemissions::route('/'),
            'create' => CreateRemission::route('/create'),
            'view' => ViewRemission::route('/{record}'),
            'edit' => EditRemission::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
