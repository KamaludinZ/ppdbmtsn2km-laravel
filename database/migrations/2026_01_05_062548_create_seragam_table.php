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
        Schema::create('seragam', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftar_id')->constrained('pendaftar')->onDelete('cascade');

            // Ukuran Badan
            $table->integer('tinggi_badan')->nullable(); // cm
            $table->integer('berat_badan')->nullable(); // kg
            $table->string('ukuran_baju', 10)->nullable(); // S, M, L, XL, XXL
            $table->string('ukuran_celana', 10)->nullable(); // S, M, L, XL, XXL
            $table->integer('lingkar_dada')->nullable(); // cm
            $table->integer('lingkar_pinggang')->nullable(); // cm
            $table->integer('panjang_lengan')->nullable(); // cm

            // Seragam Details
            $table->string('ukuran_sepatu', 5)->nullable(); // 38, 39, 40, etc
            $table->string('ukuran_topi', 5)->nullable(); // S, M, L

            // Status
            $table->boolean('sudah_diukur')->default(false);
            $table->boolean('sudah_dipesan')->default(false);
            $table->boolean('sudah_diterima')->default(false);
            $table->text('catatan')->nullable();
            $table->timestamp('tanggal_ukur')->nullable();
            $table->timestamp('tanggal_pesan')->nullable();
            $table->timestamp('tanggal_terima')->nullable();
            $table->foreignId('petugas_id')->nullable()->constrained('panitia')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seragam');
    }
};
