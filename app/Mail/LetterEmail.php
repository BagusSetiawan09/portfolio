<?php

namespace App\Mail;

use App\Models\Letter;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\Queue\ShouldQueue;

class LetterEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Letter $letter;

    public function __construct(Letter $letter)
    {
        $this->letter = $letter;
    }

    public function build()
    {
        return $this->subject('Dokumen Resmi: ' . $this->letter->subject)
            ->view('emails.letter')
            ->attachData(
                $this->generatePdf(),
                "Surat-{$this->letter->number}.pdf",
                ['mime' => 'application/pdf']
            );
    }

    protected function generatePdf(): string
    {
        return Pdf::loadView('emails.letter', [
            'letter' => $this->letter
            ])
            ->setPaper('a4')
            ->output();
    }
}
