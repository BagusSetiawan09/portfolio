<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // pilihan: order atau consultation
            if (!Schema::hasColumn('orders', 'type')) {
                $table->string('type')->default('order')->after('id');
            }

            // kalau mau beda dari phone, simpan whatsapp terpisah
            if (!Schema::hasColumn('orders', 'whatsapp')) {
                $table->string('whatsapp')->nullable()->after('phone');
            }

            if (!Schema::hasColumn('orders', 'topic')) {
                $table->string('topic')->nullable()->after('service');
            }

            if (!Schema::hasColumn('orders', 'budget_range')) {
                $table->string('budget_range')->nullable()->after('budget');
            }

            if (!Schema::hasColumn('orders', 'deadline')) {
                $table->date('deadline')->nullable()->after('budget_range');
            }

            if (!Schema::hasColumn('orders', 'preferred_channel')) {
                $table->string('preferred_channel')->nullable()->after('deadline');
            }

            if (!Schema::hasColumn('orders', 'preferred_time')) {
                $table->string('preferred_time')->nullable()->after('preferred_channel');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $drop = [];

            foreach (['type','whatsapp','topic','budget_range','deadline','preferred_channel','preferred_time'] as $col) {
                if (Schema::hasColumn('orders', $col)) $drop[] = $col;
            }

            if (!empty($drop)) {
                $table->dropColumn($drop);
            }
        });
    }
};
