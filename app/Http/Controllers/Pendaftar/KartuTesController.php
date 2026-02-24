<?php

namespace App\Http\Controllers\Pendaftar;

use App\Http\Controllers\Controller;
use App\Models\Pendaftar;
use App\Models\Setting;
use App\Models\JadwalTes;
use App\Models\KartuKendali;
use App\Models\Province;
use App\Models\Regency;
use App\Models\District;
use App\Models\Village;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class KartuTesController extends Controller
{
    /**
     * Download Kartu Tes Seleksi PDF (untuk pendaftar sendiri)
     */
    public function downloadKartuTes()
    {
        // Get pendaftar yang sedang login
        $user = Auth::user();
        $pendaftar = Pendaftar::with(['jalurPendaftaran', 'tesPsikologiPendaftar', 'prestasis'])
            ->where('user_id', $user->id)
            ->firstOrFail();

        // Validasi status pendaftaran (harus sudah disetujui)
        if (!in_array($pendaftar->status_pendaftaran, ['data_disetujui', 'lulus', 'diterima', 'tidak_lulus', 'ditolak', 'cadangan'])) {
            abort(403, 'Kartu tes belum tersedia. Menunggu persetujuan data.');
        }

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
            'Content-Disposition' => 'attachment; filename="kartu-tes-seleksi-' . $pendaftar->nomor_pendaftaran . '.pdf"',
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
