<?php

namespace App\Support;

use App\Models\DaftarUlang;
use App\Models\Pendaftar;
use Illuminate\Support\Facades\DB;
use RuntimeException;

/**
 * Satu-satunya tempat untuk mengubah persetujuan melanjutkan daftar ulang
 * (dipakai pendaftar, admin dan panitia), agar ketiga kondisi selalu konsisten:
 *
 *  - setuju        : status = setuju, setuju_daftar_ulang = true, ada record daftar_ulang
 *  - tidak_setuju  : status = tidak_setuju, wajib ada alasan, setuju_daftar_ulang = false
 *  - belum_memilih : status = belum_memilih, tanggal & alasan kosong
 *
 * Semua perubahan memakai transaksi + kunci baris, sehingga klik ganda atau dua tab
 * tidak menghasilkan data ganda / setengah tersimpan.
 */
class PersetujuanDaftarUlang
{
    public const STATUS = [
        'belum_memilih' => 'Belum Memilih',
        'setuju' => 'Setuju Melanjutkan',
        'tidak_setuju' => 'Tidak Melanjutkan',
    ];

    public const ALASAN_MAKS = 500;

    /** Alasan bawaan bila petugas menandai "tidak melanjutkan" tanpa menulis alasan. */
    public const ALASAN_OLEH_PETUGAS = 'Ditandai oleh admin/panitia (alasan tidak dicatat)';

    /**
     * Status persetujuan yang sudah dinormalkan. Data lama yang hanya punya
     * setuju_daftar_ulang = true (sebelum kolom status ada) tetap dianggap "setuju".
     */
    public static function status(Pendaftar $pendaftar): string
    {
        $status = (string) $pendaftar->status_persetujuan_daftar_ulang;

        if ($status === 'setuju' || $status === 'tidak_setuju') {
            return $status;
        }

        return $pendaftar->setuju_daftar_ulang ? 'setuju' : 'belum_memilih';
    }

    public static function label(?string $status): string
    {
        return self::STATUS[$status ?: 'belum_memilih'] ?? ucwords(str_replace('_', ' ', (string) $status));
    }

    /** Hanya pendaftar lulus/diterima yang boleh (dan wajib) memilih. */
    public static function wajibMemilih(Pendaftar $pendaftar): bool
    {
        return in_array($pendaftar->status_pendaftaran, RekapDaftarUlang::STATUS_WAJIB, true);
    }

    /**
     * Pendaftar menyatakan setuju melanjutkan daftar ulang.
     *
     * @throws RuntimeException pesan siap ditampilkan ke pengguna
     */
    public static function pilihSetuju(Pendaftar $pendaftar): Pendaftar
    {
        return DB::transaction(function () use ($pendaftar) {
            $baris = self::kunci($pendaftar);
            self::pastikanBolehMemilih($baris);

            self::simpanSetuju($baris);

            return $baris->fresh();
        });
    }

    /**
     * Pendaftar menyatakan tidak melanjutkan, wajib dengan alasan.
     *
     * @throws RuntimeException
     */
    public static function pilihTidakSetuju(Pendaftar $pendaftar, ?string $alasan): Pendaftar
    {
        $alasan = self::bersihkanAlasan($alasan);
        if ($alasan === '') {
            throw new RuntimeException('Silakan isi alasan mengapa tidak melanjutkan daftar ulang.');
        }

        return DB::transaction(function () use ($pendaftar, $alasan) {
            $baris = self::kunci($pendaftar);
            self::pastikanBolehMemilih($baris);

            self::simpanTidakSetuju($baris, $alasan);

            return $baris->fresh();
        });
    }

