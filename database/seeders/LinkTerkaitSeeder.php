<?php

namespace Database\Seeders;

use App\Models\LinkTerkait;
use Illuminate\Database\Seeder;

class LinkTerkaitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $links = [
            [
                'nama' => 'Website Utama MTsN 2 Kota Malang',
                'url' => 'https://mtsn2kotamalang.sch.id',
                'urutan' => 1,
                'aktif' => true,
            ],
            [
                'nama' => 'Portal Kemenag',
                'url' => 'https://kemenag.go.id',
                'urutan' => 2,
                'aktif' => true,
            ],
            [
                'nama' => 'E-Learning Madrasah',
                'url' => 'https://elearning.mtsn2kotamalang.sch.id',
                'urutan' => 3,
                'aktif' => true,
            ],
        ];

        foreach ($links as $link) {
            LinkTerkait::create($link);
        }
    }
}
