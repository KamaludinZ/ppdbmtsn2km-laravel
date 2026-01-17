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
        Schema::create('video_panduan', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 200); // Judul video
            $table->text('deskripsi')->nullable(); // Deskripsi video
            $table->string('url_video', 500); // URL video YouTube
            $table->string('kategori', 100)->default('lainnya'); // Kategori video (profil, panduan, dll)
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
        Schema::dropIfExists('video_panduan');
    }
};
