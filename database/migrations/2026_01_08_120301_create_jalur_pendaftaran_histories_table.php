<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jalur_pendaftaran_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jalur_pendaftaran_id')->constrained('jalur_pendaftaran')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Siapa yang mengubah
            $table->longText('konten_html_lama')->nullable();
            $table->longText('konten_html_baru')->nullable();
            $table->string('perubahan_tipe')->default('update'); // update, create, restore
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jalur_pendaftaran_histories');
    }
};