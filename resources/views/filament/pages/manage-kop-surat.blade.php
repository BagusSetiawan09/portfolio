<x-filament-panels::page>
    <form wire:submit.prevent="submit">
        {{ $this->form }}

        <div class="mt-6 p-6 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm">
            <label class="block text-sm font-medium mb-4 text-gray-700 dark:text-gray-300">
                Tanda Tangan Digital (Coret di bawah ini)
            </label>
            
            <div wire:ignore id="signature-container" class="relative w-full overflow-hidden" style="border: 2px dashed #9ca3af; background: white; border-radius: 8px; height: 250px;">
                <canvas id="signature-pad-canvas" 
                    style="position: absolute; left: 0; top: 0; width: 100%; height: 100%; cursor: crosshair; touch-action: none;"></canvas>
            </div>

            <div class="mt-6 flex items-center gap-4 ml-1">
                <button 
                    type="button" 
                    id="btn-clear-signature"
                    class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-bold text-white transition-all bg-red-600 rounded-lg shadow-md hover:bg-red-500 active:scale-95 focus:outline-none"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Hapus Coretan
                </button>
                
                <p class="text-xs text-gray-500 italic dark:text-gray-400">
                    *Tanda tangan tersimpan otomatis saat Anda mencoret.
                </p>
            </div>
        </div>

        <div class="mt-8">
            <x-filament::button type="submit" size="lg" icon="heroicon-m-check-circle" class="px-8" onclick="sendSignatureToLivewire()">
                Simpan Semua Pengaturan
            </x-filament::button>
        </div>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
    
    <script>
        let signaturePad = null;

        function initSignaturePad() {
            const canvas = document.getElementById('signature-pad-canvas');
            if (!canvas) return;

            const rect = canvas.getBoundingClientRect();
            canvas.width = rect.width;
            canvas.height = rect.height;

            signaturePad = new SignaturePad(canvas, {
                backgroundColor: 'rgba(255, 255, 255, 0)',
                penColor: 'rgb(0, 0, 0)'
            });

            const data = @this.get('company_signature');
            if (data) signaturePad.fromDataURL(data);
        }

        // Fungsi yang dipanggil saat tombol simpan diklik
        window.sendSignatureToLivewire = function() {
            if (signaturePad) {
                const dataUrl = signaturePad.isEmpty() ? null : signaturePad.toDataURL();
                @this.set('company_signature', dataUrl);
            }
        }

        // Tombol Hapus tetap menggunakan delegasi event agar tidak mati
        document.addEventListener('click', function (e) {
            if (e.target.closest('#btn-clear-signature')) {
                e.preventDefault();
                if (signaturePad) {
                    signaturePad.clear();
                    @this.set('company_signature', null);
                }
            }
        });

        document.addEventListener('DOMContentLoaded', initSignaturePad);
        document.addEventListener('filament_init', initSignaturePad);
        window.addEventListener('resize', initSignaturePad);
    </script>
</x-filament-panels::page>