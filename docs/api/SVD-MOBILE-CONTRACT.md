# SVD · Contrato API para la App Móvil

> **⚙️ Este archivo se genera automáticamente.** No lo edites a mano.
> Se regenera al correr `php artisan svd:contract` en el backend.

| Campo | Valor |
|-------|-------|
| **Versión** | `ec882d1f078c` |
| **Generado** | `2026-05-25T20:47:18+00:00` |
| **Base URL (prod)** | `https://svd.example.com/api/v1` |

---

## 1 · Cómo sincronizar este archivo con la app móvil

El contrato se mantiene como un **archivo compartido** entre los dos proyectos. No hay endpoint HTTP: el backend genera el archivo en disco y la app móvil lo lee.

### Opción A — Carpeta compartida (recomendada)

Cuando se regenera el contrato en el backend, también se copia automáticamente al escritorio:

```sh
# En el repo del backend (este proyecto):
php artisan svd:contract --desktop
```

Esto deja `SVD-MOBILE-CONTRACT.md` y `SVD-MOBILE-CONTRACT.manifest.json` en el escritorio del usuario actual.

En la app móvil, agregar un script `bin/sync-contract.sh` que copie desde el escritorio al repo móvil antes de cada build:

```sh
cp "$USERPROFILE/Desktop/SVD-MOBILE-CONTRACT.md"          docs/SVD-CONTRACT.md
cp "$USERPROFILE/Desktop/SVD-MOBILE-CONTRACT.manifest.json" docs/SVD-CONTRACT.manifest.json
```

### Opción B — Ruta directa

```sh
# Desde el backend, copia al repo de la app móvil:
php artisan svd:contract --copy-to=C:/wamp64/www/svd-mobile/docs/SVD-CONTRACT.md
```

### Opción C — Lectura desde el repo backend

Si los dos proyectos están en la misma máquina, la app móvil puede leer directo:

```
c:/wamp64/www/SVD/docs/api/SVD-MOBILE-CONTRACT.md
c:/wamp64/www/SVD/docs/api/SVD-MOBILE-CONTRACT.manifest.json
```

### Verificar si el contrato cambió

El campo `version` del manifest cambia solo cuando algo del API cambia. La app móvil debería comparar la `version` actual con la última que vio y, si difiere, regenerar sus clientes HTTP o avisar al equipo.

---

## 2 · Autenticación

Todas las requests (excepto `login`) requieren:

```http
Authorization: Bearer {token}
Accept: application/json
X-App-Version: 1.2.0
```

El header `X-App-Version` se compara contra `min_app_version` y `force_update_version`
(ver sección Mobile Settings) — la app puede recibir `426 Upgrade Required` si está vieja.

---

## 3 · Endpoints

Total: **20 rutas** bajo `/api/v1`.

| Método | URI | Auth | Throttle |
|--------|-----|------|----------|
| `GET` | `/api/v1/admin/mobile-devices` | 🔒 sanctum | api |
| `POST` | `/api/v1/admin/mobile-devices/revoke-all` | 🔒 sanctum | api |
| `DELETE` | `/api/v1/admin/mobile-devices/{mobileDevice}` | 🔒 sanctum | api |
| `GET` | `/api/v1/admin/mobile-settings` | 🔒 sanctum | api |
| `PUT` | `/api/v1/admin/mobile-settings` | 🔒 sanctum | api |
| `GET` | `/api/v1/admin/users` | 🔒 sanctum | api |
| `GET` | `/api/v1/clients` | 🔒 sanctum | api |
| `GET` | `/api/v1/clients/{client}` | 🔒 sanctum | api |
| `GET` | `/api/v1/clients/{client}/products` | 🔒 sanctum | api |
| `POST` | `/api/v1/login` | público | api-login |
| `POST` | `/api/v1/logout` | 🔒 sanctum | api |
| `GET` | `/api/v1/me` | 🔒 sanctum | api |
| `GET` | `/api/v1/payment-types` | 🔒 sanctum | api |
| `GET` | `/api/v1/remissions` | 🔒 sanctum | api |
| `POST` | `/api/v1/remissions` | 🔒 sanctum | api, api-write |
| `GET` | `/api/v1/remissions/export` | 🔒 sanctum | api |
| `GET` | `/api/v1/remissions/{remission}` | 🔒 sanctum | api |
| `GET` | `/api/v1/remissions/{remission}/pdf` | 🔒 sanctum | api |
| `POST` | `/api/v1/remissions/{remission}/signature` | 🔒 sanctum | api, api-write |
| `GET` | `/api/v1/routes` | 🔒 sanctum | api |

> Para el detalle completo de payloads, validation rules y respuestas de cada endpoint,
> consulta `MOBILE-APP-CONTEXT.md` §4 en el repo principal (`https://github.com/logo3x/SVD`).
> Este contrato lista solo la **superficie** de la API.

---

## 3.1 · Roles del sistema

