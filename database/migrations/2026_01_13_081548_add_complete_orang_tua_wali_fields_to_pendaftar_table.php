<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Menambahkan field lengkap untuk Data Orang Tua & Wali
     */
    public function up(): void
    {
        Schema::table('pendaftar', function (Blueprint $table) {
            // ===== AYAH KANDUNG =====
            if (!Schema::hasColumn('pendaftar', 'ayah_status')) {
                $table->string('ayah_status', 50)->nullable()->after('nama_ayah')
                    ->comment('masih_hidup, meninggal, tidak_diketahui');
            }
            if (!Schema::hasColumn('pendaftar', 'ayah_kewarganegaraan')) {
                $table->string('ayah_kewarganegaraan', 10)->nullable()->after('ayah_status')
                    ->comment('WNI, WNA');
            }
            if (!Schema::hasColumn('pendaftar', 'ayah_negara_asal')) {
                $table->string('ayah_negara_asal', 100)->nullable()->after('ayah_nik');
            }
            if (!Schema::hasColumn('pendaftar', 'ayah_nomor_kitas')) {
                $table->string('ayah_nomor_kitas', 50)->nullable()->after('ayah_negara_asal');
            }
            if (!Schema::hasColumn('pendaftar', 'ayah_tempat_lahir')) {
                $table->string('ayah_tempat_lahir', 255)->nullable()->after('ayah_nomor_kitas');
            }
            if (!Schema::hasColumn('pendaftar', 'ayah_tanggal_lahir')) {
                $table->date('ayah_tanggal_lahir')->nullable()->after('ayah_tempat_lahir');
            }
            if (!Schema::hasColumn('pendaftar', 'ayah_pekerjaan')) {
                $table->string('ayah_pekerjaan', 100)->nullable()->after('ayah_pendidikan');
            }
            if (!Schema::hasColumn('pendaftar', 'ayah_tidak_punya_hp')) {
                $table->boolean('ayah_tidak_punya_hp')->default(false)->after('ayah_no_hp');
            }

            // ===== IBU KANDUNG =====
            if (!Schema::hasColumn('pendaftar', 'ibu_status')) {
                $table->string('ibu_status', 50)->nullable()->after('nama_ibu')
                    ->comment('masih_hidup, meninggal, tidak_diketahui');
            }
            if (!Schema::hasColumn('pendaftar', 'ibu_kewarganegaraan')) {
                $table->string('ibu_kewarganegaraan', 10)->nullable()->after('ibu_status')
                    ->comment('WNI, WNA');
            }
            if (!Schema::hasColumn('pendaftar', 'ibu_negara_asal')) {
                $table->string('ibu_negara_asal', 100)->nullable()->after('ibu_nik');
            }
            if (!Schema::hasColumn('pendaftar', 'ibu_nomor_kitas')) {
                $table->string('ibu_nomor_kitas', 50)->nullable()->after('ibu_negara_asal');
            }
            if (!Schema::hasColumn('pendaftar', 'ibu_tempat_lahir')) {
                $table->string('ibu_tempat_lahir', 255)->nullable()->after('ibu_nomor_kitas');
            }
            if (!Schema::hasColumn('pendaftar', 'ibu_tanggal_lahir')) {
                $table->date('ibu_tanggal_lahir')->nullable()->after('ibu_tempat_lahir');
            }
            if (!Schema::hasColumn('pendaftar', 'ibu_pekerjaan')) {
                $table->string('ibu_pekerjaan', 100)->nullable()->after('ibu_pendidikan');
            }
            if (!Schema::hasColumn('pendaftar', 'ibu_tidak_punya_hp')) {
                $table->boolean('ibu_tidak_punya_hp')->default(false)->after('ibu_no_hp');
            }

            // ===== DATA WALI =====
            if (!Schema::hasColumn('pendaftar', 'wali_hubungan')) {
                $table->string('wali_hubungan', 50)->nullable()->after('wali_nama')
                    ->comment('sama_ayah, sama_ibu, lainnya');
            }
            if (!Schema::hasColumn('pendaftar', 'wali_status')) {
                $table->string('wali_status', 50)->nullable()->after('wali_hubungan')
                    ->comment('masih_hidup, meninggal, tidak_diketahui');
            }
            if (!Schema::hasColumn('pendaftar', 'wali_kewarganegaraan')) {
                $table->string('wali_kewarganegaraan', 10)->nullable()->after('wali_status')
                    ->comment('WNI, WNA');
            }
            if (!Schema::hasColumn('pendaftar', 'wali_negara_asal')) {
                $table->string('wali_negara_asal', 100)->nullable()->after('wali_nik');
            }
            if (!Schema::hasColumn('pendaftar', 'wali_nomor_kitas')) {
                $table->string('wali_nomor_kitas', 50)->nullable()->after('wali_negara_asal');
            }
            if (!Schema::hasColumn('pendaftar', 'wali_tempat_lahir')) {
                $table->string('wali_tempat_lahir', 255)->nullable()->after('wali_nomor_kitas');
            }
            if (!Schema::hasColumn('pendaftar', 'wali_tanggal_lahir')) {
                $table->date('wali_tanggal_lahir')->nullable()->after('wali_tempat_lahir');
            }
            if (!Schema::hasColumn('pendaftar', 'wali_tidak_punya_hp')) {
                $table->boolean('wali_tidak_punya_hp')->default(false)->after('wali_no_hp');
            }

            // ===== KARTU BANTUAN =====
            if (!Schema::hasColumn('pendaftar', 'nomor_kks')) {
                $table->string('nomor_kks', 50)->nullable()->after('wali_tidak_punya_hp')
                    ->comment('Kartu Keluarga Sejahtera');
            }
            if (!Schema::hasColumn('pendaftar', 'nomor_pkh')) {
                $table->string('nomor_pkh', 50)->nullable()->after('nomor_kks')
                    ->comment('Program Keluarga Harapan');
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
                'ayah_status',
                'ayah_kewarganegaraan',
                'ayah_negara_asal',
                'ayah_nomor_kitas',
                'ayah_tempat_lahir',
                'ayah_tanggal_lahir',
                'ayah_pekerjaan',
                'ayah_tidak_punya_hp',
                'ibu_status',
                'ibu_kewarganegaraan',
                'ibu_negara_asal',
                'ibu_nomor_kitas',
                'ibu_tempat_lahir',
                'ibu_tanggal_lahir',
                'ibu_pekerjaan',
                'ibu_tidak_punya_hp',
                'wali_hubungan',
                'wali_status',
                'wali_kewarganegaraan',
                'wali_negara_asal',
                'wali_nomor_kitas',
                'wali_tempat_lahir',
                'wali_tanggal_lahir',
                'wali_tidak_punya_hp',
                'nomor_kks',
                'nomor_pkh',
            ];

            foreach ($columnsToCheck as $column) {
                if (Schema::hasColumn('pendaftar', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
