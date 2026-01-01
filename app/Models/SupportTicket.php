<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;

class SupportTicket extends Model
{
    protected $fillable = [
        'client_id',
        'client_name',
        'client_email',
        'client_whatsapp',
        'subject',
        'description',
        'category',
        'priority',
        'status',
        'website_url',
        'attachments',
        'assigned_to',
        'resolved_at',
    ];

    protected $casts = [
        'attachments' => 'array',
        'resolved_at' => 'datetime',
    ];

    public function client()
    {
        // kalau kamu punya App\Models\Client
        return class_exists(\App\Models\Client::class)
            ? $this->belongsTo(\App\Models\Client::class)
            : null;
    }

    public function assignee()
    {
        return $this->belongsTo(\App\Models\User::class, 'assigned_to');
    }

    public function getClientDisplayNameAttribute(): string
    {
        if (!empty($this->client_name)) return $this->client_name;

        if (method_exists($this, 'client') && $this->client && isset($this->client->name)) {
            return $this->client->name;
        }

        return '-';
    }
}
