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
        Schema::create('asal_sekolah', function (Blueprint $table) {
            $table->id();
            $table->string('nama_sekolah', 255);
            $table->string('npsn', 8)->unique();
            $table->string('nsm', 12)->nullable();
            $table->string('alamat', 500);
            $table->string('kecamatan', 100);
            $table->string('kabupaten_kota', 100);
            $table->string('provinsi', 100);
            $table->string('status_sekolah', 50); // negeri/swasta
            $table->string('jenjang', 50); // SD/MI/SDLB dll
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asal_sekolah');
    }
};
