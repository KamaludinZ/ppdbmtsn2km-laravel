<?php

namespace Database\Seeders;

use App\Models\JalurPendaftaran;
use Illuminate\Database\Seeder;

class JalurPendaftaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jalurPendaftaran = [
            [
                'nama' => 'Jalur Prestasi Akademik',
                'kode' => 'prestasi_akademik',
                'deskripsi' => 'Jalur pendaftaran bagi siswa yang memiliki prestasi akademik',
                'deskripsi_lengkap' => 'Jalur ini diperuntukkan bagi calon siswa yang memiliki prestasi akademik yang baik, dibuktikan dengan nilai rapor yang tinggi dan/atau sertifikat lomba akademik tingkat kabupaten/kota atau lebih tinggi.',
                'kuota' => 80,
                'nilai_minimum' => 85.00,
                'persyaratan' => [
                    'Nilai rata-rata rapor (B. Indonesia, Matematika, IPA) minimal 85',
                    'Sertifikat prestasi akademik tingkat kabupaten/kota atau lebih tinggi (jika ada)',
                    'Surat keterangan dari sekolah asal',
                ],
                'icon' => 'trophy',
                'warna' => 'blue',
                'aktif' => true,
                'urutan' => 1,
            ],
            [
                'nama' => 'Jalur Prestasi Non-Akademik',
                'kode' => 'prestasi_nonakademik',
                'deskripsi' => 'Jalur pendaftaran bagi siswa yang memiliki prestasi non-akademik',
                'deskripsi_lengkap' => 'Jalur ini diperuntukkan bagi calon siswa yang memiliki prestasi di bidang non-akademik seperti olahraga, seni, atau keagamaan.',
                'kuota' => 40,
                'nilai_minimum' => 75.00,
                'persyaratan' => [
                    'Nilai rata-rata rapor (B. Indonesia, Matematika, IPA) minimal 75',
                    'Sertifikat prestasi non-akademik tingkat kabupaten/kota atau lebih tinggi',
                    'Surat rekomendasi dari sekolah asal atau lembaga terkait',
                ],
                'icon' => 'star',
                'warna' => 'indigo',
                'aktif' => true,
                'urutan' => 2,
            ],
            [
                'nama' => 'Jalur Reguler',
                'kode' => 'reguler',
                'deskripsi' => 'Jalur pendaftaran umum untuk seluruh calon siswa',
                'deskripsi_lengkap' => 'Jalur pendaftaran umum yang terbuka untuk seluruh calon siswa yang memenuhi persyaratan minimum dan lulus seleksi.',
                'kuota' => 200,
                'nilai_minimum' => 70.00,
                'persyaratan' => [
                    'Ijazah SD/MI atau surat keterangan lulus',
                    'Nilai rata-rata rapor (B. Indonesia, Matematika, IPA) minimal 70',
                    'Berusia maksimal 15 tahun pada saat pendaftaran',
                    'Lulus tes seleksi',
                ],
                'icon' => 'users',
                'warna' => 'emerald',
                'aktif' => true,
                'urutan' => 3,
            ],
            [
                'nama' => 'Jalur Tahfidz',
                'kode' => 'tahfidz',
                'deskripsi' => 'Jalur khusus bagi siswa yang memiliki hafalan Al-Quran',
                'deskripsi_lengkap' => 'Jalur khusus bagi calon siswa yang memiliki hafalan Al-Quran minimal 5 juz dan telah mendapat sertifikasi dari lembaga/pondok pesantren.',
                'kuota' => 50,
                'nilai_minimum' => 70.00,
                'persyaratan' => [
                    'Nilai rata-rata rapor (B. Indonesia, Matematika, IPA) minimal 70',
                    'Minimal hafal 5 juz Al-Quran',
                    'Sertifikat tahfidz dari lembaga/pondok pesantren',
                    'Lulus tes hafalan Al-Quran dan tajwid',
                ],
                'icon' => 'book-open',
                'warna' => 'purple',
                'aktif' => true,
                'urutan' => 4,
            ],
            [
                'nama' => 'Jalur Afirmasi',
                'kode' => 'afirmasi',
                'deskripsi' => 'Jalur khusus untuk siswa dari keluarga kurang mampu',
                'deskripsi_lengkap' => 'Jalur ini diperuntukkan bagi calon siswa dari keluarga kurang mampu yang dibuktikan dengan Kartu Indonesia Pintar (KIP) atau surat keterangan tidak mampu dari kelurahan.',
                'kuota' => 30,
                'nilai_minimum' => 65.00,
                'persyaratan' => [
                    'Nilai rata-rata rapor (B. Indonesia, Matematika, IPA) minimal 65',
                    'Kartu Indonesia Pintar (KIP) atau Kartu Keluarga Sejahtera (KKS)',
                    'Surat keterangan tidak mampu dari kelurahan',
                    'Fotokopi Kartu Keluarga',
                ],
                'icon' => 'hand-heart',
                'warna' => 'orange',
                'aktif' => true,
                'urutan' => 5,
            ],
            [
                'nama' => 'Jalur Perpindahan Tugas Orang Tua',
                'kode' => 'perpindahan_ortu',
                'deskripsi' => 'Jalur untuk siswa yang orang tuanya pindah tugas',
                'deskripsi_lengkap' => 'Jalur ini diperuntukkan bagi calon siswa yang orang tua/wali mengalami perpindahan tugas ke wilayah Kota Malang.',
                'kuota' => 20,
                'nilai_minimum' => 70.00,
                'persyaratan' => [
                    'Nilai rata-rata rapor (B. Indonesia, Matematika, IPA) minimal 70',
                    'Surat keterangan perpindahan tugas orang tua/wali',
                    'Rapor asli dari sekolah asal',
                    'Surat pindah dari sekolah asal',
                ],
                'icon' => 'map-pin',
                'warna' => 'teal',
                'aktif' => false,
                'urutan' => 6,
            ],
        ];

        foreach ($jalurPendaftaran as $jalur) {
            JalurPendaftaran::updateOrCreate(
                ['kode' => $jalur['kode']],
                $jalur
            );
        }

        $this->command->info('Jalur Pendaftaran seeded successfully!');
    }
}
