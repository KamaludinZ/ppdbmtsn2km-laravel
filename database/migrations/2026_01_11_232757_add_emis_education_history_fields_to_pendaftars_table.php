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
            // Riwayat Pendidikan TK (EMIS Kemenag 4.0)
            $table->string('tk_nama', 200)->nullable()->comment('Nama TK/RA');
            $table->string('tk_npsn', 20)->nullable()->comment('NPSN TK/RA');
            $table->integer('tk_tahun_lulus')->nullable();

            // Riwayat Pendidikan SD (EMIS Kemenag 4.0) - WAJIB
            $table->string('sd_nama', 200)->nullable()->comment('Nama SD/MI - WAJIB untuk EMIS');
            $table->string('sd_npsn', 20)->nullable()->comment('NPSN SD/MI - WAJIB untuk EMIS');
            $table->integer('sd_tahun_lulus')->nullable()->comment('Tahun lulus SD/MI - WAJIB untuk EMIS');
            $table->string('sd_nomor_peserta_ujian', 50)->nullable()->comment('Nomor peserta UN SD/MI');
            $table->string('sd_nomor_seri_ijazah', 50)->nullable()->comment('Nomor seri ijazah SD/MI');
            $table->string('sd_nomor_skhun', 50)->nullable()->comment('Nomor SKHUN SD/MI');

            // Nilai Rapor SD (rata-rata semester akhir)
            $table->decimal('sd_nilai_matematika', 5, 2)->nullable();
            $table->decimal('sd_nilai_ipa', 5, 2)->nullable();
            $table->decimal('sd_nilai_ips', 5, 2)->nullable();
            $table->decimal('sd_nilai_bahasa_indonesia', 5, 2)->nullable();
            $table->decimal('sd_nilai_bahasa_inggris', 5, 2)->nullable();

            // Data tambahan
            $table->string('hobi', 200)->nullable();
            $table->string('cita_cita', 200)->nullable();

            // Kebutuhan khusus siswa
            $table->boolean('berkebutuhan_khusus')->default(false);
            $table->json('jenis_kebutuhan_khusus')->nullable()->comment('Array of special needs');

            // Dokumen pendukung
            $table->string('file_ijazah_sd')->nullable()->comment('Path file ijazah SD');
            $table->string('file_skhun_sd')->nullable()->comment('Path file SKHUN SD');
            $table->string('file_kartu_keluarga')->nullable()->comment('Path file KK');
            $table->string('file_akta_kelahiran')->nullable()->comment('Path file akta kelahiran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pendaftar', function (Blueprint $table) {
            $table->dropColumn([
                // TK
                'tk_nama',
                'tk_npsn',
                'tk_tahun_lulus',
                // SD
                'sd_nama',
                'sd_npsn',
                'sd_tahun_lulus',
                'sd_nomor_peserta_ujian',
                'sd_nomor_seri_ijazah',
                'sd_nomor_skhun',
                'sd_nilai_matematika',
                'sd_nilai_ipa',
                'sd_nilai_ips',
                'sd_nilai_bahasa_indonesia',
                'sd_nilai_bahasa_inggris',
                // Tambahan
                'hobi',
                'cita_cita',
                'berkebutuhan_khusus',
                'jenis_kebutuhan_khusus',
                // Dokumen
                'file_ijazah_sd',
                'file_skhun_sd',
                'file_kartu_keluarga',
                'file_akta_kelahiran',
            ]);
        });
    }
};
