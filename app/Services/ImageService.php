<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Http\UploadedFile;
use App\Services\FileNamingService;

class ImageService
{
    /**
     * Upload dan konversi gambar ke WebP dengan kompresi
     *
     * @param UploadedFile $file
     * @param string $directory
     * @param int $maxWidth
     * @param int $quality
     * @return string Path file yang disimpan
     */
    public function uploadAndConvertToWebP(
        UploadedFile $file,
        string $directory = 'photos',
        int $maxWidth = 800,
        int $quality = 80
    ): string {
        // Generate unique filename
        $filename = time() . '_' . uniqid() . '.webp';
        $path = $directory . '/' . $filename;

        // Load image menggunakan Intervention Image
        $image = Image::read($file->getRealPath());

        // Resize jika lebih besar dari maxWidth, maintain aspect ratio
        if ($image->width() > $maxWidth) {
            $image->scale(width: $maxWidth);
        }

        // Convert ke WebP dengan quality setting
        $webpImage = $image->toWebp($quality);

        // Simpan ke storage
        Storage::disk('public')->put($path, (string) $webpImage);

        return $path;
    }

    /**
     * Upload gambar dokumen dengan kompresi lebih tinggi
     *
     * @param UploadedFile $file
     * @param string $directory
     * @param int $maxWidth
     * @param int $quality
     * @return string Path file yang disimpan
     */
    public function uploadDocumentImage(
        UploadedFile $file,
        string $directory = 'documents',
        int $maxWidth = 1200,
        int $quality = 85
    ): string {
        return $this->uploadAndConvertToWebP($file, $directory, $maxWidth, $quality);
    }

    /**
     * Upload foto profil dengan kompresi optimal
     *
     * @param UploadedFile $file
     * @param string $directory
     * @param int $size Square size for profile photo
     * @param int $quality
     * @param string|null $nomorPendaftaran Nomor pendaftaran untuk nama file sistematis
     * @param string|null $jenisFoto Jenis foto (foto_profil, foto_3x4, dll)
     * @param string|null $namaPendaftar Nama pendaftar untuk nama file
     * @return string Path file yang disimpan
     */
    public function uploadProfilePhoto(
        UploadedFile $file,
        string $directory = 'photos',
        int $size = 500,
        int $quality = 80,
        ?string $nomorPendaftaran = null,
        ?string $jenisFoto = 'foto_profil',
        ?string $namaPendaftar = null
    ): string {
        // Generate filename (sistematis jika nomor pendaftaran disediakan)
        if ($nomorPendaftaran) {
            // Format: foto_profil-{nomor_pendaftaran}-{nama_pendaftar}-{timestamp}.webp
            $filename = FileNamingService::generateImageFileName(
                $jenisFoto,
                $nomorPendaftaran,
                'webp',
                $namaPendaftar
            );
        } else {
            // Fallback ke random filename jika nomor pendaftaran tidak ada
            $filename = time() . '_' . uniqid() . '.webp';
        }

        $path = $directory . '/' . $filename;

        // Load image
        $image = Image::read($file->getRealPath());

        // Resize dan crop ke square untuk foto profil
        $image->cover($size, $size);

        // Convert ke WebP
        $webpImage = $image->toWebp($quality);

        // Simpan ke storage
        Storage::disk('public')->put($path, (string) $webpImage);

        return $path;
    }

    /**
     * Delete file lama jika ada
     *
     * @param string|null $oldPath
     * @return void
     */
    public function deleteOldFile(?string $oldPath): void
    {
        if ($oldPath && Storage::disk('public')->exists($oldPath)) {
            Storage::disk('public')->delete($oldPath);
        }
    }
}
