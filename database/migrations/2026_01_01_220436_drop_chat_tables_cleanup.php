<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        // Drop dulu tabel child (messages), baru parent (threads)
        Schema::dropIfExists('chat_messages');
        Schema::dropIfExists('chat_threads');

        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        // Tidak perlu balikkan (karena memang mau dihapus total)
        // Kalau mau, bisa kamu isi ulang create table.
    }
};
