<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="SVD — Sistema de Ventas y Despachos para distribución de hielo, agua y neveras. Catálogo maestro, rutas de reparto, comprobantes PDF y app móvil para vendedor.">
    <meta name="theme-color" content="#0a2540">
    <title>SVD · Sistema de Ventas y Despachos</title>

    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased">

{{-- ============================================================
     HEADER · Identidad + navegación corporativa
============================================================ --}}
<header class="nav-shell sticky top-0 z-30">
    <div class="mx-auto max-w-[1320px] px-6 lg:px-10">
        <div class="flex items-center justify-between gap-4 py-4">
            <a href="/" class="flex items-center gap-3 group">
                <span class="brand-mark" aria-hidden="true">
                    <svg viewBox="0 0 32 32" width="20" height="20" fill="none">
                        <path d="M16 3L27 9.5V22.5L16 29L5 22.5V9.5L16 3Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
                        <path d="M16 10L22 13.5V20L16 23L10 20V13.5L16 10Z" fill="currentColor" opacity="0.18"/>
                        <path d="M16 14V20M13 17L16 18.5L19 17" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
                    </svg>
                </span>
                <div class="flex flex-col leading-tight">
                    <span class="brand-name">SVD</span>
                    <span class="brand-tagline">Sistema de Ventas y Despachos</span>
                </div>
            </a>

            <nav class="hidden lg:flex items-center gap-8">
                <a href="#catalogo" class="nav-link">Catálogo</a>
                <a href="#operacion" class="nav-link">Operación</a>
                <a href="#flujo" class="nav-link">Flujo</a>
                <a href="#metricas" class="nav-link">Métricas</a>
                <a href="#acceso" class="nav-link">Acceso</a>
            </nav>

            <div class="flex items-center gap-2.5">
                <a href="/vendedor/login" class="hidden sm:inline-flex btn btn-secondary">
                    Vendedor
                </a>
                <a href="/admin/login" class="btn btn-primary">
                    Iniciar sesión
                </a>
            </div>
        </div>
    </div>
</header>

