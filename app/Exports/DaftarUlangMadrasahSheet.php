<?php

namespace App\Exports;

use App\Models\DaftarUlang;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Carbon\Carbon;

class DaftarUlangMadrasahSheet implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle
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
        $query = DaftarUlang::with(['pendaftar.jalurPendaftaran', 'pendaftar.user']);

        if ($this->status) {
            $query->where('status_daftar_ulang', $this->status);
        }

        if ($this->search) {
            $query->whereHas('pendaftar', function($q) {
                $q->where('nama_lengkap', 'like', '%' . $this->search . '%')
                  ->orWhere('nomor_pendaftaran', 'like', '%' . $this->search . '%');
            });
        }

        return $query->orderBy('created_at', 'desc');
    }

    public function map($daftarUlang): array
    {
        $pendaftar = $daftarUlang->pendaftar;

        return [
            $pendaftar->nomor_pendaftaran ?? '',
            $pendaftar->nisn ?? '',
            $pendaftar->nama_lengkap ?? '',
            $pendaftar->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan',
            $pendaftar->tempat_lahir ?? '',
            $pendaftar->tanggal_lahir ? Carbon::parse($pendaftar->tanggal_lahir)->format('d-m-Y') : '',
            $pendaftar->alamat ?? '',
            $pendaftar->telepon_ortu ?? $pendaftar->no_hp ?? '',
            $pendaftar->user->email ?? '',
            $pendaftar->jalurPendaftaran->nama ?? '',
            $pendaftar->asal_sekolah ?? '',
            $pendaftar->nsm_sekolah_asal ?? $pendaftar->npsn_sekolah_asal ?? '',
            $this->getStatusLabel($daftarUlang->status_daftar_ulang),
            $daftarUlang->tanggal_bayar ? Carbon::parse($daftarUlang->tanggal_bayar)->format('d-m-Y H:i') : '',
            'Rp ' . number_format($daftarUlang->nominal_bayar ?? 0, 0, ',', '.'),
            $daftarUlang->bukti_bayar ? 'Sudah Upload' : 'Belum Upload',
            $daftarUlang->tanggal_verifikasi ? Carbon::parse($daftarUlang->tanggal_verifikasi)->format('d-m-Y H:i') : '',
            $daftarUlang->catatan ?? '',
            $daftarUlang->created_at ? Carbon::parse($daftarUlang->created_at)->format('d-m-Y H:i') : '',
        ];
    }

    public function headings(): array
    {
        return [
            'No. Pendaftaran',
            'NISN',
            'Nama Lengkap',
            'Jenis Kelamin',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Alamat',
            'No. Telepon',
            'Email',
            'Jalur Pendaftaran',
            'Asal Sekolah',
            'NSM/NPSN Sekolah',
            'Status Daftar Ulang',
            'Tanggal Bayar',
            'Nominal Bayar',
            'Status Bukti Bayar',
            'Tanggal Verifikasi',
            'Catatan',
            'Tanggal Daftar Ulang',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 11],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '2196F3']
                ],
                'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true],
            ],
        ];
    }

    public function title(): string
    {
        return 'Daftar Ulang Madrasah';
    }

    private function getStatusLabel($status)
    {
        $statusLabels = [
            'belum' => 'Belum Daftar Ulang',
            'proses' => 'Proses Verifikasi',
            'selesai' => 'Selesai',
            'batal' => 'Dibatalkan'
        ];

        return $statusLabels[$status] ?? ucfirst($status);
    }
}
