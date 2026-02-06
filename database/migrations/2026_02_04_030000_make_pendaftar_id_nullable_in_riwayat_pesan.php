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
        Schema::table('riwayat_pesan', function (Blueprint $table) {
            // Drop foreign key constraint terlebih dahulu
            $table->dropForeign(['pendaftar_id']);

            // Ubah kolom pendaftar_id menjadi nullable
            $table->foreignId('pendaftar_id')->nullable()->change();

            // Tambahkan kembali foreign key dengan nullable
            $table->foreign('pendaftar_id')
                  ->references('id')
                  ->on('pendaftar')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('riwayat_pesan', function (Blueprint $table) {
            // Drop foreign key
            $table->dropForeign(['pendaftar_id']);

            // Kembalikan ke NOT NULL
            $table->foreignId('pendaftar_id')->nullable(false)->change();

            // Tambahkan kembali foreign key
            $table->foreign('pendaftar_id')
                  ->references('id')
                  ->on('pendaftar')
                  ->onDelete('cascade');
        });
    }
};
