<?php

namespace App\Livewire\Pendaftar;

use App\Models\TesPsikologiSetting;
use App\Models\TesPsikologiOpsi;
use App\Models\TesPsikologiBank;
use App\Models\TesPsikologiPendaftar;
use App\Models\Setting;
use App\Services\FileNamingService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Barryvdh\DomPDF\Facade\Pdf;

class TesPsikologi extends Component
{
    use WithFileUploads;

    public $setting;
    public $opsiList;
    public $bankList;
    public $tesPsikologiData;
    public $pendaftar;

    public $selectedBank;
    public $selectedOpsi;
    public $waktuTransfer;
    public $buktiTransfer;
    public $uploadProgress = false;

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

        $this->setting = TesPsikologiSetting::getSetting();

        // Check if tes psikologi is active
        if (!$this->setting->aktif) {
            session()->flash('error', 'Fitur tes psikologi saat ini tidak aktif');
            return redirect()->to('/pendaftar/dashboard');
        }

        $this->opsiList = TesPsikologiOpsi::aktif()->ordered()->get();
        $this->bankList = TesPsikologiBank::aktif()->ordered()->get();

        // Get or create tes psikologi data
        $this->tesPsikologiData = TesPsikologiPendaftar::firstOrCreate(
            ['pendaftar_id' => $this->pendaftar->id],
            ['status_verifikasi' => 'belum_upload']
        );

