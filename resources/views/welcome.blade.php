<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="SVD — Sistema de Ventas y Despachos. Plataforma de gestión construida sobre Laravel 13 + Filament v5.">
    <title>SVD · Sistema de Ventas y Despachos</title>

    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-paper text-ink antialiased" style="background-color: var(--color-paper); color: var(--color-ink);">

{{-- ============================================================
     HEADER · Periódico técnico — fecha + nº de edición + identidad
============================================================ --}}
<header class="rule-b">
    <div class="mx-auto max-w-[1480px] px-6 lg:px-10">
        <div class="flex items-center justify-between gap-4 py-4">
            <div class="flex items-center gap-3">
                <span class="inline-block w-2.5 h-2.5" style="background-color: var(--color-terra);"></span>
                <span class="mono text-[11px] tracking-[0.18em] uppercase" style="color: var(--color-ink);">SVD · Sistema de Ventas y Despachos</span>
            </div>
            <div class="hidden md:flex items-center gap-6 mono text-[11px] tracking-[0.14em] uppercase" style="color: var(--color-ink-soft);">
                <span>Edición 01</span>
                <span class="opacity-60">·</span>
                <span>{{ now()->locale('es')->isoFormat('D MMM YYYY') }}</span>
                <span class="opacity-60">·</span>
                <span>Barrancabermeja, CO</span>
            </div>
            <nav class="flex items-center gap-3">
                <a href="https://github.com/logo3x/SVD" target="_blank" rel="noopener" class="hidden sm:inline-flex items-center gap-1.5 mono text-[11px] tracking-[0.14em] uppercase link-rev" style="color: var(--color-ink-soft);">
                    Repositorio
                    <svg width="10" height="10" viewBox="0 0 10 10" fill="none" aria-hidden="true"><path d="M1 9L9 1M9 1H2M9 1V8" stroke="currentColor" stroke-width="1"/></svg>
                </a>
                <a href="/admin/login" class="inline-flex items-center px-4 py-2 mono text-[11px] tracking-[0.14em] uppercase btn-ink">
                    Acceder
                </a>
            </nav>
        </div>
    </div>
</header>

{{-- ============================================================
     HERO · Display editorial gigante + meta-data del proyecto
============================================================ --}}
<section class="relative overflow-hidden bg-blueprint">
    <div class="mx-auto max-w-[1480px] px-6 lg:px-10 pt-12 lg:pt-20 pb-16 lg:pb-28">

        <div class="grid grid-cols-12 gap-y-10 lg:gap-8">

            {{-- Eyebrow columna 1 --}}
            <div class="col-span-12 lg:col-span-3 rise rise-1">
                <div class="eyebrow mb-3">SEC 01 · LANZAMIENTO</div>
                <div class="mono text-[12px] leading-relaxed" style="color: var(--color-ink-soft);">
                    Reconstrucción del sistema legacy<br>
                    <span style="color: var(--color-ink);">ventas.icemanservice.com.co</span><br>
                    Laravel 8 → Laravel 13.
                </div>
                <div class="mt-6 inline-flex items-center gap-2 mono text-[10px] tracking-[0.18em] uppercase" style="color: var(--color-terra);">
                    <span class="inline-block w-1.5 h-1.5 rounded-full" style="background-color: var(--color-terra);"></span>
                    En producción
                </div>
            </div>

            {{-- Display headline columna 2 --}}
            <div class="col-span-12 lg:col-span-9 rise rise-2">
                <h1 class="display-xl" style="color: var(--color-ink);">
                    Ventas,<br>
                    despachos<br>
                    <span style="color: var(--color-terra);">&amp; <span class="serif italic">control</span></span>
                </h1>
            </div>

            {{-- Linea divisora animada --}}
            <div class="col-span-12 -mt-2">
                <div class="draw-rule rise rise-3" style="height: 1px; background-color: var(--color-ink);"></div>
            </div>

            {{-- Bajada + meta-data --}}
            <div class="col-span-12 lg:col-span-5 lg:col-start-1 rise rise-3">
                <p class="display-md" style="color: var(--color-ink-soft);">
                    Una plataforma <span style="color: var(--color-ink);">monolítica y honesta</span> para gestionar remisiones, clientes y catálogo —
                    sin la deuda técnica de doce años.
                </p>
            </div>

            <div class="col-span-12 lg:col-span-4 lg:col-start-7 rise rise-4">
                <div class="rule-t pt-5">
                    <p class="text-[15px] leading-[1.65]" style="color: var(--color-ink-soft); font-family: var(--font-sans);">
                        Construida sobre <span style="color: var(--color-ink); font-weight: 500;">Laravel 13 + Filament v5</span> con dos paneles
                        (administración + vendedor en campo), API REST con Sanctum, reportes Excel/PDF y catálogo maestro
                        con override de precio por cliente. Pensada para vivir diez años más.
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
                    REF · SVD/2026.05<br>
                    BUILD · main@5dbce28<br>
                    STACK · PHP 8.5 / MySQL 8
                </div>
            </div>

        </div>
    </div>

    {{-- "SVD" marca de agua tipo título de revista --}}
    <div aria-hidden="true" class="absolute -bottom-12 lg:-bottom-20 right-[-4%] pointer-events-none select-none opacity-[0.05]" style="font-family: var(--font-serif); font-style: italic; font-size: clamp(20rem, 38vw, 44rem); line-height: 0.8; color: var(--color-ink);">
        SVD
    </div>
