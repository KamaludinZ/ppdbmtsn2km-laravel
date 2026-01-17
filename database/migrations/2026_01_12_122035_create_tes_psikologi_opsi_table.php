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
        Schema::create('tes_psikologi_opsi', function (Blueprint $table) {
            $table->id();
            $table->string('nama'); // Option name
            $table->text('deskripsi')->nullable(); // Option description
            $table->integer('urutan')->default(0); // Sort order
            $table->boolean('aktif')->default(true); // Active status
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tes_psikologi_opsi');
    }
};
