# SVD — Contexto para la App Móvil (Vendedores en Campo)

> Documento de contexto para iniciar el desarrollo de la app móvil del Sistema de Ventas y Despachos (SVD).
> El backend ya está construido en `c:\wamp64\www\SVD` (Laravel 13 + Filament v5). Esta app es **un proyecto separado** que consume su API REST.

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
| Framework móvil | **NativePHP iOS + Android** (`nativephp/ios`, `nativephp/android`) | Reutiliza Laravel/PHP; UI con Livewire. Alternativa: NativePHP Electron para desktop. |
| Framework alternativo | React Native / Flutter | Si NativePHP móvil presenta limitaciones; el backend no cambia. |
| UI Library | TailwindCSS + componentes nativos | Coherente con Filament del admin. |
| Autenticación | Laravel Sanctum con **personal access tokens** | Token persistido en SecureStorage local. |
| HTTP | Cliente HTTP nativo (Guzzle si NativePHP / Axios si JS) | Soporte multipart/form-data para la firma. |
| Captura firma | Canvas táctil (`<canvas>` HTML5 o equivalente nativo) | `toDataURL('image/png')` o blob para subir. |
| Storage offline | SQLite local (NativePHP/Capacitor) | Cola de remisiones pendientes. |

---

## 3. Conexión con el Backend

- **Base URL desarrollo**: `http://127.0.0.1:8000/api/v1`
- **Base URL producción**: configurable vía settings de la app (`https://svd.example.com/api/v1`).
- **Content-Type**: `application/json` salvo upload de firma (`multipart/form-data`).
- **Header en todas las requests autenticadas**: `Authorization: Bearer {token}` + `Accept: application/json`.
- **CORS**: el backend ya lo permite para origin local; producción debe whitelist el dominio de la app.

---

## 4. Endpoints API (versionados en /api/v1)

### 4.1 Autenticación

#### `POST /api/v1/login`
Cuerpo:
```json
{ "email": "vendedor@svd.test", "password": "secret", "device_name": "iPhone-15-Luis" }
```
Respuesta 200:
```json
{
  "token": "1|abc123...",
  "user": {
    "id": 5,
    "name": "Luis Vendedor",
    "email": "vendedor@svd.test",
    "roles": ["seller"]
  }
}
```
Errores: `422` validación, `401` credenciales inválidas.

#### `POST /api/v1/logout` (autenticado)
Revoca el token actual. Respuesta `204 No Content`.

#### `GET /api/v1/me` (autenticado)
Devuelve el usuario logueado. Útil para validar sesión al abrir la app.

---

### 4.2 Clientes

