<?php

declare(strict_types=1);

namespace App\Filament\Admin\Pages;

use App\Models\MobileDevice;
use App\Models\User;
use App\Settings\MobileSettings;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class MobileSettingsPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?string $navigationLabel = 'Configuración móvil';

    protected static ?string $title = 'Configuración App Móvil';

    protected static string|\UnitEnum|null $navigationGroup = 'App móvil';

    protected static ?int $navigationSort = 70;

    protected string $view = 'filament.admin.pages.mobile-settings-page';

    /** @var array<string, mixed> */
    public array $data = [];

    public ?int $activeDevices = null;

    public ?int $totalUsers = null;

    public function mount(MobileSettings $settings): void
    {
        $this->form->fill($settings->toArray());
        $this->refreshStats();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Versionado de la app')
                    ->description('Controla qué versiones de la app pueden usar el backend. La app envía el header X-App-Version en cada request.')
                    ->columns(2)
                    ->components([
                        TextInput::make('min_app_version')
                            ->label('Versión mínima recomendada')
                            ->placeholder('1.2.0')
                            ->helperText('Por debajo de esta versión, la app recibe 426 con "force_update: false".')
                            ->required(),
                        TextInput::make('force_update_version')
                            ->label('Versión que fuerza actualización')
                            ->placeholder('1.0.0')
                            ->helperText('Por debajo de esta versión, la app recibe 426 con "force_update: true" y se bloquea.')
                            ->required(),
                    ]),

                Section::make('Modo mantenimiento')
                    ->description('Cuando está activo, TODAS las requests API responden 503 con el mensaje configurado.')
                    ->columns(1)
                    ->components([
                        Toggle::make('maintenance_mode')
                            ->label('Activar modo mantenimiento')
                            ->helperText('La app móvil mostrará el mensaje y bloqueará la operación hasta que se desactive.'),
                        Textarea::make('maintenance_message')
                            ->label('Mensaje de mantenimiento')
                            ->rows(2)
                            ->placeholder('Plataforma en mantenimiento. Vuelve en unos minutos.')
                            ->required(),
                    ]),

                Section::make('Anuncio en la app')
                    ->description('Banner informativo no bloqueante que se inyecta como header X-SVD-Announcement en cada respuesta.')
                    ->columns(1)
                    ->components([
                        Toggle::make('announcement_enabled')
                            ->label('Mostrar anuncio'),
                        Textarea::make('announcement_message')
                            ->label('Mensaje del anuncio')
                            ->rows(2)
                            ->placeholder('Nueva versión disponible · Toca para actualizar'),
                    ]),

                Section::make('Conexión')
                    ->columns(2)
                    ->components([
                        TextInput::make('api_base_url')
                            ->label('Base URL de la API')
                            ->placeholder('https://svd.example.com/api/v1')
                            ->helperText('URL que la app móvil debe consumir. Solo informativo.')
                            ->url()
                            ->required(),
                        TextInput::make('default_token_ttl_days')
                            ->label('TTL por defecto de tokens (días)')
                            ->integer()
                            ->minValue(0)
                            ->helperText('0 = los tokens no expiran.')
                            ->required(),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(MobileSettings $settings): void
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            if (! property_exists($settings, $key)) {
                continue;
            }

            // El TextInput numérico de Livewire puede entregar el valor como
            // float/string; casteamos al tipo declarado de la propiedad.
            $type = (new \ReflectionProperty($settings, $key))->getType();
            if ($type instanceof \ReflectionNamedType && $type->getName() === 'int') {
                $value = (int) $value;
            }

            // Los campos de texto vacíos llegan como null; las propiedades string no lo admiten.
            if ($type instanceof \ReflectionNamedType && $type->getName() === 'string' && ! $type->allowsNull()) {
                $value = (string) $value;
            }

            $settings->{$key} = $value;
        }

        $settings->save();

        Notification::make()
            ->title('Configuración móvil guardada')
            ->success()
            ->send();
    }

    public function refreshStats(): void
    {
        $this->activeDevices = MobileDevice::where('tokenable_type', User::class)
            ->where('last_used_at', '>=', now()->subDays(7))
            ->count();
        $this->totalUsers = MobileDevice::where('tokenable_type', User::class)
            ->distinct('tokenable_id')
            ->count('tokenable_id');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label('Guardar cambios')
                ->action('save'),
        ];
    }
}
