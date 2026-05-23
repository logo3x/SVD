<x-filament-panels::page>
    {{-- Stat cards rápidas --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-900">
            <div class="text-sm text-gray-500 dark:text-gray-400">Dispositivos activos (últimos 7 días)</div>
            <div class="mt-1 text-3xl font-bold text-gray-900 dark:text-gray-50">
                {{ number_format($activeDevices ?? 0) }}
            </div>
        </div>
        <div class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-900">
            <div class="text-sm text-gray-500 dark:text-gray-400">Vendedores con app instalada</div>
            <div class="mt-1 text-3xl font-bold text-gray-900 dark:text-gray-50">
                {{ number_format($totalUsers ?? 0) }}
            </div>
        </div>
    </div>

    <form wire:submit="save">
        {{ $this->form }}

        <div class="mt-6 flex justify-end">
            <x-filament::button type="submit">
                Guardar cambios
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
