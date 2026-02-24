<?php

namespace App\Exports;

use App\Models\Seragam;
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
    protected $status;
    protected $search;

    public function __construct($status = null, $search = null)
    {
        $this->status = $status;
        $this->search = $search;
    }

    public function query()
    {
        $query = Seragam::with(['pendaftar.jalurPendaftaran', 'pendaftar.user', 'pendaftar.daftarUlang']);

        // Only show records with size data
        $query->where(function($q) {
            $q->whereNotNull('ukuran_jas')
              ->orWhereNotNull('ukuran_baju_olahraga')
              ->orWhereNotNull('ukuran_celana_olahraga')
              ->orWhereNotNull('ukuran_baju_harian')
              ->orWhereNotNull('ukuran_celana_rok_harian')
              ->orWhereNotNull('ukuran_kopyah');
        });

        // Filter based on daftar ulang status if provided
        if ($this->status) {
            $query->whereHas('pendaftar.daftarUlang', function($q) {
                $q->where('status_daftar_ulang', $this->status);
            });
        }

        if ($this->search) {
            $query->whereHas('pendaftar', function($q) {
                $q->where('nama_lengkap', 'like', '%' . $this->search . '%')
                  ->orWhere('nomor_pendaftaran', 'like', '%' . $this->search . '%');
            });
        }

        return $query->orderBy('created_at', 'desc');
    }

    public function map($seragam): array
    {
        $pendaftar = $seragam->pendaftar;

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
            'Status',
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
        $statusLabels = [
            'menunggu_pembayaran' => 'Menunggu Pembayaran',
            'menunggu_verifikasi' => 'Menunggu Verifikasi',
            'pembayaran_diterima' => 'Pembayaran Diterima',
            'seragam_disiapkan' => 'Seragam Disiapkan',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan'
        ];

        return $statusLabels[$status] ?? ucfirst(str_replace('_', ' ', $status));
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
