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
        // Step 1: Check and update all NULL or empty status values to 'belum_diisi' first
        // This must be done BEFORE dropping constraints to avoid constraint violations
        
        $updated1 = DB::table('daftar_ulang')
            ->whereNull('status')
            ->orWhere('status', '')
            ->update(['status' => 'belum_diisi']);
            
        $updated2 = DB::table('seragam')
            ->whereNull('status_seragam')
            ->orWhere('status_seragam', '')
            ->update(['status_seragam' => 'belum_diisi']);
            
        $updated3 = DB::table('seragam')
            ->whereNull('status_ukuran')
            ->orWhere('status_ukuran', '')
            ->update(['status_ukuran' => 'belum_diisi']);

        // Step 2: Drop old constraints
        DB::statement('ALTER TABLE daftar_ulang DROP CONSTRAINT IF EXISTS daftar_ulang_status_check');
        DB::statement('ALTER TABLE seragam DROP CONSTRAINT IF EXISTS seragam_status_seragam_check');
        DB::statement('ALTER TABLE seragam DROP CONSTRAINT IF EXISTS seragam_status_ukuran_check');

        // Step 3: Add new constraints with 'belum_diisi' included
        DB::statement("ALTER TABLE daftar_ulang ADD CONSTRAINT daftar_ulang_status_check CHECK (status = ANY (ARRAY['belum_diisi'::character varying, 'menunggu'::character varying, 'disetujui'::character varying, 'ditolak'::character varying, 'perbaiki'::character varying, 'proses'::character varying]))");

        DB::statement("ALTER TABLE seragam ADD CONSTRAINT seragam_status_seragam_check CHECK (status_seragam = ANY (ARRAY['belum_diisi'::character varying, 'menunggu'::character varying, 'disetujui'::character varying, 'ditolak'::character varying, 'perbaiki'::character varying]))");

        DB::statement("ALTER TABLE seragam ADD CONSTRAINT seragam_status_ukuran_check CHECK (status_ukuran = ANY (ARRAY['belum_diisi'::character varying, 'menunggu'::character varying, 'disetujui'::character varying, 'ditolak'::character varying, 'perbaiki'::character varying]))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop new constraints
        DB::statement('ALTER TABLE daftar_ulang DROP CONSTRAINT IF EXISTS daftar_ulang_status_check');
        DB::statement('ALTER TABLE seragam DROP CONSTRAINT IF EXISTS seragam_status_seragam_check');
        DB::statement('ALTER TABLE seragam DROP CONSTRAINT IF EXISTS seragam_status_ukuran_check');

        // Revert to original constraints without 'belum_diisi'
        DB::statement("ALTER TABLE daftar_ulang ADD CONSTRAINT daftar_ulang_status_check CHECK (status = ANY (ARRAY['menunggu'::character varying, 'disetujui'::character varying, 'ditolak'::character varying, 'perbaiki'::character varying, 'proses'::character varying]))");

        DB::statement("ALTER TABLE seragam ADD CONSTRAINT seragam_status_seragam_check CHECK (status_seragam = ANY (ARRAY['menunggu'::character varying, 'disetujui'::character varying, 'ditolak'::character varying, 'perbaiki'::character varying]))");

        DB::statement("ALTER TABLE seragam ADD CONSTRAINT seragam_status_ukuran_check CHECK (status_ukuran = ANY (ARRAY['menunggu'::character varying, 'disetujui'::character varying, 'ditolak'::character varying, 'perbaiki'::character varying]))");
    }
};
