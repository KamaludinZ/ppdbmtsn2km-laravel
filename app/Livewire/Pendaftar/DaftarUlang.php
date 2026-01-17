<?php

namespace App\Livewire\Pendaftar;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\DaftarUlang as DaftarUlangModel;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class DaftarUlang extends Component
{
    use WithFileUploads;

    public $pendaftar;
    public $daftarUlang;
    public $seragam;

    // Form Inputs - Madrasah
    public $opsiPembayaran;
    public $bankPilihan;
    public $buktiPembayaran;
    public $suratPernyataan;

    public function mount()
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'pendaftar') {
            return redirect()->to('/login');
        }

        $this->pendaftar = $user->pendaftar;
        $this->daftarUlang = $this->pendaftar->daftarUlang;
        $this->seragam = $this->pendaftar->seragam ?? null;

        // Initialize inputs if data exists
        if ($this->daftarUlang) {
            $this->opsiPembayaran = $this->daftarUlang->opsi_pembayaran;
            // bankPilihan might need decoding if stored as JSON or complex string
            // Assuming simplified storage for now or matching value
            $this->bankPilihan = $this->daftarUlang->metode_pembayaran; // Adjust column name if needed
        }
    }

    public function saveMadrasah()
    {
        $this->validate([
            'opsiPembayaran' => 'nullable|string',
            'bankPilihan' => 'nullable|string',
            'buktiPembayaran' => 'nullable|mimes:pdf,jpg,jpeg,png|max:2048',
            'suratPernyataan' => 'nullable|mimes:pdf|max:2048',
        ], [
            'buktiPembayaran.mimes' => 'Bukti pembayaran harus berupa file PDF atau Gambar (JPG, JPEG, PNG).',
            'buktiPembayaran.max' => 'Ukuran file bukti pembayaran maksimal 2MB.',
            'suratPernyataan.mimes' => 'Surat pernyataan harus berupa file PDF.',
            'suratPernyataan.max' => 'Ukuran file surat pernyataan maksimal 2MB.',
        ]);

        if (!$this->daftarUlang) {
            $this->daftarUlang = DaftarUlangModel::create([
                'pendaftar_id' => $this->pendaftar->id,
                'status' => 'proses',
            ]);
        }

        $dataToUpdate = [
            'opsi_pembayaran' => $this->opsiPembayaran,
            'metode_pembayaran' => $this->bankPilihan,
        ];

        if ($this->buktiPembayaran) {
            $mimeType = $this->buktiPembayaran->getMimeType();
            
            if (str_starts_with($mimeType, 'image/')) {
                // If image, convert to WebP
                $path = $this->convertAndStoreAsWebp($this->buktiPembayaran, 'bukti-bayar-madrasah');
            } else {
                // If PDF, store as is
                $path = $this->buktiPembayaran->store('bukti-bayar-madrasah', 'public');
            }
            
            $dataToUpdate['bukti_pembayaran_madrasah'] = $path;
            $dataToUpdate['status'] = 'proses';
        }

        if ($this->suratPernyataan) {
            $path = $this->suratPernyataan->store('surat-pernyataan', 'public');
            $dataToUpdate['surat_pernyataan_path'] = $path;
        }

        $this->daftarUlang->update($dataToUpdate);
        
        session()->flash('message', 'Data daftar ulang madrasah berhasil disimpan.');
        $this->buktiPembayaran = null;
        $this->suratPernyataan = null;
        $this->daftarUlang = $this->daftarUlang->fresh();
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
                // If not standard image, try storing directly or throw error
                // For safety, let's just store original if conversion fails logic
                // But validation ensures it is an image.
                throw new \Exception('Unsupported image type for conversion: ' . $mimeType);
        }

        // Convert to WebP with quality 80
        imagewebp($image, $fullPath, 80);

        // Free memory
        imagedestroy($image);

        // Return relative path
        return $folder . '/' . $filename;
    }

    public function previewInvoiceMadrasah()
    {
        if (!$this->daftarUlang || !$this->daftarUlang->bukti_pembayaran_madrasah) {
            return;
        }

        $printSettings = [
            'print_institution_name' => Setting::get('print_institution_name'),
            'print_institution_address' => Setting::get('print_institution_address'),
            'print_institution_phone' => Setting::get('print_institution_phone'),
            'print_institution_email' => Setting::get('print_institution_email'),
        ];

        $invoiceSettings = [
            'nomor_format' => Setting::get('invoice_madrasah_nomor_format', 'INV-MDR-{TAHUN}-{NOMOR}'),
            'footer' => Setting::get('invoice_madrasah_footer', ''),
        ];

        $details = Setting::get('daftar_ulang_madrasah_nominals', []);
        $banks = Setting::get('daftar_ulang_madrasah_banks', []);
        
        // Find selected bank or default
        $bank = collect($banks)->firstWhere('name', $this->daftarUlang->metode_pembayaran) ?? ($banks[0] ?? []);

        $pdf = Pdf::loadView('pdf.invoice-daftar-ulang-madrasah', [
            'pendaftar' => $this->pendaftar,
            'daftarUlang' => $this->daftarUlang,
            'printSettings' => $printSettings,
            'invoiceSettings' => $invoiceSettings,
            'details' => $details,
            'bank' => $bank,
        ]);

        $pdf->setPaper('A5', 'landscape');

        return response()->stream(function () use ($pdf) {
            echo $pdf->output();
        }, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="invoice-du-madrasah.pdf"',
        ]);
    }

    public function downloadInvoiceMadrasah()
    {
        if (!$this->daftarUlang || !$this->daftarUlang->bukti_pembayaran_madrasah) {
            return;
        }

        $printSettings = [
            'print_institution_name' => Setting::get('print_institution_name'),
            'print_institution_address' => Setting::get('print_institution_address'),
            'print_institution_phone' => Setting::get('print_institution_phone'),
            'print_institution_email' => Setting::get('print_institution_email'),
        ];

        $invoiceSettings = [
            'nomor_format' => Setting::get('invoice_madrasah_nomor_format', 'INV-MDR-{TAHUN}-{NOMOR}'),
            'footer' => Setting::get('invoice_madrasah_footer', ''),
        ];

        $details = Setting::get('daftar_ulang_madrasah_nominals', []);
        
        // Get Bank Info (Assume first bank or specific logic if needed, currently generic in view)
        $banks = Setting::get('daftar_ulang_madrasah_banks', []);
        $bank = !empty($banks) ? $banks[0] : [];

        $pdf = Pdf::loadView('pdf.invoice-daftar-ulang-madrasah', [
            'pendaftar' => $this->pendaftar,
            'daftarUlang' => $this->daftarUlang,
            'printSettings' => $printSettings,
            'invoiceSettings' => $invoiceSettings,
            'details' => $details,
            'bank' => $bank,
        ]);

        $pdf->setPaper('A5', 'landscape');

        $filename = 'invoice-du-madrasah-' . $this->pendaftar->nomor_pendaftaran . '-' . Str::slug($this->pendaftar->nama_lengkap) . '.pdf';

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $filename);
    }

    public function downloadInvoiceSeragam()
    {
        if (!$this->pendaftar->seragam || !$this->pendaftar->seragam->bukti_pembayaran) {
            return;
        }

        $printSettings = [
            'print_institution_name' => Setting::get('print_institution_name'),
            'print_institution_address' => Setting::get('print_institution_address'),
            'print_institution_phone' => Setting::get('print_institution_phone'),
            'print_institution_email' => Setting::get('print_institution_email'),
        ];

        $invoiceSettings = [
            'nomor_format' => Setting::get('invoice_seragam_nomor_format', 'INV-SRG-{TAHUN}-{NOMOR}'),
            'footer' => Setting::get('invoice_seragam_footer', ''),
        ];

        $details = Setting::get('daftar_ulang_seragam_nominals', []);
        
        // Get Bank Info
        $banks = Setting::get('daftar_ulang_seragam_banks', []);
        $bank = !empty($banks) ? $banks[0] : [];

        $pdf = Pdf::loadView('pdf.invoice-daftar-ulang-seragam', [
            'pendaftar' => $this->pendaftar,
            'seragam' => $this->pendaftar->seragam,
            'pendaftar' => $this->pendaftar, // duplicated key but consistent
            'printSettings' => $printSettings,
            'invoiceSettings' => $invoiceSettings,
            'details' => $details,
            'bank' => $bank,
        ]);

        $pdf->setPaper('A5', 'landscape');

        $filename = 'invoice-du-seragam-' . $this->pendaftar->nomor_pendaftaran . '-' . Str::slug($this->pendaftar->nama_lengkap) . '.pdf';

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $filename);
    }

    public function render()
    {
        return view('livewire.pendaftar.daftar-ulang')
            ->layout('components.layouts.dashboard', [
                'role' => 'pendaftar',
                'pageTitle' => 'Daftar Ulang',
                'pageDescription' => 'Informasi dan status daftar ulang'
            ]);
    }
}
