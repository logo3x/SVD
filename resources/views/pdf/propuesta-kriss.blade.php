<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Propuesta económica — SVD para Agua Kriss</title>
    <style>
        @page { margin: 40px 45px 40px 45px; }
        * { box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; color: #1f2937; font-size: 9.5pt; line-height: 1.35; margin: 0; }
        h1, h2, h3 { color: #0c3a64; margin: 0 0 4px; }
        h2 { font-size: 11pt; border-bottom: 1.5px solid #0c3a64; padding-bottom: 2px; margin-top: 12px; text-transform: uppercase; letter-spacing: 0.5px; }
        h3 { font-size: 10pt; margin-top: 8px; color: #1d4ed8; }
        p { margin: 4px 0; text-align: justify; }
        small { color: #6b7280; font-size: 8pt; }
        .header { border-bottom: 2px solid #0c3a64; padding-bottom: 6px; margin-bottom: 10px; display: table; width: 100%; }
        .header .brand { font-size: 20pt; font-weight: bold; color: #0c3a64; letter-spacing: 1.5px; display: table-cell; vertical-align: bottom; }
        .header .meta-h { display: table-cell; text-align: right; vertical-align: bottom; font-size: 8.5pt; color: #6b7280; }
        .header .meta-h strong { color: #0c3a64; }
        .sub { color: #6b7280; font-size: 9pt; letter-spacing: 0.5px; text-transform: uppercase; margin-bottom: 8px; }
        .destinatario { background: #f3f4f6; border-left: 3px solid #0c3a64; padding: 6px 10px; margin: 8px 0; font-size: 9pt; }
        .destinatario strong { color: #0c3a64; font-size: 10pt; }
        ul { margin: 4px 0 4px 16px; padding: 0; font-size: 9pt; }
        li { margin-bottom: 2px; }
        table.cols { width: 100%; border-collapse: collapse; }
        table.cols td { vertical-align: top; padding: 0; }
        table.cols td.col-l { width: 50%; padding-right: 8px; }
        table.cols td.col-r { width: 50%; padding-left: 8px; }
        table.precios { width: 100%; border-collapse: collapse; margin: 4px 0; font-size: 9pt; }
        table.precios th, table.precios td { border: 1px solid #d1d5db; padding: 5px 7px; text-align: left; }
        table.precios th { background: #0c3a64; color: white; font-size: 8.5pt; text-transform: uppercase; letter-spacing: 0.3px; }
        table.precios td.valor { text-align: right; font-weight: bold; color: #0c3a64; white-space: nowrap; }
        table.precios .destacado { background: #fef3c7; }
        table.precios .destacado td.valor { color: #78350f; font-size: 10pt; }
        .compact-list { font-size: 8.8pt; line-height: 1.3; }
        .compact-list .col { width: 49%; display: inline-block; vertical-align: top; }
        .compact-list b { color: #0c3a64; }
        .nota { background: #eff6ff; border-left: 3px solid #1d4ed8; padding: 5px 8px; margin: 6px 0; font-size: 8.5pt; color: #1e3a8a; }
        .alerta { background: #fef3c7; border-left: 3px solid #d97706; padding: 5px 8px; margin: 6px 0; font-size: 8.5pt; color: #78350f; }
        .firma { margin-top: 18px; padding-top: 8px; border-top: 1px solid #d1d5db; font-size: 9pt; }
        .firma .linea { display: table; width: 100%; }
        .firma .linea .nombre { display: table-cell; }
        .firma .linea .nombre strong { color: #0c3a64; font-size: 10pt; }
        .pie { text-align: center; font-size: 8pt; color: #9ca3af; margin-top: 8px; }
        .badge-precio { display: inline-block; background: #0c3a64; color: white; padding: 1px 6px; border-radius: 3px; font-size: 8pt; font-weight: bold; margin-left: 4px; }
        .check { color: #16a34a; font-weight: bold; }
    </style>
</head>
<body>

<div class="header">
    <div class="brand">SVD</div>
    <div class="meta-h">
        Propuesta <strong>{{ $propuesta_numero }}</strong><br>
        {{ $fecha }} · validez 30 días
    </div>
</div>

<div class="sub">Sistema de Ventas y Despachos · Propuesta económica</div>

<div class="destinatario">
    Dirigido a <strong>Agua Kriss</strong> — Barrancabermeja, Santander
</div>

<p>
    Implementación de <strong>SVD — Sistema de Ventas y Despachos</strong>: plataforma para
    gestionar catálogo, clientes, remisiones, vendedores y reportes. Incluye
    <strong>panel web administrativo</strong> y <strong>aplicación móvil Android</strong> totalmente
    integradas (Laravel 13 + Filament v5 + API REST + Android nativa).
</p>

<h2>Alcance del sistema</h2>

<table class="cols">
    <tr>
        <td class="col-l">
            <h3>Panel web</h3>
            <ul>
                <li><span class="check">✓</span> Catálogo de productos con precio maestro único.</li>
                <li><span class="check">✓</span> Clientes con <b>precio especial</b> por cliente (override).</li>
                <li><span class="check">✓</span> Emisión de remisiones con firma digital y PDF.</li>
                <li><span class="check">✓</span> Envío automático del comprobante por correo.</li>
                <li><span class="check">✓</span> Reportes filtrables y exportables a Excel.</li>
                <li><span class="check">✓</span> Dashboard con gráficas (ventas, top vendedores/clientes/productos).</li>
                <li><span class="check">✓</span> Gestión de empleados, usuarios, roles y permisos.</li>
                <li><span class="check">✓</span> Personalización de marca (logo, datos, correos de routing).</li>
                <li><span class="check">✓</span> Bitácora de auditoría de cambios.</li>
            </ul>
        </td>
        <td class="col-r">
            <h3>Aplicación móvil Android</h3>
            <ul>
                <li><span class="check">✓</span> Login seguro por token (Sanctum).</li>
                <li><span class="check">✓</span> Catálogo de clientes con precios resueltos.</li>
                <li><span class="check">✓</span> Emisión de remisiones en terreno (cálculo en vivo).</li>
                <li><span class="check">✓</span> Captura de firma del cliente en pantalla.</li>
                <li><span class="check">✓</span> GPS opcional del punto de entrega.</li>
                <li><span class="check">✓</span> Descarga de reportes propios a Excel.</li>
                <li><span class="check">✓</span> Modo administrador en la app móvil.</li>
                <li><span class="check">✓</span> Control remoto: revocar dispositivos, modo mantenimiento, forzar actualización.</li>
            </ul>
        </td>
    </tr>
</table>

<h2>Inversión (pago único)</h2>

<table class="precios">
    <thead>
        <tr>
            <th>Concepto</th>
            <th style="text-align: right;">Valor (COP)</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><strong>Plataforma Web SVD</strong> — Panel admin + vendedor web + reportes + API</td>
            <td class="valor">$ 5.500.000</td>
        </tr>
        <tr>
            <td><strong>Aplicación móvil Android</strong> — APK firmada instalable</td>
            <td class="valor">$ 3.800.000</td>
        </tr>
        <tr class="destacado">
            <td><strong>Paquete completo Web + Móvil</strong> (recomendado · ahorra $800.000)</td>
            <td class="valor">$ 8.500.000</td>
        </tr>
    </tbody>
</table>
<small>Valores en pesos colombianos, no incluye IVA. Pago único — el cliente recibe el sistema instalado y operando.</small>

<h2>Servicios opcionales</h2>

<table class="precios">
    <tr>
        <td>Carga inicial de productos (hasta 100)</td><td class="valor">$ 450.000</td>
        <td>Capacitación presencial (8 h, Barrancabermeja)</td><td class="valor">$ 700.000</td>
    </tr>
    <tr>
        <td>Carga inicial de clientes (hasta 200)</td><td class="valor">$ 650.000</td>
        <td>Capacitación virtual (4 h por Meet)</td><td class="valor">$ 350.000</td>
    </tr>
    <tr>
        <td>Precios especiales por cliente (hasta 500 overrides)</td><td class="valor">$ 550.000</td>
        <td>Personalización adicional (por hora)</td><td class="valor">$ 80.000 / h</td>
    </tr>
    <tr>
        <td colspan="3">Soporte técnico mensual post-implementación (opcional)</td>
        <td class="valor">$ 350.000 / mes</td>
    </tr>
</table>

<div class="alerta">
    <strong>Importante sobre la carga de datos:</strong> la carga inicial de clientes, productos
    y la configuración de precios especiales es <strong>responsabilidad del cliente</strong>
    (el panel cuenta con formularios sencillos). Si Agua Kriss prefiere que nuestro equipo lo
    realice, se cotiza como servicio adicional según los valores anteriores.
</div>

{{-- ===== PÁGINA 2 ===== --}}
<div style="page-break-before: always;"></div>

<div class="header">
    <div class="brand">SVD</div>
    <div class="meta-h">
        Propuesta <strong>{{ $propuesta_numero }}</strong> · Página 2 de 2
    </div>
</div>

<h2>Tiempos de entrega</h2>
<table class="precios">
    <tr>
        <td>Despliegue de la plataforma web en servidor del cliente</td>
        <td class="valor">3 días hábiles</td>
    </tr>
    <tr>
        <td>Instalación, configuración inicial y entrega de APK firmada</td>
        <td class="valor">3 días hábiles</td>
    </tr>
    <tr>
        <td>Acompañamiento puesta en producción</td>
        <td class="valor">5 días hábiles</td>
    </tr>
    <tr class="destacado">
        <td><strong>Total estimado puesta en marcha</strong></td>
        <td class="valor"><strong>2 semanas</strong></td>
    </tr>
</table>

<h2>Forma de pago</h2>
<ul>
    <li><strong>50%</strong> a la firma del contrato.</li>
    <li><strong>30%</strong> al despliegue de la plataforma web en el servidor.</li>
    <li><strong>20%</strong> a la entrega de la APK Android y verificación final.</li>
</ul>

<h2>Garantía e incluido</h2>
<table class="cols">
    <tr>
        <td class="col-l">
            <ul>
                <li>Código fuente entregable con derechos de uso para Agua Kriss.</li>
                <li>Garantía de funcionamiento de <strong>3 meses</strong>.</li>
                <li>Manual de usuario y guía rápida en digital.</li>
                <li>Cuentas de Super Administrador configuradas.</li>
                <li>Soporte por correo durante la garantía sin costo.</li>
            </ul>
        </td>
        <td class="col-r">
            <h3 style="margin-top: 0;">No incluye</h3>
            <ul>
                <li>Servidor / hosting (lo provee el cliente; se asesora).</li>
                <li>Certificado SSL/HTTPS (Let's Encrypt gratis; se asesora).</li>
                <li>Dominio propio (registro a cargo de Agua Kriss).</li>
                <li>Carga inicial de datos (salvo contrato adicional).</li>
                <li>Integraciones con ERP/sistemas contables externos.</li>
            </ul>
        </td>
    </tr>
</table>

<div class="nota">
    <strong>Próximo paso:</strong> al aceptar esta propuesta, confirmar por correo o WhatsApp para
    proceder con la firma del acuerdo y la emisión de la primera factura. Quedo atento a cualquier
    consulta o ajuste que requieran.
</div>

<div class="firma">
    <p>Cordialmente,</p>
    <div class="linea">
        <div class="nombre">
            <strong>{{ $proveedor_nombre }}</strong> — Desarrollador de software<br>
            <small>{{ $proveedor_email }} · {{ $proveedor_telefono }}</small>
        </div>
    </div>
</div>

<div class="pie">
    Propuesta {{ $propuesta_numero }} · Generada el {{ $fecha }} · Confidencial — solo para Agua Kriss
</div>

</body>
</html>
