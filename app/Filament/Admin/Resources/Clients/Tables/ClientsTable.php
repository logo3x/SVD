<?php

namespace App\Filament\Admin\Resources\Clients\Tables;

use App\Enums\DeliveryPoint;
use App\Enums\PaymentType;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ClientsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->columns([
                SpatieMediaLibraryImageColumn::make('logo')
                    ->label('Logo')
                    ->collection('logo')
                    ->circular(),
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold'),
                TextColumn::make('nit')
                    ->label('NIT')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('manager_name')
                    ->label('Administrador')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('whatsapp')
                    ->label('WhatsApp')
                    ->searchable(),
                TextColumn::make('delivery_point')
                    ->label('Punto entrega')
                    ->badge(),
                TextColumn::make('payment_type')
                    ->label('Pago')
                    ->badge(),
                TextColumn::make('contract_end')
                    ->label('Fin contrato')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(),
                IconColumn::make('is_active')
                    ->label('Activo')
                    ->boolean(),
                TextColumn::make('products_count')
                    ->label('Productos')
                    ->counts('products')
                    ->badge()
                    ->color('info'),
                TextColumn::make('remissions_count')
                    ->label('Remisiones')
                    ->counts('remissions')
                    ->badge()
                    ->color('gray'),
            ])
            ->filters([
                SelectFilter::make('delivery_point')
                    ->label('Punto entrega')
                    ->options(collect(DeliveryPoint::cases())->mapWithKeys(fn ($c) => [$c->value => $c->getLabel()])),
                SelectFilter::make('payment_type')
                    ->label('Tipo de pago')
                    ->options(collect(PaymentType::cases())->mapWithKeys(fn ($c) => [$c->value => $c->getLabel()])),
                TernaryFilter::make('is_active')->label('Activo'),
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
