<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\VideoPanduan;

class VideoPanduanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $videos = [
            [
                'judul' => 'Profil MTsN 2 Kota Malang',
                'deskripsi' => 'Video profil singkat Madrasah Tsanawiyah Negeri 2 Kota Malang, menjelaskan sejarah, visi, misi dan keunggulan madrasah.',
                'url_video' => 'https://www.youtube.com/watch?v=2XGGzgdZU24', // Video profil MTsN 2 Malang
                'kategori' => 'profil',
                'aktif' => true,
                'urutan' => 1,
            ],
            [
                'judul' => 'Tata Tertib Siswa MTsN 2 Kota Malang',
                'deskripsi' => 'Penjelasan mengenai tata tertib dan peraturan yang berlaku di MTsN 2 Kota Malang.',
                'url_video' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', // Placeholder - ganti dengan video sesuai
                'kategori' => 'tata-tertib',
                'aktif' => true,
                'urutan' => 2,
            ],
            [
                'judul' => 'Cara Pendaftaran Online SPMBM',
                'deskripsi' => 'Petunjuk langkah demi langkah cara mendaftar secara online pada Sistem Penerimaan Murid Baru Madrasah di MTsN 2 Kota Malang.',
                'url_video' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', // Placeholder - ganti dengan video sesuai
                'kategori' => 'panduan',
                'aktif' => true,
                'urutan' => 3,
            ],
            [
                'judul' => 'Kegiatan Ekstrakurikuler',
                'deskripsi' => 'Berbagai kegiatan ekstrakurikuler yang tersedia di MTsN 2 Kota Malang untuk mengembangkan bakat dan minat siswa.',
                'url_video' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', // Placeholder - ganti dengan video sesuai
                'kategori' => 'ekstrakurikuler',
                'aktif' => true,
                'urutan' => 4,
            ],
            [
                'judul' => 'Fasilitas dan Prasarana',
                'deskripsi' => 'Tinjauan terhadap fasilitas dan prasarana yang tersedia di MTsN 2 Kota Malang untuk mendukung proses pembelajaran.',
                'url_video' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', // Placeholder - ganti dengan video sesuai
                'kategori' => 'fasilitas',
                'aktif' => true,
                'urutan' => 5,
            ]
        ];

        foreach ($videos as $video) {
            VideoPanduan::updateOrCreate(
                ['judul' => $video['judul']], // Match by judul
                $video
            );
        }
    }
}
