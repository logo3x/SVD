# Deploy en Windows Server — SVD

Guía para clonar y correr SVD en un servidor **Windows** (ej. `svd.sytes.net` / `20.186.79.27`).

> Asume que tienes acceso por **Escritorio Remoto (RDP)** al servidor. Todos los comandos se ejecutan en **PowerShell como Administrador** en el servidor, no en tu PC local.

---

## 0 · Resumen de la arquitectura

```
Internet → svd.sytes.net (puerto 80/443)
            │
            ▼
   [ Apache o IIS o Nginx ]  → sirve la carpeta public/ de Laravel
            │
            ▼
   PHP 8.3+  ←→  MySQL 8 (local o remoto)
            │
   Tarea programada: php artisan queue:work (emails)
```

Tienes 3 maneras de servir Laravel en Windows. **Recomiendo WampServer o Laragon** porque traen Apache + PHP + MySQL juntos y configurados:

| Opción | Esfuerzo | Recomendado para |
|--------|----------|------------------|
| **Laragon** | Bajo | La más fácil. Apache+PHP+MySQL+virtual hosts automáticos. |
| **WampServer** | Bajo | Si ya lo conoces (lo usas en local). |
| **IIS + PHP** | Alto | Si la empresa exige IIS. Requiere configurar FastCGI + URL Rewrite. |

Esta guía usa **Laragon** (o Wamp — los pasos de Laravel son idénticos, solo cambia dónde va la carpeta).

---

## 1 · Instalar prerequisitos en el servidor

Conéctate por RDP a `20.186.79.27` y en PowerShell (Admin):

### 1.1 Git
Descarga e instala desde https://git-scm.com/download/win
Verifica: `git --version`

### 1.2 Laragon (Apache + PHP + MySQL)
Descarga **Laragon Full** desde https://laragon.org/download/
- Instálalo en `C:\laragon`.
- Trae PHP 8.x, Apache, MySQL y Composer.
- Abre Laragon → **Start All**.

> Si tu PHP en Laragon es < 8.3, ve a Laragon → Menu → PHP → Version y descarga PHP 8.3+. SVD requiere PHP 8.3 mínimo.

### 1.3 Verifica extensiones PHP
SVD necesita: `pdo_mysql`, `mbstring`, `bcmath`, `gd`, `xml`, `zip`, `intl`, `fileinfo`, `curl`, `openssl`.

```powershell
php -m
```

Laragon las trae casi todas activas. Si falta alguna, edita `C:\laragon\bin\php\php-8.3.x\php.ini` y descomenta la línea `extension=nombre`.

### 1.4 Composer
Laragon ya lo trae. Verifica: `composer --version`. Si no: https://getcomposer.org/Composer-Setup.exe

### 1.5 Node + npm (para compilar assets)
Descarga Node 20 LTS: https://nodejs.org/
Verifica: `node --version` y `npm --version`

---

## 2 · Clonar el proyecto

Laragon usa `C:\laragon\www` como raíz web (igual que `c:\wamp64\www`).

```powershell
cd C:\laragon\www
git clone https://github.com/logo3x/SVD.git svd
cd svd
```

> Si el repo es **privado**, Git pedirá tus credenciales de GitHub. Usa un **Personal Access Token** como contraseña (GitHub ya no acepta la contraseña normal). Genéralo en GitHub → Settings → Developer settings → Personal access tokens → Tokens (classic) → con scope `repo`.

---

## 3 · Instalar dependencias

```powershell
cd C:\laragon\www\svd

# Dependencias PHP de producción (sin dev)
composer install --no-dev --optimize-autoloader

# Dependencias JS y compilación de assets
npm install
npm run build
```

`npm run build` genera `public/build/` — los CSS/JS compilados. Sin esto, Filament se ve roto.

---

## 4 · Crear la base de datos

Abre **HeidiSQL** (viene con Laragon) o por consola MySQL:

