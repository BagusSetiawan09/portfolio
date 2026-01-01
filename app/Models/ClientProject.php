<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientProject extends Model
{
    protected $guarded = [];

    protected $casts = [
        'hosting_expires_at' => 'date',
        'ssl_expires_at'     => 'date',
        'last_backup_at'     => 'datetime',
        'last_deploy_at'     => 'datetime',
        'is_active'          => 'boolean',

        // ✅ Password terenkripsi otomatis di DB
        'server_password'    => 'encrypted',
    ];

    // Optional relations (aman kalau class ada)
    public function client()
    {
        return $this->belongsTo(\App\Models\Client::class, 'client_id');
    }

    public function order()
    {
        return $this->belongsTo(\App\Models\Order::class, 'order_id');
    }

    // Helper untuk tampilkan nama client (relasi atau fallback)
    public function getClientDisplayNameAttribute(): string
    {
        return $this->client?->name
            ?? $this->client_name
            ?? '-';
    }
}
