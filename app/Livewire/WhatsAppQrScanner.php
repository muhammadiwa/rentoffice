<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\WhatsAppService;

class WhatsAppQrScanner extends Component
{
    public $qrCode;
    protected $whatsappService;

    public function mount(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    public function generateQrCode()
    {
        $this->qrCode = $this->whatsappService->generateQrCode();
        $this->dispatch('qr-code-generated', ['qrCode' => $this->qrCode]);
    }
    public function render()
    {
        return view('livewire.whats-app-qr-scanner');
    }
}
