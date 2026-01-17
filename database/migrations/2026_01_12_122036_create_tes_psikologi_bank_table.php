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
        Schema::create('tes_psikologi_bank', function (Blueprint $table) {
            $table->id();
            $table->string('nama_bank'); // Bank name
            $table->string('nomor_rekening'); // Account number
            $table->string('atas_nama'); // Account holder name
            $table->text('keterangan')->nullable(); // Additional notes
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
        Schema::dropIfExists('tes_psikologi_bank');
    }
};
