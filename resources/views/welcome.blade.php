<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="SVD — Sistema de Ventas y Despachos para distribución de hielo, agua y neveras. Catálogo maestro, rutas de reparto, comprobantes PDF y app de vendedor.">
    <meta name="theme-color" content="#0a0a0a">
    <title>SVD · Distribución de hielo y agua — Sistema de Ventas y Despachos</title>

    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-paper text-ink antialiased" style="background-color: var(--color-paper); color: var(--color-ink);">

{{-- ============================================================
     HEADER · Identidad + ubicación + accesos
============================================================ --}}
<header class="rule-b">
    <div class="mx-auto max-w-[1480px] px-6 lg:px-10">
        <div class="flex items-center justify-between gap-4 py-4">
            <div class="flex items-center gap-3">
                <span class="brand-mark" aria-hidden="true">
                    <svg viewBox="0 0 32 32" width="28" height="28" fill="none">
                        <path d="M16 2L28 9V23L16 30L4 23V9L16 2Z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/>
                        <path d="M16 9.5L22 13V20L16 23.5L10 20V13L16 9.5Z" fill="var(--color-frost)" stroke="currentColor" stroke-width="1"/>
                        <path d="M16 13V20M13 16.75L16 18.5L19 16.75" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/>
                    </svg>
                </span>
                <div class="flex flex-col leading-tight">
                    <span class="mono text-[11px] tracking-[0.18em] uppercase" style="color: var(--color-ink);">SVD</span>
                    <span class="mono text-[9px] tracking-[0.14em] uppercase" style="color: var(--color-ink-mute);">Ventas &amp; Despachos</span>
                </div>
            </div>
            <nav class="hidden md:flex items-center gap-7 mono text-[11px] tracking-[0.14em] uppercase" style="color: var(--color-ink-soft);">
                <a href="#catalogo" class="link-rev">Catálogo</a>
                <a href="#operacion" class="link-rev">Operación</a>
                <a href="#flujo" class="link-rev">Flujo</a>
                <a href="#metricas" class="link-rev">Métricas</a>
                <a href="#acceso" class="link-rev">Acceso</a>
            </nav>
            <div class="flex items-center gap-3">
                <a href="/vendedor/login" class="hidden sm:inline-flex items-center px-3.5 py-2 mono text-[11px] tracking-[0.14em] uppercase btn-ghost">
                    Vendedor
                </a>
                <a href="/admin/login" class="inline-flex items-center px-4 py-2 mono text-[11px] tracking-[0.14em] uppercase btn-ink">
                    Acceder
                </a>
            </div>
        </div>
    </div>
</header>

{{-- ============================================================
     HERO · Producto y promesa
============================================================ --}}
<section class="relative overflow-hidden bg-blueprint">
    <div class="mx-auto max-w-[1480px] px-6 lg:px-10 pt-12 lg:pt-20 pb-16 lg:pb-24">

        <div class="grid grid-cols-12 gap-y-10 lg:gap-8">

            {{-- Eyebrow columna 1 --}}
            <div class="col-span-12 lg:col-span-3 rise rise-1">
                <div class="eyebrow mb-3">SEC 01 · PLATAFORMA</div>
                <div class="mono text-[12px] leading-relaxed" style="color: var(--color-ink-soft);">
                    Distribución de hielo, agua<br>
                    y neveras para empresas,<br>
                    <span style="color: var(--color-ink);">restaurantes y eventos.</span>
                </div>
                <div class="mt-6 inline-flex items-center gap-2 mono text-[10px] tracking-[0.18em] uppercase" style="color: var(--color-frost-deep);">
                    <span class="dot-pulse"></span>
                    En operación · Barrancabermeja, CO
                </div>
            </div>

            {{-- Display headline columna 2 --}}
            <div class="col-span-12 lg:col-span-9 rise rise-2">
                <h1 class="display-xl" style="color: var(--color-ink);">
                    Hielo, agua<br>
                    &amp; despacho<br>
                    <span class="serif italic" style="color: var(--color-frost-deep);">en una sola</span> <span style="color: var(--color-terra);">plataforma.</span>
                </h1>
            </div>

            {{-- Linea divisora animada --}}
            <div class="col-span-12 -mt-2">
                <div class="draw-rule rise rise-3" style="height: 1px; background-color: var(--color-ink);"></div>
            </div>

            {{-- Bajada --}}
            <div class="col-span-12 lg:col-span-5 lg:col-start-1 rise rise-3">
                <p class="display-md" style="color: var(--color-ink-soft);">
                    Del <span style="color: var(--color-ink);">pedido al comprobante firmado</span> sin papeles —
                    catálogo maestro, precios por cliente, ruta de reparto y PDF en el bolsillo del vendedor.
                </p>
            </div>

            <div class="col-span-12 lg:col-span-4 lg:col-start-7 rise rise-4">
                <div class="rule-t pt-5">
                    <p class="text-[15px] leading-[1.65]" style="color: var(--color-ink-soft); font-family: var(--font-sans);">
                        Una plataforma <span style="color: var(--color-ink); font-weight: 500;">B2B para distribuidoras de hielo y agua</span>:
                        catálogo con override por cliente, remisiones digitales con firma, comprobantes PDF
                        adjuntos al correo y un panel de vendedor para la calle.
                    </p>
                </div>
            </div>

            {{-- CTAs --}}
            <div class="col-span-12 lg:col-span-3 lg:col-start-7 rise rise-4 mt-2">
                <div class="flex flex-col sm:flex-row lg:flex-col gap-3">
                    <a href="/admin/login" class="group inline-flex items-center justify-between px-5 py-3.5 btn-ink">
                        <span class="mono text-[12px] tracking-[0.14em] uppercase">Panel administración</span>
                        <svg width="14" height="14" viewBox="0 0 14 14" fill="none" class="ml-3 transition-transform group-hover:translate-x-1"><path d="M1 7H13M13 7L7 1M13 7L7 13" stroke="currentColor" stroke-width="1.2"/></svg>
                    </a>
                    <a href="/vendedor/login" class="group inline-flex items-center justify-between px-5 py-3.5 btn-ghost">
                        <span class="mono text-[12px] tracking-[0.14em] uppercase">Panel vendedor</span>
                        <svg width="14" height="14" viewBox="0 0 14 14" fill="none" class="ml-3 transition-transform group-hover:translate-x-1"><path d="M1 7H13M13 7L7 1M13 7L7 13" stroke="currentColor" stroke-width="1.2"/></svg>
                    </a>
                </div>
            </div>

            <div class="col-span-12 lg:col-span-2 lg:col-start-11 rise rise-5">
                <div class="rule-l pl-4 mono text-[10px] tracking-[0.14em] uppercase leading-relaxed" style="color: var(--color-ink-mute);">
                    EDIC · {{ now()->locale('es')->isoFormat('MMM YYYY') }}<br>
                    RUTAS · 16 activas<br>
                    SKU · 31 en catálogo
                </div>
            </div>

        </div>
    </div>

    {{-- Bloque "ice cube" decorativo en lugar de marca de agua --}}
    <div aria-hidden="true" class="absolute -bottom-20 right-[-3%] pointer-events-none select-none opacity-[0.07] hidden md:block">
        <svg width="560" height="560" viewBox="0 0 100 100" fill="none">
            <path d="M50 5L92 27V73L50 95L8 73V27L50 5Z" stroke="currentColor" stroke-width="0.8"/>
            <path d="M50 22L78 36V64L50 78L22 64V36L50 22Z" stroke="currentColor" stroke-width="0.6"/>
            <path d="M50 36L66 44V58L50 66L34 58V44L50 36Z" fill="currentColor" opacity="0.25"/>
            <path d="M8 27L50 49L92 27M50 49V95M50 49L78 36M50 49L22 36" stroke="currentColor" stroke-width="0.4"/>
        </svg>
    </div>
