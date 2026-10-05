<?php

namespace App\Filament\Admin\Pages;

use App\Enums\PaymentType;
use App\Enums\RemissionStatus;
use App\Models\Client;
use App\Models\Remission;
use App\Models\User;
use App\Services\RemissionsXlsxExporter;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Reportes extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected static ?string $navigationLabel = 'Reportes';

    protected static ?string $title = 'Reportes de Ventas';

    protected static string|\UnitEnum|null $navigationGroup = 'Ventas';

    protected static ?int $navigationSort = 8;

    protected string $view = 'filament.admin.pages.reportes';

    public ?array $data = [];

    public ?int $previewCount = null;

    public ?int $previewTotal = null;

    public function mount(): void
    {
        $this->form->fill([
            'from' => now()->startOfMonth()->toDateString(),
            'to' => now()->toDateString(),
        ]);
        $this->refreshPreview();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Filtros')
                    ->description('Todos los filtros se combinan con AND. Deja vacío lo que no quieras filtrar.')
                    ->columns(3)
                    ->components([
                        DatePicker::make('from')
                            ->label('Desde')
                            ->native(false)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn () => $this->refreshPreview()),
                        DatePicker::make('to')
                            ->label('Hasta')
                            ->native(false)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn () => $this->refreshPreview()),
                        Select::make('client_id')
                            ->label('Cliente')
                            ->options(fn () => Client::query()->orderBy('name')->pluck('name', 'id'))
                            ->searchable()
                            ->placeholder('Todos')
                            ->live()
                            ->afterStateUpdated(fn () => $this->refreshPreview()),
                        Select::make('user_id')
                            ->label('Vendedor')
                            ->options(fn () => User::query()->orderBy('name')->pluck('name', 'id'))
                            ->searchable()
                            ->placeholder('Todos')
                            ->live()
                            ->afterStateUpdated(fn () => $this->refreshPreview()),
                        Select::make('payment_type')
                            ->label('Tipo de pago')
                            ->options(collect(PaymentType::cases())->mapWithKeys(fn ($c) => [$c->value => $c->getLabel()]))
                            ->placeholder('Todos')
                            ->live()
                            ->afterStateUpdated(fn () => $this->refreshPreview()),
                        Select::make('status')
                            ->label('Estado')
                            ->options(collect(RemissionStatus::cases())->mapWithKeys(fn ($c) => [$c->value => $c->getLabel()]))
                            ->placeholder('Todos')
                            ->live()
                            ->afterStateUpdated(fn () => $this->refreshPreview()),
                    ]),
            ])
            ->statePath('data');
    }

    public function refreshPreview(): void
    {
        $query = $this->buildQuery();
        $this->previewCount = (clone $query)->count();
        $this->previewTotal = (int) (clone $query)->sum('total_amount');
    }

    public function downloadXlsx(): StreamedResponse
    {
        $count = $this->buildQuery()->count();
        if ($count === 0) {
            Notification::make()->title('Sin resultados')->warning()->send();

            return response()->streamDownload(fn () => null, 'vacio.xlsx');
        }

        return app(RemissionsXlsxExporter::class)
            ->streamDownload($this->buildQuery(), 'reporte-remisiones-'.now()->format('Ymd-His').'.xlsx');
    }

    private function buildQuery(): Builder
    {
        $data = $this->form->getRawState();

        return Remission::query()
            ->when($data['from'] ?? null, fn ($q, $d) => $q->whereDate('issued_at', '>=', $d))
            ->when($data['to'] ?? null, fn ($q, $d) => $q->whereDate('issued_at', '<=', $d))
            ->when($data['client_id'] ?? null, fn ($q, $v) => $q->where('client_id', $v))
            ->when($data['user_id'] ?? null, fn ($q, $v) => $q->where('user_id', $v))
            ->when($data['payment_type'] ?? null, fn ($q, $v) => $q->where('payment_type', $v))
            ->when($data['status'] ?? null, fn ($q, $v) => $q->where('status', $v))
            ->orderBy('issued_at', 'desc');
    }
}
