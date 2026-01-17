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
        Schema::create('pendidikan_lains', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftar_id')->constrained('pendaftar')->onDelete('cascade');
            $table->string('nama_lembaga', 255)->comment('Nama lembaga pendidikan');
            $table->string('jenis_lembaga', 255)->comment('Jenis lembaga pendidikan');
            $table->date('mulai_belajar')->comment('Tanggal mulai belajar');
            $table->string('frekuensi_belajar', 50)->comment('Frekuensi belajar');
            $table->text('lokasi_lembaga')->comment('Lokasi/alamat lembaga');
            $table->timestamps();

            $table->index('pendaftar_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendidikan_lains');
    }
};
