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
        // --- TAMBAHKAN KOLOM BARU INI AGAR BISA DISIMPAN ---
        'ip_address',
        'country',
        'city',
        'lat',
        'lng',
    ];

    protected $casts = [
        'deadline' => 'date',
        // Casting lat/lng jadi float/decimal biar aman (opsional tapi bagus)
        'lat' => 'float',
        'lng' => 'float',
    ];

    protected static function booted()
    {
        static::creating(function ($order) {
            // 1. Ambil IP User
            $ip = request()->ip();
            
            // Cek Localhost untuk testing
            if ($ip == '127.0.0.1' || $ip == '::1') {
                $ip = '103.19.111.4'; // IP Contoh (Jakarta)
            }

            $order->ip_address = $ip;

            try {
                // 2. Tembak API Gratis ip-api.com
                // timeout(2) agar loading tidak lama jika koneksi API lambat
                /** @var \Illuminate\Http\Client\Response $response */  // <--- TAMBAHKAN BARIS INI
                $response = Http::timeout(2)->get("http://ip-api.com/json/{$ip}");
                
                if ($response->successful()) {
                    $data = $response->json();
                    if (isset($data['status']) && $data['status'] === 'success') {
                        $order->country = $data['country'] ?? null;
                        $order->city = $data['city'] ?? null;
                        $order->lat = $data['lat'] ?? null;
                        $order->lng = $data['lon'] ?? null; // API pakai 'lon', DB kita pakai 'lng'
                    }
                }
            } catch (\Exception $e) {
                // Biarkan kosong jika error, supaya order tetap masuk
            }
        });
    }
}