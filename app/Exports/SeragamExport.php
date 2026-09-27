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
    protected $jenisKelamin;
    protected $jalur;

    public function __construct($status = null, $search = null, $jenisKelamin = null, $jalur = null)
    {
        $this->status = $status;
        $this->search = $search;
        $this->jenisKelamin = $jenisKelamin;
        $this->jalur = $jalur;
    }

    public function query()
    {
        $query = Seragam::with(['pendaftar.jalurPendaftaran', 'pendaftar.user']);

        if ($this->status) {
            $query->where('status_ukuran', $this->status);
        }

        if ($this->search) {
            $query->whereHas('pendaftar', function($q) {
                $q->where('nama_lengkap', 'like', '%' . $this->search . '%')
                  ->orWhere('nomor_pendaftaran', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->jenisKelamin) {
            $query->whereHas('pendaftar', function($q) {
                $q->where('jenis_kelamin', $this->jenisKelamin);
            });
        }

        if ($this->jalur) {
            $query->whereHas('pendaftar', function($q) {
                $q->where('jalur_pendaftaran_id', $this->jalur);
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
            $nominalSeragamAmount = \App\Support\Nominal::toNumber($nominalSeragam['amount'] ?? 0);
        }

        return [
            $pendaftar->nomor_pendaftaran ?? '',
            $pendaftar->nama_lengkap ?? '',
            $pendaftar->nisn ?? '',
            $pendaftar->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan',
            $pendaftar->jalurPendaftaran->nama ?? '',
            $seragam->ukuran_jas ?? '',
            $seragam->ukuran_baju_olahraga ?? '',
            $seragam->ukuran_celana_olahraga ?? '',
            $seragam->ukuran_baju_harian ?? '',
            $seragam->ukuran_celana_rok_harian ?? '',
            $pendaftar->jenis_kelamin === 'L' ? ($seragam->ukuran_kopyah ?? '') : 'N/A',
            $seragam->paket_seragam ?? '',
            $this->getStatusLabel($seragam->status_ukuran),
            $this->getStatusLabel($seragam->status_seragam),
            $nominalSeragamName,
            'Rp ' . number_format($nominalSeragamAmount, 0, ',', '.'),
            $seragam->opsi_pembayaran_seragam ?? '',
            $seragam->bank_seragam ?? '',
            $seragam->bukti_pembayaran ? 'Sudah Upload' : 'Belum Upload',
            $seragam->catatan_ukuran ?? '',
            $seragam->catatan_seragam ?? '',
            $seragam->created_at ? Carbon::parse($seragam->created_at)->format('d-m-Y H:i') : '',
        ];
    }

    public function headings(): array
    {
        return [
            'No. Pendaftaran',
            'Nama Siswa',
            'NISN',
            'Jenis Kelamin',
            'Jalur Pendaftaran',
            'Ukuran Jas',
            'Ukuran Baju Olahraga',
            'Ukuran Celana Olahraga',
            'Ukuran Baju Seragam Harian',
            'Ukuran Celana/Rok Harian',
            'Ukuran Kopyah (Laki-laki)',
            'Paket Seragam',
            'Status Ukuran',
            'Status Pembayaran Seragam',
            'Jenis Nominal Seragam',
            'Nominal Seragam',
            'Opsi Pembayaran',
            'Bank',
            'Bukti Pembayaran',
            'Catatan Ukuran',
            'Catatan Seragam',
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