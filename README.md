# SVD — Sistema de Ventas y Despachos

Reconstrucción moderna del sistema `ventas.icemanservice.com.co` (Laravel 8) sobre **Laravel 13 + Filament v5 + PHP 8.5**, con catálogo de productos único + override por cliente, RBAC vía Filament Shield, API REST para app móvil, reportes Excel (OpenSpout) y comprobantes PDF (DomPDF).

## Stack

| Capa | Paquete | Versión |
|------|---------|---------|
| Framework | `laravel/framework` | 13.x |
| Panel admin | `filament/filament` | 5.6 |
| RBAC | `bezhansalleh/filament-shield` | 4.2 |
| Impersonación | `stechstudio/filament-impersonate` | 5.4 |
| Media | `filament/spatie-laravel-media-library-plugin` | 5.6 |
| Auditoría | `spatie/laravel-activitylog` | 4.12 |
| Settings | `spatie/laravel-settings` | 3.8 |
| API auth | `laravel/sanctum` | 4.3 |
| PDF | `barryvdh/laravel-dompdf` | 3.1 |
| Excel | `openspout/openspout` | 4.32 |
| Base de datos | MySQL 8 (InnoDB) | — |

## Inicio rápido

```sh
composer install
npm install && npm run build
cp .env.example .env
php artisan key:generate

# Configurar credenciales MySQL en .env (DB_DATABASE=svd)

php artisan migrate:fresh --seed
php artisan shield:generate --all --panel=admin --option=permissions
php artisan serve
php artisan queue:work    # otra terminal — necesario para emails
```

Login admin en `/admin`:

- Email: `superadmin@svd.test`
- Password: `Super/Admin?`

## Arquitectura clave

### Catálogo de productos (mejora vs sistema legacy)

El sistema viejo creaba 31 filas en `productospers` por cada cliente nuevo (≈ N×31 duplicados). Si subía el precio del HIELO 5K había que actualizar N filas. Aquí lo rediseñamos:

- **`products`** — catálogo maestro (≈31 productos por defecto, una fila cada uno).
- **`client_product`** — pivot con override opcional (`custom_price`, `custom_alias`, `is_available`). NULL en `custom_price` = usa el precio del catálogo.
- **`remission_product`** — pivot de la remisión con `unit_price_snapshot` y `subtotal` (el precio queda congelado al momento de la venta, no se recalcula nunca).

Editar el precio maestro = una sola modificación. Override por cliente = un solo registro en el pivot.

### Paneles Filament

- **`/admin`** — SuperAdmin/Administradores: clientes, productos, remisiones (con repeater dinámico de productos y precio resuelto reactivo), empleados, usuarios, roles (Shield), branding (Settings), reportes con filtros combinables.
- **`/vendedor`** — vendedores web (responsive, restringido a Remissions).

### API REST (para app móvil)

12 endpoints en `/api/v1` con Sanctum + abilities. Ver [MOBILE-APP-CONTEXT.md](MOBILE-APP-CONTEXT.md) para el contexto completo de la app móvil (otro proyecto).

### Notificaciones

Crear una remisión confirmada encola `RemisionCreada` (mailable `ShouldQueue`) con PDF DomPDF adjunto. El routing por tipo de pago se resuelve vía `BrandingSettings` + `PaymentType` enum (no hardcodeado). Acción "Reenviar copia" disponible desde la vista de la remisión.

## Mejoras vs sistema legacy

| # | Mejora | Sistema viejo | Sistema nuevo |
|---|--------|---------------|---------------|
| 1 | Catálogo productos | 31 filas por cliente | Catálogo maestro + pivot overrides |
| 2 | Permisos | 24 hardcodeados | Filament Shield (76 auto-generados) |
| 3 | Firmas digitales | `public/firmas/*.png` (URL pública) | Disco privado `local` |
| 4 | API Sanctum | Sin middleware (vulnerable) | `auth:sanctum` + abilities |
| 5 | Exports | Rutas públicas sin auth | Policy + filtros server-side |
| 6 | Empleados/Usuarios | Mezclado en `users` | `users` + `employee_profiles` |
| 7 | Edición remisiones | Deshabilitada | Habilitada + ActivityLog |
| 8 | Auditoría | No existe | Spatie ActivityLog |
| 9 | Emails | Sincrónicos | Encolados (`database` driver) |
| 10 | Comprobante | Solo HTML print | PDF DomPDF descargable |
| 11 | Frontend | AdminLTE + jQuery | Filament 5 (Livewire 3 + Alpine + Tailwind) |
| 12 | Tipos | Strings sueltos | PHP 8.5 Enums |
| 13 | Precio histórico | Se perdía | `unit_price_snapshot` en pivot |
| 14 | Multi-marca | Hardcodeado | `spatie/laravel-settings` |

## Estructura

```
app/
  Enums/                       PaymentType, RemissionStatus, DeliveryPoint,
                               DeliveryRoute, EmploymentStatus, ProductCategory, ProductUnit
  Filament/Admin/              Resources, Pages, Widgets del panel admin
  Http/Controllers/Api/V1/     Endpoints REST
  Http/Resources/Api/V1/       Resources de Eloquent → JSON
  Http/Requests/Api/V1/        FormRequests con reglas de validación
  Mail/                        RemisionCreada (queueable + PDF attach)
  Models/                      User, Client, Product, Remission, EmployeeProfile
  Observers/                   ClientObserver dispara AttachDefaultProductsAction
  Actions/                     AttachDefaultProductsAction
  Services/                    RemissionInvoicePdf, RemissionEmailRouter, RemissionsXlsxExporter
  Settings/                    BrandingSettings (multi-marca)
database/
  migrations/                  Schema (clients, products, client_product, remissions, remission_product...)
  seeders/                     MasterProductCatalogSeeder + SuperAdmin
resources/
  views/pdf/remission.blade.php            Template comprobante
  views/emails/remission-created.blade.php Template email
routes/
  api.php                      /api/v1/* con Sanctum
  web.php                      Filament panels
```

## Testing

```sh
php artisan test --compact
```

## Documentación complementaria

- [MOBILE-APP-CONTEXT.md](MOBILE-APP-CONTEXT.md) — Contexto para construir la app móvil de vendedores (proyecto separado).
- [AGENTS.md](AGENTS.md) — Guías de Laravel Boost para asistentes IA.

## Licencia

Privado — ICEMAN SERVICES / Confipetrol.