</section>

{{-- ============================================================
     STATS · Marquee con métricas operativas reales
============================================================ --}}
<section class="rule-strong-t rule-b overflow-hidden" style="background-color: var(--color-ink); color: var(--color-paper);">
    <div class="marquee-track flex whitespace-nowrap py-5">
        @for ($i = 0; $i < 2; $i++)
            <div class="flex items-center gap-12 px-6 mono text-[13px] tracking-[0.18em] uppercase">
                <span class="flex items-center gap-3"><span class="serif italic text-2xl" style="color: var(--color-frost);">31</span> productos en catálogo</span>
                <span class="opacity-40">§</span>
                <span class="flex items-center gap-3"><span class="serif italic text-2xl" style="color: var(--color-frost);">16</span> rutas de reparto</span>
                <span class="opacity-40">§</span>
                <span class="flex items-center gap-3"><span class="serif italic text-2xl" style="color: var(--color-frost);">3</span> presentaciones de hielo</span>
                <span class="opacity-40">§</span>
                <span class="flex items-center gap-3"><span class="serif italic text-2xl" style="color: var(--color-frost);">14</span> marcas de agua</span>
                <span class="opacity-40">§</span>
                <span class="flex items-center gap-3"><span class="serif italic text-2xl" style="color: var(--color-frost);">PDF</span> firmado por entrega</span>
                <span class="opacity-40">§</span>
                <span class="flex items-center gap-3"><span class="serif italic text-2xl" style="color: var(--color-frost);">GPS</span> en cada remisión</span>
                <span class="opacity-40">§</span>
            </div>
        @endfor
    </div>
</section>

