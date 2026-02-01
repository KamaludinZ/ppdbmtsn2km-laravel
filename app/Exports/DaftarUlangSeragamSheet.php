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

class DaftarUlangSeragamSheet implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle
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

        // Get nominal seragam (supports both predefined and custom "opsi lainnya")
        $nominalSeragam = $seragam->nominal_seragam;
        $nominalSeragamName = '';
        $nominalSeragamAmount = 0;

        if (is_array($nominalSeragam)) {
            $nominalSeragamName = $nominalSeragam['name'] ?? '';
            $nominalSeragamAmount = $nominalSeragam['amount'] ?? 0;
        }

        return [
            $pendaftar->nomor_pendaftaran ?? '',
            $pendaftar->nisn ?? '',
            $pendaftar->nama_lengkap ?? '',
            $pendaftar->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan',
            $pendaftar->telepon_ortu ?? $pendaftar->no_hp ?? '',
            $pendaftar->user->email ?? '',
            $this->getStatusPersetujuanLabel($pendaftar->status_persetujuan_daftar_ulang),
            $pendaftar->alasan_tidak_setuju ?? '-',
            $seragam->paket_seragam ?? '-',
            $nominalSeragamName,
            'Rp ' . number_format($nominalSeragamAmount, 0, ',', '.'),
            $seragam->opsi_pembayaran_seragam ?? '',
            $seragam->bank_seragam ?? '',
            $this->getStatusLabel($seragam->status_seragam),
            $seragam->bukti_pembayaran ? 'Sudah Upload' : 'Belum Upload',
            $seragam->catatan_seragam ?? '',
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
            'No. Telepon',
            'Email',
            'Status Persetujuan',
            'Alasan Tidak Melanjutkan',
            'Paket Seragam',
            'Jenis Nominal Seragam',
            'Nominal Seragam',
            'Opsi Pembayaran',
            'Bank',
            'Status Pembayaran',
            'Status Bukti Bayar',
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
                    'startColor' => ['rgb' => 'FF9800']
                ],
                'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true],
            ],
        ];
    }

    public function title(): string
    {
        return 'Daftar Ulang Seragam';
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