</section>

{{-- ============================================================
     STATS · Marquee técnico tipo tira de cinta
============================================================ --}}
<section class="rule-strong-t rule-b overflow-hidden" style="background-color: var(--color-ink); color: var(--color-paper);">
    <div class="marquee-track flex whitespace-nowrap py-5">
        @for ($i = 0; $i < 2; $i++)
            <div class="flex items-center gap-12 px-6 mono text-[13px] tracking-[0.18em] uppercase">
                <span class="flex items-center gap-3"><span class="serif italic text-2xl" style="color: var(--color-terra-soft);">76</span> permisos RBAC</span>
                <span class="opacity-40">§</span>
                <span class="flex items-center gap-3"><span class="serif italic text-2xl" style="color: var(--color-terra-soft);">31</span> productos catálogo</span>
                <span class="opacity-40">§</span>
                <span class="flex items-center gap-3"><span class="serif italic text-2xl" style="color: var(--color-terra-soft);">12</span> endpoints API v1</span>
                <span class="opacity-40">§</span>
                <span class="flex items-center gap-3"><span class="serif italic text-2xl" style="color: var(--color-terra-soft);">29</span> tests verdes</span>
                <span class="opacity-40">§</span>
                <span class="flex items-center gap-3"><span class="serif italic text-2xl" style="color: var(--color-terra-soft);">16</span> rutas de reparto</span>
                <span class="opacity-40">§</span>
                <span class="flex items-center gap-3"><span class="serif italic text-2xl" style="color: var(--color-terra-soft);">2</span> paneles Filament</span>
                <span class="opacity-40">§</span>
            </div>
        @endfor
    </div>
</section>