{{-- ============================================================
     HERO
============================================================ --}}
<section class="hero-shell">
    <div aria-hidden="true" class="absolute inset-0 bg-grid pointer-events-none"></div>
    <div class="relative mx-auto max-w-[1320px] px-6 lg:px-10 pt-16 lg:pt-24 pb-16 lg:pb-24">

        <div class="grid grid-cols-12 gap-8 items-start">

            <div class="col-span-12 lg:col-span-7 rise rise-1">
                <div class="hero-pill mb-7">
                    <span class="dot-pulse"></span>
                    En operación · Barrancabermeja, Santander · CO
                </div>

                <h1 class="heading-xl mb-6">
                    Distribución de <span class="hl-primary">hielo y agua</span>,<br>
                    gestionada de extremo a extremo.
                </h1>

                <p class="lead max-w-[58ch] mb-9">
                    SVD es la plataforma institucional para administrar el catálogo,
                    los clientes, las rutas de reparto y los comprobantes firmados —
                    sin papel, sin Excel paralelos y con auditoría completa.
                </p>

                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="/admin/login" class="btn btn-primary">
                        Acceder al panel
                        <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M1 7H13M13 7L7 1M13 7L7 13" stroke="currentColor" stroke-width="1.5"/></svg>
                    </a>
                    <a href="#catalogo" class="btn btn-secondary">
                        Ver catálogo
                    </a>
                </div>

                <div class="mt-10 grid grid-cols-3 gap-6 max-w-md">
                    <div>
                        <div class="font-mono text-[11px] tracking-[0.14em] uppercase text-mute mb-1">Productos</div>
                        <div class="heading-md">31</div>
                    </div>
                    <div>
                        <div class="font-mono text-[11px] tracking-[0.14em] uppercase text-mute mb-1">Rutas</div>
                        <div class="heading-md">16</div>
                    </div>
                    <div>
                        <div class="font-mono text-[11px] tracking-[0.14em] uppercase text-mute mb-1">Permisos</div>
                        <div class="heading-md">76</div>
                    </div>
                </div>
            </div>

            <div class="col-span-12 lg:col-span-5 rise rise-3">
                <div class="hero-panel relative bg-white border border-[var(--color-rule)] rounded-xl p-6 lg:p-8 shadow-[var(--shadow-lg)]">
                    <div class="flex items-center justify-between mb-5">
                        <div class="flex items-center gap-2.5">
                            <span class="w-2 h-2 rounded-full" style="background-color: var(--color-success);"></span>
                            <span class="mono text-[11px] tracking-[0.14em] uppercase text-mute">REMISIÓN · LIVE</span>
                        </div>
                        <span class="mono text-[11px] text-mute">#R-{{ now()->format('Ymd') }}-014</span>
                    </div>

                    <div class="space-y-3 mb-5">
                        <div class="flex justify-between items-baseline py-2 border-b border-[var(--color-rule)]">
                            <span class="text-[13.5px] font-medium">HIELO 5K</span>
                            <div class="flex items-baseline gap-3">
                                <span class="mono text-[12px] text-mute">12 ud</span>
                                <span class="font-semibold">$93.600</span>
                            </div>
                        </div>
                        <div class="flex justify-between items-baseline py-2 border-b border-[var(--color-rule)]">
                            <span class="text-[13.5px] font-medium">KRISS 600 ML · paq x24</span>
                            <div class="flex items-baseline gap-3">
                                <span class="mono text-[12px] text-mute">3 paq</span>
                                <span class="font-semibold">$114.000</span>
                            </div>
                        </div>
                        <div class="flex justify-between items-baseline py-2 border-b border-[var(--color-rule)]">
                            <span class="text-[13.5px] font-medium">NEVERA ICOPOR · M</span>
                            <div class="flex items-baseline gap-3">
                                <span class="mono text-[12px] text-mute">2 ud</span>
                                <span class="font-semibold">$28.000</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-baseline justify-between pt-3 border-t-2" style="border-color: var(--color-primary);">
                        <div>
                            <div class="mono text-[10px] tracking-[0.14em] uppercase text-mute">TOTAL · CRÉDITO</div>
                            <div class="mono text-[11px] text-mute mt-1">RUTA 03 · BARRANCA SUR</div>
                        </div>
                        <div class="heading-md" style="color: var(--color-primary);">$235.600</div>
                    </div>

                    <div class="mt-5 flex items-center justify-between text-[12px]">
                        <div class="flex items-center gap-2 text-mute">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            <span class="mono">7.0651°N · 73.8547°W</span>
                        </div>
                        <span class="hero-pill" style="background-color: var(--color-primary-soft); color: var(--color-primary); border-color: transparent; padding: 0.15rem 0.55rem; font-size: 11px;">FIRMADA</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ============================================================
     STAT STRIP
============================================================ --}}
<section class="stat-strip">
    <div class="mx-auto max-w-[1320px] px-6 lg:px-10 py-8 grid grid-cols-2 md:grid-cols-4 gap-y-6 gap-x-8">
        <div class="stat-strip-item">
            <span class="stat-strip-num">31</span>
            <span class="stat-strip-label">Productos en catálogo</span>
        </div>
        <div class="stat-strip-item">
            <span class="stat-strip-num">16</span>
            <span class="stat-strip-label">Rutas de reparto</span>
        </div>
        <div class="stat-strip-item">
            <span class="stat-strip-num">14</span>
            <span class="stat-strip-label">Marcas de agua</span>
        </div>
        <div class="stat-strip-item">
            <span class="stat-strip-num">100%</span>
            <span class="stat-strip-label">Entregas firmadas</span>
        </div>
    </div>
</section>

