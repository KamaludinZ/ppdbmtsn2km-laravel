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
        // Step 1: Drop all dependent materialized views
        DB::statement("DROP MATERIALIZED VIEW IF EXISTS mv_dashboard_stats CASCADE");
        DB::statement("DROP MATERIALIZED VIEW IF EXISTS mv_panitia_stats CASCADE");
        DB::statement("DROP MATERIALIZED VIEW IF EXISTS mv_recent_pendaftar CASCADE");
        DB::statement("DROP MATERIALIZED VIEW IF EXISTS mv_jalur_stats CASCADE");

        // Step 2: Drop default value temporarily
        DB::statement("ALTER TABLE pendaftar ALTER COLUMN status_pendaftaran DROP DEFAULT");

        // Step 3: Change column to varchar temporarily
        DB::statement("ALTER TABLE pendaftar ALTER COLUMN status_pendaftaran TYPE VARCHAR(50)");

        // Step 4: Drop old enum type
        DB::statement("DROP TYPE IF EXISTS pendaftar_status_pendaftaran_final CASCADE");

        // Step 5: Create new enum type with ALL values (verification + graduation)
        // Added: lulus, tidak_lulus, cadangan, diterima, ditolak
        DB::statement("CREATE TYPE pendaftar_status_pendaftaran_final AS ENUM (
            'menunggu_verifikasi', 
            'lengkapi_data', 
            'perbaiki_data', 
            'data_ditolak', 
            'data_disetujui',
            'lulus',
            'tidak_lulus',
            'cadangan',
            'diterima',
            'ditolak'
        )");

        // Step 6: Alter column to use new enum type
        DB::statement("ALTER TABLE pendaftar ALTER COLUMN status_pendaftaran TYPE pendaftar_status_pendaftaran_final USING status_pendaftaran::pendaftar_status_pendaftaran_final");

        // Step 7: Set default value
        DB::statement("ALTER TABLE pendaftar ALTER COLUMN status_pendaftaran SET DEFAULT 'menunggu_verifikasi'");

        // Step 8: Recreate materialized views
        DB::statement("
            CREATE MATERIALIZED VIEW mv_dashboard_stats AS
            SELECT
                COUNT(*) AS total_pendaftar,
                SUM(CASE WHEN status_pendaftaran = 'menunggu_verifikasi' THEN 1 ELSE 0 END) AS menunggu_verifikasi,
                SUM(CASE WHEN status_pendaftaran = 'lengkapi_data' THEN 1 ELSE 0 END) AS lengkapi_data,
                SUM(CASE WHEN status_pendaftaran = 'perbaiki_data' THEN 1 ELSE 0 END) AS perbaiki_data,
                SUM(CASE WHEN status_pendaftaran = 'data_ditolak' THEN 1 ELSE 0 END) AS data_ditolak,
                SUM(CASE WHEN status_pendaftaran = 'data_disetujui' THEN 1 ELSE 0 END) AS data_disetujui,
                SUM(CASE WHEN status_pendaftaran IN ('lulus', 'diterima') THEN 1 ELSE 0 END) AS lulus,
                SUM(CASE WHEN status_pendaftaran IN ('tidak_lulus', 'ditolak') THEN 1 ELSE 0 END) AS tidak_lulus,
                SUM(CASE WHEN status_pendaftaran = 'cadangan' THEN 1 ELSE 0 END) AS cadangan,
                COUNT(*) AS total_submitted
            FROM pendaftar;
        ");

        DB::statement("
            CREATE MATERIALIZED VIEW mv_panitia_stats AS
            SELECT COUNT(DISTINCT id) as panitia_aktif
            FROM users
            WHERE role IN ('panitia', 'panitia_pendaftaran', 'panitia_daftarulang')
            AND aktif = true;
        ");

        DB::statement("
            CREATE MATERIALIZED VIEW mv_recent_pendaftar AS
            SELECT
                id,
                nama_lengkap,
                nomor_pendaftaran,
                status_pendaftaran,
                tanggal_daftar,
                jalur_pendaftaran_id
            FROM pendaftar
            ORDER BY tanggal_daftar DESC
            LIMIT 10;
        ");

        DB::statement("
            CREATE MATERIALIZED VIEW mv_jalur_stats AS
            SELECT
                j.id,
                j.nama,
                COUNT(p.id) as total_pendaftar,
                SUM(CASE WHEN p.status_pendaftaran IN ('data_disetujui', 'lulus', 'diterima') THEN 1 ELSE 0 END) as diterima
            FROM jalur_pendaftaran j
            LEFT JOIN pendaftar p ON j.id = p.jalur_pendaftaran_id
            GROUP BY j.id, j.nama;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We assume we don't strictly need to revert to the restricted enum in down()
        // as having more allowed values doesn't break logic usually.
        // But for correctness, we would repeat the process with the smaller list.
        // Skipping strict revert to avoid data loss if 'lulus' rows exist.
    }
};
