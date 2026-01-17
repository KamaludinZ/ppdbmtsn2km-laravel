<?php

namespace Database\Seeders;

use App\Models\TimelinePendaftaran;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TimelinePendaftaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currentYear = date('Y');

        $timelines = [
            [
                'judul_kegiatan' => 'Pendaftaran Online & Verifikasi Berkas (Jalur Prestasi)',
                'deskripsi' => 'Peserta melakukan pendaftaran secara online dan verifikasi berkas',
                'tanggal_mulai' => Carbon::parse("$currentYear-02-17"),
                'tanggal_selesai' => Carbon::parse("$currentYear-02-20"),
                'jalur_kode' => 'prestasi_akademik',
                'urutan' => 1,
            ],
            [
                'judul_kegiatan' => 'Tes Akademik, Baca Al-Qur\'an, & Wawancara (Jalur Prestasi)',
                'deskripsi' => 'Tes dilaksanakan secara offline di Madrasah',
                'tanggal_mulai' => Carbon::parse("$currentYear-02-22"),
                'tanggal_selesai' => Carbon::parse("$currentYear-02-22"),
                'jalur_kode' => 'prestasi_akademik',
                'urutan' => 2,
            ],
            [
                'judul_kegiatan' => 'Pengumuman Kelulusan (Jalur Prestasi)',
                'deskripsi' => 'Pengumuman dapat dilihat melalui website',
                'tanggal_mulai' => Carbon::parse("$currentYear-02-24"),
                'tanggal_selesai' => Carbon::parse("$currentYear-02-24"),
                'jalur_kode' => 'prestasi_akademik',
                'urutan' => 3,
            ],
            [
                'judul_kegiatan' => 'Daftar Ulang (Jalur Prestasi)',
                'deskripsi' => 'Peserta yang dinyatakan lulus wajib melakukan daftar ulang',
                'tanggal_mulai' => Carbon::parse("$currentYear-02-24"),
                'tanggal_selesai' => Carbon::parse("$currentYear-02-25"),
                'jalur_kode' => 'prestasi_akademik',
                'urutan' => 4,
            ],
            // Reguler & Tahfidz
            [
                'judul_kegiatan' => 'Pendaftaran Online & Verifikasi Berkas (Reguler & Tahfidz)',
                'deskripsi' => 'Peserta melakukan pendaftaran secara online dan verifikasi berkas',
                'tanggal_mulai' => Carbon::parse("$currentYear-03-03"),
                'tanggal_selesai' => Carbon::parse("$currentYear-03-06"),
                'jalur_kode' => 'reguler',
                'urutan' => 5,
            ],
            [
                'judul_kegiatan' => 'Tes Akademik, Baca Al-Qur\'an (Reguler & Tahfidz)',
                'deskripsi' => 'Tes dilaksanakan secara offline di Madrasah',
                'tanggal_mulai' => Carbon::parse("$currentYear-03-08"),
                'tanggal_selesai' => Carbon::parse("$currentYear-03-08"),
                'jalur_kode' => 'reguler',
                'urutan' => 6,
            ],
             [
                'judul_kegiatan' => 'Pengumuman Kelulusan (Reguler & Tahfidz)',
                'deskripsi' => 'Pengumuman dapat dilihat melalui website',
                'tanggal_mulai' => Carbon::parse("$currentYear-03-10"),
                'tanggal_selesai' => Carbon::parse("$currentYear-03-10"),
                'jalur_kode' => 'reguler',
                'urutan' => 7,
            ],
            [
                'judul_kegiatan' => 'Daftar Ulang (Reguler & Tahfidz)',
                'deskripsi' => 'Peserta yang dinyatakan lulus wajib melakukan daftar ulang',
                'tanggal_mulai' => Carbon::parse("$currentYear-03-10"),
                'tanggal_selesai' => Carbon::parse("$currentYear-03-12"),
                'jalur_kode' => 'reguler',
                'urutan' => 8,
            ],
        ];

        foreach ($timelines as $timeline) {
            TimelinePendaftaran::create($timeline);
        }
    }
}
