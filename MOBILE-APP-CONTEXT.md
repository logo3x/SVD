# SVD — Contexto para la App Móvil (Vendedores en Campo)

> Documento de contexto para iniciar el desarrollo de la app móvil del Sistema de Ventas y Despachos (SVD).
> El backend ya está construido en `c:\wamp64\www\SVD` (Laravel 13 + Filament v5 + PHP 8.5). Esta app es **un proyecto separado** que consume su API REST.
>
> **Última actualización: 23 de mayo de 2026** — refleja el código en `main@0b64c34+`.

---

## 1. Objetivo de la App

Permitir a vendedores/repartidores registrar remisiones de venta **en terreno**, con:

- Selección de cliente y productos con precios resueltos por cliente (override de catálogo).
- Captura de firma digital del cliente.
- Confirmación + envío automático al backend, que dispara emails con PDF adjunto a los buzones internos y al cliente.
- Modo offline básico: cola local de remisiones pendientes con re-envío al recuperar conexión.

---

## 2. Stack Recomendado

| Componente | Recomendación | Motivo |
|------------|---------------|--------|
| Framework móvil | **NativePHP iOS + Android** (`nativephp/ios`, `nativephp/android`) | Reutiliza Laravel/PHP; UI con Livewire. |
| Framework alternativo | React Native / Flutter | Si NativePHP móvil presenta limitaciones; el backend no cambia. |
| UI Library | TailwindCSS + componentes nativos | Coherente con Filament del admin. |
| Autenticación | Laravel Sanctum con **personal access tokens + abilities** | Token persistido en SecureStorage local. |
| HTTP | Cliente HTTP nativo (Guzzle si NativePHP / Axios si JS) | Soporte multipart/form-data para la firma. |
| Captura firma | Canvas táctil (`<canvas>` HTML5 o equivalente nativo) | `toDataURL('image/png')` o blob para subir. |
| Storage offline | SQLite local (NativePHP/Capacitor) | Cola de remisiones pendientes. |

---

## 3. Conexión con el Backend

- **Base URL desarrollo**: `http://127.0.0.1:8000/api/v1`
- **Base URL producción**: configurable vía settings de la app, también expuesta en `/admin/mobile-settings-page` (campo `api_base_url`).
- **Content-Type**: `application/json` salvo upload de firma (`multipart/form-data`).
- **Headers obligatorios en cada request**:
  - `Accept: application/json`
  - `Authorization: Bearer {token}` (en requests autenticadas)
  - `X-App-Version: 1.2.0` (semver) — usado por el backend para enforce de versionado
- **CORS**: el backend lo permite para origin local; producción debe whitelist el dominio de la app.

---

## 4. Endpoints API (versionados en /api/v1)

### 4.1 Autenticación

#### `POST /api/v1/login`

Throttle: **6 intentos/min por IP + por email** (mitigación de fuerza bruta).

Cuerpo:
```json
{ "email": "vendedor@svd.test", "password": "vendedor", "device_name": "iPhone-15-Luis" }
```
Respuesta 200:
```json
{
  "token": "1|abc123...",
  "user": {
    "id": 2,
    "name": "Vend Test",
    "email": "vendedor@svd.test",
    "roles": ["seller"]
  }
}
```
**Token abilities**: dependen del rol del usuario. El backend asigna automáticamente:
- `super_admin` → `["*"]`
- `seller` u otros → `["remissions:read", "remissions:create", "clients:read", "products:read"]`

Errores: `422` validación (credenciales inválidas también devuelven 422 con `errors.email`).

#### `POST /api/v1/logout` (autenticado)
Revoca el token actual. Respuesta `204 No Content`.

#### `GET /api/v1/me` (autenticado)
Devuelve el usuario logueado. Útil para validar sesión al abrir la app.

---

### 4.2 Clientes

#### `GET /api/v1/clients?search=acme&page=1`
Throttle: 60/min por usuario.

Listado paginado de **clientes activos** (`is_active = true`). Búsqueda por `name` o `nit`.
Respuesta:
```json
{
  "data": [
    {
      "id": 1,
      "name": "ACME Foods",
      "nit": "900123456",
      "manager_name": "Juan Pérez",
      "whatsapp": "3001234567",
      "phone": "(311) 226 1339",
      "email": "compras@acme.test",
      "delivery_point": "external",
      "payment_type": "credit",
      "address": "Cra 50 #45-67",
      "city": "Barrancabermeja",
      "is_active": true
    }
  ],
  "links": {...},
  "meta": { "current_page": 1, "last_page": 12, "per_page": 25, "total": 297 }
}
```

