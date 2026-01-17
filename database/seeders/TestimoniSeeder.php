<?php

namespace Database\Seeders;

use App\Models\Testimoni;
use Illuminate\Database\Seeder;

class TestimoniSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $testimonis = [
            [
                'nama' => 'Dr. H. Ahmad Fulan',
                'tahun_lulus' => '1995',
                'testimoni' => 'MTsN 2 Kota Malang memberikan pondasi agama yang kuat dan akademik yang unggul. Guru-gurunya sangat berdedikasi.',
                'institusi_sekarang' => 'Dosen UIN Malang',
                'urutan' => 1,
                'aktif' => true,
            ],
            [
                'nama' => 'Siti Aminah, S.Kom.',
                'tahun_lulus' => '2010',
                'testimoni' => 'Pengalaman belajar di sini sangat berkesan. Fasilitas lengkap dan lingkungan yang kondusif untuk menghafal Al-Quran.',
                'institusi_sekarang' => 'Software Engineer di Unicorn',
                'urutan' => 2,
                'aktif' => true,
            ],
            [
                'nama' => 'Rizki Pratama',
                'tahun_lulus' => '2018',
                'testimoni' => 'Bangga menjadi alumni MTsN 2 Kota Malang. Banyak ilmu bermanfaat yang saya dapatkan untuk bekal masa depan.',
                'institusi_sekarang' => 'Mahasiswa Kedokteran UB',
                'urutan' => 3,
                'aktif' => true,
            ],
        ];

        foreach ($testimonis as $testimoni) {
            Testimoni::create($testimoni);
        }
    }
}
