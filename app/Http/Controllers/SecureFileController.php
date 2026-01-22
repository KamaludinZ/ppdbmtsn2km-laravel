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
        if (!Storage::disk('private')->exists($dokumen->file_path)) {
            abort(404, 'File tidak ditemukan.');
        }

        // Get file name
        $fileName = $this->getOriginalFileName($dokumen);

        // Download file
        return Storage::disk('private')->download(
            $dokumen->file_path,
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
        if (!Storage::disk('private')->exists($dokumen->file_path)) {
            abort(404, 'File tidak ditemukan.');
        }

        // Get file
        $file = Storage::disk('private')->get($dokumen->file_path);
        $mimeType = Storage::disk('private')->mimeType($dokumen->file_path);

        // Return file as response (inline display)
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

        // Get file path based on type
        $filePath = null;
        $fileName = null;

        switch ($type) {
            case 'daftar_ulang':
                $daftarUlang = $pendaftar->daftarUlang;
                if ($daftarUlang && $daftarUlang->bukti_bayar) {
                    $filePath = $daftarUlang->bukti_bayar;
                    $fileName = 'bukti_daftar_ulang_' . $pendaftar->nomor_pendaftaran . '.pdf';
                }
                break;

            case 'seragam':
                $seragam = $pendaftar->seragam;
                if ($seragam && $seragam->bukti_bayar) {
                    $filePath = $seragam->bukti_bayar;
                    $fileName = 'bukti_seragam_' . $pendaftar->nomor_pendaftaran . '.pdf';
                }
                break;

            case 'psikologi':
                $tesPsikologi = $pendaftar->tesPsikologi;
                if ($tesPsikologi && $tesPsikologi->bukti_transfer) {
                    $filePath = $tesPsikologi->bukti_transfer;
                    $fileName = 'bukti_psikologi_' . $pendaftar->nomor_pendaftaran . '.pdf';
                }
                break;

            default:
                abort(404);
        }

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
            'akta_lahir' => 'Akta_Lahir',
            'ijazah' => 'Ijazah',
            'raport' => 'Raport',
            'skhun' => 'SKHUN',
            'foto' => 'Foto',
            'skl' => 'SKL',
            'prestasi' => 'Sertifikat_Prestasi',
            'pkh' => 'Kartu_PKH',
            'kks' => 'Kartu_KKS',
            'kip' => 'Kartu_KIP',
        ];

        $jenis = $jenisLabels[$dokumen->jenis_dokumen] ?? $dokumen->jenis_dokumen;
        $nomorPendaftaran = $dokumen->pendaftar->nomor_pendaftaran;
        $extension = pathinfo($dokumen->file_path, PATHINFO_EXTENSION);

        return "{$jenis}_{$nomorPendaftaran}.{$extension}";
    }
}
