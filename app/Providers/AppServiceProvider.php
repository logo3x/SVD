<?php

declare(strict_types=1);

namespace App\Providers;

use App\Listeners\LogAuthActivity;
use App\Models\Client;
use App\Observers\ClientObserver;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Client::observe(ClientObserver::class);

        $this->registerRateLimiters();
        $this->registerAuthListeners();
    }

    private function registerRateLimiters(): void
    {
        // Login: 6 intentos/min por IP + email para mitigar fuerza bruta.
        RateLimiter::for('api-login', fn (Request $request) => [
            Limit::perMinute(6)->by($request->ip()),
            Limit::perMinute(6)->by((string) $request->string('email')),
        ]);

        // Endpoints leídos autenticados: 60/min por usuario o IP.
        RateLimiter::for('api', fn (Request $request) => Limit::perMinute(60)
            ->by(optional($request->user())->id ?: $request->ip()));

        // Escrituras de remisiones/firmas: más restrictivo (30/min por usuario o IP).
        RateLimiter::for('api-write', fn (Request $request) => Limit::perMinute(30)
            ->by(optional($request->user())->id ?: $request->ip()));
    }

    private function registerAuthListeners(): void
    {
        Event::listen(Login::class, [LogAuthActivity::class, 'handleLogin']);
        Event::listen(Logout::class, [LogAuthActivity::class, 'handleLogout']);
        Event::listen(Failed::class, [LogAuthActivity::class, 'handleFailed']);
        Event::listen(Lockout::class, [LogAuthActivity::class, 'handleLockout']);
    }
}
