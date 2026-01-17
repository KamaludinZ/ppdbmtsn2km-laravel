<?php

namespace Database\Seeders;

use App\Models\Pendaftar;
use App\Models\User;
use App\Models\JalurPendaftaran;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PendaftarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data lama
        \DB::table('pendaftar')->delete();

        // Get users with role pendaftar
        $users = User::where('role', 'pendaftar')->get();

        // Get jalur pendaftaran
        $jalurPrestasi = JalurPendaftaran::where('kode', 'prestasi')->first();
        $jalurReguler = JalurPendaftaran::where('kode', 'reguler')->first();
        $jalurAfirmasi = JalurPendaftaran::where('kode', 'afirmasi')->first();

        if (!$jalurPrestasi || !$jalurReguler || !$jalurAfirmasi) {
            $this->command->error('Jalur Pendaftaran belum di-seed. Jalankan JalurPendaftaranSeeder terlebih dahulu.');
            return;
        }

        // Pendaftar 1 - Status Menunggu Verifikasi (Sekolah Umum)
        Pendaftar::create([
            'user_id' => $users[0]->id,
            'jalur_pendaftaran_id' => $jalurPrestasi->id,
            'nomor_pendaftaran' => 'P0001',
            'nisn' => '0012345678',
            'nama_lengkap' => 'Ahmad Maulana Yusuf',
            'tempat_lahir' => 'Malang',
            'tanggal_lahir' => '2012-05-15',
            'jenis_kelamin' => 'L',
            'agama' => 'Islam',
            'alamat' => 'Jl. Soekarno Hatta No. 123, Malang',
            'telepon' => '081234567890',
            'email' => 'ahmad.maulana@example.com',
            'nama_ayah' => 'Bapak Ahmad',
            'nama_ibu' => 'Ibu Siti',
            'pekerjaan_ayah' => 'Wiraswasta',
            'pekerjaan_ibu' => 'Ibu Rumah Tangga',
            'penghasilan_ortu' => '3-5 Juta',
            'telepon_ortu' => '081234567891',
            'asal_sekolah' => 'SDN 1 Malang',
            'jenis_sekolah_asal' => 'umum',
            'npsn_sekolah_asal' => '20536326',
            'nilai_rata_rata' => 85.50,
            'nilai_b_indo_5_1' => 85,
            'nilai_matematika_5_1' => 87,
            'nilai_ipa_5_1' => 84,
            'nilai_b_indo_5_2' => 86,
            'nilai_matematika_5_2' => 88,
            'nilai_ipa_5_2' => 85,
            'nilai_b_indo_6_1' => 87,
            'nilai_matematika_6_1' => 89,
            'nilai_ipa_6_1' => 86,
            'prestasi' => 'Juara 1 Olimpiade Matematika Tingkat Kota',
            'status_pendaftaran' => 'menunggu_verifikasi',
            'tanggal_daftar' => Carbon::now()->subDays(3),
        ]);

        // Pendaftar 2 - Status Diterima (Lulus) (Madrasah)
        Pendaftar::create([
            'user_id' => $users[1]->id,
            'jalur_pendaftaran_id' => $jalurReguler->id,
            'nomor_pendaftaran' => 'R0001',
            'nisn' => '0012345679',
            'nama_lengkap' => 'Siti Aisyah',
            'tempat_lahir' => 'Malang',
            'tanggal_lahir' => '2012-08-20',
            'jenis_kelamin' => 'P',
            'agama' => 'Islam',
            'alamat' => 'Jl. MT Haryono No. 45, Malang',
            'telepon' => '081234567892',
            'email' => 'siti.aisyah@example.com',
            'nama_ayah' => 'Bapak Abdullah',
            'nama_ibu' => 'Ibu Aminah',
            'pekerjaan_ayah' => 'PNS',
            'pekerjaan_ibu' => 'Guru',
            'penghasilan_ortu' => '5-10 Juta',
            'telepon_ortu' => '081234567893',
            'asal_sekolah' => 'MI Al-Falah Malang',
            'jenis_sekolah_asal' => 'madrasah',
            'nsm_sekolah_asal' => '121135740001',
            'nilai_rata_rata' => 88.75,
            'nilai_b_indo_5_1' => 88,
            'nilai_matematika_5_1' => 90,
            'nilai_ipa_5_1' => 87,
            'nilai_b_indo_5_2' => 89,
            'nilai_matematika_5_2' => 91,
            'nilai_ipa_5_2' => 88,
            'nilai_b_indo_6_1' => 90,
            'nilai_matematika_6_1' => 92,
            'nilai_ipa_6_1' => 89,
            'status_pendaftaran' => 'data_disetujui',
            'catatan_verifikasi' => 'Selamat, Anda diterima di MTsN 2 Kota Malang. Silakan melakukan daftar ulang.',
            'tanggal_daftar' => Carbon::now()->subDays(10),
            'tanggal_verifikasi' => Carbon::now()->subDays(2),
        ]);

        // Pendaftar 3 - Status Verifikasi (Sekolah Umum)
        Pendaftar::create([
            'user_id' => $users[2]->id,
            'jalur_pendaftaran_id' => $jalurAfirmasi->id,
            'nomor_pendaftaran' => 'A0001',
            'nisn' => '0012345680',
            'nama_lengkap' => 'Muhammad Fauzi',
            'tempat_lahir' => 'Malang',
            'tanggal_lahir' => '2012-03-10',
            'jenis_kelamin' => 'L',
            'agama' => 'Islam',
            'alamat' => 'Jl. Veteran No. 78, Malang',
            'telepon' => '081234567894',
            'email' => 'muhammad.fauzi@example.com',
            'nama_ayah' => 'Bapak Fauzi',
            'nama_ibu' => 'Ibu Zahra',
            'pekerjaan_ayah' => 'Petani',
            'pekerjaan_ibu' => 'Ibu Rumah Tangga',
            'penghasilan_ortu' => 'Kurang dari 2 Juta',
            'telepon_ortu' => '081234567895',
            'asal_sekolah' => 'SDN 3 Malang',
            'jenis_sekolah_asal' => 'umum',
            'npsn_sekolah_asal' => '20536328',
            'nilai_rata_rata' => 82.30,
            'nilai_b_indo_5_1' => 82,
            'nilai_matematika_5_1' => 80,
            'nilai_ipa_5_1' => 83,
            'nilai_b_indo_5_2' => 83,
            'nilai_matematika_5_2' => 81,
            'nilai_ipa_5_2' => 84,
            'nilai_b_indo_6_1' => 84,
            'nilai_matematika_6_1' => 82,
            'nilai_ipa_6_1' => 85,
            'status_pendaftaran' => 'perbaiki_data',
            'tanggal_daftar' => Carbon::now()->subDays(5),
        ]);

        // Pendaftar 4 - Status Ditolak (Madrasah)
        Pendaftar::create([
            'user_id' => $users[3]->id,
            'jalur_pendaftaran_id' => $jalurPrestasi->id,
            'nomor_pendaftaran' => 'P0002',
            'nisn' => '0012345681',
            'nama_lengkap' => 'Fatimah Azzahra',
            'tempat_lahir' => 'Malang',
            'tanggal_lahir' => '2012-11-25',
            'jenis_kelamin' => 'P',
            'agama' => 'Islam',
            'alamat' => 'Jl. Simpang Gajayana No. 90, Malang',
            'telepon' => '081234567896',
            'email' => 'fatimah.azzahra@example.com',
            'nama_ayah' => 'Bapak Hasan',
            'nama_ibu' => 'Ibu Khadijah',
            'pekerjaan_ayah' => 'Pedagang',
            'pekerjaan_ibu' => 'Ibu Rumah Tangga',
            'penghasilan_ortu' => '2-3 Juta',
            'telepon_ortu' => '081234567897',
            'asal_sekolah' => 'MI Nurul Huda Malang',
            'jenis_sekolah_asal' => 'madrasah',
            'nsm_sekolah_asal' => '121135740002',
            'nilai_rata_rata' => 75.50,
            'nilai_b_indo_5_1' => 75,
            'nilai_matematika_5_1' => 73,
            'nilai_ipa_5_1' => 76,
            'nilai_b_indo_5_2' => 76,
            'nilai_matematika_5_2' => 74,
            'nilai_ipa_5_2' => 77,
            'nilai_b_indo_6_1' => 77,
            'nilai_matematika_6_1' => 75,
            'nilai_ipa_6_1' => 78,
            'status_pendaftaran' => 'data_ditolak',
            'catatan_verifikasi' => 'Maaf, dokumen yang diupload tidak lengkap. Silakan melengkapi dokumen dan mendaftar kembali di periode berikutnya.',
            'tanggal_daftar' => Carbon::now()->subDays(12),
            'tanggal_verifikasi' => Carbon::now()->subDays(8),
        ]);

        // Pendaftar 5 - Status Draft (Belum Submit) (Sekolah Umum - sama dengan Pendaftar 1 untuk test auto-fill)
        Pendaftar::create([
            'user_id' => $users[4]->id,
            'jalur_pendaftaran_id' => $jalurReguler->id,
            'nomor_pendaftaran' => 'R0002',
            'nisn' => '0012345682',
            'nama_lengkap' => 'Rizki Hidayat',
            'tempat_lahir' => 'Malang',
            'tanggal_lahir' => '2012-07-18',
            'jenis_kelamin' => 'L',
            'agama' => 'Islam',
            'alamat' => 'Jl. Bandung No. 56, Malang',
            'telepon' => '081234567898',
            'email' => 'rizki.hidayat@example.com',
            'nama_ayah' => 'Bapak Hidayat',
            'nama_ibu' => 'Ibu Nurma',
            'pekerjaan_ayah' => 'Buruh',
            'pekerjaan_ibu' => 'Buruh',
            'penghasilan_ortu' => 'Kurang dari 2 Juta',
            'telepon_ortu' => '081234567899',
            'asal_sekolah' => 'SDN 1 Malang',
            'jenis_sekolah_asal' => 'umum',
            'npsn_sekolah_asal' => '20536326',
            'nilai_rata_rata' => 80.00,
            'nilai_b_indo_5_1' => 80,
            'nilai_matematika_5_1' => 78,
            'nilai_ipa_5_1' => 81,
            'nilai_b_indo_5_2' => 81,
            'nilai_matematika_5_2' => 79,
            'nilai_ipa_5_2' => 82,
            'nilai_b_indo_6_1' => 82,
            'nilai_matematika_6_1' => 80,
            'nilai_ipa_6_1' => 83,
            'status_pendaftaran' => 'lengkapi_data',
            'tanggal_daftar' => null,
        ]);

        $this->command->info('5 Pendaftar berhasil di-seed dengan berbagai status.');
    }
}
