<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $fillable = [
        'number',
        'order_id',
        'project_id',

        'client_name',
        'client_email',
        'client_whatsapp',

        'project_title',
        'scope',
        'price',
        'start_date',
        'end_date',
        'payment_terms',
        'notes',

        'status',
        'content',
        'signed_at',
    ];

    protected $casts = [
        'price'      => 'decimal:0',
        'start_date' => 'date',
        'end_date'   => 'date',
        'signed_at'  => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    protected static function booted(): void
    {
        static::created(function (self $contract) {
            if (blank($contract->number)) {
                $contract->updateQuietly([
                    'number' => 'CTR-' . now()->format('Ym') . '-' . str_pad((string) $contract->id, 5, '0', STR_PAD_LEFT),
                ]);
            }
        });
    }

    public function defaultTemplateHtml(): string
    {
        // ====== BRAND / KOP ======
        $brandCenter = 'GOOD CODE';

        $companyName    = 'Bagus Setiawan';
        $companyAddress = 'Isi alamat kamu di sini';
        $companyEmail   = 'bagussetiawan.lz24@gmail.com';
        $companyPhone   = '+62 895-6288-94070';
        $companyCity    = 'Indonesia';

        // ====== DATA KONTRAK ======
        $number = $this->number ?? ('CTR-' . now()->format('Ym') . '-00000');

        // Pakai start_date kalau ada, kalau tidak pakai created_at
        $contractDate = $this->start_date ?: ($this->created_at ?: now());
        $dateHuman = \Carbon\Carbon::parse($contractDate)->translatedFormat('d M Y');

        // ====== DATA KLIEN ======
        $clientName  = $this->client_name ?: 'Nama Klien';
        $clientEmail = $this->client_email ?: 'email@klien.com';
        $clientWa    = $this->client_whatsapp ?: '[No. WA Klien]';

        // ====== DATA PROJECT ======
        $projectTitle = $this->project_title
            ?: optional($this->project)->title
            ?: '[Nama Project]';

        // Scope (newline jadi <br>)
        $scopeText = trim((string) ($this->scope ?? ''));
        $scopeHtml = $scopeText !== ''
            ? nl2br(e($scopeText))
            : '<ul style="margin:8px 0 0 18px; padding:0;">
                <li>[Ruang lingkup 1]</li>
                <li>[Ruang lingkup 2]</li>
                <li>[Ruang lingkup 3]</li>
            </ul>';

        // Durasi dari start_date - end_date kalau ada
        $duration = '[Estimasi durasi pengerjaan]';
        if ($this->start_date && $this->end_date) {
            $s = \Carbon\Carbon::parse($this->start_date);
            $e = \Carbon\Carbon::parse($this->end_date);
            $duration = $s->translatedFormat('d M Y') . ' s/d ' . $e->translatedFormat('d M Y');
        }

        // ====== BIAYA ======
        $money = function ($val) {
            if ($val === null || $val === '') return null;
            if (is_numeric($val)) return 'Rp ' . number_format((float) $val, 0, ',', '.');
            return (string) $val;
        };

        $total = $money($this->price ?? null) ?? '[Isi total biaya]';
        $paymentTerms = trim((string) ($this->payment_terms ?? ''));
        $paymentHtml = $paymentTerms !== ''
            ? nl2br(e($paymentTerms))
            : '<ul style="margin:6px 0 0 18px; padding:0;">
                <li><b>DP (Down Payment):</b> 50% sebelum pengerjaan dimulai.</li>
                <li><b>Pelunasan:</b> sisa pembayaran setelah project selesai dan sebelum serah terima final.</li>
            </ul>
            <div style="margin-top:8px; color:#555;">
                Rekening/Metode pembayaran: <b>[Isi Bank / E-Wallet]</b> — a.n <b>[Nama Pemilik]</b>
            </div>';

        $html = <<<HTML
    <div style="font-family: DejaVu Sans, Arial, sans-serif; color:#111; font-size:12px; line-height:1.55;">

    <!-- HEADER ROW (KIRI: kontrak+client | KANAN: logo) -->
    <table style="width:100%; border-collapse:collapse; margin:0 0 12px 0;">
        <tr>
        <td style="vertical-align:top; padding-right:16px;">
            <div style="font-weight:900; font-size:18px; letter-spacing:.2px;">SURAT KONTRAK</div>

            <div style="margin-top:6px; color:#444;">
            <div style="margin-top:2px;"><span style="color:#777;">No:</span> <b>{$number}</b></div>
            <div style="margin-top:2px;"><span style="color:#777;">Tanggal:</span> {$dateHuman}</div>
            <div style="margin-top:2px;"><span style="color:#777;">Kota:</span> {$companyCity}</div>
            </div>

            <div style="margin-top:10px; border:1px solid #e6e6e6; border-radius:10px; padding:10px 12px; width:360px;">
            <div style="font-weight:800; margin-bottom:4px;">Client: <span style="font-weight:700;">{$clientName}</span></div>
            <div style="color:#555; margin-top:2px;">Email: {$clientEmail}</div>
            <div style="color:#555; margin-top:2px;">WhatsApp: {$clientWa}</div>
            </div>
        </td>

        <td style="width:260px; vertical-align:top; text-align:right;">
            <div style="font-weight:900; font-size:46px; line-height:0.95; letter-spacing:1px;">
            <span style="color:#111;">GOOD</span><br>
            <span style="color:#B7FF00;">CODE</span>
            </div>
        </td>
        </tr>
    </table>

    <div style="height:1px; background:#e5e5e5; margin:8px 0 18px;"></div>

    <!-- CENTER BRAND BLOCK -->
    <div style="text-align:center; margin:0 0 10px 0;">
        <div style="font-weight:900; font-size:30px; letter-spacing:.6px;">{$brandCenter}</div>
        <div style="margin-top:8px; font-size:13px; color:#222;">{$companyAddress}</div>
        <div style="margin-top:8px; font-size:12px; color:#444;">
        Email : {$companyEmail} | Phone : {$companyPhone}
        </div>
    </div>

    <div style="height:1px; background:#e5e5e5; margin:14px 0 18px;"></div>

    <!-- JUDUL DOKUMEN -->
    <div style="text-align:center; margin-bottom:14px;">
        <div style="font-weight:900; font-size:18px; letter-spacing:0.4px;">SURAT PERJANJIAN KERJA SAMA</div>
        <div style="margin-top:4px; font-size:13px; color:#222;">Pembuatan / Pengembangan Project</div>
        <div style="margin-top:8px; font-size:11px; color:#666;">Dokumen ini dibuat secara elektronik.</div>
    </div>

    <!-- PEMBUKA -->
    <div style="margin-bottom:10px;">
        Pada hari ini, <b>{$dateHuman}</b>, telah dibuat dan disepakati perjanjian kerja sama (“Perjanjian”) antara:
    </div>

    <!-- PARA PIHAK -->
    <table style="width:100%; border-collapse:collapse; margin:10px 0 14px;">
        <tr>
        <td style="width:50%; padding:10px 12px; border:1px solid #e2e2e2; border-radius:10px; vertical-align:top;">
            <div style="font-weight:800; margin-bottom:6px;">PIHAK PERTAMA</div>
            <div style="font-weight:700;">{$companyName}</div>
            <div style="color:#444; margin-top:6px;">{$companyAddress}</div>
            <div style="color:#444; margin-top:4px;">{$companyEmail} • {$companyPhone}</div>
            <div style="margin-top:8px; color:#666;"><i>Selanjutnya disebut “Pihak Pertama”.</i></div>
        </td>
        <td style="width:50%; padding:10px 12px; border:1px solid #e2e2e2; border-radius:10px; vertical-align:top;">
            <div style="font-weight:800; margin-bottom:6px;">PIHAK KEDUA</div>
            <div style="font-weight:700;">{$clientName}</div>
            <div style="color:#444; margin-top:6px;">[Alamat Klien]</div>
            <div style="color:#444; margin-top:4px;">{$clientEmail} • {$clientWa}</div>
            <div style="margin-top:8px; color:#666;"><i>Selanjutnya disebut “Pihak Kedua”.</i></div>
        </td>
        </tr>
    </table>

    <!-- PASAL -->
    <div style="margin-top:8px;">
        <div style="font-weight:900; font-size:13px; margin:12px 0 6px;">Pasal 1 — Ruang Lingkup Pekerjaan</div>
        <div>Pihak Pertama akan mengerjakan project: <b>{$projectTitle}</b> dengan ruang lingkup sebagai berikut:</div>
        <div style="margin-top:8px;">{$scopeHtml}</div>

        <div style="font-weight:900; font-size:13px; margin:14px 0 6px;">Pasal 2 — Waktu Pengerjaan</div>
        <div>Estimasi waktu pengerjaan: <b>{$duration}</b>.</div>

        <div style="font-weight:900; font-size:13px; margin:14px 0 6px;">Pasal 3 — Biaya & Pembayaran</div>
        <table style="width:100%; border-collapse:collapse; margin-top:8px; border:1px solid #e2e2e2; border-radius:10px; overflow:hidden;">
        <tr style="background:#f6f6f6;">
            <th style="text-align:left; padding:10px; font-size:12px;">Item</th>
            <th style="text-align:right; padding:10px; font-size:12px;">Biaya</th>
        </tr>
        <tr>
            <td style="padding:10px; border-top:1px solid #eee;">Pembuatan / Pengembangan Project</td>
            <td style="padding:10px; border-top:1px solid #eee; text-align:right; font-weight:800;">{$total}</td>
        </tr>
        </table>

        <div style="margin-top:10px;">
        <div><b>Skema Pembayaran:</b></div>
        <div style="margin-top:6px;">{$paymentHtml}</div>
        </div>

        <div style="font-weight:900; font-size:13px; margin:14px 0 6px;">Pasal 4 — Revisi</div>
        <div>Termasuk <b>2x revisi minor</b> sesuai scope. Revisi di luar scope / tambahan fitur dapat dikenakan biaya tambahan.</div>

        <div style="font-weight:900; font-size:13px; margin:14px 0 6px;">Pasal 5 — Hak Kekayaan Intelektual</div>
        <div>Hak cipta hasil kerja diserahkan setelah pelunasan, kecuali ada ketentuan lain yang disepakati.</div>

        <div style="font-weight:900; font-size:13px; margin:14px 0 6px;">Pasal 6 — Ketentuan Lain</div>
        <ul style="margin:8px 0 0 18px; padding:0;">
        <li>Pihak Kedua bertanggung jawab menyediakan materi yang memiliki izin penggunaan.</li>
        <li>Keterlambatan materi dapat mempengaruhi timeline.</li>
        <li>Perjanjian ini sah sejak disetujui kedua pihak.</li>
        </ul>
    </div>

    <!-- TANDA TANGAN -->
    <div style="margin-top:22px; page-break-inside:avoid;">
        <div style="margin-bottom:10px;">Demikian perjanjian ini dibuat untuk dipatuhi oleh kedua belah pihak.</div>

        <table style="width:100%; border-collapse:collapse; margin-top:14px;">
        <tr>
            <td style="width:50%; vertical-align:top; padding-right:10px;">
            <div style="font-weight:900; margin-bottom:6px;">PIHAK PERTAMA</div>
            <div style="font-weight:700;">{$companyName}</div>
            <div style="height:52px;"></div>
            <div>Nama: ___________________________</div>
            <div style="margin-top:6px;">Tanda tangan: ____________________</div>
            </td>
            <td style="width:50%; vertical-align:top; padding-left:10px;">
            <div style="font-weight:900; margin-bottom:6px;">PIHAK KEDUA</div>
            <div style="font-weight:700;">{$clientName}</div>
            <div style="height:52px;"></div>
            <div>Nama: ___________________________</div>
            <div style="margin-top:6px;">Tanda tangan: ____________________</div>
            </td>
        </tr>
        </table>

        <div style="margin-top:14px; font-size:11px; color:#666;">
        Catatan: Dokumen ini dapat dicetak. Jika diperlukan tanda tangan basah, silakan cetak dan tandatangani.
        </div>
    </div>

    </div>
    HTML;

        return $html;
    }
}
