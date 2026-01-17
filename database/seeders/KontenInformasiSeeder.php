<?php

namespace Database\Seeders;

use App\Models\KontenInformasi;
use Illuminate\Database\Seeder;

class KontenInformasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contents = [
            [
                'judul' => 'Sambutan Kepala Madrasah',
                'isi' => 'Selamat datang di PPDB MTsN 2 Kota Malang. Kami berkomitmen untuk mencetak generasi yang cerdas, berakhlak mulia, dan berprestasi. Mari bergabung bersama kami!',
                'urutan' => 1,
                'aktif' => true,
            ],
            [
                'judul' => 'Visi & Misi',
                'isi' => 'Visi: Terwujudnya Insan yang Islami, Cerdas, dan Peduli Lingkungan. Misi: 1. Menyelenggarakan pendidikan yang berkualitas. 2. Membiasakan perilaku Islami. 3. Menciptakan lingkungan belajar yang nyaman.',
                'urutan' => 2,
                'aktif' => true,
            ],
        ];

        foreach ($contents as $content) {
            KontenInformasi::create($content);
        }
    }
}
