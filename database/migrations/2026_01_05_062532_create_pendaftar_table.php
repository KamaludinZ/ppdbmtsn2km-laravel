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
        Schema::create('pendaftar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('jalur_pendaftaran_id')->constrained('jalur_pendaftaran')->onDelete('restrict');
            $table->string('nomor_pendaftaran', 50)->unique(); // PPDB-2025-001234

            // Data Pribadi
            $table->string('nisn', 10)->unique();
            $table->string('nama_lengkap', 150);
            $table->string('tempat_lahir', 100);
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('agama', 50);
            $table->text('alamat');
            $table->string('telepon', 20);
            $table->string('email', 100);
            $table->string('foto')->nullable(); // Path to photo file

            // Data Orang Tua
            $table->string('nama_ayah', 150);
            $table->string('nama_ibu', 150);
            $table->string('pekerjaan_ayah', 100)->nullable();
            $table->string('pekerjaan_ibu', 100)->nullable();
            $table->string('penghasilan_ortu', 50)->nullable();
            $table->string('telepon_ortu', 20)->nullable();

            // Data Pendidikan
            $table->string('asal_sekolah', 150);
            $table->decimal('nilai_rata_rata', 5, 2)->nullable(); // Average score
            $table->text('prestasi')->nullable(); // JSON or text for achievements

            // Status
            $table->enum('status_pendaftaran', ['draft', 'menunggu', 'verifikasi', 'diterima', 'ditolak'])->default('draft');
            $table->text('catatan_verifikasi')->nullable();
            $table->timestamp('tanggal_daftar')->nullable();
            $table->timestamp('tanggal_verifikasi')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftar');
    }
};
