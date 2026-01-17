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
        Schema::create('jalur_pendaftaran', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100); // Prestasi, Reguler, Tahfidz
            $table->string('kode', 50)->unique(); // prestasi, reguler, tahfidz
            $table->text('deskripsi')->nullable();
            $table->integer('kuota')->default(0);
            $table->json('persyaratan')->nullable(); // Requirements as JSON
            $table->string('warna', 50)->default('blue'); // Color for UI badge
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jalur_pendaftaran');
    }
};
