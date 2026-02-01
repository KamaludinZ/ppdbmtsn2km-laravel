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
        Schema::create('riwayat_pesan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftar_id')->constrained('pendaftar')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); // Admin yang kirim
            $table->enum('jenis_pesan', ['email', 'whatsapp']); // Jenis pesan
            $table->string('judul')->nullable(); // Subject untuk email
            $table->text('isi_pesan'); // Isi pesan
            $table->string('penerima'); // Email atau nomor WA
            $table->enum('status', ['pending', 'terkirim', 'gagal'])->default('pending');
            $table->text('error_message')->nullable(); // Pesan error jika gagal
            $table->timestamp('sent_at')->nullable(); // Waktu terkirim
            $table->timestamps();

            // Index untuk performa
            $table->index('pendaftar_id');
            $table->index('jenis_pesan');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_pesan');
    }
};
