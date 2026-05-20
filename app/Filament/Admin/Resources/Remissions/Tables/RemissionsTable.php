<?php

namespace App\Filament\Admin\Resources\Remissions\Tables;

use App\Enums\PaymentType;
use App\Enums\RemissionStatus;
use App\Models\Remission;
use App\Services\RemissionsXlsxExporter;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class RemissionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('issued_at', 'desc')
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
                    ->sortable()
                    ->weight('semibold'),
                TextColumn::make('user.name')
                    ->label('Vendedor')
                    ->searchable(),
                TextColumn::make('route')
                    ->label('Ruta')
                    ->badge(),
                TextColumn::make('payment_type')
                    ->label('Pago')
                    ->badge(),
                TextColumn::make('status')
                    ->label('Estado')
                    ->badge(),
                TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('COP')
                    ->sortable()
                    ->alignEnd()
                    ->weight('bold'),
            ])
            ->filters([
                SelectFilter::make('client_id')
                    ->label('Cliente')
                    ->relationship('client', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('user_id')
                    ->label('Vendedor')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
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
                TrashedFilter::make(),
            ])
            ->headerActions([
                Action::make('exportAll')
                    ->label('Exportar (filtros)')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->action(function ($livewire) {
                        $query = $livewire->getFilteredTableQuery();

                        return app(RemissionsXlsxExporter::class)
                            ->streamDownload($query, 'remisiones-'.now()->format('Ymd-His').'.xlsx');
                    }),
            ])
            ->recordActions([
                ViewAction::make()->label('Ver'),
                EditAction::make()->label('Editar'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('exportSelected')
                        ->label('Exportar selección')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->color('success')
                        ->action(function (Collection $records) {
                            $ids = $records->pluck('id');
                            $query = Remission::query()->whereIn('id', $ids);

                            return app(RemissionsXlsxExporter::class)
                                ->streamDownload($query, 'remisiones-seleccion-'.now()->format('Ymd-His').'.xlsx');
                        })
                        ->deselectRecordsAfterCompletion(),
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
