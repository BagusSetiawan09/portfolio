<?php

namespace App\Observers;

use App\Actions\AdminNotifier;
use App\Models\Order;

class OrderObserver
{
    public function created(Order $order): void
    {
        $serviceName = data_get($order, 'service.title')
            ?? data_get($order, 'service')
            ?? '-';

        $title = "Order Masuk: " . (data_get($order, 'name') ?? 'Tanpa Nama');
        $body  = "Service: {$serviceName}\n"
               . "WhatsApp: " . (data_get($order, 'whatsapp') ?? '-') . "\n"
               . "Status: " . (data_get($order, 'status') ?? '-');

        $url = null;
        // kalau route filament kamu ada:
        // $url = route('filament.admin.resources.orders.edit', ['record' => $order]);

        app(AdminNotifier::class)->send($title, $body, $url, 'heroicon-o-shopping-bag');
    }
}
