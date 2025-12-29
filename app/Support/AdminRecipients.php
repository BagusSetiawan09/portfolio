<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Support\Collection;

class AdminRecipients
{
    public function emails(): array
    {
        return config('admin.dashboard_emails', []);
    }

    public function users(): Collection
    {
        $emails = $this->emails();

        if (empty($emails)) {
            return collect();
        }

        return User::query()
            ->whereIn('email', $emails)
            ->get();
    }
}
