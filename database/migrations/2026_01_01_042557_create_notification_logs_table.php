<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('notification_logs', function (Blueprint $table) {
            $table->id();

            // polymorphic target (Order / Contract / Proposal / Bast / Letter)
            $table->string('notifiable_type');
            $table->unsignedBigInteger('notifiable_id');

            // contoh: order_reminder_15m, contract_followup_day3, dst.
            $table->string('kind');

            // telegram / email (kita start dari telegram dulu karena kamu sudah siap tokennya)
            $table->string('channel')->default('telegram');

            $table->timestamp('sent_at')->nullable();
            $table->json('meta')->nullable();

            $table->timestamps();

            $table->index(['notifiable_type', 'notifiable_id']);
            $table->unique(['notifiable_type', 'notifiable_id', 'kind', 'channel'], 'notif_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_logs');
    }
};
