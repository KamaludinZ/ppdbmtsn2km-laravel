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
            // ======= AYAH KANDUNG ADDRESS =======
            $table->boolean('ayah_tinggal_luar_negeri')->default(false)->after('ayah_penghasilan');
            $table->string('ayah_status_kepemilikan_rumah')->nullable()->after('ayah_tinggal_luar_negeri');
            $table->string('ayah_provinsi')->nullable()->after('ayah_status_kepemilikan_rumah');
            $table->string('ayah_kabupaten')->nullable()->after('ayah_provinsi');
            $table->string('ayah_kecamatan')->nullable()->after('ayah_kabupaten');
            $table->string('ayah_kelurahan')->nullable()->after('ayah_kecamatan');
            $table->string('ayah_rt', 3)->nullable()->after('ayah_kelurahan');
            $table->string('ayah_rw', 3)->nullable()->after('ayah_rt');
            $table->string('ayah_kode_pos', 6)->nullable()->after('ayah_rw');

            // ======= IBU KANDUNG ADDRESS =======
            $table->boolean('ibu_tinggal_luar_negeri')->default(false)->after('ibu_penghasilan');
            $table->boolean('ibu_sama_dengan_ayah')->default(false)->after('ibu_tinggal_luar_negeri');
            $table->string('ibu_status_kepemilikan_rumah')->nullable()->after('ibu_sama_dengan_ayah');
            $table->string('ibu_provinsi')->nullable()->after('ibu_status_kepemilikan_rumah');
            $table->string('ibu_kabupaten')->nullable()->after('ibu_provinsi');
            $table->string('ibu_kecamatan')->nullable()->after('ibu_kabupaten');
            $table->string('ibu_kelurahan')->nullable()->after('ibu_kecamatan');
            $table->string('ibu_rt', 3)->nullable()->after('ibu_kelurahan');
            $table->string('ibu_rw', 3)->nullable()->after('ibu_rt');
            $table->string('ibu_kode_pos', 6)->nullable()->after('ibu_rw');

            // ======= WALI ADDRESS =======
            $table->string('wali_status_wali')->nullable()->after('wali_pekerjaan')->comment('sama_dengan_ayah, sama_dengan_ibu, lainnya');
            $table->boolean('wali_tinggal_luar_negeri')->default(false)->after('wali_status_wali');
            $table->boolean('wali_sama_dengan_ayah')->default(false)->after('wali_tinggal_luar_negeri');
            $table->string('wali_status_kepemilikan_rumah')->nullable()->after('wali_sama_dengan_ayah');
            $table->string('wali_provinsi')->nullable()->after('wali_status_kepemilikan_rumah');
            $table->string('wali_kabupaten')->nullable()->after('wali_provinsi');
            $table->string('wali_kecamatan')->nullable()->after('wali_kabupaten');
            $table->string('wali_kelurahan')->nullable()->after('wali_kecamatan');
            $table->string('wali_rt', 3)->nullable()->after('wali_kelurahan');
            $table->string('wali_rw', 3)->nullable()->after('wali_rt');
            $table->string('wali_kode_pos', 6)->nullable()->after('wali_rw');

            // ======= SISWA ADDRESS =======
            $table->string('status_tempat_tinggal')->nullable()->after('dusun');
            $table->string('siswa_status_kepemilikan_rumah')->nullable()->after('status_tempat_tinggal');
            $table->string('siswa_provinsi')->nullable()->after('siswa_status_kepemilikan_rumah');
            $table->string('siswa_kabupaten')->nullable()->after('siswa_provinsi');
            $table->string('siswa_kecamatan')->nullable()->after('siswa_kabupaten');
            $table->string('siswa_kelurahan')->nullable()->after('siswa_kecamatan');
            $table->string('siswa_rt', 3)->nullable()->after('siswa_kelurahan');
            $table->string('siswa_rw', 3)->nullable()->after('siswa_rt');
            $table->string('siswa_kode_pos', 6)->nullable()->after('siswa_rw');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pendaftar', function (Blueprint $table) {
            $table->dropColumn([
                // Ayah
                'ayah_tinggal_luar_negeri',
                'ayah_status_kepemilikan_rumah',
                'ayah_provinsi',
                'ayah_kabupaten',
                'ayah_kecamatan',
                'ayah_kelurahan',
                'ayah_rt',
                'ayah_rw',
                'ayah_kode_pos',
                // Ibu
                'ibu_tinggal_luar_negeri',
                'ibu_sama_dengan_ayah',
                'ibu_status_kepemilikan_rumah',
                'ibu_provinsi',
                'ibu_kabupaten',
                'ibu_kecamatan',
                'ibu_kelurahan',
                'ibu_rt',
                'ibu_rw',
                'ibu_kode_pos',
                // Wali
                'wali_status_wali',
                'wali_tinggal_luar_negeri',
                'wali_sama_dengan_ayah',
                'wali_status_kepemilikan_rumah',
                'wali_provinsi',
                'wali_kabupaten',
                'wali_kecamatan',
                'wali_kelurahan',
                'wali_rt',
                'wali_rw',
                'wali_kode_pos',
                // Siswa
                'status_tempat_tinggal',
                'siswa_status_kepemilikan_rumah',
                'siswa_provinsi',
                'siswa_kabupaten',
                'siswa_kecamatan',
                'siswa_kelurahan',
                'siswa_rt',
                'siswa_rw',
                'siswa_kode_pos',
            ]);
        });
    }
};
