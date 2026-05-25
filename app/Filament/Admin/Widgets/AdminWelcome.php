<?php

declare(strict_types=1);

namespace App\Filament\Admin\Widgets;

use App\Models\Remission;
use Filament\Widgets\Widget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class AdminWelcome extends Widget
{
    protected string $view = 'filament.admin.widgets.admin-welcome';

    protected int|string|array $columnSpan = 'full';

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
        $user = Auth::user();
        $role = $user?->getRoleNames()->first();

        return match ($role) {
            'super_admin' => 'Administrador',
            'seller' => 'Vendedor',
            default => $role ? ucfirst((string) $role) : 'Usuario',
        };
    }

    /** Cuántas remisiones se han creado hoy en TODO el sistema. */
    public function getTodayCount(): int
    {
        return Remission::whereDate('issued_at', today())->count();
    }

    /** Cuántas remisiones se han creado en la última hora. */
    public function getLastHourCount(): int
    {
        return Remission::where('issued_at', '>=', now()->subHour())->count();
    }
}
