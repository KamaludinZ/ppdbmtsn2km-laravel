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
        Schema::create('infografis', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 200); // Judul infografis
            $table->text('deskripsi')->nullable(); // Deskripsi infografis
            $table->string('gambar_path', 500); // Path ke file gambar infografis
            $table->string('kategori', 100)->default('umum'); // Kategori infografis
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
        Schema::dropIfExists('infografis');
    }
};
