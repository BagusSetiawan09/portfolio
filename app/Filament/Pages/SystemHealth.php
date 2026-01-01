<?php

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\Client\Response;

class SystemHealth extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-heart';
    protected static ?string $navigationLabel = 'System Health';
    protected static ?string $title = 'System Health / Stabilizer';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?int $navigationSort = 999;

    protected static string $view = 'filament.pages.system-health';

    public array $results = [];
    public array $envSummary = [];

    public function mount(): void
    {
        // Summary info (tanpa password)
        $this->envSummary = [
            'APP_ENV' => config('app.env'),
            'APP_DEBUG' => config('app.debug') ? 'true' : 'false',
            'APP_TIMEZONE' => config('app.timezone'),
            'MAIL_MAILER' => config('mail.default'),
            'MAIL_HOST' => config('mail.mailers.smtp.host') ?? env('MAIL_HOST'),
            'MAIL_PORT' => config('mail.mailers.smtp.port') ?? env('MAIL_PORT'),
            'MAIL_ENCRYPTION' => config('mail.mailers.smtp.encryption') ?? env('MAIL_ENCRYPTION'),
            'MAIL_FROM' => config('mail.from.address'),
            'QUEUE_CONNECTION' => config('queue.default'),
            'FILESYSTEM_DISK' => config('filesystems.default'),
            'TELEGRAM_ENABLED' => (env('TELEGRAM_BOT_TOKEN') && env('TELEGRAM_CHAT_ID')) ? 'yes' : 'no',
        ];

        // Default status awal: cek config doang (tanpa ngirim apa-apa)
        $this->results = $this->checkConfigsOnly();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('runAll')
                ->label('Run All Checks')
                ->icon('heroicon-m-play')
                ->color('primary')
                ->action(function () {
                    $this->results = $this->runAllChecks(sendEmail: false, sendTelegram: false);
                    Notification::make()->title('Checks selesai')->success()->send();
                }),

            Action::make('testSmtp')
                ->label('Test SMTP (Send Email)')
                ->icon('heroicon-m-envelope')
                ->color('success')
                ->form([
                    \Filament\Forms\Components\TextInput::make('to')
                        ->label('Kirim ke email')
                        ->default(fn () => Auth::user()?->email ?? env('FILAMENT_SUPER_ADMIN_EMAIL'))
                        ->email()
                        ->required(),
                ])
                ->action(function (array $data) {
                    $res = $this->testSmtpSend($data['to']);
                    $this->results['smtp_send'] = $res;

                    $this->toastFromResult('SMTP Send', $res);
                }),

            Action::make('testTelegram')
                ->label('Test Telegram (Send)')
                ->icon('heroicon-m-paper-airplane')
                ->color('info')
                ->action(function () {
                    $res = $this->testTelegramSend();
                    $this->results['telegram_send'] = $res;

                    $this->toastFromResult('Telegram Send', $res);
                }),

            Action::make('testStorage')
                ->label('Test Storage')
                ->icon('heroicon-m-folder-open')
                ->color('warning')
                ->action(function () {
                    $res = $this->testStorageWrite();
                    $this->results['storage'] = $res;

                    $this->toastFromResult('Storage', $res);
                }),

            Action::make('testPdf')
                ->label('Test PDF Render')
                ->icon('heroicon-m-document-text')
                ->color('gray')
                ->action(function () {
                    $res = $this->testPdfRender();
                    $this->results['pdf'] = $res;

                    $this->toastFromResult('PDF Render', $res);
                }),
        ];
    }

    private function toastFromResult(string $title, array $res): void
    {
        if (($res['ok'] ?? false) === true) {
            Notification::make()
                ->title("✅ {$title}: OK")
                ->body($res['message'] ?? null)
                ->success()
                ->send();
        } else {
            Notification::make()
                ->title("❌ {$title}: FAIL")
                ->body($res['message'] ?? 'Unknown error')
                ->danger()
                ->send();
        }
    }

    private function checkConfigsOnly(): array
    {
        $smtpOk = (bool) (env('MAIL_HOST') && env('MAIL_PORT') && env('MAIL_USERNAME'));
        $telegramOk = (bool) (env('TELEGRAM_BOT_TOKEN') && env('TELEGRAM_CHAT_ID'));
        $disk = config('filesystems.default');

        return [
            'smtp_config' => [
                'ok' => $smtpOk,
                'message' => $smtpOk ? 'MAIL_* terlihat terisi.' : 'MAIL_HOST/PORT/USERNAME kosong atau belum kebaca.',
            ],
            'telegram_config' => [
                'ok' => $telegramOk,
                'message' => $telegramOk ? 'Telegram token & chat_id ada.' : 'TELEGRAM_BOT_TOKEN / TELEGRAM_CHAT_ID kosong.',
            ],
            'storage_config' => [
                'ok' => !empty($disk),
                'message' => "Default disk: {$disk}",
            ],
            'pdf_config' => [
                'ok' => view()->exists('health.pdf-test'),
                'message' => view()->exists('health.pdf-test')
                    ? 'Blade health.pdf-test tersedia.'
                    : 'View health.pdf-test belum ada (kita buat di step berikut).',
            ],
        ];
    }

    private function runAllChecks(bool $sendEmail, bool $sendTelegram): array
    {
        $out = $this->checkConfigsOnly();

        // Storage write test
        $out['storage'] = $this->testStorageWrite();

        // PDF render test
        $out['pdf'] = $this->testPdfRender();

        // Optional sending
        if ($sendEmail) {
            $out['smtp_send'] = $this->testSmtpSend(Auth::user()?->email ?? env('FILAMENT_SUPER_ADMIN_EMAIL'));
        }

        if ($sendTelegram) {
            $out['telegram_send'] = $this->testTelegramSend();
        }

        return $out;
    }

    private function testSmtpSend(string $to): array
    {
        try {
            Mail::raw("✅ SMTP test dari System Health\nTime: " . now()->toDateTimeString(), function ($m) use ($to) {
                $m->to($to)->subject('SMTP Test - System Health');
            });

            return ['ok' => true, 'message' => "Email test terkirim ke {$to}."];
        } catch (\Throwable $e) {
            return ['ok' => false, 'message' => $e->getMessage()];
        }
    }

    private function testTelegramSend(): array
    {
        try {
            $token = env('TELEGRAM_BOT_TOKEN');
            $chatId = env('TELEGRAM_CHAT_ID');

            if (!$token || !$chatId) {
                return ['ok' => false, 'message' => 'TELEGRAM_BOT_TOKEN/CHAT_ID belum di-set.'];
            }

            $text = "✅ Telegram test dari System Health\nTime: " . now()->toDateTimeString();

            /** @var Response $resp */
            $resp = Http::timeout(8)->post("https://api.telegram.org/bot{$token}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $text,
            ]);

            if (!$resp->successful()) {
                return ['ok' => false, 'message' => 'HTTP ' . $resp->status() . ' - ' . Str::limit($resp->body(), 200)];
            }

            return ['ok' => true, 'message' => 'Telegram test terkirim.'];
        } catch (\Throwable $e) {
            return ['ok' => false, 'message' => $e->getMessage()];
        }
    }

    private function testStorageWrite(): array
    {
        try {
            $disk = Storage::disk(config('filesystems.default'));
            $path = 'healthchecks/health_' . now()->format('Ymd_His') . '.txt';

            $disk->put($path, 'ok ' . now()->toDateTimeString());
            $exists = $disk->exists($path);

            // cleanup
            $disk->delete($path);

            if (!$exists) {
                return ['ok' => false, 'message' => "File gagal dibuat di disk: " . config('filesystems.default')];
            }

            return ['ok' => true, 'message' => "Write/delete OK di disk: " . config('filesystems.default')];
        } catch (\Throwable $e) {
            return ['ok' => false, 'message' => $e->getMessage()];
        }
    }

    private function testPdfRender(): array
    {
        try {
            if (!view()->exists('health.pdf-test')) {
                return ['ok' => false, 'message' => 'View health.pdf-test tidak ditemukan.'];
            }

            // Test render view
            $html = view('health.pdf-test', ['time' => now()->toDateTimeString()])->render();
            if (strlen($html) < 20) {
                return ['ok' => false, 'message' => 'Render view terlalu pendek / gagal.'];
            }

            // OPTIONAL: kalau ada DomPDF, test generate PDF beneran
            if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html)->output();
                if (strlen($pdf) < 1000) {
                    return ['ok' => false, 'message' => 'DomPDF ada, tapi output PDF terlalu kecil (mungkin gagal).'];
                }
                return ['ok' => true, 'message' => 'Render view OK + DomPDF generate OK.'];
            }

            return ['ok' => true, 'message' => 'Render view OK (DomPDF tidak terdeteksi, skip generate).'];
        } catch (\Throwable $e) {
            return ['ok' => false, 'message' => $e->getMessage()];
        }
    }

    // Optional: batasi akses hanya admin tertentu
    public static function canAccess(): bool
    {
        $user = Auth::user();
        if (!$user) return false;

        // boleh kamu ganti sesuai kebutuhan:
        $super = env('FILAMENT_SUPER_ADMIN_EMAIL');

        return $super ? ($user->email === $super) : true;
    }
}
