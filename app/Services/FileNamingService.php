<?php

namespace App\Services;

use Illuminate\Support\Str;

/**
 * File Naming Service
 *
 * Service untuk generate nama file yang sistematis dan mudah dicari
 * Format: {jenis_dokumen}-{nomor_pendaftaran}-{nama_pendaftar}-{timestamp}.{ext}
 */
class FileNamingService
{
    /**
     * Generate nama file yang sistematis
     *
     * @param string $jenisFile Jenis file (rapor, ktp, kk, foto, dll)
     * @param string $nomorPendaftaran Nomor pendaftaran
     * @param string $extension Extension file (pdf, jpg, png, dll)
     * @param string|null $namaPendaftar Nama pendaftar (optional)
     * @return string Nama file yang sudah di-format
     *
     * @example
     * generateFileName('rapor', 'PPDB2024001', 'pdf', 'Ahmad Fulan')
     * // Output: rapor-PPDB2024001-Ahmad_Fulan-20260131.pdf
     */
    public static function generateFileName(string $jenisFile, string $nomorPendaftaran, string $extension, ?string $namaPendaftar = null): string
    {
        // Clean jenis file (remove spaces, convert to lowercase, use dash as separator)
        $jenisFile = Str::slug($jenisFile, '_');

        // Clean nomor pendaftaran (remove special chars except dash and underscore)
        $nomorPendaftaran = preg_replace('/[^A-Za-z0-9\-_]/', '', $nomorPendaftaran);

        // Clean nama pendaftar (replace spaces with underscore, remove special chars)
        $namaPendaftarClean = '';
        if ($namaPendaftar) {
            // Replace spaces with underscore
            $namaPendaftarClean = preg_replace('/\s+/', '_', trim($namaPendaftar));
            // Remove special chars except underscore
            $namaPendaftarClean = preg_replace('/[^A-Za-z0-9_]/', '', $namaPendaftarClean);
            // Limit to 50 chars
            $namaPendaftarClean = substr($namaPendaftarClean, 0, 50);
        }

        // Clean extension (remove dots)
        $extension = str_replace('.', '', $extension);
        $extension = strtolower($extension);

        // Generate timestamp (format: YYYYMMDD for date only)
        $timestamp = now()->format('Ymd');

        // Format: {jenis}-{nomor}-{nama}-{timestamp}.{ext}
        if ($namaPendaftarClean) {
            return "{$jenisFile}-{$nomorPendaftaran}-{$namaPendaftarClean}-{$timestamp}.{$extension}";
        }

        // Fallback to old format if nama pendaftar not provided
        return "{$jenisFile}_{$nomorPendaftaran}_{$timestamp}.{$extension}";
    }

    /**
     * Generate path dan nama file untuk storage
     *
     * @param string $jenisFile Jenis file (rapor, ktp, kk, dll)
     * @param string $nomorPendaftaran Nomor pendaftaran
     * @param string $pendaftarId ID pendaftar
     * @param string $extension Extension file
     * @return string Path lengkap untuk storage
     *
     * @example
     * generateFilePath('rapor', 'PPDB2024001', '123', 'pdf')
     * // Output: dokumen/123/rapor_PPDB2024001_20260119_143052.pdf
     */
    public static function generateFilePath(
        string $jenisFile,
        string $nomorPendaftaran,
        string $pendaftarId,
        string $extension
    ): string {
        $fileName = self::generateFileName($jenisFile, $nomorPendaftaran, $extension);
        return "dokumen/{$pendaftarId}/{$fileName}";
    }

    /**
     * Generate nama file untuk foto/gambar
     *
     * @param string $jenisGambar Jenis gambar (foto_profil, foto_3x4, dll)
     * @param string $nomorPendaftaran Nomor pendaftaran
     * @param string $extension Extension file (jpg, png, dll)
     * @param string|null $namaPendaftar Nama pendaftar (optional)
     * @return string Nama file yang sudah di-format
     *
     * @example
     * generateImageFileName('foto_profil', 'PPDB2024001', 'jpg', 'Ahmad Fulan')
     * // Output: foto_profil-PPDB2024001-Ahmad_Fulan-20260131_143052.jpg
     */
    public static function generateImageFileName(
        string $jenisGambar,
        string $nomorPendaftaran,
        string $extension,
        ?string $namaPendaftar = null
    ): string {
        return self::generateFileName($jenisGambar, $nomorPendaftaran, $extension, $namaPendaftar);
    }