{{-- ============================================================
     SEC 02 · MÓDULOS / CARACTERÍSTICAS — grid editorial
============================================================ --}}
<section class="rule-b">
    <div class="mx-auto max-w-[1480px] px-6 lg:px-10 pt-16 lg:pt-24 pb-20 lg:pb-28">

        <div class="grid grid-cols-12 gap-8 mb-16">
            <div class="col-span-12 lg:col-span-3">
                <div class="eyebrow mb-4">SEC 02 · ARQUITECTURA</div>
                <p class="mono text-[12px] leading-relaxed" style="color: var(--color-ink-mute);">
                    Seis decisiones de diseño<br>
                    que distinguen este sistema<br>
                    del manejo legacy.
                </p>
            </div>
            <div class="col-span-12 lg:col-span-9">
                <h2 class="display-lg" style="color: var(--color-ink);">
                    Decisiones <span class="serif italic">no triviales</span><br>
                    sobre datos y dominio.
                </h2>
            </div>
        </div>

        <div class="rule-strong-t grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3">

            {{-- Módulo 01 — Catálogo maestro --}}
            <article class="p-8 lg:p-10 rule-b md:rule-r relative" style="border-bottom: 1px solid var(--color-rule); border-right: 1px solid var(--color-rule);">
                <div class="flex items-baseline justify-between mb-10">
                    <span class="serif italic text-5xl leading-none" style="color: var(--color-terra);">01</span>
                    <span class="mono text-[10px] tracking-[0.14em] uppercase" style="color: var(--color-ink-mute);">MOD·CATÁLOGO</span>
                </div>
                <h3 class="display-md mb-4" style="color: var(--color-ink);">Catálogo maestro con override por cliente.</h3>
                <p class="text-[14px] leading-[1.6]" style="color: var(--color-ink-soft);">
                    En lugar de duplicar 31 productos por cada cliente (≈ N×31 filas en producción), un catálogo maestro
                    + un pivot <span class="mono text-[13px]">client_product.custom_price</span>. Editar un precio = una sola fila.
                </p>
                <div class="mt-6 pt-4 rule-t mono text-[11px] tracking-[0.08em]" style="color: var(--color-ink-mute);">
                    <span style="color: var(--color-ink);">products</span> · <span style="color: var(--color-ink);">client_product</span> · <span style="color: var(--color-ink);">remission_product</span>
                </div>
            </article>

            {{-- Módulo 02 — Dos paneles Filament --}}
            <article class="p-8 lg:p-10" style="border-bottom: 1px solid var(--color-rule); border-right: 1px solid var(--color-rule);">
                <div class="flex items-baseline justify-between mb-10">
                    <span class="serif italic text-5xl leading-none" style="color: var(--color-navy);">02</span>
                    <span class="mono text-[10px] tracking-[0.14em] uppercase" style="color: var(--color-ink-mute);">MOD·PANEL</span>
                </div>
                <h3 class="display-md mb-4" style="color: var(--color-ink);">Dos paneles, un dominio.</h3>
                <p class="text-[14px] leading-[1.6]" style="color: var(--color-ink-soft);">
                    <span class="mono text-[13px]">/admin</span> con clientes, productos, empleados, reportes y branding.
                    <span class="mono text-[13px]">/vendedor</span> recortado a las remisiones del usuario logueado.
                    Filament v5, Livewire 3, Alpine.
                </p>
                <div class="mt-6 pt-4 rule-t flex items-center gap-3 mono text-[11px] tracking-[0.08em]" style="color: var(--color-ink-mute);">
                    <span class="inline-block w-1.5 h-1.5" style="background-color: var(--color-mint);"></span>
                    Shield · 76 permisos auto-generados
                </div>
            </article>

            {{-- Módulo 03 — API REST --}}
            <article class="p-8 lg:p-10" style="border-bottom: 1px solid var(--color-rule);">
                <div class="flex items-baseline justify-between mb-10">
                    <span class="serif italic text-5xl leading-none" style="color: var(--color-mint);">03</span>
                    <span class="mono text-[10px] tracking-[0.14em] uppercase" style="color: var(--color-ink-mute);">MOD·API</span>
                </div>
                <h3 class="display-md mb-4" style="color: var(--color-ink);">API REST con Sanctum &amp; throttle.</h3>
                <p class="text-[14px] leading-[1.6]" style="color: var(--color-ink-soft);">
                    12 endpoints en <span class="mono text-[13px]">/api/v1</span> para la app móvil de vendedores:
                    login con token, productos del cliente con precio resuelto, crear remisión, subir firma.
                </p>
                <div class="mt-6 pt-4 rule-t mono text-[11px] tracking-[0.08em]" style="color: var(--color-ink-mute);">
                    Throttle <span style="color: var(--color-ink);">6/min</span> login · <span style="color: var(--color-ink);">30/min</span> escrituras
                </div>
            </article>

            {{-- Módulo 04 — Reportes --}}
            <article class="p-8 lg:p-10" style="border-bottom: 1px solid var(--color-rule); border-right: 1px solid var(--color-rule);">
                <div class="flex items-baseline justify-between mb-10">
                    <span class="serif italic text-5xl leading-none" style="color: var(--color-terra);">04</span>
                    <span class="mono text-[10px] tracking-[0.14em] uppercase" style="color: var(--color-ink-mute);">MOD·REPORTES</span>
                </div>
                <h3 class="display-md mb-4" style="color: var(--color-ink);">Excel &amp; PDF, sin abrir la página.</h3>
                <p class="text-[14px] leading-[1.6]" style="color: var(--color-ink-soft);">
                    OpenSpout streamed para datasets grandes (chunkById 200). DomPDF para el comprobante de cada remisión,
                    adjuntado al email automático con routing por tipo de pago.
                </p>
                <div class="mt-6 pt-4 rule-t mono text-[11px] tracking-[0.08em]" style="color: var(--color-ink-mute);">
                    <span style="color: var(--color-ink);">Reportes</span> · filtros combinables, preview reactivo
                </div>
            </article>

            {{-- Módulo 05 — Multi-marca --}}
            <article class="p-8 lg:p-10" style="border-bottom: 1px solid var(--color-rule); border-right: 1px solid var(--color-rule);">
                <div class="flex items-baseline justify-between mb-10">
                    <span class="serif italic text-5xl leading-none" style="color: var(--color-navy);">05</span>
                    <span class="mono text-[10px] tracking-[0.14em] uppercase" style="color: var(--color-ink-mute);">MOD·BRANDING</span>
                </div>
                <h3 class="display-md mb-4" style="color: var(--color-ink);">Multi-marca por configuración.</h3>
                <p class="text-[14px] leading-[1.6]" style="color: var(--color-ink-soft);">
                    Spatie Settings. El routing de emails y el branding del PDF se resuelve desde
                    <span class="mono text-[13px]">BrandingSettings</span> — nada hardcodeado.
                    El mismo código sirve para varios clientes.
                </p>
                <div class="mt-6 pt-4 rule-t mono text-[11px] tracking-[0.08em]" style="color: var(--color-ink-mute);">
                    Página <span style="color: var(--color-ink);">/admin/settings</span> · cambio en caliente
                </div>
            </article>

            {{-- Módulo 06 — Auditoría --}}
            <article class="p-8 lg:p-10" style="border-bottom: 1px solid var(--color-rule);">
                <div class="flex items-baseline justify-between mb-10">
                    <span class="serif italic text-5xl leading-none" style="color: var(--color-mint);">06</span>
                    <span class="mono text-[10px] tracking-[0.14em] uppercase" style="color: var(--color-ink-mute);">MOD·AUDIT</span>
                </div>
                <h3 class="display-md mb-4" style="color: var(--color-ink);">Auditoría sobre todo lo que importa.</h3>
                <p class="text-[14px] leading-[1.6]" style="color: var(--color-ink-soft);">
                    Spatie ActivityLog en Cliente, Producto, Remisión y Usuario. Login, logout, intentos fallidos y
                    lockouts también quedan registrados. La auditoría no es una página, es una garantía.
                </p>
                <div class="mt-6 pt-4 rule-t mono text-[11px] tracking-[0.08em]" style="color: var(--color-ink-mute);">
                    Tabla <span style="color: var(--color-ink);">activity_log</span> · IP, user-agent, guard
                </div>
            </article>

        </div>
    </div>