```powershell
mysql -u root -e "CREATE DATABASE svd CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

> En Laragon el usuario root por defecto **no tiene contraseña**. En producción real deberías crear un usuario dedicado:
> ```sql
> CREATE USER 'svd_user'@'localhost' IDENTIFIED BY 'una-clave-fuerte';
> GRANT ALL PRIVILEGES ON svd.* TO 'svd_user'@'localhost';
> FLUSH PRIVILEGES;
> ```

---

## 5 · Configurar el `.env`

```powershell
cd C:\laragon\www\svd
Copy-Item .env.example .env
```

Edita `.env` (con Notepad o VS Code). Valores clave para este servidor:

```env
APP_NAME=SVD
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=http://svd.sytes.net

APP_LOCALE=es
APP_FALLBACK_LOCALE=es
APP_FAKER_LOCALE=es_CO

LOG_CHANNEL=single
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=svd
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=database
SESSION_LIFETIME=120
CACHE_STORE=database
QUEUE_CONNECTION=database

MAIL_MAILER=log
MAIL_FROM_ADDRESS=no-reply@svd.sytes.net
MAIL_FROM_NAME="SVD"

FILESYSTEM_DISK=local

SANCTUM_STATEFUL_DOMAINS=svd.sytes.net
SESSION_DOMAIN=svd.sytes.net
```

> Cuando tengas HTTPS (sección 9), cambia `APP_URL` a `https://...`.
> Para emails reales, cambia `MAIL_MAILER=smtp` y llena las credenciales SMTP.

Genera la clave de la app:

```powershell
php artisan key:generate
```

---

## 6 · Preparar la aplicación (migraciones, permisos, caché)

```powershell
cd C:\laragon\www\svd

# Crea las tablas
php artisan migrate --force

# Crea SuperAdmin, catálogo de productos, roles, permisos demo
php artisan db:seed --class=DatabaseSeeder --force

# Genera los permisos de Filament Shield
php artisan shield:generate --all --panel=admin --no-interaction

# Enlace simbólico de storage (en Windows necesita Admin)
php artisan storage:link

# Cachés de producción (acelera el arranque)
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
php artisan filament:cache-components
```

> **`storage:link` en Windows**: requiere PowerShell **como Administrador** (los symlinks en Windows necesitan privilegios). Si falla, actívalos en Configuración → Privacidad → Para programadores → Modo de programador.

### Credenciales iniciales (del seeder)
| Rol | Email | Password |
|-----|-------|----------|
| Super Admin | `superadmin@svd.test` | `Super/Admin?` |
| Administrador | `admin@svd.test` | `admin` |
| Vendedor | `vendedor@svd.test` | `vendedor` |

⚠️ **Cámbialas de inmediato** tras el primer login.

---

## 7 · Configurar el virtual host (Apache vía Laragon)

Laragon crea virtual hosts automáticamente, pero el document root debe apuntar a `public/`.

1. Laragon → Menu → Apache → sites-enabled → abre `auto.svd.test.conf` (o créalo).
2. Asegúrate que el `DocumentRoot` apunte a `C:\laragon\www\svd\public`.

Si Laragon no lo hace solo, crea `C:\laragon\etc\apache2\sites-enabled\svd.conf`:

```apache
<VirtualHost *:80>
    ServerName svd.sytes.net
    ServerAlias 20.186.79.27
    DocumentRoot "C:/laragon/www/svd/public"
    <Directory "C:/laragon/www/svd/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

Reinicia Apache desde Laragon (**Stop All** → **Start All**).

---

## 8 · Abrir el firewall + DNS

### 8.1 Firewall de Windows
Permite el tráfico web entrante:

```powershell
New-NetFirewallRule -DisplayName "HTTP 80"  -Direction Inbound -Protocol TCP -LocalPort 80  -Action Allow
New-NetFirewallRule -DisplayName "HTTPS 443" -Direction Inbound -Protocol TCP -LocalPort 443 -Action Allow
```

### 8.2 Azure Network Security Group (si es Azure)
La IP `20.186.79.27` parece Azure. Además del firewall de Windows, debes abrir los puertos 80 y 443 en el **NSG** de la VM desde el portal de Azure:
- Portal Azure → tu VM → Networking → Add inbound port rule → puertos 80 y 443, Allow.

### 8.3 DNS
`svd.sytes.net` (No-IP / DynDNS) debe apuntar a `20.186.79.27`. Configúralo en tu panel de No-IP.

---

## 9 · HTTPS (recomendado antes de usar en producción)

Sin HTTPS, las contraseñas viajan en texto plano. Opciones:

- **win-acme** (Let's Encrypt para Windows): https://www.win-acme.com/ — gratis, automático. Detecta el sitio de Apache y configura el certificado.
- Tras instalarlo, cambia en `.env`: `APP_URL=https://svd.sytes.net` y recachea: `php artisan config:cache`.

