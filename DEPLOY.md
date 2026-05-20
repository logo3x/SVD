# Deploy — Sistema de Ventas y Despachos (SVD)

Guía rápida para desplegar SVD en producción. Dos opciones cubiertas: **Laravel Cloud** (más rápido) y **VPS Linux con Nginx + PHP-FPM + supervisor**.

## Pre-requisitos generales

- PHP 8.5+ con extensiones: `pdo_mysql`, `mbstring`, `bcmath`, `gd`, `xml`, `zip`, `intl`, `fileinfo`.
- Composer 2.x.
- Node 20+ y npm.
- MySQL 8.x o MariaDB 11+ con InnoDB.
- Acceso SMTP (o Mailgun/Postmark) para envío real de emails.

## Variables de entorno mínimas (`.env`)

```env
APP_NAME="SVD"
APP_ENV=production
APP_KEY=                              # generar con: php artisan key:generate
APP_DEBUG=false
APP_URL=https://svd.tu-dominio.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=svd
DB_USERNAME=svd_user
DB_PASSWORD=<strong-password>
DB_ENGINE=InnoDB

SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database
FILESYSTEM_DISK=local

MAIL_MAILER=smtp
MAIL_HOST=smtp.tu-proveedor.com
MAIL_PORT=587
MAIL_USERNAME=...
MAIL_PASSWORD=...
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="no-reply@tu-dominio.com"
MAIL_FROM_NAME="${APP_NAME}"

# Sanctum: dominios autorizados para SPA (no aplica si solo usas tokens móvil)
SANCTUM_STATEFUL_DOMAINS=svd.tu-dominio.com
```

## Opción A — Laravel Cloud

1. Crear proyecto desde https://cloud.laravel.com apuntando al repo `logo3x/SVD`.
2. Añadir base de datos MySQL 8 desde el panel (genera credenciales automáticamente).
3. En "Environment" pegar las variables de arriba (Laravel Cloud ya inyecta `APP_KEY` y `DB_*`).
4. En "Deploy hook" agregar (orden importa):
   ```sh
   composer install --no-dev --optimize-autoloader
   npm ci && npm run build
   php artisan migrate --force
   php artisan shield:generate --all --panel=admin --option=permissions --silent
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   php artisan filament:optimize
   ```
5. En "Workers" añadir un worker para la cola:
   ```sh
   php artisan queue:work database --sleep=3 --tries=3 --max-time=3600
   ```
6. Deploy. Verificar `/up` (healthcheck) → 200.

## Opción B — VPS Linux (Ubuntu 24.04 + Nginx)

### 1. Instalar pre-requisitos
```sh
sudo apt update && sudo apt install -y nginx mysql-server php8.5-fpm php8.5-{cli,mysql,mbstring,bcmath,xml,zip,gd,intl,fileinfo,curl} composer supervisor unzip
```

### 2. MySQL: crear base y usuario
```sql
CREATE DATABASE svd CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'svd_user'@'localhost' IDENTIFIED BY 'strong-password';
GRANT ALL PRIVILEGES ON svd.* TO 'svd_user'@'localhost';
FLUSH PRIVILEGES;
```

### 3. Clonar y configurar
```sh
cd /var/www
sudo git clone https://github.com/logo3x/SVD.git svd
sudo chown -R www-data:www-data svd
cd svd
sudo -u www-data composer install --no-dev --optimize-autoloader
sudo -u www-data cp .env.example .env
sudo -u www-data nano .env   # pegar variables de entorno
sudo -u www-data php artisan key:generate
sudo -u www-data php artisan storage:link
```

### 4. Migrar y permisos
```sh
sudo -u www-data php artisan migrate --force
sudo -u www-data php artisan db:seed --class=MasterProductCatalogSeeder --force
sudo -u www-data php artisan shield:generate --all --panel=admin --option=permissions --silent

# Crear superadmin
sudo -u www-data php artisan tinker --execute "
\$u = App\Models\User::create(['name'=>'Admin','email'=>'admin@tu-dominio.com','password'=>bcrypt('cambiar123'),'email_verified_at'=>now()]);
\$u->assignRole('super_admin');
"

sudo chmod -R 775 storage bootstrap/cache
```

### 5. Build assets
```sh
sudo -u www-data npm ci
sudo -u www-data npm run build
```