    /**
     * Generate path untuk foto/gambar di public storage
     *
     * @param string $jenisGambar Jenis gambar
     * @param string $nomorPendaftaran Nomor pendaftaran
     * @param string $extension Extension file
     * @return string Path lengkap untuk storage
     *
     * @example
     * generateImagePath('foto_profil', 'PPDB2024001', 'jpg')
     * // Output: photos/foto_profil_PPDB2024001_20260119_143052.jpg
     */
    public static function generateImagePath(
        string $jenisGambar,
        string $nomorPendaftaran,
        string $extension
    ): string {
        $fileName = self::generateImageFileName($jenisGambar, $nomorPendaftaran, $extension);
        return "photos/{$fileName}";
    }

    /**
     * Generate nama file untuk bukti bayar
     *
     * @param string $jenisPembayaran Jenis pembayaran (daftar_ulang, seragam, psikologi)
     * @param string $nomorPendaftaran Nomor pendaftaran
     * @param string $extension Extension file
     * @param string|null $namaPendaftar Nama pendaftar (optional)
     * @return string Nama file yang sudah di-format
     *
     * @example
     * generateBuktiBayarFileName('daftar_ulang', 'PPDB2024001', 'pdf', 'Ahmad Fulan')
     * // Output: bukti_daftar_ulang-PPDB2024001-Ahmad_Fulan-20260131_143052.pdf
     */
    public static function generateBuktiBayarFileName(
        string $jenisPembayaran,
        string $nomorPendaftaran,
        string $extension,
        ?string $namaPendaftar = null
    ): string {
        $jenisFile = "bukti_{$jenisPembayaran}";
        return self::generateFileName($jenisFile, $nomorPendaftaran, $extension, $namaPendaftar);
    }

    /**
     * Generate path untuk bukti bayar di private storage
     *
     * @param string $jenisPembayaran Jenis pembayaran
     * @param string $nomorPendaftaran Nomor pendaftaran
     * @param string $pendaftarId ID pendaftar
     * @param string $extension Extension file
     * @return string Path lengkap untuk storage
     *
     * @example
     * generateBuktiBayarPath('daftar_ulang', 'PPDB2024001', '123', 'pdf')
     * // Output: bukti_bayar/123/bukti_daftar_ulang_PPDB2024001_20260119_143052.pdf
     */
    public static function generateBuktiBayarPath(
        string $jenisPembayaran,
        string $nomorPendaftaran,
        string $pendaftarId,
        string $extension
    ): string {
        $fileName = self::generateBuktiBayarFileName($jenisPembayaran, $nomorPendaftaran, $extension);
        return "bukti_bayar/{$pendaftarId}/{$fileName}";
    }

    /**
     * Extract extension dari uploaded file
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @return string Extension file (tanpa dot)
     */
    public static function getFileExtension($file): string
    {
        return $file->getClientOriginalExtension();
    }

    /**
     * Mapping jenis dokumen untuk penamaan yang lebih deskriptif
     *
     * @param string $jenisRaw Jenis dokumen raw dari form
     * @return string Jenis dokumen yang sudah di-clean
     */
    public static function cleanJenisDokumen(string $jenisRaw): string
    {
        $mapping = [
            'rapor' => 'rapor',
            'raport' => 'rapor',
            'akta_kelahiran' => 'akta_kelahiran',
            'akta' => 'akta_kelahiran',
            'kartu_keluarga' => 'kartu_keluarga',
            'kk' => 'kartu_keluarga',
            'nisn' => 'nisn',
            'ijazah' => 'ijazah',
            'ijazah_sd' => 'ijazah_sd',
            'skhun' => 'skhun',
            'skhun_sd' => 'skhun_sd',
            'sertifikat' => 'sertifikat',
            'prestasi' => 'sertifikat_prestasi',
            'kip' => 'kip',
            'pkh' => 'pkh',
            'kks' => 'kks',
            'foto' => 'foto_profil',
            'foto_profil' => 'foto_profil',
        ];

        return $mapping[$jenisRaw] ?? Str::slug($jenisRaw, '_');
    }
}
