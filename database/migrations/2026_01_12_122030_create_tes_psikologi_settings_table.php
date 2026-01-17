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
        Schema::create('tes_psikologi_settings', function (Blueprint $table) {
            $table->id();
            $table->text('informasi')->nullable(); // Information text
            $table->integer('waktu_pembayaran')->default(7); // Payment deadline in days
            $table->boolean('aktif')->default(true); // Active status
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tes_psikologi_settings');
    }
};
