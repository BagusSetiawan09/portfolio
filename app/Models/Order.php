<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

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
        'ip_address',
        'country',
        'city',
        'lat',
        'lng',
    ];

    protected $casts = [
        'deadline' => 'date',
        'lat' => 'float',
        'lng' => 'float',
    ];

    protected static function booted()
    {
        static::creating(function ($order) {
            $ip = request()->ip();
            
            if ($ip == '127.0.0.1' || $ip == '::1') {
                $ip = '103.19.111.4';
            }

            $order->ip_address = $ip;

            try {
                /** @var \Illuminate\Http\Client\Response $response */
                $response = Http::timeout(2)->get("http://ip-api.com/json/{$ip}");
                
                if ($response->successful()) {
                    $data = $response->json();
                    if (isset($data['status']) && $data['status'] === 'success') {
                        $order->country = $data['country'] ?? null;
                        $order->city = $data['city'] ?? null;
                        $order->lat = $data['lat'] ?? null;
                        $order->lng = $data['lon'] ?? null;
                    }
                }
            } catch (\Exception $e) {
            }
        });
    }
}