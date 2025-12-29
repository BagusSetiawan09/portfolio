<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin', // ✅ penting
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_admin' => 'boolean', // ✅ penting
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        // ✅ aman untuk production
        // return (bool) $this->is_admin;

        $superAdminEmail = env('FILAMENT_SUPER_ADMIN_EMAIL');

        return (bool) $this->is_admin
            || ($superAdminEmail && $this->email === $superAdminEmail);
    }
}
