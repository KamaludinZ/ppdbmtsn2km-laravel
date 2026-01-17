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
        Schema::create('timeline_pendaftaran', function (Blueprint $table) {
            $table->id();
            $table->string('judul_kegiatan', 200); // Nama kegiatan
            $table->text('deskripsi')->nullable(); // Deskripsi tambahan
            $table->date('tanggal_mulai'); // Tanggal mulai
            $table->date('tanggal_selesai'); // Tanggal selesai
            $table->time('waktu_mulai')->nullable(); // Waktu mulai
            $table->time('waktu_selesai')->nullable(); // Waktu selesai
            $table->string('tempat', 200)->nullable(); // Tempat kegiatan
            $table->string('jalur_kode', 50)->nullable(); // Kode jalur pendaftaran (prestasi, reguler, tahfidz)
            $table->string('warna', 50)->default('blue'); // Warna untuk UI
            $table->boolean('aktif')->default(true); // Status aktif/nonaktif
            $table->integer('urutan')->default(0); // Urutan tampil
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timeline_pendaftaran');
    }
};
