@php
    $statePath = $getStatePath();
@endphp

<div
    x-data="signaturePadField({
        state: $wire.$entangle(@js($statePath), false),
    })"
    x-init="init"
    wire:ignore
    class="svd-signature-shell"
>
    <div class="svd-signature-toolbar">
        <span class="svd-signature-hint" x-text="isEmpty ? 'Firma aquí con el dedo o el mouse' : 'Firma capturada'"></span>
        <div class="svd-signature-actions">
            <button type="button" @click="undo" class="svd-sig-btn" :disabled="isEmpty">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7v6h6"/><path d="M21 17a9 9 0 0 0-9-9 9 9 0 0 0-6 2.3L3 13"/></svg>
                Deshacer
            </button>
            <button type="button" @click="clear" class="svd-sig-btn svd-sig-btn-danger" :disabled="isEmpty">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-2 14a2 2 0 0 1-2 2H9a2 2 0 0 1-2-2L5 6"/></svg>
                Limpiar
            </button>
        </div>
    </div>

    <div class="svd-signature-canvas-wrap">
        <canvas x-ref="canvas" class="svd-signature-canvas"></canvas>
        <div class="svd-signature-baseline" aria-hidden="true">X _______________________________</div>
    </div>

    <p class="svd-signature-help">
        El trazo se guarda como imagen PNG al confirmar la remisión.
        Pestaña "Subir imagen" si prefieres adjuntar un archivo existente.
    </p>
</div>

@once
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.2.0/dist/signature_pad.umd.min.js" defer></script>
        <script>
            window.signaturePadField = function ({ state }) {
                return {
                    pad: null,
                    state,
                    isEmpty: true,

                    init() {
                        const start = () => {
                            if (typeof SignaturePad === 'undefined') {
                                setTimeout(start, 50);
                                return;
                            }

                            const canvas = this.$refs.canvas;
                            this.resize(canvas);
                            this.pad = new SignaturePad(canvas, {
                                penColor: '#0a2540',
                                backgroundColor: 'rgba(255,255,255,0)',
                                minWidth: 0.6,
                                maxWidth: 2.4,
                            });

                            // Si ya hay estado previo (edición), restaurarlo.
                            if (this.state) {
                                this.pad.fromDataURL(this.state);
                                this.isEmpty = false;
                            }

                            this.pad.addEventListener('endStroke', () => {
                                this.isEmpty = this.pad.isEmpty();
                                this.state = this.isEmpty ? null : this.pad.toDataURL('image/png');
                            });

                            window.addEventListener('resize', () => this.resize(canvas));
                        };
                        start();
                    },

                    resize(canvas) {
                        const ratio = Math.max(window.devicePixelRatio || 1, 1);
                        const rect = canvas.getBoundingClientRect();
                        canvas.width = rect.width * ratio;
                        canvas.height = rect.height * ratio;
                        canvas.getContext('2d').scale(ratio, ratio);
                        if (this.pad) this.pad.clear();
                    },

                    clear() {
                        if (this.pad) this.pad.clear();
                        this.isEmpty = true;
                        this.state = null;
                    },

                    undo() {
                        if (! this.pad) return;
                        const data = this.pad.toData();
                        if (data && data.length > 0) {
                            data.pop();
                            this.pad.fromData(data);
                            this.isEmpty = this.pad.isEmpty();
                            this.state = this.isEmpty ? null : this.pad.toDataURL('image/png');
                        }
                    },
                };
            };
        </script>
        <style>
            .svd-signature-shell { display: flex; flex-direction: column; gap: 0.5rem; }
            .svd-signature-toolbar {
                display: flex; align-items: center; justify-content: space-between;
                padding: 0.5rem 0.75rem;
                background: rgb(249 250 251);
                border: 1px solid rgb(229 231 235);
                border-bottom: 0;
                border-radius: 8px 8px 0 0;
            }
            .dark .svd-signature-toolbar { background: rgb(31 41 55); border-color: rgb(55 65 81); }
            .svd-signature-hint { font-size: 12px; color: rgb(107 114 128); font-weight: 500; }
            .svd-signature-actions { display: flex; gap: 0.5rem; }
            .svd-sig-btn {
                display: inline-flex; align-items: center; gap: 0.375rem;
                padding: 0.375rem 0.75rem; font-size: 11.5px; font-weight: 500;
                border: 1px solid rgb(209 213 219); background: white; color: rgb(55 65 81);
                border-radius: 6px; cursor: pointer; transition: all 0.15s;
            }
            .svd-sig-btn:hover:not(:disabled) { border-color: rgb(107 114 128); background: rgb(243 244 246); }
            .svd-sig-btn:disabled { opacity: 0.4; cursor: not-allowed; }
            .svd-sig-btn-danger { color: rgb(185 28 28); border-color: rgb(254 202 202); }
            .svd-sig-btn-danger:hover:not(:disabled) { background: rgb(254 242 242); border-color: rgb(220 38 38); }
            .dark .svd-sig-btn { background: rgb(17 24 39); border-color: rgb(55 65 81); color: rgb(229 231 235); }

            .svd-signature-canvas-wrap {
                position: relative;
                background: white;
                border: 1px solid rgb(229 231 235);
                border-radius: 0 0 8px 8px;
                aspect-ratio: 3 / 1;
                min-height: 160px;
                overflow: hidden;
                touch-action: none;
            }
            .dark .svd-signature-canvas-wrap { background: rgb(31 41 55); border-color: rgb(55 65 81); }
            .svd-signature-canvas { width: 100%; height: 100%; cursor: crosshair; touch-action: none; display: block; }
            .svd-signature-baseline {
                position: absolute; bottom: 12px; left: 50%; transform: translateX(-50%);
                color: rgb(209 213 219); font-family: monospace; font-size: 14px;
                letter-spacing: 0.05em; pointer-events: none; user-select: none;
                white-space: nowrap;
            }
            .dark .svd-signature-baseline { color: rgb(75 85 99); }
            .svd-signature-help { font-size: 11.5px; color: rgb(107 114 128); margin: 0.25rem 0 0; line-height: 1.5; }
        </style>
    @endpush
@endonce
