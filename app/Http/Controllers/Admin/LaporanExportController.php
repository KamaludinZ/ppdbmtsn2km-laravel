<?php

namespace App\Http\Controllers\Admin;

use App\Exports\DaftarUlangExport;
use App\Exports\DaftarUlangSeragamExport;
use App\Exports\PendaftarExport;
use App\Exports\SeragamExport;
use App\Exports\TesPsikologiExport;
use App\Http\Controllers\Controller;
use App\Support\RekapDaftarUlang;
use App\Support\RekapPendaftaran;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Unduh Excel dari menu Laporan admin lewat link biasa (bukan aksi Livewire),
 * sehingga halaman tidak "terkunci" selama file dibuat dan browser menampilkan progres unduhan.
 */
class LaporanExportController extends Controller
{
    public function __invoke(Request $request, string $jenis)
    {
        // Nilai filter yang tidak dikenal diabaikan (bukan redirect), karena ini link unduhan
        $jalur = is_string($request->query('jalur')) && ctype_digit($request->query('jalur')) ? (int) $request->query('jalur') : null;
        $status = is_string($request->query('status')) ? substr($request->query('status'), 0, 50) : null;
        $jk = in_array($request->query('jk'), ['L', 'P'], true) ? $request->query('jk') : null;
        $kategori = $status && isset(RekapDaftarUlang::KATEGORI[$status]) ? $status : null;

        // File besar (200+ kolom) butuh waktu & memori lebih dari batas default
        @set_time_limit(300);
        @ini_set('memory_limit', '1024M');

        $waktu = now()->format('Y-m-d_His');

        [$export, $nama] = match ($jenis) {
            'data-lengkap' => [
                new PendaftarExport($jalur, $this->statusKelulusan($status), null),
                "data_pendaftar_lengkap_{$waktu}.xlsx",
            ],
            'daftar-ulang' => [
                new DaftarUlangExport($kategori, null, $jalur),
                "data_daftar_ulang_{$waktu}.xlsx",
            ],
            'daftar-ulang-seragam' => [
                new DaftarUlangSeragamExport($kategori, null, $jalur, $jk),
                "data_daftar_ulang_seragam_{$waktu}.xlsx",
            ],
            'seragam' => [
                new SeragamExport($kategori, null, $jk, $jalur),
                "data_seragam_ukuran_{$waktu}.xlsx",
            ],
            'tes-psikologi' => [
                new TesPsikologiExport($jalur, in_array($status, ['belum_upload', 'menunggu', 'disetujui', 'ditolak', 'perbaiki'], true) ? $status : null),
                "laporan_tes_psikologi_{$waktu}.xlsx",
            ],
            default => abort(404),
        };

        return Excel::download($export, $nama);
    }

    /** Filter status kelulusan tab Data Lengkap: "lulus" mencakup lulus & diterima. */
    private function statusKelulusan(?string $status): ?array
    {
        return match ($status) {
            'lulus' => RekapPendaftaran::STATUS_LULUS,
            'tidak_lulus', 'cadangan' => [$status],
            default => null,
        };
    }
}