### 6. Nginx vhost (`/etc/nginx/sites-available/svd`)
```nginx
server {
    listen 80;
    server_name svd.tu-dominio.com;
    root /var/www/svd/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;
    charset utf-8;

    location / { try_files $uri $uri/ /index.php?$query_string; }
    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.5-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* { deny all; }
}
```
```sh
sudo ln -s /etc/nginx/sites-available/svd /etc/nginx/sites-enabled/
sudo nginx -t && sudo systemctl reload nginx
```

### 7. SSL con Certbot
```sh
sudo apt install -y certbot python3-certbot-nginx
sudo certbot --nginx -d svd.tu-dominio.com
```

### 8. Worker de cola con Supervisor

`/etc/supervisor/conf.d/svd-queue.conf`:
```ini
[program:svd-queue]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/svd/artisan queue:work database --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/log/svd-queue.log
stopwaitsecs=3600
```
```sh
sudo supervisorctl reread && sudo supervisorctl update && sudo supervisorctl start svd-queue:*
```

### 9. Cache optimizations (cada deploy)
```sh
sudo -u www-data php artisan config:cache
sudo -u www-data php artisan route:cache
sudo -u www-data php artisan view:cache
sudo -u www-data php artisan filament:optimize
```

## Pipeline de actualizaciones (post-merge a main)

```sh
cd /var/www/svd
sudo -u www-data git pull origin main
sudo -u www-data composer install --no-dev --optimize-autoloader
sudo -u www-data npm ci && sudo -u www-data npm run build
sudo -u www-data php artisan migrate --force
sudo -u www-data php artisan shield:generate --all --panel=admin --option=permissions --silent
sudo -u www-data php artisan optimize:clear
sudo -u www-data php artisan config:cache && sudo -u www-data php artisan route:cache && sudo -u www-data php artisan view:cache
sudo supervisorctl restart svd-queue:*
```

## Verificación post-deploy

1. `curl https://svd.tu-dominio.com/up` → 200
2. Acceso a `/admin/login` con SuperAdmin → dashboard cargado
3. Crear cliente → verifica que se vinculen los productos por defecto en `client_product`
4. Crear remisión → `php artisan queue:work --once` debe procesar el email
5. `curl -X POST https://svd.tu-dominio.com/api/v1/login -H "Accept: application/json" ...` → token devuelto

## Backups recomendados

Tres niveles:

### Manual rápido (mínimo viable)

- **DB**: `mysqldump --single-transaction svd > svd-$(date +%Y%m%d).sql` diario, retención 30 días.
- **Media**: `storage/app/private/` (firmas, contratos, logos) — rsync o snapshots de volumen.
- **`.env`**: respaldo seguro fuera del servidor (1Password, vault).

### Automatizado con `spatie/laravel-backup` (recomendado)

Instala el paquete y schedule diario:

```sh
composer require spatie/laravel-backup
php artisan vendor:publish --provider="Spatie\Backup\BackupServiceProvider"
```

Edita `config/backup.php`:

```php
'source' => [
    'files' => [
        'include' => [base_path('storage/app/private'), base_path('.env')],
    ],
    'databases' => ['mysql'],
],
'destination' => [
    'disks' => ['local', 's3'],  // configurar S3 en config/filesystems.php
],
'notifications' => [
    'mail' => ['to' => 'ops@tu-dominio.com'],
    'slack' => ['webhook_url' => env('SLACK_WEBHOOK_URL')],
],
```

Schedule diario (en `routes/console.php`):

```php
use Illuminate\Support\Facades\Schedule;

Schedule::command('backup:clean')->daily()->at('01:00');
Schedule::command('backup:run')->daily()->at('01:30');
Schedule::command('backup:monitor')->daily()->at('06:00');
```

Agrega un cron de sistema que dispare el scheduler de Laravel cada minuto:

```cron
* * * * * cd /var/www/svd && php artisan schedule:run >> /dev/null 2>&1
```

### Snapshots de servidor

Si despliegas en VPS gestionado (DigitalOcean, Hetzner, AWS Lightsail), activa snapshots diarios del volumen completo. Cubre el caso "se cayó el disco" sin necesidad de logic-level restore.

## Monitoreo

- Healthcheck: configurar UptimeRobot/Pingdom contra `/up`.
- Logs: `tail -f storage/logs/laravel.log` o configurar `LOG_CHANNEL=stack` + papertrail/loki.
- Queue lag: alertar si `php artisan queue:size` > 100 más de 10 minutos.
