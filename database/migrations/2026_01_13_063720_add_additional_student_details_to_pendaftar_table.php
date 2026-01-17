<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Menambahkan 8 field detail siswa tambahan yang ditemukan saat transform formulir
     */
    public function up(): void
    {
        Schema::table('pendaftar', function (Blueprint $table) {
            // Field tambahan untuk detail siswa yang lebih lengkap
            if (!Schema::hasColumn('pendaftar', 'no_akta_kelahiran')) {
                $table->string('no_akta_kelahiran', 50)->nullable()->after('nik');
            }
            if (!Schema::hasColumn('pendaftar', 'jumlah_saudara_tiri')) {
                $table->integer('jumlah_saudara_tiri')->nullable()->after('jumlah_saudara_kandung');
            }
            if (!Schema::hasColumn('pendaftar', 'golongan_darah')) {
                $table->enum('golongan_darah', ['A', 'B', 'AB', 'O', 'Tidak Tahu'])->nullable()->after('jenis_kelamin');
            }
            if (!Schema::hasColumn('pendaftar', 'tinggi_badan')) {
                $table->integer('tinggi_badan')->nullable()->after('golongan_darah')->comment('Dalam cm');
            }
            if (!Schema::hasColumn('pendaftar', 'berat_badan')) {
                $table->decimal('berat_badan', 5, 2)->nullable()->after('tinggi_badan')->comment('Dalam kg');
            }
            if (!Schema::hasColumn('pendaftar', 'bahasa_sehari_hari')) {
                $table->string('bahasa_sehari_hari', 100)->nullable()->after('berat_badan');
            }
            if (!Schema::hasColumn('pendaftar', 'yatim_piatu')) {
                $table->enum('yatim_piatu', ['Tidak', 'Yatim', 'Piatu', 'Yatim Piatu'])->default('Tidak')->after('bahasa_sehari_hari');
            }
            if (!Schema::hasColumn('pendaftar', 'keterangan_kondisi_khusus')) {
                $table->text('keterangan_kondisi_khusus')->nullable()->after('jenis_kebutuhan_khusus')
                    ->comment('Penjelasan detail untuk kondisi berkebutuhan khusus');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pendaftar', function (Blueprint $table) {
            $columnsToCheck = [
                'no_akta_kelahiran',
                'jumlah_saudara_tiri',
                'golongan_darah',
                'tinggi_badan',
                'berat_badan',
                'bahasa_sehari_hari',
                'yatim_piatu',
                'keterangan_kondisi_khusus',
            ];

            foreach ($columnsToCheck as $column) {
                if (Schema::hasColumn('pendaftar', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
