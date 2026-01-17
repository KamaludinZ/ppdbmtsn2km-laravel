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
        Schema::create('beasiswas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftar_id')->constrained('pendaftar')->onDelete('cascade');
            $table->year('tahun')->comment('Tahun beasiswa diterima (max 4 digit)');
            $table->string('kategori', 100)->comment('Kategori beasiswa');
            $table->string('nama_beasiswa', 255)->comment('Nama beasiswa/bantuan');
            $table->string('jenis_instansi_pemberi', 100)->comment('Jenis instansi pemberi beasiswa');
            $table->string('nama_instansi_pemberi', 255)->comment('Nama instansi pemberi');
            $table->integer('jangka_waktu_bulan')->comment('Jangka waktu dalam bulan (max 2 digit)');
            $table->decimal('nominal_beasiswa', 15, 2)->comment('Nominal beasiswa dalam rupiah');
            $table->timestamps();

            $table->index('pendaftar_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beasiswas');
    }
};
