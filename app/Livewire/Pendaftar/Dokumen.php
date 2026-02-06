<?php

namespace App\Livewire\Pendaftar;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Pendaftar;
use App\Models\Dokumen as DokumenModel;
use App\Models\VerifikasiBerkas;
use App\Services\ImageService;
use App\Services\FileNamingService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Dokumen extends Component
{
    use WithFileUploads;

    public $pendaftar;
    public $verifikasi;
    public $uploaded_dokumen = []; // Stores Dokumen models keyed by jenis

    // Temporary file uploads
    public $file_rapor;
    public $file_akta_kelahiran;
    public $file_kartu_keluarga;
    public $file_nisn;
    public $file_ijazah;
    public $file_sertifikat;
    public $file_kip;
    public $file_pkh;
    public $file_kks;

    public $documentTypes = [
        'rapor' => 'Scan Rapor',
        'akta_kelahiran' => 'Akta Kelahiran',
        'kartu_keluarga' => 'Kartu Keluarga',
        'nisn' => 'Scan/Bukti NISN',
        'ijazah' => 'Ijazah/SKL/Surat Keterangan Aktif',
        'sertifikat' => 'Sertifikat/Piagam',
        'kip' => 'Kartu Indonesia Pintar (KIP)',
        'pkh' => 'Program Keluarga Harapan (PKH)',
        'kks' => 'Kartu Keluarga Sejahtera (KKS)',
    ];

    public $documentLabels = [
        'rapor' => ['label' => 'Scan Rapor', 'keterangan' => 'Upload dalam format PDF, maksimal 2 MB', 'wajib' => true],
        'akta_kelahiran' => ['label' => 'Akta Kelahiran', 'keterangan' => 'Scan akta kelahiran dalam format PDF, maksimal 2 MB', 'wajib' => true],
        'kartu_keluarga' => ['label' => 'Kartu Keluarga', 'keterangan' => 'Scan Kartu Keluarga dalam format PDF, maksimal 2 MB', 'wajib' => true],
        'nisn' => ['label' => 'Scan/Bukti NISN', 'keterangan' => 'Kartu NISN atau screenshot cek NISN dalam format PDF, maksimal 2 MB', 'wajib' => true],
        'ijazah' => ['label' => 'Ijazah/SKL/Surat Keterangan Aktif', 'keterangan' => 'Dari sekolah asal (SD/MI) dalam format PDF, maksimal 2 MB', 'wajib' => true],
        'sertifikat' => ['label' => 'Sertifikat/Piagam', 'keterangan' => 'Jika memiliki prestasi, upload dalam format PDF, maksimal 2 MB', 'wajib' => false],
        'kip' => ['label' => 'Kartu Indonesia Pintar (KIP)', 'keterangan' => 'Upload jika memiliki nomor KIP di formulir, format PDF maksimal 2 MB', 'wajib' => false, 'conditional' => 'nomor_kip'],
        'pkh' => ['label' => 'Program Keluarga Harapan (PKH)', 'keterangan' => 'Upload jika memiliki nomor PKH di formulir, format PDF maksimal 2 MB', 'wajib' => false, 'conditional' => 'nomor_pkh'],
        'kks' => ['label' => 'Kartu Keluarga Sejahtera (KKS)', 'keterangan' => 'Upload jika memiliki nomor KKS di formulir, format PDF maksimal 2 MB', 'wajib' => false, 'conditional' => 'nomor_kks'],
    ];

    public function mount()
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'pendaftar') {
            return redirect()->to('/login');
        }

        $this->pendaftar = $user->pendaftar;
        if (!$this->pendaftar) {
            return redirect()->to('/daftar');
        }

        // Upload dokumen dapat diakses tanpa perlu menunggu verifikasi tes psikologi
        // Tes psikologi dan upload dokumen adalah proses yang independen

        $this->loadData();
    }

    public function loadData()
    {
        $this->verifikasi = VerifikasiBerkas::firstOrCreate(
            ['pendaftar_id' => $this->pendaftar->id]
        );

        $docs = DokumenModel::where('pendaftar_id', $this->pendaftar->id)->get();
        foreach ($docs as $doc) {
            $this->uploaded_dokumen[$doc->jenis_dokumen] = $doc;
        }

        // Migrate legacy rapor from Pendaftar model to Dokumen model
        if (!isset($this->uploaded_dokumen['rapor']) && $this->pendaftar->file_rapor) {
            // Check if file exists in storage
            if (Storage::exists($this->pendaftar->file_rapor)) {
                // Create proper Dokumen record for legacy rapor
                $raporDokumen = DokumenModel::create([
                    'pendaftar_id' => $this->pendaftar->id,
                    'jenis_dokumen' => 'rapor',
                    'nama_file' => 'Rapor (Migrated)',
                    'path_file' => $this->pendaftar->file_rapor,
                    'mime_type' => 'application/pdf',
                    'ukuran_file' => Storage::size($this->pendaftar->file_rapor),
                    'tanggal_upload' => $this->pendaftar->updated_at,
                ]);

                $this->uploaded_dokumen['rapor'] = $raporDokumen;
            }
        }
    }

    // Auto-upload when file is selected (magic method)
    public function updated($propertyName)
    {
        // Check if the updated property is a file upload
        if (strpos($propertyName, 'file_') === 0) {
            $type = substr($propertyName, 5); // Remove 'file_' prefix
            $this->processUpload($type);
        }
    }

    private function processUpload($type)
    {
        $fileProperty = 'file_' . $type;
        $file = $this->$fileProperty;

        if (!$file) {
            return; // Silently return if no file
        }

        // Validasi: Semua dokumen harus PDF dengan max 2MB
        $this->validate([
            $fileProperty => 'required|file|mimes:pdf|max:2048', // 2MB Max, PDF only
        ], [
            $fileProperty . '.required' => 'File wajib diupload.',
            $fileProperty . '.file' => 'File yang diupload tidak valid.',
            $fileProperty . '.mimes' => 'File harus berformat PDF.',
            $fileProperty . '.max' => 'Ukuran file maksimal 2 MB.',
        ]);

        try {
            // Check if document already exists - CHECK FIRST!
            $existingDokumen = DokumenModel::where('pendaftar_id', $this->pendaftar->id)
                ->where('jenis_dokumen', $type)
                ->first();

            // Delete old file from private storage BEFORE uploading new file
            if ($existingDokumen && Storage::disk('private')->exists($existingDokumen->path_file)) {
                Storage::disk('private')->delete($existingDokumen->path_file);
            }

            // Generate file name using FileNamingService
            $extension = FileNamingService::getFileExtension($file);
            $jenisClean = FileNamingService::cleanJenisDokumen($type);

            $filename = FileNamingService::generateFileName(
                $jenisClean,
                $this->pendaftar->nomor_pendaftaran,
                $extension,
                $this->pendaftar->nama_lengkap
            );

            // Store NEW file to private storage (dengan subdirektori pendaftar_id untuk konsistensi)
            $path = $file->storeAs('dokumen/' . $this->pendaftar->id, $filename, 'private');

            // Get file info
            $mimeType = $file->getMimeType();
            $size = $file->getSize();

            if ($existingDokumen) {
                // Update existing document record
                $existingDokumen->update([
                    'nama_file' => $filename,
                    'path_file' => $path,
                    'mime_type' => $mimeType,
                    'ukuran_file' => $size,
                    'tanggal_upload' => now(),
                ]);
            } else {
                // Create new document
                DokumenModel::create([
                    'pendaftar_id' => $this->pendaftar->id,
                    'jenis_dokumen' => $type,
                    'nama_file' => $filename,
                    'path_file' => $path,
                    'mime_type' => $mimeType,
                    'ukuran_file' => $size,
                    'tanggal_upload' => now(),
                ]);
            }

            // Special handling for Rapor: Sync to Pendaftar table for legacy support
            if ($type === 'rapor') {
                $this->pendaftar->update(['file_rapor' => $path]);
            }

            // Reset file input
            $this->$fileProperty = null;

            // Reload data with fresh data
            $this->loadData();

            // Force refresh pendaftar data
            $this->pendaftar = $this->pendaftar->fresh();

            $documentLabel = $this->documentLabels[$type]['label'] ?? 'Dokumen';
            $this->dispatch('statusUpdated', ['type' => 'success', 'message' => $documentLabel . ' berhasil diupload!']);

        } catch (\Exception $e) {
            $this->dispatch('statusUpdated', [
                'type' => 'error',
                'message' => 'Gagal mengupload dokumen: ' . $e->getMessage()
            ]);
        }
    }

    // Keep the manual upload method for backward compatibility
    public function upload($type)
    {
        $this->processUpload($type);
    }

    public function render()
    {
        return view('livewire.pendaftar.dokumen')
            ->layout('components.layouts.dashboard', [
                'role' => 'pendaftar',
                'pageTitle' => 'Upload Dokumen',
                'pageDescription' => 'Silakan upload berkas persyaratan pendaftaran'
            ]);
    }
}
