<?php

namespace App\Jobs;

use App\Services\WhatsAppCloud;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class SendWhatsAppText implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public string $to,
        public string $message,
    ) {}

    public function handle(WhatsAppCloud $wa): void
    {
        $wa->sendText($this->to, $this->message);
    }

    public function failed(Throwable $e): void
    {
        logger()->error('WA send failed', [
            'to' => $this->to,
            'error' => $e->getMessage(),
        ]);
    }
}
