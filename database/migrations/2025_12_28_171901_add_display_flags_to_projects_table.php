<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            if (!Schema::hasColumn('projects', 'show_in_latest')) {
                $table->boolean('show_in_latest')->default(true)->after('is_published');
            }

            if (!Schema::hasColumn('projects', 'show_in_portfolio')) {
                $table->boolean('show_in_portfolio')->default(true)->after('show_in_latest');
            }
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $drop = [];

            foreach (['show_in_latest', 'show_in_portfolio'] as $col) {
                if (Schema::hasColumn('projects', $col)) $drop[] = $col;
            }

            if (!empty($drop)) $table->dropColumn($drop);
        });
    }
};
