<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Fasilitas;

class FasilitasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if facilities already exist to avoid duplicates
        if (Fasilitas::count() == 0) {
            // Default facilities that were previously hardcoded on the public page
            $defaultFacilities = [
                [
                    'nama_fasilitas' => 'Ruang Kelas Ber-AC',
                    'deskripsi' => 'Nyaman untuk belajar',
                    'icon' => 'building',
                    'warna' => 'emerald',
                    'urutan' => 1,
                    'aktif' => true,
                ],
                [
                    'nama_fasilitas' => 'Perpustakaan Digital',
                    'deskripsi' => 'Koleksi buku lengkap',
                    'icon' => 'library',
                    'warna' => 'blue',
                    'urutan' => 2,
                    'aktif' => true,
                ],
                [
                    'nama_fasilitas' => 'Laboratorium Komputer',
                    'deskripsi' => 'Perangkat modern dan lengkap',
                    'icon' => 'computer',
                    'warna' => 'purple',
                    'urutan' => 3,
                    'aktif' => true,
                ],
                [
                    'nama_fasilitas' => 'Laboratorium IPA',
                    'deskripsi' => 'Fasilitas praktikum modern',
                    'icon' => 'science',
                    'warna' => 'yellow',
                    'urutan' => 4,
                    'aktif' => true,
                ],
                [
                    'nama_fasilitas' => 'Lapangan Olahraga',
                    'deskripsi' => 'Basket, voli, dan futsal',
                    'icon' => 'sports',
                    'warna' => 'red',
                    'urutan' => 5,
                    'aktif' => true,
                ],
                [
                    'nama_fasilitas' => 'Masjid',
                    'deskripsi' => 'Tempat ibadah yang luas',
                    'icon' => 'mosque',
                    'warna' => 'green',
                    'urutan' => 6,
                    'aktif' => true,
                ],
                [
                    'nama_fasilitas' => 'Kantin Sehat',
                    'deskripsi' => 'Makanan bergizi dan bersih',
                    'icon' => 'cafe',
                    'warna' => 'indigo',
                    'urutan' => 7,
                    'aktif' => true,
                ],
                [
                    'nama_fasilitas' => 'Ruang Multimedia',
                    'deskripsi' => 'Audio visual lengkap',
                    'icon' => 'computer',
                    'warna' => 'pink',
                    'urutan' => 8,
                    'aktif' => true,
                ],
                [
                    'nama_fasilitas' => 'Ruang Seni & Musik',
                    'deskripsi' => 'Pengembangan bakat siswa',
                    'icon' => 'music',
                    'warna' => 'purple',
                    'urutan' => 9,
                    'aktif' => true,
                ],
                [
                    'nama_fasilitas' => 'Asrama (Mahad)',
                    'deskripsi' => 'Asrama madrasah siswa putra',
                    'icon' => 'building',
                    'warna' => 'emerald',
                    'urutan' => 10,
                    'aktif' => true,
                ],
                [
                    'nama_fasilitas' => 'Ruang BK & Konseling',
                    'deskripsi' => 'Bimbingan konseling siswa',
                    'icon' => 'book',
                    'warna' => 'blue',
                    'urutan' => 11,
                    'aktif' => true,
                ],
                [
                    'nama_fasilitas' => 'UKS',
                    'deskripsi' => 'Unit Kesehatan Sekolah',
                    'icon' => 'building',
                    'warna' => 'red',
                    'urutan' => 12,
                    'aktif' => true,
                ],
            ];

            foreach ($defaultFacilities as $facility) {
                Fasilitas::create($facility);
            }
        }
    }
}