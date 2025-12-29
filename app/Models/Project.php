<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ProjectPublishedNotification;

class Project extends Model
{
    protected $fillable = [
        'title','slug','image_url','link_url','year','tags','excerpt',
        'is_published','sort_order',
        'show_in_latest','show_in_portfolio',
    ];

    protected $casts = [
        'tags' => 'array',
        'is_published' => 'boolean',
        'show_in_latest' => 'boolean',
        'show_in_portfolio' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function ($model) {
        if (blank($model->slug) && filled($model->title)) {
            $model->slug = Str::slug($model->title);
        }
    });

    static::saved(function ($model) {
        // kirim notif hanya saat berubah menjadi published
        if ($model->wasChanged('is_published') && $model->is_published) {
            $adminEmail = config('app.admin_notify_email');
            if ($adminEmail) {
                Notification::route('mail', $adminEmail)
                    ->notify(new ProjectPublishedNotification($model));
            }
        }
    });
    }

    public function scopePublished($q)
    {
        return $q->where('is_published', true);
    }

    public function scopeForLatest($q)
    {
        return $q->where('show_in_latest', true);
    }

    public function scopeForPortfolio($q)
    {
        return $q->where('show_in_portfolio', true);
    }
}
