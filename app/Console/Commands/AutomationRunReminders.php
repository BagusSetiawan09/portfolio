<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Automation\ReminderService;

class AutomationRunReminders extends Command
{
    protected $signature = 'automation:reminders {--dry-run : Do not actually send, just log}';
    protected $description = 'Run automation reminders & follow ups';

    public function handle(ReminderService $service): int
    {
        $dry = (bool) $this->option('dry-run');

        $result = $service->run($dry);

        $this->info("sent={$result['sent']} skipped={$result['skipped']} errors={$result['errors']}");

        foreach ($result['messages'] as $m) {
            $this->line($m);
        }

        return self::SUCCESS;
    }
}