</section>

{{-- ============================================================
     SEC 03 · COMPARATIVA — antes / después en formato tabular
============================================================ --}}
<section class="rule-b" style="background-color: var(--color-paper-warm);">
    <div class="mx-auto max-w-[1480px] px-6 lg:px-10 pt-16 lg:pt-24 pb-20 lg:pb-28">

        <div class="grid grid-cols-12 gap-8 mb-14">
            <div class="col-span-12 lg:col-span-3">
                <div class="eyebrow mb-4">SEC 03 · DIFERENCIAS</div>
                <p class="mono text-[12px] leading-relaxed" style="color: var(--color-ink-mute);">
                    Catorce mejoras<br>
                    documentadas frente al<br>
                    sistema heredado.
                </p>
            </div>
            <div class="col-span-12 lg:col-span-9">
                <h2 class="display-lg" style="color: var(--color-ink);">
                    Antes, <span class="serif italic">después</span>.
                </h2>
            </div>
        </div>

        {{-- Tabla técnica --}}
        <div class="rule-strong-t rule-strong-b">

            {{-- Header --}}
            <div class="grid grid-cols-12 py-3 rule-b mono text-[10px] tracking-[0.16em] uppercase" style="color: var(--color-ink-mute);">
                <div class="col-span-1">Nº</div>
                <div class="col-span-3 lg:col-span-3">Dimensión</div>
                <div class="col-span-4 lg:col-span-4">Sistema legacy</div>
                <div class="col-span-4 lg:col-span-4">SVD 2026</div>
            </div>

            @php
                $rows = [
                    ['01', 'Catálogo productos', '31 filas duplicadas por cliente', 'Catálogo maestro + pivot de overrides'],
                    ['02', 'Permisos', '24 hardcodeados manualmente', 'Filament Shield — 76 auto-generados'],
                    ['03', 'Firmas digitales', 'public/firmas/*.png — URL pública', 'Disco privado local, signed URLs'],
                    ['04', 'API Sanctum', 'Sin middleware (vulnerable)', 'auth:sanctum + abilities + throttle'],
                    ['05', 'Exports Excel', 'Rutas públicas sin auth', 'OpenSpout + policy + filtros server-side'],
                    ['06', 'Empleados / Usuarios', 'Todo mezclado en users', 'users + employee_profiles desacoplados'],
                    ['07', 'Edición remisiones', 'Deshabilitada en el código', 'Habilitada + ActivityLog'],
                    ['08', 'Auditoría', 'No existe', 'Spatie ActivityLog · login/logout incluidos'],
                    ['09', 'Notificaciones email', 'Sincrónicas, bloqueantes', 'Encoladas en database driver'],
                    ['10', 'Comprobantes', 'HTML print', 'PDF DomPDF descargable + adjunto'],
                    ['11', 'Frontend', 'AdminLTE + jQuery', 'Filament 5 · Livewire 3 · Alpine · Tailwind'],
                    ['12', 'Tipos', 'Strings sueltos', 'PHP 8.5 backed enums tipo-seguros'],
                    ['13', 'Precio histórico', 'Se perdía al cambiar el catálogo', 'unit_price_snapshot en el pivot'],
                    ['14', 'GPS de remisión', 'varchar "lat,lng"', 'decimal(10,8) / decimal(11,8)'],
                ];
            @endphp

            @foreach ($rows as $row)
                <div class="grid grid-cols-12 py-4 rule-b items-baseline group">
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
     SEC 04 · MÉTRICAS DEL PROYECTO — números enormes
