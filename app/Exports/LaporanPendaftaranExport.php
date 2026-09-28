<?php

namespace App\Exports;

use App\Models\Pendaftar;
use App\Models\JalurPendaftaran;
use App\Models\Setting;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class LaporanPendaftaranExport implements FromCollection, WithHeadings, WithStyles, WithTitle, ShouldAutoSize
{
    protected $jalurId;
    protected $status;
    protected $startDate;
    protected $endDate;

    public function __construct($jalurId = null, $status = null, $startDate = null, $endDate = null)
    {
        $this->jalurId = $jalurId;
        $this->status = $status;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        // Get per jalur statistics (only active jalur)
        $jalurStats = \App\Support\RekapPendaftaran::perJalur([
            'jalur' => $this->jalurId,
            'status' => $this->status,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
        ]);

        // Get settings - Identitas Cetak (Kop PDF)
        $printInstitutionName = Setting::get('print_institution_name', 'MADRASAH TSANAWIYAH NEGERI 2 KOTA MALANG');
        $printInstitutionAddress = Setting::get('print_institution_address', 'Jl. Bandung No. 7 Kota Malang, Jawa Timur 65117');
        $printInstitutionPhone = Setting::get('print_institution_phone', '(0341) 551276');
        $printInstitutionEmail = Setting::get('print_institution_email', 'ppdb@mtsn2kotamalang.sch.id');
        $printInstitutionWebsite = Setting::get('print_institution_website', 'www.mtsn2kotamalang.sch.id');
        $tahunPelajaran = Setting::get('tahun_pelajaran', date('Y') . '/' . (date('Y') + 1));

        // Prepare data array
        $data = collect();

        // Add header (Kop Surat)
        $data->push([
            $printInstitutionName,
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
        ]);
        $data->push([
            $printInstitutionAddress,
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
        ]);

        // Build contact line
        $contactParts = [];
        if ($printInstitutionPhone) $contactParts[] = 'Telp: ' . $printInstitutionPhone;
        if ($printInstitutionEmail) $contactParts[] = 'Email: ' . $printInstitutionEmail;
        if ($printInstitutionWebsite) $contactParts[] = $printInstitutionWebsite;
        $contactLine = implode(' | ', $contactParts);

        $data->push([
            $contactLine,
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
        ]);
        $data->push(['']); // Empty row

        // Document title
        $data->push([
            'LAPORAN PENDAFTARAN',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
        ]);
        $data->push([
            'Tahun Pelajaran ' . $tahunPelajaran,
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
        ]);
        $data->push(['']); // Empty row

        // Add per jalur statistics
        $data->push(['STATISTIK PER JALUR PENDAFTARAN', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '']);
        $data->push(['Jalur', 'Kuota', 'Total', 'Menunggu', 'Lengkapi', 'Perbaiki', 'Ditolak', 'Disetujui', 'Lulus', 'Tdk Lulus', 'Cadangan', 'L', 'P', 'MI', 'SD', '%', '']);

        foreach ($jalurStats as $jalur) {
            $percentage = $jalur->kuota > 0 ? round(($jalur->pendaftar_count / $jalur->kuota) * 100, 2) : 0;
            $data->push([
                $jalur->nama . ($jalur->nonaktif ? ' (nonaktif)' : ''),
                number_format($jalur->kuota),
                number_format($jalur->pendaftar_count),
                number_format($jalur->menunggu_count),
                number_format($jalur->lengkapi_count),
                number_format($jalur->perbaiki_count),
                number_format($jalur->ditolak_count),
                number_format($jalur->disetujui_count),
                number_format($jalur->lulus_count),
                number_format($jalur->tidak_lulus_count),
                number_format($jalur->cadangan_count),
                number_format($jalur->laki_laki_count),
                number_format($jalur->perempuan_count),
                number_format($jalur->madrasah_count),
                number_format($jalur->umum_count),
                number_format($percentage, 2),
                '',
            ]);
        }

        // Add footer
        $data->push(['']); // Empty row
        $data->push([
            'Dicetak pada: ' . date('d F Y, H:i:s') . ' WIB',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
        ]);

        return $data;
    }

    public function headings(): array
    {
        return [];
    }

    public function styles(Worksheet $sheet)
    {
        // Merge cells for header (kop surat)
        $sheet->mergeCells('A1:Q1'); // Nama lembaga
        $sheet->mergeCells('A2:Q2'); // Alamat
        $sheet->mergeCells('A3:Q3'); // Kontak (Telp, Email, Website)

        // Style for header (kop surat) - Rows 1-3
        $sheet->getStyle('A1:A3')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Style untuk nama lembaga (row 1) lebih besar
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Merge cells for document title
        $sheet->mergeCells('A5:Q5'); // LAPORAN PENDAFTARAN PPDB
        $sheet->mergeCells('A6:Q6'); // Tahun Pelajaran

        // Style for document title - Row 5-6
        $sheet->getStyle('A5:A6')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Style for per jalur section header (row 8)
        $sheet->getStyle('A8')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'D3D3D3'],
            ],
        ]);

        // Style for per jalur table header (row 9)
        $sheet->getStyle('A9:P9')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '10b981'],
            ],
            'font' => [
                'color' => ['rgb' => 'FFFFFF'],
                'bold' => true,
            ],
        ]);

        return [];
    }

    public function title(): string
    {
        return 'Laporan Pendaftaran';
    }
}
