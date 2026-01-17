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
        Schema::create('daftar_ulang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftar_id')->constrained('pendaftar')->onDelete('cascade');
            $table->enum('status', ['belum', 'proses', 'selesai'])->default('belum');
            $table->boolean('pembayaran_lunas')->default(false);
            $table->boolean('seragam_diukur')->default(false);
            $table->boolean('berkas_lengkap')->default(false);
            $table->text('catatan')->nullable();
            $table->timestamp('tanggal_daftar_ulang')->nullable();
            $table->timestamp('batas_daftar_ulang')->nullable();
            $table->foreignId('panitia_id')->nullable()->constrained('panitia')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daftar_ulang');
    }
};