#### `GET /api/v1/clients/{id}`
Detalle de un cliente. Devuelve 404 si el cliente está inactivo.

#### `GET /api/v1/clients/{id}/products`
Productos disponibles para un cliente (`client_product.is_available = true` AND `products.is_active = true`).

Respuesta:
```json
{
  "data": [
    {
      "id": 2,
      "sku": "H-5000",
      "name": "HIELO 5K",
      "description": "...",
      "category": "ice",
      "unit": "bag",
      "alias": null,
      "default_price": 8500,
      "custom_price": 7800,
      "effective_price": 7800,
      "is_available": true
    }
  ]
}
```
**Importante**: la app debe usar `effective_price` para mostrar precios y enviar como `unit_price_snapshot` al crear la remisión. Esto preserva el precio histórico aunque cambie el catálogo después.

---

### 4.3 Catálogos auxiliares

#### `GET /api/v1/payment-types`
```json
{ "data": [
  { "value": "cash", "label": "Contado" },
  { "value": "cash_for_billing", "label": "Contado para Facturar" },
  { "value": "credit", "label": "Crédito" },
  { "value": "gift", "label": "Obsequio" },
  { "value": "other", "label": "Otro" }
]}
```

#### `GET /api/v1/routes`
Devuelve `route_01` ... `route_16` con label "Ruta N".

---

### 4.4 Remisiones

#### `POST /api/v1/remissions` (autenticado)
Throttle: **30/min** por usuario.

Crea una remisión completa. Cuerpo:
```json
{
  "client_id": 1,
  "issued_at": "2026-05-20T15:30:00-05:00",
  "route": "route_03",
  "payment_type": "credit",
  "status": "confirmed",
  "observations": "Entrega segundo piso",
  "gps_lat": 7.06530000,
  "gps_lng": -73.85470000,
  "items": [
    { "product_id": 2, "quantity": 5, "unit_price_snapshot": 7800 },
    { "product_id": 6, "quantity": 12, "unit_price_snapshot": 2200 }
  ]
}
```

> **CAMBIO desde el legacy**: `gps_location: "lat,lng"` (string) **ya no existe**. Ahora son dos campos numéricos:
> - `gps_lat`: decimal(10,8) entre -90 y 90.
> - `gps_lng`: decimal(11,8) entre -180 y 180.

Reglas de validación:
- `client_id`: required, exists, cliente activo.
- `issued_at`: nullable, date (si omite → `now()`).
- `route`: required, enum DeliveryRoute (`route_01`..`route_16`).
- `payment_type`: required, enum PaymentType.
- `status`: nullable, enum RemissionStatus (default `confirmed`).
- `gps_lat`: nullable, numeric, between -90 y 90.
- `gps_lng`: nullable, numeric, between -180 y 180.
- `items`: required array min:1.
- `items.*.product_id`: required, exists, producto activo.
- `items.*.quantity`: required integer min:1.
- `items.*.unit_price_snapshot`: required integer min:0.

**El backend recalcula `subtotal = quantity * unit_price_snapshot` y `total_amount = SUM(subtotal)`** server-side. No envíes esos campos — se ignoran.

**El backend asigna `user_id` automáticamente** desde el token autenticado (no se acepta del cliente).

Respuesta 201:
```json
{
  "data": {
    "id": 124,
    "issued_at": "2026-05-20T15:30:00-05:00",
    "status": "confirmed",
    "route": { "value": "route_03", "label": "Ruta 3" },
    "payment_type": { "value": "credit", "label": "Crédito" },
    "observations": "Entrega segundo piso",
    "gps_lat": 7.0653,
    "gps_lng": -73.8547,
    "total_amount": 65400,
    "client": { "id": 1, "name": "ACME Foods", "nit": "900123456" },
    "user": { "id": 2, "name": "Vend Test" },
    "items": [
      { "product_id": 2, "sku": "H-5000", "name": "HIELO 5K", "quantity": 5, "unit_price_snapshot": 7800, "subtotal": 39000 },
      { "product_id": 6, "sku": "A-KRISS-600", "name": "KRISS 600 ML", "quantity": 12, "unit_price_snapshot": 2200, "subtotal": 26400 }
    ],
    "has_signature": false
  }
}
```

