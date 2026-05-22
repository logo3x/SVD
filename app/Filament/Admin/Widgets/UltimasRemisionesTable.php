<?php

namespace App\Filament\Admin\Widgets;

use App\Filament\Admin\Resources\Remissions\RemissionResource;
use App\Models\Remission;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class UltimasRemisionesTable extends TableWidget
{
    protected static ?string $heading = 'Últimas remisiones';

    protected int|string|array $columnSpan = 'full';

    /**
     * Lazy load: la tabla llega después del primer render del dashboard.
     */
    protected static bool $isLazy = true;

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Remission::query()->with(['client', 'user'])->latest('issued_at')->limit(10))
            ->defaultPaginationPageOption(10)
            ->paginated(false)
            ->columns([
                TextColumn::make('id')->label('#')->prefix('#')->weight('bold'),
                TextColumn::make('issued_at')->label('Fecha')->dateTime('d/m/Y H:i'),
                TextColumn::make('client.name')->label('Cliente')->weight('semibold'),
                TextColumn::make('user.name')->label('Vendedor'),
                TextColumn::make('payment_type')->label('Pago')->badge(),
                TextColumn::make('total_amount')->label('Total')->money('COP')->alignEnd()->weight('bold'),
            ])
            ->recordActions([
                Action::make('view')
                    ->label('Ver')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Remission $record) => RemissionResource::getUrl('view', ['record' => $record])),
            ]);
    }
}
