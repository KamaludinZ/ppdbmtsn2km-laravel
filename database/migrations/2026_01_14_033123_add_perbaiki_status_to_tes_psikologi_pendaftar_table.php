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
        // First, change the column to varchar temporarily
        DB::statement("ALTER TABLE tes_psikologi_pendaftar ALTER COLUMN status_verifikasi DROP DEFAULT");
        DB::statement("ALTER TABLE tes_psikologi_pendaftar ALTER COLUMN status_verifikasi TYPE VARCHAR(50)");

        // Drop the old constraint
        DB::statement("ALTER TABLE tes_psikologi_pendaftar DROP CONSTRAINT IF EXISTS tes_psikologi_pendaftar_status_verifikasi_check");

        // Add the new constraint with the additional 'perbaiki' value
        DB::statement("ALTER TABLE tes_psikologi_pendaftar ADD CONSTRAINT tes_psikologi_pendaftar_status_verifikasi_check CHECK (status_verifikasi IN ('belum_upload', 'menunggu', 'disetujui', 'ditolak', 'perbaiki'))");

        // Set default back
        DB::statement("ALTER TABLE tes_psikologi_pendaftar ALTER COLUMN status_verifikasi SET DEFAULT 'belum_upload'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original constraint
        DB::statement("ALTER TABLE tes_psikologi_pendaftar ALTER COLUMN status_verifikasi DROP DEFAULT");
        DB::statement("ALTER TABLE tes_psikologi_pendaftar DROP CONSTRAINT IF EXISTS tes_psikologi_pendaftar_status_verifikasi_check");
        DB::statement("ALTER TABLE tes_psikologi_pendaftar ADD CONSTRAINT tes_psikologi_pendaftar_status_verifikasi_check CHECK (status_verifikasi IN ('belum_upload', 'menunggu', 'disetujui', 'ditolak'))");
        DB::statement("ALTER TABLE tes_psikologi_pendaftar ALTER COLUMN status_verifikasi SET DEFAULT 'belum_upload'");
    }
};
