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
        Schema::table('pendaftar', function (Blueprint $table) {
            $table->boolean('setuju_daftar_ulang')->default(false)->after('status_pendaftaran');
            $table->timestamp('tanggal_setuju_daftar_ulang')->nullable()->after('setuju_daftar_ulang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pendaftar', function (Blueprint $table) {
            $table->dropColumn(['setuju_daftar_ulang', 'tanggal_setuju_daftar_ulang']);
        });
    }
};