============================================================ --}}
<section class="rule-b bg-grain">
    <div class="mx-auto max-w-[1480px] px-6 lg:px-10 pt-16 lg:pt-24 pb-20 lg:pb-28">

        <div class="grid grid-cols-12 gap-8 mb-14">
            <div class="col-span-12 lg:col-span-3">
                <div class="eyebrow mb-4">SEC 04 · MÉTRICAS</div>
                <p class="mono text-[12px] leading-relaxed" style="color: var(--color-ink-mute);">
                    Cifras de la edición<br>
                    actual del repositorio,<br>
                    publicadas sin maquillaje.
                </p>
            </div>
            <div class="col-span-12 lg:col-span-9">
                <h2 class="display-lg" style="color: var(--color-ink);">
                    Lo que <span class="serif italic">cabe</span><br>
                    en una sola tabla.
                </h2>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 rule-strong-t rule-b">

            @php
                $stats = [
                    ['76', 'permisos RBAC', 'shield:generate'],
                    ['31', 'productos catálogo', 'MasterProductCatalogSeeder'],
                    ['12', 'endpoints API', '/api/v1/*'],
                    ['29', 'tests verdes', 'phpunit · 68 asserts'],
                    ['20', 'archivos strict_types', 'declare strict'],
                    ['14', 'mejoras vs legacy', 'ver tabla §03'],
                ];
            @endphp

            @foreach ($stats as $i => $stat)
                <div class="px-5 lg:px-6 py-8 lg:py-12 {{ $i < count($stats) - 1 ? 'rule-r' : '' }}" style="border-right: {{ $i < count($stats) - 1 ? '1px solid var(--color-rule)' : 'none' }};">
                    <div class="serif italic leading-none" style="color: var(--color-ink); font-size: clamp(3.5rem, 6vw, 5.5rem);">
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
     SEC 05 · CTA FINAL — invitación a entrar al sistema