{{-- ============================================================
     SEC 02 · CATÁLOGO — productos reales del negocio
============================================================ --}}
<section id="catalogo" class="rule-b">
    <div class="mx-auto max-w-[1480px] px-6 lg:px-10 pt-16 lg:pt-24 pb-20 lg:pb-28">

        <div class="grid grid-cols-12 gap-8 mb-14">
            <div class="col-span-12 lg:col-span-3">
                <div class="eyebrow mb-4">SEC 02 · CATÁLOGO</div>
                <p class="mono text-[12px] leading-relaxed" style="color: var(--color-ink-mute);">
                    Cuatro familias de producto,<br>
                    un único catálogo maestro,<br>
                    precios por cliente.
                </p>
            </div>
            <div class="col-span-12 lg:col-span-9">
                <h2 class="display-lg" style="color: var(--color-ink);">
                    Lo que <span class="serif italic">despachamos</span><br>
                    todos los días.
                </h2>
            </div>
        </div>

        <div class="rule-strong-t grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4">

            {{-- Familia: HIELO --}}
            <article class="p-7 lg:p-9 product-card" style="border-bottom: 1px solid var(--color-rule); border-right: 1px solid var(--color-rule);">
                <div class="flex items-start justify-between mb-6">
                    <span class="mono text-[10px] tracking-[0.18em] uppercase" style="color: var(--color-ink-mute);">FAMILIA · 01</span>
                    <span class="serif italic text-3xl leading-none" style="color: var(--color-frost-deep);">03</span>
                </div>
                <div class="product-illus mb-6" aria-hidden="true">
                    <svg viewBox="0 0 120 120" width="100%" height="120" fill="none">
                        <rect x="30" y="40" width="60" height="65" rx="6" stroke="currentColor" stroke-width="1.4" fill="var(--color-frost)"/>
                        <path d="M40 40 Q42 28 50 24 Q58 20 60 22 Q66 18 74 24 Q82 30 80 40" stroke="currentColor" stroke-width="1.2" fill="none"/>
                        <text x="60" y="78" text-anchor="middle" font-family="JetBrains Mono" font-size="11" font-weight="500" fill="currentColor">HIELO</text>
                        <text x="60" y="92" text-anchor="middle" font-family="Instrument Serif" font-size="16" font-style="italic" fill="var(--color-terra)">5K</text>
                    </svg>
                </div>
                <h3 class="display-md mb-3" style="color: var(--color-ink);">Hielo.</h3>
                <p class="text-[13px] leading-[1.6] mb-4" style="color: var(--color-ink-soft);">
                    Bolsa <span class="mono text-[12px]">1.5K</span>, <span class="mono text-[12px]">5K</span> y <span class="mono text-[12px]">15K</span>.
                    Producto estrella de la operación.
                </p>
                <div class="pt-3 rule-t mono text-[11px]" style="color: var(--color-ink-mute);">
                    SKU H-1500 · H-5000 · H-15000
                </div>
            </article>

            {{-- Familia: AGUA --}}
            <article class="p-7 lg:p-9 product-card" style="border-bottom: 1px solid var(--color-rule); border-right: 1px solid var(--color-rule);">
                <div class="flex items-start justify-between mb-6">
                    <span class="mono text-[10px] tracking-[0.18em] uppercase" style="color: var(--color-ink-mute);">FAMILIA · 02</span>
                    <span class="serif italic text-3xl leading-none" style="color: var(--color-frost-deep);">22</span>
                </div>
                <div class="product-illus mb-6" aria-hidden="true">
                    <svg viewBox="0 0 120 120" width="100%" height="120" fill="none">
                        <path d="M52 22H68V30L72 36V100C72 104 68 108 64 108H56C52 108 48 104 48 100V36L52 30V22Z" stroke="currentColor" stroke-width="1.4" fill="var(--color-frost)"/>
                        <rect x="46" y="60" width="28" height="22" fill="var(--color-paper)" stroke="currentColor" stroke-width="1"/>
                        <text x="60" y="73" text-anchor="middle" font-family="JetBrains Mono" font-size="7" font-weight="500" fill="currentColor">KRISS</text>
                        <text x="60" y="80" text-anchor="middle" font-family="Instrument Serif" font-size="6" font-style="italic" fill="var(--color-terra)">600 ML</text>
                    </svg>
                </div>
                <h3 class="display-md mb-3" style="color: var(--color-ink);">Agua.</h3>
                <p class="text-[13px] leading-[1.6] mb-4" style="color: var(--color-ink-soft);">
                    14 marcas (KRISS, EDEN, MANA, CRISTAL...) en
                    <span class="mono text-[12px]">600 ml</span>, <span class="mono text-[12px]">1.5 L</span>, <span class="mono text-[12px]">5 L</span>, paquete o caja.
                </p>
                <div class="pt-3 rule-t mono text-[11px]" style="color: var(--color-ink-mute);">
                    Botella · paquete x24 · caja
                </div>
            </article>

            {{-- Familia: ENVASES --}}
            <article class="p-7 lg:p-9 product-card" style="border-bottom: 1px solid var(--color-rule); border-right: 1px solid var(--color-rule);">
                <div class="flex items-start justify-between mb-6">
                    <span class="mono text-[10px] tracking-[0.18em] uppercase" style="color: var(--color-ink-mute);">FAMILIA · 03</span>
                    <span class="serif italic text-3xl leading-none" style="color: var(--color-frost-deep);">03</span>
                </div>
                <div class="product-illus mb-6" aria-hidden="true">
                    <svg viewBox="0 0 120 120" width="100%" height="120" fill="none">
                        <path d="M40 30H80V42L84 50V96C84 102 80 106 74 106H46C40 106 36 102 36 96V50L40 42V30Z" stroke="currentColor" stroke-width="1.4" stroke-dasharray="3 2" fill="none"/>
                        <path d="M50 60 L70 80 M70 60 L50 80" stroke="currentColor" stroke-width="1.4"/>
                        <text x="60" y="98" text-anchor="middle" font-family="JetBrains Mono" font-size="8" fill="var(--color-ink-mute)">VACÍO</text>
                    </svg>
                </div>
                <h3 class="display-md mb-3" style="color: var(--color-ink);">Envases.</h3>
                <p class="text-[13px] leading-[1.6] mb-4" style="color: var(--color-ink-soft);">
                    Devolución de envase vacío KRISS o EDEN. Categoría
                    <span class="mono text-[12px]">EmptyContainer</span> con precio de retorno.
                </p>
                <div class="pt-3 rule-t mono text-[11px]" style="color: var(--color-ink-mute);">
                    Retorno · descuento al cliente
                </div>
            </article>

            {{-- Familia: NEVERAS --}}
            <article class="p-7 lg:p-9 product-card" style="border-bottom: 1px solid var(--color-rule);">
                <div class="flex items-start justify-between mb-6">
                    <span class="mono text-[10px] tracking-[0.18em] uppercase" style="color: var(--color-ink-mute);">FAMILIA · 04</span>
                    <span class="serif italic text-3xl leading-none" style="color: var(--color-frost-deep);">03</span>
                </div>
                <div class="product-illus mb-6" aria-hidden="true">
                    <svg viewBox="0 0 120 120" width="100%" height="120" fill="none">
                        <rect x="22" y="42" width="76" height="56" rx="3" stroke="currentColor" stroke-width="1.4" fill="var(--color-paper-mid)"/>
                        <rect x="22" y="42" width="76" height="14" rx="3" stroke="currentColor" stroke-width="1.4" fill="var(--color-frost)"/>
                        <path d="M40 36 L40 30 Q40 26 44 26 L76 26 Q80 26 80 30 L80 36" stroke="currentColor" stroke-width="1.2" fill="none"/>
                        <line x1="22" y1="70" x2="98" y2="70" stroke="currentColor" stroke-width="0.6" stroke-dasharray="2 2"/>
                        <text x="60" y="92" text-anchor="middle" font-family="JetBrains Mono" font-size="8" fill="currentColor">ICOPOR</text>
                    </svg>
                </div>
                <h3 class="display-md mb-3" style="color: var(--color-ink);">Neveras.</h3>
                <p class="text-[13px] leading-[1.6] mb-4" style="color: var(--color-ink-soft);">
                    Icopor pequeña, mediana y grande. Para eventos y clientes
                    que requieren cadena de frío portátil.
                </p>
                <div class="pt-3 rule-t mono text-[11px]" style="color: var(--color-ink-mute);">
                    S · M · L · cadena de frío
                </div>
            </article>

        </div>

        {{-- Banda inferior: precio inteligente --}}
        <div class="mt-16 grid grid-cols-12 gap-8 items-end">
            <div class="col-span-12 lg:col-span-8">
                <div class="eyebrow mb-3">PRECIO INTELIGENTE</div>
                <h3 class="display-md mb-4" style="color: var(--color-ink);">
                    Un catálogo maestro, <span class="serif italic" style="color: var(--color-terra);">precios por cliente.</span>
                </h3>
                <p class="text-[14px] leading-[1.65] max-w-[58ch]" style="color: var(--color-ink-soft); font-family: var(--font-sans);">
                    El sistema anterior duplicaba 31 productos por cada cliente: subir el precio de la bolsa HIELO 5K
                    significaba editar fila por fila. Aquí editas <span class="mono text-[13px]">products.default_price</span> una vez.
                    Si un cliente tiene tarifa especial, vive en <span class="mono text-[13px]">client_product.custom_price</span> y solo ese cliente la usa.
                </p>
            </div>
            <div class="col-span-12 lg:col-span-4">
                <div class="price-demo rule-t pt-5">
                    <div class="flex items-baseline justify-between py-2 rule-b">
                        <span class="mono text-[12px]" style="color: var(--color-ink-mute);">HIELO 5K · catálogo</span>
                        <span class="serif italic text-2xl" style="color: var(--color-ink);">$8.500</span>
                    </div>
                    <div class="flex items-baseline justify-between py-2 rule-b">
                        <span class="mono text-[12px]" style="color: var(--color-ink-mute);">Restaurante A · override</span>
                        <span class="serif italic text-2xl" style="color: var(--color-terra);">$7.800</span>
                    </div>
                    <div class="flex items-baseline justify-between py-2">
                        <span class="mono text-[12px]" style="color: var(--color-ink-mute);">Cliente nuevo · usa default</span>
                        <span class="serif italic text-2xl" style="color: var(--color-ink);">$8.500</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

