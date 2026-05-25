<?php

declare(strict_types=1);

namespace App\Filament\Vendedor\Resources\Remissions\Tables;

use App\Enums\PaymentType;
use App\Enums\RemissionStatus;
use App\Filament\Vendedor\Resources\Remissions\RemissionResource;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class RemissionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('issued_at', 'desc')
            ->striped()
            ->defaultPaginationPageOption(10)
            ->paginated([10, 25, 50, 100])
            ->extremePaginationLinks(false)
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->prefix('#')
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('issued_at')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('client.name')
                    ->label('Cliente')
                    ->searchable()
                    ->weight('semibold')
                    ->wrap(),
                TextColumn::make('route')
                    ->label('Ruta')
                    ->badge()
                    ->toggleable(),
                TextColumn::make('payment_type')
                    ->label('Pago')
                    ->badge(),
                TextColumn::make('status')
                    ->label('Estado')
                    ->badge(),
                TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('COP')
                    ->alignEnd()
                    ->weight('bold'),
            ])
            ->filters([
                SelectFilter::make('payment_type')
                    ->label('Tipo de pago')
                    ->options(collect(PaymentType::cases())->mapWithKeys(fn ($c) => [$c->value => $c->getLabel()])),
                SelectFilter::make('status')
                    ->label('Estado')
                    ->options(collect(RemissionStatus::cases())->mapWithKeys(fn ($c) => [$c->value => $c->getLabel()])),
                Filter::make('issued_at')
                    ->label('Rango de fechas')
                    ->schema([
                        DatePicker::make('from')->label('Desde')->native(false),
                        DatePicker::make('to')->label('Hasta')->native(false),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'] ?? null, fn ($q, $d) => $q->whereDate('issued_at', '>=', $d))
                            ->when($data['to'] ?? null, fn ($q, $d) => $q->whereDate('issued_at', '<=', $d));
                    }),
            ])
            ->recordActions([
                ViewAction::make()->label('Ver'),
            ])
            ->emptyStateHeading('Aún no tienes remisiones')
            ->emptyStateDescription('Crea tu primera remisión para empezar.')
            ->emptyStateIcon('heroicon-o-document-text')
            ->emptyStateActions([
                Action::make('createFirst')
                    ->label('Crear primera remisión')
                    ->icon('heroicon-o-plus')
                    ->color('primary')
                    ->url(fn () => RemissionResource::getUrl('create')),
            ]);
    }
}