El backend define 3 roles (slugs internos en inglés, labels en español para UI):

| Slug | Label | `is_admin` (en API) | Token abilities | Acceso móvil |
|------|-------|----------------------|------------------|--------------|
| `super_admin` | Super Administrador | true | `['*']` | Total |
| `admin` | Administrador | true | `['*']` | Total |
| `seller` | Vendedor | false | `['remissions:read','remissions:create','clients:read','products:read']` | Limitado |

La respuesta de `POST /login` y `GET /me` incluye `role_label` (mostrar en UI) e `is_admin` (decidir qué pantallas renderizar). Ver §15 en MOBILE-APP-CONTEXT.md para el detalle del modo administrador en la app móvil.

---

## 4 · Enums

### 4.1 `payment_type`
- `cash` → Contado
- `cash_for_billing` → Contado para Facturar
- `credit` → Crédito
- `gift` → Obsequio
- `other` → Otro

### 4.2 `route` (DeliveryRoute)
- `route_01` → Ruta 1
- `route_02` → Ruta 2
- `route_03` → Ruta 3
- `route_04` → Ruta 4
- `route_05` → Ruta 5
- `route_06` → Ruta 6
- `route_07` → Ruta 7
- `route_08` → Ruta 8
- `route_09` → Ruta 9
- `route_10` → Ruta 10
- `route_11` → Ruta 11
- `route_12` → Ruta 12
- `route_13` → Ruta 13
- `route_14` → Ruta 14
- `route_15` → Ruta 15
- `route_16` → Ruta 16

### 4.3 `status` (RemissionStatus)
- `draft` → Borrador
- `confirmed` → Confirmada
- `delivered` → Entregada
- `cancelled` → Anulada

---

## 5 · Mobile Settings (estado runtime del backend)

Estos valores los puede cambiar el admin desde `/admin/mobile-settings-page`
sin necesidad de redeploy. La app móvil debe respetarlos:

| Setting | Valor actual | Efecto |
|---------|--------------|--------|
| `min_app_version` | `1.0.0` | Versión por debajo → 426 force_update=false (aviso) |
| `force_update_version` | `0.0.0` | Versión por debajo → 426 force_update=true (bloqueo) |
| `maintenance_mode` | `false` | Si true → toda la API devuelve 503 |
| `announcement_enabled` | `false` | Si true → header `X-SVD-Announcement` en respuestas |
| `default_token_ttl_days` | `0` | TTL por defecto al emitir tokens (0 = no expira) |

---

## 6 · Status codes globales

| Código | Significado | Acción del cliente |
|--------|-------------|---------------------|
| `200/201` | OK | — |
| `204` | OK sin contenido (logout, signature delete) | — |
| `401` | Token revocado/expirado/inválido | Limpiar token local, ir a Login |
| `403` | Falta permiso/ability/scope | "No tienes acceso" — no reintentar |
| `404` | Recurso no existe o no pertenece al usuario | — |
| `422` | Validación | Mostrar `errors.{campo}[0]` |
| `426` | Versión obsoleta (ver `force_update`) | Bloquear o avisar según `force_update` |
| `429` | Rate limit excedido | Backoff exponencial |
| `503` | Maintenance mode | Mostrar `message`, deshabilitar UI |

---

## 7 · Workflow recomendado

### En el backend (este repo, `svd`)

```sh
# Cuando se agrega/modifica un endpoint, FormRequest, enum o setting:
php artisan svd:contract --desktop          # regenera + copia al escritorio
git add docs/api/                           # commit del contrato actualizado
git commit -m "chore(api): update mobile contract"
```

### En la app móvil (repo separado)

```sh
# Antes de cada build, copia el archivo desde el escritorio:
cp "$USERPROFILE/Desktop/SVD-MOBILE-CONTRACT.md" docs/SVD-CONTRACT.md
```

### Comunicación bidireccional

| Dirección | Cómo |
|-----------|------|
| **Backend → app móvil** | Cambios en código → `svd:contract --desktop` → app móvil copia el nuevo archivo del escritorio → ve nueva `version` → regenera clientes |
| **App móvil → backend** | Si la app necesita un cambio (endpoint nuevo, campo extra, error de doc), abre **issue/PR** en `github.com/logo3x/SVD`. El backend hace el cambio + regenera el contrato. |

---

## 8 · Referencias completas

| Documento | Contenido |
|-----------|-----------|
| `MOBILE-APP-CONTEXT.md` | Contexto completo de la app: stack, flujos de pantalla, offline, módulo admin, ejemplos curl detallados |
| `README.md` | Arquitectura del backend |
| `DEPLOY.md` | Cómo desplegar el backend |

---

**Repo backend:** `https://github.com/logo3x/SVD`
**Comando para regenerar:** `php artisan svd:contract --desktop`
**Versión actual:** `ec882d1f078c` — generado `2026-05-25T20:47:18+00:00`
