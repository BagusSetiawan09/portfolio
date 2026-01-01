<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('support_tickets', function (Blueprint $table) {
            $table->id();

            // Relasi ke client (optional) kalau kamu punya tabel clients
            $table->foreignId('client_id')->nullable()->constrained()->nullOnDelete();

            $table->string('client_name')->nullable();   // kalau tidak pakai relasi client
            $table->string('client_email')->nullable();
            $table->string('client_whatsapp')->nullable();

            $table->string('subject');
            $table->longText('description')->nullable();

            $table->string('category')->default('maintenance'); // maintenance/bug/request
            $table->string('priority')->default('medium');      // low/medium/high/urgent
            $table->string('status')->default('open');          // open/in_progress/waiting_client/resolved/closed

            $table->string('website_url')->nullable();

            // lampiran (path file)
            $table->json('attachments')->nullable();

            // assigned to (user filament)
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('support_tickets');
    }
};
