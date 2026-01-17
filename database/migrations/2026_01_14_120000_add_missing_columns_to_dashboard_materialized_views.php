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
        // Drop and recreate mv_recent_pendaftar with jalur columns
        DB::statement('DROP MATERIALIZED VIEW IF EXISTS mv_recent_pendaftar CASCADE');

        DB::statement("
            CREATE MATERIALIZED VIEW mv_recent_pendaftar AS
            SELECT
                p.id,
                p.nomor_pendaftaran,
                p.nama_lengkap,
                p.nisn,
                p.status_pendaftaran,
                p.tanggal_daftar,
                p.jalur_pendaftaran_id,
                jp.nama as jalur_nama,
                jp.warna as jalur_warna
            FROM pendaftar p
            LEFT JOIN jalur_pendaftaran jp ON p.jalur_pendaftaran_id = jp.id
            ORDER BY p.tanggal_daftar DESC
            LIMIT 10;
        ");

        DB::statement("CREATE INDEX mv_recent_pendaftar_idx ON mv_recent_pendaftar (tanggal_daftar DESC)");

        // Drop and recreate mv_jalur_stats with warna column
        DB::statement('DROP MATERIALIZED VIEW IF EXISTS mv_jalur_stats CASCADE');

        DB::statement("
            CREATE MATERIALIZED VIEW mv_jalur_stats AS
            SELECT
                j.id,
                j.nama,
                j.warna,
                COUNT(p.id) as pendaftar_count,
                SUM(CASE WHEN p.status_pendaftaran = 'data_disetujui' THEN 1 ELSE 0 END) as diterima
            FROM jalur_pendaftaran j
            LEFT JOIN pendaftar p ON j.id = p.jalur_pendaftaran_id
            WHERE j.aktif = true
            GROUP BY j.id, j.nama, j.warna, j.urutan
            ORDER BY j.urutan ASC;
        ");

        DB::statement("CREATE UNIQUE INDEX mv_jalur_stats_idx ON mv_jalur_stats (id)");

        // Refresh views to populate with current data
        DB::statement('REFRESH MATERIALIZED VIEW mv_recent_pendaftar');
        DB::statement('REFRESH MATERIALIZED VIEW mv_jalur_stats');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to previous structure without jalur columns
        DB::statement('DROP MATERIALIZED VIEW IF EXISTS mv_recent_pendaftar CASCADE');

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

        // Revert mv_jalur_stats to previous structure without warna
        DB::statement('DROP MATERIALIZED VIEW IF EXISTS mv_jalur_stats CASCADE');

        DB::statement("
            CREATE MATERIALIZED VIEW mv_jalur_stats AS
            SELECT
                j.id,
                j.nama,
                COUNT(p.id) as total_pendaftar,
                SUM(CASE WHEN p.status_pendaftaran = 'data_disetujui' THEN 1 ELSE 0 END) as diterima
            FROM jalur_pendaftaran j
            LEFT JOIN pendaftar p ON j.id = p.jalur_pendaftaran_id
            GROUP BY j.id, j.nama;
        ");
    }
};
