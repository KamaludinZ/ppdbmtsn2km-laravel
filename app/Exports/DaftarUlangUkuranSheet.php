<?php

namespace App\Exports;

use App\Exports\Concerns\PendaftarWajibDaftarUlang;
use App\Models\Seragam;
use App\Support\RekapDaftarUlang;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Carbon\Carbon;

class DaftarUlangUkuranSheet implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle
{
    use PendaftarWajibDaftarUlang;

    protected $status;
    protected $search;
    protected $jalur;
    protected $jenisRekap;
    protected $jenisKelamin;

    /**
     * @param  string|null  $status  kategori rekap (RekapDaftarUlang::KATEGORI)
     * @param  string|null  $jenisRekap  madrasah|seragam|ukuran — dasar kategori $status
     */
    public function __construct($status = null, $search = null, $jalur = null, $jenisRekap = null, $jenisKelamin = null)
    {
        $this->status = $status;
        $this->search = $search;
        $this->jalur = $jalur;
        $this->jenisRekap = $jenisRekap;
        $this->jenisKelamin = $jenisKelamin;
    }

    public function query()
    {
        return $this->queryPendaftarWajib($this->status, $this->jenisRekap ?? 'ukuran', $this->search, $this->jalur ?? null, $this->jenisKelamin ?? null);
    }

    public function map($pendaftar): array
    {
        // Pendaftar yang belum mengisi seragam tetap tampil (kolom seragam kosong)
        $seragam = $pendaftar->seragam ?? new Seragam();

        // Parse paket seragam (same as DaftarUlangSeragamSheet for consistency)
        $paketName = 'Belum Dipilih';
        $paketSeragamRaw = $seragam->paket_seragam;

        if ($paketSeragamRaw === 'paket_lainnya') {
            $paketName = 'Paket Lainnya (Manual)';
        } elseif (!empty($paketSeragamRaw)) {
            // Try to decode if it's JSON
            $paketData = json_decode($paketSeragamRaw, true);
            if (is_array($paketData) && isset($paketData['name'])) {
                $paketName = $paketData['name'];
            } else {
                $paketName = 'Paket Seragam';
            }
        }

        return [
            $pendaftar->nomor_pendaftaran ?? '',
            $pendaftar->nisn ?? '',
            $pendaftar->nama_lengkap ?? '',
            $pendaftar->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan',
            $seragam->tinggi_badan ? $seragam->tinggi_badan . ' cm' : '-',
            $seragam->berat_badan ? $seragam->berat_badan . ' kg' : '-',
            $paketName, // Nama paket (readable)
            $this->getStatusPersetujuanLabel($pendaftar->status_persetujuan_daftar_ulang),
            $pendaftar->alasan_tidak_setuju ?? '-',
            $seragam->ukuran_jas ?? '-',
            $seragam->ukuran_baju_olahraga ?? '-',
            $seragam->ukuran_celana_olahraga ?? '-',
            $seragam->ukuran_baju_harian ?? '-',
            $seragam->ukuran_celana_rok_harian ?? '-',
            $pendaftar->jenis_kelamin === 'L' ? ($seragam->ukuran_kopyah ?? '-') : 'N/A',
            $pendaftar->telepon_ortu ?? $pendaftar->no_hp ?? '',
            $pendaftar->alamat ?? '',
            RekapDaftarUlang::label(RekapDaftarUlang::kategori($pendaftar, 'ukuran')),
            $this->getStatusLabel($seragam->status_ukuran),
            $seragam->catatan_ukuran ?? '',
            $seragam->created_at ? Carbon::parse($seragam->created_at)->format('d-m-Y H:i') : '',
        ];
    }

    public function headings(): array
    {
        return [
            'No. Pendaftaran',
            'NISN',
            'Nama Lengkap',
            'Jenis Kelamin',
            'Tinggi Badan',
            'Berat Badan',
            'Paket Seragam',
            'Status Persetujuan',
            'Alasan Tidak Melanjutkan',
            'Ukuran Jas',
            'Ukuran Baju Olahraga',
            'Ukuran Celana Olahraga',
            'Ukuran Baju Seragam Harian',
            'Ukuran Celana/Rok Harian',
            'Ukuran Kopyah (Laki-laki)',
            'No. Telepon',
            'Alamat',
            'Status Rekap',
            'Status Ukuran',
            'Catatan',
            'Tanggal Input',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 11],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '9C27B0']
                ],
                'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true],
            ],
        ];
    }

    public function title(): string
    {
        return 'Ukuran Seragam & Atribut';
    }

    private function getStatusLabel($status)
    {
        return RekapDaftarUlang::labelStatusProses($status);
    }

    private function getStatusPersetujuanLabel($status)
    {
        $statusLabels = [
            'belum_memilih' => 'Belum Memilih',
            'setuju' => 'Setuju Daftar Ulang',
            'tidak_setuju' => 'Tidak Melanjutkan',
        ];

        return $statusLabels[$status] ?? 'Belum Memilih';
    }
}
