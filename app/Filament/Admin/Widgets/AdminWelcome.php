<?php

declare(strict_types=1);

namespace App\Filament\Admin\Widgets;

use App\Models\Remission;
use Filament\Widgets\Widget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class AdminWelcome extends Widget
{
    protected string $view = 'filament.admin.widgets.admin-welcome';

    protected int|string|array $columnSpan = 'full';

    /** Difiere el render para no bloquear el TTFB del dashboard. */
    protected static bool $isLazy = true;

    /** Saludo contextual según la hora local del servidor. */
    public function getGreeting(): string
    {
        $hour = (int) now()->format('H');

        return match (true) {
            $hour < 12 => 'Buenos días',
            $hour < 19 => 'Buenas tardes',
            default => 'Buenas noches',
        };
    }

    public function getUserName(): string
    {
        return Auth::user()?->name ?? '';
    }

    public function getUserInitials(): string
    {
        $name = $this->getUserName();
        $parts = preg_split('/\s+/', trim($name)) ?: [];

        return strtoupper(
            substr($parts[0] ?? '', 0, 1)
            .substr($parts[1] ?? '', 0, 1)
        );
    }

    public function getFormattedDate(): string
    {
        return Carbon::now()
            ->locale('es')
            ->isoFormat('dddd, D [de] MMMM [de] YYYY');
    }

    public function getFormattedTime(): string
    {
        return Carbon::now()->format('H:i');
    }

    public function getRoleLabel(): string
    {
        return Auth::user()?->getRoleLabel() ?? 'Usuario';
    }

    /** Cuántas remisiones se han creado hoy en TODO el sistema. */
    public function getTodayCount(): int
    {
        return $this->counts()['today'];
    }

    /** Cuántas remisiones se han creado en la última hora. */
    public function getLastHourCount(): int
    {
        return $this->counts()['last_hour'];
    }

    /**
     * Resuelve ambos contadores en una sola query agregada y los cachea
     * 60s para no golpear la BD en cada render del dashboard.
     *
     * @return array{today: int, last_hour: int}
     */
    private function counts(): array
    {
        return Cache::remember('svd.admin.welcome.counts', now()->addSeconds(60), function (): array {
            $row = Remission::query()
                ->selectRaw('count(case when issued_at >= ? then 1 end) as today', [today()])
                ->selectRaw('count(case when issued_at >= ? then 1 end) as last_hour', [now()->subHour()])
                ->first();

            return [
                'today' => (int) ($row->today ?? 0),
                'last_hour' => (int) ($row->last_hour ?? 0),
            ];
        });
    }
}
