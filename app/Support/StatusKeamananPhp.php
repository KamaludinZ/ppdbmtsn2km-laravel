<?php

namespace App\Support;

use Illuminate\Support\Carbon;

/**
 * Pemeriksaan kesiapan server PHP terhadap PHP2Shell (CVE-2026-109618 & CVE-2026-109619)
 * untuk halaman Keamanan Sistem admin.
 *
 * Celahnya ada di inti PHP, jadi aplikasi hanya bisa mengurangi permukaan serangan;
 * perbaikan utamanya tetap update PHP di server. Halaman ini membantu admin melihat apakah
 * mitigasi sudah aktif dan apakah ada tanda-tanda serangan di log.
 */
class StatusKeamananPhp
{
    /** Tanggal rilis patch darurat PHP menurut advisory. */
    public const TANGGAL_PATCH = '2026-10-21';

    /** Cabang PHP yang masih mendapat patch resmi menurut advisory. */
    public const CABANG_DIDUKUNG = ['8.2', '8.3', '8.4', '8.5'];

    private const POLA_OOM = 'Allowed memory size of';

    /**
     * @return array<int, array{label: string, nilai: string, status: string, saran: string}>
     */
    public static function periksa(): array
    {
        $cabang = PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION;
        $build = self::tanggalBuild();
        $patch = Carbon::parse(self::TANGGAL_PATCH);
        $sapi = PHP_SAPI;
        $hasil = [];

        $hasil[] = in_array($cabang, self::CABANG_DIDUKUNG, true)
            ? self::item('Versi PHP', PHP_VERSION . " ({$sapi})", 'info',
                'Cabang ini masih mendapat patch resmi. Pastikan hosting sudah memasang rilis ' . $patch->locale('id')->translatedFormat('j F Y') . ' atau lebih baru.')
            : self::item('Versi PHP', PHP_VERSION . " ({$sapi})", 'bahaya',
                "Cabang {$cabang} sudah tidak mendapat patch resmi. Pindah ke PHP 8.3/8.4 lewat cPanel (Select PHP Version / MultiPHP Manager).");

        if ($build === null) {
            $hasil[] = self::item('Tanggal build PHP', 'tidak terbaca', 'waspada',
                'Tanyakan ke hosting apakah PHP sudah ditambal untuk CVE-2026-109618 & CVE-2026-109619.');
        } elseif ($build->lt($patch)) {
            $hasil[] = self::item('Tanggal build PHP', $build->locale('id')->translatedFormat('j F Y'), 'bahaya',
                'Build PHP lebih lama dari patch ' . $patch->locale('id')->translatedFormat('j F Y') . '. Minta hosting memperbarui PHP lalu restart PHP (php-fpm / lsphp).');
        } else {
            $hasil[] = self::item('Tanggal build PHP', $build->locale('id')->translatedFormat('j F Y'), 'aman',
                'Build sesudah tanggal patch. Tetap konfirmasi ke hosting bahwa versi ini memuat perbaikan PHP2Shell.');
        }

        $uploadProgress = filter_var(ini_get('session.upload_progress.enabled'), FILTER_VALIDATE_BOOLEAN);
        $hasil[] = $uploadProgress
            ? self::item('session.upload_progress.enabled', 'On', 'bahaya',
                $sapi === 'apache2handler'
                    ? 'Server memakai mod_php: setting di public/.htaccess (php_flag) belum terbaca. Matikan lewat cPanel MultiPHP INI Editor.'
                    : 'File public/.user.ini belum terbaca (tunggu ±5 menit atau restart PHP). Bila tetap On, matikan lewat cPanel MultiPHP INI Editor.')
            : self::item('session.upload_progress.enabled', 'Off', 'aman',
                'Jalur serangan CVE-2026-109618 tertutup. CVE-2026-109619 tetap butuh update PHP.');

        $htaccess = @file_get_contents(public_path('.htaccess')) ?: '';
        $hasil[] = str_contains($htaccess, '/upload-file[?')
            ? self::item('Pembatas upload (.htaccess)', 'terpasang', 'aman',
                'Permintaan multipart/form-data hanya diterima di endpoint upload Livewire.')
            : self::item('Pembatas upload (.htaccess)', 'tidak ditemukan', 'waspada',
                'public/.htaccess tidak memuat aturan pembatas multipart. Pastikan file dari repository ikut ter-upload.');

        $hasil[] = filter_var(ini_get('display_errors'), FILTER_VALIDATE_BOOLEAN) && $sapi !== 'cli'
            ? self::item('display_errors', 'On', 'waspada', 'Pesan error PHP bisa terlihat pengunjung. Matikan di cPanel MultiPHP INI Editor.')
            : self::item('display_errors', 'Off', 'aman', 'Pesan error tidak ditampilkan ke pengunjung.');

        return $hasil;
    }

