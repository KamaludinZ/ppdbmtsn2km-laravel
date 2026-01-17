<?php

namespace Database\Seeders;

use App\Models\PersyaratanUmum;
use Illuminate\Database\Seeder;

class PersyaratanUmumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $persyaratans = [
            [
                'persyaratan' => 'Beragama Islam',
                'urutan' => 1,
                'aktif' => true,
            ],
            [
                'persyaratan' => 'Usia maksimal 15 tahun pada 1 Juli tahun pelajaran berjalan',
                'urutan' => 2,
                'aktif' => true,
            ],
            [
                'persyaratan' => 'Memiliki Ijazah SD/MI/Program Paket A',
                'urutan' => 3,
                'aktif' => true,
            ],
            [
                'persyaratan' => 'Sehat jasmani dan rohani',
                'urutan' => 4,
                'aktif' => true,
            ],
            [
                'persyaratan' => 'Bersedia menaati tata tertib madrasah',
                'urutan' => 5,
                'aktif' => true,
            ],
        ];

        foreach ($persyaratans as $persyaratan) {
            PersyaratanUmum::create($persyaratan);
        }
    }
}