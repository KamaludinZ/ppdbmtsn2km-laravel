<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Pendaftar;
use App\Models\Dokumen;

/**
 * Secure File Controller
 *
 * Handles secure file downloads from private storage
 * Ensures authorization before allowing file access
 */
class SecureFileController extends Controller
{
    /**
     * Download private document
     *
     * @param int $dokumenId
     * @return \Illuminate\Http\Response
     */
    public function downloadDokumen($dokumenId)
    {
        $dokumen = Dokumen::findOrFail($dokumenId);
        $pendaftar = $dokumen->pendaftar;

        // Authorization check
        if (!$this->canAccessDokumen($pendaftar)) {
            abort(403, 'Anda tidak memiliki akses ke dokumen ini.');
        }

        // Check if file exists
        if (!Storage::disk('private')->exists($dokumen->path_file)) {
            abort(404, 'File tidak ditemukan.');
        }

        // Get file name
        $fileName = $this->getOriginalFileName($dokumen);

        // Download file
        return Storage::disk('private')->download(
            $dokumen->path_file,
            $fileName
        );
    }

    /**
     * View/Stream private document (for preview)
     *
     * @param int $dokumenId
     * @return \Illuminate\Http\Response
     */
    public function viewDokumen($dokumenId)
    {
        $dokumen = Dokumen::findOrFail($dokumenId);
        $pendaftar = $dokumen->pendaftar;

        // Authorization check
        if (!$this->canAccessDokumen($pendaftar)) {
            abort(403, 'Anda tidak memiliki akses ke dokumen ini.');
        }

        // Check if file exists
        if (!Storage::disk('private')->exists($dokumen->path_file)) {
            abort(404, 'File tidak ditemukan.');
        }

        // Get file
        $file = Storage::disk('private')->get($dokumen->path_file);
        $mimeType = Storage::disk('private')->mimeType($dokumen->path_file);

        // Return file as response (inline display) with no-cache headers
        return response($file, 200)
            ->header('Content-Type', $mimeType)
            ->header('Content-Disposition', 'inline')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    /**
     * View/Preview bukti pembayaran (inline display)
     *
     * @param int $pendaftarId
     * @param string $type (daftar_ulang|seragam|psikologi)
     * @return \Illuminate\Http\Response
     */
    public function viewBuktiBayar($pendaftarId, $type)
    {
        $pendaftar = Pendaftar::findOrFail($pendaftarId);

        // Authorization check
        if (!$this->canAccessBuktiBayar($pendaftar)) {
            abort(403, 'Anda tidak memiliki akses ke file ini.');
        }

        // Get file path based on type
        $filePath = $this->getBuktiBayarPath($pendaftar, $type);

        if (!$filePath) {
            abort(404, 'Bukti pembayaran tidak ditemukan.');
        }

        // Check if file exists
        if (!Storage::disk('private')->exists($filePath)) {
            abort(404, 'File tidak ditemukan di: ' . $filePath);
        }

        // Get file and mime type
        $file = Storage::disk('private')->get($filePath);
        $mimeType = Storage::disk('private')->mimeType($filePath);

        // Return file as inline response (for preview in browser)
        return response($file, 200)
            ->header('Content-Type', $mimeType)
            ->header('Content-Disposition', 'inline');
    }

    /**
     * Download bukti pembayaran
     *
     * @param int $pendaftarId
     * @param string $type (daftar_ulang|seragam|psikologi)
     * @return \Illuminate\Http\Response
     */
    public function downloadBuktiBayar($pendaftarId, $type)
    {
        $pendaftar = Pendaftar::findOrFail($pendaftarId);

        // Authorization check
        if (!$this->canAccessBuktiBayar($pendaftar)) {
            abort(403, 'Anda tidak memiliki akses ke file ini.');
        }

        // Get file path and name
        $filePath = $this->getBuktiBayarPath($pendaftar, $type);
        $fileName = $this->getBuktiBayarFileName($pendaftar, $type, $filePath);

        if (!$filePath) {
            abort(404, 'Bukti pembayaran tidak ditemukan.');
        }

        // Check if file exists
        if (!Storage::disk('private')->exists($filePath)) {
            abort(404, 'File tidak ditemukan.');
        }

        // Download file
        return Storage::disk('private')->download($filePath, $fileName);
    }

    /**
     * Get bukti bayar file path based on type
     *
     * @param Pendaftar $pendaftar
     * @param string $type
     * @return string|null
     */
    protected function getBuktiBayarPath(Pendaftar $pendaftar, string $type): ?string
    {
        switch ($type) {
            case 'daftar_ulang':
                $daftarUlang = $pendaftar->daftarUlang;
                return $daftarUlang?->bukti_bayar;

            case 'seragam':
                $seragam = $pendaftar->seragam;
                return $seragam?->bukti_bayar;

            case 'psikologi':
                $tesPsikologi = $pendaftar->tesPsikologiPendaftar;
                return $tesPsikologi?->bukti_transfer;

            default:
                return null;
        }
    }

    /**
     * Get bukti bayar file name for download
     *
     * @param Pendaftar $pendaftar
     * @param string $type
     * @param string $filePath
     * @return string
     */
    protected function getBuktiBayarFileName(Pendaftar $pendaftar, string $type, string $filePath): string
    {
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $nomorPendaftaran = $pendaftar->nomor_pendaftaran;

        $typeLabels = [
            'daftar_ulang' => 'bukti_daftar_ulang',
            'seragam' => 'bukti_seragam',
            'psikologi' => 'bukti_psikologi',
        ];

        $label = $typeLabels[$type] ?? 'bukti';

        return "{$label}_{$nomorPendaftaran}.{$extension}";
    }

    /**
     * Check if user can access document
     *
     * @param Pendaftar $pendaftar
     * @return bool
     */
    protected function canAccessDokumen(Pendaftar $pendaftar): bool
    {
        $user = Auth::user();

        // Admin and panitia can access all documents
        if ($user->isAdmin() || $user->isPanitia()) {
            return true;
        }

        // Pendaftar can only access their own documents
        if ($user->isPendaftar() && $user->id === $pendaftar->user_id) {
            return true;
        }

        return false;
    }

    /**
     * Check if user can access bukti pembayaran
     *
     * @param Pendaftar $pendaftar
     * @return bool
     */
    protected function canAccessBuktiBayar(Pendaftar $pendaftar): bool
    {
        return $this->canAccessDokumen($pendaftar);
    }

    /**
     * Get original file name from dokumen
     *
     * @param Dokumen $dokumen
     * @return string
     */
    protected function getOriginalFileName(Dokumen $dokumen): string
    {
        // Generate descriptive file name
        $jenisLabels = [
            'ktp' => 'KTP',
            'kk' => 'Kartu_Keluarga',
            'akta_kelahiran' => 'Akta_Kelahiran',
            'akta_lahir' => 'Akta_Lahir',
            'ijazah' => 'Ijazah',
            'rapor' => 'Rapor',
            'raport' => 'Raport',
            'skhun' => 'SKHUN',
            'foto' => 'Foto',
            'skl' => 'SKL',
            'prestasi' => 'Sertifikat_Prestasi',
            'sertifikat' => 'Sertifikat',
            'pkh' => 'Kartu_PKH',
            'kks' => 'Kartu_KKS',
            'kip' => 'Kartu_KIP',
            'nisn' => 'NISN',
        ];

        $jenis = $jenisLabels[$dokumen->jenis_dokumen] ?? $dokumen->jenis_dokumen;
        $nomorPendaftaran = $dokumen->pendaftar->nomor_pendaftaran;
        $extension = pathinfo($dokumen->path_file, PATHINFO_EXTENSION);

        return "{$jenis}_{$nomorPendaftaran}.{$extension}";
    }
}