{{-- ============================================================
     SEC 03 · OPERACIÓN — capacidades reales que usa el negocio
============================================================ --}}
<section id="operacion" class="rule-b" style="background-color: var(--color-paper-warm);">
    <div class="mx-auto max-w-[1480px] px-6 lg:px-10 pt-16 lg:pt-24 pb-20 lg:pb-28">

        <div class="grid grid-cols-12 gap-8 mb-14">
            <div class="col-span-12 lg:col-span-3">
                <div class="eyebrow mb-4">SEC 03 · OPERACIÓN</div>
                <p class="mono text-[12px] leading-relaxed" style="color: var(--color-ink-mute);">
                    Lo que el vendedor<br>
                    y el administrador hacen<br>
                    todos los días.
                </p>
            </div>
            <div class="col-span-12 lg:col-span-9">
                <h2 class="display-lg" style="color: var(--color-ink);">
                    De la oficina a la <span class="serif italic">ruta</span><br>
                    sin papel intermedio.
                </h2>
            </div>
        </div>

        <div class="rule-strong-t grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3">

            <article class="p-8 lg:p-10" style="border-bottom: 1px solid var(--color-rule); border-right: 1px solid var(--color-rule);">
                <div class="op-icon mb-8" aria-hidden="true">
                    <svg width="42" height="42" viewBox="0 0 42 42" fill="none">
                        <rect x="6" y="10" width="30" height="24" rx="2" stroke="currentColor" stroke-width="1.4"/>
                        <path d="M6 16H36" stroke="currentColor" stroke-width="1.4"/>
                        <circle cx="11" cy="13" r="0.8" fill="currentColor"/>
                        <circle cx="14" cy="13" r="0.8" fill="currentColor"/>
                        <path d="M11 22H22M11 26H30M11 30H18" stroke="currentColor" stroke-width="1.2"/>
                    </svg>
                </div>
                <div class="flex items-baseline justify-between mb-3">
                    <span class="serif italic text-4xl leading-none" style="color: var(--color-terra);">01</span>
                    <span class="mono text-[10px] tracking-[0.14em] uppercase" style="color: var(--color-ink-mute);">MOD·CLIENTES</span>
                </div>
                <h3 class="display-md mb-4" style="color: var(--color-ink);">Ficha del cliente.</h3>
                <p class="text-[14px] leading-[1.6]" style="color: var(--color-ink-soft);">
                    Razón social, NIT, gerente, dirección, WhatsApp, punto de entrega y tipo de pago.
                    Al crear el cliente, se le asignan automáticamente los 31 productos del catálogo.
                </p>
            </article>

            <article class="p-8 lg:p-10" style="border-bottom: 1px solid var(--color-rule); border-right: 1px solid var(--color-rule);">
                <div class="op-icon mb-8" aria-hidden="true">
                    <svg width="42" height="42" viewBox="0 0 42 42" fill="none">
                        <path d="M8 8H30L34 12V34H8V8Z" stroke="currentColor" stroke-width="1.4"/>
                        <path d="M30 8V12H34" stroke="currentColor" stroke-width="1.4"/>
                        <path d="M12 18H30M12 22H30M12 26H22" stroke="currentColor" stroke-width="1.2"/>
                        <path d="M22 30L24.5 32.5L30 27" stroke="var(--color-terra)" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="flex items-baseline justify-between mb-3">
                    <span class="serif italic text-4xl leading-none" style="color: var(--color-frost-deep);">02</span>
                    <span class="mono text-[10px] tracking-[0.14em] uppercase" style="color: var(--color-ink-mute);">MOD·REMISIÓN</span>
                </div>
                <h3 class="display-md mb-4" style="color: var(--color-ink);">Remisión en 30 segundos.</h3>
                <p class="text-[14px] leading-[1.6]" style="color: var(--color-ink-soft);">
                    Repeater dinámico de productos con precio resuelto reactivo. El vendedor pone cantidad y
                    el subtotal se calcula al instante. Total fijado al confirmar la remisión.
                </p>
            </article>

            <article class="p-8 lg:p-10" style="border-bottom: 1px solid var(--color-rule);">
                <div class="op-icon mb-8" aria-hidden="true">
                    <svg width="42" height="42" viewBox="0 0 42 42" fill="none">
                        <path d="M6 30C6 28 8 26 10 26L14 22L18 26L24 18L30 26L34 24" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
                        <path d="M6 34H36" stroke="currentColor" stroke-width="1.4"/>
                        <circle cx="21" cy="14" r="3" stroke="var(--color-terra)" stroke-width="1.6"/>
                        <path d="M21 8V11M21 17V20M15 14H18M24 14H27" stroke="var(--color-terra)" stroke-width="1.2"/>
                    </svg>
                </div>
                <div class="flex items-baseline justify-between mb-3">
                    <span class="serif italic text-4xl leading-none" style="color: var(--color-mint);">03</span>
                    <span class="mono text-[10px] tracking-[0.14em] uppercase" style="color: var(--color-ink-mute);">MOD·RUTAS</span>
                </div>
                <h3 class="display-md mb-4" style="color: var(--color-ink);">Rutas &amp; GPS.</h3>
                <p class="text-[14px] leading-[1.6]" style="color: var(--color-ink-soft);">
                    16 rutas de reparto en enum tipado. Cada remisión guarda lat/lng con precisión decimal
                    para auditoría posterior y trazabilidad del despacho.
                </p>
            </article>

            <article class="p-8 lg:p-10" style="border-bottom: 1px solid var(--color-rule); border-right: 1px solid var(--color-rule);">
                <div class="op-icon mb-8" aria-hidden="true">
                    <svg width="42" height="42" viewBox="0 0 42 42" fill="none">
                        <path d="M8 6H26L34 14V36H8V6Z" stroke="currentColor" stroke-width="1.4"/>
                        <path d="M26 6V14H34" stroke="currentColor" stroke-width="1.4"/>
                        <text x="21" y="26" text-anchor="middle" font-family="JetBrains Mono" font-size="6" font-weight="500" fill="currentColor">PDF</text>
                        <path d="M12 31C14 29 16 30 18 28 C20 26 22 27 24 25C26 23 28 25 30 24" stroke="var(--color-terra)" stroke-width="1.2" fill="none" stroke-linecap="round"/>
                    </svg>
                </div>
                <div class="flex items-baseline justify-between mb-3">
                    <span class="serif italic text-4xl leading-none" style="color: var(--color-terra);">04</span>
                    <span class="mono text-[10px] tracking-[0.14em] uppercase" style="color: var(--color-ink-mute);">MOD·FIRMA</span>
                </div>
                <h3 class="display-md mb-4" style="color: var(--color-ink);">Firma digital.</h3>
                <p class="text-[14px] leading-[1.6]" style="color: var(--color-ink-soft);">
                    El cliente firma en el teléfono. La imagen vive en el disco privado, no en URL pública.
                    Queda incrustada en el PDF que se envía por correo.
                </p>
            </article>

            <article class="p-8 lg:p-10" style="border-bottom: 1px solid var(--color-rule); border-right: 1px solid var(--color-rule);">
                <div class="op-icon mb-8" aria-hidden="true">
                    <svg width="42" height="42" viewBox="0 0 42 42" fill="none">
                        <rect x="6" y="10" width="30" height="22" rx="2" stroke="currentColor" stroke-width="1.4"/>
                        <path d="M6 12L21 22L36 12" stroke="currentColor" stroke-width="1.4"/>
                        <circle cx="32" cy="10" r="4" fill="var(--color-terra)"/>
                        <text x="32" y="13" text-anchor="middle" font-family="JetBrains Mono" font-size="5" font-weight="600" fill="var(--color-paper)">PDF</text>
                    </svg>
                </div>
                <div class="flex items-baseline justify-between mb-3">
                    <span class="serif italic text-4xl leading-none" style="color: var(--color-frost-deep);">05</span>
                    <span class="mono text-[10px] tracking-[0.14em] uppercase" style="color: var(--color-ink-mute);">MOD·EMAIL</span>
                </div>
                <h3 class="display-md mb-4" style="color: var(--color-ink);">Comprobante por correo.</h3>
                <p class="text-[14px] leading-[1.6]" style="color: var(--color-ink-soft);">
                    Al confirmar la remisión se encola un email con PDF adjunto. El destinatario se decide por
                    tipo de pago (crédito, contado, obsequio) — todo configurable, nada hardcodeado.
                </p>
            </article>

            <article class="p-8 lg:p-10" style="border-bottom: 1px solid var(--color-rule);">
                <div class="op-icon mb-8" aria-hidden="true">
                    <svg width="42" height="42" viewBox="0 0 42 42" fill="none">
                        <rect x="8" y="6" width="26" height="30" rx="2" stroke="currentColor" stroke-width="1.4"/>
                        <path d="M12 14H30M12 18H24M12 22H30M12 26H18" stroke="currentColor" stroke-width="1"/>
                        <rect x="14" y="28" width="14" height="4" fill="var(--color-mint)"/>
                        <path d="M36 18L40 18M36 22L40 22" stroke="var(--color-terra)" stroke-width="1.4"/>
                    </svg>
                </div>
                <div class="flex items-baseline justify-between mb-3">
                    <span class="serif italic text-4xl leading-none" style="color: var(--color-mint);">06</span>
                    <span class="mono text-[10px] tracking-[0.14em] uppercase" style="color: var(--color-ink-mute);">MOD·REPORTES</span>
                </div>
                <h3 class="display-md mb-4" style="color: var(--color-ink);">Reportes Excel.</h3>
                <p class="text-[14px] leading-[1.6]" style="color: var(--color-ink-soft);">
                    Filtros combinables: por cliente, por ruta, por rango de fechas, por tipo de pago.
                    Stream con OpenSpout — funciona aunque haya 50 mil filas.
                </p>
            </article>

        </div>
    </div>