    /**
     * Admin/panitia mengubah persetujuan pendaftar.
     *
     * @throws RuntimeException
     */
    public static function ubahOlehPetugas(Pendaftar $pendaftar, ?string $status, ?string $alasan = null): Pendaftar
    {
        if (!isset(self::STATUS[(string) $status])) {
            throw new RuntimeException('Status persetujuan tidak dikenal.');
        }

        if ($status === 'belum_memilih') {
            return self::reset($pendaftar);
        }

        return DB::transaction(function () use ($pendaftar, $status, $alasan) {
            $baris = self::kunci($pendaftar);

            if (!self::wajibMemilih($baris)) {
                throw new RuntimeException('Persetujuan daftar ulang hanya untuk pendaftar berstatus Lulus/Diterima.');
            }

            if ($status === 'setuju') {
                self::simpanSetuju($baris);
            } else {
                $alasan = self::bersihkanAlasan($alasan)
                    ?: self::bersihkanAlasan($baris->alasan_tidak_setuju)
                    ?: self::ALASAN_OLEH_PETUGAS;
                self::simpanTidakSetuju($baris, $alasan);
            }

            return $baris->fresh();
        });
    }

    /**
     * Kembalikan ke "belum memilih" agar pendaftar dapat memilih ulang.
     * Record daftar ulang hanya dihapus bila masih kosong, supaya bukti bayar /
     * surat yang sudah diunggah tidak ikut hilang.
     */
    public static function reset(Pendaftar $pendaftar): Pendaftar
    {
        return DB::transaction(function () use ($pendaftar) {
            $baris = self::kunci($pendaftar);

            $baris->forceFill([
                'status_persetujuan_daftar_ulang' => 'belum_memilih',
                'setuju_daftar_ulang' => false,
                'tanggal_pilihan_daftar_ulang' => null,
                'alasan_tidak_setuju' => null,
            ])->save();

            $daftarUlang = DaftarUlang::where('pendaftar_id', $baris->id)->first();
            if ($daftarUlang && self::daftarUlangKosong($daftarUlang)) {
                $daftarUlang->delete();
            }

            return $baris->fresh();
        });
    }

    private static function simpanSetuju(Pendaftar $baris): void
    {
        $baris->forceFill([
            'status_persetujuan_daftar_ulang' => 'setuju',
            'setuju_daftar_ulang' => true,
            'tanggal_pilihan_daftar_ulang' => now(),
            'alasan_tidak_setuju' => null,
        ])->save();

        // Record daftar ulang dipakai halaman daftar ulang pendaftar; data lama (jika ada) dipertahankan
        DaftarUlang::firstOrCreate(
            ['pendaftar_id' => $baris->id],
            ['status' => 'belum_diisi', 'batas_daftar_ulang' => now()->addMonths(2)]
        );
    }

    private static function simpanTidakSetuju(Pendaftar $baris, string $alasan): void
    {
        $baris->forceFill([
            'status_persetujuan_daftar_ulang' => 'tidak_setuju',
            'setuju_daftar_ulang' => false,
            'tanggal_pilihan_daftar_ulang' => now(),
            'alasan_tidak_setuju' => $alasan,
        ])->save();
    }

    private static function pastikanBolehMemilih(Pendaftar $baris): void
    {
        if (!self::wajibMemilih($baris)) {
            throw new RuntimeException('Hanya pendaftar yang dinyatakan LULUS yang dapat membuat pilihan daftar ulang.');
        }

        if (self::status($baris) !== 'belum_memilih') {
            throw new RuntimeException('Anda sudah membuat pilihan daftar ulang sebelumnya. Hubungi panitia bila perlu mengubahnya.');
        }
    }

    private static function kunci(Pendaftar $pendaftar): Pendaftar
    {
        return Pendaftar::whereKey($pendaftar->getKey())->lockForUpdate()->firstOrFail();
    }

    private static function daftarUlangKosong(DaftarUlang $daftarUlang): bool
    {
        return in_array($daftarUlang->status, [null, '', 'belum_diisi'], true)
            && empty($daftarUlang->bukti_pembayaran_madrasah)
            && empty($daftarUlang->surat_pernyataan_path);
    }

    private static function bersihkanAlasan(?string $alasan): string
    {
        $alasan = trim(preg_replace('/\s+/u', ' ', strip_tags((string) $alasan)));

        return mb_substr($alasan, 0, self::ALASAN_MAKS);
    }
}
