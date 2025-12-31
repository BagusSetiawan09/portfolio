<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Bast;
use App\Models\Letter;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');

// DOWNLOAD INVOICE ORDER
Route::get('/order/{record}/invoice', function (Order $record) {
    $pdf = Pdf::loadView('pdf.invoice', ['order' => $record]);
    return $pdf->download('Invoice-Order-' . $record->id . '.pdf');
})->name('order.invoice');

// ==================================================================
// --- PRINT BAST (BERITA ACARA SERAH TERIMA) ---
// ==================================================================
Route::get('/admin/bast/{bast}/print', function (Bast $bast) {
    return '
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <title>BAST - '.$bast->number.'</title>
        <style>
            @page { size: A4; margin: 2.5cm; }
            @media print { 
                body { margin: 0; padding: 0; }
                header, footer { display: none !important; }
            }
            body {
                font-family: "Times New Roman", serif;
                font-size: 12pt;
                line-height: 1.5;
                color: #000;
                background: white;
            }
            .header-surat {
                text-align: center;
                margin-bottom: 20px;
                font-weight: bold;
                text-transform: uppercase;
            }
            .header-surat h3 { margin: 0; text-decoration: underline; font-size: 14pt; }
            .header-surat p { margin: 5px 0 0 0; font-size: 12pt; font-weight: normal; }
            .content { text-align: justify; }
            .table-identity { width: 100%; border-collapse: collapse; margin-top: 10px; margin-bottom: 10px; }
            .table-identity td { padding: 2px 5px; vertical-align: top; border: none; }
            .col-number { width: 25px; }
            .col-label { width: 130px; }
            .col-sep { width: 10px; }
            .pasal-title { text-align: center; font-weight: bold; margin-top: 20px; margin-bottom: 10px; }
            ul, ol { margin-top: 5px; margin-bottom: 5px; padding-left: 25px; }
            li { margin-bottom: 5px; }
            .signature-section { width: 100%; margin-top: 30px; page-break-inside: avoid; }
            .signature-table { width: 100%; border-collapse: collapse; text-align: center; }
            .signature-table td { border: none; vertical-align: top; padding: 0; }
            .ttd-space { height: 80px; }
        </style>
    </head>
    <body onload="window.print()">
        <div class="header-surat">
            <h3>BERITA ACARA SERAH TERIMA PEKERJAAN</h3>
            <p>NOMOR: '.$bast->number.'</p>
        </div>
        <div class="content">
            <p>Pada hari ini, <strong>'.now()->parse($bast->handover_date)->locale('id')->isoFormat('dddd, D MMMM Y').'</strong>, kami yang bertanda tangan di bawah ini:</p>
            <table class="table-identity">
                <tr><td class="col-number">1.</td><td class="col-label">Nama</td><td class="col-sep">:</td><td><strong>Bagus Setiawan</strong></td></tr>
                <tr><td></td><td>Jabatan</td><td>:</td><td>Freelance Fullstack Developer</td></tr>
                <tr><td></td><td colspan="3">Selanjutnya disebut sebagai <strong>PIHAK PERTAMA</strong>.</td></tr>
            </table>
            <table class="table-identity">
                <tr><td class="col-number">2.</td><td class="col-label">Nama</td><td class="col-sep">:</td><td><strong>'.$bast->client_name.'</strong></td></tr>
                <tr><td></td><td>Jabatan</td><td>:</td><td>Owner / Penanggung Jawab</td></tr>
                <tr><td></td><td colspan="3">Selanjutnya disebut sebagai <strong>PIHAK KEDUA</strong>.</td></tr>
            </table>
            <p>Selanjutnya secara bersama-sama PIHAK PERTAMA dan PIHAK KEDUA dalam hal ini disebut <strong>PARA PIHAK</strong>.</p>
            <p>PARA PIHAK sepakat melaksanakan serah terima pekerjaan <strong>Development Website ('.$bast->project_title.')</strong> dengan ketentuan sebagai berikut:</p>
            <div class="pasal-title">Pasal 1<br>Serah Terima Pekerjaan</div>
            <p>PIHAK PERTAMA menyerahkan kepada PIHAK KEDUA, dan PIHAK KEDUA menerima penyerahan dari PIHAK PERTAMA berupa hasil pekerjaan Website dengan rincian akses dan file sebagai berikut:</p>
            <ul>
                <li>Source Code Website (Full)</li>
                <li>Akses Login Administrator (Dashboard)</li>
                <li>Akses Hosting / CPanel / Server (Jika ada)</li>
                <li>Dokumentasi Penggunaan / Manual</li>
            </ul>
            <p><em>(Rincian kredensial lengkap terlampir terpisah demi keamanan).</em></p>
            <div class="pasal-title">Pasal 2<br>Garansi dan Pemeliharaan</div>
            <ol>
                <li>Sejak penandatanganan Berita Acara ini, maka seluruh tanggung jawab pengelolaan konten dan operasional website berpindah dari PIHAK PERTAMA kepada PIHAK KEDUA.</li>
                <li>PIHAK PERTAMA memberikan masa <strong>Garansi (Maintenance) selama 30 (Tiga Puluh) Hari</strong> kalender terhitung sejak tanggal surat ini diterbitkan.</li>
                <li>Garansi meliputi perbaikan <em>bug</em> atau <em>error</em> teknis. Penambahan fitur baru di luar kesepakatan awal akan dikenakan biaya tambahan.</li>
            </ol>
            <div class="pasal-title">Pasal 3<br>Penutup</div>
            <p>Demikian Berita Acara Serah Terima ini dibuat dengan sebenarnya dalam rangkap secukupnya untuk dipergunakan sebagaimana mestinya.</p>
            <div class="signature-section">
                <table class="signature-table">
                    <tr><td width="50%">PIHAK KEDUA</td><td width="50%">PIHAK PERTAMA</td></tr>
                    <tr><td class="ttd-space"></td> <td class="ttd-space"></td></tr>
                    <tr><td><strong>( '.$bast->client_name.' )</strong></td><td><strong>( Bagus Setiawan )</strong></td></tr>
                </table>
            </div>
        </div>
    </body>
    </html>
    ';
})->name('bast.print')->middleware('auth');