</section>

{{-- ============================================================
     SEC 04 · FLUJO — el día a día visualizado
============================================================ --}}
<section id="flujo" class="rule-b">
    <div class="mx-auto max-w-[1480px] px-6 lg:px-10 pt-16 lg:pt-24 pb-20 lg:pb-28">

        <div class="grid grid-cols-12 gap-8 mb-14">
            <div class="col-span-12 lg:col-span-3">
                <div class="eyebrow mb-4">SEC 04 · FLUJO</div>
                <p class="mono text-[12px] leading-relaxed" style="color: var(--color-ink-mute);">
                    Una remisión nace en el panel<br>
                    de admin y termina en el<br>
                    correo del cliente.
                </p>
            </div>
            <div class="col-span-12 lg:col-span-9">
                <h2 class="display-lg" style="color: var(--color-ink);">
                    El recorrido de <span class="serif italic">una bolsa de hielo.</span>
                </h2>
            </div>
        </div>

        {{-- Timeline horizontal --}}
        <div class="flow-timeline">
            @php
                $steps = [
                    ['01', 'Cliente solicita',  'WhatsApp, llamada o app móvil. El vendedor abre el cliente en el panel.', 'PASO'],
                    ['02', 'Remisión',          'Selección de productos con precio resuelto reactivo y cantidad. Subtotal en vivo.', 'PASO'],
                    ['03', 'Firma + GPS',       'El cliente firma en el teléfono. La remisión guarda lat/lng del despacho.', 'PASO'],
                    ['04', 'Confirma',          'Total congelado en unit_price_snapshot. ActivityLog registra el cambio.', 'PASO'],
                    ['05', 'Comprobante',       'PDF DomPDF generado al vuelo y adjuntado al correo encolado.', 'CIERRE'],
                ];
            @endphp

            <ol class="flow-grid">
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
                                    <path d="M0 5H20M16 1L20 5L16 9" stroke="currentColor" stroke-width="1.1"/>
                                </svg>
                            </span>
                        @endif
                    </li>
                @endforeach
            </ol>
        </div>

    </div>
