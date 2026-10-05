<?php

namespace App\Filament\Admin\Resources\Clients\Schemas;

use App\Enums\DeliveryPoint;
use App\Enums\PaymentType;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class ClientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Cliente')
                    ->columnSpanFull()
                    ->tabs([
                        Tab::make('Datos del Cliente')
                            ->icon(Heroicon::OutlinedIdentification)
                            ->columns(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Razón social / Nombre')
                                    ->required()
                                    ->maxLength(128),
                                TextInput::make('nit')
                                    ->label('NIT')
                                    ->required()
                                    ->maxLength(64),
                                TextInput::make('manager_name')
                                    ->label('Administrador / Contacto')
                                    ->maxLength(128),
                                Textarea::make('description')
                                    ->label('Descripción del negocio')
                                    ->rows(2),
                                TextInput::make('address')
                                    ->label('Dirección'),
                                TextInput::make('city')
                                    ->label('Ciudad'),
                                TextInput::make('phone')
                                    ->label('Teléfono fijo')
                                    ->tel(),
                                TextInput::make('whatsapp')
                                    ->label('WhatsApp')
                                    ->tel()
                                    ->required(),
                                TextInput::make('email')
                                    ->label('Correo')
                                    ->email()
                                    ->required(),
                                TextInput::make('social_networks')
                                    ->label('Redes sociales'),
                            ]),

                        Tab::make('Contrato y Pago')
                            ->icon(Heroicon::OutlinedBriefcase)
                            ->columns(2)
                            ->schema([
                                Select::make('delivery_point')
                                    ->label('Punto de entrega')
                                    ->options(DeliveryPoint::class)
                                    ->required()
                                    ->native(false),
                                Select::make('payment_type')
                                    ->label('Tipo de pago')
                                    ->options(PaymentType::class)
                                    ->required()
                                    ->native(false),
                                DatePicker::make('contract_start')
                                    ->label('Inicio de contrato')
                                    ->native(false),
                                DatePicker::make('contract_end')
                                    ->label('Fin de contrato')
                                    ->native(false),
                                Toggle::make('is_active')
                                    ->label('Cliente activo')
                                    ->default(true),
                                Textarea::make('notes')
                                    ->label('Notas internas')
                                    ->rows(3)
                                    ->columnSpanFull(),
                            ]),

                        Tab::make('Adjuntos')
                            ->icon(Heroicon::OutlinedPaperClip)
                            ->columns(2)
                            ->schema([
                                SpatieMediaLibraryFileUpload::make('logo')
                                    ->label('Logo')
                                    ->collection('logo')
                                    ->image()
                                    ->imageEditor()
                                    ->avatar()
                                    ->columnSpanFull(),
                                SpatieMediaLibraryFileUpload::make('contract')
                                    ->label('Contrato / Documentos')
                                    ->collection('contract')
                                    ->multiple()
                                    ->columnSpanFull(),
                            ]),
                    ]),
            ]);
    }
}
