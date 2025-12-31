<x-filament-panels::page>
    <x-filament-panels::form wire:submit="save">
        
        {{-- Tampilkan Form --}}
        {{ $this->form }}

        {{-- Tampilkan Tombol (Save & Reset) Otomatis dari PHP --}}
        <x-filament-panels::form.actions 
            :actions="$this->getFormActions()" 
        />
        
    </x-filament-panels::form>
</x-filament-panels::page>