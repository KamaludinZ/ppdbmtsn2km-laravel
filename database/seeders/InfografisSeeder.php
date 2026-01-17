<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Infografis;

class InfografisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $infografis = [
            [
                'judul' => 'Profil MTsN 2 Kota Malang',
                'deskripsi' => 'Infografis penjelasan singkat tentang sejarah, visi, misi dan keunggulan MTsN 2 Kota Malang.',
                'gambar_path' => 'https://placehold.co/400x400/4ade80/ffffff?text=Profil+MTsN+2+Malang', // Placeholder image
                'kategori' => 'profil',
                'aktif' => true,
                'urutan' => 1,
            ],
            [
                'judul' => 'Jalur Pendaftaran',
                'deskripsi' => 'Infografis penjelasan mengenai jalur pendaftaran dalam SPMBM (Prestasi, Reguler, Tahfidz)',
                'gambar_path' => 'https://placehold.co/400x400/3b82f6/ffffff?text=Jalur+Pendaftaran', // Placeholder image
                'kategori' => 'pendaftaran',
                'aktif' => true,
                'urutan' => 2,
            ],
            [
                'judul' => 'Jadwal Pendaftaran',
                'deskripsi' => 'Infografis penjelasan mengenai timeline dan jadwal penting pendaftaran SPMBM',
                'gambar_path' => 'https://placehold.co/400x400/8b5cf6/ffffff?text=Jadwal+Pendaftaran', // Placeholder image
                'kategori' => 'jadwal',
                'aktif' => true,
                'urutan' => 3,
            ],
            [
                'judul' => 'Persyaratan Pendaftaran',
                'deskripsi' => 'Infografis penjelasan mengenai berkas dan persyaratan yang harus dipenuhi dalam pendaftaran',
                'gambar_path' => 'https://placehold.co/400x400/f59e0b/ffffff?text=Persyaratan+Pendaftaran', // Placeholder image
                'kategori' => 'persyaratan',
                'aktif' => true,
                'urutan' => 4,
            ],
            [
                'judul' => 'Fasilitas Madrasah',
                'deskripsi' => 'Infografis penjelasan mengenai fasilitas unggulan yang tersedia di MTsN 2 Kota Malang',
                'gambar_path' => 'https://placehold.co/400x400/ef4444/ffffff?text=Fasilitas+Madrasah', // Placeholder image
                'kategori' => 'fasilitas',
                'aktif' => true,
                'urutan' => 5,
            ],
            [
                'judul' => 'Ekstrakurikuler',
                'deskripsi' => 'Infografis penjelasan mengenai kegiatan ekstrakurikuler yang tersedia di MTsN 2 Kota Malang',
                'gambar_path' => 'https://placehold.co/400x400/10b981/ffffff?text=Ekstrakurikuler', // Placeholder image
                'kategori' => 'ekstra',
                'aktif' => true,
                'urutan' => 6,
            ],
            [
                'judul' => 'Program Unggulan',
                'deskripsi' => 'Infografis penjelasan mengenai program-program unggulan yang dimiliki MTsN 2 Kota Malang',
                'gambar_path' => 'https://placehold.co/400x400/ec4899/ffffff?text=Program+Unggulan', // Placeholder image
                'kategori' => 'program',
                'aktif' => true,
                'urutan' => 7,
            ]
        ];

        foreach ($infografis as $info) {
            Infografis::create($info);
        }
    }
}
