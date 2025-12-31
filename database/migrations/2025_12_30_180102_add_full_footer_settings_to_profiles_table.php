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
            $table->string('email_label')->nullable(); // "Please send me an email to"
            $table->string('phone_label')->nullable(); // "Let's talk!"
            $table->string('social_title')->nullable(); // "Social"
            $table->string('quick_link_title')->nullable(); // "Quick link"

            $table->json('footer_links')->nullable(); 

            // Copyright Custom
            $table->string('copyright_text')->nullable();
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
