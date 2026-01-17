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
        Schema::create('tes_psikologi_pendaftar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftar_id')->constrained('pendaftar')->onDelete('cascade'); // Link to pendaftar
            $table->foreignId('bank_id')->nullable()->constrained('tes_psikologi_bank')->onDelete('set null'); // Selected bank
            $table->string('bukti_transfer')->nullable(); // Payment proof file path
            $table->timestamp('tanggal_upload')->nullable(); // Upload date
            $table->enum('status_verifikasi', ['belum_upload', 'menunggu', 'disetujui', 'ditolak'])->default('belum_upload'); // Verification status
            $table->text('catatan_verifikasi')->nullable(); // Verification notes
            $table->timestamp('tanggal_verifikasi')->nullable(); // Verification date
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null'); // Verified by user
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tes_psikologi_pendaftar');
    }
};
