<?php

namespace App\Filament\Admin\Resources\Remissions\Schemas;

use App\Enums\DeliveryRoute;
use App\Enums\PaymentType;
use App\Enums\RemissionStatus;
use App\Models\Client;
use App\Models\Product;
use Filament\Facades\Filament;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Html;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;

class RemissionForm
{
    public static function configure(Schema $schema): Schema
    {
        $isVendorPanel = Filament::getCurrentPanel()?->getId() === 'vendedor';

        return $schema
            ->components([
                // ============================================================
                // CLIENTE Y ENTREGA — fila compacta con todos los datos clave
                // ============================================================
                Section::make('Cliente y entrega')
                    ->columnSpanFull()
                    ->columns(['default' => 1, 'md' => 4])
                    ->components([
                        Select::make('client_id')
                            ->label('Cliente')
                            ->relationship('client', 'name', fn ($query) => $query->where('is_active', true))
                            ->searchable(['name', 'nit'])
                            ->preload()
                            ->required()
                            ->live()
                            ->columnSpan(['default' => 1, 'md' => 2])
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

                        DateTimePicker::make('issued_at')
                            ->label('Fecha y hora')
                            ->default(now())
                            ->required()
                            ->native(false)
                            ->seconds(false)
                            ->columnSpan(['default' => 1, 'md' => 2]),

                        // En el panel del vendedor el user_id se asigna en backend.
                        // En el panel admin se puede elegir explícitamente.
                        Select::make('user_id')
                            ->label('Vendedor')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->default(fn () => Auth::id())
                            ->required()
                            ->hidden($isVendorPanel),

                        Select::make('status')
                            ->label('Estado')
                            ->options(RemissionStatus::class)
                            ->default(RemissionStatus::Confirmed->value)
                            ->required()
                            ->native(false)
                            ->hidden($isVendorPanel),

                        // Cuando el vendedor está creando, status va oculto con default.
                        Hidden::make('status')
                            ->default(RemissionStatus::Confirmed->value)
                            ->visible($isVendorPanel)
                            ->dehydrated(),
                    ]),

                // ============================================================
                // PRODUCTOS — full width con repeater compacto y TOTAL grande
                // ============================================================
                Section::make('Productos')
                    ->description('Selecciona los productos. El precio se resuelve automáticamente desde el catálogo del cliente.')
                    ->columnSpanFull()
                    ->components([
                        Repeater::make('items')
                            ->label('')
                            ->relationship('products')
                            ->columns(12)
                            ->reorderable(false)
                            ->live()
                            ->addActionLabel('+ Agregar producto')
                            ->minItems(1)
                            ->itemLabel(function (array $state): ?string {
                                $name = $state['product_id'] ?? null;
                                if (! $name) {
                                    return null;
                                }
                                $product = Product::find($name);
                                $subtotal = (int) ($state['subtotal'] ?? 0);

                                return $product
                                    ? sprintf('%s · %d × $%s = $%s',
                                        $product->name,
                                        (int) ($state['quantity'] ?? 0),
                                        number_format((int) ($state['unit_price_snapshot'] ?? 0), 0, ',', '.'),
                                        number_format($subtotal, 0, ',', '.')
                                    )
                                    : null;
                            })
                            ->schema([
                                Select::make('product_id')
                                    ->label('Producto')
                                    ->columnSpan(['default' => 12, 'md' => 6])
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
                                    ->columnSpan(['default' => 4, 'md' => 2])
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
                                    ->label('Precio unit.')
                                    ->columnSpan(['default' => 4, 'md' => 2])
                                    ->numeric()
                                    ->prefix('$')
                                    ->required()
                                    ->readOnly()
                                    ->dehydrated(),

                                TextInput::make('subtotal')
                                    ->label('Subtotal')
                                    ->columnSpan(['default' => 4, 'md' => 2])
                                    ->numeric()
                                    ->prefix('$')
                                    ->required()
                                    ->readOnly()
                                    ->dehydrated(),
                            ]),

                        // TOTAL grande en lugar de un TextInput pequeño.
                        Html::make(function (Get $get): HtmlString {
                            $total = collect($get('items') ?? [])->sum('subtotal');

                            return new HtmlString(
                                '<div style="display:flex;justify-content:space-between;align-items:baseline;padding:1rem 0 0;border-top:2px solid rgb(var(--primary-600));">'
                                .'<span style="font-size:0.875rem;font-weight:500;color:rgb(107 114 128);text-transform:uppercase;letter-spacing:0.08em;">Total</span>'
                                .'<span style="font-size:2rem;font-weight:700;color:rgb(var(--primary-600));font-variant-numeric:tabular-nums;">$'
                                .number_format((int) $total, 0, ',', '.')
                                .' COP</span>'
                                .'</div>'
                            );
                        }),

                        // Total real persistido (no visible).
                        Hidden::make('total_amount')
                            ->default(0)
                            ->dehydrated()
                            ->afterStateHydrated(fn (Set $set, Get $get) => $set('total_amount', collect($get('items') ?? [])->sum('subtotal'))),
                    ]),

                // ============================================================
                // INFORMACIÓN ADICIONAL — collapsible, opcional
                // ============================================================
                Section::make('Información adicional')
                    ->description('Observaciones, ubicación GPS y firma del cliente (opcional).')
                    ->icon('heroicon-o-information-circle')
                    ->collapsible()
                    ->collapsed()
                    ->columnSpanFull()
                    ->columns(['default' => 1, 'md' => 4])
                    ->components([
                        Textarea::make('observations')
                            ->label('Observaciones')
                            ->rows(2)
                            ->columnSpanFull(),

                        TextInput::make('gps_lat')
                            ->label('GPS Latitud')
                            ->numeric()
                            ->step('0.00000001')
                            ->minValue(-90)
                            ->maxValue(90)
                            ->placeholder('7.06530000')
                            ->columnSpan(['default' => 1, 'md' => 2]),

                        TextInput::make('gps_lng')
                            ->label('GPS Longitud')
                            ->numeric()
                            ->step('0.00000001')
                            ->minValue(-180)
                            ->maxValue(180)
                            ->placeholder('-73.85470000')
                            ->columnSpan(['default' => 1, 'md' => 2]),

                        Tabs::make('Firma del cliente')
                            ->columnSpanFull()
                            ->tabs([
                                Tab::make('Firmar en pantalla')
                                    ->icon('heroicon-o-pencil-square')
                                    ->schema([
                                        Hidden::make('signature_data_url')
                                            ->dehydrated(),
                                        View::make('filament.components.signature-pad')
                                            ->statePath('signature_data_url'),
                                    ]),
                                Tab::make('Subir imagen')
                                    ->icon('heroicon-o-arrow-up-tray')
                                    ->schema([
                                        SpatieMediaLibraryFileUpload::make('signature')
                                            ->label('Firma digital del cliente')
                                            ->collection('signature')
                                            ->disk('local')
                                            ->image()
                                            ->visibility('private')
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                    ]),
            ]);
    }
}
