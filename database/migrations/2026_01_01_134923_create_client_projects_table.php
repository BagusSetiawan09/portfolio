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
        Schema::create('client_projects', function (Blueprint $table) {
            $table->id();

            // Optional relasi ke clients & orders (kalau ada)
            $table->unsignedBigInteger('client_id')->nullable()->index();
            $table->unsignedBigInteger('order_id')->nullable()->index();

            // Fallback kalau tidak pakai relasi client
            $table->string('client_name')->nullable();

            // Project / Order info
            $table->string('project_type')->nullable(); // contoh: Website, Maintenance, App, dll
            $table->string('order_status')->default('new'); // sync dari order atau manual

            // Website
            $table->string('website_status')->default('draft'); // draft|published|maintenance|offline
            $table->string('domain')->nullable();
            $table->string('website_url')->nullable();

            // Server
            $table->string('server_type')->nullable(); // cpanel|vps|shared|cloud|none
            $table->string('server_provider')->nullable(); // contoh: Niagahoster, AWS, dll
            $table->string('server_ip')->nullable();
            $table->string('server_panel_url')->nullable(); // login url panel
            $table->string('server_username')->nullable();
            $table->text('server_password')->nullable(); // nanti di-cast encrypted
            $table->text('server_notes')->nullable(); // instruksi / catatan akses

            // Renewals / Dates
            $table->date('hosting_expires_at')->nullable();
            $table->date('ssl_expires_at')->nullable();
            $table->dateTime('last_backup_at')->nullable();
            $table->dateTime('last_deploy_at')->nullable();

            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable(); // catatan internal + next action

            $table->timestamps();

            // Optional FK (aktifkan kalau tabelnya beneran ada)
            // $table->foreign('client_id')->references('id')->on('clients')->nullOnDelete();
            // $table->foreign('order_id')->references('id')->on('orders')->nullOnDelete();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_projects');
    }
};
