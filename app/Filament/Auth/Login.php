<?php

declare(strict_types=1);

namespace App\Filament\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Facades\Filament;

class Login extends BaseLogin
{
    protected string $view = 'filament.auth.login';

    protected static string $layout = 'layouts.svd-auth';

    /**
     * Texto contextual mostrado en el masthead izquierdo.
     */
    public function getPanelLabel(): string
    {
        return match (Filament::getCurrentPanel()?->getId()) {
            'admin' => 'Panel de administración',
            'vendedor' => 'Panel del vendedor',
            default => 'Acceso',
        };
    }

    /**
     * Bajada editorial corta para cada panel.
     */
    public function getPanelTagline(): string
    {
        return match (Filament::getCurrentPanel()?->getId()) {
            'admin' => 'Catálogo, clientes, remisiones y reportes.',
            'vendedor' => 'Levanta pedidos en campo. Firma. Envía.',
            default => '',
        };
    }

    /**
     * Color de acento por panel (clave CSS variable de la paleta SVD).
     */
    public function getPanelAccent(): string
    {
        return match (Filament::getCurrentPanel()?->getId()) {
            'vendedor' => 'var(--color-mint)',
            default => 'var(--color-terra)',
        };
    }

    /**
     * Etiqueta corta tipo "MOD" usada arriba del masthead.
     */
    public function getPanelModuleTag(): string
    {
        return match (Filament::getCurrentPanel()?->getId()) {
            'admin' => 'MOD·ADMIN',
            'vendedor' => 'MOD·VENDEDOR',
            default => 'MOD·AUTH',
        };
    }
}
