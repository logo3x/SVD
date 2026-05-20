<?php

namespace App\Filament\Admin\Pages;

use App\Settings\BrandingSettings;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class Settings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?string $navigationLabel = 'Configuración';

    protected static ?string $title = 'Configuración / Branding';

    protected static string|\UnitEnum|null $navigationGroup = 'Configuración';

    protected static ?int $navigationSort = 90;

    protected string $view = 'filament.admin.pages.settings';

    public ?array $data = [];

    public function mount(BrandingSettings $settings): void
    {
        $this->form->fill($settings->toArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identidad')
                    ->columns(2)
                    ->components([
                        TextInput::make('company_name')->label('Nombre de la empresa')->required(),
                        TextInput::make('company_tagline')->label('Eslogan'),
                        TextInput::make('city')->label('Ciudad'),
                        TextInput::make('address')->label('Dirección'),
                    ]),

                Section::make('Contacto')
                    ->columns(2)
                    ->components([
                        TextInput::make('primary_phone')->label('Teléfono principal'),
                        TextInput::make('secondary_phone')->label('Teléfono secundario'),
                    ]),

                Section::make('Routing de correos por tipo de pago')
                    ->description('Cada remisión envía un email al buzón correspondiente según su tipo de pago, más una copia al buzón principal y al cliente.')
                    ->columns(2)
                    ->components([
                        TextInput::make('email_inbox')->label('Buzón principal')->email()->required(),
                        TextInput::make('email_cash')->label('Contado')->email()->required(),
                        TextInput::make('email_cash_for_billing')->label('Contado para facturar')->email()->required(),
                        TextInput::make('email_credit')->label('Crédito')->email()->required(),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(BrandingSettings $settings): void
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            $settings->{$key} = $value;
        }

        $settings->save();

        Notification::make()
            ->title('Configuración guardada')
            ->success()
            ->send();
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