</section>

{{-- ============================================================
     SEC 05 · MÉTRICAS — operación real
============================================================ --}}
<section id="metricas" class="rule-b bg-grain">
    <div class="mx-auto max-w-[1480px] px-6 lg:px-10 pt-16 lg:pt-24 pb-20 lg:pb-28">

        <div class="grid grid-cols-12 gap-8 mb-14">
            <div class="col-span-12 lg:col-span-3">
                <div class="eyebrow mb-4">SEC 05 · MÉTRICAS</div>
                <p class="mono text-[12px] leading-relaxed" style="color: var(--color-ink-mute);">
                    Cifras del catálogo<br>
                    y de la operación,<br>
                    expuestas sin maquillaje.
                </p>
            </div>
            <div class="col-span-12 lg:col-span-9">
                <h2 class="display-lg" style="color: var(--color-ink);">
                    En <span class="serif italic">números</span><br>
                    redondos.
                </h2>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 rule-strong-t rule-b">

            @php
                $stats = [
                    ['31', 'productos en catálogo', '4 familias'],
                    ['16', 'rutas de reparto', 'enum tipado'],
                    ['14', 'marcas de agua', 'KRISS · EDEN · MANA…'],
                    ['76', 'permisos RBAC', 'Filament Shield'],
                    ['12', 'endpoints API', 'app móvil del vendedor'],
                    ['29', 'tests verdes', 'cobertura crítica'],
                ];
            @endphp

            @foreach ($stats as $i => $stat)
                <div class="px-5 lg:px-6 py-8 lg:py-12" style="border-right: {{ $i < count($stats) - 1 ? '1px solid var(--color-rule)' : 'none' }};">
                    <div class="serif italic leading-none stat-number" style="color: var(--color-ink); font-size: clamp(3.5rem, 6vw, 5.5rem);">
                        {{ $stat[0] }}
                    </div>
                    <div class="mt-4 text-[13px] font-medium" style="color: var(--color-ink);">{{ $stat[1] }}</div>
                    <div class="mt-1 mono text-[10px] tracking-[0.10em]" style="color: var(--color-ink-mute);">{{ $stat[2] }}</div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ============================================================
     SEC 06 · COMPARATIVA antes/después (corta, contextualizada)
