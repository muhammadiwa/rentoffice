<div>
    <div class="p-4 bg-white rounded-lg shadow">
        <div class="mb-4">
            <h3 class="text-lg font-medium">WhatsApp QR Scanner</h3>
            <p class="text-sm text-gray-500">Scan this QR code with WhatsApp to connect</p>
        </div>
        
        <div id="qr-code-container" class="flex justify-center">
            <div id="qr-code">
                @if($qrCode)
                    {!! $qrCode !!}
                @endif
            </div>
        </div>
        
        <div class="mt-4">
            <button wire:click="generateQrCode" type="button" class="px-4 py-2 bg-primary-600 text-white rounded-lg">
                Refresh QR Code
            </button>
        </div>
    </div>
</div>
