<x-filament-panels::page>
    <form>
        {{ $this->form }}
    </form>

    <div class="mt-6 rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <div class="text-sm text-gray-500 dark:text-gray-400">Resultados del filtro</div>
                <div class="mt-1 text-2xl font-bold text-gray-900 dark:text-gray-50">
                    {{ number_format($previewCount ?? 0) }}
                    <span class="text-sm font-normal text-gray-500">remisiones</span>
                </div>
                <div class="mt-1 text-lg font-semibold text-emerald-600">
                    ${{ number_format($previewTotal ?? 0, 0, ',', '.') }} COP
                    <span class="text-xs font-normal text-gray-500">en ventas</span>
                </div>
            </div>

            <x-filament::button
                wire:click="downloadXlsx"
                icon="heroicon-o-arrow-down-tray"
                size="lg"
            >
                Descargar Excel
            </x-filament::button>
        </div>
    </div>
</x-filament-panels::page>
