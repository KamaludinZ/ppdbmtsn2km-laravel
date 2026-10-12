<?php

namespace App\Support;

use App\Models\JalurPendaftaran;
use App\Models\Pendaftar;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Rekap pendaftaran per status & per jalur untuk menu Laporan, export Excel dan PDF.
 *
 * Satu query GROUP BY (bukan belasan subquery per jalur), dan jalur yang sudah
 * dinonaktifkan tetap ikut bila masih punya pendaftar — sehingga total per jalur
 * selalu sama dengan total pendaftar.
 */
class RekapPendaftaran
{
    /** Status yang dihitung "Data Disetujui" (sudah lolos verifikasi berkas). */
    public const STATUS_DISETUJUI = ['data_disetujui', 'lulus', 'diterima', 'tidak_lulus', 'cadangan', 'ditolak'];

    public const STATUS_LULUS = ['lulus', 'diterima'];

    /**
     * @param  array  $filter  jalur, start_date, end_date, status
     */
    public static function query(array $filter = []): Builder
    {
        return Pendaftar::query()
            ->when($filter['jalur'] ?? null, fn ($q, $v) => $q->where('jalur_pendaftaran_id', $v))
            ->when($filter['status'] ?? null, fn ($q, $v) => $q->whereIn('status_pendaftaran', (array) $v))
            ->when($filter['start_date'] ?? null, fn ($q, $v) => $q->whereDate('tanggal_daftar', '>=', $v))
            ->when($filter['end_date'] ?? null, fn ($q, $v) => $q->whereDate('tanggal_daftar', '<=', $v));
    }

    /**
     * Ringkasan kartu statistik (total & per status verifikasi).
     */
    public static function ringkasan(array $filter = []): array
    {
        $perStatus = self::query($filter)
            ->select('status_pendaftaran', DB::raw('COUNT(*) AS jumlah'))
            ->groupBy('status_pendaftaran')
            ->pluck('jumlah', 'status_pendaftaran');

        $sum = fn (array $status) => (int) $perStatus->only($status)->sum();

        return [
            'total' => (int) $perStatus->sum(),
            'menunggu_verifikasi' => $sum(['menunggu_verifikasi']),
            'lengkapi_data' => $sum(['lengkapi_data']),
            'perbaiki_data' => $sum(['perbaiki_data']),
            'data_ditolak' => $sum(['data_ditolak']),
            'data_disetujui' => $sum(self::STATUS_DISETUJUI),
            'lulus' => $sum(self::STATUS_LULUS),
            'tidak_lulus' => $sum(['tidak_lulus']),
            'cadangan' => $sum(['cadangan']),
        ];
    }

    /**
     * Statistik per jalur. Hasilnya model JalurPendaftaran dengan atribut *_count
     * (nama sama seperti withCount lama) agar view, Excel dan PDF tetap kompatibel.
     */
    public static function perJalur(array $filter = []): Collection
    {
        $in = fn (array $status) => "SUM(CASE WHEN status_pendaftaran IN ('" . implode("','", $status) . "') THEN 1 ELSE 0 END)";

        $baris = self::query($filter)
            ->select('jalur_pendaftaran_id')
            ->selectRaw('COUNT(*) AS pendaftar_count')
            ->selectRaw($in(['menunggu_verifikasi']) . ' AS menunggu_count')
            ->selectRaw($in(['lengkapi_data']) . ' AS lengkapi_count')
            ->selectRaw($in(['perbaiki_data']) . ' AS perbaiki_count')
            ->selectRaw($in(['data_ditolak']) . ' AS ditolak_count')
            ->selectRaw($in(self::STATUS_DISETUJUI) . ' AS disetujui_count')
            ->selectRaw($in(self::STATUS_LULUS) . ' AS lulus_count')
            ->selectRaw($in(['tidak_lulus']) . ' AS tidak_lulus_count')
            ->selectRaw($in(['cadangan']) . ' AS cadangan_count')
            ->selectRaw("SUM(CASE WHEN jenis_kelamin = 'L' THEN 1 ELSE 0 END) AS laki_laki_count")
            ->selectRaw("SUM(CASE WHEN jenis_kelamin = 'P' THEN 1 ELSE 0 END) AS perempuan_count")
            ->selectRaw("SUM(CASE WHEN jenis_sekolah_asal = 'madrasah' THEN 1 ELSE 0 END) AS madrasah_count")
            ->selectRaw("SUM(CASE WHEN jenis_sekolah_asal = 'umum' THEN 1 ELSE 0 END) AS umum_count")
            ->groupBy('jalur_pendaftaran_id')
            ->get()
            ->keyBy('jalur_pendaftaran_id');

        $kolom = ['pendaftar_count', 'menunggu_count', 'lengkapi_count', 'perbaiki_count', 'ditolak_count', 'disetujui_count',
            'lulus_count', 'tidak_lulus_count', 'cadangan_count', 'laki_laki_count', 'perempuan_count', 'madrasah_count', 'umum_count'];

        return JalurPendaftaran::query()
            ->where(fn ($q) => $q->where('aktif', true)->orWhereIn('id', $baris->keys()))
            ->when($filter['jalur'] ?? null, fn ($q, $v) => $q->where('id', $v))
            ->ordered()
            ->get()
            ->each(function (JalurPendaftaran $jalur) use ($baris, $kolom) {
                $row = $baris->get($jalur->id);
                foreach ($kolom as $k) {
                    $jalur->setAttribute($k, (int) ($row->{$k} ?? 0));
                }
                $jalur->setAttribute('nonaktif', !$jalur->aktif);
            });
    }

