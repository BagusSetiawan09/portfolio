<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('basts', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique(); // Nomor BAST (ex: BAST/2025/001)
            
            // Relasi ke Project
            $table->foreignId('project_id')->nullable()->constrained('projects')->nullOnDelete();
            
            $table->string('client_name'); // Nama Klien (Snapshot, jaga2 kalau project dihapus)
            $table->string('project_title'); // Judul Project (Snapshot)
            
            $table->date('handover_date'); // Tanggal Serah Terima
            $table->longText('items_list'); // Apa saja yang diserahkan (Source code, Credential, dll)
            
            $table->string('status')->default('draft'); // draft, sent, signed
            $table->string('file_path')->nullable(); // Upload file BAST yang sudah TTD basah/digital
            
            $table->text('notes')->nullable(); // Catatan tambahan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('basts');
    }
};
