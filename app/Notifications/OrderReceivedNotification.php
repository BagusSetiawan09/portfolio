<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class OrderReceivedNotification extends Notification
{
    use Queueable;

    public function __construct(public Order $order) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $o = $this->order;

        $adminUrl = url('/admin'); // dashboard Filament kamu
        $type = $o->type ?? 'order';

        return (new MailMessage)
            ->subject('Order Masuk — ' . ($o->name ?? 'Client'))
            ->greeting('Ada order baru masuk!')
            ->line('**Tipe:** ' . strtoupper($type))
            ->line('**Nama:** ' . ($o->name ?? '-'))
            ->line('**Email:** ' . ($o->email ?? '-'))
            ->line('**WhatsApp:** ' . ($o->whatsapp ?? $o->phone ?? '-'))
            ->line('**Service:** ' . ($o->service ?? '-'))
            ->line('**Topic:** ' . ($o->topic ?? '-'))
            ->line('**Budget:** ' . ($o->budget_range ?? $o->budget ?? '-'))
            ->line('**Message:**')
            ->line($o->message ?? '-')
            ->action('Buka Dashboard', $adminUrl)
            ->line('Status awal: NEW');
    }
}
