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
        Schema::create('statistik_tahun', function (Blueprint $table) {
            $table->id();
            $table->year('tahun')->unique();
            $table->integer('total_kuota')->default(0);
            $table->integer('total_pendaftar')->default(0);
            $table->integer('menunggu')->default(0);
            $table->integer('lengkapi')->default(0);
            $table->integer('perbaiki')->default(0);
            $table->integer('ditolak')->default(0);
            $table->integer('disetujui')->default(0);
            $table->integer('lulus')->default(0);
            $table->integer('tidak_lulus')->default(0);
            $table->integer('cadangan')->default(0);
            $table->integer('laki_laki')->default(0);
            $table->integer('perempuan')->default(0);
            $table->integer('madrasah')->default(0);
            $table->integer('umum')->default(0);
            $table->timestamps();
        });

        Schema::create('statistik_jalur_tahun', function (Blueprint $table) {
            $table->id();
            $table->foreignId('statistik_tahun_id')->constrained('statistik_tahun')->onDelete('cascade');
            $table->string('nama_jalur');
            $table->string('warna')->nullable();
            $table->integer('kuota')->default(0);
            $table->integer('total')->default(0);
            $table->integer('menunggu')->default(0);
            $table->integer('lengkapi')->default(0);
            $table->integer('perbaiki')->default(0);
            $table->integer('ditolak')->default(0);
            $table->integer('disetujui')->default(0);
            $table->integer('lulus')->default(0);
            $table->integer('tidak_lulus')->default(0);
            $table->integer('cadangan')->default(0);
            $table->integer('laki_laki')->default(0);
            $table->integer('perempuan')->default(0);
            $table->integer('madrasah')->default(0);
            $table->integer('umum')->default(0);
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('statistik_jalur_tahun');
        Schema::dropIfExists('statistik_tahun');
    }
};
