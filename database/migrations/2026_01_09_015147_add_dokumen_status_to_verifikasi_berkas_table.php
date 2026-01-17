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
        Schema::table('verifikasi_berkas', function (Blueprint $table) {
            // Status untuk setiap dokumen: belum_upload, menunggu_verifikasi, tidak_sesuai, perlu_perbaikan, disetujui
            $table->enum('status_rapor', ['belum_upload', 'menunggu_verifikasi', 'tidak_sesuai', 'perlu_perbaikan', 'disetujui'])->default('belum_upload')->after('status');
            $table->text('catatan_rapor')->nullable()->after('status_rapor');

            $table->enum('status_akte', ['belum_upload', 'menunggu_verifikasi', 'tidak_sesuai', 'perlu_perbaikan', 'disetujui'])->default('belum_upload')->after('catatan_rapor');
            $table->text('catatan_akte')->nullable()->after('status_akte');

            $table->enum('status_kk', ['belum_upload', 'menunggu_verifikasi', 'tidak_sesuai', 'perlu_perbaikan', 'disetujui'])->default('belum_upload')->after('catatan_akte');
            $table->text('catatan_kk')->nullable()->after('status_kk');

            $table->enum('status_nisn', ['belum_upload', 'menunggu_verifikasi', 'tidak_sesuai', 'perlu_perbaikan', 'disetujui'])->default('belum_upload')->after('catatan_kk');
            $table->text('catatan_nisn')->nullable()->after('status_nisn');

            $table->enum('status_ijazah', ['belum_upload', 'menunggu_verifikasi', 'tidak_sesuai', 'perlu_perbaikan', 'disetujui'])->default('belum_upload')->after('catatan_nisn');
            $table->text('catatan_ijazah')->nullable()->after('status_ijazah');

            $table->enum('status_sertifikat', ['belum_upload', 'menunggu_verifikasi', 'tidak_sesuai', 'perlu_perbaikan', 'disetujui'])->default('belum_upload')->after('catatan_ijazah');
            $table->text('catatan_sertifikat')->nullable()->after('status_sertifikat');

            $table->enum('status_kip', ['belum_upload', 'menunggu_verifikasi', 'tidak_sesuai', 'perlu_perbaikan', 'disetujui'])->default('belum_upload')->after('catatan_sertifikat');
            $table->text('catatan_kip')->nullable()->after('status_kip');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('verifikasi_berkas', function (Blueprint $table) {
            $table->dropColumn([
                'status_rapor', 'catatan_rapor',
                'status_akte', 'catatan_akte',
                'status_kk', 'catatan_kk',
                'status_nisn', 'catatan_nisn',
                'status_ijazah', 'catatan_ijazah',
                'status_sertifikat', 'catatan_sertifikat',
                'status_kip', 'catatan_kip',
            ]);
        });
    }
};
