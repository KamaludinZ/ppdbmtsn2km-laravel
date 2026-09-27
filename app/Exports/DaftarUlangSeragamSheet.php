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

class DaftarUlangSeragamSheet implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle
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
        return $this->queryPendaftarWajib($this->status, $this->jenisRekap ?? 'seragam', $this->search, $this->jalur ?? null, $this->jenisKelamin ?? null);
    }

    public function map($pendaftar): array
    {
        // Pendaftar yang belum mengisi seragam tetap tampil (kolom seragam kosong)
        $seragam = $pendaftar->seragam ?? new Seragam();

        // Parse paket seragam (JSON paket normal atau "paket_lainnya")
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

        // Get nominal seragam awal (opsi yang dipilih dari paket)
        // Could be single object or array (multi-select/checkbox mode)
        $nominalSeragamAwal = $seragam->nominal_seragam_awal;
        $opsiName = '';
        $opsiAmount = 0;

        if (is_array($nominalSeragamAwal)) {
            // Check if multi-select (array of objects with name/amount)
            if (isset($nominalSeragamAwal[0]) && is_array($nominalSeragamAwal[0])) {
                // Multi-select: combine names and sum amounts
                $names = [];
                $totalAmount = 0;
                foreach ($nominalSeragamAwal as $opsi) {
                    $names[] = $opsi['name'] ?? '';
                    $totalAmount += \App\Support\Nominal::toNumber($opsi['amount'] ?? 0);
                }
                $opsiName = implode(' + ', array_filter($names));
                $opsiAmount = $totalAmount;
            } else {
                // Single select or paket_lainnya
                $opsiName = $nominalSeragamAwal['name'] ?? '';
                $opsiAmount = \App\Support\Nominal::toNumber($nominalSeragamAwal['amount'] ?? 0);
            }
        }

        // Get nominal seragam (konfirmasi nominal yang dibayarkan)
        $nominalSeragam = $seragam->nominal_seragam;
        $nominalSeragamName = '';
        $nominalSeragamAmount = 0;

        if (is_array($nominalSeragam)) {
            // Handle if it's multi-select konfirmasi
            if (isset($nominalSeragam['items']) && is_array($nominalSeragam['items'])) {
                // Multi-select konfirmasi
                $nominalSeragamName = $nominalSeragam['name'] ?? '';
                $nominalSeragamAmount = \App\Support\Nominal::toNumber($nominalSeragam['amount'] ?? 0);
            } else {
                // Single konfirmasi
                $nominalSeragamName = $nominalSeragam['name'] ?? '';
                $nominalSeragamAmount = \App\Support\Nominal::toNumber($nominalSeragam['amount'] ?? 0);
            }
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
            $paketName, // Nama paket (readable)
            $opsiName, // Opsi yang dipilih (bisa multi-select dengan +)
            'Rp ' . number_format($opsiAmount, 0, ',', '.'), // Nominal opsi yang dipilih
            $nominalSeragamName, // Konfirmasi nominal
            'Rp ' . number_format($nominalSeragamAmount, 0, ',', '.'), // Nominal yang dibayarkan
            $seragam->opsi_pembayaran_seragam ?? '',
            $seragam->bank_seragam ?? '',
            $seragam->nama_pengirim_seragam ?? '-',
            RekapDaftarUlang::label(RekapDaftarUlang::kategori($pendaftar, 'seragam')),
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
            'Paket Seragam', // UPDATED: Nama paket yang readable
            'Opsi yang Dipilih', // NEW: Nama opsi (bisa multi-select dengan +)
            'Nominal Opsi', // NEW: Total nominal dari opsi yang dipilih
            'Konfirmasi Nominal', // RENAMED: Konfirmasi pembayaran
            'Nominal yang Dibayarkan', // RENAMED: Nominal yang dikonfirmasi
            'Opsi Pembayaran',
            'Bank',
            'Nama Pengirim',
            'Status Rekap',
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
