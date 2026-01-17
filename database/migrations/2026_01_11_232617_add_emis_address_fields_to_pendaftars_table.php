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
            // Data Alamat Lengkap (EMIS Kemenag 4.0)
            // Note: provinsi, kabupaten, kecamatan, kode_pos already exist from previous migration
            $table->string('rt', 5)->nullable()->after('alamat')->comment('RT - WAJIB untuk EMIS');
            $table->string('rw', 5)->nullable()->after('rt')->comment('RW - WAJIB untuk EMIS');
            $table->string('dusun', 100)->nullable()->after('rw')->comment('Nama dusun/kampung');

            // Note: Existing column 'desa' can be used as 'kelurahan' for EMIS purposes

            // Koordinat Lokasi
            $table->decimal('latitude', 10, 8)->nullable()->comment('Koordinat lintang');
            $table->decimal('longitude', 11, 8)->nullable()->comment('Koordinat bujur');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pendaftar', function (Blueprint $table) {
            $table->dropColumn([
                'rt',
                'rw',
                'dusun',
                'latitude',
                'longitude',
            ]);
        });
    }
};
