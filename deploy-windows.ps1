# Deploy SVD en Windows Server
# Uso:
#   .\deploy-windows.ps1 -FirstRun    → instalación inicial (migra + seed + shield + storage:link)
#   .\deploy-windows.ps1              → deploy de actualización (pull + build + migrate + cache)
#
# Ejecutar desde la carpeta del proyecto en PowerShell (Admin para -FirstRun por storage:link).

param(
    [switch]$FirstRun
)

$ErrorActionPreference = 'Stop'

function Step($msg) { Write-Host "`n==> $msg" -ForegroundColor Cyan }

# Localiza el php.exe que usa la app (ajusta si tu ruta difiere)
$php = (Get-Command php -ErrorAction SilentlyContinue).Source
if (-not $php) { throw "No se encontró php en el PATH. Asegúrate de que Laragon/Wamp esté en el PATH." }

Step "PHP: $php"
& $php --version

if (-not $FirstRun) {
    Step "git pull"
    git pull
}

Step "composer install (producción)"
composer install --no-dev --optimize-autoloader --no-interaction

Step "npm install"
npm install

Step "npm run build (assets)"
npm run build

Step "Migraciones"
& $php artisan migrate --force

if ($FirstRun) {
    Step "Seed inicial (SuperAdmin, catálogo, roles)"
    & $php artisan db:seed --class=DatabaseSeeder --force

    Step "Permisos Filament Shield"
    & $php artisan shield:generate --all --panel=admin --no-interaction

    Step "storage:link (requiere Admin)"
    & $php artisan storage:link
}

Step "Cachés de producción"
& $php artisan config:cache
& $php artisan route:cache
& $php artisan view:cache
& $php artisan event:cache
& $php artisan filament:cache-components

Step "Listo."
Write-Host "Abre http://svd.sytes.net  (health: /up)" -ForegroundColor Green
if ($FirstRun) {
    Write-Host "Credenciales: superadmin@svd.test / Super/Admin?  — CÁMBIALAS tras el primer login." -ForegroundColor Yellow
}
