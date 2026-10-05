<?php

declare(strict_types=1);

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

/**
 * Configuración runtime de la app móvil de vendedores.
 *
 * La app envía el header X-App-Version en cada request y el backend
 * compara contra estas variables para forzar actualización o bloquear
 * la operación si está en mantenimiento.
 */
class MobileSettings extends Settings
{
    /** Versión mínima compatible. Por debajo → 426 Upgrade Required. */
    public string $min_app_version;

    /** Versión bajo la cual se fuerza actualización (no se permite usar la app). */
    public string $force_update_version;

    /** Si true, todas las requests API responden 503 + mensaje. */
    public bool $maintenance_mode;

    /** Mensaje mostrado a la app cuando maintenance_mode está activo. */
    public string $maintenance_message;

    /** Bandera para mostrar un banner informativo no bloqueante en la app. */
    public bool $announcement_enabled;

    /** Texto del banner informativo. */
    public string $announcement_message;

    /** TTL por defecto (días) para tokens nuevos. 0 = sin expiración. */
    public int $default_token_ttl_days;

    /** URL base que la app debe consumir (solo informativo, mostrado en el panel). */
    public string $api_base_url;

    public static function group(): string
    {
        return 'mobile';
    }
}
