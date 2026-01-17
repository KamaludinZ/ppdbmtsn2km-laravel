<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Migration ini memverifikasi dan menambahkan field EMIS yang mungkin belum ada
     * Berdasarkan Dokumentasi Field EMIS Siswa.pdf
     */
    public function up(): void
    {
        Schema::table('pendaftar', function (Blueprint $table) {
            // Check dan tambahkan field yang belum ada
            // Menggunakan hasColumn untuk menghindari error jika field sudah ada

            // Data Identitas Siswa
            if (!Schema::hasColumn('pendaftar', 'nik')) {
                $table->string('nik', 16)->nullable()->after('nisn');
            }
            if (!Schema::hasColumn('pendaftar', 'nama_panggilan')) {
                $table->string('nama_panggilan', 100)->nullable()->after('nama_lengkap');
            }
            if (!Schema::hasColumn('pendaftar', 'kewarganegaraan')) {
                $table->enum('kewarganegaraan', ['WNI', 'WNA'])->default('WNI')->after('agama');
            }
            if (!Schema::hasColumn('pendaftar', 'negara_asal')) {
                $table->string('negara_asal', 100)->nullable()->after('kewarganegaraan')->comment('Khusus untuk WNA');
            }

            // Data Keluarga
            if (!Schema::hasColumn('pendaftar', 'nomor_kk')) {
                $table->string('nomor_kk', 16)->nullable()->after('negara_asal');
            }
            if (!Schema::hasColumn('pendaftar', 'anak_ke')) {
                $table->integer('anak_ke')->nullable()->after('nomor_kk');
            }
            if (!Schema::hasColumn('pendaftar', 'jumlah_saudara_kandung')) {
                $table->integer('jumlah_saudara_kandung')->nullable()->after('anak_ke');
            }

            // Kontak
            if (!Schema::hasColumn('pendaftar', 'nomor_telepon_rumah')) {
                $table->string('nomor_telepon_rumah', 20)->nullable()->after('no_hp');
            }
            if (!Schema::hasColumn('pendaftar', 'email_pribadi')) {
                $table->string('email_pribadi')->nullable()->after('email');
            }

            // Alamat Detail (RT, RW, Dusun sudah dicek sebelumnya, tapi pastikan)
            if (!Schema::hasColumn('pendaftar', 'rt')) {
                $table->string('rt', 5)->nullable()->after('alamat');
            }
            if (!Schema::hasColumn('pendaftar', 'rw')) {
                $table->string('rw', 5)->nullable()->after('rt');
            }
            if (!Schema::hasColumn('pendaftar', 'dusun')) {
                $table->string('dusun', 100)->nullable()->after('rw');
            }
            if (!Schema::hasColumn('pendaftar', 'desa')) {
                $table->string('desa', 100)->nullable()->after('dusun');
            }
            if (!Schema::hasColumn('pendaftar', 'latitude')) {
                $table->decimal('latitude', 10, 8)->nullable()->after('kode_pos');
            }
            if (!Schema::hasColumn('pendaftar', 'longitude')) {
                $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            }

            // Tempat Tinggal & Transportasi
            if (!Schema::hasColumn('pendaftar', 'jenis_tinggal')) {
                $table->enum('jenis_tinggal', [
                    'Bersama Orang Tua',
                    'Wali',
                    'Kost',
                    'Asrama',
                    'Panti Asuhan',
                    'Pesantren',
                    'Lainnya'
                ])->nullable()->after('longitude');
            }
            if (!Schema::hasColumn('pendaftar', 'alat_transportasi')) {
                $table->enum('alat_transportasi', [
                    'Jalan Kaki',
                    'Sepeda',
                    'Sepeda Motor',
                    'Mobil Pribadi',
                    'Antar Jemput Sekolah',
                    'Angkutan Umum',
                    'Lainnya'
                ])->nullable()->after('jenis_tinggal');
            }
            if (!Schema::hasColumn('pendaftar', 'jarak_tempat_tinggal')) {
                $table->integer('jarak_tempat_tinggal')->nullable()->after('alat_transportasi')->comment('Dalam kilometer');
            }
            if (!Schema::hasColumn('pendaftar', 'waktu_tempuh')) {
                $table->integer('waktu_tempuh')->nullable()->after('jarak_tempat_tinggal')->comment('Dalam menit');
            }

            // Minat & Bakat
            if (!Schema::hasColumn('pendaftar', 'hobi')) {
                $table->string('hobi')->nullable()->after('waktu_tempuh');
            }
            if (!Schema::hasColumn('pendaftar', 'cita_cita')) {
                $table->string('cita_cita')->nullable()->after('hobi');
            }

            // Berkebutuhan Khusus
            if (!Schema::hasColumn('pendaftar', 'berkebutuhan_khusus')) {
                $table->boolean('berkebutuhan_khusus')->default(false)->after('cita_cita');
            }
            if (!Schema::hasColumn('pendaftar', 'jenis_kebutuhan_khusus')) {
                $table->json('jenis_kebutuhan_khusus')->nullable()->after('berkebutuhan_khusus')
                    ->comment('Array: Tuna Netra, Tuna Rungu, Tuna Daksa, Tuna Grahita, Tuna Laras, Lainnya');
            }

            // Data Ayah
            if (!Schema::hasColumn('pendaftar', 'ayah_nik')) {
                $table->string('ayah_nik', 16)->nullable()->after('nama_ayah');
            }
            if (!Schema::hasColumn('pendaftar', 'ayah_tahun_lahir')) {
                $table->integer('ayah_tahun_lahir')->nullable()->after('ayah_nik');
            }
            if (!Schema::hasColumn('pendaftar', 'ayah_pendidikan')) {
                $table->enum('ayah_pendidikan', [
                    'Tidak Sekolah',
                    'SD/MI',
                    'SMP/MTs',
                    'SMA/MA',
                    'D1',
                    'D2',
                    'D3',
                    'S1',
                    'S2',
                    'S3'
                ])->nullable()->after('pekerjaan_ayah');
            }
            if (!Schema::hasColumn('pendaftar', 'ayah_penghasilan')) {
                $table->enum('ayah_penghasilan', [
                    'Kurang dari Rp 500.000',
                    'Rp 500.000 - Rp 1.000.000',
                    'Rp 1.000.000 - Rp 2.000.000',
                    'Rp 2.000.000 - Rp 5.000.000',
                    'Rp 5.000.000 - Rp 10.000.000',
                    'Lebih dari Rp 10.000.000'
                ])->nullable()->after('ayah_pendidikan');
            }
            if (!Schema::hasColumn('pendaftar', 'ayah_no_hp')) {
                $table->string('ayah_no_hp', 20)->nullable()->after('ayah_penghasilan');
            }
            if (!Schema::hasColumn('pendaftar', 'ayah_berkebutuhan_khusus')) {
                $table->boolean('ayah_berkebutuhan_khusus')->default(false)->after('ayah_no_hp');
            }

            // Data Ibu
            if (!Schema::hasColumn('pendaftar', 'ibu_nik')) {
                $table->string('ibu_nik', 16)->nullable()->after('nama_ibu');
            }
            if (!Schema::hasColumn('pendaftar', 'ibu_tahun_lahir')) {
                $table->integer('ibu_tahun_lahir')->nullable()->after('ibu_nik');
            }
            if (!Schema::hasColumn('pendaftar', 'ibu_pendidikan')) {
                $table->enum('ibu_pendidikan', [
                    'Tidak Sekolah',
                    'SD/MI',
                    'SMP/MTs',
                    'SMA/MA',
                    'D1',
                    'D2',
                    'D3',
                    'S1',
                    'S2',
                    'S3'
                ])->nullable()->after('pekerjaan_ibu');
            }
            if (!Schema::hasColumn('pendaftar', 'ibu_penghasilan')) {
                $table->enum('ibu_penghasilan', [
                    'Kurang dari Rp 500.000',
                    'Rp 500.000 - Rp 1.000.000',
                    'Rp 1.000.000 - Rp 2.000.000',
                    'Rp 2.000.000 - Rp 5.000.000',
                    'Rp 5.000.000 - Rp 10.000.000',
                    'Lebih dari Rp 10.000.000'
                ])->nullable()->after('ibu_pendidikan');
            }
            if (!Schema::hasColumn('pendaftar', 'ibu_no_hp')) {
                $table->string('ibu_no_hp', 20)->nullable()->after('ibu_penghasilan');
            }
            if (!Schema::hasColumn('pendaftar', 'ibu_berkebutuhan_khusus')) {
                $table->boolean('ibu_berkebutuhan_khusus')->default(false)->after('ibu_no_hp');
            }

            // Data Wali
            if (!Schema::hasColumn('pendaftar', 'memiliki_wali')) {
                $table->boolean('memiliki_wali')->default(false)->after('ibu_berkebutuhan_khusus');
            }
            if (!Schema::hasColumn('pendaftar', 'wali_nik')) {
                $table->string('wali_nik', 16)->nullable()->after('memiliki_wali');
            }
            if (!Schema::hasColumn('pendaftar', 'wali_nama')) {
                $table->string('wali_nama')->nullable()->after('wali_nik');
            }
            if (!Schema::hasColumn('pendaftar', 'wali_tahun_lahir')) {
                $table->integer('wali_tahun_lahir')->nullable()->after('wali_nama');
            }
            if (!Schema::hasColumn('pendaftar', 'wali_pendidikan')) {
                $table->enum('wali_pendidikan', [
                    'Tidak Sekolah',
                    'SD/MI',
                    'SMP/MTs',
                    'SMA/MA',
                    'D1',
                    'D2',
                    'D3',
                    'S1',
                    'S2',
                    'S3'
                ])->nullable()->after('wali_tahun_lahir');
            }
            if (!Schema::hasColumn('pendaftar', 'wali_pekerjaan')) {
                $table->string('wali_pekerjaan')->nullable()->after('wali_pendidikan');
            }
            if (!Schema::hasColumn('pendaftar', 'wali_penghasilan')) {
                $table->enum('wali_penghasilan', [
                    'Kurang dari Rp 500.000',
                    'Rp 500.000 - Rp 1.000.000',
                    'Rp 1.000.000 - Rp 2.000.000',
                    'Rp 2.000.000 - Rp 5.000.000',
                    'Rp 5.000.000 - Rp 10.000.000',
                    'Lebih dari Rp 10.000.000'
                ])->nullable()->after('wali_pekerjaan');
            }
            if (!Schema::hasColumn('pendaftar', 'wali_no_hp')) {
                $table->string('wali_no_hp', 20)->nullable()->after('wali_penghasilan');
            }

            // Program Bantuan
            if (!Schema::hasColumn('pendaftar', 'penerima_kps')) {
                $table->boolean('penerima_kps')->default(false)->after('wali_no_hp');
            }
            if (!Schema::hasColumn('pendaftar', 'nomor_kps')) {
                $table->string('nomor_kps', 50)->nullable()->after('penerima_kps');
            }
            if (!Schema::hasColumn('pendaftar', 'penerima_kip')) {
                $table->boolean('penerima_kip')->default(false)->after('nomor_kps');
            }
            if (!Schema::hasColumn('pendaftar', 'nomor_kip')) {
                $table->string('nomor_kip', 50)->nullable()->after('penerima_kip');
            }
            if (!Schema::hasColumn('pendaftar', 'layak_pip')) {
                $table->boolean('layak_pip')->default(false)->after('nomor_kip');
            }
            if (!Schema::hasColumn('pendaftar', 'alasan_layak_pip')) {
                $table->text('alasan_layak_pip')->nullable()->after('layak_pip');
            }

            // Riwayat Pendidikan - TK/PAUD
            if (!Schema::hasColumn('pendaftar', 'tk_nama')) {
                $table->string('tk_nama')->nullable()->after('alasan_layak_pip');
            }
            if (!Schema::hasColumn('pendaftar', 'tk_npsn')) {
                $table->string('tk_npsn', 8)->nullable()->after('tk_nama');
            }
            if (!Schema::hasColumn('pendaftar', 'tk_tahun_lulus')) {
                $table->integer('tk_tahun_lulus')->nullable()->after('tk_npsn');
            }

            // Riwayat Pendidikan - SD/MI
            if (!Schema::hasColumn('pendaftar', 'sd_nama')) {
                $table->string('sd_nama')->nullable()->after('tk_tahun_lulus');
            }
            if (!Schema::hasColumn('pendaftar', 'sd_npsn')) {
                $table->string('sd_npsn', 8)->nullable()->after('sd_nama');
            }
            if (!Schema::hasColumn('pendaftar', 'sd_tahun_lulus')) {
                $table->integer('sd_tahun_lulus')->nullable()->after('sd_npsn');
            }
            if (!Schema::hasColumn('pendaftar', 'sd_nomor_peserta_ujian')) {
                $table->string('sd_nomor_peserta_ujian', 50)->nullable()->after('sd_tahun_lulus');
            }
            if (!Schema::hasColumn('pendaftar', 'sd_nomor_seri_ijazah')) {
                $table->string('sd_nomor_seri_ijazah', 50)->nullable()->after('sd_nomor_peserta_ujian');
            }
            if (!Schema::hasColumn('pendaftar', 'sd_nomor_skhun')) {
                $table->string('sd_nomor_skhun', 50)->nullable()->after('sd_nomor_seri_ijazah');
            }

            // Nilai Rapor SD
            if (!Schema::hasColumn('pendaftar', 'sd_nilai_matematika')) {
                $table->decimal('sd_nilai_matematika', 5, 2)->nullable()->after('sd_nomor_skhun');
            }
            if (!Schema::hasColumn('pendaftar', 'sd_nilai_ipa')) {
                $table->decimal('sd_nilai_ipa', 5, 2)->nullable()->after('sd_nilai_matematika');
            }
            if (!Schema::hasColumn('pendaftar', 'sd_nilai_ips')) {
                $table->decimal('sd_nilai_ips', 5, 2)->nullable()->after('sd_nilai_ipa');
            }
            if (!Schema::hasColumn('pendaftar', 'sd_nilai_bahasa_indonesia')) {
                $table->decimal('sd_nilai_bahasa_indonesia', 5, 2)->nullable()->after('sd_nilai_ips');
            }
            if (!Schema::hasColumn('pendaftar', 'sd_nilai_bahasa_inggris')) {
                $table->decimal('sd_nilai_bahasa_inggris', 5, 2)->nullable()->after('sd_nilai_bahasa_indonesia');
            }

            // File Upload
            if (!Schema::hasColumn('pendaftar', 'file_kartu_keluarga')) {
                $table->string('file_kartu_keluarga')->nullable()->after('sd_nilai_bahasa_inggris');
            }
            if (!Schema::hasColumn('pendaftar', 'file_akta_kelahiran')) {
                $table->string('file_akta_kelahiran')->nullable()->after('file_kartu_keluarga');
            }
            if (!Schema::hasColumn('pendaftar', 'file_ijazah_sd')) {
                $table->string('file_ijazah_sd')->nullable()->after('file_akta_kelahiran');
            }
            if (!Schema::hasColumn('pendaftar', 'file_skhun_sd')) {
                $table->string('file_skhun_sd')->nullable()->after('file_ijazah_sd');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pendaftar', function (Blueprint $table) {
            // Drop columns yang ditambahkan (hanya jika ada)
            $columnsToCheck = [
                'nik', 'nama_panggilan', 'kewarganegaraan', 'negara_asal',
                'nomor_kk', 'anak_ke', 'jumlah_saudara_kandung',
                'nomor_telepon_rumah', 'email_pribadi',
                'rt', 'rw', 'dusun', 'desa', 'latitude', 'longitude',
                'jenis_tinggal', 'alat_transportasi', 'jarak_tempat_tinggal', 'waktu_tempuh',
                'hobi', 'cita_cita', 'berkebutuhan_khusus', 'jenis_kebutuhan_khusus',
                'ayah_nik', 'ayah_tahun_lahir', 'ayah_pendidikan', 'ayah_penghasilan', 'ayah_no_hp', 'ayah_berkebutuhan_khusus',
                'ibu_nik', 'ibu_tahun_lahir', 'ibu_pendidikan', 'ibu_penghasilan', 'ibu_no_hp', 'ibu_berkebutuhan_khusus',
                'memiliki_wali', 'wali_nik', 'wali_nama', 'wali_tahun_lahir', 'wali_pendidikan', 'wali_pekerjaan', 'wali_penghasilan', 'wali_no_hp',
                'penerima_kps', 'nomor_kps', 'penerima_kip', 'nomor_kip', 'layak_pip', 'alasan_layak_pip',
                'tk_nama', 'tk_npsn', 'tk_tahun_lulus',
                'sd_nama', 'sd_npsn', 'sd_tahun_lulus', 'sd_nomor_peserta_ujian', 'sd_nomor_seri_ijazah', 'sd_nomor_skhun',
                'sd_nilai_matematika', 'sd_nilai_ipa', 'sd_nilai_ips', 'sd_nilai_bahasa_indonesia', 'sd_nilai_bahasa_inggris',
                'file_kartu_keluarga', 'file_akta_kelahiran', 'file_ijazah_sd', 'file_skhun_sd',
            ];

            foreach ($columnsToCheck as $column) {
                if (Schema::hasColumn('pendaftar', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