{{-- ============================================================
     SEC · CATÁLOGO
============================================================ --}}
<section id="catalogo" class="section-shell">
    <div class="mx-auto max-w-[1320px] px-6 lg:px-10 pt-20 lg:pt-28 pb-20 lg:pb-28">

        <div class="grid grid-cols-12 gap-8 mb-12 items-end">
            <div class="col-span-12 lg:col-span-7">
                <div class="eyebrow mb-3">CATÁLOGO MAESTRO</div>
                <h2 class="heading-lg mb-4">
                    Cuatro familias de producto.<br>
                    <span class="hl-primary">Un único origen de la verdad.</span>
                </h2>
                <p class="lead max-w-[60ch]">
                    Todo el portafolio administrado desde un solo catálogo. Precios maestros,
                    overrides por cliente y precio histórico congelado en cada remisión.
                </p>
            </div>
            <div class="col-span-12 lg:col-span-5 lg:text-right">
                <a href="/admin/login" class="btn btn-secondary">
                    Administrar catálogo
                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M1 7H13M13 7L7 1M13 7L7 13" stroke="currentColor" stroke-width="1.5"/></svg>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">

            {{-- Hielo --}}
            <article class="product-card">
                <div class="flex items-start justify-between mb-5">
                    <span class="product-card-badge">Familia · 01</span>
                    <span class="product-card-count">03</span>
                </div>
                <div class="product-illus mb-5">
                    <svg viewBox="0 0 120 120" width="100%" height="110" fill="none">
                        <rect x="30" y="42" width="60" height="63" rx="4" stroke="currentColor" stroke-width="1.6" fill="var(--color-frost)"/>
                        <path d="M40 42 Q42 30 50 26 Q60 22 70 26 Q80 30 80 42" stroke="currentColor" stroke-width="1.4" fill="none"/>
                        <path d="M44 60 L48 64 L52 60 M58 60 L62 64 L66 60 M72 60 L76 64 L80 60" stroke="currentColor" stroke-width="0.9" stroke-linecap="round"/>
                        <text x="60" y="86" text-anchor="middle" font-family="Instrument Sans" font-size="11" font-weight="600" fill="currentColor">HIELO 5K</text>
                    </svg>
                </div>
                <h3 class="heading-sm mb-2">Hielo</h3>
                <p class="text-[13.5px] leading-[1.6] text-[color:var(--color-ink-soft)] mb-3">
                    Bolsa de 1.5K, 5K y 15K. El producto estrella de la operación.
                </p>
                <div class="mono text-[11px] text-mute mt-auto pt-3 border-t border-[var(--color-rule)]">
                    SKU H-1500 · H-5000 · H-15000
                </div>
            </article>

            {{-- Agua --}}
            <article class="product-card">
                <div class="flex items-start justify-between mb-5">
                    <span class="product-card-badge">Familia · 02</span>
                    <span class="product-card-count">22</span>
                </div>
                <div class="product-illus mb-5">
                    <svg viewBox="0 0 120 120" width="100%" height="110" fill="none">
                        <path d="M52 24H68V32L72 38V102C72 106 68 110 64 110H56C52 110 48 106 48 102V38L52 32V24Z" stroke="currentColor" stroke-width="1.6" fill="var(--color-frost)"/>
                        <rect x="46" y="60" width="28" height="22" fill="var(--color-surface)" stroke="currentColor" stroke-width="1.2"/>
                        <text x="60" y="71" text-anchor="middle" font-family="Instrument Sans" font-size="7" font-weight="600" fill="currentColor">KRISS</text>
                        <text x="60" y="79" text-anchor="middle" font-family="JetBrains Mono" font-size="6" fill="var(--color-accent)">600 ML</text>
                    </svg>
                </div>
                <h3 class="heading-sm mb-2">Agua envasada</h3>
                <p class="text-[13.5px] leading-[1.6] text-[color:var(--color-ink-soft)] mb-3">
                    14 marcas (KRISS, EDEN, MANA, CRISTAL…) en botella, paquete x24 y caja.
                </p>
                <div class="mono text-[11px] text-mute mt-auto pt-3 border-t border-[var(--color-rule)]">
                    600 ml · 1.5 L · 5 L · paq · caja
                </div>
            </article>

            {{-- Envases --}}
            <article class="product-card">
                <div class="flex items-start justify-between mb-5">
                    <span class="product-card-badge">Familia · 03</span>
                    <span class="product-card-count">03</span>
                </div>
                <div class="product-illus mb-5">
                    <svg viewBox="0 0 120 120" width="100%" height="110" fill="none">
                        <path d="M40 30H80V42L84 50V96C84 102 80 106 74 106H46C40 106 36 102 36 96V50L40 42V30Z" stroke="currentColor" stroke-width="1.6" stroke-dasharray="3 2.5" fill="none"/>
                        <path d="M50 58L70 78 M70 58L50 78" stroke="var(--color-accent)" stroke-width="1.6" stroke-linecap="round"/>
                        <text x="60" y="98" text-anchor="middle" font-family="Instrument Sans" font-size="9" font-weight="500" fill="currentColor">RETORNO</text>
                    </svg>
                </div>
                <h3 class="heading-sm mb-2">Envases vacíos</h3>
                <p class="text-[13.5px] leading-[1.6] text-[color:var(--color-ink-soft)] mb-3">
                    Devolución de KRISS o EDEN. Descuento aplicado al cliente en la remisión.
                </p>
                <div class="mono text-[11px] text-mute mt-auto pt-3 border-t border-[var(--color-rule)]">
                    EV-KRISS · EV-EDEN
                </div>
            </article>

            {{-- Neveras --}}
            <article class="product-card">
                <div class="flex items-start justify-between mb-5">
                    <span class="product-card-badge">Familia · 04</span>
                    <span class="product-card-count">03</span>
                </div>
                <div class="product-illus mb-5">
                    <svg viewBox="0 0 120 120" width="100%" height="110" fill="none">
                        <rect x="22" y="44" width="76" height="56" rx="3" stroke="currentColor" stroke-width="1.6" fill="var(--color-surface)"/>
                        <rect x="22" y="44" width="76" height="14" rx="3" stroke="currentColor" stroke-width="1.6" fill="var(--color-frost)"/>
                        <path d="M40 38V32Q40 28 44 28L76 28Q80 28 80 32V38" stroke="currentColor" stroke-width="1.4" fill="none"/>
                        <line x1="22" y1="72" x2="98" y2="72" stroke="currentColor" stroke-width="0.6" stroke-dasharray="2 2"/>
                        <text x="60" y="92" text-anchor="middle" font-family="Instrument Sans" font-size="9" font-weight="500" fill="currentColor">ICOPOR · M</text>
                    </svg>
                </div>
                <h3 class="heading-sm mb-2">Neveras</h3>
                <p class="text-[13.5px] leading-[1.6] text-[color:var(--color-ink-soft)] mb-3">
                    Icopor pequeña, mediana y grande para eventos y cadena de frío portátil.
                </p>
                <div class="mono text-[11px] text-mute mt-auto pt-3 border-t border-[var(--color-rule)]">
                    NEV-ICO · S · M · L
                </div>
            </article>

        </div>

        {{-- Precio inteligente --}}
        <div class="mt-16 grid grid-cols-12 gap-8 items-start">
            <div class="col-span-12 lg:col-span-7">
                <div class="eyebrow mb-3">PRECIO INTELIGENTE</div>
                <h3 class="heading-md mb-4">
                    Un catálogo maestro, <span class="hl-primary">precios negociados por cliente.</span>
                </h3>
                <p class="lead max-w-[58ch]">
                    El sistema anterior duplicaba 31 productos por cada cliente: subir el precio
                    de la bolsa <span class="mono text-[14px] text-[color:var(--color-ink)]">HIELO 5K</span> significaba editar fila por fila.
                    Aquí se edita una sola vez. Si un cliente tiene tarifa especial, vive en
                    <span class="mono text-[14px] text-[color:var(--color-ink)]">client_product.custom_price</span> y solo aplica para él.
                </p>
            </div>
            <div class="col-span-12 lg:col-span-5">
                <div class="price-demo">
                    <div class="mono text-[10.5px] tracking-[0.14em] uppercase text-mute mb-2">HIELO 5K · resolución de precio</div>
                    <div class="price-row">
                        <span class="price-row-label">Catálogo maestro (default)</span>
                        <span class="price-row-value">$ 8.500</span>
                    </div>
                    <div class="price-row">
                        <span class="price-row-label">Restaurante A · override</span>
                        <span class="price-row-value is-override">$ 7.800</span>
                    </div>
                    <div class="price-row">
                        <span class="price-row-label">Cliente nuevo · default</span>
                        <span class="price-row-value">$ 8.500</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

