<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</title>
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Unbounded:wght@700;900&display=swap');

        /* RESET & BASE STYLES */
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #000;
            font-size: 14px;
            margin: 0;
            padding: 20px;
        }

        /* HELPER CLASSES */
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }
        
        /* HEADER SECTION */
        .header-table { width: 100%; margin-bottom: 20px; }
        
        .invoice-title {
            font-size: 42px;
            font-weight: bold;
            letter-spacing: -1px;
            text-transform: uppercase;
        }

        /* LOGO CUSTOM (Unbounded) */
        .logo-container {
            font-family: 'Unbounded', sans-serif;
            font-weight: 900;
            font-size: 28px;
            line-height: 0.85;
            text-transform: uppercase;
            text-align: right;
        }
        .logo-top { display: block; color: #000; }
        .logo-bottom { display: block; color: #b7ff00; }

        /* GARIS PEMBATAS */
        .separator {
            border-bottom: 1px solid #555;
            margin-bottom: 25px;
            margin-top: 10px;
        }

        /* INFO SECTION (Kepada & Tanggal) */
        .info-table { width: 100%; margin-bottom: 30px; }
        .info-heading { font-weight: bold; font-size: 14px; margin-bottom: 5px; }
        .info-content { font-size: 14px; line-height: 1.4; color: #333; }

        /* MAIN ITEMS TABLE */
        .items-table {
            width: 100%;
            border-collapse: separate; 
            border-spacing: 0;
            margin-bottom: 20px;
        }
        
        /* HEADER TABEL HITAM */
        .items-table th {
            background-color: #18181b;
            color: #b7ff00;
            padding: 12px 15px;
            text-align: left;
            font-size: 14px;
            font-weight: bold;
            text-transform: capitalize;
        }
        
        /* ISI TABEL */
        .items-table td {
            padding: 15px;
            border-bottom: 1px solid #eee;
        }
        .items-table th:last-child, 
        .items-table td:last-child { text-align: right; }

        /* TOTAL SECTION */
        .totals-table { width: 100%; margin-top: 10px; }
        .totals-table td { padding: 5px 15px; text-align: right; }
        .total-label { font-size: 14px; }
        .total-value { font-size: 14px; font-weight: bold; }
        .final-total { font-size: 16px; font-weight: 900; }

        /* FOOTER */
        .footer-table {
            width: 100%;
            margin-top: 60px;
            border-top: 1px solid #555;
            padding-top: 20px;
        }
        .footer-left { font-size: 13px; line-height: 1.4; }
        .footer-right { font-size: 13px; line-height: 1.4; text-align: right; }
        
        .signature-area {
            margin-top: 40px;
        }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td valign="bottom">
                <div class="invoice-title">INVOICE</div>
            </td>
            <td valign="bottom" class="text-right">
                <div class="logo-container">
                    <span class="logo-top">CODEXLY</span>
                    {{-- <span class="logo-bottom">LY</span> --}}
                </div>
            </td>
        </tr>
    </table>

    <div class="separator"></div>

    <table class="info-table">
        <tr>
            <td width="60%" valign="top">
                <div class="info-heading">Kepada</div>
                <div class="info-content">
                    <strong>{{ $order->name }}</strong><br>
                    {{ $order->email }}<br>
                    {{ $order->whatsapp ?? '' }}
                </div>
            </td>
            <td width="40%" valign="top" class="text-right">
                <div class="info-heading">Tanggal</div>
                <div class="info-content">
                    {{ now()->translatedFormat('l, d F Y') }}
                </div>
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th width="50%" style="border-top-left-radius: 8px; border-bottom-left-radius: 8px;">Produk</th>
                <th width="15%">Jumlah</th>
                <th width="35%" style="border-top-right-radius: 8px; border-bottom-right-radius: 8px;">Harga</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <div style="font-weight: bold; font-size: 14px;">
                        {{ $order->service ?? 'Jasa Pembuatan Software' }}
                    </div>
                    <div style="font-size: 12px; color: #555; margin-top: 4px;">
                        {{ $order->topic ? $order->topic : '-' }}
                    </div>
                </td>
                <td style="vertical-align: top;">1</td>
                <td style="vertical-align: top;">
                    {{ $order->budget_range ?? 'Hubungi Admin' }}
                </td>
            </tr>
            <tr><td colspan="3" style="height: 100px;"></td></tr>
        </tbody>
    </table>

    <table class="totals-table">
        <tr>
            <td width="60%"></td>
            <td width="20%" class="total-label">Subtotal</td>
            <td width="20%" class="total-value">{{ $order->budget_range ?? '-' }}</td>
        </tr>
        <tr>
            <td></td>
            <td class="total-label bold">Total</td>
            <td class="total-value final-total">{{ $order->budget_range ?? '-' }}</td>
        </tr>
    </table>

    <table class="footer-table">
        <tr>
            <td width="50%" valign="top" class="footer-left">
                <strong>Codexly</strong><br>
                Medan, Indonesia
            </td>
            <td width="50%" valign="top" class="footer-right">
                +62 895 6288 94070<br>
                hello@codexly.site
                
                <div class="signature-area">
                    Medan, {{ now()->translatedFormat('d F Y') }}
                    <br><br><br><br>
                    <strong>Bagus Setiawan</strong>
                </div>
            </td>
        </tr>
    </table>

</body>
</html>