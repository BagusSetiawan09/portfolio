<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #000; }
        .kop { border-bottom: 3px solid #000; padding-bottom: 10px; margin-bottom: 18px; }
        table { width: 100%; border-collapse: collapse; }
        .right { text-align: right; font-size: 10px; }
        .meta p { margin: 0 0 4px 0; }
        .content { margin-top: 16px; }
        .signature { margin-top: 40px; }
    </style>
</head>
<body>
@php
    $settings  = \App\Filament\Pages\ManageKopSurat::getSettings();

    $logoUrl   = $settings['company_logo'] ?? null;
    $signUrl   = $settings['company_signature'] ?? null;
    $compName  = $settings['company_name'] ?? 'CODEXLY';
    $compSub   = $settings['company_subtext'] ?? '';
    $compAddr  = $settings['company_address'] ?? '';
    $compEmail = $settings['company_email'] ?? '';
    $compPhone = $settings['company_phone'] ?? '';

    $formattedDate = \Illuminate\Support\Carbon::parse($letter->letter_date)
        ->locale('id')->isoFormat('D MMMM Y');
@endphp

<div class="kop">
    <table>
        <tr>
            <td style="width: 60%;">
                @if ($logoUrl)
                    <img src="{{ $logoUrl }}" style="height: 70px;"><br>
                @endif
                <strong>{{ $compName }}</strong><br>
                <small>{{ $compSub }}</small>
            </td>
            <td class="right">
                {!! nl2br(e(trim($compPhone))) !!}<br>
                {!! nl2br(e(trim($compEmail))) !!}<br>
                {!! nl2br(e(trim($compAddr))) !!}
            </td>
        </tr>
    </table>
</div>

<div class="meta">
    <p><strong>Nomor:</strong> {{ $letter->number }}</p>
    <p><strong>Tanggal:</strong> {{ $formattedDate }}</p>
    <p><strong>Perihal:</strong> {{ $letter->subject }}</p>
</div>

<br>

<p>
    Kepada Yth,<br>
    <strong>{{ $letter->recipient_name }}</strong>
    @if(!empty($letter->recipient_company))<br>{{ $letter->recipient_company }}@endif
    @if(!empty($letter->recipient_address))<br>{{ $letter->recipient_address }}@endif
</p>

<div class="content">
    {!! $letter->content !!}
</div>

<div class="signature">
    <p>Hormat Kami,</p>

    @if ($signUrl)
        <img src="{{ $signUrl }}" style="height: 80px;"><br>
    @else
        <div style="height: 80px;"></div>
    @endif

    <strong>Bagus Setiawan</strong>
</div>

</body>
</html>
