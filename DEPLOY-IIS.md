# Deploy de SVD en IIS (Windows Server)

Proyecto en `C:\inetpub\wwwroot\SVD\SVD`. Esta guía configura IIS para servir Laravel.

IIS necesita 3 piezas: **PHP vía FastCGI**, **URL Rewrite**, y el sitio apuntando a `public/`.

---

## 1 · Instalar PHP en IIS

### 1.1 Descargar PHP (Non-Thread-Safe, x64)
SVD requiere **PHP 8.3+ NTS**. Descarga el ZIP "Non Thread Safe" desde:
https://windows.php.net/download/ (ej. `php-8.3.x-nts-Win32-vs16-x64.zip`)

Descomprime en `C:\PHP`.

### 1.2 Configurar php.ini
```powershell
Copy-Item C:\PHP\php.ini-production C:\PHP\php.ini
notepad C:\PHP\php.ini
```

Descomenta (quita el `;`) estas extensiones — SVD las necesita:
```ini
extension_dir = "ext"
extension=curl
extension=fileinfo
extension=gd
extension=intl
extension=mbstring
extension=openssl
extension=pdo_mysql
extension=zip
extension=bcmath
extension=exif
```
Y ajusta:
```ini
cgi.fix_pathinfo=1
upload_max_filesize=20M
post_max_size=20M
```

### 1.3 Verificar
```powershell
C:\PHP\php.exe -v
C:\PHP\php.exe -m
```

---

## 2 · Instalar componentes de IIS

### 2.1 CGI (para FastCGI)
```powershell
Enable-WindowsOptionalFeature -Online -FeatureName IIS-CGI -All
```

### 2.2 URL Rewrite
Descarga e instala desde: https://www.iis.net/downloads/microsoft/url-rewrite
(Sin esto, todas las rutas de Laravel dan 404/500.)

### 2.3 Conectar PHP a IIS (FastCGI)
Abre **IIS Manager** → servidor (nodo raíz) → **FastCGI Settings** → Add Application:
- Full Path: `C:\PHP\php-cgi.exe`
- Luego: servidor → **Handler Mappings** → Add Module Mapping:
  - Request path: `*.php`
  - Module: `FastCgiModule`
  - Executable: `C:\PHP\php-cgi.exe`
  - Name: `PHP_via_FastCGI`

---

## 3 · Apuntar el sitio a public/

**Este es el punto más importante** y la causa #1 del error 500.

IIS Manager → tu sitio (o crea uno nuevo "SVD"):
- **Physical path**: `C:\inetpub\wwwroot\SVD\SVD\public`  ← debe terminar en `\public`
- Binding: host name `svd.sytes.net`, puerto 80.

> Si el sitio apunta a `...\SVD\SVD` (sin `\public`), Laravel no arranca → 500.

El archivo `public/web.config` (ya está en el repo) maneja el URL Rewrite hacia `index.php`.

---

## 4 · Permisos de carpetas

IIS corre como `IIS_IUSRS` / `IUSR`. Necesita escribir en `storage` y `bootstrap/cache`:

```powershell
icacls "C:\inetpub\wwwroot\SVD\SVD\storage" /grant "IIS_IUSRS:(OI)(CI)F" /T
icacls "C:\inetpub\wwwroot\SVD\SVD\bootstrap\cache" /grant "IIS_IUSRS:(OI)(CI)F" /T
```

---

## 5 · Verificar la app Laravel

```powershell
cd C:\inetpub\wwwroot\SVD\SVD

# Si cambiaste algo en .env, limpia y recachea
C:\PHP\php.exe artisan optimize:clear
C:\PHP\php.exe artisan config:cache

# Diagnóstico
C:\PHP\php.exe artisan about
```

---

## 6 · storage:link en IIS

```powershell
cd C:\inetpub\wwwroot\SVD\SVD
C:\PHP\php.exe artisan storage:link
```
Crea el symlink `public/storage` → `storage/app/public`. Requiere PowerShell Admin.

---

## 7 · Firewall + Azure NSG

```powershell
New-NetFirewallRule -DisplayName "HTTP 80"  -Direction Inbound -Protocol TCP -LocalPort 80  -Action Allow
New-NetFirewallRule -DisplayName "HTTPS 443" -Direction Inbound -Protocol TCP -LocalPort 443 -Action Allow
```
Si es Azure: abre también 80/443 en el **NSG** de la VM desde el portal Azure.

---

## 8 · Diagnóstico del error 500

```powershell
# Activa errores detallados temporalmente
cd C:\inetpub\wwwroot\SVD\SVD
notepad .env          # pon APP_DEBUG=true
C:\PHP\php.exe artisan config:cache

# Revisa el log de Laravel
Get-Content storage\logs\laravel.log -Tail 40
```

Recarga la página → verás el error real. **Devuelve APP_DEBUG=false** al terminar.

| Síntoma | Causa | Fix |
|---------|-------|-----|
| 500 en `/` y `/admin` | Sitio no apunta a `public/` | Sección 3 |
| 404 en rutas pero `/` ok | Falta URL Rewrite | Sección 2.2 + `web.config` |
| "No application encryption key" | Falta APP_KEY | `php artisan key:generate` |
| "could not find driver" | Falta `pdo_mysql` | Sección 1.2 |
| "Permission denied" en storage | Permisos IIS | Sección 4 |
| Página en blanco / php no ejecuta | FastCGI mal configurado | Sección 2.3 |

---

## 9 · App móvil

Una vez funcione `http://svd.sytes.net`:
1. `http://svd.sytes.net/admin/mobile-settings-page` → `api_base_url = http://svd.sytes.net/api/v1` → Guardar.
2. Verifica `http://svd.sytes.net/up` → 200.

---

**Proyecto:** `C:\inetpub\wwwroot\SVD\SVD` · **Sitio IIS apunta a:** `...\SVD\SVD\public`
