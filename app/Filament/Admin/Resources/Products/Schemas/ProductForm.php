<?php

namespace App\Filament\Admin\Resources\Products\Schemas;

use App\Enums\ProductCategory;
use App\Enums\ProductUnit;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identificación')
                    ->description('Datos únicos del producto en el catálogo maestro.')
                    ->columns(2)
                    ->components([
                        TextInput::make('sku')
                            ->label('SKU')
                            ->required()
                            ->maxLength(64)
                            ->unique(ignoreRecord: true)
                            ->helperText('Código único interno (ej: H-5000).'),
                        TextInput::make('name')
                            ->label('Nombre')
                            ->required()
                            ->maxLength(191),
                        Textarea::make('description')
                            ->label('Descripción')
                            ->rows(2)
                            ->columnSpanFull(),
                    ]),

                Section::make('Clasificación')
                    ->columns(3)
                    ->components([
                        Select::make('category')
                            ->label('Categoría')
                            ->options(ProductCategory::class)
                            ->required()
                            ->native(false),
                        Select::make('unit')
                            ->label('Unidad')
                            ->options(ProductUnit::class)
                            ->required()
                            ->native(false),
                        TextInput::make('default_price')
                            ->label('Precio base')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->prefix('$')
                            ->helperText('Precio por defecto (en pesos colombianos). Los clientes pueden tener override.'),
                    ]),

                Section::make('Disponibilidad')
                    ->columns(2)
                    ->components([
                        Toggle::make('is_default_for_new_clients')
                            ->label('Adjuntar a nuevos clientes')
                            ->helperText('Si está activo, al crear un cliente se le vincula este producto automáticamente.')
                            ->default(true),
                        Toggle::make('is_active')
                            ->label('Activo')
                            ->default(true),
                    ]),
            ]);
    }
}
