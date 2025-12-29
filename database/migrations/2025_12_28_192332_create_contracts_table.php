<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();

            // Nomor kontrak dibuat otomatis setelah record dibuat
            $table->string('number')->nullable()->unique();

            // optional relasi (kalau mau tarik data dari order/project)
            $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();
            $table->foreignId('project_id')->nullable()->constrained('projects')->nullOnDelete();

            // client
            $table->string('client_name');
            $table->string('client_email')->nullable();
            $table->string('client_whatsapp')->nullable();

            // project/terms ringkas (untuk metadata + bisa dipakai template)
            $table->string('project_title')->nullable();
            $table->text('scope')->nullable();
            $table->string('price')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->text('payment_terms')->nullable();
            $table->text('notes')->nullable();

            $table->string('status')->default('draft'); // draft | sent | signed | cancelled

            // isi kontrak fleksibel (HTML dari RichEditor)
            $table->longText('content')->nullable();

            $table->timestamp('signed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