============================================================ --}}
<section class="rule-b" style="background-color: var(--color-paper-warm);">
    <div class="mx-auto max-w-[1480px] px-6 lg:px-10 pt-16 lg:pt-24 pb-20 lg:pb-28">

        <div class="grid grid-cols-12 gap-8 mb-14">
            <div class="col-span-12 lg:col-span-3">
                <div class="eyebrow mb-4">SEC 06 · DIFERENCIAS</div>
                <p class="mono text-[12px] leading-relaxed" style="color: var(--color-ink-mute);">
                    Cinco cambios concretos<br>
                    que el operador siente<br>
                    desde el primer día.
                </p>
            </div>
            <div class="col-span-12 lg:col-span-9">
                <h2 class="display-lg" style="color: var(--color-ink);">
                    Antes, <span class="serif italic">después.</span>
                </h2>
            </div>
        </div>

        <div class="rule-strong-t rule-strong-b">

            <div class="grid grid-cols-12 py-3 rule-b mono text-[10px] tracking-[0.16em] uppercase" style="color: var(--color-ink-mute);">
                <div class="col-span-1">Nº</div>
                <div class="col-span-3 lg:col-span-3">Operación</div>
                <div class="col-span-4 lg:col-span-4">Antes</div>
                <div class="col-span-4 lg:col-span-4">Hoy</div>
            </div>

            @php
                $rows = [
                    ['01', 'Subir precio del hielo 5K', 'Editar fila por fila para cada cliente', 'Una sola modificación en el catálogo maestro'],
                    ['02', 'Comprobante de entrega', 'Imprimir en papel y archivar', 'PDF firmado adjunto al correo automáticamente'],
                    ['03', 'Firma del cliente', 'Hoja física, archivador físico', 'Firma en pantalla, disco privado, embebida en PDF'],
                    ['04', 'Reporte de ventas por ruta', 'Excel manual al final del mes', 'Filtros combinables + export al instante'],
                    ['05', 'Acceso del vendedor', 'No existía panel propio', 'Panel /vendedor responsive + API para app móvil'],
                ];
            @endphp

            @foreach ($rows as $row)
                <div class="grid grid-cols-12 py-4 rule-b items-baseline">
                    <div class="col-span-1 serif italic text-2xl" style="color: var(--color-terra);">{{ $row[0] }}</div>
                    <div class="col-span-3 lg:col-span-3 text-[14px] font-medium" style="color: var(--color-ink); font-family: var(--font-sans);">{{ $row[1] }}</div>
                    <div class="col-span-4 lg:col-span-4 text-[13px] mono leading-snug" style="color: var(--color-ink-mute); text-decoration: line-through; text-decoration-thickness: 0.5px;">{{ $row[2] }}</div>
                    <div class="col-span-4 lg:col-span-4 text-[13px] leading-snug" style="color: var(--color-ink); font-family: var(--font-sans);">
                        <span class="serif italic" style="color: var(--color-terra); margin-right: 4px;">→</span>{{ $row[3] }}
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</section>

