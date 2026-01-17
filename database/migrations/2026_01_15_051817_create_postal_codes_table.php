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
        Schema::create('postal_codes', function (Blueprint $table) {
            $table->id();
            $table->string('urban', 100); // Kelurahan/Desa
            $table->string('sub_district', 100); // Kecamatan
            $table->string('city', 100); // Kabupaten/Kota
            $table->integer('province_code'); // Kode Provinsi (11-92)
            $table->string('postal_code', 5); // Kode Pos (5 digits)

            // Indexes for faster search
            $table->index(['urban', 'sub_district', 'city']);
            $table->index('postal_code');
            $table->index('province_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('postal_codes');
    }
};
