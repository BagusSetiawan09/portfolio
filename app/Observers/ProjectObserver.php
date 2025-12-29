<?php

namespace App\Observers;

use App\Actions\AdminNotifier;
use App\Models\Project;

class ProjectObserver
{
    public function updated(Project $project): void
    {
        // notif hanya ketika status berubah dari draft -> published
        if ($project->wasChanged('is_published') && $project->is_published) {
            $title = "Project Published: " . ($project->title ?? 'Project');
            $body  = "Project berhasil dipublish.\n"
                   . "Year: " . ($project->year ?? '-') . "\n"
                   . "Slug: " . ($project->slug ?? '-');

            $url = null;
            // $url = route('filament.admin.resources.projects.edit', ['record' => $project]);

            app(AdminNotifier::class)->send($title, $body, $url, 'heroicon-o-briefcase');
        }
    }
}
