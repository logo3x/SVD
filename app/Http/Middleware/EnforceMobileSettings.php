<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Settings\MobileSettings;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Aplica los settings globales de la app móvil a cada request /api/v1.
 *
 * - Modo mantenimiento → 503 con mensaje.
 * - Header X-App-Version < force_update_version → 426 con force_update=true.
 * - Header X-App-Version < min_app_version       → 426 con force_update=false (sugerido).
 * - Si announcement_enabled, se inyecta un header X-SVD-Announcement con el texto.
 *
 * El header X-App-Version debe seguir formato semver: "1.2.0".
 * Si no se envía, no se aplica el chequeo de versión (compatible con tests).
 */
class EnforceMobileSettings
{
    public function __construct(private MobileSettings $settings) {}

    public function handle(Request $request, Closure $next): Response
    {
        if ($this->settings->maintenance_mode) {
            return $this->json([
                'message' => $this->settings->maintenance_message ?: 'Servicio en mantenimiento.',
                'maintenance' => true,
            ], 503);
        }

        $clientVersion = $request->header('X-App-Version');
        if ($clientVersion) {
            if ($this->settings->force_update_version
                && version_compare((string) $clientVersion, $this->settings->force_update_version, '<')) {
                return $this->json([
                    'message' => 'Versión obsoleta. Actualiza la aplicación para continuar.',
                    'force_update' => true,
                    'min_app_version' => $this->settings->min_app_version,
                    'force_update_version' => $this->settings->force_update_version,
                ], 426);
            }

            if ($this->settings->min_app_version
                && version_compare((string) $clientVersion, $this->settings->min_app_version, '<')) {
                return $this->json([
                    'message' => 'Hay una versión más nueva de la aplicación.',
                    'force_update' => false,
                    'min_app_version' => $this->settings->min_app_version,
                ], 426);
            }
        }

        /** @var Response $response */
        $response = $next($request);

        if ($this->settings->announcement_enabled && $this->settings->announcement_message !== '') {
            $response->headers->set('X-SVD-Announcement', rawurlencode($this->settings->announcement_message));
        }

        return $response;
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function json(array $payload, int $status): JsonResponse
    {
        return response()->json($payload, $status);
    }
}
