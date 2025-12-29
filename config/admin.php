<?php

return [
    'notify_emails' => array_values(array_filter(array_map('trim', explode(',', env('ADMIN_NOTIFY_EMAILS', ''))))),

    // email user yang login ke Filament (untuk notifikasi panel)
    'dashboard_emails' => array_values(array_filter(array_map('trim', explode(',', env('ADMIN_DASHBOARD_EMAILS', ''))))),
];
