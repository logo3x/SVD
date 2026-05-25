<x-filament-widgets::widget>
    <div style="
        position: relative;
        overflow: hidden;
        border-radius: 12px;
        border: 1px solid rgba(255,255,255,0.12);
        background: linear-gradient(135deg, #0a2540 0%, #1e4d8b 100%);
        color: #f3eee5;
    ">
        {{-- Patrón grid sutil --}}
        <div aria-hidden="true" style="
            position: absolute; inset: 0; pointer-events: none;
            background-image:
                linear-gradient(to right, rgba(255,255,255,0.04) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255,255,255,0.04) 1px, transparent 1px);
            background-size: 32px 32px;
        "></div>

        {{-- Marca de agua SVD --}}
        <div aria-hidden="true" style="
            position: absolute; top: 50%; right: 32px; transform: translateY(-50%);
            pointer-events: none; user-select: none;
            font-family: 'Instrument Serif', Georgia, serif; font-style: italic;
            font-size: 8.5rem; line-height: 0.8;
            color: rgba(255,255,255,0.05); letter-spacing: -0.04em;
            z-index: 1;
        ">SVD<span style="color: rgba(208, 92, 51, 0.35);">.</span></div>

        {{-- Contenido principal en flex horizontal --}}
        <div style="
            position: relative; z-index: 2;
            padding: 1.5rem 2rem;
            display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between;
            gap: 1.5rem;
        ">
            {{-- LEFT: avatar + saludo --}}
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="
                    flex-shrink: 0;
                    width: 56px; height: 56px;
                    display: flex; align-items: center; justify-content: center;
                    background: rgba(255,255,255,0.10);
                    border: 1px solid rgba(255,255,255,0.20);
                    border-radius: 999px;
                    font-size: 18px; font-weight: 600; letter-spacing: 0.05em;
                    color: #f3eee5;
                ">{{ $this->getUserInitials() }}</div>

                <div>
                    <div style="
                        font-family: 'JetBrains Mono', ui-monospace, monospace;
                        font-size: 10px; letter-spacing: 0.18em; text-transform: uppercase;
                        color: rgba(243, 238, 229, 0.55); margin-bottom: 6px;
                    ">{{ $this->getRoleLabel() }} · Panel</div>

                    <div style="
                        font-size: 22px; font-weight: 600; line-height: 1.2;
                        color: #f3eee5; margin: 0;
                    ">
                        {{ $this->getGreeting() }},
                        <span style="
                            font-family: 'Instrument Serif', Georgia, serif; font-style: italic;
                            color: #d05c33; font-weight: 400;
                        ">{{ $this->getUserName() }}</span>
                    </div>

                    <div style="
                        font-size: 13px; color: rgba(243, 238, 229, 0.7);
                        margin-top: 4px;
                    ">{{ ucfirst($this->getFormattedDate()) }} · {{ $this->getFormattedTime() }}</div>
                </div>
            </div>

            {{-- RIGHT: stats + salir --}}
            <div style="display: flex; align-items: center; gap: 1.5rem;">
                <div style="display: flex; align-items: center; gap: 1.5rem;">
                    <div style="text-align: right;">
                        <div style="
                            font-family: 'Instrument Serif', Georgia, serif; font-style: italic;
                            font-size: 32px; line-height: 1; color: #f3eee5;
                        ">{{ $this->getTodayCount() }}</div>
                        <div style="
                            font-family: 'JetBrains Mono', ui-monospace, monospace;
                            font-size: 9.5px; letter-spacing: 0.14em; text-transform: uppercase;
                            color: rgba(243, 238, 229, 0.55); margin-top: 4px;
                        ">Hoy</div>
                    </div>
                    <div style="
                        text-align: right;
                        border-left: 1px solid rgba(255,255,255,0.15);
                        padding-left: 1.25rem;
                    ">
                        <div style="
                            font-family: 'Instrument Serif', Georgia, serif; font-style: italic;
                            font-size: 32px; line-height: 1; color: #f3eee5;
                        ">{{ $this->getLastHourCount() }}</div>
                        <div style="
                            font-family: 'JetBrains Mono', ui-monospace, monospace;
                            font-size: 9.5px; letter-spacing: 0.14em; text-transform: uppercase;
                            color: rgba(243, 238, 229, 0.55); margin-top: 4px;
                        ">Última hora</div>
                    </div>
                </div>

                <form action="{{ filament()->getLogoutUrl() }}" method="POST" style="display: inline-flex; margin: 0;">
                    @csrf
                    <button type="submit"
                            style="
                                display: inline-flex; align-items: center; gap: 0.5rem;
                                padding: 0.6rem 1.1rem;
                                background: rgba(255,255,255,0.08);
                                color: #f3eee5;
                                border: 1px solid rgba(255,255,255,0.18);
                                border-radius: 6px;
                                font-size: 12.5px; font-weight: 500; letter-spacing: 0.02em;
                                font-family: 'Instrument Sans', system-ui, sans-serif;
                                cursor: pointer;
                                transition: background 0.18s ease, border-color 0.18s ease;
                            "
                            onmouseover="this.style.background='rgba(208,92,51,0.85)'; this.style.borderColor='rgba(208,92,51,1)';"
                            onmouseout="this.style.background='rgba(255,255,255,0.08)'; this.style.borderColor='rgba(255,255,255,0.18)';">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                            <polyline points="16 17 21 12 16 7"/>
                            <line x1="21" y1="12" x2="9" y2="12"/>
                        </svg>
                        Salir
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>
