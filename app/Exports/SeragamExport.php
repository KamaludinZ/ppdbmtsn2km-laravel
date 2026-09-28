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

class SeragamExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle
{
    use PendaftarWajibDaftarUlang;

    protected $status;
    protected $search;
    protected $jenisKelamin;
    protected $jalur;
    protected $jenisRekap = 'ukuran';

    public function __construct($status = null, $search = null, $jenisKelamin = null, $jalur = null)
    {
        $this->status = $status;
        $this->search = $search;
        $this->jenisKelamin = $jenisKelamin;
        $this->jalur = $jalur;
    }

    public function query()
    {
        return $this->queryPendaftarWajib($this->status, $this->jenisRekap ?? 'ukuran', $this->search, $this->jalur ?? null, $this->jenisKelamin ?? null);
    }

    public function map($pendaftar): array
    {
        // Pendaftar yang belum mengisi seragam tetap tampil (kolom seragam kosong)
        $seragam = $pendaftar->seragam ?? new Seragam();

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
            $this->namaPaket($seragam->paket_seragam),
            RekapDaftarUlang::label(RekapDaftarUlang::kategori($pendaftar, 'ukuran')),
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
            'Status Rekap',
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

    /** Nama paket yang terbaca (paket_seragam disimpan sebagai JSON atau "paket_lainnya"). */
    private function namaPaket($paket): string
    {
        if (empty($paket)) {
            return 'Belum Dipilih';
        }
        if ($paket === 'paket_lainnya') {
            return 'Paket Lainnya (Manual)';
        }
        $data = is_array($paket) ? $paket : json_decode((string) $paket, true);

        return is_array($data) && isset($data['name']) ? $data['name'] : (string) $paket;
    }

    private function getStatusLabel($status)
    {
        return RekapDaftarUlang::labelStatusProses($status);
    }
}