Al guardar la remisión confirmada, el backend encola automáticamente un email (`RemisionCreada`) con PDF adjunto, routeado según `payment_type` a los buzones internos configurados en `BrandingSettings` + email del cliente.

#### `POST /api/v1/remissions/{id}/signature` (autenticado, multipart)
Throttle: **30/min** por usuario.

Sube la firma del cliente. Form data:
- `signature`: file · PNG/JPG · max 2 MB · obligatorio.

Respuesta 200:
```json
{ "data": { "id": 124, ..., "has_signature": true } }
```

> **Nota sobre el campo `signature_url`**: la firma se guarda en el disco `local` (privado) del backend. Actualmente la API devuelve `has_signature: true/false`. La URL temporal de previsualización **no se expone via JSON** (se podría añadir un endpoint dedicado `GET /api/v1/remissions/{id}/signature.png` si la app lo necesita).

#### `GET /api/v1/remissions/{id}` (autenticado)
Detalle de una remisión.

#### `GET /api/v1/remissions/{id}/pdf` (autenticado)
Descarga el **comprobante PDF** de la remisión, igual al que se genera desde
el panel web (`/admin/remissions/{id}` → Imprimir PDF). Incluye el logo y
nombre de la empresa configurados en `/admin/settings`. Si no hay logo
cargado, se usa un placeholder genérico (caja con la inicial del nombre).

Respuesta:
```
HTTP/1.1 200 OK
Content-Type: application/pdf
Content-Disposition: attachment; filename="remision-000124.pdf"
```

Scope: el vendedor sólo puede descargar **sus** remisiones; `super_admin`
puede descargar cualquiera. 403 en caso contrario.

Ejemplo:
```sh
curl -OJ -H 'Authorization: Bearer {token}' -H 'X-App-Version: 1.0.0' \
  'https://svd.example.com/api/v1/remissions/124/pdf'
```

#### `GET /api/v1/remissions?from=2026-05-01&to=2026-05-20&mine=1&client_id=5` (autenticado)
Listado paginado, ordenado por `issued_at DESC`. Filtros opcionales:
- `from`, `to`: fecha (yyyy-mm-dd o ISO).
- `client_id`: filtra por un negocio/cliente específico.
- `mine=1`: sólo del vendedor logueado.

#### `GET /api/v1/remissions/export` (autenticado)
Descarga XLSX de las remisiones del vendedor logueado.

Filtros opcionales (mismos que `GET /remissions`):
- `from`, `to`: rango de fechas.
- `client_id`: filtra por un negocio/cliente específico.
- `payment_type`: `cash`, `cash_for_billing`, `credit`, `gift`, `other`.
- `status`: `draft`, `confirmed`, `cancelled`.
- `all=1`: sólo para `super_admin` — exporta de todos los vendedores.

Respuesta:
```
HTTP/1.1 200 OK
Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet
Content-Disposition: attachment; filename="mis-remisiones-20260525-160000.xlsx"
```

Throttle: 60/min (lectura). El archivo se streamea con chunks de 200 filas → soporta datasets grandes sin OOM.

Ejemplo:
```sh
curl -OJ -H 'Authorization: Bearer {token}' -H 'Accept: application/json' \
  -H 'X-App-Version: 1.0.0' \
  'https://svd.example.com/api/v1/remissions/export?from=2026-05-01&to=2026-05-31'
```

Una fila por línea de producto (SKU, cantidad, precio, subtotal) → permite tablas dinámicas y rollups por cliente/ruta en Excel.

---

## 5. Headers globales y enforcement de versión

Todas las requests bajo `/api/v1` pasan por el middleware `EnforceMobileSettings` que aplica los settings configurables desde `/admin/mobile-settings-page`. La app debe estar preparada para los siguientes escenarios:

### 5.1 Maintenance mode (503)

Si el admin activa el modo mantenimiento, **todas** las requests devuelven:
```http
HTTP/1.1 503 Service Unavailable
Content-Type: application/json

{ "message": "Plataforma en mantenimiento. Vuelve en unos minutos.", "maintenance": true }
```
La app debe mostrar el mensaje y deshabilitar la operación hasta que `503` deje de retornarse.

### 5.2 Force update (426)

