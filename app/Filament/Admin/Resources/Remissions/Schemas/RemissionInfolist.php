<?php

namespace App\Filament\Admin\Resources\Remissions\Schemas;

use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RemissionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Comprobante')
                    ->columns(3)
                    ->components([
                        TextEntry::make('id')->label('# Remisión')->prefix('#')->weight('bold'),
                        TextEntry::make('issued_at')->label('Fecha')->dateTime('d/m/Y H:i'),
                        TextEntry::make('status')->label('Estado')->badge(),
                        TextEntry::make('client.name')->label('Cliente')->weight('semibold'),
                        TextEntry::make('client.nit')->label('NIT'),
                        TextEntry::make('client.address')->label('Dirección'),
                        TextEntry::make('user.name')->label('Vendedor'),
                        TextEntry::make('route')->label('Ruta')->badge(),
                        TextEntry::make('payment_type')->label('Tipo de pago')->badge(),
                        TextEntry::make('observations')->label('Observaciones')->placeholder('—')->columnSpanFull(),
                    ]),

                Section::make('Productos')
                    ->components([
                        RepeatableEntry::make('products')
                            ->label('')
                            ->columns(4)
                            ->schema([
                                TextEntry::make('name')->label('Producto')->weight('semibold')->columnSpan(2),
                                TextEntry::make('pivot.quantity')->label('Cantidad'),
                                TextEntry::make('pivot.unit_price_snapshot')->label('Precio unit.')->money('COP'),
                                TextEntry::make('pivot.subtotal')->label('Subtotal')->money('COP')->columnSpan(4)->alignEnd()->weight('bold'),
                            ]),
                        TextEntry::make('total_amount')
                            ->label('TOTAL')
                            ->money('COP')
                            ->size('xl')
                            ->weight('bold')
                            ->color('success'),
                    ]),

                Section::make('Firma')
                    ->components([
                        SpatieMediaLibraryImageEntry::make('signature')
                            ->label('Firma del cliente')
                            ->collection('signature')
                            ->disk('local')
                            ->visibility('private')
                            ->height(120),
                    ]),
            ]);
    }
}
