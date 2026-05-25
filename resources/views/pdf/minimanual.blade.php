<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>SVD · Minimanual de Usuario</title>
    <style>
        @page { margin: 28px 32px 32px; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 10.5px; line-height: 1.5; color: #0b1e36; }

        h1 { font-size: 26px; margin: 0 0 4px; color: #0a2540; letter-spacing: -0.02em; }
        h2 { font-size: 16px; margin: 22px 0 8px; color: #0a2540; padding-bottom: 5px; border-bottom: 2px solid #1e4d8b; }
        h3 { font-size: 13px; margin: 14px 0 5px; color: #1e4d8b; }
        h4 { font-size: 11.5px; margin: 10px 0 4px; color: #0a2540; }
        p  { margin: 0 0 6px; }
        ul, ol { margin: 4px 0 8px 16px; padding: 0; }
        li { margin: 2px 0; }

        /* Portada */
        .cover { text-align: left; padding-top: 80px; page-break-after: always; }
        .cover .brand { font-size: 64px; font-weight: bold; color: #0a2540; letter-spacing: -0.04em; }
        .cover .brand .dot { color: #b04420; }
        .cover .subtitle { font-size: 14px; color: #5b7088; margin-top: 4px; }
        .cover .meta { margin-top: 60px; font-size: 10px; color: #5b7088; letter-spacing: 0.14em; text-transform: uppercase; }
        .cover .ref { margin-top: 4px; font-family: monospace; font-size: 10px; color: #1e4d8b; }
        .cover .footer-line { margin-top: 280px; border-top: 1px solid #e4ebf3; padding-top: 10px; font-size: 9px; color: #94a3b8; }

        /* Estado / pill */
        .pill { display: inline-block; padding: 2px 8px; border-radius: 999px; font-size: 9px; font-weight: bold; letter-spacing: 0.1em; text-transform: uppercase; }
        .pill-ok { background: #d1ecda; color: #1d6f4a; }
        .pill-warn { background: #fbe6c8; color: #b8761e; }
        .pill-info { background: #d6e4f3; color: #1e4d8b; }

        /* Boxes */
        .box { border: 1px solid #e4ebf3; background: #f7f9fc; padding: 10px 12px; margin: 8px 0; border-radius: 4px; }
        .box-credential { border-left: 3px solid #1e4d8b; background: #f7f9fc; padding: 8px 12px; margin: 6px 0; font-family: monospace; font-size: 10px; }
        .box-warning { border-left: 3px solid #b8761e; background: #fdf6e9; padding: 8px 12px; margin: 6px 0; }
        .box-danger { border-left: 3px solid #b13a3a; background: #fbe9e9; padding: 8px 12px; margin: 6px 0; }
        .box-ok { border-left: 3px solid #1d6f4a; background: #ecf6f0; padding: 8px 12px; margin: 6px 0; }

        /* Tablas */
        table { width: 100%; border-collapse: collapse; margin: 6px 0 12px; font-size: 10px; }
        th { background: #0a2540; color: #fff; padding: 5px 7px; text-align: left; font-weight: bold; letter-spacing: 0.04em; }
        td { padding: 5px 7px; border-bottom: 1px solid #e4ebf3; vertical-align: top; }
        tr:nth-child(even) td { background: #f7f9fc; }

        /* Code blocks */
        code, .code { font-family: Courier, monospace; font-size: 9.5px; background: #eef2f7; padding: 1px 4px; border-radius: 2px; color: #0a2540; }
        pre { background: #0a2540; color: #d6e4f3; padding: 8px 10px; font-family: Courier, monospace; font-size: 9px; line-height: 1.45; border-radius: 4px; white-space: pre-wrap; word-wrap: break-word; }

        /* Sectioning */
        .toc-item { padding: 3px 0; border-bottom: 1px dotted #e4ebf3; }
        .toc-num { display: inline-block; width: 28px; font-family: monospace; color: #1e4d8b; }

        .lead { font-size: 11px; color: #1f3a5f; margin: 6px 0 14px; }
        .small { font-size: 9px; color: #5b7088; }
        .mono { font-family: monospace; font-size: 10px; }

        .page-break { page-break-before: always; }
        .avoid-break { page-break-inside: avoid; }

        .grid-2 { width: 100%; }
        .grid-2 td { width: 50%; vertical-align: top; padding: 4px 8px 4px 0; border: 0; }

        .step { padding: 6px 0 6px 28px; position: relative; border-bottom: 1px solid #e4ebf3; }
        .step-num { position: absolute; left: 0; top: 8px; font-family: monospace; font-size: 11px; color: #b04420; font-weight: bold; }

        .footer-page { position: fixed; bottom: -22px; left: 0; right: 0; text-align: center; font-size: 8.5px; color: #94a3b8; }
    </style>
</head>
<body>

<div class="footer-page">SVD · Minimanual de usuario · Generado {{ now()->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }}</div>

{{-- =================================================================
     PORTADA
================================================================= --}}
<div class="cover">
    <div class="meta">SISTEMA DE VENTAS Y DESPACHOS</div>
    <div class="brand">SVD<span class="dot">.</span></div>
    <div class="subtitle">Minimanual de Usuario · Edición {{ now()->locale('es')->isoFormat('MMMM YYYY') }}</div>

    <p style="margin-top: 70px; font-size: 12px; line-height: 1.7; max-width: 480px; color: #1f3a5f;">
        Plataforma institucional de distribución de hielo, agua y neveras.
        Catálogo maestro, rutas de reparto, comprobantes firmados, dos paneles
        (administración y vendedor) y app móvil para campo.
    </p>

    <div class="meta">CONTENIDO</div>
    <p style="font-size: 10.5px; margin-top: 6px;">
        Credenciales · Accesos · Funcionalidades del panel admin · Panel del vendedor ·
        Casos de uso comunes · Comandos útiles · API REST resumida.
    </p>

    <div class="footer-line">
        REF · SVD/{{ now()->format('Y.m') }} &nbsp; · &nbsp;
        STACK · Laravel 13 · Filament 5 · PHP 8.5 · MySQL 8 &nbsp; · &nbsp;
        DOC · auto-generado vía <span class="mono">php artisan svd:manual</span>
    </div>
</div>

{{-- =================================================================
     ÍNDICE
================================================================= --}}
<h2>Índice</h2>
<div class="toc-item"><span class="toc-num">01</span> Resumen ejecutivo</div>
<div class="toc-item"><span class="toc-num">02</span> Credenciales y accesos</div>
<div class="toc-item"><span class="toc-num">03</span> Panel administración (<span class="mono">/admin</span>)</div>
<div class="toc-item"><span class="toc-num">04</span> Panel del vendedor (<span class="mono">/vendedor</span>)</div>
<div class="toc-item"><span class="toc-num">05</span> Módulo app móvil</div>
<div class="toc-item"><span class="toc-num">06</span> Casos de uso completos</div>
<div class="toc-item"><span class="toc-num">07</span> Comandos útiles</div>
<div class="toc-item"><span class="toc-num">08</span> API REST resumida</div>
<div class="toc-item"><span class="toc-num">09</span> Resolución de incidencias</div>

{{-- =================================================================
     01 · RESUMEN EJECUTIVO
================================================================= --}}
<h2>01 · Resumen ejecutivo</h2>

<p class="lead">
    SVD es la plataforma que reemplaza la operación de hielo + agua + envases + neveras de papel y Excel
    por un sistema único con catálogo maestro, override de precios por cliente, comprobantes PDF, emails
    automatizados y API REST para los vendedores en campo.
</p>

<h3>Lo que el sistema hace</h3>
<ul>
    <li><strong>Catálogo maestro único</strong>: un solo registro por producto. Editar precio = 1 fila modificada (vs N en el sistema anterior).</li>
    <li><strong>Override de precio por cliente</strong>: pivot <span class="mono">client_product.custom_price</span>. NULL = usa precio base.</li>
    <li><strong>Precio histórico congelado</strong>: cada línea de remisión guarda <span class="mono">unit_price_snapshot</span> — cambiar el catálogo no altera las ventas pasadas.</li>
    <li><strong>Dos paneles Filament</strong>: <span class="mono">/admin</span> (todo) y <span class="mono">/vendedor</span> (sólo "Mis Remisiones").</li>
    <li><strong>API REST</strong> (<span class="mono">/api/v1</span>) con 12 endpoints + Sanctum + rate limit + abilities por rol.</li>
    <li><strong>Comprobante PDF</strong> por cada remisión, enviado automáticamente por email a los buzones internos + el cliente.</li>
    <li><strong>Reportes Excel</strong> (OpenSpout streamed) con filtros combinables.</li>
    <li><strong>Auditoría</strong>: ActivityLog en Cliente/Producto/Remisión/Usuario + Login/Logout/Failed.</li>
    <li><strong>Multi-marca</strong>: branding y routing de emails se editan en panel sin tocar código.</li>
    <li><strong>Módulo móvil</strong>: settings runtime (versión mínima, mantenimiento, anuncios) y kill switch de dispositivos.</li>
</ul>

<h3>Roles</h3>
<table>
    <tr><th style="width: 22%;">Rol</th><th style="width: 28%;">Acceso</th><th>Para qué sirve</th></tr>
    <tr><td>super_admin</td><td>/admin (total) + /vendedor</td><td>Administrador del sistema. Bypass de permisos via Shield.</td></tr>
    <tr><td>seller</td><td>/vendedor (restringido)</td><td>Vendedor que sólo ve y crea sus propias remisiones.</td></tr>
</table>

{{-- =================================================================
     02 · CREDENCIALES Y ACCESOS
================================================================= --}}
<h2>02 · Credenciales y accesos</h2>

<h3>Usuarios pre-provisionados por el seeder</h3>

<table>
    <tr><th>Rol</th><th>Email</th><th>Contraseña</th><th>Panel principal</th></tr>
    <tr>
        <td><span class="pill pill-info">super_admin</span></td>
        <td class="mono">superadmin@svd.test</td>
        <td class="mono">Super/Admin?</td>
        <td>/admin</td>
    </tr>
    <tr>
        <td><span class="pill pill-ok">seller</span></td>
        <td class="mono">vendedor@svd.test</td>
        <td class="mono">vendedor</td>
        <td>/vendedor</td>
    </tr>
</table>

<div class="box-warning">
    <strong>Importante:</strong> estas son credenciales por defecto del seeder de demo.
    En producción cambia las contraseñas inmediatamente con
    <span class="mono">php artisan tinker --execute "App\Models\User::where('email','superadmin@svd.test')->update(['password' => bcrypt('NuevaPassword')]);"</span>
</div>

<h3>URLs locales (dev)</h3>
<table>
    <tr><th style="width: 38%;">Recurso</th><th>URL</th></tr>
    <tr><td>Landing pública</td><td class="mono">http://127.0.0.1:8000/</td></tr>
    <tr><td>Panel administración</td><td class="mono">http://127.0.0.1:8000/admin/login</td></tr>
    <tr><td>Panel del vendedor</td><td class="mono">http://127.0.0.1:8000/vendedor/login</td></tr>
    <tr><td>Reportes (admin)</td><td class="mono">/admin/reportes</td></tr>
    <tr><td>Configuración branding</td><td class="mono">/admin/settings</td></tr>
    <tr><td>Configuración app móvil</td><td class="mono">/admin/mobile-settings-page</td></tr>
    <tr><td>Dispositivos conectados</td><td class="mono">/admin/mobile-devices</td></tr>
    <tr><td>Roles (Shield)</td><td class="mono">/admin/shield/roles</td></tr>
    <tr><td>API base</td><td class="mono">/api/v1</td></tr>
    <tr><td>Healthcheck</td><td class="mono">/up</td></tr>
</table>

<h3>Base de datos local</h3>
<div class="box-credential">
    Engine: MySQL 8 (WAMP) &middot; Host: 127.0.0.1:3306 &middot; Database: svd &middot; User: root &middot; Password: (vacío)
</div>

{{-- =================================================================
     03 · PANEL ADMIN
================================================================= --}}
<div class="page-break"></div>
<h2>03 · Panel administración (/admin)</h2>

<p class="lead">
    Acceso total para el equipo de oficina. Sidebar organizado por grupos: Ventas, Catálogo, Configuración, App móvil, Filament Shield.
</p>

<h3>3.1 Ventas › Remisiones</h3>
<p>El módulo central. Lista, crea y reenvía comprobantes.</p>
<ul>
    <li><strong>Listado</strong> con filtros: cliente, vendedor, tipo de pago, estado, rango de fechas.</li>
    <li><strong>Crear remisión</strong>: select de cliente → carga su <span class="mono">payment_type</span> automáticamente y los productos disponibles. Repeater dinámico de productos con precio resuelto (override o catálogo).</li>
    <li><strong>Ver remisión</strong>: comprobante en pantalla + acciones <em>Imprimir PDF</em> y <em>Reenviar copia</em> (modal con email adicional opcional).</li>
    <li><strong>Editar / Eliminar</strong> con confirmación. Cambios registrados en <span class="mono">activity_log</span>.</li>
    <li><strong>Exportar (filtros)</strong>: header action — genera XLSX con todos los registros filtrados (chunked, soporta datasets grandes).</li>
    <li><strong>Exportar selección</strong>: bulk action sobre los seleccionados.</li>
</ul>

<h3>3.2 Ventas › Reportes</h3>
<p>Página dedicada con filtros combinables:</p>
<ul>
    <li>Rango de fechas (Desde / Hasta).</li>
    <li>Cliente, Vendedor, Tipo de pago, Estado.</li>
    <li>Preview reactivo: cantidad de remisiones + total acumulado.</li>
    <li>Descarga Excel streamed.</li>
</ul>

<h3>3.3 Catálogo › Catálogo de Productos</h3>
<ul>
    <li>CRUD del catálogo maestro. 31 productos seed por defecto.</li>
    <li>Campos: SKU, nombre, descripción, categoría, unidad, precio base, activo, default-for-new-clients.</li>
    <li>Filtros: por categoría, por activo, por default.</li>
    <li>Bulk actions: activar/desactivar, toggle default.</li>
</ul>

<h3>3.4 Catálogo › Clientes</h3>
<ul>
    <li>CRUD de clientes con form en 3 tabs: Datos · Contrato · Adjuntos (logo + contrato vía Spatie MediaLibrary).</li>
    <li>Al <strong>crear un cliente nuevo</strong>, el observer adjunta automáticamente todos los productos del catálogo marcados como default (con <span class="mono">custom_price = NULL</span>).</li>
    <li>RelationManager <strong>Productos del Cliente</strong>: gestiona los overrides por cliente (precio especial, alias interno, disponibilidad).</li>
</ul>

<h3>3.5 Configuración › Empleados</h3>
<p>Información de RRHH (datos personales, contrato, seguridad social, banco, documentos). Desacoplado de <span class="mono">users</span>.</p>

<h3>3.6 Configuración › Usuarios &amp; Roles</h3>
<ul>
    <li>CRUD de cuentas + asignación de roles (multi-select).</li>
    <li>Botón <strong>Impersonate</strong> (stechstudio/filament-impersonate): el admin puede entrar como cualquier usuario para depurar.</li>
    <li>Roles administrados via Shield: <span class="mono">/admin/shield/roles</span> · 90 permisos auto-generados.</li>
</ul>

<h3>3.7 Configuración › Configuración (branding)</h3>
<p>Spatie Settings. Edita sin tocar código:</p>
<ul>
    <li>Nombre de empresa, eslogan, ciudad, dirección, teléfonos.</li>
    <li><strong>Routing de emails por tipo de pago</strong>: contado, contado-para-facturar, crédito, buzón principal (siempre copia).</li>
</ul>

<h3>3.8 App móvil › Dispositivos móviles</h3>
<ul>
    <li>Lista de Personal Access Tokens (Sanctum) emitidos a vendedores.</li>
    <li>Columnas: nombre del dispositivo, vendedor, abilities, último uso, expiración.</li>
    <li>Filtros: por vendedor, activos últimas 24h, sin uso +30 días.</li>
    <li>Acciones: <em>Revocar</em> (uno) · <em>Revocar selección</em> · <span style="color:#b13a3a; font-weight:bold;">Revocar TODOS (kill switch)</span>.</li>
</ul>

<h3>3.9 App móvil › Configuración móvil</h3>
<p>Settings runtime de la app móvil:</p>
<table>
    <tr><th>Setting</th><th>Efecto</th></tr>
    <tr><td>min_app_version</td><td>App con versión menor → 426 force_update=false (aviso suave)</td></tr>
    <tr><td>force_update_version</td><td>App con versión menor → 426 force_update=true (bloqueo)</td></tr>
    <tr><td>maintenance_mode</td><td>true → toda la API devuelve 503</td></tr>
    <tr><td>announcement_enabled + msg</td><td>Inyecta header X-SVD-Announcement en cada respuesta</td></tr>
    <tr><td>api_base_url</td><td>URL que la app móvil consume (informativo)</td></tr>
    <tr><td>default_token_ttl_days</td><td>TTL por defecto al emitir tokens (0 = sin expiración)</td></tr>
</table>

{{-- =================================================================
     04 · PANEL VENDEDOR
================================================================= --}}
<div class="page-break"></div>
<h2>04 · Panel del vendedor (/vendedor)</h2>

<p class="lead">
    Para los vendedores cuando trabajan desde un navegador (PC o tablet). El listado está
    <strong>scoped al usuario logueado</strong> — un vendedor sólo ve sus propias remisiones.
</p>

<h3>Sidebar</h3>
<ul>
    <li><strong>Dashboard</strong>: welcome card con su nombre + botón Sign out.</li>
    <li><strong>Mis Remisiones</strong>: listado de las remisiones creadas por el vendedor.</li>
</ul>

<h3>Funcionalidades</h3>
<ul>
    <li><strong>Crear remisión</strong>: mismo formulario que en /admin pero <span class="mono">user_id</span> se asigna automáticamente a su cuenta.</li>
    <li><strong>Ver detalle</strong>: comprobante + botón <em>Imprimir PDF</em>.</li>
    <li>Filtros: tipo de pago, estado, rango de fechas.</li>
    <li>No puede editar, eliminar ni ver remisiones de otros vendedores.</li>
    <li>Intento de acceso a /admin → 403 Forbidden.</li>
</ul>

<div class="box-ok">
    <strong>Al crear una remisión confirmada</strong>, el backend automáticamente: (1) guarda los items con
    <span class="mono">unit_price_snapshot</span>, (2) calcula <span class="mono">total_amount</span> server-side,
    (3) encola un email con PDF adjunto a los buzones internos + el cliente, (4) registra la acción en
    <span class="mono">activity_log</span>.
</div>

{{-- =================================================================
     05 · MÓDULO APP MÓVIL
================================================================= --}}
<h2>05 · Módulo app móvil</h2>

<p class="lead">
    La app móvil (proyecto separado) consume <span class="mono">/api/v1</span>. Desde el panel admin
    se controla la conexión sin tocar código.
</p>

<h3>Endpoints clave (Sanctum)</h3>
<table>
    <tr><th>Método</th><th>Endpoint</th><th>Throttle</th><th>Uso</th></tr>
    <tr><td>POST</td><td>/api/v1/login</td><td>6/min</td><td>Email + password + device_name → token</td></tr>
    <tr><td>POST</td><td>/api/v1/logout</td><td>60/min</td><td>Revoca el token actual</td></tr>
    <tr><td>GET</td><td>/api/v1/me</td><td>60/min</td><td>Validar sesión</td></tr>
    <tr><td>GET</td><td>/api/v1/clients?search=</td><td>60/min</td><td>Listado paginado, búsqueda por nit/nombre</td></tr>
    <tr><td>GET</td><td>/api/v1/clients/{id}/products</td><td>60/min</td><td>Productos del cliente con effective_price</td></tr>
    <tr><td>POST</td><td>/api/v1/remissions</td><td>30/min</td><td>Crear remisión completa</td></tr>
    <tr><td>POST</td><td>/api/v1/remissions/{id}/signature</td><td>30/min</td><td>Subir firma (multipart, max 2MB)</td></tr>
    <tr><td>GET</td><td>/api/v1/remissions?mine=1</td><td>60/min</td><td>Historial del vendedor</td></tr>
    <tr><td>GET</td><td>/api/v1/remissions/export</td><td>60/min</td><td>Descarga XLSX scoped al vendedor (filtros: from/to/payment_type/status)</td></tr>
</table>

<h3>Header obligatorio en cada request</h3>
<div class="box-credential">
    Authorization: Bearer {token} <br>
    Accept: application/json <br>
    X-App-Version: 1.2.0
</div>

<h3>Respuestas posibles del enforcement</h3>
<table>
    <tr><th>Código</th><th>Cuándo</th><th>Acción del cliente</th></tr>
    <tr><td>503</td><td>maintenance_mode = true</td><td>Mostrar mensaje y bloquear</td></tr>
    <tr><td>426 force_update=true</td><td>X-App-Version &lt; force_update_version</td><td>Bloquear app + "Actualizar ahora"</td></tr>
    <tr><td>426 force_update=false</td><td>X-App-Version &lt; min_app_version</td><td>Aviso, permitir continuar</td></tr>
    <tr><td>401</td><td>Token revocado o expirado</td><td>Redirigir a Login, limpiar token</td></tr>
    <tr><td>429</td><td>Rate limit excedido</td><td>Backoff exponencial</td></tr>
    <tr><td>422</td><td>Validación</td><td>Mostrar errores.{campo}[0]</td></tr>
</table>

<p class="small">Para el contexto completo de la app móvil ver <span class="mono">MOBILE-APP-CONTEXT.md</span> en el repo.</p>

{{-- =================================================================
     06 · CASOS DE USO
================================================================= --}}
<div class="page-break"></div>
<h2>06 · Casos de uso completos</h2>

<h3>Caso 1 · Onboarding de un cliente nuevo</h3>
<div class="step"><span class="step-num">1</span>Entra a <span class="mono">/admin/clients</span> y haz clic en <strong>"New Cliente"</strong>.</div>
<div class="step"><span class="step-num">2</span>Tab <strong>Datos</strong>: razón social, NIT, contacto, WhatsApp, email.</div>
<div class="step"><span class="step-num">3</span>Tab <strong>Contrato</strong>: punto de entrega, tipo de pago, fechas de contrato.</div>
<div class="step"><span class="step-num">4</span>Tab <strong>Adjuntos</strong>: sube logo y contrato (opcional).</div>
<div class="step"><span class="step-num">5</span>Click <em>Crear</em>. El observer adjunta automáticamente los productos del catálogo marcados como default.</div>
<div class="step"><span class="step-num">6</span>(Opcional) abre el cliente, ve al RelationManager <strong>Productos</strong> y configura precios especiales con "Editar override".</div>

<h3>Caso 2 · Crear una venta con precio especial para un cliente</h3>
<div class="step"><span class="step-num">1</span>Entra a <span class="mono">/admin/remissions/create</span> (o <span class="mono">/vendedor/remissions/create</span>).</div>
<div class="step"><span class="step-num">2</span>Selecciona el cliente — el form carga su <span class="mono">payment_type</span> automáticamente.</div>
<div class="step"><span class="step-num">3</span>En el Repeater de productos, agrega filas. Cada producto carga su <em>precio efectivo</em> (override si existe, sino catálogo).</div>
<div class="step"><span class="step-num">4</span>Ajusta cantidades. Subtotal y total se recalculan en vivo.</div>
<div class="step"><span class="step-num">5</span>Selecciona Ruta + Tipo de pago + observaciones.</div>
<div class="step"><span class="step-num">6</span>Click <em>Crear</em>. Eres redirigido a la vista de la remisión + email encolado automáticamente + activity_log registra la acción.</div>

<h3>Caso 3 · Reenviar el comprobante a un email adicional</h3>
<div class="step"><span class="step-num">1</span>Abre la remisión en <span class="mono">/admin/remissions/{id}</span>.</div>
<div class="step"><span class="step-num">2</span>Click en <strong>"Reenviar copia"</strong> (header action).</div>
<div class="step"><span class="step-num">3</span>El modal trae el email del cliente pre-cargado. Añade emails adicionales si quieres.</div>
<div class="step"><span class="step-num">4</span>Click <em>Enviar copia</em>. La cola encola un nuevo mail con asunto "[COPIA]…" y PDF adjunto.</div>

<h3>Caso 4 · Cambiar el precio del HIELO 5K en el catálogo</h3>
<div class="step"><span class="step-num">1</span>Entra a <span class="mono">/admin/products</span> → busca SKU <span class="mono">H-5000</span>.</div>
<div class="step"><span class="step-num">2</span>Click <em>Editar</em> → cambia <strong>Precio base</strong>.</div>
<div class="step"><span class="step-num">3</span>Click <em>Guardar</em>. <strong>Los clientes con override mantienen su precio especial.</strong> Los demás usarán el nuevo default desde la próxima remisión.</div>
<div class="step"><span class="step-num">4</span><strong>Las remisiones históricas no se ven afectadas</strong> — el snapshot del precio quedó congelado en el pivot al momento de cada venta.</div>

<h3>Caso 5 · Activar mantenimiento (sólo afecta a la app móvil)</h3>
<div class="step"><span class="step-num">1</span>Entra a <span class="mono">/admin/mobile-settings-page</span>.</div>
<div class="step"><span class="step-num">2</span>En la sección <strong>Modo mantenimiento</strong>, activa el toggle.</div>
<div class="step"><span class="step-num">3</span>Ajusta el mensaje ("Plataforma en mantenimiento. Vuelve en X minutos.").</div>
<div class="step"><span class="step-num">4</span>Click <em>Guardar cambios</em>. Desde ese instante, toda request a <span class="mono">/api/v1/*</span> devuelve 503 con el mensaje. <strong>El panel web sigue funcionando normal.</strong></div>
<div class="step"><span class="step-num">5</span>Cuando termines, desactiva el toggle y guarda.</div>

<h3>Caso 6 · Revocar todos los dispositivos móviles (kill switch)</h3>
<div class="step"><span class="step-num">1</span>Entra a <span class="mono">/admin/mobile-devices</span>.</div>
<div class="step"><span class="step-num">2</span>Click en el botón rojo <strong>"Revocar todos (kill switch)"</strong>.</div>
<div class="step"><span class="step-num">3</span>Confirma. Todos los tokens Sanctum se eliminan. Todos los vendedores recibirán 401 en su próxima request y deberán volver a iniciar sesión.</div>

<h3>Caso 7 · Cambiar buzones de email del routing</h3>
<div class="step"><span class="step-num">1</span>Entra a <span class="mono">/admin/settings</span>.</div>
<div class="step"><span class="step-num">2</span>En <strong>Routing de correos por tipo de pago</strong> edita los 4 emails: principal, contado, contado-para-facturar, crédito.</div>
<div class="step"><span class="step-num">3</span>Guarda. La próxima remisión usará la nueva configuración (los enums internos no cambian, sólo el destino del email).</div>

<h3>Caso 8 · Generar un reporte de ventas del mes</h3>
<div class="step"><span class="step-num">1</span>Entra a <span class="mono">/admin/reportes</span>.</div>
<div class="step"><span class="step-num">2</span>Pon <em>Desde</em> = 1 del mes actual, <em>Hasta</em> = hoy.</div>
<div class="step"><span class="step-num">3</span>(Opcional) filtra por cliente o vendedor.</div>
<div class="step"><span class="step-num">4</span>El preview muestra cantidad de remisiones y total acumulado.</div>
<div class="step"><span class="step-num">5</span>Click <em>Descargar Excel</em>. Se descarga un XLSX con una fila por línea de producto (vs una fila por remisión) — útil para tablas dinámicas.</div>

{{-- =================================================================
     07 · COMANDOS ÚTILES
================================================================= --}}
<div class="page-break"></div>
<h2>07 · Comandos útiles</h2>

<h3>Reset completo del entorno</h3>
<pre>php artisan migrate:fresh --seed
php artisan shield:generate --all --panel=admin --option=permissions
php artisan shield:generate --all --panel=vendedor --option=permissions
php artisan db:seed --force   # reasigna permisos al rol seller</pre>

<h3>Levantar servidor + cola</h3>
<pre>php artisan serve         # http://127.0.0.1:8000
php artisan queue:work    # otra terminal — procesa emails encolados</pre>

<h3>Generar este minimanual</h3>
<pre>php artisan svd:manual</pre>

<h3>Activar mantenimiento desde CLI</h3>
<pre>php artisan tinker --execute "app(App\Settings\MobileSettings::class)->fill(['maintenance_mode' => true])->save();"</pre>

<h3>Kill switch desde CLI (revocar todos los tokens)</h3>
<pre>php artisan tinker --execute "Laravel\Sanctum\PersonalAccessToken::where('tokenable_type', 'App\Models\User')->delete();"</pre>

<h3>Cambiar password del SuperAdmin</h3>
<pre>php artisan tinker --execute "App\Models\User::where('email','superadmin@svd.test')
    ->update(['password' => bcrypt('NuevaPassword')]);"</pre>

<h3>Crear vendedor adicional</h3>
<pre>php artisan tinker --execute "App\Models\User::create([
    'name' => 'Vend 2',
    'email' => 'v2@svd.test',
    'password' => bcrypt('xxx'),
    'email_verified_at' => now()
])->assignRole('seller');"</pre>

<h3>Optimizaciones para producción</h3>
<pre>composer install --no-dev --optimize-autoloader
npm ci && npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
php artisan filament:optimize</pre>

<h3>Tests</h3>
<pre>php artisan test --compact            # corre los 38 tests (~3s)
php artisan test --filter=Auth        # filtro por nombre</pre>

{{-- =================================================================
     08 · API REST RESUMIDA
================================================================= --}}
<div class="page-break"></div>
<h2>08 · API REST resumida</h2>

<h3>Login (no autenticado)</h3>
<pre>POST /api/v1/login
Content-Type: application/json
Accept: application/json
X-App-Version: 1.0.0

{ "email": "vendedor@svd.test", "password": "vendedor", "device_name": "iPhone-15" }

→ 200
{ "token": "1|abc...", "user": { "id": 2, "name": "Vend Test", "email": "...", "roles": ["seller"] } }</pre>

<h3>Crear remisión (autenticado)</h3>
<pre>POST /api/v1/remissions
Authorization: Bearer {token}
Content-Type: application/json
X-App-Version: 1.0.0

{
  "client_id": 1,
  "route": "route_03",
  "payment_type": "credit",
  "observations": "Entrega segundo piso",
  "gps_lat": 7.06530000,
  "gps_lng": -73.85470000,
  "items": [
    { "product_id": 2, "quantity": 5, "unit_price_snapshot": 7800 },
    { "product_id": 6, "quantity": 12, "unit_price_snapshot": 2200 }
  ]
}

→ 201 con la remisión completa (cliente, vendedor, items, total). Email encolado automáticamente.</pre>

<h3>Subir firma (multipart)</h3>
<pre>POST /api/v1/remissions/{id}/signature
Authorization: Bearer {token}
Content-Type: multipart/form-data

signature=@firma.png  (max 2MB, PNG/JPG)

→ 200 { "data": { "id": 124, ..., "has_signature": true } }</pre>

<h3>Errores comunes</h3>
<table>
    <tr><th>Status</th><th>Significado</th></tr>
    <tr><td>401</td><td>Token inválido/expirado/revocado</td></tr>
    <tr><td>403</td><td>Falta ability o permiso de Shield</td></tr>
    <tr><td>422</td><td>Validación. Ver <span class="mono">errors.{campo}[0]</span></td></tr>
    <tr><td>426 force_update=true</td><td>App debe actualizarse antes de continuar</td></tr>
    <tr><td>429</td><td>Rate limit excedido. Backoff exponencial</td></tr>
    <tr><td>503</td><td>Maintenance mode activo</td></tr>
</table>

{{-- =================================================================
     09 · RESOLUCIÓN DE INCIDENCIAS
================================================================= --}}
<h2>09 · Resolución de incidencias</h2>

<h3>"Vista en blanco" o 500 después de un deploy</h3>
<pre>php artisan optimize:clear   # limpia config, route, view, event caches</pre>

<h3>El email del comprobante no llega</h3>
<ul>
    <li>Verifica que <span class="mono">php artisan queue:work</span> esté corriendo.</li>
    <li>Revisa <span class="mono">storage/logs/laravel.log</span> en local (driver "log" escribe ahí).</li>
    <li>En prod, verifica SMTP en <span class="mono">.env</span> y la tabla <span class="mono">failed_jobs</span>.</li>
</ul>

<h3>El vendedor no puede crear remisión ("Forbidden")</h3>
<ol>
    <li>Verifica que el usuario tenga el rol <span class="mono">seller</span> en <span class="mono">/admin/users</span>.</li>
    <li>Verifica que el rol <span class="mono">seller</span> tenga los 7 permisos en <span class="mono">/admin/shield/roles</span>.</li>
    <li>Si los permisos están vacíos: <span class="mono">php artisan db:seed --force</span> los reasigna.</li>
</ol>

<h3>La app móvil queda bloqueada con "Actualiza la aplicación"</h3>
<p>Significa que <span class="mono">X-App-Version</span> es menor que <span class="mono">force_update_version</span>.
Para destrabar urgente: entra a <span class="mono">/admin/mobile-settings-page</span> y baja la
<span class="mono">force_update_version</span> a algo &lt; la versión actual de la app.</p>

<h3>Las remisiones de un vendedor no aparecen en /vendedor/remissions</h3>
<p>El listado tiene scope <span class="mono">where user_id = Auth::id()</span>. Si el vendedor cambia de cuenta o se
creó la remisión desde /admin con otro <span class="mono">user_id</span>, no la verá. Verifica el <span class="mono">user_id</span>
real en la BD.</p>

<h3>"Too many requests" (429)</h3>
<p>Backoff exponencial. Los límites son: 6/min en login (por IP+email), 60/min en lecturas, 30/min en escrituras.</p>

<div style="margin-top: 30px; border-top: 1px solid #e4ebf3; padding-top: 10px;">
    <p class="small">
        <strong>Fin del minimanual.</strong> Para detalle técnico completo ver el <span class="mono">README.md</span>
        y el <span class="mono">MOBILE-APP-CONTEXT.md</span> en el repo (https://github.com/logo3x/SVD).
    </p>
    <p class="small">
        Documento auto-generado vía <span class="mono">php artisan svd:manual</span>. Para regenerar después de cambios en el sistema, vuelve a ejecutar el comando.
    </p>
</div>

</body>
</html>
