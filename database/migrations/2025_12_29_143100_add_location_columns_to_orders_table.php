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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('ip_address')->nullable()->after('status');
            $table->string('country')->nullable()->after('ip_address');
            $table->string('city')->nullable()->after('country');
            // Lat & Long untuk titik koordinat peta
            $table->decimal('lat', 10, 8)->nullable()->after('city');
            $table->decimal('lng', 11, 8)->nullable()->after('lat');
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['ip_address', 'country', 'city', 'lat', 'lng']);
        });
    }
};
