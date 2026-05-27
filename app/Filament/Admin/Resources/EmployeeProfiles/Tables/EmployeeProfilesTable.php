<?php

namespace App\Filament\Admin\Resources\EmployeeProfiles\Tables;

use App\Enums\EmploymentStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class EmployeeProfilesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('avatar')
                    ->label('Foto')
                    ->collection('avatar')
                    ->circular(),
                TextColumn::make('display_name')
                    ->label('Nombre')
                    // Nombre del usuario vinculado o el full_name propio.
                    ->state(fn ($record): string => $record->displayName())
                    ->description(fn ($record) => $record->user ? 'Usuario del sistema' : 'Sin acceso')
                    ->searchable(query: fn ($query, string $search) => $query
                        ->where('full_name', 'like', "%{$search}%")
                        ->orWhereHas('user', fn ($q) => $q->where('name', 'like', "%{$search}%")))
                    ->weight('semibold'),
                TextColumn::make('national_id')
                    ->label('Cédula')
                    ->searchable(),
                TextColumn::make('job_title')
                    ->label('Cargo')
                    ->searchable(),
                TextColumn::make('whatsapp')
                    ->label('WhatsApp')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('employment_link')
                    ->label('Vínculo')
                    ->toggleable(),
                TextColumn::make('employment_status')
                    ->label('Estado')
                    ->badge(),
                TextColumn::make('retired_at')
                    ->label('Fecha retiro')
                    ->date('d/m/Y')
                    ->placeholder('—')
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('employment_status')
                    ->label('Estado laboral')
                    ->options(collect(EmploymentStatus::cases())->mapWithKeys(fn ($c) => [$c->value => $c->getLabel()])),
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
