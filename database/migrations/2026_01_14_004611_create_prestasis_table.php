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
        Schema::create('prestasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftar_id')->constrained('pendaftar')->onDelete('cascade');
            $table->year('tahun')->comment('Tahun prestasi diraih (max 4 digit)');
            $table->string('nama_lomba', 255)->comment('Nama lomba/kompetisi');
            $table->string('bidang_lomba', 100)->comment('Akademik, Keagamaan, Teknologi, Olahraga, dll');
            $table->string('nama_penyelenggara', 255)->comment('Nama penyelenggara lomba');
            $table->string('tingkat_lomba', 50)->comment('Kabupaten/Kota, Provinsi, Nasional, Internasional, Lainnya');
            $table->string('peringkat', 100)->comment('Juara yang diraih');
            $table->string('kategori_lomba', 50)->comment('Individu atau Kelompok');
            $table->string('file_sertifikat', 255)->nullable()->comment('Path file sertifikat prestasi');
            $table->timestamps();

            // Index untuk performa query
            $table->index('pendaftar_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestasis');
    }
};
