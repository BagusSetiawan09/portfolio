<?php

namespace App\Services\Automation;

use App\Models\NotificationLog;
use App\Models\Order;
use App\Models\Contract;
use App\Models\Proposal;
use App\Models\Bast;
use App\Models\Letter;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class ReminderService
{
    public function run(bool $dryRun = false): array
    {
        $results = [
            'sent' => 0,
            'skipped' => 0,
            'errors' => 0,
            'messages' => [],
        ];

        // 1) ORDER reminders (paling penting karena order masuk tiap hari)
        $this->handleOrders($dryRun, $results);

        // 2) CONTRACT follow-up (sent tapi belum signed)
        $this->handleContracts($dryRun, $results);

        // 3) PROPOSAL follow-up (sent tapi masih pending)
        $this->handleProposals($dryRun, $results);

        // 4) BAST follow-up (sent tapi belum signed)
        $this->handleBasts($dryRun, $results);

        // 5) LETTER follow-up (khusus type tagihan, kalau mau)
        $this->handleLetters($dryRun, $results);

        return $results;
    }

    private function handleOrders(bool $dryRun, array &$results): void
    {
        $now = now();

        $orders = Order::query()
            ->whereIn('status', ['new', 'in_progress'])
            ->latest()
            ->get();

        foreach ($orders as $order) {
            // Rule A: reminder 15 menit setelah created jika masih new
            if ($order->status === 'new' && $order->created_at && $order->created_at->diffInMinutes($now) >= 3) {
                $this->sendOrderTelegramOnce($order, 'order_reminder_15m', $dryRun, $results);
            }

            // Rule B: reminder 2 jam setelah created jika masih new
            if ($order->status === 'new' && $order->created_at && $order->created_at->diffInMinutes($now) >= 120) {
                $this->sendOrderTelegramOnce($order, 'order_reminder_2h', $dryRun, $results);
            }

            // Rule C: reminder harian jam 09:00 WIB kalau masih new/in_progress
            if ((int) $now->format('H') === 9) {
                $kind = 'order_daily_' . $now->format('Ymd');

                // supaya gak “spam”, daily reminder hanya buat order yang umurnya >= 1 hari
                if ($order->created_at && $order->created_at->diffInHours($now) >= 24) {
                    $this->sendOrderTelegramOnce($order, $kind, $dryRun, $results);
                }
            }

            // Rule D (opsional): kalau ada deadline H-1 dan belum done
            if ($order->deadline && $order->deadline->isSameDay($now->copy()->addDay())) {
                $this->sendOrderTelegramOnce($order, 'order_deadline_h1', $dryRun, $results);
            }
        }
    }

    private function handleContracts(bool $dryRun, array &$results): void
    {
        $now = now();

        $contracts = Contract::query()
            ->where('status', 'sent')
            ->whereNull('signed_at')
            ->get();

        foreach ($contracts as $contract) {
            $base = $contract->sent_at ?: $contract->updated_at;
            if (!$base) continue;

            if (Carbon::parse($base)->diffInDays($now) >= 3) {
                $this->sendTelegramOnce(
                    $contract,
                    'contract_followup_day3',
                    $this->buildSimpleTelegram(
                        "FOLLOW UP CONTRACT (H+3)\n\n".
                        "Client: {$contract->client_name}\n".
                        "Email: {$contract->client_email}\n".
                        "Number: {$contract->number}\n".
                        "Status: {$contract->status}\n\n".
                        "Tolong follow up ya."
                    ),
                    $dryRun,
                    $results
                );
            }
        }
    }

    private function handleProposals(bool $dryRun, array &$results): void
    {
        $now = now();

        $proposals = Proposal::query()
            ->where('status', 'pending')
            ->get();

        foreach ($proposals as $proposal) {
            $base = $proposal->sent_at ?: $proposal->updated_at;
            if (!$base) continue;

            if (Carbon::parse($base)->diffInDays($now) >= 7) {
                $this->sendTelegramOnce(
                    $proposal,
                    'proposal_followup_day7',
                    $this->buildSimpleTelegram(
                        "FOLLOW UP PROPOSAL (H+7)\n\n".
                        "Client: {$proposal->client_name}\n".
                        "Project: {$proposal->project_title}\n".
                        "Email: ".($proposal->client_email ?? '-')."\n".
                        "Status: {$proposal->status}\n\n".
                        "Tolong follow up ya."
                    ),
                    $dryRun,
                    $results
                );
            }
        }
    }

    private function handleBasts(bool $dryRun, array &$results): void
    {
        $now = now();

        $basts = Bast::query()
            ->where('status', 'sent')
            ->get();

        foreach ($basts as $bast) {
            $base = $bast->sent_at ?: $bast->updated_at;
            if (!$base) continue;

            if (Carbon::parse($base)->diffInDays($now) >= 2) {
                $this->sendTelegramOnce(
                    $bast,
                    'bast_followup_day2',
                    $this->buildSimpleTelegram(
                        "FOLLOW UP BAST (H+2)\n\n".
                        "Client: {$bast->client_name}\n".
                        "Project: {$bast->project_title}\n".
                        "Email: ".($bast->client_email ?? '-')."\n".
                        "Number: {$bast->number}\n\n".
                        "BAST sudah dikirim tapi belum beres? cek ya."
                    ),
                    $dryRun,
                    $results
                );
            }
        }
    }

    private function handleLetters(bool $dryRun, array &$results): void
    {
        $now = now();

        // contoh: follow up khusus surat tagihan 3 hari setelah sent
        $letters = Letter::query()
            ->where('type', 'tagihan')
            ->where('status', 'sent')
            ->get();

        foreach ($letters as $letter) {
            if (!$letter->sent_at) continue;

            if ($letter->sent_at->diffInDays($now) >= 3) {
                $this->sendTelegramOnce(
                    $letter,
                    'letter_tagihan_followup_day3',
                    $this->buildSimpleTelegram(
                        "FOLLOW UP TAGIHAN (H+3)\n\n".
                        "To: {$letter->recipient_name}\n".
                        "Email: {$letter->client_email}\n".
                        "Subject: {$letter->subject}\n".
                        "No: {$letter->number}\n\n".
                        "Reminder pembayaran / konfirmasi."
                    ),
                    $dryRun,
                    $results
                );
            }
        }
    }

    private function sendOrderTelegramOnce(Order $order, string $kind, bool $dryRun, array &$results): void
    {
        $adminUrl = route('filament.admin.resources.orders.edit', $order);

        $waNumber = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', (string) $order->whatsapp));
        $waText = "Halo Kak *{$order->name}*, terima kasih sudah order di Codexly.\n\nBoleh info detail kebutuhan project-nya?";
        $waUrl = $waNumber ? "https://wa.me/{$waNumber}?text=" . urlencode($waText) : null;

        $message =
            "<b>REMINDER ORDER</b>\n\n" .
            "<b>Client:</b> {$order->name}\n" .
            "<b>Status:</b> {$order->status}\n" .
            "<b>Service:</b> {$order->service}\n" .
            "<b>Email:</b> {$order->email}\n" .
            "<b>WhatsApp:</b> {$order->whatsapp}\n" .
            "<b>Preferred:</b> {$order->preferred_channel} ({$order->preferred_time})\n";

        $keyboard = [
            'inline_keyboard' => [
                array_values(array_filter([
                    ['text' => 'Dashboard', 'url' => $adminUrl],
                    $waUrl ? ['text' => 'Contact Client', 'url' => $waUrl] : null,
                ]))
            ]
        ];

        $this->sendTelegramOnce($order, $kind, [
            'text' => $message,
            'parse_mode' => 'HTML',
            'reply_markup' => json_encode($keyboard),
        ], $dryRun, $results);
    }

    private function sendTelegramOnce($model, string $kind, array $payload, bool $dryRun, array &$results): void
    {
        $token = env('TELEGRAM_BOT_TOKEN');
        $chatId = env('TELEGRAM_CHAT_ID');

        if (!$token || !$chatId) {
            $results['skipped']++;
            $results['messages'][] = "SKIP {$kind} (telegram env kosong)";
            return;
        }

        if ($this->alreadySent($model, $kind, 'telegram')) {
            $results['skipped']++;
            return;
        }

        if ($dryRun) {
            $results['sent']++;
            $results['messages'][] = "[DRY RUN] would send {$kind} to " . class_basename($model) . "#{$model->id}";
            return;
        }

        try {
            Http::post("https://api.telegram.org/bot{$token}/sendMessage", array_merge([
                'chat_id' => $chatId,
            ], $payload));

            $results['sent']++;
            $this->logSent($model, $kind, 'telegram');
        } catch (\Throwable $e) {
            $results['errors']++;
            $results['messages'][] = "ERROR {$kind}: " . $e->getMessage();
        }
    }

    private function buildSimpleTelegram(string $plainText): array
    {
        return [
            'text' => "<pre>" . e($plainText) . "</pre>",
            'parse_mode' => 'HTML',
        ];
    }

    private function alreadySent($model, string $kind, string $channel): bool
    {
        return NotificationLog::query()
            ->where('notifiable_type', get_class($model))
            ->where('notifiable_id', $model->id)
            ->where('kind', $kind)
            ->where('channel', $channel)
            ->exists();
    }

    private function logSent($model, string $kind, string $channel, array $meta = []): void
    {
        NotificationLog::create([
            'notifiable_type' => get_class($model),
            'notifiable_id' => $model->id,
            'kind' => $kind,
            'channel' => $channel,
            'sent_at' => now(),
            'meta' => $meta ?: null,
        ]);
    }
}
