<?php

namespace App\Filament\Admin\Resources\Clients\RelationManagers;

use App\Models\Product;
use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'products';

    protected static ?string $title = 'Productos del Cliente';

    protected static ?string $modelLabel = 'producto';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('custom_price')
                    ->label('Precio especial (override)')
                    ->numeric()
                    ->prefix('$')
                    ->helperText('Dejar vacío para usar el precio base del catálogo.'),
                TextInput::make('custom_alias')
                    ->label('Alias / Nombre interno')
                    ->maxLength(191)
                    ->helperText('Opcional. Si el cliente llama al producto de otra forma.'),
                Toggle::make('is_available')
                    ->label('Disponible para este cliente')
                    ->default(true),
                Textarea::make('notes')
                    ->label('Notas')
                    ->rows(2)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->defaultSort('name')
            ->columns([
                TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable(),
                TextColumn::make('name')
                    ->label('Producto')
                    ->searchable()
                    ->weight('semibold'),
                TextColumn::make('category')
                    ->label('Categoría')
                    ->badge(),
                TextColumn::make('default_price')
                    ->label('Precio base')
                    ->money('COP')
                    ->color('gray'),
                TextColumn::make('pivot.custom_price')
                    ->label('Precio especial')
                    ->money('COP')
                    ->placeholder('—')
                    ->color(fn ($state) => $state ? 'success' : 'gray'),
                TextColumn::make('effective_price')
                    ->label('Precio efectivo')
                    ->money('COP')
                    ->state(fn (Product $record) => $record->pivot->custom_price ?? $record->default_price)
                    ->weight('bold'),
                TextColumn::make('pivot.custom_alias')
                    ->label('Alias')
                    ->placeholder('—'),
                IconColumn::make('pivot.is_available')
                    ->label('Disponible')
                    ->boolean(),
            ])
            ->filters([
                TernaryFilter::make('is_available')
                    ->label('Disponible')
                    ->queries(
                        true: fn ($query) => $query->wherePivot('is_available', true),
                        false: fn ($query) => $query->wherePivot('is_available', false),
                    ),
            ])
            ->headerActions([
                AttachAction::make()
                    ->label('Agregar producto')
                    ->preloadRecordSelect()
                    ->recordSelectSearchColumns(['sku', 'name'])
                    ->form(fn (AttachAction $action): array => [
                        $action->getRecordSelect(),
                        TextInput::make('custom_price')
                            ->label('Precio especial')
                            ->numeric()
                            ->prefix('$'),
                        Toggle::make('is_available')->default(true),
                    ]),
            ])
            ->recordActions([
                EditAction::make()->label('Editar override'),
                DetachAction::make()->label('Quitar'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
                ]),
            ]);
    }
}
