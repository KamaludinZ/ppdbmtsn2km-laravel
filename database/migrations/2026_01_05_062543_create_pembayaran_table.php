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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftar_id')->constrained('pendaftar')->onDelete('cascade');
            $table->enum('jenis_pembayaran', ['pendaftaran', 'daftar_ulang', 'spp']);
            $table->decimal('jumlah', 15, 2); // Payment amount
            $table->integer('cicilan_ke')->nullable(); // Installment number (1, 2, 3, etc)
            $table->integer('total_cicilan')->nullable(); // Total installments
            $table->enum('metode_pembayaran', ['tunai', 'transfer', 'virtual_account', 'lainnya'])->nullable();
            $table->string('nomor_referensi', 100)->nullable(); // Payment reference number
            $table->enum('status', ['menunggu', 'lunas', 'ditolak'])->default('menunggu');
            $table->text('catatan')->nullable();
            $table->string('bukti_pembayaran')->nullable(); // Path to payment proof file
            $table->timestamp('tanggal_bayar')->nullable();
            $table->timestamp('tanggal_verifikasi')->nullable();
            $table->foreignId('verifikator_id')->nullable()->constrained('panitia')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