============================================================ --}}
<section class="relative overflow-hidden" style="background-color: var(--color-ink); color: var(--color-paper);">

    <div aria-hidden="true" class="absolute inset-0 opacity-[0.04] pointer-events-none"
         style="background-image: linear-gradient(to right, rgba(255,255,255,0.5) 1px, transparent 1px), linear-gradient(to bottom, rgba(255,255,255,0.5) 1px, transparent 1px); background-size: 56px 56px;"></div>

    <div class="relative mx-auto max-w-[1480px] px-6 lg:px-10 pt-20 lg:pt-28 pb-20 lg:pb-28">
        <div class="grid grid-cols-12 gap-8">

            <div class="col-span-12 lg:col-span-3">
                <div class="mono text-[11px] tracking-[0.16em] uppercase opacity-60">SEC 05 · ACCESO</div>
            </div>

            <div class="col-span-12 lg:col-span-9">
                <h2 class="display-lg mb-10" style="color: var(--color-paper);">
                    Entra como <span class="serif italic" style="color: var(--color-terra-soft);">administrador</span><br>
                    o como <span class="serif italic" style="color: var(--color-terra-soft);">vendedor.</span>
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 lg:gap-8 max-w-3xl">

                    <a href="/admin/login" class="group relative overflow-hidden p-6 lg:p-8 transition-colors duration-300" style="border: 1px solid rgba(243, 238, 229, 0.2);">
                        <div class="flex items-baseline justify-between mb-8">
                            <span class="mono text-[10px] tracking-[0.18em] uppercase opacity-60">MOD·ADMIN</span>
                            <span class="serif italic text-3xl opacity-40 group-hover:opacity-100 transition-opacity" style="color: var(--color-terra-soft);">/admin</span>
                        </div>
                        <div class="display-md mb-3" style="color: var(--color-paper);">Panel administración</div>
                        <p class="text-[13px] leading-[1.55] opacity-70" style="font-family: var(--font-sans);">
                            Catálogo, clientes, remisiones, empleados, usuarios &amp; roles, reportes, branding.
                            Acceso total con SuperAdmin.
                        </p>
                        <div class="mt-6 inline-flex items-center gap-2 mono text-[11px] tracking-[0.14em] uppercase opacity-90 group-hover:opacity-100">
                            Acceder
                            <svg width="12" height="12" viewBox="0 0 14 14" fill="none" class="transition-transform group-hover:translate-x-1"><path d="M1 7H13M13 7L7 1M13 7L7 13" stroke="currentColor" stroke-width="1.2"/></svg>
                        </div>
                    </a>

                    <a href="/vendedor/login" class="group relative overflow-hidden p-6 lg:p-8 transition-colors duration-300" style="border: 1px solid rgba(243, 238, 229, 0.2);">
                        <div class="flex items-baseline justify-between mb-8">
                            <span class="mono text-[10px] tracking-[0.18em] uppercase opacity-60">MOD·VENDEDOR</span>
                            <span class="serif italic text-3xl opacity-40 group-hover:opacity-100 transition-opacity" style="color: var(--color-terra-soft);">/vendedor</span>
                        </div>
                        <div class="display-md mb-3" style="color: var(--color-paper);">Panel vendedor</div>
                        <p class="text-[13px] leading-[1.55] opacity-70" style="font-family: var(--font-sans);">
                            Crear remisiones desde el navegador (responsive). Listado scope al usuario logueado.
                            Comprobante PDF descargable.
                        </p>
                        <div class="mt-6 inline-flex items-center gap-2 mono text-[11px] tracking-[0.14em] uppercase opacity-90 group-hover:opacity-100">
                            Acceder
                            <svg width="12" height="12" viewBox="0 0 14 14" fill="none" class="transition-transform group-hover:translate-x-1"><path d="M1 7H13M13 7L7 1M13 7L7 13" stroke="currentColor" stroke-width="1.2"/></svg>
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
                            <div class="mono text-[10px] tracking-[0.18em] uppercase opacity-50 mb-2">Repositorio público</div>
                            <a href="https://github.com/logo3x/SVD" target="_blank" rel="noopener" class="mono text-[12px] opacity-90 link-rev inline-flex items-center gap-2">
                                github.com/logo3x/SVD
                                <svg width="10" height="10" viewBox="0 0 10 10" fill="none"><path d="M1 9L9 1M9 1H2M9 1V8" stroke="currentColor" stroke-width="1"/></svg>
                            </a>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</section>

