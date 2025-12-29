<?php

namespace App\Actions;

use App\Notifications\AdminAlertNotification;
use App\Support\AdminRecipients;

class AdminNotifier
{
    public function __construct(
        protected AdminRecipients $recipients
    ) {}

    public function send(string $title, string $body, ?string $url = null, ?string $icon = null): void
    {
        $users = $this->recipients->users();

        if ($users->isEmpty()) {
            return; // kalau penerima belum diset, diam-diam skip
        }

        foreach ($users as $user) {
            $user->notify(new AdminAlertNotification($title, $body, $url, $icon));
        }
    }
}
