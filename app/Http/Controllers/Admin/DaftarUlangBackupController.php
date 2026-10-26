<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DaftarUlang;
use App\Models\Seragam;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

/**
 * Backup file daftar ulang (surat pernyataan / bukti pembayaran) dalam satu ZIP.
 *
 * Semua file ikut tanpa melihat status, dikelompokkan per folder status
 * (Menunggu, Disetujui, dst.). Diunduh lewat link biasa (bukan aksi Livewire)
 * agar halaman tidak "terkunci" selama ZIP dibuat.
 */
class DaftarUlangBackupController extends Controller
{
    private const STATUS_LABEL = [
        'belum_diisi' => 'Belum Diisi',
        'menunggu' => 'Menunggu',
        'disetujui' => 'Disetujui',
        'ditolak' => 'Ditolak',
        'perbaiki' => 'Perbaiki',
    ];

    public function __invoke(string $jenis)
    {
        // [label, tab asal, daftar [status, path file, pendaftar]]
        [$label, $tab, $items] = match ($jenis) {
            'surat-pernyataan' => [
                'surat_pernyataan',
                'madrasah',
                DaftarUlang::with('pendaftar:id,nomor_pendaftaran,nama_lengkap')
                    ->whereNotNull('surat_pernyataan_path')->where('surat_pernyataan_path', '!=', '')
                    ->get()
                    ->map(fn ($du) => [$du->status, $du->surat_pernyataan_path, $du->pendaftar]),
            ],
            'bukti-pembayaran-madrasah' => [
                'bukti_pembayaran_madrasah',
                'madrasah',
                DaftarUlang::with('pendaftar:id,nomor_pendaftaran,nama_lengkap')
                    ->whereNotNull('bukti_pembayaran_madrasah')->where('bukti_pembayaran_madrasah', '!=', '')
                    ->get()
                    ->map(fn ($du) => [$du->status, $du->bukti_pembayaran_madrasah, $du->pendaftar]),
            ],
            'bukti-pembayaran-seragam' => [
                'bukti_pembayaran_seragam',
                'seragam',
                Seragam::with('pendaftar:id,nomor_pendaftaran,nama_lengkap')
                    ->whereNotNull('bukti_pembayaran')->where('bukti_pembayaran', '!=', '')
                    ->get()
                    ->map(fn ($s) => [$s->status_seragam, $s->bukti_pembayaran, $s->pendaftar]),
            ],
            default => abort(404),
        };

        $disk = Storage::disk('private');
        $items = $items->filter(fn ($item) => $item[2] !== null);

        if ($items->isEmpty()) {
            return redirect()->route('admin.daftar-ulang', ['activeTab' => $tab])
                ->with('error', 'Belum ada file ' . str_replace('_', ' ', $label) . ' untuk di-backup.');
        }

        // Banyak file bisa butuh waktu lebih dari batas default
        @set_time_limit(300);

        $zipPath = tempnam(sys_get_temp_dir(), 'backup_du_');
        $zip = new ZipArchive();

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            @unlink($zipPath);
            abort(500, 'Gagal membuat file ZIP.');
        }

        $hilang = [];

        foreach ($items as [$status, $path, $pendaftar]) {
            $folder = self::STATUS_LABEL[$status ?: 'belum_diisi'] ?? Str::headline((string) $status);
            $nomor = preg_replace('/[^A-Za-z0-9_-]/', '_', (string) $pendaftar->nomor_pendaftaran);
            $nama = $nomor . '_' . Str::slug($pendaftar->nama_lengkap);

            if (!$disk->exists($path)) {
                $hilang[] = "{$pendaftar->nomor_pendaftaran} - {$pendaftar->nama_lengkap} ({$folder}): {$path}";
                continue;
            }

            $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION)) ?: 'bin';
            $zip->addFile($disk->path($path), "{$folder}/{$nama}.{$extension}");
        }

        // Catat file yang terdaftar di database tetapi tidak ada di storage
        if ($hilang) {
            $zip->addFromString('FILE_TIDAK_DITEMUKAN.txt', "File berikut tercatat di database tetapi tidak ada di storage:\n\n" . implode("\n", $hilang) . "\n");
        }

        $zip->close();

        return response()
            ->download($zipPath, "backup_{$label}_" . now()->format('Y-m-d_His') . '.zip', ['Content-Type' => 'application/zip'])
            ->deleteFileAfterSend(true);
    }
}
