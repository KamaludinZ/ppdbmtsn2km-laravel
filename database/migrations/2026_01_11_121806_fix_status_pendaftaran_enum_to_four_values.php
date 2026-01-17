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

        // Step 4: Update existing values - convert draft and diverifikasi to menunggu
        DB::statement("UPDATE pendaftar SET status_pendaftaran = 'menunggu' WHERE status_pendaftaran IN ('draft', 'diverifikasi')");

        // Step 5: Drop old enum type
        DB::statement("DROP TYPE IF EXISTS pendaftar_status_pendaftaran_new CASCADE");

        // Step 6: Create new enum type with only 4 values
        DB::statement("CREATE TYPE pendaftar_status_pendaftaran_final AS ENUM ('menunggu', 'lulus', 'tidak_lulus', 'cadangan')");

        // Step 7: Alter column to use new enum type
        DB::statement("ALTER TABLE pendaftar ALTER COLUMN status_pendaftaran TYPE pendaftar_status_pendaftaran_final USING status_pendaftaran::pendaftar_status_pendaftaran_final");

        // Step 8: Set default value to 'menunggu'
        DB::statement("ALTER TABLE pendaftar ALTER COLUMN status_pendaftaran SET DEFAULT 'menunggu'");

        // Step 9: Recreate materialized views with updated column reference
        DB::statement("
            CREATE MATERIALIZED VIEW mv_dashboard_stats AS
            SELECT
                COUNT(*) AS total_pendaftar,
                SUM(CASE WHEN status_pendaftaran = 'menunggu' THEN 1 ELSE 0 END) AS menunggu_verifikasi,
                SUM(CASE WHEN status_pendaftaran = 'lulus' THEN 1 ELSE 0 END) AS diterima,
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
                SUM(CASE WHEN p.status_pendaftaran = 'lulus' THEN 1 ELSE 0 END) as diterima
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
        // Drop materialized views
        DB::statement("DROP MATERIALIZED VIEW IF EXISTS mv_dashboard_stats CASCADE");
        DB::statement("DROP MATERIALIZED VIEW IF EXISTS mv_panitia_stats CASCADE");
        DB::statement("DROP MATERIALIZED VIEW IF EXISTS mv_recent_pendaftar CASCADE");
        DB::statement("DROP MATERIALIZED VIEW IF EXISTS mv_jalur_stats CASCADE");

        // Revert to previous enum
        DB::statement("ALTER TABLE pendaftar ALTER COLUMN status_pendaftaran DROP DEFAULT");
        DB::statement("ALTER TABLE pendaftar ALTER COLUMN status_pendaftaran TYPE VARCHAR(50)");
        DB::statement("DROP TYPE IF EXISTS pendaftar_status_pendaftaran_final CASCADE");
        DB::statement("CREATE TYPE pendaftar_status_pendaftaran_new AS ENUM ('draft', 'menunggu', 'diverifikasi', 'lulus', 'tidak_lulus', 'cadangan')");
        DB::statement("ALTER TABLE pendaftar ALTER COLUMN status_pendaftaran TYPE pendaftar_status_pendaftaran_new USING status_pendaftaran::pendaftar_status_pendaftaran_new");
        DB::statement("ALTER TABLE pendaftar ALTER COLUMN status_pendaftaran SET DEFAULT 'draft'");

        // Recreate materialized views
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
};
