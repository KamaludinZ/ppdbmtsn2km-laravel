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
            // Tambahkan kembali kolom data orang tua setelah kolom foto
            $table->string('nama_ayah', 150)->nullable()->after('foto');
            $table->string('nama_ibu', 150)->nullable()->after('nama_ayah');
            $table->string('pekerjaan_ayah', 100)->nullable()->after('nama_ibu');
            $table->string('pekerjaan_ibu', 100)->nullable()->after('pekerjaan_ayah');
            $table->string('penghasilan_ortu', 50)->nullable()->after('pekerjaan_ibu');
            $table->string('telepon_ortu', 20)->nullable()->after('penghasilan_ortu');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pendaftar', function (Blueprint $table) {
            $table->dropColumn([
                'nama_ayah',
                'nama_ibu',
                'pekerjaan_ayah',
                'pekerjaan_ibu',
                'penghasilan_ortu',
                'telepon_ortu'
            ]);
        });
    }
};
