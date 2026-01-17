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
            // Tambahkan field untuk jenis sekolah asal (madrasah/umum)
            $table->enum('jenis_sekolah_asal', ['madrasah', 'umum'])->after('asal_sekolah')->nullable();

            // NSM untuk madrasah (12 digit)
            $table->string('nsm_sekolah_asal', 12)->after('jenis_sekolah_asal')->nullable();

            // NPSN untuk semua sekolah (8 digit)
            $table->string('npsn_sekolah_asal', 8)->after('nsm_sekolah_asal')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pendaftar', function (Blueprint $table) {
            $table->dropColumn(['jenis_sekolah_asal', 'nsm_sekolah_asal', 'npsn_sekolah_asal']);
        });
    }
};
