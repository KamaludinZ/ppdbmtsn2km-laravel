<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;

class SampleExport implements FromCollection, WithHeadings, WithStyles, WithTitle
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return new Collection([
            [1, 'Ahmad Zainudin', '0012345678', '7A', 'Laki-laki', 'Jakarta'],
            [2, 'Siti Nurhaliza', '0012345679', '7A', 'Perempuan', 'Bandung'],
            [3, 'Muhammad Rizki', '0012345680', '7B', 'Laki-laki', 'Surabaya'],
            [4, 'Fatimah Zahra', '0012345681', '7B', 'Perempuan', 'Yogyakarta'],
            [5, 'Abdul Rahman', '0012345682', '7C', 'Laki-laki', 'Medan'],
            [6, 'Khadijah Aisyah', '0012345683', '7C', 'Perempuan', 'Makassar'],
            [7, 'Umar Faruq', '0012345684', '8A', 'Laki-laki', 'Semarang'],
            [8, 'Aisha Rahmawati', '0012345685', '8A', 'Perempuan', 'Palembang'],
            [9, 'Ali Ibrahim', '0012345686', '8B', 'Laki-laki', 'Bali'],
            [10, 'Zaynab Husna', '0012345687', '8B', 'Perempuan', 'Malang'],
        ]);
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'No',
            'Nama Lengkap',
            'NISN',
            'Kelas',
            'Jenis Kelamin',
            'Asal Daerah',
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style untuk header
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4'],
                ],
                'font' => [
                    'color' => ['rgb' => 'FFFFFF'],
                    'bold' => true,
                ],
            ],
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Data Siswa';
    }
}