---

## 10 · Cola de emails (tarea programada)

SVD encola los emails. Necesitas un proceso que los procese. En Windows usa el **Programador de tareas**:

1. Abre **Task Scheduler** → Create Task.
2. General: nombre `SVD Queue Worker`, "Run whether user is logged on or not", "Run with highest privileges".
3. Triggers: At startup + Repeat (cada 1 min como respaldo).
4. Actions: Program = `C:\laragon\bin\php\php-8.3.x\php.exe`, Arguments = `artisan queue:work --tries=3 --stop-when-empty`, Start in = `C:\laragon\www\svd`.

> Alternativa simple: deja `QUEUE_CONNECTION=sync` en `.env` (los emails se envían en el mismo request, sin worker). Más lento por request pero cero configuración. Para empezar, **sync está bien**.

Para el **scheduler** de Laravel (tareas programadas), otra tarea que corra cada minuto:
- Program: el mismo `php.exe`, Arguments: `artisan schedule:run`, cada 1 minuto.

---

## 11 · Verificación final

Abre en el navegador: **http://svd.sytes.net**

- `/` → landing pública de SVD.
- `/admin/login` → panel admin (credenciales del seeder).
- `/vendedor/login` → panel vendedor.
- `/api/v1/login` → endpoint para la app móvil.

Health check: `http://svd.sytes.net/up` debe devolver 200.

### Actualizar la URL de la app móvil
Una vez funcionando:
1. Entra a `http://svd.sytes.net/admin/mobile-settings-page`.
2. Pon `api_base_url = http://svd.sytes.net/api/v1` (o https cuando lo tengas).
3. Guarda.
4. En local: `php artisan svd:contract --desktop` para regenerar el contrato con la URL real.

---

## 12 · Actualizar el código (deploys futuros)

Cuando hagas cambios y los pushees a GitHub, en el servidor:

```powershell
cd C:\laragon\www\svd
git pull
composer install --no-dev --optimize-autoloader
npm install
npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
php artisan filament:cache-components
```

O usa el script `deploy-windows.ps1` (ver sección 13).

---

## 13 · Script de deploy automatizado

En el repo hay `deploy-windows.ps1`. En el servidor, dentro de la carpeta del proyecto:

```powershell
# Primera vez (instala todo + migra + seed):
.\deploy-windows.ps1 -FirstRun

# Deploys posteriores (pull + build + migrate + cache):
.\deploy-windows.ps1
```

---

## Troubleshooting

| Problema | Solución |
|----------|----------|
| `500` en `/admin` | Revisa `storage/logs/laravel.log`. Suele faltar `php artisan migrate` o `key:generate`. |
| Filament sin estilos | Falta `npm run build`. Córrelo y `php artisan filament:cache-components`. |
| `storage:link` falla | PowerShell como Admin + Modo programador activado. |
| Firmas/imágenes 404 | `php artisan storage:link` no se ejecutó. |
| App móvil no conecta | Firewall Windows + NSG de Azure deben permitir 80/443. Verifica con `http://20.186.79.27/up`. |
| Cambié `.env` y no aplica | Corre `php artisan config:cache` de nuevo (la caché lo congela). |
| Permisos vacíos en Filament | `php artisan shield:generate --all --panel=admin` + `php artisan shield:super-admin --user=1`. |

---

**Repo:** `https://github.com/logo3x/SVD`
**Servidor:** `svd.sytes.net` / `20.186.79.27`
