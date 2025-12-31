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
        Schema::table('profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('profiles', 'footer_quote')) {
            $table->text('footer_quote')->nullable();
            }
            if (!Schema::hasColumn('profiles', 'email_label')) {
                $table->string('email_label')->nullable();
                $table->string('phone_label')->nullable();
                $table->string('social_title')->nullable();
                $table->string('quick_link_title')->nullable();
                $table->json('footer_links')->nullable();
                $table->string('copyright_text')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            //
        });
    }
};