Si el header `X-App-Version` es menor que `force_update_version`:
```http
HTTP/1.1 426 Upgrade Required
Content-Type: application/json

{
  "message": "Versión obsoleta. Actualiza la aplicación para continuar.",
  "force_update": true,
  "min_app_version": "1.2.0",
  "force_update_version": "1.0.0"
}
```
La app debe **bloquear toda operación** y mostrar pantalla "Actualizar ahora".

### 5.3 Soft update sugerido (426)

Si la versión está entre `force_update_version` y `min_app_version`:
```http
HTTP/1.1 426 Upgrade Required

{
  "message": "Hay una versión más nueva de la aplicación.",
  "force_update": false,
  "min_app_version": "1.2.0"
}
```
La app puede mostrar un aviso pero continuar funcionando.

### 5.4 Anuncio informativo (header en respuesta)

Si el admin activa un anuncio, todas las respuestas exitosas incluyen:
```http
X-SVD-Announcement: Nueva%20versi%C3%B3n%20disponible%20%C2%B7%20Toca%20para%20actualizar
```
(URL-encoded). La app debe leerlo y mostrar un banner no bloqueante.

---

## 6. Manejo de Errores

| Código | Significado | Acción sugerida en la app |
|--------|-------------|---------------------------|
| 401 | Token expirado, revocado por kill switch, o credenciales inválidas | Redirigir a Login, limpiar token. |
| 403 | Falta ability/permiso | Mostrar "No tienes permisos para esta acción". |
| 422 | Validación | Mostrar errores por campo (`errors.{field}[0]`). |
| 426 | Versión obsoleta | Bloquear app (force_update) o avisar (soft). |
| 429 | Rate limit excedido | Mostrar "Demasiados intentos, espera un momento". Backoff exponencial. |
| 503 | Mantenimiento o error servidor | Mostrar mensaje + reintentar más tarde. |

