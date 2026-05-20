<?php

namespace App\Filament\Admin\Resources\Remissions\Schemas;

use App\Enums\DeliveryRoute;
use App\Enums\PaymentType;
use App\Enums\RemissionStatus;
use App\Models\Client;
use App\Models\Product;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class RemissionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Cabecera')
                    ->columns(3)
                    ->components([
                        Select::make('client_id')
                            ->label('Cliente')
                            ->relationship('client', 'name', fn ($query) => $query->where('is_active', true))
                            ->searchable(['name', 'nit'])
                            ->preload()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Set $set, ?int $state) {
                                if (! $state) {
                                    return;
                                }

                                $client = Client::find($state);
                                if ($client) {
                                    $set('payment_type', $client->payment_type?->value);
                                }
                                $set('items', []);
                            }),
                        Select::make('user_id')
                            ->label('Vendedor')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->default(fn () => auth()->id())
                            ->required(),
                        DateTimePicker::make('issued_at')
                            ->label('Fecha y hora')
                            ->default(now())
                            ->required()
                            ->native(false)
                            ->seconds(false),
                        Select::make('payment_type')
                            ->label('Tipo de pago')
                            ->options(PaymentType::class)
                            ->required()
                            ->native(false),
                        Select::make('route')
                            ->label('Ruta')
                            ->options(DeliveryRoute::class)
                            ->required()
                            ->native(false),
                        Select::make('status')
                            ->label('Estado')
                            ->options(RemissionStatus::class)
                            ->default(RemissionStatus::Confirmed->value)
                            ->required()
                            ->native(false),
                    ]),

                Section::make('Productos')
                    ->description('Selecciona los productos del cliente. El precio se resuelve automáticamente desde el catálogo (con override si aplica).')
                    ->components([
                        Repeater::make('items')
                            ->label('')
                            ->relationship('products')
                            ->columns(12)
                            ->reorderable(false)
                            ->live()
                            ->addActionLabel('Agregar producto')
                            ->minItems(1)
                            ->schema([
                                Select::make('product_id')
                                    ->label('Producto')
                                    ->columnSpan(5)
                                    ->options(function (Get $get) {
                                        $clientId = $get('../../client_id');
                                        if (! $clientId) {
                                            return Product::active()->orderBy('name')->pluck('name', 'id');
                                        }

                                        return Product::query()
                                            ->whereHas('clients', fn ($q) => $q->where('clients.id', $clientId)->where('client_product.is_available', true))
                                            ->active()
                                            ->orderBy('name')
                                            ->pluck('name', 'id');
                                    })
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function (Set $set, Get $get, ?int $state) {
                                        if (! $state) {
                                            return;
                                        }

                                        $clientId = $get('../../client_id');
                                        $product = Product::find($state);
                                        if (! $product) {
                                            return;
                                        }

                                        $price = $product->default_price;
                                        if ($clientId) {
                                            $pivot = $product->clients()->where('clients.id', $clientId)->first()?->pivot;
                                            $price = $pivot?->custom_price ?? $product->default_price;
                                        }

                                        $set('unit_price_snapshot', $price);
                                        $quantity = (int) ($get('quantity') ?? 1);
                                        $set('subtotal', $price * $quantity);
                                    })
                                    ->distinct(),

                                TextInput::make('quantity')
                                    ->label('Cantidad')
                                    ->columnSpan(2)
                                    ->numeric()
                                    ->minValue(1)
                                    ->default(1)
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (Set $set, Get $get, $state) {
                                        $price = (int) ($get('unit_price_snapshot') ?? 0);
                                        $set('subtotal', $price * (int) $state);
                                    }),

                                TextInput::make('unit_price_snapshot')
                                    ->label('Precio unitario')
                                    ->columnSpan(2)
                                    ->numeric()
                                    ->prefix('$')
                                    ->required()
                                    ->readOnly()
                                    ->dehydrated(),

                                TextInput::make('subtotal')
                                    ->label('Subtotal')
                                    ->columnSpan(3)
                                    ->numeric()
                                    ->prefix('$')
                                    ->required()
                                    ->readOnly()
                                    ->dehydrated(),
                            ]),

                        TextInput::make('total_amount')
                            ->label('Total')
                            ->numeric()
                            ->prefix('$')
                            ->readOnly()
                            ->default(0)
                            ->dehydrated()
                            ->afterStateHydrated(fn (Set $set, Get $get) => $set('total_amount', collect($get('items') ?? [])->sum('subtotal'))),
                    ]),

                Section::make('Información adicional')
                    ->columns(2)
                    ->components([
                        Textarea::make('observations')
                            ->label('Observaciones')
                            ->rows(2)
                            ->columnSpanFull(),
                        TextInput::make('gps_location')
                            ->label('Ubicación GPS')
                            ->placeholder('lat,lng'),
                        SpatieMediaLibraryFileUpload::make('signature')
                            ->label('Firma digital del cliente')
                            ->collection('signature')
                            ->disk('local')
                            ->image()
                            ->visibility('private'),
                    ]),
            ]);
    }
}
