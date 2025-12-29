<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;

class WhatsAppCloud
{
    public function sendText(string $to, string $message): void
    {
        if (! (bool) config('services.whatsapp.enabled')) {
            return;
        }

        $token   = config('services.whatsapp.cloud.token');
        $phoneId = config('services.whatsapp.cloud.phone_number_id');
        $version = config('services.whatsapp.cloud.version', 'v21.0');

        if (! $token || ! $phoneId) {
            return;
        }

        $to = preg_replace('/\D+/', '', $to);
        if ($to === '') {
            return;
        }

        $url = "https://graph.facebook.com/{$version}/{$phoneId}/messages";

        /** @var Response $response */
        $response = Http::withToken($token)
            ->acceptJson()
            ->timeout(15)
            ->retry(3, 250)
            ->post($url, [
                'messaging_product' => 'whatsapp',
                'to' => $to,
                'type' => 'text',
                'text' => [
                    'preview_url' => true,
                    'body' => $message,
                ],
            ]);

        $response->throw();
    }
}