        $this->selectedBank = $this->tesPsikologiData->bank_id;
        $this->selectedOpsi = $this->tesPsikologiData->opsi_id;
        $this->waktuTransfer = $this->tesPsikologiData->waktu_transfer ? $this->tesPsikologiData->waktu_transfer->format('Y-m-d\TH:i') : null;
    }

    public function uploadBukti()
    {
        $this->validate([
            'selectedOpsi' => 'required|exists:tes_psikologi_opsi,id',
            'waktuTransfer' => 'required|date',
            'selectedBank' => 'required|exists:tes_psikologi_bank,id',
            'buktiTransfer' => 'required|image|max:2048'
        ], [
            'selectedOpsi.required' => 'Pilih biaya tes psikologi terlebih dahulu',
            'waktuTransfer.required' => 'Waktu transfer harus diisi',
            'waktuTransfer.date' => 'Format waktu transfer tidak valid',
            'selectedBank.required' => 'Pilih bank terlebih dahulu',
            'buktiTransfer.required' => 'Upload bukti transfer terlebih dahulu',
            'buktiTransfer.image' => 'File harus berupa gambar',
            'buktiTransfer.max' => 'Ukuran file maksimal 2MB'
        ]);

        $this->uploadProgress = true;

        try {
            // Delete old file if exists
            if ($this->tesPsikologiData->bukti_transfer) {
                Storage::disk('private')->delete($this->tesPsikologiData->bukti_transfer);
            }

            // Generate nama file sistematis: bukti_psikologi_{nomor_pendaftaran}_{timestamp}.{ext}
            $extension = FileNamingService::getFileExtension($this->buktiTransfer);
            $customFileName = FileNamingService::generateBuktiBayarFileName(
                'psikologi',
                $this->pendaftar->nomor_pendaftaran,
                $extension
            );

            // Simpan ke private storage untuk keamanan dokumen keuangan
            $path = $this->buktiTransfer->storeAs(
                'bukti_bayar/' . $this->pendaftar->id,
                $customFileName,
                'private'
            );

            // Update data
            $this->tesPsikologiData->update([
                'opsi_id' => $this->selectedOpsi,
                'waktu_transfer' => $this->waktuTransfer,
                'bank_id' => $this->selectedBank,
                'bukti_transfer' => $path,
                'tanggal_upload' => now(),
                'status_verifikasi' => 'menunggu'
            ]);

            session()->flash('message', 'Bukti transfer berhasil diupload. Menunggu verifikasi panitia.');

            // Refresh data
            $this->tesPsikologiData = $this->tesPsikologiData->fresh();
            $this->buktiTransfer = null;
            $this->uploadProgress = false;

        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
            $this->uploadProgress = false;
        }
    }

    public function hapusBukti()
    {
        if ($this->tesPsikologiData->status_verifikasi === 'disetujui') {
            session()->flash('error', 'Tidak dapat menghapus bukti yang sudah disetujui');
            return;
        }

        // Delete file from private storage
        if ($this->tesPsikologiData->bukti_transfer) {
            Storage::disk('private')->delete($this->tesPsikologiData->bukti_transfer);
        }

        // Reset data
        $this->tesPsikologiData->update([
            'opsi_id' => null,
            'waktu_transfer' => null,
            'bank_id' => null,
            'bukti_transfer' => null,
            'tanggal_upload' => null,
            'status_verifikasi' => 'belum_upload',
            'catatan_verifikasi' => null,
            'tanggal_verifikasi' => null,
            'verified_by' => null
        ]);

        session()->flash('message', 'Bukti transfer berhasil dihapus');
        $this->tesPsikologiData = $this->tesPsikologiData->fresh();
        $this->selectedBank = null;
        $this->selectedOpsi = null;
        $this->waktuTransfer = null;
    }

    public function downloadInvoice()
    {
        if ($this->tesPsikologiData->status_verifikasi !== 'disetujui') {
            return;
        }

        $pdf = $this->generateInvoicePdf();
        $filename = 'invoice-tes-psikologi-' . $this->pendaftar->nomor_pendaftaran . '-' . Str::slug($this->pendaftar->nama_lengkap) . '.pdf';
        
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $filename);
    }

    public function previewInvoice()
    {
        if (!$this->pendaftar) {
            $user = Auth::user();
            if (!$user || $user->role !== 'pendaftar') {
                abort(403);
            }
            $this->pendaftar = $user->pendaftar;
        }

        if (!$this->tesPsikologiData) {
            $this->tesPsikologiData = TesPsikologiPendaftar::where('pendaftar_id', $this->pendaftar->id)->first();
        }

        if (!$this->tesPsikologiData || $this->tesPsikologiData->status_verifikasi !== 'disetujui') {
            abort(403, 'Invoice hanya tersedia untuk pembayaran yang sudah disetujui.');
        }

        $pdf = $this->generateInvoicePdf();
        return response()->stream(function () use ($pdf) {
            echo $pdf->output();
        }, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="invoice-tes-psikologi.pdf"',
        ]);
    }

    private function generateInvoicePdf()
    {
        $printSettings = [
            'print_institution_name' => Setting::get('print_institution_name'),
            'print_institution_address' => Setting::get('print_institution_address'),
            'print_institution_phone' => Setting::get('print_institution_phone'),
            'print_institution_email' => Setting::get('print_institution_email'),
        ];

        $invoiceSettings = [
            'nomor_format' => Setting::get('invoice_psikologi_nomor_format', 'INV-PSI-{TAHUN}-{NOMOR}'),
            'footer' => Setting::get('invoice_psikologi_footer', ''),
        ];

        $opsi = TesPsikologiOpsi::find($this->tesPsikologiData->opsi_id);
        $bank = TesPsikologiBank::find($this->tesPsikologiData->bank_id);

        $pdf = Pdf::loadView('pdf.invoice-psikologi', [
            'pendaftar' => $this->pendaftar,
            'tesPsikologiData' => $this->tesPsikologiData,
            'printSettings' => $printSettings,
            'invoiceSettings' => $invoiceSettings,
            'opsi' => $opsi,
            'bank' => $bank,
        ]);

        $pdf->setPaper('A5', 'landscape');

        return $pdf;
    }

    /**
     * Convert uploaded image to WebP format and store it
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $folder
     * @return string Path to stored file
     */
    private function convertAndStoreAsWebp($file, $folder)
    {
        // Generate unique filename
        $filename = Str::random(40) . '.webp';
        $storagePath = storage_path('app/public/' . $folder);

        // Create directory if not exists
        if (!file_exists($storagePath)) {
            mkdir($storagePath, 0755, true);
        }

        $fullPath = $storagePath . '/' . $filename;

        // Get image info
        $imageInfo = getimagesize($file->getRealPath());
        $mimeType = $imageInfo['mime'];

        // Create image resource based on mime type
        switch ($mimeType) {
            case 'image/jpeg':
            case 'image/jpg':
                $image = imagecreatefromjpeg($file->getRealPath());
                break;
            case 'image/png':
                $image = imagecreatefrompng($file->getRealPath());
                break;
            case 'image/gif':
                $image = imagecreatefromgif($file->getRealPath());
                break;
            case 'image/webp':
                $image = imagecreatefromwebp($file->getRealPath());
                break;
            default:
                throw new \Exception('Unsupported image type: ' . $mimeType);
        }

        // Convert to WebP with quality 80
        imagewebp($image, $fullPath, 80);

        // Free memory
        imagedestroy($image);

        // Return relative path
        return $folder . '/' . $filename;
    }

    public function render()
    {
        return view('livewire.pendaftar.tes-psikologi')
            ->layout('components.layouts.dashboard', [
                'role' => 'pendaftar',
                'pageTitle' => 'Tes Psikologi',
                'pageDescription' => 'Upload bukti pembayaran tes psikologi'
            ]);
    }
}
