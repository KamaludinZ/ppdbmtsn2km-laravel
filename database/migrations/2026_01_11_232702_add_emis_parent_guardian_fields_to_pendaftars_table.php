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
            // Data Ayah (EMIS Kemenag 4.0)
            // Note: nama_ayah, pekerjaan_ayah already exist from previous migration
            $table->string('ayah_nik', 16)->nullable()->after('nama_ayah')->comment('NIK Ayah - WAJIB untuk EMIS');
            $table->integer('ayah_tahun_lahir')->nullable()->after('ayah_nik')->comment('Tahun lahir ayah');
            $table->enum('ayah_pendidikan', [
                'Tidak sekolah',
                'Putus SD',
                'SD Sederajat',
                'SMP Sederajat',
                'SMA Sederajat',
                'D1',
                'D2',
                'D3',
                'D4/S1',
                'S2',
                'S3'
            ])->nullable()->after('ayah_tahun_lahir');
            // Note: pekerjaan_ayah already exists, we'll use that
            // Note: penghasilan_ortu exists but is combined, we'll add separate ones
            $table->enum('ayah_penghasilan', [
                'Tidak berpenghasilan',
                'Kurang dari Rp. 500,000',
                'Rp. 500,000 - Rp. 999,999',
                'Rp. 1,000,000 - Rp. 1,999,999',
                'Rp. 2,000,000 - Rp. 4,999,999',
                'Rp. 5,000,000 - Rp. 20,000,000',
                'Lebih dari Rp. 20,000,000'
            ])->nullable()->after('pekerjaan_ayah');
            $table->string('ayah_no_hp', 20)->nullable()->after('ayah_penghasilan');
            $table->boolean('ayah_berkebutuhan_khusus')->default(false)->after('ayah_no_hp');

            // Data Ibu (EMIS Kemenag 4.0)
            // Note: nama_ibu, pekerjaan_ibu already exist from previous migration
            $table->string('ibu_nik', 16)->nullable()->after('nama_ibu')->comment('NIK Ibu - WAJIB untuk EMIS');
            $table->integer('ibu_tahun_lahir')->nullable()->after('ibu_nik')->comment('Tahun lahir ibu');
            $table->enum('ibu_pendidikan', [
                'Tidak sekolah',
                'Putus SD',
                'SD Sederajat',
                'SMP Sederajat',
                'SMA Sederajat',
                'D1',
                'D2',
                'D3',
                'D4/S1',
                'S2',
                'S3'
            ])->nullable()->after('ibu_tahun_lahir');
            $table->enum('ibu_penghasilan', [
                'Tidak berpenghasilan',
                'Kurang dari Rp. 500,000',
                'Rp. 500,000 - Rp. 999,999',
                'Rp. 1,000,000 - Rp. 1,999,999',
                'Rp. 2,000,000 - Rp. 4,999,999',
                'Rp. 5,000,000 - Rp. 20,000,000',
                'Lebih dari Rp. 20,000,000'
            ])->nullable()->after('pekerjaan_ibu');
            $table->string('ibu_no_hp', 20)->nullable()->after('ibu_penghasilan');
            $table->boolean('ibu_berkebutuhan_khusus')->default(false)->after('ibu_no_hp');

            // Data Wali (EMIS Kemenag 4.0) - Optional
            $table->boolean('memiliki_wali')->default(false)->comment('Apakah siswa memiliki wali');
            $table->string('wali_nik', 16)->nullable()->comment('NIK Wali');
            $table->string('wali_nama', 150)->nullable()->comment('Nama lengkap wali');
            $table->integer('wali_tahun_lahir')->nullable()->comment('Tahun lahir wali');
            $table->enum('wali_pendidikan', [
                'Tidak sekolah',
                'Putus SD',
                'SD Sederajat',
                'SMP Sederajat',
                'SMA Sederajat',
                'D1',
                'D2',
                'D3',
                'D4/S1',
                'S2',
                'S3'
            ])->nullable();
            $table->string('wali_pekerjaan', 100)->nullable();
            $table->enum('wali_penghasilan', [
                'Tidak berpenghasilan',
                'Kurang dari Rp. 500,000',
                'Rp. 500,000 - Rp. 999,999',
                'Rp. 1,000,000 - Rp. 1,999,999',
                'Rp. 2,000,000 - Rp. 4,999,999',
                'Rp. 5,000,000 - Rp. 20,000,000',
                'Lebih dari Rp. 20,000,000'
            ])->nullable();
            $table->string('wali_no_hp', 20)->nullable();
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
                'ayah_nik',
                'ayah_tahun_lahir',
                'ayah_pendidikan',
                'ayah_penghasilan',
                'ayah_no_hp',
                'ayah_berkebutuhan_khusus',
                // Ibu
                'ibu_nik',
                'ibu_tahun_lahir',
                'ibu_pendidikan',
                'ibu_penghasilan',
                'ibu_no_hp',
                'ibu_berkebutuhan_khusus',
                // Wali
                'memiliki_wali',
                'wali_nik',
                'wali_nama',
                'wali_tahun_lahir',
                'wali_pendidikan',
                'wali_pekerjaan',
                'wali_penghasilan',
                'wali_no_hp',
            ]);
        });
    }
};
