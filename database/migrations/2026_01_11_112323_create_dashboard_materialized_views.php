<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Materialized View 1: Dashboard Statistics
        DB::statement("
            CREATE MATERIALIZED VIEW mv_dashboard_stats AS
            SELECT
                COUNT(*) as total_pendaftar,
                COUNT(*) FILTER (WHERE status_pendaftaran = 'menunggu') as menunggu_verifikasi,
                COUNT(*) FILTER (WHERE status_pendaftaran = 'lulus') as diterima,
                COUNT(*) FILTER (WHERE status_pendaftaran = 'diverifikasi') as diverifikasi,
                COUNT(*) FILTER (WHERE status_pendaftaran = 'tidak_lulus') as tidak_lulus,
                COUNT(*) FILTER (WHERE status_pendaftaran = 'mengundurkan_diri') as mengundurkan_diri,
                NOW() as last_refreshed
            FROM pendaftar
        ");

        // Create index on materialized view
        DB::statement("CREATE UNIQUE INDEX mv_dashboard_stats_idx ON mv_dashboard_stats ((1))");

        // Materialized View 2: Jalur Statistics
        DB::statement("
            CREATE MATERIALIZED VIEW mv_jalur_stats AS
            SELECT
                jp.id,
                jp.kode,
                jp.nama,
                jp.warna,
                COUNT(p.id) as pendaftar_count,
                COUNT(p.id) FILTER (WHERE p.status_pendaftaran = 'lulus') as lulus_count,
                COUNT(p.id) FILTER (WHERE p.status_pendaftaran = 'menunggu') as menunggu_count,
                NOW() as last_refreshed
            FROM jalur_pendaftaran jp
            LEFT JOIN pendaftar p ON p.jalur_pendaftaran_id = jp.id
            WHERE jp.aktif = true
            GROUP BY jp.id, jp.kode, jp.nama, jp.warna, jp.urutan
            ORDER BY jp.urutan ASC
        ");

        // Create index on jalur stats
        DB::statement("CREATE UNIQUE INDEX mv_jalur_stats_idx ON mv_jalur_stats (id)");

        // Materialized View 3: Recent Pendaftar (for quick dashboard loading)
        DB::statement("
            CREATE MATERIALIZED VIEW mv_recent_pendaftar AS
            SELECT
                p.id,
                p.nama_lengkap,
                p.nisn,
                p.status_pendaftaran,
                p.tanggal_daftar,
                jp.nama as jalur_nama,
                jp.warna as jalur_warna,
                NOW() as last_refreshed
            FROM pendaftar p
            LEFT JOIN jalur_pendaftaran jp ON p.jalur_pendaftaran_id = jp.id
            ORDER BY p.tanggal_daftar DESC
            LIMIT 100
        ");

        // Create index on recent pendaftar
        DB::statement("CREATE INDEX mv_recent_pendaftar_idx ON mv_recent_pendaftar (tanggal_daftar DESC)");

        // Materialized View 4: Panitia Stats
        DB::statement("
            CREATE MATERIALIZED VIEW mv_panitia_stats AS
            SELECT
                COUNT(*) as total_panitia,
                COUNT(*) FILTER (WHERE aktif = true) as panitia_aktif,
                COUNT(*) FILTER (WHERE role = 'admin' AND aktif = true) as admin_aktif,
                COUNT(*) FILTER (WHERE role = 'panitia_pendaftaran' AND aktif = true) as panitia_pendaftaran_aktif,
                COUNT(*) FILTER (WHERE role = 'panitia_daftarulang' AND aktif = true) as panitia_daftarulang_aktif,
                NOW() as last_refreshed
            FROM users
            WHERE role IN ('admin', 'panitia_pendaftaran', 'panitia_daftarulang', 'panitia')
        ");

        // Create index on panitia stats
        DB::statement("CREATE UNIQUE INDEX mv_panitia_stats_idx ON mv_panitia_stats ((1))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP MATERIALIZED VIEW IF EXISTS mv_dashboard_stats CASCADE');
        DB::statement('DROP MATERIALIZED VIEW IF EXISTS mv_jalur_stats CASCADE');
        DB::statement('DROP MATERIALIZED VIEW IF EXISTS mv_recent_pendaftar CASCADE');
        DB::statement('DROP MATERIALIZED VIEW IF EXISTS mv_panitia_stats CASCADE');
    }
};
