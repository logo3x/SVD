<x-filament-panels::layout.base :livewire="$livewire">
    @vite(['resources/css/app.css'])

    <div class="svd-login" style="min-height: 100dvh; background-color: var(--color-paper); color: var(--color-ink); font-family: var(--font-sans), system-ui, sans-serif;">
        <div class="svd-login-grid">

            {{-- LEFT · Masthead editorial --}}
            <aside class="svd-login-masthead">

                <div class="svd-login-top">
                    <div class="svd-login-eyebrow">
                        <span class="svd-login-dot" style="background-color: {{ $livewire->getPanelAccent() }};"></span>
                        SVD · SISTEMA DE VENTAS Y DESPACHOS
                    </div>
                    <div class="svd-login-meta">
                        <span>{{ $livewire->getPanelModuleTag() }}</span>
                        <span class="svd-login-meta-sep">·</span>
                        <span>{{ now()->locale('es')->isoFormat('D MMM YYYY') }}</span>
                    </div>
                </div>

                <div class="svd-login-center">
                    <div class="svd-login-tag">{{ strtoupper($livewire->getPanelLabel()) }}</div>
                    <h1 class="svd-login-headline">
                        Entra<br>
                        <em>como</em><br>
                        <em style="color: {{ $livewire->getPanelAccent() }};">{{ Str::of($livewire->getPanelLabel())->after('del ')->after('de ')->lower() }}.</em>
                    </h1>
                    <p class="svd-login-tagline">{{ $livewire->getPanelTagline() }}</p>
                </div>

                <div class="svd-login-bottom">
                    <div class="svd-login-watermark" aria-hidden="true">SVD<span style="color: {{ $livewire->getPanelAccent() }};">.</span></div>
                    <div class="svd-login-foot">
                        <a href="/" class="svd-login-foot-link">← Volver al inicio</a>
                        <span class="svd-login-foot-ref">REF · SVD/2026.05</span>
                    </div>
                </div>

                <div aria-hidden="true" class="svd-login-blueprint"></div>
            </aside>

            {{-- RIGHT · Formulario (Livewire slot) --}}
            <main class="svd-login-form-col">
                <div class="svd-login-form-frame">

                    <div class="svd-login-form-head">
                        <span class="svd-login-form-num" style="color: {{ $livewire->getPanelAccent() }};">01</span>
                        <div class="svd-login-form-head-text">
                            <div class="svd-login-form-head-eyebrow">CREDENCIALES</div>
                            <div class="svd-login-form-head-title">Identifícate para continuar.</div>
                        </div>
                    </div>

                    <div class="svd-login-rule" style="background-color: var(--color-ink);"></div>

                    <div class="svd-login-form-body">
                        {{ $slot }}
                    </div>

                    <div class="svd-login-rule-soft"></div>

                    <div>
                        <div class="svd-login-form-foot-label">CUENTA DE DEMO</div>
                        <div class="svd-login-form-foot-creds">
                            superadmin@svd.test<br>
                            Super/Admin?
                        </div>
                    </div>
                </div>

                <div class="svd-login-form-colofon">
                    Compuesto en <em>Instrument Serif</em>, Instrument Sans, JetBrains Mono · © {{ now()->year }}
                </div>
            </main>

        </div>
    </div>
</x-filament-panels::layout.base>
