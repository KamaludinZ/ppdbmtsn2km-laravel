<?php

namespace Database\Seeders;

use App\Models\AsalSekolah;
use Illuminate\Database\Seeder;

class AsalSekolahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sekolahs = [
            [
                'nama_sekolah' => 'SDN 1 Malang',
                'npsn' => '10101010',
                'nsm' => null,
                'jenis_sekolah_asal' => 'umum',
                'alamat' => 'Jl. Pendidikan No. 1',
                'kecamatan' => 'Klojen',
                'kabupaten_kota' => 'Kota Malang',
                'provinsi' => 'Jawa Timur',
                'status_sekolah' => 'negeri',
                'jenjang' => 'SD',
                'aktif' => true,
            ],
            [
                'nama_sekolah' => 'MI Al-Ikhlas',
                'npsn' => '20202020',
                'nsm' => '111135730001',
                'jenis_sekolah_asal' => 'madrasah',
                'alamat' => 'Jl. Ikhlas No. 99',
                'kecamatan' => 'Lowokwaru',
                'kabupaten_kota' => 'Kota Malang',
                'provinsi' => 'Jawa Timur',
                'status_sekolah' => 'swasta',
                'jenjang' => 'MI',
                'aktif' => true,
            ],
        ];

        foreach ($sekolahs as $sekolah) {
            AsalSekolah::updateOrCreate(
                ['npsn' => $sekolah['npsn']],
                $sekolah
            );
        }
    }
}
