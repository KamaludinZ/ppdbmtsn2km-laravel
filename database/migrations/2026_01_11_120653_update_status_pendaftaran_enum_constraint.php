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
        // Step 0: Drop all dependent materialized views
        DB::statement("DROP MATERIALIZED VIEW IF EXISTS mv_dashboard_stats CASCADE");
        DB::statement("DROP MATERIALIZED VIEW IF EXISTS mv_panitia_stats CASCADE");
        DB::statement("DROP MATERIALIZED VIEW IF EXISTS mv_recent_pendaftar CASCADE");
        DB::statement("DROP MATERIALIZED VIEW IF EXISTS mv_jalur_stats CASCADE");

        // Step 1: Drop the check constraint first
        DB::statement("ALTER TABLE pendaftar DROP CONSTRAINT IF EXISTS pendaftar_status_pendaftaran_check");

        // Step 2: Change column to varchar temporarily
        DB::statement("ALTER TABLE pendaftar ALTER COLUMN status_pendaftaran TYPE VARCHAR(50)");

        // Step 3: Update existing values to new format
        DB::statement("UPDATE pendaftar SET status_pendaftaran = 'diverifikasi' WHERE status_pendaftaran = 'verifikasi'");
        DB::statement("UPDATE pendaftar SET status_pendaftaran = 'lulus' WHERE status_pendaftaran = 'diterima'");
        DB::statement("UPDATE pendaftar SET status_pendaftaran = 'tidak_lulus' WHERE status_pendaftaran = 'ditolak'");

        // Step 4: Drop default value temporarily
        DB::statement("ALTER TABLE pendaftar ALTER COLUMN status_pendaftaran DROP DEFAULT");

        // Step 5: Drop the old enum type if exists
        DB::statement("DROP TYPE IF EXISTS pendaftar_status_pendaftaran_old CASCADE");

        // Step 6: Create new enum type with updated values
        DB::statement("CREATE TYPE pendaftar_status_pendaftaran_new AS ENUM ('draft', 'menunggu', 'diverifikasi', 'lulus', 'tidak_lulus', 'cadangan')");

        // Step 7: Alter column to use new enum type
        DB::statement("ALTER TABLE pendaftar ALTER COLUMN status_pendaftaran TYPE pendaftar_status_pendaftaran_new USING status_pendaftaran::pendaftar_status_pendaftaran_new");

        // Step 8: Set default value back
        DB::statement("ALTER TABLE pendaftar ALTER COLUMN status_pendaftaran SET DEFAULT 'draft'");

        // Step 8: Recreate materialized views with updated column reference
        DB::statement("
            CREATE MATERIALIZED VIEW mv_dashboard_stats AS
            SELECT
                COUNT(*) AS total_pendaftar,
                SUM(CASE WHEN status_pendaftaran = 'menunggu' THEN 1 ELSE 0 END) AS menunggu_verifikasi,
                SUM(CASE WHEN status_pendaftaran = 'lulus' THEN 1 ELSE 0 END) AS diterima,
                SUM(CASE WHEN status_pendaftaran NOT IN ('draft') THEN 1 ELSE 0 END) AS total_submitted
            FROM pendaftar
            WHERE status_pendaftaran != 'draft';
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
            WHERE status_pendaftaran != 'draft'
            ORDER BY tanggal_daftar DESC
            LIMIT 10;
        ");

        DB::statement("
            CREATE MATERIALIZED VIEW mv_jalur_stats AS
            SELECT
                j.id,
                j.nama,
                COUNT(p.id) as total_pendaftar,
                SUM(CASE WHEN p.status_pendaftaran = 'lulus' THEN 1 ELSE 0 END) as diterima
            FROM jalur_pendaftaran j
            LEFT JOIN pendaftar p ON j.id = p.jalur_pendaftaran_id AND p.status_pendaftaran != 'draft'
            GROUP BY j.id, j.nama;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse the process
        DB::statement("ALTER TABLE pendaftar ALTER COLUMN status_pendaftaran TYPE VARCHAR(50)");

        // Revert values
        DB::statement("UPDATE pendaftar SET status_pendaftaran = 'verifikasi' WHERE status_pendaftaran = 'diverifikasi'");
        DB::statement("UPDATE pendaftar SET status_pendaftaran = 'diterima' WHERE status_pendaftaran = 'lulus'");
        DB::statement("UPDATE pendaftar SET status_pendaftaran = 'ditolak' WHERE status_pendaftaran = 'tidak_lulus'");
        DB::statement("UPDATE pendaftar SET status_pendaftaran = 'draft' WHERE status_pendaftaran = 'cadangan'");

        // Recreate old enum
        DB::statement("DROP TYPE IF EXISTS pendaftar_status_pendaftaran_old CASCADE");
        DB::statement("CREATE TYPE pendaftar_status_pendaftaran_old AS ENUM ('draft', 'menunggu', 'verifikasi', 'diterima', 'ditolak')");
        DB::statement("ALTER TABLE pendaftar ALTER COLUMN status_pendaftaran TYPE pendaftar_status_pendaftaran_old USING status_pendaftaran::pendaftar_status_pendaftaran_old");
        DB::statement("DROP TYPE IF EXISTS pendaftar_status_pendaftaran_new CASCADE");
    }
};
