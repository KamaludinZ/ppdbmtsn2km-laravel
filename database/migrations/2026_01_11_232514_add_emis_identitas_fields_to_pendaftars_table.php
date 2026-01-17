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
            // Data Identitas Siswa (EMIS Kemenag 4.0)
            $table->string('nik', 16)->nullable()->after('nisn')->comment('NIK 16 digit - WAJIB untuk EMIS');
            $table->string('nama_panggilan', 100)->nullable()->after('nama_lengkap');
            $table->enum('kewarganegaraan', ['WNI', 'WNA'])->default('WNI')->after('jenis_kelamin');
            $table->string('negara_asal', 100)->nullable()->after('kewarganegaraan')->comment('Diisi jika WNA');

            // Data Keluarga
            $table->string('nomor_kk', 16)->nullable()->comment('Nomor Kartu Keluarga - WAJIB untuk EMIS');
            $table->integer('anak_ke')->nullable()->comment('Anak keberapa dalam keluarga');
            $table->integer('jumlah_saudara_kandung')->nullable();

            // Data Kontak
            $table->string('nomor_telepon_rumah', 20)->nullable();
            $table->string('nomor_hp', 20)->nullable();
            $table->string('email_pribadi', 100)->nullable();

            // Data Tempat Tinggal
            $table->enum('jenis_tinggal', [
                'Bersama orang tua',
                'Wali',
                'Kost',
                'Asrama',
                'Panti asuhan',
                'Pesantren',
                'Lainnya'
            ])->nullable();

            $table->enum('alat_transportasi', [
                'Jalan kaki',
                'Sepeda',
                'Sepeda motor',
                'Mobil pribadi',
                'Antar jemput sekolah',
                'Angkutan umum',
                'Perahu/sampan',
                'Lainnya',
                'Kereta api',
                'Ojek',
                'Andong/bendi/sado/dokar/delman/becak'
            ])->nullable();

            $table->integer('jarak_tempat_tinggal')->nullable()->comment('Dalam kilometer');
            $table->integer('waktu_tempuh')->nullable()->comment('Dalam menit');

            // Kartu Program Pemerintah
            $table->boolean('penerima_kps')->default(false)->comment('Kartu Perlindungan Sosial');
            $table->string('nomor_kps', 50)->nullable();
            $table->boolean('penerima_kip')->default(false)->comment('Kartu Indonesia Pintar');
            $table->string('nomor_kip', 50)->nullable();
            $table->boolean('layak_pip')->default(false)->comment('Layak Program Indonesia Pintar');
            $table->string('alasan_layak_pip')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pendaftar', function (Blueprint $table) {
            $table->dropColumn([
                'nik',
                'nama_panggilan',
                'kewarganegaraan',
                'negara_asal',
                'nomor_kk',
                'anak_ke',
                'jumlah_saudara_kandung',
                'nomor_telepon_rumah',
                'nomor_hp',
                'email_pribadi',
                'jenis_tinggal',
                'alat_transportasi',
                'jarak_tempat_tinggal',
                'waktu_tempuh',
                'penerima_kps',
                'nomor_kps',
                'penerima_kip',
                'nomor_kip',
                'layak_pip',
                'alasan_layak_pip',
            ]);
        });
    }
};
