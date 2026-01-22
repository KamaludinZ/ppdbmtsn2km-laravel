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

class SeragamExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle
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
        $query = Seragam::with(['pendaftar.jalurPendaftaran', 'pendaftar.user']);

        if ($this->status) {
            $query->where('status', $this->status);
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

        return [
            $pendaftar->nomor_pendaftaran ?? '',
            $pendaftar->nama_lengkap ?? '',
            $pendaftar->nisn ?? '',
            $pendaftar->jalurPendaftaran->nama ?? '',
            $pendaftar->asal_sekolah ?? '',
            $pendaftar->nomor_hp ?? $pendaftar->no_hp ?? '',
            $pendaftar->user->email ?? '',
            $seragam->paket_seragam ?? '',
            $seragam->tinggi_badan ?? '',
            $seragam->berat_badan ?? '',
            $seragam->ukuran_baju ?? '',
            $seragam->ukuran_celana ?? '',
            $seragam->ukuran_topi ?? '',
            $seragam->ukuran_sepatu ?? '',
            $seragam->lingkar_dada ?? '',
            $seragam->lingkar_pinggang ?? '',
            $seragam->panjang_lengan ?? '',
            $seragam->opsi_pembayaran_seragam ?? '',
            $seragam->bank_seragam ?? '',
            $this->getStatusLabel($seragam->status_seragam),
            $this->getStatusLabel($seragam->status_ukuran),
            $seragam->tanggal_pesan ? Carbon::parse($seragam->tanggal_pesan)->format('d-m-Y') : '',
            $seragam->tanggal_terima ? Carbon::parse($seragam->tanggal_terima)->format('d-m-Y') : '',
            $seragam->bukti_pembayaran ? 'Sudah Upload' : 'Belum Upload',
            $seragam->sudah_diukur ? 'Ya' : 'Tidak',
            $seragam->sudah_dipesan ? 'Ya' : 'Tidak',
            $seragam->sudah_diterima ? 'Ya' : 'Tidak',
            $seragam->catatan ?? '',
            $seragam->catatan_seragam ?? '',
            $seragam->catatan_ukuran ?? '',
            $seragam->created_at ? Carbon::parse($seragam->created_at)->format('d-m-Y H:i') : '',
        ];
    }

    public function headings(): array
    {
        return [
            'No. Pendaftaran',
            'Nama Siswa',
            'NISN',
            'Jalur Pendaftaran',
            'Asal Sekolah',
            'No. HP',
            'Email',
            'Paket Seragam',
            'Tinggi Badan (cm)',
            'Berat Badan (kg)',
            'Ukuran Baju',
            'Ukuran Celana',
            'Ukuran Topi',
            'Ukuran Sepatu',
            'Lingkar Dada (cm)',
            'Lingkar Pinggang (cm)',
            'Panjang Lengan (cm)',
            'Opsi Pembayaran',
            'Bank',
            'Status Seragam',
            'Status Ukuran',
            'Tanggal Pesan',
            'Tanggal Terima',
            'Bukti Pembayaran',
            'Sudah Diukur',
            'Sudah Dipesan',
            'Sudah Diterima',
            'Catatan',
            'Catatan Seragam',
            'Catatan Ukuran',
            'Tanggal Input'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function title(): string
    {
        return 'Data Seragam';
    }

    private function getStatusLabel($status)
    {
        $statusLabels = [
            'menunggu' => 'Menunggu',
            'disetujui' => 'Disetujui',
            'ditolak' => 'Ditolak',
            'perbaiki' => 'Perlu Perbaikan',
            'menunggu_pembayaran' => 'Menunggu Pembayaran',
            'menunggu_verifikasi' => 'Menunggu Verifikasi',
            'pembayaran_diterima' => 'Pembayaran Diterima',
            'seragam_disiapkan' => 'Seragam Disiapkan',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan'
        ];

        return $statusLabels[$status] ?? ucfirst(str_replace('_', ' ', $status ?? ''));
    }
}