<?php

namespace Database\Seeders;

use App\Models\KontakPanitia;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KontakPanitiaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kontakData = [
            [
                'nama' => 'Panitia Pendaftaran 1',
                'nomor_wa' => '081234567890',
                'keterangan' => 'Jalur Prestasi',
                'urutan' => 1,
                'aktif' => true,
            ],
            [
                'nama' => 'Panitia Pendaftaran 2',
                'nomor_wa' => '081234567891',
                'keterangan' => 'Jalur Reguler',
                'urutan' => 2,
                'aktif' => true,
            ],
            [
                'nama' => 'Panitia Pendaftaran 3',
                'nomor_wa' => '081234567892',
                'keterangan' => 'Jalur Afirmasi',
                'urutan' => 3,
                'aktif' => true,
            ],
            [
                'nama' => 'Panitia Pendaftaran 4',
                'nomor_wa' => '081234567893',
                'keterangan' => 'Jalur Mutasi',
                'urutan' => 4,
                'aktif' => true,
            ],
            [
                'nama' => 'Panitia Teknis',
                'nomor_wa' => '081234567894',
                'keterangan' => 'Bantuan Teknis',
                'urutan' => 5,
                'aktif' => true,
            ],
            [
                'nama' => 'Koordinator PPDB',
                'nomor_wa' => '081234567895',
                'keterangan' => 'Info Umum PPDB',
                'urutan' => 6,
                'aktif' => true,
            ],
        ];

        foreach ($kontakData as $data) {
            KontakPanitia::create($data);
        }
    }
}