{{-- ============================================================
     SEC · OPERACIÓN
============================================================ --}}
<section id="operacion" class="section-shell-alt">
    <div class="mx-auto max-w-[1320px] px-6 lg:px-10 pt-20 lg:pt-28 pb-20 lg:pb-28">

        <div class="grid grid-cols-12 gap-8 mb-14">
            <div class="col-span-12 lg:col-span-7">
                <div class="eyebrow mb-3">OPERACIÓN</div>
                <h2 class="heading-lg mb-4">
                    Las capacidades que usa<br>
                    <span class="hl-primary">el negocio todos los días.</span>
                </h2>
            </div>
            <div class="col-span-12 lg:col-span-5">
                <p class="lead max-w-[42ch] lg:ml-auto">
                    Seis módulos institucionales que cubren el ciclo completo de venta y despacho:
                    desde la ficha del cliente hasta el comprobante en el correo.
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">

            <article class="cap-card">
                <div class="cap-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                        <path d="M3 7v14h18V7"/>
                        <path d="M3 7l9-4 9 4"/>
                        <path d="M9 21V12h6v9"/>
                    </svg>
                </div>
                <div class="cap-num mb-1">01 · CLIENTES</div>
                <h3 class="heading-sm mb-2">Ficha 360°</h3>
                <p class="text-[13.5px] leading-[1.6] text-[color:var(--color-ink-soft)]">
                    Razón social, NIT, gerente, dirección, WhatsApp, punto de entrega y tipo de pago.
                    Al crear el cliente se asignan los 31 productos del catálogo automáticamente.
                </p>
                <div class="cap-meta">Auditoría completa vía ActivityLog</div>
            </article>

            <article class="cap-card">
                <div class="cap-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                        <rect x="4" y="3" width="16" height="18" rx="2"/>
                        <path d="M8 8h8M8 12h8M8 16h5"/>
                        <path d="M14 17l1.5 1.5L19 15" stroke="var(--color-success)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="cap-num mb-1">02 · REMISIONES</div>
                <h3 class="heading-sm mb-2">Remisión en 30 segundos</h3>
                <p class="text-[13.5px] leading-[1.6] text-[color:var(--color-ink-soft)]">
                    Repeater dinámico con precio resuelto reactivo. El vendedor pone cantidad,
                    el subtotal se calcula al instante. Total congelado al confirmar.
                </p>
                <div class="cap-meta">Precio histórico inmutable en unit_price_snapshot</div>
            </article>

            <article class="cap-card">
                <div class="cap-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                        <path d="M3 18c2-1 3-1 5-3s3-3 5-2 4 4 6 2 2-4 2-4"/>
                        <circle cx="13" cy="7" r="3"/>
                        <path d="M3 21h18"/>
                    </svg>
                </div>
                <div class="cap-num mb-1">03 · RUTAS · GPS</div>
                <h3 class="heading-sm mb-2">Trazabilidad por ruta</h3>
                <p class="text-[13.5px] leading-[1.6] text-[color:var(--color-ink-soft)]">
                    16 rutas de reparto en enum tipado. Cada remisión guarda lat/lng con precisión
                    decimal — auditoría posterior y verificación del despacho.
                </p>
                <div class="cap-meta">decimal(10,8) / decimal(11,8)</div>
            </article>

            <article class="cap-card">
                <div class="cap-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                        <path d="M14 2v6h6"/>
                        <path d="M9 18c1.5-1 2.5-1 4-2.5"/>
                    </svg>
                </div>
                <div class="cap-num mb-1">04 · FIRMA DIGITAL</div>
                <h3 class="heading-sm mb-2">Firma del cliente</h3>
                <p class="text-[13.5px] leading-[1.6] text-[color:var(--color-ink-soft)]">
                    El cliente firma en pantalla. La imagen vive en disco privado, no en URL pública.
                    Queda incrustada en el PDF que se envía por correo.
                </p>
                <div class="cap-meta">Disco local · signed URLs</div>
            </article>

            <article class="cap-card">
                <div class="cap-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                        <rect x="3" y="5" width="18" height="14" rx="2"/>
                        <path d="M3 7l9 6 9-6"/>
                    </svg>
                </div>
                <div class="cap-num mb-1">05 · CORREO</div>
                <h3 class="heading-sm mb-2">Comprobante automatizado</h3>
                <p class="text-[13.5px] leading-[1.6] text-[color:var(--color-ink-soft)]">
                    Al confirmar la remisión se encola un correo con PDF adjunto.
                    El destinatario se decide por tipo de pago — configurable, no hardcodeado.
                </p>
                <div class="cap-meta">Queue · database driver</div>
            </article>

            <article class="cap-card">
                <div class="cap-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                        <path d="M3 3v18h18"/>
                        <path d="M7 14l3-3 3 3 5-6"/>
                        <circle cx="10" cy="11" r="0.8" fill="currentColor"/>
                        <circle cx="13" cy="14" r="0.8" fill="currentColor"/>
                        <circle cx="18" cy="8" r="0.8" fill="currentColor"/>
                    </svg>
                </div>
                <div class="cap-num mb-1">06 · REPORTES</div>
                <h3 class="heading-sm mb-2">Excel a la medida</h3>
                <p class="text-[13.5px] leading-[1.6] text-[color:var(--color-ink-soft)]">
                    Filtros combinables: cliente, ruta, rango de fechas, tipo de pago.
                    Stream con OpenSpout — funciona aunque haya 50 mil filas.
                </p>
                <div class="cap-meta">chunkById 200 · server-side</div>
            </article>

        </div>
    </div>
