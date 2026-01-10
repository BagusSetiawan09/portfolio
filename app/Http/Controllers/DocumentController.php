<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Bast;
use App\Models\Letter;
use App\Models\Proposal;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    // 1. DOWNLOAD INVOICE
    public function invoice(Order $order)
    {
        $pdf = Pdf::loadView('pdf.invoice', ['order' => $order]);
        return $pdf->download('Invoice-Order-' . $order->id . '.pdf');
    }

    // 2. DOWNLOAD PROPOSAL (Yang barusan kita buat)
    public function proposal(Proposal $proposal)
    {
        $pdf = Pdf::loadView('pdf.proposal', ['record' => $proposal]);
        $pdf->setPaper('a4', 'portrait');
        return $pdf->stream('Proposal-' . $proposal->client_name . '.pdf');
    }

    // 3. PRINT BAST (Berita Acara)
    public function bast(Bast $bast)
    {
        return view('print.bast', compact('bast'));
    }

    // 4. PRINT SURAT RESMI
    public function letter(Letter $letter)
    {
        // Ambil Settings
        $path = storage_path('app/company_settings.json');
        $settings = file_exists($path) ? json_decode(file_get_contents($path), true) : [];

        // Bersihkan Konten (Logic Agresif tadi)
        $content = $letter->content;
        $content = str_replace(['Hormat Kami,', 'Hormat Kami', 'Bagus Setiawan'], '', $content);
        $content = preg_replace('/(<p[^>]*>(\s|&nbsp;|<\/?br\s?\/?>)*<\/p>)+$/i', '', trim($content));

        return view('print.letter', compact('letter', 'settings', 'content'));
    }
}