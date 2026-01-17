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
            $table->text('alamat_ayah')->nullable()->after('alamat');
            $table->text('alamat_ibu')->nullable()->after('alamat_ayah');
            $table->text('alamat_wali')->nullable()->after('alamat_ibu');
            
            // Detail alamat siswa (atau umum)
            $table->string('provinsi')->nullable()->after('alamat_wali');
            $table->string('kabupaten')->nullable()->after('provinsi');
            $table->string('kecamatan')->nullable()->after('kabupaten');
            $table->string('desa')->nullable()->after('kecamatan');
            $table->string('kode_pos')->nullable()->after('desa');
            
            $table->string('jarak_ke_sekolah')->nullable()->after('kode_pos');
            $table->string('transportasi')->nullable()->after('jarak_ke_sekolah');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pendaftar', function (Blueprint $table) {
            $table->dropColumn([
                'alamat_ayah',
                'alamat_ibu',
                'alamat_wali',
                'provinsi',
                'kabupaten',
                'kecamatan',
                'desa',
                'kode_pos',
                'jarak_ke_sekolah',
                'transportasi',
            ]);
        });
    }
};