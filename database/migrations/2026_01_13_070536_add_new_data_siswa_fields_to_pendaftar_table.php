<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Menambahkan field baru untuk Tab Data Siswa sesuai spesifikasi
     */
    public function up(): void
    {
        Schema::table('pendaftar', function (Blueprint $table) {
            // Kewarganegaraan - Nomor KITAS untuk WNA
            if (!Schema::hasColumn('pendaftar', 'nomor_kitas')) {
                $table->string('nomor_kitas', 50)->nullable()->after('negara_asal');
            }

            // Data Keluarga - Jumlah Saudara (total kandung + tiri)
            if (!Schema::hasColumn('pendaftar', 'jumlah_saudara')) {
                $table->integer('jumlah_saudara')->nullable()->after('anak_ke')->comment('Total saudara kandung dan tiri');
            }

            // Kartu Keluarga - Nama Kepala Keluarga
            if (!Schema::hasColumn('pendaftar', 'nama_kepala_keluarga')) {
                $table->string('nama_kepala_keluarga', 255)->nullable()->after('nomor_kk');
            }

            // Cita-cita
            if (!Schema::hasColumn('pendaftar', 'cita_cita')) {
                $table->string('cita_cita', 100)->nullable()->after('hobi');
            }

            // Kontak - Checkbox tidak punya HP
            if (!Schema::hasColumn('pendaftar', 'tidak_punya_hp')) {
                $table->boolean('tidak_punya_hp')->default(false)->after('no_hp');
            }

            // Yang Membiayai Sekolah
            if (!Schema::hasColumn('pendaftar', 'yang_membiayai_sekolah')) {
                $table->string('yang_membiayai_sekolah', 100)->nullable()->after('cita_cita');
            }

            // Pra Sekolah - TK/RA
            if (!Schema::hasColumn('pendaftar', 'pra_sekolah_tk')) {
                $table->boolean('pra_sekolah_tk')->default(false)->after('yang_membiayai_sekolah');
            }

            // Pra Sekolah - PAUD
            if (!Schema::hasColumn('pendaftar', 'pra_sekolah_paud')) {
                $table->boolean('pra_sekolah_paud')->default(false)->after('pra_sekolah_tk');
            }

            // Imunisasi - Hepatitis B
            if (!Schema::hasColumn('pendaftar', 'imunisasi_hepatitis_b')) {
                $table->boolean('imunisasi_hepatitis_b')->default(false)->after('pra_sekolah_paud');
            }

            // Imunisasi - BCG
            if (!Schema::hasColumn('pendaftar', 'imunisasi_bcg')) {
                $table->boolean('imunisasi_bcg')->default(false)->after('imunisasi_hepatitis_b');
            }

            // Imunisasi - DPT
            if (!Schema::hasColumn('pendaftar', 'imunisasi_dpt')) {
                $table->boolean('imunisasi_dpt')->default(false)->after('imunisasi_bcg');
            }

            // Imunisasi - Polio
            if (!Schema::hasColumn('pendaftar', 'imunisasi_polio')) {
                $table->boolean('imunisasi_polio')->default(false)->after('imunisasi_dpt');
            }

            // Imunisasi - Campak
            if (!Schema::hasColumn('pendaftar', 'imunisasi_campak')) {
                $table->boolean('imunisasi_campak')->default(false)->after('imunisasi_polio');
            }

            // Imunisasi - Covid
            if (!Schema::hasColumn('pendaftar', 'imunisasi_covid')) {
                $table->boolean('imunisasi_covid')->default(false)->after('imunisasi_campak');
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
                'nomor_kitas',
                'jumlah_saudara',
                'nama_kepala_keluarga',
                'cita_cita',
                'tidak_punya_hp',
                'yang_membiayai_sekolah',
                'pra_sekolah_tk',
                'pra_sekolah_paud',
                'imunisasi_hepatitis_b',
                'imunisasi_bcg',
                'imunisasi_dpt',
                'imunisasi_polio',
                'imunisasi_campak',
                'imunisasi_covid',
            ];

            foreach ($columnsToCheck as $column) {
                if (Schema::hasColumn('pendaftar', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
