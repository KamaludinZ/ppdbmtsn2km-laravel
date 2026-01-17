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
        Schema::create('dokumen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftar_id')->constrained('pendaftar')->onDelete('cascade');
            $table->enum('jenis_dokumen', [
                'foto',
                'kk', // Kartu Keluarga
                'akta_kelahiran',
                'ijazah',
                'skhun', // Surat Keterangan Hasil Ujian Nasional
                'raport',
                'surat_keterangan_prestasi',
                'sertifikat_tahfidz',
                'lainnya'
            ]);
            $table->string('nama_file', 255);
            $table->string('path_file');
            $table->string('mime_type', 100)->nullable();
            $table->integer('ukuran_file')->nullable(); // File size in bytes
            $table->enum('status_verifikasi', ['menunggu', 'diterima', 'ditolak'])->default('menunggu');
            $table->text('catatan')->nullable(); // Admin notes
            $table->timestamp('tanggal_upload');
            $table->timestamp('tanggal_verifikasi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumen');
    }
};