#### `GET /api/v1/clients?search=acme&page=1`
Listado paginado de clientes activos. Soporta búsqueda por `name` o `nit`.
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
      "email": "compras@acme.test",
      "delivery_point": "external",
      "payment_type": "credit",
      "address": "Cra 50 #45-67",
      "city": "Barrancabermeja"
    }
  ],
  "meta": { "current_page": 1, "last_page": 12, "per_page": 25, "total": 297 }
}
```

#### `GET /api/v1/clients/{id}`
Detalle de un cliente.

#### `GET /api/v1/clients/{id}/products`
Productos disponibles para un cliente con **precios resueltos** (override del pivot o precio base del catálogo).
```json
{
  "data": [
    {
      "id": 2,
      "sku": "H-5000",
      "name": "HIELO 5K",
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
Crea una remisión completa. Cuerpo:
```json
{
  "client_id": 1,
  "issued_at": "2026-05-20T15:30:00-05:00",
  "route": "route_03",
  "payment_type": "credit",
  "observations": "Entrega segundo piso",
  "gps_location": "7.0653,-73.8547",
  "items": [
    { "product_id": 2, "quantity": 5, "unit_price_snapshot": 7800 },
    { "product_id": 6, "quantity": 12, "unit_price_snapshot": 2200 }
  ]
}
```
Reglas de validación:
- `client_id`: required, exists:clients,id, cliente activo
- `route`: required, in:route_01..route_16
- `payment_type`: required, in:cash,cash_for_billing,credit,gift,other
- `items`: required array min:1
- `items.*.product_id`: required, exists:products,id, producto activo y disponible para el cliente
- `items.*.quantity`: required integer min:1
- `items.*.unit_price_snapshot`: required integer min:0

**El backend recalcula `subtotal` (`quantity * unit_price_snapshot`) y `total_amount` (suma de subtotales)** — no aceptes esos valores del cliente. La app los muestra para preview pero no los envía.

Respuesta 201:
```json
{
  "data": {
    "id": 124,
    "issued_at": "2026-05-20T15:30:00-05:00",
    "client": { "id": 1, "name": "ACME Foods", "nit": "900123456" },
    "user": { "id": 5, "name": "Luis Vendedor" },
    "route": { "value": "route_03", "label": "Ruta 3" },
    "payment_type": { "value": "credit", "label": "Crédito" },
    "status": "confirmed",
    "items": [
      { "product_id": 2, "sku": "H-5000", "name": "HIELO 5K", "quantity": 5, "unit_price_snapshot": 7800, "subtotal": 39000 },
      { "product_id": 6, "sku": "A-KRISS-600", "name": "KRISS 600 ML", "quantity": 12, "unit_price_snapshot": 2200, "subtotal": 26400 }
    ],
    "total_amount": 65400,
    "observations": "Entrega segundo piso",
    "signature_url": null
  }
}
```

#### `POST /api/v1/remissions/{id}/signature` (autenticado, multipart)
Sube la firma del cliente como imagen (PNG/JPEG, max 2 MB).
Form data:
- `signature`: file

Respuesta 200:
```json
{ "data": { "id": 124, "signature_url": "https://.../tmp_signed_url" } }
```
La URL de firma es **temporal firmada** (disco privado, no accesible públicamente).

#### `GET /api/v1/remissions/{id}` (autenticado)
Detalle de una remisión.

#### `GET /api/v1/remissions?from=2026-05-01&to=2026-05-20&mine=1`
Listado paginado, filtros opcionales: `from`, `to`, `mine` (sólo del vendedor logueado).

---

## 5. Flujo de Pantallas Sugerido

1. **Splash + Login**: email + password + device name. Guarda token en SecureStorage.
2. **Home**: lista de clientes con búsqueda y "Crear remisión".
3. **Detalle Cliente → Nueva Remisión**:
   - Selección de productos disponibles (lista + buscador, muestra `effective_price` con badge si hay override).
   - Por producto: cantidad numérica, subtotal calculado en vivo.
   - Total calculado en vivo.
   - Select de ruta + tipo de pago (pre-seleccionado del cliente).
   - Observaciones.
4. **Captura firma**: canvas táctil con botones "Limpiar" / "Confirmar".
5. **Preview**: vista de resumen estilo comprobante. Botones "Editar" / "Enviar".
6. **Envío**:
   - POST `/remissions` con body JSON.
   - Si OK: POST `/remissions/{id}/signature` con imagen.
   - Notificar éxito.
   - Si offline: guardar en cola SQLite local con timestamp y reintentar al detectar conexión.
7. **Historial**: GET `/remissions?mine=1` para ver lo que el vendedor ha emitido en el día/mes.

---

## 6. Manejo de Errores

| Código | Significado | Acción sugerida en la app |
|--------|-------------|---------------------------|
| 401 | Token expirado o inválido | Redirigir a Login, limpiar token. |
| 403 | Falta ability/permiso | Mostrar "No tienes permisos para esta acción". |
| 422 | Validación | Mostrar errores por campo (`errors.{field}[0]`). |
| 429 | Rate limit | Mostrar "Demasiados intentos, espera un momento". |
| 500 | Error servidor | Guardar la request en cola para reintento y notificar a soporte. |

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
3. Si falla con 4xx: marcar como fallida y notificar al usuario (probablemente datos inválidos: cliente borrado, etc.).
4. Si falla con 5xx o timeout: incrementar `attempts`, reintentar después.

---

## 8. Seguridad

- Token Sanctum se almacena en **SecureStorage** del dispositivo (Keychain en iOS, EncryptedSharedPreferences en Android).
- Nunca persistir password.
- HTTPS obligatorio en producción.
- La firma se sube al disco `local` (privado) del backend; la app no debe asumir que la URL es pública. Sólo usa `signature_url` mientras esté vigente (URL firmada temporal).
- Cerrar sesión revoca el token en el servidor (POST /logout).

---

## 9. Migración del Sistema Legacy

La app móvil legacy enviaba a `POST /api/ingresarremision` con un único multipart. La app nueva separa: primero JSON con los datos, luego un POST de firma. Si el backend necesita conservar compatibilidad temporal con la app vieja durante la transición, se puede agregar un endpoint `POST /api/v1/remissions/with-signature` que acepte ambos en un solo multipart.

---

## 10. Credenciales de Prueba

Desarrollo local:
- Email: `superadmin@svd.test`
- Password: `Super/Admin?`
- Rol: `super_admin` (acceso total)

Crear vendedor de prueba:
```sh
php artisan tinker --execute 'App\Models\User::create(["name"=>"Vend Test","email"=>"vendedor@svd.test","password"=>bcrypt("123456"),"email_verified_at"=>now()])->assignRole("seller");'
```

---

## 11. Comandos Útiles en el Backend

```sh
# Iniciar el servidor
php artisan serve

# Procesar la cola de emails (necesario para que el envío de comprobantes funcione)
php artisan queue:work

# Migrar + seed limpio (incluye el catálogo maestro de 31 productos)
php artisan migrate:fresh --seed

# Listar rutas API
php artisan route:list --path=api

# Inspeccionar logs
php artisan pail
```

---

## 12. Archivos del Backend que la App Necesita Conocer

| Concepto | Archivo backend |
|----------|----------------|
| Modelo Cliente | `app/Models/Client.php` |
| Modelo Producto | `app/Models/Product.php` con `priceFor($client)` |
| Modelo Remisión | `app/Models/Remission.php` |
| Pivot precios | tabla `client_product` con `custom_price`, `custom_alias`, `is_available` |
| Pivot líneas | tabla `remission_product` con `quantity`, `unit_price_snapshot`, `subtotal` |
| Enums | `app/Enums/{PaymentType,DeliveryRoute,RemissionStatus}.php` |
| Routing email | `app/Services/RemissionEmailRouter.php` (se dispara automáticamente al crear remisión) |
| PDF | `app/Services/RemissionInvoicePdf.php` |

---

**Fin del contexto. Cualquier asistente o desarrollador puede leer este archivo y empezar a construir la app móvil sin más preguntas — todos los endpoints, payloads, validaciones y flujos están aquí.**
