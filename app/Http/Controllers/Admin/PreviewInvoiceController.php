<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pendaftar;
use App\Models\Setting;
use App\Models\TesPsikologiOpsi;
use App\Models\TesPsikologiBank;
use App\Models\JadwalTes;
use App\Models\KartuKendali;
use App\Models\Province;
use App\Models\Regency;
use App\Models\District;
use App\Models\Village;
use Barryvdh\DomPDF\Facade\Pdf;

class PreviewInvoiceController extends Controller
{
    /**
     * Preview Invoice Tes Psikologi
     */
    public function previewInvoicePsikologi($pendaftar_id)
    {
        $pendaftar = Pendaftar::with(['tesPsikologiPendaftar'])->findOrFail($pendaftar_id);
        $tesPsikologiData = $pendaftar->tesPsikologiPendaftar;

        // Jika data kosong atau tidak lengkap, tetap generate dengan data seadanya
        $printSettings = [
            'print_institution_name' => Setting::get('print_institution_name'),
            'print_institution_address' => Setting::get('print_institution_address'),
            'print_institution_phone' => Setting::get('print_institution_phone'),
            'print_institution_email' => Setting::get('print_institution_email'),
        ];

        $invoiceSettings = [
            'nomor_format' => Setting::get('invoice_psikologi_nomor_format', 'INV-PSI-{TAHUN}-{NOMOR}'),
            'footer' => Setting::get('invoice_psikologi_footer', ''),
        ];

        // Load opsi dan bank (jika ada)
        $opsi = null;
        $bank = null;
        if ($tesPsikologiData) {
            $opsi = TesPsikologiOpsi::find($tesPsikologiData->opsi_id);
            $bank = TesPsikologiBank::find($tesPsikologiData->bank_id);
        }

        $pdf = Pdf::loadView('pdf.invoice-psikologi', [
            'pendaftar' => $pendaftar,
            'tesPsikologiData' => $tesPsikologiData,
            'printSettings' => $printSettings,
            'invoiceSettings' => $invoiceSettings,
            'opsi' => $opsi,
            'bank' => $bank,
        ]);

        $pdf->setPaper('A5', 'landscape');

        return response()->stream(function () use ($pdf) {
            echo $pdf->output();
        }, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="invoice-tes-psikologi-' . $pendaftar->nomor_pendaftaran . '.pdf"',
        ]);
    }

    /**
     * Preview Invoice Daftar Ulang Madrasah
     */
    public function previewInvoiceMadrasah($pendaftar_id)
    {
        $pendaftar = Pendaftar::with(['daftarUlang'])->findOrFail($pendaftar_id);
        $daftarUlang = $pendaftar->daftarUlang;

        // Tetap generate meskipun data kosong atau tidak lengkap
        $printSettings = [
            'print_institution_name' => Setting::get('print_institution_name'),
            'print_institution_address' => Setting::get('print_institution_address'),
            'print_institution_phone' => Setting::get('print_institution_phone'),
            'print_institution_email' => Setting::get('print_institution_email'),
        ];

        $invoiceSettings = [
            'nomor_format' => Setting::get('invoice_madrasah_nomor_format', 'INV-MDR-{TAHUN}-{NOMOR}'),
            'footer' => Setting::get('invoice_madrasah_footer', ''),
        ];

        $details = Setting::get('daftar_ulang_madrasah_nominals', []);
        $banks = Setting::get('daftar_ulang_madrasah_banks', []);

        // Find selected bank or default
        $bank = [];
        if ($daftarUlang && $daftarUlang->metode_pembayaran) {
            $bank = collect($banks)->firstWhere('name', $daftarUlang->metode_pembayaran) ?? ($banks[0] ?? []);
        } elseif (!empty($banks)) {
            $bank = $banks[0];
        }

        $pdf = Pdf::loadView('pdf.invoice-daftar-ulang-madrasah', [
            'pendaftar' => $pendaftar,
            'daftarUlang' => $daftarUlang,
            'printSettings' => $printSettings,
            'invoiceSettings' => $invoiceSettings,
            'details' => $details,
            'bank' => $bank,
        ]);

        $pdf->setPaper('A5', 'landscape');

        return response()->stream(function () use ($pdf) {
            echo $pdf->output();
        }, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="invoice-du-madrasah-' . $pendaftar->nomor_pendaftaran . '.pdf"',
        ]);
    }

    /**
     * Preview Invoice Daftar Ulang Seragam
     */
    public function previewInvoiceSeragam($pendaftar_id)
    {
        $pendaftar = Pendaftar::with(['seragam'])->findOrFail($pendaftar_id);
        $seragam = $pendaftar->seragam;

        // Tetap generate meskipun data kosong atau tidak lengkap
        $printSettings = [
            'print_institution_name' => Setting::get('print_institution_name'),
            'print_institution_address' => Setting::get('print_institution_address'),
            'print_institution_phone' => Setting::get('print_institution_phone'),
            'print_institution_email' => Setting::get('print_institution_email'),
        ];

        $invoiceSettings = [
            'nomor_format' => Setting::get('invoice_seragam_nomor_format', 'INV-SRG-{TAHUN}-{NOMOR}'),
            'footer' => Setting::get('invoice_seragam_footer', ''),
        ];

        $details = Setting::get('daftar_ulang_seragam_nominals', []);

        // Get Bank Info
        $banks = Setting::get('daftar_ulang_seragam_banks', []);
        $bank = !empty($banks) ? $banks[0] : [];

        $pdf = Pdf::loadView('pdf.invoice-daftar-ulang-seragam', [
            'pendaftar' => $pendaftar,
            'seragam' => $seragam,
            'printSettings' => $printSettings,
            'invoiceSettings' => $invoiceSettings,
            'details' => $details,
            'bank' => $bank,
        ]);

        $pdf->setPaper('A5', 'landscape');

        return response()->stream(function () use ($pdf) {
            echo $pdf->output();
        }, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="invoice-du-seragam-' . $pendaftar->nomor_pendaftaran . '.pdf"',
        ]);
    }

    /**
     * Preview/Download Kartu Tes Seleksi PDF
     */
    public function previewKartuTes($pendaftar_id)
    {
        $pendaftar = Pendaftar::with(['jalurPendaftaran', 'tesPsikologiPendaftar', 'prestasis'])->findOrFail($pendaftar_id);

        // Generate alamat lengkap
        $alamatLengkap = $this->generateAlamatLengkap($pendaftar);

        // Load print settings
        $printSettings = [
            'print_logo_left' => Setting::get('print_logo_left'),
            'print_logo_right' => Setting::get('print_logo_right'),
            'print_institution_name' => Setting::get('print_institution_name'),
            'print_institution_address' => Setting::get('print_institution_address'),
            'print_institution_phone' => Setting::get('print_institution_phone'),
            'print_institution_email' => Setting::get('print_institution_email'),
            'print_institution_website' => Setting::get('print_institution_website'),
            'tahun_pelajaran' => Setting::get('tahun_pelajaran', date('Y') . '/' . (date('Y') + 1)),
        ];

        // Load jadwal tes and kartu kendali
        $jadwalTesList = JadwalTes::orderBy('urutan', 'asc')->get();
        $kartuKendaliList = KartuKendali::orderBy('urutan', 'asc')->get();

        // Load catatan kartu tes seleksi
        $catatanKartuTesSeleksi = Setting::get('catatan_kartu_ujian', '');

        $pdf = Pdf::loadView('pdf.kartu-tes-seleksi', [
            'pendaftar' => $pendaftar,
            'alamatLengkap' => $alamatLengkap,
            'printSettings' => $printSettings,
            'jadwalTesList' => $jadwalTesList,
            'kartuKendaliList' => $kartuKendaliList,
            'catatanKartuTesSeleksi' => $catatanKartuTesSeleksi,
        ]);

        $pdf->setPaper('A4', 'portrait');

        return response()->stream(function () use ($pdf) {
            echo $pdf->output();
        }, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="kartu-tes-seleksi-' . $pendaftar->nomor_pendaftaran . '.pdf"',
        ]);
    }

    /**
     * Helper: Generate Alamat Lengkap
     */
    private function generateAlamatLengkap($pendaftar)
    {
        $parts = [];

        // 1. Alamat Jalan
        $alamatJalan = $pendaftar->siswa_alamat ?: $pendaftar->alamat;
        if ($alamatJalan) $parts[] = $alamatJalan;

        // 2. RT/RW
        $rt = $pendaftar->siswa_rt ?: $pendaftar->rt;
        $rw = $pendaftar->siswa_rw ?: $pendaftar->rw;
        if ($rt && $rw) {
            $parts[] = "RT. $rt / RW. $rw";
        }

        // 3. Desa / Kelurahan
        $desaId = $pendaftar->siswa_kelurahan ?: $pendaftar->desa;
        if ($desaId) {
            $desaName = is_numeric($desaId) ? Village::find($desaId)?->name : $desaId;
            if ($desaName) $parts[] = "Desa/Kel. $desaName";
        }

        // 4. Kecamatan
        $kecId = $pendaftar->siswa_kecamatan ?: $pendaftar->kecamatan;
        if ($kecId) {
            $kecName = is_numeric($kecId) ? District::find($kecId)?->name : $kecId;
            if ($kecName) $parts[] = "Kec. $kecName";
        }

        // 5. Kabupaten / Kota
        $kabId = $pendaftar->siswa_kabupaten ?: $pendaftar->kabupaten;
        if ($kabId) {
            $kabName = is_numeric($kabId) ? Regency::find($kabId)?->name : $kabId;
            if ($kabName) $parts[] = $kabName;
        }

        // 6. Provinsi
        $provId = $pendaftar->siswa_provinsi ?: $pendaftar->provinsi;
        if ($provId) {
            $provName = is_numeric($provId) ? Province::find($provId)?->name : $provId;
            if ($provName) $parts[] = $provName;
        }

        return !empty($parts) ? implode(', ', $parts) : '-';
    }
}