{{-- ============================================================
     SEC 07 · ACCESO FINAL
============================================================ --}}
<section id="acceso" class="relative overflow-hidden" style="background-color: var(--color-ink); color: var(--color-paper);">

    <div aria-hidden="true" class="absolute inset-0 opacity-[0.04] pointer-events-none"
         style="background-image: linear-gradient(to right, rgba(255,255,255,0.5) 1px, transparent 1px), linear-gradient(to bottom, rgba(255,255,255,0.5) 1px, transparent 1px); background-size: 56px 56px;"></div>

    <div class="relative mx-auto max-w-[1480px] px-6 lg:px-10 pt-20 lg:pt-28 pb-20 lg:pb-28">
        <div class="grid grid-cols-12 gap-8">

            <div class="col-span-12 lg:col-span-3">
                <div class="mono text-[11px] tracking-[0.16em] uppercase opacity-60">SEC 07 · ACCESO</div>
            </div>

            <div class="col-span-12 lg:col-span-9">
                <h2 class="display-lg mb-10" style="color: var(--color-paper);">
                    Entra como <span class="serif italic" style="color: var(--color-frost);">administrador</span><br>
                    o como <span class="serif italic" style="color: var(--color-frost);">vendedor.</span>
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 lg:gap-8 max-w-3xl">

                    <a href="/admin/login" class="access-card">
                        <div class="flex items-baseline justify-between mb-8">
                            <span class="mono text-[10px] tracking-[0.18em] uppercase opacity-60">MOD·ADMIN</span>
                            <span class="serif italic text-3xl opacity-40" style="color: var(--color-frost);">/admin</span>
                        </div>
                        <div class="display-md mb-3" style="color: var(--color-paper);">Panel administración</div>
                        <p class="text-[13px] leading-[1.55] opacity-70" style="font-family: var(--font-sans);">
                            Catálogo, clientes, remisiones, empleados, usuarios y roles, reportes,
                            branding. Acceso total para SuperAdmin.
                        </p>
                        <div class="mt-6 inline-flex items-center gap-2 mono text-[11px] tracking-[0.14em] uppercase opacity-90">
                            Acceder
                            <svg width="12" height="12" viewBox="0 0 14 14" fill="none"><path d="M1 7H13M13 7L7 1M13 7L7 13" stroke="currentColor" stroke-width="1.2"/></svg>
                        </div>
                    </a>

                    <a href="/vendedor/login" class="access-card">
                        <div class="flex items-baseline justify-between mb-8">
                            <span class="mono text-[10px] tracking-[0.18em] uppercase opacity-60">MOD·VENDEDOR</span>
                            <span class="serif italic text-3xl opacity-40" style="color: var(--color-frost);">/vendedor</span>
                        </div>
                        <div class="display-md mb-3" style="color: var(--color-paper);">Panel vendedor</div>
                        <p class="text-[13px] leading-[1.55] opacity-70" style="font-family: var(--font-sans);">
                            Crear remisiones desde el navegador con vista responsive. Listado restringido
                            al usuario logueado. Comprobante PDF descargable.
                        </p>
                        <div class="mt-6 inline-flex items-center gap-2 mono text-[11px] tracking-[0.14em] uppercase opacity-90">
                            Acceder
                            <svg width="12" height="12" viewBox="0 0 14 14" fill="none"><path d="M1 7H13M13 7L7 1M13 7L7 13" stroke="currentColor" stroke-width="1.2"/></svg>
                        </div>
                    </a>

                </div>

                <div class="mt-12 pt-8" style="border-top: 1px solid rgba(243, 238, 229, 0.15);">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-3xl">
                        <div>
                            <div class="mono text-[10px] tracking-[0.18em] uppercase opacity-50 mb-2">Credenciales de demo</div>
                            <div class="mono text-[12px] opacity-90" style="line-height: 1.7;">
                                superadmin@svd.test<br>
                                Super/Admin?
                            </div>
                        </div>
                        <div>
                            <div class="mono text-[10px] tracking-[0.18em] uppercase opacity-50 mb-2">Soporte</div>
                            <div class="mono text-[12px] opacity-90" style="line-height: 1.7;">
                                ICEMAN SERVICES<br>
                                Barrancabermeja, Santander · CO
                            </div>
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
<footer>
    <div class="mx-auto max-w-[1480px] px-6 lg:px-10 pt-12 lg:pt-16 pb-10">

        <div class="rule-b pb-10 mb-10">
            <div class="grid grid-cols-12 gap-6 items-end">
                <div class="col-span-12 lg:col-span-8">
                    <div class="serif italic leading-[0.85]" style="font-size: clamp(5rem, 14vw, 11rem); color: var(--color-ink);">
                        SVD<span style="color: var(--color-terra);">.</span>
                    </div>
                </div>
                <div class="col-span-12 lg:col-span-4 mono text-[12px] leading-[1.7]" style="color: var(--color-ink-soft);">
                    Sistema de Ventas y Despachos.<br>
                    Distribución de hielo, agua &amp; neveras.<br>
                    Edición {{ now()->locale('es')->isoFormat('MMMM YYYY') }}.
                </div>
            </div>
        </div>

        <div class="grid grid-cols-12 gap-6">

            <div class="col-span-6 md:col-span-3">
                <div class="mono text-[10px] tracking-[0.16em] uppercase mb-3" style="color: var(--color-ink-mute);">PANEL</div>
                <ul class="space-y-1.5 text-[13px]" style="font-family: var(--font-sans);">
                    <li><a href="/admin/login" class="link-rev" style="color: var(--color-ink);">Administración</a></li>
                    <li><a href="/vendedor/login" class="link-rev" style="color: var(--color-ink);">Vendedor</a></li>
                </ul>
            </div>

            <div class="col-span-6 md:col-span-3">
                <div class="mono text-[10px] tracking-[0.16em] uppercase mb-3" style="color: var(--color-ink-mute);">CATÁLOGO</div>
                <ul class="space-y-1.5 mono text-[12px]" style="color: var(--color-ink-soft);">
                    <li>Hielo · 1.5K · 5K · 15K</li>
                    <li>Agua · 14 marcas</li>
                    <li>Envases vacíos</li>
                    <li>Neveras de icopor</li>
                </ul>
            </div>

            <div class="col-span-6 md:col-span-3">
                <div class="mono text-[10px] tracking-[0.16em] uppercase mb-3" style="color: var(--color-ink-mute);">OPERACIÓN</div>
                <ul class="space-y-1.5 mono text-[12px]" style="color: var(--color-ink-soft);">
                    <li>16 rutas activas</li>
                    <li>Firma digital del cliente</li>
                    <li>PDF firmado por entrega</li>
                    <li>API móvil del vendedor</li>
                </ul>
            </div>

            <div class="col-span-6 md:col-span-3">
                <div class="mono text-[10px] tracking-[0.16em] uppercase mb-3" style="color: var(--color-ink-mute);">CONTACTO</div>
                <p class="text-[12px] leading-[1.6]" style="color: var(--color-ink-soft); font-family: var(--font-sans);">
                    Barrancabermeja, Santander · Colombia<br>
                    ICEMAN SERVICES — Confipetrol.
                </p>
                <p class="mt-3 mono text-[11px]" style="color: var(--color-ink-mute);">© {{ now()->year }} · Privado</p>
            </div>

        </div>

    </div>
</footer>

</body>
</html>