</section>

{{-- ============================================================
     SEC · FLUJO
============================================================ --}}
<section id="flujo" class="section-shell">
    <div class="mx-auto max-w-[1320px] px-6 lg:px-10 pt-20 lg:pt-28 pb-20 lg:pb-28">

        <div class="grid grid-cols-12 gap-8 mb-12 items-end">
            <div class="col-span-12 lg:col-span-8">
                <div class="eyebrow mb-3">FLUJO DE OPERACIÓN</div>
                <h2 class="heading-lg mb-4">
                    De la solicitud<br>
                    al comprobante <span class="hl-primary">firmado</span>.
                </h2>
                <p class="lead max-w-[60ch]">
                    Cinco pasos verificables que reemplazan al cuaderno y al WhatsApp suelto.
                </p>
            </div>
        </div>

        <ol class="flow-grid">
            @php
                $steps = [
                    ['1', 'Solicitud',     'WhatsApp, llamada o app móvil. El vendedor abre la ficha del cliente.', 'PASO'],
                    ['2', 'Remisión',      'Productos con precio resuelto reactivo, cantidades y subtotal en vivo.', 'PASO'],
                    ['3', 'Firma + GPS',   'El cliente firma en el teléfono. Se guardan lat/lng del despacho.', 'PASO'],
                    ['4', 'Confirmación',  'Total congelado en unit_price_snapshot. ActivityLog registra el evento.', 'PASO'],
                    ['5', 'Comprobante',   'PDF generado al vuelo y enviado al correo encolado.', 'CIERRE'],
                ];
            @endphp

            @foreach ($steps as $i => $step)
                <li class="flow-step">
                    <div class="flow-step-head">
                        <span class="flow-step-num">{{ $step[0] }}</span>
                        <span class="flow-step-tag">{{ $step[3] }}</span>
                    </div>
                    <h3 class="flow-step-title">{{ $step[1] }}</h3>
                    <p class="flow-step-body">{{ $step[2] }}</p>
                    @if ($i < count($steps) - 1)
                        <span class="flow-step-arrow" aria-hidden="true">
                            <svg width="22" height="10" viewBox="0 0 22 10" fill="none">
                                <path d="M0 5H20M16 1L20 5L16 9" stroke="currentColor" stroke-width="1.2"/>
                            </svg>
                        </span>
                    @endif
                </li>
            @endforeach
        </ol>
    </div>