Formato estándar de error de Laravel:
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "client_id": ["The client id field is required."],
    "items": ["The items field is required."]
  }
}
```

Las respuestas `401` para requests autenticadas también vienen en JSON gracias al render handler en `bootstrap/app.php`:
```json
{ "message": "Unauthenticated." }
```

---

## 7. Modo Offline (recomendado)

Estructura SQLite local sugerida:
```sql
CREATE TABLE pending_remissions (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  payload TEXT NOT NULL,      -- JSON con el body de POST /remissions
  signature_blob BLOB,        -- bytes de la firma
  created_at DATETIME,
  attempts INT DEFAULT 0,
  last_error TEXT
);
```
Worker cada N segundos al recuperar conexión:
1. Tomar las pendientes ordenadas por created_at.
2. Para cada una: POST /remissions → si 201, subir signature → marcar como sincronizada → borrar de la cola.
3. Si falla con 4xx: marcar como fallida y notificar al usuario.
4. Si falla con 5xx, 429, 503 o timeout: incrementar `attempts`, backoff exponencial.

---

## 8. Seguridad

- Token Sanctum se almacena en **SecureStorage** del dispositivo (Keychain en iOS, EncryptedSharedPreferences en Android).
- Nunca persistir password.
- HTTPS obligatorio en producción.
- La firma se sube al disco `local` (privado) del backend; no es URL pública.
- Cerrar sesión revoca el token en el servidor (`POST /logout`).
- **El admin puede revocar tokens individuales o todos a la vez** desde `/admin/mobile-devices`. La app debe manejar `401` después de eso volviendo a login.

---

## 9. Módulo de gestión móvil en el panel admin

El backend incluye dos secciones nuevas bajo el grupo "App móvil" en el sidebar de `/admin`:

### 9.1 `/admin/mobile-devices` — Dispositivos conectados

Lista todos los Personal Access Tokens emitidos a usuarios. Columnas:
- # · Dispositivo (device_name) · Vendedor · Permisos (abilities) · Último uso · Emitido · Expira.

Acciones disponibles:
- **Revocar** un dispositivo individual.
- **Revocar selección** (bulk).
- **Revocar TODOS (kill switch)** — header action roja con confirmación. Cierra sesión a todos los vendedores en sus apps. Útil en emergencias (token leak, compromiso de cuenta).

Filtros: por vendedor, dispositivos activos en últimas 24h, dispositivos sin uso +30 días (cleanup).

### 9.2 `/admin/mobile-settings-page` — Configuración móvil

Form con cuatro secciones:

**Versionado de la app**:
- `min_app_version`: versión mínima recomendada (soft 426).
- `force_update_version`: versión que fuerza upgrade (hard 426).

**Modo mantenimiento**:
- Toggle `maintenance_mode` + mensaje.

**Anuncio en la app**:
- Toggle `announcement_enabled` + texto.

**Conexión**:
- `api_base_url` (solo informativo, para que el admin sepa la URL que la app usa).
- `default_token_ttl_days` (TTL por defecto al emitir tokens nuevos, 0 = sin expiración).

Stats en la cabecera de la página: dispositivos activos últimos 7 días + vendedores con app instalada.

> Los settings se persisten via `spatie/laravel-settings` en la tabla `settings` con grupo `mobile`.

---

## 10. Flujo de Pantallas Sugerido

1. **Splash**: validar token contra `/me`. Si 401 → Login. Si maintenance/426 → pantalla bloqueante.
2. **Login**: email + password + device name. Guarda token en SecureStorage.
3. **Home**: lista de clientes con búsqueda y "Crear remisión".
4. **Detalle Cliente → Nueva Remisión**:
   - Productos disponibles (lista + buscador, badge si hay `custom_price`).
   - Cantidad numérica, subtotal calculado en vivo.
   - Select de ruta + tipo de pago (pre-seleccionado del cliente).
   - Observaciones, GPS auto-capturado.
5. **Captura firma**: canvas táctil con botones "Limpiar" / "Confirmar".
6. **Preview**: vista de resumen estilo comprobante. Botones "Editar" / "Enviar".
7. **Envío**:
   - POST `/remissions` con body JSON.
   - Si OK: POST `/remissions/{id}/signature` con imagen.
   - Notificar éxito.
   - Si offline: guardar en cola SQLite local y reintentar.
8. **Historial**: GET `/remissions?mine=1`.

---

## 11. Credenciales de Prueba

El `DatabaseSeeder` provisiona dos usuarios al hacer `migrate:fresh --seed`:

| Email | Password | Rol | Panel |
|-------|----------|-----|-------|
| `superadmin@svd.test` | `Super/Admin?` | super_admin | `/admin` (acceso total) |
| `vendedor@svd.test` | `vendedor` | seller | `/vendedor` (restringido) |

Para crear más vendedores:
```sh
php artisan tinker --execute "App\Models\User::create(['name' => 'Vend 2', 'email' => 'vend2@svd.test', 'password' => bcrypt('xxx'), 'email_verified_at' => now()])->assignRole('seller');"
```

---

## 12. Comandos Útiles en el Backend

```sh
# Iniciar el servidor
php artisan serve

# Procesar la cola de emails (necesario para que el envío de comprobantes funcione)
php artisan queue:work

# Migrar + seed limpio (incluye catálogo maestro + usuarios + permisos)
php artisan migrate:fresh --seed
php artisan shield:generate --all --panel=admin --option=permissions
php artisan shield:generate --all --panel=vendedor --option=permissions
php artisan db:seed --force   # reasigna permisos al rol seller después de Shield

# Listar rutas API
php artisan route:list --path=api

# Inspeccionar logs
php artisan pail

