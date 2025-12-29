<?php

namespace App\Notifications;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ProjectPublishedNotification extends Notification
{
    use Queueable;

    public function __construct(public Project $project) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $p = $this->project;

        $adminUrl = url('/admin');

        return (new MailMessage)
            ->subject('Project Dipublish — ' . ($p->title ?? 'Project'))
            ->greeting('Project baru dipublish ✅')
            ->line('**Judul:** ' . ($p->title ?? '-'))
            ->line('**Year:** ' . ($p->year ?? '-'))
            ->line('**Link:** ' . ($p->link_url ?? '-'))
            ->action('Buka Dashboard', $adminUrl);
    }
}
