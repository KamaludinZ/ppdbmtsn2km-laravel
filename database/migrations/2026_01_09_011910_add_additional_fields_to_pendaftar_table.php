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
        Schema::table('pendaftar', function (Blueprint $table) {
            // Tambahkan kolom diperbarui_oleh untuk tracking perubahan status
            $table->foreignId('diperbarui_oleh')->nullable()->after('status_pendaftaran')->constrained('users')->onDelete('set null');

            // Tambahkan kolom jenis sekolah asal jika belum ada
            if (!Schema::hasColumn('pendaftar', 'jenis_sekolah_asal')) {
                $table->enum('jenis_sekolah_asal', ['madrasah', 'umum'])->nullable()->after('asal_sekolah');
            }

            // Tambahkan kolom NSM jika belum ada
            if (!Schema::hasColumn('pendaftar', 'nsm_sekolah_asal')) {
                $table->string('nsm_sekolah_asal', 20)->nullable()->after('jenis_sekolah_asal');
            }

            // Tambahkan kolom NPSN jika belum ada
            if (!Schema::hasColumn('pendaftar', 'npsn_sekolah_asal')) {
                $table->string('npsn_sekolah_asal', 20)->nullable()->after('nsm_sekolah_asal');
            }

            // Tambahkan alamat sekolah jika belum ada
            if (!Schema::hasColumn('pendaftar', 'alamat_sekolah')) {
                $table->text('alamat_sekolah')->nullable()->after('npsn_sekolah_asal');
            }

            // Tambahkan no_hp jika belum ada (untuk nomor HP siswa)
            if (!Schema::hasColumn('pendaftar', 'no_hp')) {
                $table->string('no_hp', 20)->nullable()->after('email');
            }

            // Update enum status_pendaftaran untuk menambahkan status yang sesuai
            // Note: Ini akan error jika enum sudah digunakan, jadi kita skip untuk sekarang
            // dan akan handle di level aplikasi
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pendaftar', function (Blueprint $table) {
            $table->dropForeign(['diperbarui_oleh']);
            $table->dropColumn('diperbarui_oleh');

            if (Schema::hasColumn('pendaftar', 'jenis_sekolah_asal')) {
                $table->dropColumn('jenis_sekolah_asal');
            }
            if (Schema::hasColumn('pendaftar', 'nsm_sekolah_asal')) {
                $table->dropColumn('nsm_sekolah_asal');
            }
            if (Schema::hasColumn('pendaftar', 'npsn_sekolah_asal')) {
                $table->dropColumn('npsn_sekolah_asal');
            }
            if (Schema::hasColumn('pendaftar', 'alamat_sekolah')) {
                $table->dropColumn('alamat_sekolah');
            }
            if (Schema::hasColumn('pendaftar', 'no_hp')) {
                $table->dropColumn('no_hp');
            }
        });
    }
};
