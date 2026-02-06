<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Fix data pendidikan yang menggunakan slash (/) menjadi spasi
     * agar sesuai dengan ENUM constraint database
     */
    public function up(): void
    {
        // Fix ayah_pendidikan
        DB::statement("
            UPDATE pendaftar
            SET ayah_pendidikan = 'SD Sederajat'
            WHERE ayah_pendidikan = 'SD/Sederajat'
        ");

        DB::statement("
            UPDATE pendaftar
            SET ayah_pendidikan = 'SMP Sederajat'
            WHERE ayah_pendidikan = 'SMP/Sederajat'
        ");

        DB::statement("
            UPDATE pendaftar
            SET ayah_pendidikan = 'SMA Sederajat'
            WHERE ayah_pendidikan = 'SMA/Sederajat'
        ");

        DB::statement("
            UPDATE pendaftar
            SET ayah_pendidikan = 'Tidak sekolah'
            WHERE ayah_pendidikan = 'Tidak Sekolah'
        ");

        DB::statement("
            UPDATE pendaftar
            SET ayah_pendidikan = NULL
            WHERE ayah_pendidikan = 'Lainnya'
        ");

        // Fix ibu_pendidikan
        DB::statement("
            UPDATE pendaftar
            SET ibu_pendidikan = 'SD Sederajat'
            WHERE ibu_pendidikan = 'SD/Sederajat'
        ");

        DB::statement("
            UPDATE pendaftar
            SET ibu_pendidikan = 'SMP Sederajat'
            WHERE ibu_pendidikan = 'SMP/Sederajat'
        ");

        DB::statement("
            UPDATE pendaftar
            SET ibu_pendidikan = 'SMA Sederajat'
            WHERE ibu_pendidikan = 'SMA/Sederajat'
        ");

        DB::statement("
            UPDATE pendaftar
            SET ibu_pendidikan = 'Tidak sekolah'
            WHERE ibu_pendidikan = 'Tidak Sekolah'
        ");

        DB::statement("
            UPDATE pendaftar
            SET ibu_pendidikan = NULL
            WHERE ibu_pendidikan = 'Lainnya'
        ");

        // Fix wali_pendidikan
        DB::statement("
            UPDATE pendaftar
            SET wali_pendidikan = 'SD Sederajat'
            WHERE wali_pendidikan = 'SD/Sederajat'
        ");

        DB::statement("
            UPDATE pendaftar
            SET wali_pendidikan = 'SMP Sederajat'
            WHERE wali_pendidikan = 'SMP/Sederajat'
        ");

        DB::statement("
            UPDATE pendaftar
            SET wali_pendidikan = 'SMA Sederajat'
            WHERE wali_pendidikan = 'SMA/Sederajat'
        ");

        DB::statement("
            UPDATE pendaftar
            SET wali_pendidikan = 'Tidak sekolah'
            WHERE wali_pendidikan = 'Tidak Sekolah'
        ");

        DB::statement("
            UPDATE pendaftar
            SET wali_pendidikan = NULL
            WHERE wali_pendidikan = 'Lainnya'
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tidak perlu reverse migration karena ini fix data corruption
        // Data yang sudah di-fix tidak perlu di-revert
    }
};
