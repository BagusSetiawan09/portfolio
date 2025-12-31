<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_available' => 'boolean',
        'footer_links' => 'array',
        'marquee_texts' => 'array',
        'banner_row_1' => 'array',
        'banner_row_2' => 'array',
    ];
}