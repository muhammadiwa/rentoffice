<div>
    <div class="p-4 bg-white rounded-lg shadow">
        <div class="mb-4">
            <h3 class="text-lg font-medium">WhatsApp QR Scanner</h3>
            <p class="text-sm text-gray-500">Scan this QR code with WhatsApp to connect</p>
        </div>
        
        <div id="qr-code-container" class="flex justify-center">
            <div id="qr-code"></div>
        </div>
        
        <div class="mt-4">
            <x-filament::button
                wire:click="generateQrCode"
                type="button"
            >
                Refresh QR Code
            </x-filament::button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    window.addEventListener('qr-code-generated', event => {
        document.getElementById('qr-code').innerHTML = event.detail.qrCode;
    });
</script>
@endpush
