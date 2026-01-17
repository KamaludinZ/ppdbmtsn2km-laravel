<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For PostgreSQL, we need to alter the column type
        // Drop the old enum constraint and recreate with new values

        DB::statement("ALTER TABLE dokumen DROP CONSTRAINT IF EXISTS dokumen_jenis_dokumen_check");

        DB::statement("ALTER TABLE dokumen ALTER COLUMN jenis_dokumen TYPE VARCHAR(50)");

        DB::statement("
            ALTER TABLE dokumen ADD CONSTRAINT dokumen_jenis_dokumen_check
            CHECK (jenis_dokumen IN (
                'foto',
                'kk',
                'kartu_keluarga',
                'akta_kelahiran',
                'ijazah',
                'skhun',
                'raport',
                'rapor',
                'nisn',
                'surat_keterangan_prestasi',
                'sertifikat',
                'sertifikat_tahfidz',
                'kip',
                'pkh',
                'kks',
                'lainnya'
            ))
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original enum values
        DB::statement("ALTER TABLE dokumen DROP CONSTRAINT IF EXISTS dokumen_jenis_dokumen_check");

        DB::statement("
            ALTER TABLE dokumen ADD CONSTRAINT dokumen_jenis_dokumen_check
            CHECK (jenis_dokumen IN (
                'foto',
                'kk',
                'akta_kelahiran',
                'ijazah',
                'skhun',
                'raport',
                'surat_keterangan_prestasi',
                'sertifikat_tahfidz',
                'lainnya'
            ))
        ");
    }
};