# Activar/desactivar maintenance mode desde la línea de comandos
php artisan tinker --execute "app(App\Settings\MobileSettings::class)->fill(['maintenance_mode' => true])->save();"
```

---

## 13. Archivos del Backend que la App Necesita Conocer

| Concepto | Archivo backend |
|----------|----------------|
| Modelo Cliente | `app/Models/Client.php` |
| Modelo Producto | `app/Models/Product.php` con `priceFor($client)` |
| Modelo Remisión | `app/Models/Remission.php` con `gps_lat` / `gps_lng` |
| Pivot precios | tabla `client_product` con `custom_price`, `custom_alias`, `is_available` |
| Pivot líneas | tabla `remission_product` con `quantity`, `unit_price_snapshot`, `subtotal` |
| Enums | `app/Enums/{PaymentType,DeliveryRoute,RemissionStatus}.php` |
| Routing email | `app/Services/RemissionEmailRouter.php` (se dispara automáticamente al crear remisión) |
| PDF | `app/Services/RemissionInvoicePdf.php` |
| API Auth | `app/Http/Controllers/Api/V1/AuthController.php` |
| API Remission | `app/Http/Controllers/Api/V1/RemissionController.php` |
| Middleware versión | `app/Http/Middleware/EnforceMobileSettings.php` |
| Settings móvil | `app/Settings/MobileSettings.php` |
| Modelo dispositivos | `app/Models/MobileDevice.php` (alias de `PersonalAccessToken`) |

---

## 14. Rate Limits resumidos

| Endpoint | Limit | Scope |
|----------|-------|-------|
| `POST /login` | 6/min | IP + email |
| `GET /me`, `/clients*`, `/products*`, `/payment-types`, `/routes`, `/remissions` | 60/min | usuario o IP |
| `POST /remissions`, `POST /remissions/{id}/signature` | 30/min | usuario o IP |

---

## 15. Modo Administrador en la App Móvil

> Sección añadida el 25 de mayo de 2026 — refleja `main@0da58df+` con el rol `admin` agregado.

### 15.1 Roles del sistema (en español)

El backend maneja **3 roles** (los slugs internos son inglés pero los labels para UI van en español):

| Slug (interno) | Label en UI (español) | Acceso al panel `/admin` | Acceso al panel `/vendedor` | Acceso a la app móvil | Abilities del token |
|----------------|------------------------|--------------------------|------------------------------|------------------------|---------------------|
| `super_admin` | Super Administrador | ✅ bypass total | ✅ | ✅ con permisos totales | `['*']` |
| `admin` | Administrador | ✅ todos los permisos | ✅ | ✅ con permisos totales | `['*']` |
| `seller` | Vendedor | ❌ | ✅ | ✅ con permisos limitados | `['remissions:read','remissions:create','clients:read','products:read']` |

El payload de `/api/v1/login` y `/api/v1/me` ahora incluye:

```json
{
  "id": 3,
  "name": "Administrador",
  "email": "admin@svd.test",
  "roles": ["admin"],
  "role_label": "Administrador",
  "is_admin": true
}
```

**La app móvil debe basarse en `is_admin: true`** para decidir si muestra las pantallas administrativas (más abajo). El campo `roles` queda como detalle adicional. El `role_label` es lo que mostrar en pantalla (saludo, perfil, badge).

### 15.2 Credenciales de prueba

| Rol | Email | Password | `is_admin` | Puede entrar a app móvil |
|-----|-------|----------|------------|---------------------------|
| Super Administrador | `superadmin@svd.test` | `Super/Admin?` | true | ✅ |
| Administrador | `admin@svd.test` | `admin` | true | ✅ |
| Vendedor | `vendedor@svd.test` | `vendedor` | false | ✅ con permisos limitados |

### 15.3 Pantallas que la app móvil debe renderizar según `is_admin`

```
si is_admin === true:
  TabBar mostrar:
    1. Inicio (dashboard global con stats agregados)
    2. Remisiones (TODAS, filtrables por vendedor, cliente, fecha)
    3. Clientes (lista + crear/editar)
    4. Productos (catálogo maestro + precios por cliente)
    5. Vendedores (lista de usuarios con rol seller)
    6. Reportes (export XLSX con flag all=1)
    7. Configuración (mobile_settings, branding readonly)

si is_admin === false (vendedor):
  TabBar mostrar:
    1. Mis Remisiones (solo las propias)
    2. Nueva Remisión
    3. Mis Reportes (export XLSX solo mías)
    4. Mi Perfil
