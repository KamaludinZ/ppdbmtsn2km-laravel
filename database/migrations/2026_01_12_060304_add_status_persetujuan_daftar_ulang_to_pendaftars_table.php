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
        Schema::table('pendaftar', function (Blueprint $table) {
            // Tambahkan kolom status persetujuan daftar ulang
            $table->enum('status_persetujuan_daftar_ulang', ['belum_memilih', 'setuju', 'tidak_setuju'])
                  ->nullable()
                  ->default('belum_memilih')
                  ->after('setuju_daftar_ulang')
                  ->comment('Status persetujuan pendaftar untuk daftar ulang');

            // Rename tanggal_setuju_daftar_ulang menjadi tanggal_pilihan_daftar_ulang
            $table->renameColumn('tanggal_setuju_daftar_ulang', 'tanggal_pilihan_daftar_ulang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pendaftar', function (Blueprint $table) {
            $table->dropColumn('status_persetujuan_daftar_ulang');
            $table->renameColumn('tanggal_pilihan_daftar_ulang', 'tanggal_setuju_daftar_ulang');
        });
    }
};
