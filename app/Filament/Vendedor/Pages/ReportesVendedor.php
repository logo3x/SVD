<?php

declare(strict_types=1);

namespace App\Filament\Vendedor\Pages;

use App\Enums\PaymentType;
use App\Enums\RemissionStatus;
use App\Models\Remission;
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
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Reportes del vendedor: SOLO sus propias remisiones,
 * con filtros combinables y descarga Excel.
 */
class ReportesVendedor extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected static ?string $navigationLabel = 'Mis Reportes';

    protected static ?string $title = 'Mis Reportes';

    protected static ?int $navigationSort = 20;

    protected string $view = 'filament.vendedor.pages.reportes-vendedor';

    /** @var array<string, mixed> */
    public array $data = [];

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
                    ->description('Selecciona el rango y los filtros. Los datos se actualizan en vivo.')
                    ->columns(['default' => 1, 'md' => 4])
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
            Notification::make()->title('Sin resultados para exportar')->warning()->send();

            return response()->streamDownload(fn () => null, 'vacio.xlsx');
        }

        return app(RemissionsXlsxExporter::class)
            ->streamDownload(
                $this->buildQuery(),
                'mis-remisiones-'.now()->format('Ymd-His').'.xlsx'
            );
    }

    /**
     * SIEMPRE scoped al vendedor logueado.
     */
    private function buildQuery(): Builder
    {
        $data = $this->form->getRawState();

        return Remission::query()
            ->where('user_id', Auth::id())
            ->when($data['from'] ?? null, fn ($q, $d) => $q->whereDate('issued_at', '>=', $d))
            ->when($data['to'] ?? null, fn ($q, $d) => $q->whereDate('issued_at', '<=', $d))
            ->when($data['payment_type'] ?? null, fn ($q, $v) => $q->where('payment_type', $v))
            ->when($data['status'] ?? null, fn ($q, $v) => $q->where('status', $v))
            ->orderBy('issued_at', 'desc');
    }
}