</section>

{{-- ============================================================
     SEC · MÉTRICAS
============================================================ --}}
<section id="metricas" class="section-shell-alt">
    <div class="mx-auto max-w-[1320px] px-6 lg:px-10 pt-20 lg:pt-28 pb-20 lg:pb-28">

        <div class="grid grid-cols-12 gap-8 mb-12 items-end">
            <div class="col-span-12 lg:col-span-7">
                <div class="eyebrow mb-3">MÉTRICAS DE LA PLATAFORMA</div>
                <h2 class="heading-lg mb-4">
                    Cifras del catálogo y la operación,<br>
                    <span class="hl-primary">expuestas sin maquillaje.</span>
                </h2>
            </div>
        </div>

        @php
            $stats = [
                ['31', 'Productos en catálogo', '4 familias'],
                ['16', 'Rutas de reparto', 'Enum tipado'],
                ['14', 'Marcas de agua', 'KRISS · EDEN · MANA…'],
                ['76', 'Permisos RBAC', 'Filament Shield'],
                ['12', 'Endpoints API', 'App móvil del vendedor'],
                ['38', 'Tests verdes', 'PHPUnit · 104 asserts'],
            ];
        @endphp

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            @foreach ($stats as $stat)
                <div class="stat-card">
                    <div class="stat-number">{{ $stat[0] }}</div>
                    <div class="stat-label">{{ $stat[1] }}</div>
                    <div class="stat-sub">{{ $stat[2] }}</div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ============================================================
     SEC · ANTES / DESPUÉS