```

### 15.4 Endpoints administrativos (mismo `/api/v1`, scoping automático por token ability `*`)

Los endpoints ya existen, lo único que cambia es el **scope**: cuando el token tiene ability `*` (admin o super_admin) y se pasa el flag `?all=1` donde aplica, devuelve registros de todos los vendedores. Sin `all=1`, devuelve solo los del usuario autenticado.

#### Remisiones — listar todas

```
GET /api/v1/remissions?all=1&from=2026-05-01&to=2026-05-31&payment_type=credit&user_id=3
Authorization: Bearer {admin_token}
```

Filtros disponibles:
- `from`, `to` (YYYY-MM-DD)
- `client_id` (int)
- `user_id` (int) — solo si admin, filtra por vendedor
- `payment_type` (enum)
- `status` (enum)

Respuesta paginada estándar (25/pp). Incluye `client` y `user` (vendedor).

#### Remisiones — descargar PDF de cualquiera

```
GET /api/v1/remissions/{id}/pdf
Authorization: Bearer {admin_token}
```

Admin/super_admin tienen acceso a CUALQUIER remisión. Vendedor solo a las propias (403 si no).

#### Remisiones — exportar XLSX (todas o por filtros)

```
GET /api/v1/remissions/export?all=1&from=2026-05-01&to=2026-05-31
Authorization: Bearer {admin_token}
```

Devuelve un stream binario `.xlsx`. Sin `all=1`, solo del usuario actual.

#### Clientes — listar + crear + editar

```
GET    /api/v1/clients              # ya existe, paginado
GET    /api/v1/clients/{id}/products # ya existe, lista de productos con precios resueltos
```

> **Pendiente backend** (no implementado aún, lo iremos agregando si la app móvil lo necesita):
>
> ```
> POST   /api/v1/clients              # crear cliente — admin only
> PUT    /api/v1/clients/{id}         # editar cliente — admin only
> DELETE /api/v1/clients/{id}         # baja lógica — admin only
> ```

#### Productos — catálogo maestro

```
GET    /api/v1/products             # listado del catálogo (incluye default_price, is_active)
```

> **Pendiente backend**:
>
> ```
> POST   /api/v1/products             # crear producto — admin only
> PUT    /api/v1/products/{id}        # editar producto/precio maestro — admin only
> PUT    /api/v1/clients/{client}/products/{product}  # override precio para cliente — admin only
> ```

#### Vendedores — lista para filtros

> **Pendiente backend**:
>
> ```
> GET    /api/v1/admin/users?role=seller   # lista de vendedores con stats básicos
> ```

#### Dashboard admin — stats globales

> **Pendiente backend**:
>
> ```
> GET    /api/v1/admin/dashboard
> ```
> Respuesta sugerida:
> ```json
> {
>   "ventas_hoy": 1250000,
>   "ventas_mes": 38400000,
>   "remisiones_hoy": 17,
>   "remisiones_mes": 412,
>   "top_clientes": [...],
>   "top_productos": [...],
>   "vendedores_activos_hoy": 4
> }
> ```

#### Mobile Settings — leer (lectura para todos los usuarios)

```
GET /api/v1/mobile-settings
```

Devuelve los valores actuales para que la app móvil los respete (versión mínima, mantenimiento, anuncio, TTL token).

> Editar settings se hace desde el panel web `/admin/mobile-settings-page` — no hay endpoint API para escritura por ahora (decisión consciente: evitar que un token comprometido cambie la configuración global).

### 15.5 Diferenciación visual recomendada en la app móvil

| Elemento | Vendedor | Administrador |
|----------|----------|---------------|
| Color del header | Azul institucional | Granate / morado |
| Badge en avatar | "Vendedor" | "Administrador" o "Super Administrador" |
| Saludo de bienvenida | "Hola, Pedro" | "Hola, Pedro · Administrador" |
| Acceso a "mis" vs "todas" | Solo "mis" | Toggle "Mis" / "Todas" en listas |

### 15.6 Cambio de contraseña + impersonación

- **Cambio de contraseña**: pendiente endpoint `PUT /api/v1/me/password` con `current_password` + `new_password`.
- **Impersonación**: el botón "Iniciar sesión como" solo existe en el panel web `/admin/users`. No tiene sentido en móvil — un admin que quiera entrar como vendedor usa las credenciales del vendedor o la web.

### 15.7 Auditoría de acciones admin

Toda acción administrativa (editar cliente, cambiar precio maestro, etc.) queda en `activity_log` con `causer_id = admin_user.id`. La app móvil no necesita hacer nada especial — el log se llena automático en el backend porque los modelos usan el trait `LogsActivity`.

### 15.8 Sobre el chequeo `isAdmin()` en el backend

Internamente, todos los controllers usan `$user->isAdmin()` (definido en `app/Models/User.php`) que devuelve `true` para `super_admin` Y `admin`. Esto evita tener que repetir `hasAnyRole(['super_admin','admin'])` en cada chequeo y deja un solo punto de cambio si añades nuevos roles administrativos.

---

**Fin del contexto. Cualquier asistente o desarrollador puede leer este archivo y empezar a construir la app móvil sin más preguntas — todos los endpoints, payloads, validaciones, headers, flujos, módulos del panel admin y el modo administrador móvil están aquí.**
