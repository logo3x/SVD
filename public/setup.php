<?php

use Illuminate\Contracts\Console\Kernel;
use Symfony\Component\Console\Output\BufferedOutput;

/**
 * Asistente de instalación/despliegue para hosting sin terminal (cPanel).
 *
 * Acceso: https://tu-dominio/setup.php?token=EL_VALOR_DE_SETUP_TOKEN
 * Se desactiva dejando SETUP_TOKEN vacío (o borrándolo) en el archivo .env.
 * Solo ejecuta una lista cerrada de comandos artisan; nunca comandos libres.
 */
$basePath = dirname(__DIR__);
$envPath = $basePath.'/.env';

@set_time_limit(600);
ignore_user_abort(true);

/**
 * Lee una variable del .env sin arrancar Laravel.
 */
function readEnvValue(string $envPath, string $key): ?string
{
    if (! is_file($envPath)) {
        return null;
    }

    foreach (file($envPath, FILE_IGNORE_NEW_LINES) as $line) {
        if (preg_match('/^\s*'.preg_quote($key, '/').'\s*=\s*(.*)\s*$/', $line, $matches)) {
            return trim($matches[1], " \t\"'");
        }
    }

    return null;
}

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

$expectedToken = (string) readEnvValue($envPath, 'SETUP_TOKEN');
$givenToken = (string) ($_POST['token'] ?? $_GET['token'] ?? '');

if (! is_file($envPath)) {
    http_response_code(503);
    exit('Falta el archivo .env. Cópialo desde .env.cpanel.example con el Administrador de archivos de cPanel y define SETUP_TOKEN.');
}

if (strlen($expectedToken) < 16) {
    http_response_code(403);
    exit('Setup desactivado. Define SETUP_TOKEN (mínimo 16 caracteres) en .env para habilitarlo.');
}

if (! hash_equals($expectedToken, $givenToken)) {
    http_response_code(403);
    exit('Token inválido.');
}

/**
 * Acciones permitidas: clave => [etiqueta, descripción, lista de [comando, parámetros]].
 *
 * @var array<string, array{0: string, 1: string, 2: list<array{0: string, 1: array<string, mixed>}>}> $actions
 */
$actions = [
    'install' => [
        'Instalación inicial completa',
        'Genera APP_KEY (si falta), migra, siembra datos base, crea enlace storage y optimiza. Ejecutar UNA sola vez.',
        [
            ['migrate', ['--force' => true]],
            ['db:seed', ['--force' => true]],
            ['storage:link', []],
            ['optimize:clear', []],
            ['optimize', []],
            ['filament:optimize', []],
        ],
    ],
    'deploy' => [
        'Después de actualizar desde Git',
        'Migra cambios nuevos y regenera cachés. Úsalo tras cada "Update from Remote".',
        [
            ['migrate', ['--force' => true]],
            ['optimize:clear', []],
            ['optimize', []],
            ['filament:optimize', []],
        ],
    ],
    'key' => ['Generar APP_KEY', 'Solo si APP_KEY está vacío. Cambiarla invalida sesiones y datos cifrados.', [['key:generate', ['--force' => true]]]],
    'migrate' => ['Migrar base de datos', 'php artisan migrate --force', [['migrate', ['--force' => true]]]],
    'seed' => ['Sembrar datos base', 'Roles, permisos, usuarios iniciales y catálogo.', [['db:seed', ['--force' => true]]]],
    'shield' => ['Regenerar permisos', 'shield:generate para el panel admin.', [['shield:generate', ['--all' => true, '--panel' => 'admin', '--no-interaction' => true]]]],
    'storage' => ['Enlace de storage', 'php artisan storage:link (necesario para imágenes subidas).', [['storage:link', []]]],
    'optimize' => ['Optimizar (cachés)', 'config, rutas, vistas, eventos y componentes Filament.', [['optimize', []], ['filament:optimize', []]]],
    'clear' => ['Limpiar cachés', 'Úsalo después de editar el .env.', [['optimize:clear', []], ['filament:optimize-clear', []]]],
    'status' => ['Estado de migraciones', 'php artisan migrate:status', [['migrate:status', []]]],
];

/**
 * @return array<int, array{0: string, 1: bool, 2: string}>
 */