============================================================ --}}
<section class="section-shell">
    <div class="mx-auto max-w-[1320px] px-6 lg:px-10 pt-20 lg:pt-28 pb-20 lg:pb-28">

        <div class="grid grid-cols-12 gap-8 mb-12 items-end">
            <div class="col-span-12 lg:col-span-8">
                <div class="eyebrow mb-3">EVOLUCIÓN DE LA OPERACIÓN</div>
                <h2 class="heading-lg mb-4">
                    Cinco cambios concretos<br>
                    que <span class="hl-primary">el operador siente desde el primer día</span>.
                </h2>
            </div>
        </div>

        <div class="diff-table">
            <div class="diff-row is-head">
                <div>Nº</div>
                <div>Operación</div>
                <div>Antes</div>
                <div>Hoy</div>
            </div>
            @php
                $rows = [
                    ['01', 'Subir precio del HIELO 5K',  'Editar fila por fila para cada cliente',  'Una modificación en el catálogo maestro'],
                    ['02', 'Comprobante de entrega',    'Imprimir en papel y archivar',             'PDF firmado adjunto al correo automáticamente'],
                    ['03', 'Firma del cliente',         'Hoja física en archivador',                'Firma en pantalla embebida en el PDF'],
                    ['04', 'Reporte de ventas por ruta','Excel manual al final del mes',            'Filtros combinables + export al instante'],
                    ['05', 'Acceso del vendedor',       'No existía panel propio',                  'Panel /vendedor responsive + API móvil'],
                ];
            @endphp
            @foreach ($rows as $row)
                <div class="diff-row">
                    <div class="diff-num">{{ $row[0] }}</div>
                    <div class="diff-dim">{{ $row[1] }}</div>
                    <div class="diff-old">{{ $row[2] }}</div>
                    <div class="diff-new"><span class="diff-arrow">→</span>{{ $row[3] }}</div>
                </div>
            @endforeach
        </div>

    </div>
</section>

{{-- ============================================================
     SEC · ACCESO FINAL
============================================================ --}}
<section id="acceso" class="cta-shell">
    <div aria-hidden="true" class="cta-grid-bg"></div>

    <div class="relative mx-auto max-w-[1320px] px-6 lg:px-10 pt-20 lg:pt-28 pb-20 lg:pb-28">
        <div class="grid grid-cols-12 gap-8">

            <div class="col-span-12 lg:col-span-7">
                <div class="mono text-[11px] tracking-[0.18em] uppercase mb-4" style="color: var(--color-frost); font-weight: 500;">PORTAL DE ACCESO</div>
                <h2 class="heading-lg mb-5" style="color: var(--color-surface);">
                    Acceso institucional.<br>
                    <span style="color: var(--color-frost);">Administración &amp; vendedor.</span>
                </h2>
                <p class="lead max-w-[55ch]" style="color: rgba(255, 255, 255, 0.75);">
                    Dos paneles separados, una sola base de datos. Cada usuario ve exactamente
                    lo que su rol le permite — Filament Shield genera 76 permisos automáticamente.
                </p>
            </div>

            <div class="col-span-12 lg:col-span-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <a href="/admin/login" class="access-card">
                        <div class="flex items-baseline justify-between mb-8">
                            <span class="access-card-tag">MÓDULO · ADMIN</span>
                            <span class="access-card-path">/admin</span>
                        </div>
                        <div class="access-card-title">Administración</div>
                        <p class="access-card-body">
                            Catálogo, clientes, remisiones, empleados, usuarios y roles, reportes, branding.
                        </p>
                        <div class="access-card-cta">
                            Iniciar sesión
                            <svg width="12" height="12" viewBox="0 0 14 14" fill="none"><path d="M1 7H13M13 7L7 1M13 7L7 13" stroke="currentColor" stroke-width="1.4"/></svg>
                        </div>
                    </a>

                    <a href="/vendedor/login" class="access-card">
                        <div class="flex items-baseline justify-between mb-8">
                            <span class="access-card-tag">MÓDULO · VENDEDOR</span>
                            <span class="access-card-path">/vendedor</span>
                        </div>
                        <div class="access-card-title">Vendedor</div>
                        <p class="access-card-body">
                            Crear remisiones desde el navegador con vista responsive. Comprobante PDF.
                        </p>
                        <div class="access-card-cta">
                            Iniciar sesión
                            <svg width="12" height="12" viewBox="0 0 14 14" fill="none"><path d="M1 7H13M13 7L7 1M13 7L7 13" stroke="currentColor" stroke-width="1.4"/></svg>
                        </div>
                    </a>

                </div>

                <div class="mt-8 pt-6 grid grid-cols-2 gap-6" style="border-top: 1px solid rgba(255, 255, 255, 0.15);">
                    <div>
                        <div class="mono text-[10px] tracking-[0.18em] uppercase mb-1.5" style="color: rgba(255, 255, 255, 0.55); font-weight: 500;">CREDENCIALES DEMO</div>
                        <div class="mono text-[12px] leading-[1.7]" style="color: rgba(255, 255, 255, 0.85);">
                            superadmin@svd.test<br>
                            Super/Admin?
                        </div>
                    </div>
                    <div>
                        <div class="mono text-[10px] tracking-[0.18em] uppercase mb-1.5" style="color: rgba(255, 255, 255, 0.55); font-weight: 500;">SOPORTE</div>
                        <div class="mono text-[12px] leading-[1.7]" style="color: rgba(255, 255, 255, 0.85);">
                            Soporte interno SVD<br>
                            Barrancabermeja, CO
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ============================================================
     FOOTER
