<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('basts', function (Blueprint $table) {
            if (!Schema::hasColumn('basts', 'client_email')) {
                $table->string('client_email')->nullable()->after('client_name');
            }
            if (!Schema::hasColumn('basts', 'sent_at')) {
                $table->timestamp('sent_at')->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('basts', function (Blueprint $table) {
            $drop = [];
            if (Schema::hasColumn('basts', 'client_email')) $drop[] = 'client_email';
            if (Schema::hasColumn('basts', 'sent_at')) $drop[] = 'sent_at';
            if (!empty($drop)) $table->dropColumn($drop);
        });
    }
};
