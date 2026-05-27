<?php

namespace App\Filament\Admin\Resources\EmployeeProfiles\Schemas;

use App\Enums\EmploymentStatus;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class EmployeeProfileForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Empleado')
                    ->columnSpanFull()
                    ->tabs([
                        Tab::make('Cuenta vinculada')
                            ->icon(Heroicon::OutlinedUser)
                            ->columns(2)
                            ->schema([
                                Select::make('user_id')
                                    ->label('Usuario del sistema (opcional)')
                                    ->helperText('Vincula este empleado a una cuenta de acceso. Déjalo vacío si el empleado no usa el sistema.')
                                    ->relationship('user', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->unique(ignoreRecord: true)
                                    ->live()
                                    ->columnSpanFull(),
                                TextInput::make('full_name')
                                    ->label('Nombre completo')
                                    ->maxLength(128)
                                    // Obligatorio sólo cuando NO se vincula un usuario.
                                    ->required(fn (Get $get): bool => blank($get('user_id')))
                                    ->helperText('Requerido si el empleado no tiene usuario del sistema.')
                                    ->columnSpanFull(),
                                TextInput::make('email')
                                    ->label('Correo')
                                    ->email()
                                    ->maxLength(128)
                                    ->columnSpanFull(),
                                SpatieMediaLibraryFileUpload::make('avatar')
                                    ->label('Foto')
                                    ->collection('avatar')
                                    ->image()
                                    ->avatar()
                                    ->imageEditor()
                                    ->columnSpanFull(),
                            ]),

                        Tab::make('Datos Personales')
                            ->icon(Heroicon::OutlinedIdentification)
                            ->columns(2)
                            ->schema([
                                TextInput::make('national_id')->label('Cédula')->maxLength(32),
                                TextInput::make('job_title')->label('Cargo')->maxLength(128),
                                TextInput::make('phone')->label('Teléfono')->tel(),
                                TextInput::make('whatsapp')->label('WhatsApp')->tel(),
                                DatePicker::make('birth_date')->label('Fecha de nacimiento')->native(false),
                                TextInput::make('city')->label('Ciudad'),
                                TextInput::make('address')->label('Dirección')->columnSpanFull(),
                                Select::make('marital_status')
                                    ->label('Estado civil')
                                    ->options([
                                        'single' => 'Soltero',
                                        'married' => 'Casado',
                                        'divorced' => 'Divorciado',
                                        'widowed' => 'Viudo',
                                    ])
                                    ->native(false),
                                TextInput::make('children')->label('Hijos')->numeric()->minValue(0),
                            ]),

                        Tab::make('Datos Laborales')
                            ->icon(Heroicon::OutlinedBriefcase)
                            ->columns(2)
                            ->schema([
                                TextInput::make('employment_link')->label('Vínculo laboral')->placeholder('Independiente / Formal'),
                                Select::make('employment_status')
                                    ->label('Estado laboral')
                                    ->options(EmploymentStatus::class)
                                    ->required()
                                    ->default(EmploymentStatus::Active->value)
                                    ->native(false),
                                DatePicker::make('contract_start')->label('Inicio contrato')->native(false),
                                DatePicker::make('retired_at')->label('Fecha de retiro')->native(false),
                            ]),

                        Tab::make('Seguridad Social y Banco')
                            ->icon(Heroicon::OutlinedHeart)
                            ->columns(2)
                            ->schema([
                                TextInput::make('blood_type')->label('Grupo sanguíneo (RH)')->maxLength(3),
                                TextInput::make('eps')->label('EPS'),
                                TextInput::make('afp')->label('AFP')->helperText('Fondo de pensiones'),
                                TextInput::make('arl')->label('ARL')->helperText('Riesgos laborales'),
                                TextInput::make('bank_account')->label('Cuenta bancaria')->columnSpanFull(),
                            ]),

                        Tab::make('Documentos')
                            ->icon(Heroicon::OutlinedPaperClip)
                            ->schema([
                                SpatieMediaLibraryFileUpload::make('documents')
                                    ->label('Documentos (hoja de vida, contrato, etc.)')
                                    ->collection('documents')
                                    ->multiple()
                                    ->columnSpanFull(),
                            ]),
                    ]),
            ]);
    }
}
