<?php

namespace App\Exports;

use App\Models\Pendaftar;
use App\Support\RekapDaftarUlang;
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
    protected $jalur;

    public function __construct($status = null, $search = null, $jalur = null)
    {
        $this->status = $status;
        $this->search = $search;
        $this->jalur = $jalur;
    }

    public function query()
    {
        // Berbasis pendaftar (bukan tabel daftar_ulang) agar SEMUA pendaftar wajib daftar ulang
        // ikut tercantum, termasuk yang belum konfirmasi / belum punya data daftar ulang.
        $query = Pendaftar::with(['jalurPendaftaran', 'user', 'daftarUlang', 'seragam'])
            ->whereIn('status_pendaftaran', RekapDaftarUlang::STATUS_WAJIB)
            ->when($this->jalur, fn ($q) => $q->where('jalur_pendaftaran_id', $this->jalur));

        // $status = kategori rekap (lihat RekapDaftarUlang::KATEGORI)
        RekapDaftarUlang::filterKategori($query, $this->status, 'madrasah');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('nama_lengkap', 'ilike', '%' . $this->search . '%')
                  ->orWhere('nomor_pendaftaran', 'ilike', '%' . $this->search . '%');
            });
        }

        return $query->orderBy('nama_lengkap');
    }

    public function map($pendaftar): array
    {
        $daftarUlang = $pendaftar->daftarUlang; // bisa null (belum konfirmasi / belum mengisi)

        // Get nominal biaya awal (opsi yang dipilih dari paket)
        $nominalBiayaAwal = $daftarUlang?->nominal_biaya_awal;
        $opsiName = '';
        $opsiAmount = 0;

        if (is_array($nominalBiayaAwal)) {
            $opsiName = $nominalBiayaAwal['name'] ?? '';
            $opsiAmount = \App\Support\Nominal::toNumber($nominalBiayaAwal['amount'] ?? 0);
        }

        // Get nominal biaya (konfirmasi nominal yang dibayarkan)
        $nominalBiaya = $daftarUlang?->nominal_biaya;
        $nominalBiayaName = '';
        $nominalBiayaAmount = 0;

        if (is_array($nominalBiaya)) {
            $nominalBiayaName = $nominalBiaya['name'] ?? '';
            $nominalBiayaAmount = \App\Support\Nominal::toNumber($nominalBiaya['amount'] ?? 0);
        }

        // Parse paket madrasah (could be NULL, 'paket_lainnya', or JSON)
        $paketName = 'Belum Dipilih';
        if (!empty($daftarUlang?->paket_madrasah)) {
            // Note: paket_madrasah is not stored in database by default
            // If it exists, it would be from paketMadrasah field in Livewire
            // For paket_lainnya, the name is in nominal_biaya_awal
            $paketName = $opsiName ? $opsiName : 'Paket Madrasah';
        }

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
            \App\Support\PersetujuanDaftarUlang::label(\App\Support\PersetujuanDaftarUlang::status($pendaftar)),
            \App\Support\PersetujuanDaftarUlang::status($pendaftar) === 'tidak_setuju' ? ($pendaftar->alasan_tidak_setuju ?: '(tidak dicatat)') : '-',
            $pendaftar->tanggal_pilihan_daftar_ulang ? Carbon::parse($pendaftar->tanggal_pilihan_daftar_ulang)->format('d-m-Y H:i') : '-',
            $pendaftar->asal_sekolah ?? '',
            $pendaftar->nsm_sekolah_asal ?? $pendaftar->npsn_sekolah_asal ?? '',
            RekapDaftarUlang::label(RekapDaftarUlang::kategori($pendaftar, 'madrasah')),
            $this->getStatusLabel($daftarUlang?->status),
            $opsiName, // Opsi yang dipilih dari paket (atau nama paket custom)
            'Rp ' . number_format($opsiAmount, 0, ',', '.'), // Nominal opsi yang dipilih
            $nominalBiayaName, // Konfirmasi nominal (bisa sama atau "Opsi Lainnya")
            'Rp ' . number_format($nominalBiayaAmount, 0, ',', '.'), // Nominal yang dikonfirmasi
            $daftarUlang?->opsi_pembayaran ?? '',
            $daftarUlang?->metode_pembayaran ?? '',
            $daftarUlang?->nama_pengirim ?? '-',
            $daftarUlang?->bukti_pembayaran_madrasah ? 'Sudah Upload' : 'Belum Upload',
            $daftarUlang?->catatan ?? '',
            $daftarUlang?->created_at ? Carbon::parse($daftarUlang->created_at)->format('d-m-Y H:i') : '',
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
            'Status Persetujuan',
            'Alasan Tidak Melanjutkan',
            'Tanggal Pilihan',
            'Asal Sekolah',
            'NSM/NPSN Sekolah',
            'Status Rekap',
            'Status Verifikasi Daftar Ulang',
            'Opsi yang Dipilih', // NEW: Nama opsi dari paket atau nama paket custom
            'Nominal Opsi', // NEW: Nominal dari opsi yang dipilih
            'Konfirmasi Nominal', // RENAMED: Konfirmasi pembayaran (bisa sama atau Opsi Lainnya)
            'Nominal yang Dibayarkan', // RENAMED: Nominal yang dikonfirmasi untuk dibayar
            'Opsi Pembayaran',
            'Metode Pembayaran',
            'Nama Pengirim',
            'Status Bukti Bayar',
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
            'belum_diisi' => 'Belum Diisi',
            'menunggu' => 'Menunggu Verifikasi',
            'proses' => 'Proses Verifikasi',
            'perbaiki' => 'Perlu Perbaikan',
            'perlu_perbaikan' => 'Perlu Perbaikan',
            'ditolak' => 'Ditolak',
            'disetujui' => 'Disetujui',
            'selesai' => 'Selesai',
        ];

        return $status ? ($statusLabels[$status] ?? ucfirst($status)) : 'Belum Ada Data';
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
