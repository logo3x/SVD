<?php

namespace App\Filament\Admin\Resources\Products\Tables;

use App\Enums\ProductCategory;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->columns([
                TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold'),
                TextColumn::make('category')
                    ->label('Categoría')
                    ->badge(),
                TextColumn::make('unit')
                    ->label('Unidad')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('default_price')
                    ->label('Precio base')
                    ->money('COP', divideBy: 1)
                    ->sortable(),
                IconColumn::make('is_default_for_new_clients')
                    ->label('Default')
                    ->boolean(),
                IconColumn::make('is_active')
                    ->label('Activo')
                    ->boolean(),
                TextColumn::make('clients_count')
                    ->label('Clientes')
                    ->counts('clients')
                    ->badge()
                    ->color('info'),
                TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->label('Categoría')
                    ->options(collect(ProductCategory::cases())->mapWithKeys(fn ($c) => [$c->value => $c->getLabel()])),
                TernaryFilter::make('is_active')->label('Activo'),
                TernaryFilter::make('is_default_for_new_clients')->label('Default'),
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('activate')
                        ->label('Activar')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn (Collection $records) => $records->each->update(['is_active' => true]))
                        ->deselectRecordsAfterCompletion(),
                    BulkAction::make('deactivate')
                        ->label('Desactivar')
                        ->icon('heroicon-o-x-circle')
                        ->color('warning')
                        ->action(fn (Collection $records) => $records->each->update(['is_active' => false]))
                        ->deselectRecordsAfterCompletion(),
                    BulkAction::make('toggle_default')
                        ->label('Cambiar default')
                        ->icon('heroicon-o-arrow-path')
                        ->color('gray')
                        ->action(fn (Collection $records) => $records->each(fn ($r) => $r->update(['is_default_for_new_clients' => ! $r->is_default_for_new_clients])))
                        ->deselectRecordsAfterCompletion(),
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
