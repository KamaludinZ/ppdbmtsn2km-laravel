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
        Schema::create('keahlian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftar_id')->constrained('pendaftar')->onDelete('cascade');
            $table->string('bidang_keahlian')->nullable();
            $table->string('nama_keahlian')->nullable();
            $table->string('sertifikasi')->nullable();
            $table->string('lembaga_penyelenggara')->nullable();
            $table->string('hasil_tingkat_skor')->nullable();
            $table->string('file_bukti_sertifikat')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keahlian');
    }
};