    /**
     * Pilihan jalur untuk filter: jalur aktif + jalur nonaktif yang masih punya pendaftar.
     */
    /**
     * Persetujuan melanjutkan daftar ulang per jalur, dari pendaftar lulus/diterima.
     * Aturan status sama dengan PersetujuanDaftarUlang::status(): data lama yang hanya punya
     * setuju_daftar_ulang = true dihitung setuju. Setiap pendaftar masuk tepat satu kolom,
     * jadi setuju + tidak + belum = jumlah lulus/diterima.
     *
     * @return array{jalur: Collection, total: array}
     */
    public static function persetujuanPerJalur(array $filter = []): array
    {
        $belumPilih = "(status_persetujuan_daftar_ulang IS NULL OR status_persetujuan_daftar_ulang NOT IN ('setuju','tidak_setuju'))";
        $setuju = "(status_persetujuan_daftar_ulang = 'setuju' OR ({$belumPilih} AND setuju_daftar_ulang = true))";

        $baris = self::query(array_merge($filter, ['status' => RekapDaftarUlang::STATUS_WAJIB]))
            ->select('jalur_pendaftaran_id')
            ->selectRaw('COUNT(*) AS wajib')
            ->selectRaw("SUM(CASE WHEN {$setuju} THEN 1 ELSE 0 END) AS setuju")
            ->selectRaw("SUM(CASE WHEN status_persetujuan_daftar_ulang = 'tidak_setuju' THEN 1 ELSE 0 END) AS tidak_setuju")
            ->groupBy('jalur_pendaftaran_id')
            ->get()
            ->keyBy('jalur_pendaftaran_id');

        $persen = fn (int $n, int $dari) => $dari > 0 ? round($n / $dari * 100, 1) : 0;
        $susun = function (int $wajib, int $setuju, int $tidak) use ($persen) {
            $belum = $wajib - $setuju - $tidak;

            return [
                'wajib' => $wajib,
                'setuju' => $setuju, 'setuju_persen' => $persen($setuju, $wajib),
                'tidak_setuju' => $tidak, 'tidak_setuju_persen' => $persen($tidak, $wajib),
                'belum_memilih' => $belum, 'belum_memilih_persen' => $persen($belum, $wajib),
            ];
        };

        $jalur = JalurPendaftaran::query()
            ->where(fn ($q) => $q->where('aktif', true)->orWhereIn('id', $baris->keys()))
            ->when($filter['jalur'] ?? null, fn ($q, $v) => $q->where('id', $v))
            ->ordered()
            ->get(['id', 'nama', 'warna', 'aktif'])
            ->map(function ($j) use ($baris, $susun) {
                $row = $baris->get($j->id);

                return ['nama' => $j->nama, 'warna' => $j->warna, 'nonaktif' => !$j->aktif]
                    + $susun((int) ($row->wajib ?? 0), (int) ($row->setuju ?? 0), (int) ($row->tidak_setuju ?? 0));
            });

        $total = $susun((int) $baris->sum('wajib'), (int) $baris->sum('setuju'), (int) $baris->sum('tidak_setuju'));

        return ['jalur' => $jalur, 'total' => $total];
    }

    public static function pilihanJalur(): Collection
    {
        $dipakai = Pendaftar::query()->distinct()->pluck('jalur_pendaftaran_id')->filter();

        return JalurPendaftaran::query()
            ->where(fn ($q) => $q->where('aktif', true)->orWhereIn('id', $dipakai))
            ->ordered()
            ->get(['id', 'nama', 'aktif']);
    }
}
