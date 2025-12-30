<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;
use App\Models\User;

class Order extends Model
{
    protected $guarded = [];

    protected $fillable = [
        'type', 'name', 'email', 'whatsapp', 'service',
        'topic', 'message', 'budget_range', 'deadline',
        'preferred_channel', 'preferred_time', 'status',
        'ip_address', 'country', 'city', 'lat', 'lng',
    ];

    protected $casts = [
        'deadline' => 'date',
        'lat' => 'float',
        'lng' => 'float',
    ];

    protected static function booted()
    {
        // 1. EVENT: SAAT ORDER SUDAH DISIMPAN (CREATED)
        static::created(function (Order $order) {
            
            // --- A. Notifikasi Dashboard Filament ---
            $recipients = User::where('email', 'bagussetiawan.lz24@gmail.com')->get();

            Notification::make()
                ->title('Order Baru Masuk')
                ->body("{$order->name} - {$order->service}")
                ->success()
                ->actions([
            Action::make('view')
                ->label('Lihat Detail')
                ->button()
                ->url(fn () => route('filament.admin.resources.orders.view', $order))
                ->markAsRead(),
            ])
            ->sendToDatabase($recipients);

            // --- B. Notifikasi Telegram ---
            $token = env('TELEGRAM_BOT_TOKEN');
            $chatId = env('TELEGRAM_CHAT_ID');

            if ($token && $chatId) {
                
                // 1. Format Pesan
                $message = "<b>NEW ORDER SIR</b>\n\n" .
                           "<b>CLIENT :</b> {$order->name}\n" .
                           "<b>Email :</b> {$order->email}\n" .
                           "<b>WhatsApp :</b> {$order->whatsapp}\n\n" .
                           "<b>SERVICE :</b> {$order->service}\n" .
                           "<b>BUDGET :</b> {$order->budget_range}\n\n" .
                           "Cek pesanan sekarang";

                // 2. Siapkan Link untuk Tombol
                $adminUrl = route('filament.admin.resources.orders.edit', $order);
                
                // Siapkan Link WhatsApp
                $waNumber = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $order->whatsapp));

                $waText = "Halo Kak *{$order->name}*, terima kasih telah memesan layanan *{$order->service}* di Codexly.\n\n" .
                          "Saya ingin konfirmasi mengenai detail project dan budget yang diajukan. Apakah ada waktu luang untuk diskusi sebentar?";

                $waUrl = "https://wa.me/{$waNumber}?text=" . urlencode($waText);

                // 3. Membuat Inline Keyboard (Tombol)
                $keyboard = [
                    'inline_keyboard' => [
                        [
                            ['text' => 'Dashboard', 'url' => $adminUrl],
                            ['text' => 'Contact Client', 'url' => $waUrl]
                        ]
                    ]
                ];

                try {
                    Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
                        'chat_id' => $chatId,
                        'text' => $message,
                        'parse_mode' => 'HTML', // HTML untuk format tebal
                        'reply_markup' => json_encode($keyboard) // Tombol
                    ]);
                } catch (\Exception $e) {
                    // Silent fail
                }
            }
        });

        // 2. EVENT: SAAT ORDER SEDANG DIBUAT (CREATING) - AUTO LOCATION
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
            } catch (\Exception $e) {}
        });
    }
}