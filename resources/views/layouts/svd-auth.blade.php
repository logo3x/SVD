<x-filament-panels::layout.base :livewire="$livewire">
    @vite(['resources/css/app.css'])

    <div class="svd-login" style="min-height: 100dvh; background-color: var(--color-surface); color: var(--color-ink); font-family: var(--font-sans), system-ui, sans-serif;">
        <div class="svd-login-grid">

            {{-- LEFT · Brand panel institucional --}}
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
                        Acceso al<br>
                        <em style="color: {{ $livewire->getPanelAccent() }};">{{ Str::of($livewire->getPanelLabel())->after('del ')->after('de ')->lower() }}</em>.
                    </h1>
                    <p class="svd-login-tagline">{{ $livewire->getPanelTagline() }}</p>
                </div>

                <div class="svd-login-bottom">
                    <div class="svd-login-foot">
                        <a href="/" class="svd-login-foot-link">← Volver al inicio</a>
                        <span class="svd-login-foot-ref">ICEMAN · SVD</span>
                    </div>
                </div>

                <div aria-hidden="true" class="svd-login-blueprint"></div>
            </aside>

            {{-- RIGHT · Formulario (Livewire slot) --}}
            <main class="svd-login-form-col">
                <div class="svd-login-form-frame">

                    <div class="svd-login-form-head">
                        <span class="svd-login-form-num">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2"/>
                                <path d="M7 11V7a5 5 0 0110 0v4"/>
                            </svg>
                        </span>
                        <div class="svd-login-form-head-text">
                            <div class="svd-login-form-head-eyebrow">CREDENCIALES</div>
                            <div class="svd-login-form-head-title">Identifícate para continuar</div>
                        </div>
                    </div>

                    <div class="svd-login-rule"></div>

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
                    SVD · Plataforma institucional · <em>ICEMAN SERVICES</em> · © {{ now()->year }}
                </div>
            </main>

        </div>
    </div>
</x-filament-panels::layout.base>
