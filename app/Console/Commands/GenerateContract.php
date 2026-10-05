<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\DeliveryRoute;
use App\Enums\PaymentType;
use App\Enums\RemissionStatus;
use App\Settings\MobileSettings;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route as RouteFacade;

#[Signature('svd:contract {--output= : Ruta de salida (default: docs/api/SVD-MOBILE-CONTRACT.md)} {--copy-to= : Copia adicional a esta ruta (ej: ~/Desktop/SVD-CONTRACT.md)} {--desktop : Copia adicional al Escritorio del usuario}')]
#[Description('Genera el contrato API de la app móvil inspeccionando rutas, FormRequests, enums y settings')]
class GenerateContract extends Command
{
    public function handle(): int
    {
        $output = $this->option('output') ?: base_path('docs/api/SVD-MOBILE-CONTRACT.md');
        File::ensureDirectoryExists(dirname($output));

        $data = [
            'generated_at' => now()->toIso8601String(),
            'routes' => $this->collectApiRoutes(),
            'payment_types' => collect(PaymentType::cases())
                ->map(fn ($c) => ['value' => $c->value, 'label' => $c->getLabel()])
                ->all(),
            'delivery_routes' => collect(DeliveryRoute::cases())
                ->map(fn ($c) => ['value' => $c->value, 'label' => $c->getLabel()])
                ->all(),
            'remission_statuses' => collect(RemissionStatus::cases())
                ->map(fn ($c) => ['value' => $c->value, 'label' => $c->getLabel()])
                ->all(),
            'mobile_settings' => $this->collectMobileSettings(),
        ];

        // El hash del payload determina la versión.
        $hash = substr(hash('sha256', json_encode($data, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE)), 0, 12);
        $data['version'] = $hash;

        $markdown = view('contracts.mobile', $data)->render();
        File::put($output, $markdown);

        // Manifest JSON paralelo — útil para CI y para el endpoint /manifest.
        $manifestPath = dirname($output).'/SVD-MOBILE-CONTRACT.manifest.json';
        File::put($manifestPath, json_encode([
            'version' => $hash,
            'generated_at' => $data['generated_at'],
            'routes_count' => count($data['routes']),
            'enums' => [
                'payment_types' => count($data['payment_types']),
                'delivery_routes' => count($data['delivery_routes']),
                'remission_statuses' => count($data['remission_statuses']),
            ],
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        $size = number_format(File::size($output) / 1024, 1);

        $this->info('✓ Contrato regenerado.');
        $this->line("  Versión:   <fg=cyan>{$hash}</>");
        $this->line("  Archivo:   <fg=cyan>{$output}</> ({$size} KB)");
        $this->line("  Manifest:  <fg=cyan>{$manifestPath}</>");

        $copies = $this->resolveCopyTargets();
        foreach ($copies as $target) {
            File::ensureDirectoryExists(dirname($target));
            File::copy($output, $target);
            File::copy($manifestPath, dirname($target).DIRECTORY_SEPARATOR.basename($manifestPath));
            $this->line("  Copia:     <fg=green>{$target}</>");
        }

        return self::SUCCESS;
    }

    /**
     * @return array<int, string>
     */
    private function resolveCopyTargets(): array
    {
        $targets = [];

        if ($custom = $this->option('copy-to')) {
            $targets[] = $this->expandPath($custom);
        }

        if ($this->option('desktop')) {
            $desktop = $this->detectDesktop();
            if ($desktop) {
                $targets[] = $desktop.DIRECTORY_SEPARATOR.'SVD-MOBILE-CONTRACT.md';
            }
        }

        return $targets;
    }

    private function expandPath(string $path): string
    {
        if (str_starts_with($path, '~')) {
            $home = $_SERVER['HOME'] ?? $_SERVER['USERPROFILE'] ?? null;
            if ($home) {
                $path = $home.substr($path, 1);
            }
        }

        return $path;
    }

    private function detectDesktop(): ?string
    {
        $home = $_SERVER['USERPROFILE'] ?? $_SERVER['HOME'] ?? null;
        if (! $home) {
            return null;
        }

        foreach (['Desktop', 'Escritorio'] as $dir) {
            $candidate = $home.DIRECTORY_SEPARATOR.$dir;
            if (is_dir($candidate)) {
                return $candidate;
            }
        }

        return null;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function collectApiRoutes(): array
    {
        $routes = collect(RouteFacade::getRoutes())
            ->filter(fn (Route $r) => str_starts_with($r->uri(), 'api/v1'))
            ->map(fn (Route $r) => [
                'method' => collect($r->methods())->reject('HEAD')->first(),
                'uri' => '/'.$r->uri(),
                'action' => $r->getActionName(),
                'name' => $r->getName(),
                'middleware' => $this->extractMiddleware($r),
                'requires_auth' => in_array('auth:sanctum', $r->gatherMiddleware(), true),
            ])
            ->sortBy('uri')
            ->values()
            ->all();

        return $routes;
    }

    /**
     * @return array<int, string>
     */
    private function extractMiddleware(Route $route): array
    {
        return collect($route->gatherMiddleware())
            ->filter(fn (string $m) => str_starts_with($m, 'throttle:') || $m === 'auth:sanctum')
            ->values()
            ->all();
    }

    /**
     * @return array<string, mixed>
     */
    private function collectMobileSettings(): array
    {
        $settings = app(MobileSettings::class);

        return [
            'min_app_version' => $settings->min_app_version,
            'force_update_version' => $settings->force_update_version,
            'maintenance_mode' => $settings->maintenance_mode,
            'announcement_enabled' => $settings->announcement_enabled,
            'default_token_ttl_days' => $settings->default_token_ttl_days,
            'api_base_url' => $settings->api_base_url,
        ];
    }
}
