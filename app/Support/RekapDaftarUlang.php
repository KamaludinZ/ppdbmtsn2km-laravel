<?php

namespace App\Support;

use App\Models\Pendaftar;
use Illuminate\Support\Collection;

/**
 * Rekap daftar ulang yang konsisten untuk semua halaman (menu Daftar Ulang, Laporan, Export).
 *
 * Setiap pendaftar yang wajib daftar ulang masuk TEPAT SATU kategori, sehingga
 * jumlah semua kategori selalu sama dengan total — termasuk pendaftar yang belum
 * mengonfirmasi atau memilih tidak melanjutkan.
 */
class RekapDaftarUlang
{
    /**
     * Status pendaftaran yang wajib daftar ulang. Sama dengan syarat di dashboard pendaftar:
     * hanya pendaftar lulus/diterima yang bisa konfirmasi daftar ulang.
     */
    public const STATUS_WAJIB = ['lulus', 'diterima'];

    /** Urutan kategori => label & warna badge. */
    public const KATEGORI = [
        'belum_konfirmasi' => ['label' => 'Belum Konfirmasi', 'color' => 'gray'],
        'tidak_melanjutkan' => ['label' => 'Tidak Melanjutkan', 'color' => 'rose'],
        'belum_mengisi' => ['label' => 'Setuju, Belum Mengisi', 'color' => 'amber'],
        'menunggu_verifikasi' => ['label' => 'Menunggu Verifikasi', 'color' => 'blue'],
        'perlu_perbaikan' => ['label' => 'Perlu Perbaikan', 'color' => 'orange'],
        'ditolak' => ['label' => 'Ditolak', 'color' => 'red'],
        'selesai' => ['label' => 'Selesai Daftar Ulang', 'color' => 'emerald'],
    ];

    /**
     * Tentukan kategori pendaftar.
     *
     * @param  string  $jenis  madrasah | seragam | ukuran
     */
    public static function kategori(Pendaftar $pendaftar, string $jenis = 'madrasah'): string
    {
        $persetujuan = $pendaftar->status_persetujuan_daftar_ulang ?: 'belum_memilih';

        if ($persetujuan === 'tidak_setuju') {
            return 'tidak_melanjutkan';
        }

        $status = match ($jenis) {
            'seragam' => $pendaftar->seragam?->status_seragam,
            'ukuran' => $pendaftar->seragam?->status_ukuran,
            default => $pendaftar->daftarUlang?->status,
        };

        // Belum konfirmasi = belum menyatakan setuju DAN belum ada proses daftar ulang.
        // (Jika admin sudah memproses daftar ulangnya, status proses yang dipakai.)
        if ($persetujuan !== 'setuju' && in_array($status, [null, '', 'belum_diisi'], true)) {
            return 'belum_konfirmasi';
        }

        return match ($status) {
            'menunggu', 'proses' => 'menunggu_verifikasi',
            'perbaiki', 'perlu_perbaikan' => 'perlu_perbaikan',
            'ditolak' => 'ditolak',
            'disetujui', 'selesai' => 'selesai',
            default => 'belum_mengisi', // tidak ada data / belum_diisi
        };
    }

    /**
     * Ambil semua pendaftar wajib daftar ulang (dengan relasi yang dibutuhkan rekap).
     *
     * @param  array  $filter  jalur (id), jenis_kelamin (L/P)
     */
    public static function pendaftarWajib(array $filter = []): Collection
    {
        return Pendaftar::query()
            ->select(['id', 'status_pendaftaran', 'status_persetujuan_daftar_ulang', 'jalur_pendaftaran_id', 'jenis_kelamin'])
            ->with([
                'daftarUlang:id,pendaftar_id,status,surat_pernyataan_path',
                'seragam:id,pendaftar_id,status_seragam,status_ukuran',
            ])
            ->whereIn('status_pendaftaran', self::STATUS_WAJIB)
            ->when($filter['jalur'] ?? null, fn ($q, $jalur) => $q->where('jalur_pendaftaran_id', $jalur))
            ->when($filter['jenis_kelamin'] ?? null, fn ($q, $jk) => $q->where('jenis_kelamin', $jk))
            ->get();
    }

    /**
     * Rekap lengkap untuk kartu statistik per tab (madrasah | seragam | ukuran).
     */
    public static function statsTab(string $tab, array $filter = []): array
    {
        $wajib = self::pendaftarWajib($filter);
        $stats = self::stats($wajib, $tab === 'ukuran' ? 'ukuran' : ($tab === 'seragam' ? 'seragam' : 'madrasah'));
        $stats['total_pendaftar'] = Pendaftar::count();
        $stats['total_lulus'] = $stats['total'];

        if ($tab === 'seragam') {
            $ukuran = self::stats($wajib, 'ukuran');
            $stats['ukuran_menunggu'] = $ukuran['menunggu_verifikasi'];
            $stats['ukuran_disetujui'] = $ukuran['selesai'];
            $stats['ukuran_ditolak'] = $ukuran['ditolak'];
        }

        return $stats;
    }

