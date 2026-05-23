<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\MobileDevices\Tables;

use App\Models\MobileDevice;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class MobileDevicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('last_used_at', 'desc')
            ->columns([
                TextColumn::make('id')->label('#')->prefix('#')->sortable(),
                TextColumn::make('name')
                    ->label('Dispositivo')
                    ->searchable()
                    ->weight('semibold')
                    ->description(fn (MobileDevice $r) => 'Token Sanctum'),
                TextColumn::make('tokenable_id')
                    ->label('Vendedor')
                    ->state(fn (MobileDevice $r) => optional(User::find($r->tokenable_id))->name ?? '—')
                    ->description(fn (MobileDevice $r) => optional(User::find($r->tokenable_id))->email)
                    ->searchable(query: function ($query, string $search) {
                        $query->whereHas('tokenable', fn ($q) => $q
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%"));
                    }),
                TextColumn::make('abilities')
                    ->label('Permisos')
                    ->badge()
                    ->separator(',')
                    ->formatStateUsing(function ($state) {
                        if (is_string($state)) {
                            $decoded = json_decode($state, true);

                            return is_array($decoded) ? implode(',', $decoded) : $state;
                        }

                        return is_array($state) ? implode(',', $state) : (string) $state;
                    })
                    ->limit(40),
                TextColumn::make('last_used_at')
                    ->label('Último uso')
                    ->dateTime('d/m/Y H:i')
                    ->since()
                    ->sortable()
                    ->placeholder('Nunca'),
                TextColumn::make('created_at')
                    ->label('Emitido')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('expires_at')
                    ->label('Expira')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('Sin expiración')
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('user')
                    ->label('Vendedor')
                    ->options(fn () => User::query()->orderBy('name')->pluck('name', 'id'))
                    ->query(fn ($query, array $data) => $data['value']
                        ? $query->where('tokenable_id', $data['value'])
                        : $query),
                Filter::make('active_recently')
                    ->label('Activos en las últimas 24h')
                    ->query(fn ($query) => $query->where('last_used_at', '>=', now()->subDay())),
                Filter::make('idle')
                    ->label('Sin uso hace +30 días')
                    ->query(fn ($query) => $query->where(fn ($q) => $q
                        ->whereNull('last_used_at')
                        ->orWhere('last_used_at', '<', now()->subDays(30)))),
            ])
            ->recordActions([
                Action::make('revoke')
                    ->label('Revocar')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Revocar dispositivo')
                    ->modalDescription('El dispositivo no podrá usar este token. El usuario deberá volver a iniciar sesión.')
                    ->modalSubmitActionLabel('Revocar')
                    ->action(function (MobileDevice $record): void {
                        $record->delete();
                        Notification::make()
                            ->title('Dispositivo revocado')
                            ->success()
                            ->send();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('revokeBulk')
                        ->label('Revocar seleccionados')
                        ->icon('heroicon-o-x-mark')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(function (Collection $records): void {
                            $count = $records->count();
                            foreach ($records as $record) {
                                $record->delete();
                            }
                            Notification::make()
                                ->title($count.' dispositivos revocados')
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->headerActions([
                Action::make('revokeAll')
                    ->label('Revocar todos (kill switch)')
                    ->icon('heroicon-o-bolt-slash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Revocar TODOS los dispositivos')
                    ->modalDescription('Esto cerrará la sesión de todos los vendedores en sus apps móviles. Úsalo en emergencias.')
                    ->modalSubmitActionLabel('Sí, revocar todo')
                    ->action(function (): void {
                        $count = MobileDevice::where('tokenable_type', User::class)->count();
                        MobileDevice::where('tokenable_type', User::class)->delete();
                        Notification::make()
                            ->title('Kill switch ejecutado')
                            ->body("{$count} dispositivos revocados. Todos los vendedores deberán volver a iniciar sesión.")
                            ->warning()
                            ->send();
                    }),
            ]);
    }
}