{{-- ============================================================
     FOOTER · Colofón editorial
============================================================ --}}
<footer>
    <div class="mx-auto max-w-[1480px] px-6 lg:px-10 pt-12 lg:pt-16 pb-10">

        {{-- Top: marca enorme tipo masthead --}}
        <div class="rule-b pb-10 mb-10">
            <div class="grid grid-cols-12 gap-6 items-end">
                <div class="col-span-12 lg:col-span-8">
                    <div class="serif italic leading-[0.85]" style="font-size: clamp(5rem, 14vw, 11rem); color: var(--color-ink);">
                        SVD<span style="color: var(--color-terra);">.</span>
                    </div>
                </div>
                <div class="col-span-12 lg:col-span-4 mono text-[12px] leading-[1.7]" style="color: var(--color-ink-soft);">
                    Sistema de Ventas y Despachos.<br>
                    Edición publicada el {{ now()->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }}.<br>
                    Construido con Laravel 13 + Filament 5.
                </div>
            </div>
        </div>

        {{-- Bottom: data --}}
        <div class="grid grid-cols-12 gap-6">

            <div class="col-span-6 md:col-span-3">
                <div class="mono text-[10px] tracking-[0.16em] uppercase mb-3" style="color: var(--color-ink-mute);">PANEL</div>
                <ul class="space-y-1.5 text-[13px]" style="font-family: var(--font-sans);">
                    <li><a href="/admin/login" class="link-rev" style="color: var(--color-ink);">Administración</a></li>
                    <li><a href="/vendedor/login" class="link-rev" style="color: var(--color-ink);">Vendedor</a></li>
                </ul>
            </div>

            <div class="col-span-6 md:col-span-3">
                <div class="mono text-[10px] tracking-[0.16em] uppercase mb-3" style="color: var(--color-ink-mute);">DESARROLLO</div>
                <ul class="space-y-1.5 text-[13px]" style="font-family: var(--font-sans);">
                    <li><a href="https://github.com/logo3x/SVD" target="_blank" rel="noopener" class="link-rev" style="color: var(--color-ink);">Repositorio GitHub</a></li>
                    <li><a href="https://github.com/logo3x/SVD/blob/main/MOBILE-APP-CONTEXT.md" target="_blank" rel="noopener" class="link-rev" style="color: var(--color-ink);">Contexto app móvil</a></li>
                    <li><a href="https://github.com/logo3x/SVD/blob/main/DEPLOY.md" target="_blank" rel="noopener" class="link-rev" style="color: var(--color-ink);">Guía de despliegue</a></li>
                </ul>
            </div>

            <div class="col-span-6 md:col-span-3">
                <div class="mono text-[10px] tracking-[0.16em] uppercase mb-3" style="color: var(--color-ink-mute);">STACK</div>
                <ul class="space-y-1.5 mono text-[12px]" style="color: var(--color-ink-soft);">
                    <li>Laravel 13 · PHP 8.5</li>
                    <li>Filament 5 · Livewire 3</li>
                    <li>MySQL 8 · Sanctum</li>
                    <li>OpenSpout · DomPDF</li>
                </ul>
            </div>

            <div class="col-span-6 md:col-span-3">
                <div class="mono text-[10px] tracking-[0.16em] uppercase mb-3" style="color: var(--color-ink-mute);">COLOFÓN</div>
                <p class="text-[12px] leading-[1.6]" style="color: var(--color-ink-soft); font-family: var(--font-sans);">
                    Compuesto en <span class="serif italic">Instrument Serif</span>, Instrument Sans y JetBrains Mono.
                </p>
                <p class="mt-3 mono text-[11px]" style="color: var(--color-ink-mute);">© {{ now()->year }} · Privado</p>
            </div>

        </div>

    </div>
</footer>

</body>
</html>