    /**
     * Status terburuk dari hasil periksa(): bahaya > waspada > aman.
     */
    public static function ringkasan(array $hasil): string
    {
        $status = array_column($hasil, 'status');

        return in_array('bahaya', $status, true) ? 'bahaya' : (in_array('waspada', $status, true) ? 'waspada' : 'aman');
    }

    /**
     * Cari pesan "Allowed memory size of ... exhausted" (indikator serangan menurut advisory)
     * di log PHP & Laravel yang bisa dibaca aplikasi. Hanya bagian akhir tiap file yang dibaca.
     *
     * @return array{files: array<int, array{nama: string, jumlah: int}>, terakhir: array<int, string>}
     */
    public static function indikatorSerangan(int $maksBaca = 2_000_000): array
    {
        $kandidat = array_filter([
            ini_get('error_log') ?: null,
            public_path('error_log'),
            base_path('error_log'),
            storage_path('logs/php_errors.log'),
            ...array_slice(self::urutTerbaru(glob(storage_path('logs/laravel*.log')) ?: []), 0, 3),
        ]);

        $files = [];
        $terakhir = [];
        foreach (array_unique($kandidat) as $path) {
            if (!is_file($path) || !is_readable($path)) {
                continue;
            }

            $baris = array_filter(
                explode("\n", self::bacaAkhir($path, $maksBaca)),
                fn ($b) => str_contains($b, self::POLA_OOM)
            );

            $files[] = ['nama' => self::namaTampil($path), 'jumlah' => count($baris)];
            foreach (array_slice($baris, -5) as $b) {
                $terakhir[] = mb_strimwidth(trim($b), 0, 220, '…');
            }
        }

        return ['files' => $files, 'terakhir' => array_slice($terakhir, -5)];
    }

    /**
     * Tanggal build PHP dari phpinfo() (bila fungsi tidak dinonaktifkan hosting).
     */
    public static function tanggalBuild(): ?Carbon
    {
        if (!function_exists('phpinfo')) {
            return null;
        }

        ob_start();
        phpinfo(INFO_GENERAL);
        $info = strip_tags((string) ob_get_clean());

        if (!preg_match('/Build Date\s*(?:=>)?\s*([A-Z][a-z]{2}\s+\d{1,2}\s+\d{4})/', $info, $m)) {
            return null;
        }

        try {
            return Carbon::createFromFormat('M j Y', preg_replace('/\s+/', ' ', $m[1]))->startOfDay();
        } catch (\Throwable) {
            return null;
        }
    }

    private static function item(string $label, string $nilai, string $status, string $saran): array
    {
        return compact('label', 'nilai', 'status', 'saran');
    }

    private static function bacaAkhir(string $path, int $maks): string
    {
        $size = filesize($path) ?: 0;
        $fh = fopen($path, 'rb');
        if ($fh === false) {
            return '';
        }
        if ($size > $maks) {
            fseek($fh, -$maks, SEEK_END);
        }
        $isi = stream_get_contents($fh) ?: '';
        fclose($fh);

        return $isi;
    }

    private static function urutTerbaru(array $paths): array
    {
        usort($paths, fn ($a, $b) => filemtime($b) <=> filemtime($a));

        return $paths;
    }

    private static function namaTampil(string $path): string
    {
        $base = rtrim(base_path(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

        return str_starts_with($path, $base) ? substr($path, strlen($base)) : $path;
    }
}
