<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('letters', function (Blueprint $table) {
            $table->id();
            
            // Jenis Surat (Contoh: Penawaran, Tagihan, Tugas, Lainnya)
            $table->string('type')->default('general'); 
            
            // Metadata Surat
            $table->string('number')->unique(); // Nomor Surat (Auto)
            $table->string('subject'); // Perihal
            $table->date('letter_date'); // Tanggal Surat
            
            // Penerima
            $table->string('recipient_name');
            $table->string('recipient_company')->nullable();
            $table->text('recipient_address')->nullable();

            // Isi Surat
            $table->longText('content'); // Isi utama (Rich Text)
            
            // Status
            $table->string('status')->default('draft'); // draft, sent, archived
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('letters');
    }
};