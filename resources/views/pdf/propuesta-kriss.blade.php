<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Propuesta económica — SVD para Agua Kriss</title>
    <style>
        @page { margin: 80px 60px 80px 60px; }
        * { box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; color: #1f2937; font-size: 11pt; line-height: 1.55; margin: 0; }
        h1, h2, h3 { color: #0c3a64; margin: 0 0 8px; }
        h1 { font-size: 22pt; }
        h2 { font-size: 14pt; border-bottom: 2px solid #0c3a64; padding-bottom: 4px; margin-top: 28px; }
        h3 { font-size: 12pt; margin-top: 18px; color: #1d4ed8; }
        p { margin: 6px 0; text-align: justify; }
        small { color: #6b7280; font-size: 9pt; }
        .header { border-bottom: 3px solid #0c3a64; padding-bottom: 12px; margin-bottom: 24px; }
        .header .brand { font-size: 26pt; font-weight: bold; color: #0c3a64; letter-spacing: 2px; }
        .header .sub { color: #6b7280; font-size: 10pt; letter-spacing: 1px; text-transform: uppercase; }
        .meta { width: 100%; font-size: 10pt; color: #374151; margin-bottom: 18px; }
        .meta td { padding: 4px 0; }
        .meta .label { color: #6b7280; width: 130px; text-transform: uppercase; font-size: 9pt; letter-spacing: 0.5px; }
        .destinatario { background: #f3f4f6; border-left: 4px solid #0c3a64; padding: 12px 16px; margin: 18px 0; }
        .destinatario .titulo { font-size: 9pt; color: #6b7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 4px; }
        .destinatario .nombre { font-size: 14pt; font-weight: bold; color: #0c3a64; }
        .destinatario .ciudad { color: #4b5563; font-size: 10pt; }
        ul { margin: 6px 0 6px 18px; padding: 0; }
        li { margin-bottom: 4px; }
        table.precios { width: 100%; border-collapse: collapse; margin: 14px 0; }
        table.precios th, table.precios td { border: 1px solid #d1d5db; padding: 10px 12px; text-align: left; }
        table.precios th { background: #0c3a64; color: white; font-size: 10pt; text-transform: uppercase; letter-spacing: 0.5px; }
        table.precios td.valor { text-align: right; font-weight: bold; color: #0c3a64; white-space: nowrap; }
        table.precios .destacado { background: #fef3c7; }
        table.precios .total td { background: #0c3a64; color: white; font-weight: bold; font-size: 12pt; }
        .modulos { width: 100%; border-collapse: collapse; margin: 8px 0; }
        .modulos td { padding: 6px 8px; border-bottom: 1px solid #e5e7eb; font-size: 10pt; vertical-align: top; }
        .modulos td.icon { width: 24px; color: #16a34a; font-weight: bold; }
        .nota { background: #eff6ff; border-left: 4px solid #1d4ed8; padding: 10px 14px; margin: 14px 0; font-size: 10pt; color: #1e3a8a; }
        .alerta { background: #fef3c7; border-left: 4px solid #d97706; padding: 10px 14px; margin: 14px 0; font-size: 10pt; color: #78350f; }
        .firma { margin-top: 50px; }
        .firma .linea { border-top: 1px solid #6b7280; width: 50%; padding-top: 6px; font-size: 10pt; color: #374151; }
        .pie { position: fixed; bottom: -50px; left: 0; right: 0; text-align: center; font-size: 9pt; color: #9ca3af; }
        .badge { display: inline-block; background: #dbeafe; color: #1e3a8a; padding: 2px 8px; border-radius: 4px; font-size: 9pt; font-weight: bold; }
    </style>
</head>
<body>

<div class="header">
    <div class="brand">SVD</div>
    <div class="sub">Sistema de Ventas y Despachos · Propuesta económica</div>
</div>

<table class="meta">
    <tr><td class="label">Propuesta N°</td><td><strong>{{ $propuesta_numero }}</strong></td></tr>
    <tr><td class="label">Fecha</td><td>{{ $fecha }}</td></tr>
    <tr><td class="label">Validez</td><td>30 días calendario</td></tr>
</table>

<div class="destinatario">
    <div class="titulo">Dirigido a</div>
    <div class="nombre">Agua Kriss</div>
    <div class="ciudad">Barrancabermeja, Santander</div>
</div>

<p>
    Reciban un cordial saludo. La presente propuesta detalla la implementación de <strong>SVD —
    Sistema de Ventas y Despachos</strong>, una plataforma completa diseñada para gestionar el
    catálogo de productos, clientes, remisiones de venta, vendedores en campo y reportes
    operacionales. La solución incluye un <strong>panel web administrativo</strong> y una
    <strong>aplicación móvil Android</strong> para los vendedores, totalmente integradas.
</p>

<h2>1. Alcance del sistema</h2>

<h3>Panel web administrativo</h3>
<table class="modulos">
    <tr><td class="icon">✓</td><td>Gestión de catálogo de productos con precio único maestro.</td></tr>
    <tr><td class="icon">✓</td><td>Gestión de clientes con <strong>precios especiales por cliente</strong> (override del precio base).</td></tr>
    <tr><td class="icon">✓</td><td>Emisión y consulta de remisiones de venta con captura de firma digital.</td></tr>
    <tr><td class="icon">✓</td><td>Comprobante PDF descargable con logo y datos de la empresa.</td></tr>
    <tr><td class="icon">✓</td><td>Envío automático del comprobante por correo según tipo de pago (contado, crédito, etc.).</td></tr>
    <tr><td class="icon">✓</td><td>Reportes filtrables (fecha, cliente, vendedor, tipo de pago, estado) exportables a Excel.</td></tr>
    <tr><td class="icon">✓</td><td>Tablero con gráficas: ventas por día, top vendedores, top clientes, productos más vendidos, ventas por tipo de pago y por ruta.</td></tr>
    <tr><td class="icon">✓</td><td>Gestión de empleados (RRHH) con o sin acceso al sistema.</td></tr>
    <tr><td class="icon">✓</td><td>Gestión de usuarios, roles y permisos: Super Administrador, Administrador y Vendedor.</td></tr>
    <tr><td class="icon">✓</td><td>Personalización de la marca (logo, datos de empresa, correos de enrutamiento).</td></tr>
    <tr><td class="icon">✓</td><td>Bitácora de auditoría: registro de quién cambió qué y cuándo.</td></tr>
    <tr><td class="icon">✓</td><td>Panel del vendedor (web) para ver y emitir sus propias remisiones desde el computador.</td></tr>
</table>

<h3>Aplicación móvil Android (APK)</h3>
<table class="modulos">
    <tr><td class="icon">✓</td><td>Inicio de sesión seguro por token (Sanctum).</td></tr>
    <tr><td class="icon">✓</td><td>Catálogo de clientes con sus productos y precios resueltos automáticamente.</td></tr>
    <tr><td class="icon">✓</td><td>Emisión de remisiones en terreno con cálculo de totales en vivo.</td></tr>
    <tr><td class="icon">✓</td><td>Captura de firma del cliente directamente en la pantalla del celular.</td></tr>
    <tr><td class="icon">✓</td><td>Captura opcional de coordenadas GPS del punto de entrega.</td></tr>
    <tr><td class="icon">✓</td><td>Descarga de reportes propios en Excel desde el celular.</td></tr>
    <tr><td class="icon">✓</td><td>Modo administrador en la app: acceso a todas las remisiones, dispositivos conectados y configuración.</td></tr>
    <tr><td class="icon">✓</td><td>Control remoto de la flota: revocar dispositivos (kill switch), forzar actualización de la app, modo mantenimiento.</td></tr>
</table>

<div class="nota">
    <strong>Tecnologías:</strong> backend Laravel 13 + Filament v5 + PHP 8.5 + MySQL.
    Aplicación móvil Android nativa que consume API REST con autenticación por token.
    Arquitectura escalable, código fuente entregable y sistema preparado para alta concurrencia.
</div>

<h2>2. Inversión</h2>

<table class="precios">
    <thead>
        <tr>
            <th>Concepto</th>
            <th style="text-align: right;">Valor (COP)</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                <strong>Plataforma Web SVD</strong> — pago único<br>
                <small>Panel administrativo + panel vendedor web + reportes + dashboard + API</small>
            </td>
            <td class="valor">$ 5.500.000</td>
        </tr>
        <tr>
            <td>
                <strong>Aplicación móvil Android</strong> — pago único<br>
                <small>APK firmada, instalable en celulares Android de los vendedores</small>
            </td>
            <td class="valor">$ 3.800.000</td>
        </tr>
        <tr class="destacado">
            <td>
                <strong>Paquete completo Web + Móvil</strong><br>
                <small>Ahorra $800.000 contratando ambos productos juntos</small>
            </td>
            <td class="valor">$ 8.500.000</td>
        </tr>
        <tr class="total">
            <td><strong>TOTAL paquete completo (recomendado)</strong></td>
            <td class="valor">$ 8.500.000</td>
        </tr>
    </tbody>
</table>

<p><small>* Valores en pesos colombianos. No incluye IVA. Modalidad: <strong>pago único</strong>. El cliente recibe el sistema instalado y operando.</small></p>

<h2>3. Servicios opcionales</h2>

<p>Los siguientes servicios son opcionales y se cotizan por separado según las necesidades de Agua Kriss:</p>

<table class="precios">
    <thead>
        <tr>
            <th>Servicio</th>
            <th style="text-align: right;">Valor (COP)</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                <strong>Carga inicial de catálogo de productos</strong><br>
                <small>Migración de hasta 100 productos con SKU, precio, categoría y unidad</small>
            </td>
            <td class="valor">$ 450.000</td>
        </tr>
        <tr>
            <td>
                <strong>Carga inicial de clientes</strong><br>
                <small>Migración de hasta 200 clientes con datos completos</small>
            </td>
            <td class="valor">$ 650.000</td>
        </tr>
        <tr>
            <td>
                <strong>Configuración de precios especiales por cliente</strong><br>
                <small>Hasta 500 overrides de precio</small>
            </td>
            <td class="valor">$ 550.000</td>
        </tr>
        <tr>
            <td>
                <strong>Capacitación al equipo (presencial Barrancabermeja)</strong><br>
                <small>Hasta 8 horas, incluye materiales y manuales</small>
            </td>
            <td class="valor">$ 700.000</td>
        </tr>
        <tr>
            <td>
                <strong>Capacitación virtual</strong><br>
                <small>Sesiones grabadas vía Google Meet, hasta 4 horas</small>
            </td>
            <td class="valor">$ 350.000</td>
        </tr>
        <tr>
            <td>
                <strong>Personalización adicional</strong> (logo, colores, ajustes específicos)<br>
                <small>Según requerimientos, se cotiza por hora</small>
            </td>
            <td class="valor">$ 80.000 / hora</td>
        </tr>
        <tr>
            <td>
                <strong>Soporte técnico mensual</strong> (opcional, post-implementación)<br>
                <small>Atención remota, correcciones y consultas</small>
            </td>
            <td class="valor">$ 350.000 / mes</td>
        </tr>
    </tbody>
</table>

<div class="alerta">
    <strong>Importante sobre la carga de datos:</strong> la <strong>carga inicial de clientes,
    productos y la configuración de precios especiales</strong> es responsabilidad del cliente y
    se realiza directamente desde el panel web (cuenta con formularios sencillos para hacerlo).
    En caso de que Agua Kriss prefiera que este trabajo lo realice nuestro equipo, se cotiza
    como servicio adicional según la tabla anterior.
</div>

<h2>4. Tiempos de entrega</h2>

<table class="precios">
    <thead>
        <tr>
            <th>Fase</th>
            <th style="text-align: right;">Tiempo</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Despliegue de la plataforma web en servidor del cliente</td>
            <td class="valor">3 días hábiles</td>
        </tr>
        <tr>
            <td>Instalación y configuración inicial</td>
            <td class="valor">2 días hábiles</td>
        </tr>
        <tr>
            <td>Entrega de APK firmada</td>
            <td class="valor">1 día hábil</td>
        </tr>
        <tr>
            <td>Acompañamiento puesta en producción</td>
            <td class="valor">5 días hábiles</td>
        </tr>
        <tr class="destacado">
            <td><strong>Total estimado puesta en marcha</strong></td>
            <td class="valor"><strong>2 semanas</strong></td>
        </tr>
    </tbody>
</table>

<h2>5. Forma de pago</h2>
<ul>
    <li><strong>50%</strong> a la firma del contrato.</li>
    <li><strong>30%</strong> al despliegue de la plataforma web en el servidor.</li>
    <li><strong>20%</strong> a la entrega de la APK Android y verificación final.</li>
</ul>

<h2>6. Garantía e incluye</h2>
<ul>
    <li>Código fuente entregable al cliente (con derechos de uso ilimitado para Agua Kriss).</li>
    <li>Garantía de funcionamiento de <strong>3 meses</strong> sobre las funcionalidades entregadas.</li>
    <li>Manual de usuario web y guía rápida en formato digital.</li>
    <li>Cuentas de usuario inicial (Super Administrador) configuradas y entregadas.</li>
    <li>Soporte por correo durante el periodo de garantía sin costo adicional.</li>
</ul>

<h2>7. No incluye</h2>
<ul>
    <li>Servidor / hosting (lo provee el cliente — se puede asesorar en la elección).</li>
    <li>Certificado SSL/HTTPS (Let's Encrypt es gratuito; se puede asesorar en la instalación).</li>
    <li>Dominio propio del cliente (registro y renovación a cargo de Agua Kriss).</li>
    <li>Carga inicial de datos (clientes/productos/precios) salvo que se contrate como servicio adicional.</li>
    <li>Integraciones con sistemas externos (contables, ERP) no listadas explícitamente.</li>
</ul>

<div class="nota">
    <strong>Próximo paso:</strong> de aceptar esta propuesta, agradezco confirmar por correo o
    WhatsApp y procedemos a la firma del acuerdo y emisión de la primera factura.
</div>

<div class="firma">
    <p>Cordialmente,</p>
    <br><br>
    <div class="linea">
        <strong>{{ $proveedor_nombre }}</strong><br>
        <small>Desarrollador de software · {{ $proveedor_email }} · {{ $proveedor_telefono }}</small>
    </div>
</div>

<div class="pie">
    Propuesta {{ $propuesta_numero }} · Generada el {{ $fecha }} · Confidencial — solo para Agua Kriss
</div>

</body>
</html>
