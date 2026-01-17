<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Re-add unique constraint to users.email
        Schema::table('users', function (Blueprint $table) {
            $table->unique('email');
        });

        // Add performance indexes to pendaftar table
        Schema::table('pendaftar', function (Blueprint $table) {
            $table->index('nama_lengkap', 'idx_pendaftar_nama');
            $table->index('status_pendaftaran', 'idx_pendaftar_status');
            $table->index('asal_kabkota', 'idx_pendaftar_kabkota');
            $table->index(['jalur_pendaftaran_id', 'status_pendaftaran'], 'idx_pendaftar_jalur_status');
            $table->index('created_at', 'idx_pendaftar_created');
        });

        // Add indexes to dokumen table
        Schema::table('dokumen', function (Blueprint $table) {
            $table->index('status_verifikasi', 'idx_dokumen_status');
            $table->index('pendaftar_id', 'idx_dokumen_pendaftar');
        });

        // Add indexes to verifikasi_berkas table
        Schema::table('verifikasi_berkas', function (Blueprint $table) {
            $table->index('status', 'idx_verifikasi_status');
            $table->index('pendaftar_id', 'idx_verifikasi_pendaftar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove unique constraint from users.email
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['email']);
        });

        // Remove indexes from pendaftar
        Schema::table('pendaftar', function (Blueprint $table) {
            $table->dropIndex('idx_pendaftar_nama');
            $table->dropIndex('idx_pendaftar_status');
            $table->dropIndex('idx_pendaftar_kabkota');
            $table->dropIndex('idx_pendaftar_jalur_status');
            $table->dropIndex('idx_pendaftar_created');
        });

        // Remove indexes from dokumen
        Schema::table('dokumen', function (Blueprint $table) {
            $table->dropIndex('idx_dokumen_status');
            $table->dropIndex('idx_dokumen_pendaftar');
        });

        // Remove indexes from verifikasi_berkas
        Schema::table('verifikasi_berkas', function (Blueprint $table) {
            $table->dropIndex('idx_verifikasi_status');
            $table->dropIndex('idx_verifikasi_pendaftar');
        });
    }
};