    /**
     * Filter query Pendaftar ke satu kategori rekap (aturan sama dengan kategori()).
     */
    public static function filterKategori($query, ?string $kategori, string $jenis = 'madrasah')
    {
        if (!$kategori || !isset(self::KATEGORI[$kategori])) {
            return $query;
        }

        [$relation, $column] = match ($jenis) {
            'seragam' => ['seragam', 'status_seragam'],
            'ukuran' => ['seragam', 'status_ukuran'],
            default => ['daftarUlang', 'status'],
        };

        $statusIn = fn (array $values) => fn ($q) => $q->whereIn($column, $values);
        $belumAdaProses = fn ($q) => $q->whereDoesntHave($relation, fn ($r) => $r->whereNotNull($column)
            ->whereNotIn($column, ['', 'belum_diisi']));
        $bukanTidakSetuju = fn ($q) => $q->where(fn ($w) => $w->whereNull('status_persetujuan_daftar_ulang')
            ->orWhere('status_persetujuan_daftar_ulang', '!=', 'tidak_setuju'));

        return match ($kategori) {
            'tidak_melanjutkan' => $query->where('status_persetujuan_daftar_ulang', 'tidak_setuju'),
            'belum_konfirmasi' => $query->where(fn ($w) => $w->whereNull('status_persetujuan_daftar_ulang')
                ->orWhereNotIn('status_persetujuan_daftar_ulang', ['setuju', 'tidak_setuju']))->where($belumAdaProses),
            'belum_mengisi' => $query->where('status_persetujuan_daftar_ulang', 'setuju')->where($belumAdaProses),
            'menunggu_verifikasi' => $query->where($bukanTidakSetuju)->whereHas($relation, $statusIn(['menunggu', 'proses'])),
            'perlu_perbaikan' => $query->where($bukanTidakSetuju)->whereHas($relation, $statusIn(['perbaiki', 'perlu_perbaikan'])),
            'ditolak' => $query->where($bukanTidakSetuju)->whereHas($relation, $statusIn(['ditolak'])),
            'selesai' => $query->where($bukanTidakSetuju)->whereHas($relation, $statusIn(['disetujui', 'selesai'])),
        };
    }

    /** Label status proses mentah (daftar_ulang.status / status_seragam / status_ukuran). */
    public static function labelStatusProses(?string $status): string
    {
        return match ($status) {
            null, '', 'belum_diisi' => 'Belum Mengisi',
            'menunggu', 'proses' => 'Menunggu Verifikasi',
            'perbaiki', 'perlu_perbaikan' => 'Perlu Perbaikan',
            'ditolak' => 'Ditolak',
            'disetujui', 'selesai' => 'Disetujui',
            default => ucwords(str_replace('_', ' ', $status)),
        };
    }

    public static function label(string $kategori): string
    {
        return self::KATEGORI[$kategori]['label'] ?? ucfirst(str_replace('_', ' ', $kategori));
    }

    /**
     * Hitung rekap. Mengembalikan key baru (per kategori) sekaligus key lama
     * yang dipakai kartu statistik (setuju, tidak_setuju, menunggu, dst).
     */
    public static function stats(Collection $pendaftar, string $jenis = 'madrasah'): array
    {
        $counts = array_fill_keys(array_keys(self::KATEGORI), 0);
        foreach ($pendaftar as $p) {
            $counts[self::kategori($p, $jenis)]++;
        }

        $total = $pendaftar->count();
        $setuju = $total - $counts['belum_konfirmasi'] - $counts['tidak_melanjutkan'];

        $stats = $counts + [
            'total' => $total,
            'total_wajib' => $total,
            'lulus' => $total,
            'setuju' => $setuju,
            'tidak_setuju' => $counts['tidak_melanjutkan'],
            'sudah_daftar_ulang' => $counts['selesai'],
            'belum_daftar_ulang' => $total - $counts['selesai'],
            // key lama untuk kartu statistik
            'menunggu' => $counts['menunggu_verifikasi'],
            'disetujui' => $counts['selesai'],
            'perlu_perbaikan' => $counts['perlu_perbaikan'],
        ];

        if ($jenis === 'madrasah') {
            $stats['upload_surat'] = $pendaftar->filter(fn ($p) => $p->daftarUlang?->surat_pernyataan_path)->count();
        }

        return $stats;
    }
}