// ==================================================================
// --- PRINT SURAT RESMI (METODE FIX TOTAL JARAK RAPAT) ---
// ==================================================================
Route::get('/admin/letters/{letter}/print', function (Letter $letter) {
    
    $path = storage_path('app/company_settings.json');
    $settings = file_exists($path) ? json_decode(file_get_contents($path), true) : [];

    $logoUrl    = $settings['company_logo'] ?? null;
    $signBase64 = $settings['company_signature'] ?? null; 
    $compName   = $settings['company_name'] ?? 'CODEXLY';
    $compSub    = $settings['company_subtext'] ?? 'Your Website Solution';
    $compAddr   = $settings['company_address'] ?? 'Kota Medan, Sumatera Utara';
    $compEmail  = $settings['company_email'] ?? 'hello@codexly.site';
    $compPhone  = $settings['company_phone'] ?? '0895-6288-94070';

    // 1. PEMBERSIH KONTEN AGRESIF
    $content = $letter->content;
    $content = str_replace(['Hormat Kami,', 'Hormat Kami', 'Bagus Setiawan'], '', $content);
    // Hapus tag paragraf kosong di akhir konten secara paksa
    $content = preg_replace('/(<p[^>]*>(\s|&nbsp;|<\/?br\s?\/?>)*<\/p>)+$/i', '', trim($content));

    return '
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <style>
            @page { size: A4; margin: 1.5cm 2cm; }
            body { font-family: "Times New Roman", serif; font-size: 11pt; line-height: 1.3; color: #000; margin: 0; padding: 0; }
            
            .header { border-bottom: 3px solid black; padding-bottom: 5px; margin-bottom: 15px; }
            .tbl-header { width: 100%; border-collapse: collapse; }
            .comp-name { font-size: 18pt; font-weight: bold; text-transform: uppercase; margin: 0; }
            
            .meta-table { width: 100%; margin-bottom: 10px; }
            
            /* WRAPPER KONTEN UNTUK MENGUNCI POSISI */
            .main-content-wrapper { position: relative; }
            .content-box { text-align: justify; margin: 0; padding: 0; }
            .content-box p { margin: 0 0 5px 0; padding: 0; }
            .content-box p:last-child { margin-bottom: 0 !important; }
            
            /* AREA PENUTUP YANG DIRAPATKAN */
            .closing-area { 
                width: 250px; 
                margin-top: 15px; /* Jarak aman dari baris terakhir teks */
                page-break-inside: avoid; 
            }
            .sign-img { display: block; max-height: 75px; width: auto; margin: 2px 0; }
            .sign-name { font-weight: bold; text-decoration: underline; margin: 0; display: block; }
        </style>
    </head>
    <body onload="setTimeout(function(){ window.print(); }, 1000)">
        
        <div class="header">
            <table class="tbl-header">
                <tr>
                    <td style="width: 65%;">
                        '. ($logoUrl ? '<img src="'.$logoUrl.'" style="height: 60px; float: left; margin-right: 15px;">' : '') .'
                        <div style="display: inline-block;">
                            <div class="comp-name">'.$compName.'</div>
                            <div style="font-size: 9pt; font-style: italic;">'.$compSub.'</div>
                        </div>
                    </td>
                    <td style="text-align: right; font-size: 8pt; width: 35%;">
                        <strong>'.$compPhone.'</strong><br>'.$compEmail.'<br>'.$compAddr.'
                    </td>
                </tr>
            </table>
        </div>

        <table class="meta-table">
            <tr>
                <td width="60%">Nomor : <strong>'.$letter->number.'</strong></td>
                <td width="40%" style="text-align: right;">Medan, '.$letter->letter_date->locale('id')->isoFormat('D MMMM Y').'</td>
            </tr>
            <tr><td colspan="2">Perihal : '.$letter->subject.'</td></tr>
        </table>

        <div class="main-content-wrapper">
            <div class="content-box">
                '.$content.'
            </div>

            <div class="closing-area">
                <span style="display: block; margin-bottom: 0;">Hormat Kami,</span>
                '. ($signBase64 ? '<img src="'.$signBase64.'" class="sign-img">' : '<div style="height: 50px;"></div>') .'
                <span class="sign-name">Bagus Setiawan</span>
            </div>
        </div>

    </body>
    </html>';
})->name('letter.print')->middleware('auth');