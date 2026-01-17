<?php

namespace Database\Seeders;

use App\Models\Pengumuman;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PengumumanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pengumuman::create([
            'judul' => 'Pembukaan Pendaftaran PPDB MTsN 2 Kota Malang Tahun 2025',
            'konten' => 'Dengan hormat, kami informasikan bahwa Penerimaan Peserta Didik Baru (PPDB) MTsN 2 Kota Malang tahun pelajaran 2025/2026 akan dibuka mulai tanggal 1 Maret 2025. Seluruh calon peserta didik dapat melakukan pendaftaran secara online melalui website resmi PPDB MTsN 2 Kota Malang. Informasi lebih lanjut mengenai jalur pendaftaran, persyaratan, dan jadwal dapat dilihat pada menu Informasi & Panduan.',
            'tanggal_publikasi' => '2025-02-15',
            'kategori' => 'umum',
            'is_penting' => true,
            'urutan' => 1,
            'aktif' => true,
        ]);

        Pengumuman::create([
            'judul' => 'Perpanjangan Waktu Pendaftaran Jalur Prestasi',
            'konten' => 'Berdasarkan pertimbangan panitia PPDB, dengan ini kami mengumumkan bahwa waktu pendaftaran untuk Jalur Prestasi diperpanjang hingga tanggal 15 Maret 2025 pukul 23:59 WIB. Perpanjangan ini diberikan untuk memberikan kesempatan lebih luas kepada calon peserta didik berprestasi untuk mendaftar. Silakan manfaatkan kesempatan ini dengan sebaik-baiknya.',
            'tanggal_publikasi' => '2025-03-08',
            'kategori' => 'jadwal',
            'is_penting' => true,
            'urutan' => 2,
            'aktif' => true,
        ]);

        Pengumuman::create([
            'judul' => 'Pengumuman Hasil Seleksi Jalur Prestasi',
            'konten' => 'Alhamdulillah, pengumuman hasil seleksi Jalur Prestasi akan dilaksanakan pada tanggal 20 Maret 2025 pukul 14:00 WIB. Hasil seleksi dapat dilihat melalui website resmi PPDB atau dengan login ke akun pendaftaran masing-masing. Bagi peserta didik yang diterima, silakan melakukan daftar ulang sesuai jadwal yang telah ditentukan. Semoga seluruh peserta mendapatkan hasil yang terbaik.',
            'tanggal_publikasi' => '2025-03-18',
            'kategori' => 'pengumuman_lulus',
            'is_penting' => true,
            'urutan' => 3,
            'aktif' => true,
        ]);

        Pengumuman::create([
            'judul' => 'Tata Cara Daftar Ulang Peserta Didik yang Diterima',
            'konten' => 'Kepada seluruh peserta didik yang dinyatakan lulus/diterima di MTsN 2 Kota Malang, kami ucapkan selamat! Berikut adalah tata cara daftar ulang: 1) Login ke akun PPDB Anda, 2) Download formulir daftar ulang, 3) Lengkapi formulir dan siapkan dokumen asli (Ijazah/SKHUN, Akte Kelahiran, Kartu Keluarga, KTP Orang Tua), 4) Datang ke sekolah sesuai jadwal yang telah ditentukan untuk menyerahkan dokumen dan melakukan verifikasi. Pastikan Anda tidak melewatkan batas waktu daftar ulang.',
            'tanggal_publikasi' => '2025-03-21',
            'kategori' => 'umum',
            'is_penting' => false,
            'urutan' => 4,
            'aktif' => true,
        ]);

        Pengumuman::create([
            'judul' => 'Informasi Tes Kemampuan Akademik Jalur Reguler',
            'konten' => 'Tes Kemampuan Akademik untuk Jalur Reguler akan dilaksanakan pada tanggal 5 April 2025 pukul 08:00 - 12:00 WIB. Peserta wajib membawa kartu peserta ujian, alat tulis, dan perlengkapan lainnya. Materi tes meliputi Matematika, Bahasa Indonesia, IPA, dan Bahasa Inggris. Peserta diharapkan datang 30 menit sebelum tes dimulai. Informasi lebih lanjut akan dikirimkan melalui email yang terdaftar.',
            'tanggal_publikasi' => '2025-03-25',
            'kategori' => 'jadwal',
            'is_penting' => true,
            'urutan' => 5,
            'aktif' => true,
        ]);

        Pengumuman::create([
            'judul' => 'Pembukaan Jalur Tahfidz untuk Calon Peserta Didik',
            'konten' => 'MTsN 2 Kota Malang dengan bangga mengumumkan pembukaan Jalur Tahfidz untuk tahun pelajaran 2025/2026. Jalur ini diperuntukkan bagi calon peserta didik yang memiliki hafalan Al-Quran minimal 5 juz dengan kualitas bacaan yang baik. Pendaftaran dibuka mulai tanggal 1 April 2025 dan akan ditutup pada tanggal 15 April 2025. Tes hafalan dan bacaan akan dilaksanakan pada tanggal 20 April 2025.',
            'tanggal_publikasi' => '2025-03-30',
            'kategori' => 'umum',
            'is_penting' => false,
            'urutan' => 6,
            'aktif' => true,
        ]);

        Pengumuman::create([
            'judul' => 'Panduan Upload Dokumen Persyaratan',
            'konten' => 'Untuk memudahkan proses verifikasi, berikut adalah panduan upload dokumen persyaratan: 1) Pastikan semua dokumen dalam format PDF atau JPG dengan ukuran maksimal 2MB per file, 2) Scan/foto dokumen dengan jelas dan tidak terpotong, 3) Pastikan dokumen yang diupload adalah dokumen asli bukan fotokopi, 4) Upload dokumen sesuai dengan kategori yang diminta pada sistem. Jika mengalami kesulitan, silakan hubungi panitia PPDB.',
            'tanggal_publikasi' => '2025-02-20',
            'kategori' => 'umum',
            'is_penting' => false,
            'urutan' => 7,
            'aktif' => true,
        ]);

        Pengumuman::create([
            'judul' => 'Informasi Biaya Pendidikan Tahun Pelajaran 2025/2026',
            'konten' => 'Sesuai dengan kebijakan pemerintah, MTsN 2 Kota Malang tidak memungut biaya pendidikan (SPP) bulanan. Namun demikian, terdapat biaya awal masuk untuk kegiatan orientasi siswa baru, seragam, dan kegiatan ekstrakurikuler. Detail rincian biaya akan diinformasikan lebih lanjut saat daftar ulang. Untuk informasi lebih detail, silakan hubungi bagian tata usaha sekolah.',
            'tanggal_publikasi' => '2025-03-10',
            'kategori' => 'umum',
            'is_penting' => false,
            'urutan' => 8,
            'aktif' => true,
        ]);
    }
}
