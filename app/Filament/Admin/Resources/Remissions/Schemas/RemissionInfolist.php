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
            // Una sola columna: las secciones se apilan verticalmente
            // y ocupan todo el ancho disponible.
            ->columns(1)
            ->components([
                Section::make('Comprobante')
                    ->description('Datos generales de la remisión')
                    ->columns(2)
                    ->columnSpanFull()
                    ->components([
                        TextEntry::make('id')->label('# Remisión')->prefix('#')->weight('bold'),
                        TextEntry::make('status')->label('Estado')->badge(),
                        TextEntry::make('issued_at')->label('Fecha de emisión')->dateTime('d/m/Y H:i'),
                        TextEntry::make('route')->label('Ruta')->badge(),
                        TextEntry::make('payment_type')->label('Tipo de pago')->badge(),
                        TextEntry::make('user.name')->label('Vendedor'),
                    ]),

                Section::make('Cliente')
                    ->columns(2)
                    ->columnSpanFull()
                    ->components([
                        TextEntry::make('client.name')->label('Cliente')->weight('semibold'),
                        TextEntry::make('client.nit')->label('NIT'),
                        TextEntry::make('client.address')->label('Dirección')->columnSpanFull()->placeholder('—'),
                    ]),

                Section::make('Productos')
                    ->columnSpanFull()
                    ->components([
                        RepeatableEntry::make('products')
                            ->label('')
                            ->columns(4)
                            ->components([
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
                            ->color('success')
                            ->alignEnd()
                            ->columnSpanFull(),
                    ]),

                Section::make('Observaciones y firma')
                    ->columnSpanFull()
                    ->components([
                        TextEntry::make('observations')
                            ->label('Observaciones')
                            ->placeholder('Sin observaciones')
                            ->columnSpanFull(),
                        SpatieMediaLibraryImageEntry::make('signature')
                            ->label('Firma del cliente')
                            ->collection('signature')
                            ->disk('local')
                            ->visibility('private')
                            ->height(120)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