============================================================ --}}
<footer class="foot-shell">
    <div class="mx-auto max-w-[1320px] px-6 lg:px-10 pt-12 lg:pt-16 pb-10">

        <div class="grid grid-cols-12 gap-8 pb-10 mb-10" style="border-bottom: 1px solid var(--color-rule);">
            <div class="col-span-12 lg:col-span-5">
                <div class="flex items-center gap-3 mb-5">
                    <span class="brand-mark" aria-hidden="true">
                        <svg viewBox="0 0 32 32" width="20" height="20" fill="none">
                            <path d="M16 3L27 9.5V22.5L16 29L5 22.5V9.5L16 3Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
                            <path d="M16 10L22 13.5V20L16 23L10 20V13.5L16 10Z" fill="currentColor" opacity="0.18"/>
                            <path d="M16 14V20M13 17L16 18.5L19 17" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
                        </svg>
                    </span>
                    <div>
                        <div class="brand-name">SVD</div>
                        <div class="brand-tagline">Sistema de Ventas y Despachos</div>
                    </div>
                </div>
                <p class="text-[13.5px] leading-[1.6] max-w-[40ch]" style="color: var(--color-ink-soft);">
                    Plataforma institucional de distribución de hielo, agua y neveras.
                    Edición {{ now()->locale('es')->isoFormat('MMMM YYYY') }}.
                </p>
            </div>

            <div class="col-span-6 md:col-span-3 lg:col-span-2">
                <div class="mono text-[10.5px] tracking-[0.16em] uppercase mb-3" style="color: var(--color-ink-mute); font-weight: 500;">PANEL</div>
                <ul class="space-y-2 text-[13.5px]">
                    <li><a href="/admin/login" class="link-rev" style="color: var(--color-ink-soft);">Administración</a></li>
                    <li><a href="/vendedor/login" class="link-rev" style="color: var(--color-ink-soft);">Vendedor</a></li>
                </ul>
            </div>

            <div class="col-span-6 md:col-span-3 lg:col-span-2">
                <div class="mono text-[10.5px] tracking-[0.16em] uppercase mb-3" style="color: var(--color-ink-mute); font-weight: 500;">CATÁLOGO</div>
                <ul class="space-y-2 text-[13.5px]" style="color: var(--color-ink-soft);">
                    <li>Hielo · 1.5K · 5K · 15K</li>
                    <li>Agua · 14 marcas</li>
                    <li>Envases vacíos</li>
                    <li>Neveras de icopor</li>
                </ul>
            </div>

            <div class="col-span-12 md:col-span-6 lg:col-span-3">
                <div class="mono text-[10.5px] tracking-[0.16em] uppercase mb-3" style="color: var(--color-ink-mute); font-weight: 500;">CONTACTO</div>
                <p class="text-[13.5px] leading-[1.6]" style="color: var(--color-ink-soft);">
                    Barrancabermeja, Santander · Colombia<br>
                    Plataforma institucional SVD.
                </p>
            </div>
        </div>

        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-3">
            <div class="foot-brand-wordmark" style="font-size: clamp(2rem, 4vw, 3rem);">
                SVD<span style="color: var(--color-primary);">.</span>
            </div>
            <p class="mono text-[11px]" style="color: var(--color-ink-mute);">© {{ now()->year }} · SVD · Privado</p>
        </div>

    </div>
</footer>

</body>
</html>