function runChecks(string $basePath, string $envPath): array
{
    $checks = [];
    $checks[] = ['PHP >= 8.4', version_compare(PHP_VERSION, '8.4.0', '>='), PHP_VERSION];

    foreach (['bcmath', 'ctype', 'fileinfo', 'gd', 'intl', 'mbstring', 'openssl', 'pdo_mysql', 'tokenizer', 'xml', 'zip'] as $extension) {
        $checks[] = ["Extensión $extension", extension_loaded($extension), extension_loaded($extension) ? 'OK' : 'Falta: actívala en "Select PHP Version"'];
    }

    $checks[] = ['vendor/ presente', is_file($basePath.'/vendor/autoload.php'), 'Si falta, clona la rama cpanel (no main)'];
    $checks[] = ['Assets compilados', is_file($basePath.'/public/build/manifest.json'), 'public/build/manifest.json'];

    foreach (['storage', 'storage/framework/cache', 'storage/framework/sessions', 'storage/framework/views', 'storage/logs', 'bootstrap/cache'] as $directory) {
        $checks[] = ["Escribible: $directory", is_writable($basePath.'/'.$directory), 'Permisos 755/775'];
    }

    $checks[] = ['.env escribible', is_writable($envPath), 'Necesario para generar APP_KEY'];
    $checks[] = ['APP_KEY definido', (string) readEnvValue($envPath, 'APP_KEY') !== '', 'Usa "Generar APP_KEY"'];
    $checks[] = ['APP_DEBUG=false', strtolower((string) readEnvValue($envPath, 'APP_DEBUG')) === 'false', 'En producción debe ser false'];
    $checks[] = ['Enlace public/storage', is_link($basePath.'/public/storage') || is_dir($basePath.'/public/storage'), 'Usa "Enlace de storage"'];

    return $checks;
}

$output = '';
$selected = $_POST['action'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && is_string($selected) && isset($actions[$selected])) {
    if (! is_file($basePath.'/vendor/autoload.php')) {
        $output = 'No existe vendor/autoload.php. Clona la rama "cpanel" del repositorio.';
    } else {
        try {
            require $basePath.'/vendor/autoload.php';
            $app = require $basePath.'/bootstrap/app.php';
            $kernel = $app->make(Kernel::class);
            $kernel->bootstrap();

            $commands = $actions[$selected][2];

            if ($selected === 'install' && (string) readEnvValue($envPath, 'APP_KEY') === '') {
                array_unshift($commands, ['key:generate', ['--force' => true]]);
            }

            foreach ($commands as [$command, $parameters]) {
                $buffer = new BufferedOutput;
                $output .= "$ php artisan $command\n";

                try {
                    $exitCode = $kernel->call($command, $parameters, $buffer);
                    $output .= $buffer->fetch()."→ código de salida: $exitCode\n\n";
                } catch (Throwable $exception) {
                    $output .= $buffer->fetch().'ERROR: '.$exception->getMessage()."\n\n";

                    if ($command !== 'storage:link') {
                        break;
                    }
                }
            }
        } catch (Throwable $exception) {
            $output .= 'ERROR al arrancar Laravel: '.$exception->getMessage()."\n".$exception->getFile().':'.$exception->getLine();
        }
    }
}

$checks = runChecks($basePath, $envPath);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Setup SVD</title>
    <style>
        body { font-family: system-ui, sans-serif; max-width: 960px; margin: 2rem auto; padding: 0 1rem; color: #1f2937; }
        h1 { margin-bottom: .25rem; }
        table { width: 100%; border-collapse: collapse; margin: 1rem 0 2rem; font-size: .9rem; }
        td { padding: .35rem .5rem; border-bottom: 1px solid #e5e7eb; }
        .ok { color: #047857; font-weight: 600; }
        .fail { color: #b91c1c; font-weight: 600; }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: .75rem; }
        form { border: 1px solid #e5e7eb; border-radius: .5rem; padding: .75rem; }
        form.primary { border-color: #2563eb; background: #eff6ff; }
        button { cursor: pointer; padding: .5rem .9rem; border: 0; border-radius: .375rem; background: #2563eb; color: #fff; font-weight: 600; }
        p.small { font-size: .8rem; color: #6b7280; margin: .4rem 0 .6rem; }
        pre { background: #111827; color: #e5e7eb; padding: 1rem; border-radius: .5rem; overflow-x: auto; white-space: pre-wrap; }
        .warn { background: #fef3c7; padding: .75rem; border-radius: .5rem; font-size: .9rem; }
    </style>
</head>
<body>
    <h1>Setup SVD</h1>
    <p class="warn">Al terminar, deja <code>SETUP_TOKEN=</code> vacío en el .env para desactivar esta página.</p>

    <?php if ($output !== '') { ?>
        <h2>Resultado: <?= e($actions[$selected][0]) ?></h2>
        <pre><?= e($output) ?></pre>
    <?php } ?>

    <h2>Acciones</h2>
    <div class="grid">
        <?php foreach ($actions as $key => [$label, $description]) { ?>
            <form method="post" class="<?= in_array($key, ['install', 'deploy'], true) ? 'primary' : '' ?>"
                  onsubmit="return <?= $key === 'key' || $key === 'install' ? "confirm('¿Seguro? Ejecuta esto solo en la instalación inicial.')" : 'true' ?>">
                <input type="hidden" name="token" value="<?= e($givenToken) ?>">
                <input type="hidden" name="action" value="<?= e($key) ?>">
                <strong><?= e($label) ?></strong>
                <p class="small"><?= e($description) ?></p>
                <button type="submit">Ejecutar</button>
            </form>
        <?php } ?>
    </div>

    <h2>Diagnóstico</h2>
    <table>
        <?php foreach ($checks as [$label, $passed, $detail]) { ?>
            <tr>
                <td><?= e($label) ?></td>
                <td class="<?= $passed ? 'ok' : 'fail' ?>"><?= $passed ? '✔' : '✘' ?></td>
                <td><?= e($detail) ?></td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>
