<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>BAST - {{ $bast->number }}</title>
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
        <p>NOMOR: {{ $bast->number }}</p>
    </div>

    <div class="content">
        <p>Pada hari ini, <strong>{{ \Carbon\Carbon::parse($bast->handover_date)->locale('id')->isoFormat('dddd, D MMMM Y') }}</strong>, kami yang bertanda tangan di bawah ini:</p>
        
        <table class="table-identity">
            <tr><td class="col-number">1.</td><td class="col-label">Nama</td><td class="col-sep">:</td><td><strong>Bagus Setiawan</strong></td></tr>
            <tr><td></td><td>Jabatan</td><td>:</td><td>Freelance Fullstack Developer</td></tr>
            <tr><td></td><td colspan="3">Selanjutnya disebut sebagai <strong>PIHAK PERTAMA</strong>.</td></tr>
        </table>

        <table class="table-identity">
            <tr><td class="col-number">2.</td><td class="col-label">Nama</td><td class="col-sep">:</td><td><strong>{{ $bast->client_name }}</strong></td></tr>
            <tr><td></td><td>Jabatan</td><td>:</td><td>Owner / Penanggung Jawab</td></tr>
            <tr><td></td><td colspan="3">Selanjutnya disebut sebagai <strong>PIHAK KEDUA</strong>.</td></tr>
        </table>

        <p>Selanjutnya secara bersama-sama PIHAK PERTAMA dan PIHAK KEDUA dalam hal ini disebut <strong>PARA PIHAK</strong>.</p>
        <p>PARA PIHAK sepakat melaksanakan serah terima pekerjaan <strong>Development Website ({{ $bast->project_title }})</strong> dengan ketentuan sebagai berikut:</p>

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
                <tr><td><strong>( {{ $bast->client_name }} )</strong></td><td><strong>( Bagus Setiawan )</strong></td></tr>
            </table>
        </div>
    </div>
</body>
</html>