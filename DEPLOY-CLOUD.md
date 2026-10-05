# Deploy a Laravel Cloud — SVD

Guía paso a paso para desplegar SVD en [Laravel Cloud](https://cloud.laravel.com).
Estimado: ~10 minutos desde cero hasta tener URL pública funcionando.

---

## 1 · Crear cuenta y conectar GitHub

1. Entra a https://cloud.laravel.com → **Sign up with GitHub**.
2. Autoriza el acceso a `logo3x/SVD`.
3. En el dashboard → **New Application**.

## 2 · Configurar la aplicación

| Campo | Valor |
|-------|-------|
| **Repository** | `logo3x/SVD` |
| **Branch** | `main` |
| **Region** | `us-east-1` (Virginia) — el más cercano a Colombia |
| **PHP version** | `8.3` (Laravel Cloud aún no expone 8.5 en el plan free; el código es compatible con 8.3+) |

## 3 · Recursos a aprovisionar

Laravel Cloud te pide elegir qué necesita la app. Marca:

- ✅ **MySQL 8** (database) — plan más chico (free)
- ✅ **Redis** (cache + sessions + queue) — opcional pero recomendado
- ❌ Object Storage — no necesario por ahora (las firmas viven en disco privado)

## 4 · Variables de entorno

En la sección **Environment** pega esto (los valores `${...}` los inyecta Laravel Cloud automáticamente):

```env
APP_NAME=SVD
APP_ENV=production
APP_DEBUG=false
APP_URL=${APP_URL}

LOG_CHANNEL=stack
LOG_LEVEL=warning

DB_CONNECTION=mysql
DB_HOST=${DB_HOST}
DB_PORT=${DB_PORT}
DB_DATABASE=${DB_DATABASE}
DB_USERNAME=${DB_USERNAME}
DB_PASSWORD=${DB_PASSWORD}

SESSION_DRIVER=redis
SESSION_LIFETIME=120
CACHE_STORE=redis
QUEUE_CONNECTION=database

REDIS_HOST=${REDIS_HOST}
REDIS_PORT=${REDIS_PORT}
REDIS_PASSWORD=${REDIS_PASSWORD}

MAIL_MAILER=log
MAIL_FROM_ADDRESS=no-reply@svd.example.com
MAIL_FROM_NAME=SVD

FILESYSTEM_DISK=local

# Sanctum — dominios permitidos
SANCTUM_STATEFUL_DOMAINS=${APP_DOMAIN}
SESSION_DOMAIN=${APP_DOMAIN}
```

> El `APP_KEY` Laravel Cloud lo genera solo en el primer deploy. Si no lo hace, en la consola web corre: `php artisan key:generate --force`.

## 5 · Build commands

Ya están en `cloud.yaml` en la raíz del repo, Laravel Cloud los detecta automático:

```yaml
- composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader
- npm ci
- npm run build
- php artisan storage:link
- php artisan migrate --force
- php artisan db:seed --class=DatabaseSeeder --force
- php artisan shield:generate --all --panel=admin --no-interaction
- php artisan config:cache
- php artisan route:cache
- php artisan view:cache
- php artisan event:cache
- php artisan filament:cache-components
```

## 6 · Primer deploy

1. Click **Deploy**.
2. Esperar 3-5 minutos. El log debería terminar con: `✓ Deployment complete`.
3. Laravel Cloud te asigna una URL tipo `https://svd-xxxxx.laravel.cloud`.

## 7 · Verificación post-deploy

Abre la URL en el navegador:

- `/` → landing pública con copy de SVD.
- `/admin/login` → login admin. Credenciales del seeder:
  - **Email:** `admin@svd.test`
  - **Password:** `password`
  - ⚠️ **Cambiarlas de inmediato** desde Filament → tu perfil.
- `/vendedor/login` → panel vendedor.

## 8 · Actualizar el contrato de la app móvil

Después de tener la URL definitiva:

1. Entra a `https://{tu-url}/admin/mobile-settings-page` con el SuperAdmin.
2. Pon `api_base_url = https://{tu-url}/api/v1`.
3. Guarda.
4. En local corre: `php artisan svd:contract --desktop` — regenera el MD con la URL nueva y actualiza la copia del escritorio.
5. En el repo móvil corre `bin/sync-contract.sh` para traer la versión actualizada.

## 9 · Custom domain (opcional)

Cuando tengas un dominio propio (ej. `svd.miempresa.com`):

1. En Laravel Cloud → **Domains** → Add domain.
2. Apunta el CNAME a la URL que te dé Cloud.
3. SSL se aprovisiona automático (Let's Encrypt).
4. Actualiza `APP_URL` y `SANCTUM_STATEFUL_DOMAINS` en Environment.
5. Redeploy.

## 10 · Troubleshooting

| Problema | Solución |
|----------|----------|
| `APP_KEY missing` | Consola web → `php artisan key:generate --force` → redeploy |
| `500 en /admin` | Revisar logs en Cloud → suele ser `shield:generate` que falló si no había usuarios. Correr manualmente desde la consola |
| Tablas faltantes | Consola web → `php artisan migrate --force` |
| Permisos vacíos en Filament | `php artisan shield:generate --all --panel=admin` + `php artisan shield:super-admin --user=1` |
| Storage 404 en firmas | `php artisan storage:link` |
| Queue no procesa | Verificar que `processes: 1` esté activo en `cloud.yaml` |

---

**Repo:** `https://github.com/logo3x/SVD`
**Branch:** `main`
**Comando local para regenerar contrato móvil:** `php artisan svd:contract --desktop`
