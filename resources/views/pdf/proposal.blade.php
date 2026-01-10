<!DOCTYPE html>
<html>
<head>
    <title>Proposal - {{ $record->project_title }}</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 12pt; line-height: 1.6; color: #333; }
        
        /* Format Kertas A4 */
        @page { margin: 0cm 0cm; }
        .page-content { margin-top: 4cm; margin-left: 2.5cm; margin-right: 2.5cm; margin-bottom: 2.5cm; }

        /* Kop Surat (Header) */
        header { position: fixed; top: 0cm; left: 0cm; right: 0cm; height: 3cm; background-color: #f4f4f4; border-bottom: 2px solid #000; text-align: center; line-height: 0.5cm; }
        
        /* Judul Dokumen */
        .doc-title { text-align: center; margin-bottom: 30px; margin-top: 20px;}
        .doc-title h2 { text-transform: uppercase; text-decoration: underline; font-size: 16pt; margin: 0; }
        .doc-title p { margin: 5px 0 0; font-size: 11pt; }

        /* Isi Konten dari AI */
        .content { text-align: justify; }
        .content h3 { font-size: 13pt; margin-top: 20px; border-bottom: 1px solid #ddd; padding-bottom: 5px; }
        .content ul { margin-left: 20px; }

        /* Tanda Tangan */
        .signature { margin-top: 50px; width: 100%; }
        .signature-box { width: 40%; float: right; text-align: center; }
        .ttd-space { height: 80px; }
    </style>
</head>
<body>

    <header>
        <br>
        <h1>YOUR CREATIVE STUDIO</h1>
        <p>Jalan Kreatif No. 1, Jakarta | www.portfolio.com | +62 812-3456-7890</p>
    </header>

    <div class="page-content">
        <div class="doc-title">
            <h2>PROPOSAL PENAWARAN</h2>
            <p>Nomor: {{ $record->id }}/PR/{{ date('Y') }}</p>
        </div>

        <p>
            <strong>Kepada Yth.</strong><br>
            {{ $record->client_name }}<br>
            Di Tempat
        </p>

        <div class="content">
            {!! $record->content !!}
        </div>

        <div class="signature">
            <div class="signature-box">
                <p>Hormat Kami,</p>
                <div class="ttd-space">
                    </div>
                <p><strong>( Nama Anda Disini )</strong></p>
            </div>
        </div>
    </div>

</body>
</html>