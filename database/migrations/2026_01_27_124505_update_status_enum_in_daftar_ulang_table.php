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
        // For PostgreSQL, we need to use raw SQL to change enum constraint
        DB::statement("ALTER TABLE daftar_ulang DROP CONSTRAINT IF EXISTS daftar_ulang_status_check");
        DB::statement("ALTER TABLE daftar_ulang ADD CONSTRAINT daftar_ulang_status_check CHECK (status IN ('belum', 'proses', 'selesai', 'disetujui', 'ditolak', 'perbaiki', 'menunggu', 'batal'))");

        // Update existing 'belum' values to remain as is (they're valid)
        // Update any 'proses' to 'menunggu' for consistency
        DB::statement("UPDATE daftar_ulang SET status = 'menunggu' WHERE status = 'proses'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert status values back
        DB::statement("UPDATE daftar_ulang SET status = 'proses' WHERE status = 'menunggu'");

        // Restore original constraint
        DB::statement("ALTER TABLE daftar_ulang DROP CONSTRAINT IF EXISTS daftar_ulang_status_check");
        DB::statement("ALTER TABLE daftar_ulang ADD CONSTRAINT daftar_ulang_status_check CHECK (status IN ('belum', 'proses', 'selesai'))");
    }
};
