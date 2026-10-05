<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Models\User;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Request;

class LogAuthActivity
{
    public function handleLogin(Login $event): void
    {
        if (! $event->user instanceof User) {
            return;
        }

        activity('auth')
            ->causedBy($event->user)
            ->withProperties([
                'ip' => Request::ip(),
                'user_agent' => Request::userAgent(),
                'guard' => $event->guard,
            ])
            ->event('login')
            ->log('Inicio de sesión');
    }

    public function handleLogout(Logout $event): void
    {
        if (! $event->user instanceof User) {
            return;
        }

        activity('auth')
            ->causedBy($event->user)
            ->withProperties([
                'ip' => Request::ip(),
                'guard' => $event->guard,
            ])
            ->event('logout')
            ->log('Cierre de sesión');
    }

    public function handleFailed(Failed $event): void
    {
        activity('auth')
            ->withProperties([
                'ip' => Request::ip(),
                'user_agent' => Request::userAgent(),
                'email' => $event->credentials['email'] ?? null,
                'guard' => $event->guard,
            ])
            ->event('failed')
            ->log('Intento fallido de login');
    }

    public function handleLockout(Lockout $event): void
    {
        activity('auth')
            ->withProperties([
                'ip' => Request::ip(),
                'email' => $event->request->input('email'),
            ])
            ->event('lockout')
            ->log('Lockout por rate limit');
    }
}
