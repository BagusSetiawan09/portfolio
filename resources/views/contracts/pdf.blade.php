<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>{{ $contract->number }}</title>
    <style>
        @page { margin: 22mm 18mm 20mm 18mm; }

        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 12px;
            color: #111;
        }

        img { max-width: 100%; }

        /* Biar tabel/section gak pecah aneh */
        .no-break { page-break-inside: avoid; }

        /* Garis divider */
        .divider { height: 1px; background: #e5e5e5; margin: 14px 0 18px; }
    </style>
</head>
<body>

    {{-- PENTING: jangan escape --}}
    {!! $contract->content !!}

    {{-- Page number (Dompdf) --}}
    <script type="text/php">
        if (isset($pdf)) {
            $pdf->page_text(520, 820, "Halaman {PAGE_NUM} / {PAGE_COUNT}", null, 9, array(0,0,0));
        }
    </script>

</body>
</html>
