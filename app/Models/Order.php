<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'type',
        'name',
        'email',
        'whatsapp',
        'service',
        'topic',
        'message',
        'budget_range',
        'deadline',
        'preferred_channel',
        'preferred_time',
        'status',
    ];

    protected $casts = [
        'deadline' => 'date',
    ];
}
