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
    public static function pilihanJalur(): Collection
    {
        $dipakai = Pendaftar::query()->distinct()->pluck('jalur_pendaftaran_id')->filter();

        return JalurPendaftaran::query()
            ->where(fn ($q) => $q->where('aktif', true)->orWhereIn('id', $dipakai))
            ->ordered()
            ->get(['id', 'nama', 'aktif']);
    }
}
