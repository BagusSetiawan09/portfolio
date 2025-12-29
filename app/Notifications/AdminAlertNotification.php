<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AdminAlertNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $title,
        public string $body,
        public ?string $url = null,
        public ?string $icon = 'heroicon-o-bell',
    ) {}

    public function via(object $notifiable): array
    {
        // database = tampil di lonceng Filament
        // mail = kirim email
        return ['database', 'mail'];
    }

    public function toDatabase(object $notifiable): array
    {
        // Format data yang “aman” untuk Filament
        return [
            'title' => $this->title,
            'body'  => $this->body,
            'icon'  => $this->icon,
            'actions' => $this->url ? [
                [
                    'name' => 'Open',
                    'label' => 'Buka',
                    'url' => $this->url,
                    'openUrlInNewTab' => true,
                ],
            ] : [],
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject($this->title)
            ->line($this->body);

        if ($this->url) {
            $mail->action('Buka di Dashboard', $this->url);
        }

        return $mail;
    }
}
