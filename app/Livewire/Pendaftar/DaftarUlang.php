<?php

namespace App\Livewire\Pendaftar;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\DaftarUlang as DaftarUlangModel;
use App\Models\Setting;
use App\Services\FileNamingService;
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

    // Settings
    public $paymentInfoText;

    // Form Inputs - Madrasah
    public $nominalBiaya; // JSON encoded nominal yang dipilih
    public $opsiPembayaran;
    public $bankPilihan;
    public $buktiPembayaran;
    public $suratPernyataan;

    // Form Inputs - Seragam
    public $paketSeragam;
    public $nominalSeragam; // JSON encoded nominal yang dipilih
    public $opsiPembayaranSeragam;
    public $bankSeragam;
    public $buktiSeragam;

    // Form Inputs - Ukuran
    public $tinggi_badan;
    public $berat_badan;
    public $ukuran_jas;
    public $ukuran_baju_olahraga;
    public $ukuran_celana_olahraga;
    public $ukuran_baju_harian;
    public $ukuran_celana_rok_harian;
    public $ukuran_kopyah;
    public $ukuran_baju; // Keep for backward compatibility
    public $ukuran_celana; // Keep for backward compatibility
    public $lingkar_dada;
    public $lingkar_pinggang;
    public $panjang_lengan;
    public $ukuran_sepatu;
    public $ukuran_topi;

    public function mount()
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'pendaftar') {
            return redirect()->to('/login');
        }

        $this->pendaftar = $user->pendaftar;
        $this->daftarUlang = $this->pendaftar->daftarUlang;
        $this->seragam = $this->pendaftar->seragam ?? null;

        // Load payment info text setting
        $this->paymentInfoText = Setting::get('daftar_ulang_payment_info_text', 'Pembayaran disesuaikan dengan kemampuan orang tua/wali. Apabila ingin berkomunikasi perihal kompensasi waktu/biaya/lainnya lebih lanjut, dapat dibicarakan dengan komite madrasah untuk menemukan solusi secara kesepakatan bersama. Waktu sesuai jadwal yang disampaikan oleh pihak komite madrasah.');

        // Initialize inputs if data exists
        if ($this->daftarUlang) {
            $this->nominalBiaya = $this->daftarUlang->nominal_biaya ? json_encode($this->daftarUlang->nominal_biaya) : null;
            $this->opsiPembayaran = $this->daftarUlang->opsi_pembayaran;
            $this->bankPilihan = $this->daftarUlang->metode_pembayaran;
        }

        // Initialize seragam inputs if data exists
        if ($this->seragam) {
            $this->paketSeragam = $this->seragam->paket_seragam;
            $this->nominalSeragam = $this->seragam->nominal_seragam ? json_encode($this->seragam->nominal_seragam) : null;
            $this->opsiPembayaranSeragam = $this->seragam->opsi_pembayaran_seragam;
            $this->bankSeragam = $this->seragam->bank_seragam;
            $this->tinggi_badan = $this->seragam->tinggi_badan;
            $this->berat_badan = $this->seragam->berat_badan;

            // Ukuran detail seragam
            $this->ukuran_jas = $this->seragam->ukuran_jas;
            $this->ukuran_baju_olahraga = $this->seragam->ukuran_baju_olahraga;
            $this->ukuran_celana_olahraga = $this->seragam->ukuran_celana_olahraga;
            $this->ukuran_baju_harian = $this->seragam->ukuran_baju_harian;
            $this->ukuran_celana_rok_harian = $this->seragam->ukuran_celana_rok_harian;
            $this->ukuran_kopyah = $this->seragam->ukuran_kopyah;

            // Backward compatibility
            $this->ukuran_baju = $this->seragam->ukuran_baju;
            $this->ukuran_celana = $this->seragam->ukuran_celana;
            $this->lingkar_dada = $this->seragam->lingkar_dada;
            $this->lingkar_pinggang = $this->seragam->lingkar_pinggang;
            $this->panjang_lengan = $this->seragam->panjang_lengan;
            $this->ukuran_sepatu = $this->seragam->ukuran_sepatu;
            $this->ukuran_topi = $this->seragam->ukuran_topi;
        }
    }

    public function saveMadrasah()
    {
        $this->validate([
            'nominalBiaya' => 'required|string',
            'opsiPembayaran' => 'nullable|string',
            'bankPilihan' => 'nullable|string',
            'buktiPembayaran' => 'nullable|mimes:pdf,jpg,jpeg,png|max:2048',
            'suratPernyataan' => 'nullable|mimes:pdf|max:2048',
        ], [
            'nominalBiaya.required' => 'Silakan pilih nominal biaya terlebih dahulu.',
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

        // Decode nominal yang dipilih
        $nominalData = json_decode($this->nominalBiaya, true);

        $dataToUpdate = [
            'nominal_biaya' => $nominalData,
            'opsi_pembayaran' => $this->opsiPembayaran,
            'metode_pembayaran' => $this->bankPilihan,
        ];

        if ($this->buktiPembayaran) {
            // Generate nama file sistematis: bukti_daftar_ulang_{nomor_pendaftaran}_{timestamp}.{ext}
            $extension = FileNamingService::getFileExtension($this->buktiPembayaran);
            $customFileName = FileNamingService::generateBuktiBayarFileName(
                'daftar_ulang',
                $this->pendaftar->nomor_pendaftaran,
                $extension
            );

            // Simpan ke private storage untuk keamanan dokumen keuangan
            $path = $this->buktiPembayaran->storeAs(
                'bukti_bayar/' . $this->pendaftar->id,
                $customFileName,
                'private'
            );

            $dataToUpdate['bukti_pembayaran_madrasah'] = $path;
            $dataToUpdate['status'] = 'proses';
        }

        if ($this->suratPernyataan) {
            // Generate nama file sistematis: surat_pernyataan_{nomor_pendaftaran}_{timestamp}.pdf
            $extension = FileNamingService::getFileExtension($this->suratPernyataan);
            $customFileName = FileNamingService::generateFileName(
                'surat_pernyataan',
                $this->pendaftar->nomor_pendaftaran,
                $extension
            );

            // Simpan ke private storage
            $path = $this->suratPernyataan->storeAs(
                'dokumen/' . $this->pendaftar->id,
                $customFileName,
                'private'
            );

            $dataToUpdate['surat_pernyataan_path'] = $path;
        }

        $this->daftarUlang->update($dataToUpdate);

        session()->flash('message', 'Data daftar ulang madrasah berhasil disimpan.');
        $this->buktiPembayaran = null;
        $this->suratPernyataan = null;
        $this->daftarUlang = $this->daftarUlang->fresh();
    }

    public function saveSeragam()
    {
        $this->validate([
            'paketSeragam' => 'nullable|string',
            'nominalSeragam' => 'required|string',
            'opsiPembayaranSeragam' => 'nullable|string',
            'bankSeragam' => 'nullable|string',
            'buktiSeragam' => 'nullable|mimes:pdf,jpg,jpeg,png|max:5120',
        ], [
            'nominalSeragam.required' => 'Silakan pilih nominal biaya seragam terlebih dahulu.',
            'buktiSeragam.mimes' => 'Bukti pembayaran harus berupa file PDF atau Gambar (JPG, JPEG, PNG).',
            'buktiSeragam.max' => 'Ukuran file bukti pembayaran maksimal 5MB.',
        ]);

        if (!$this->seragam) {
            $this->seragam = \App\Models\Seragam::create([
                'pendaftar_id' => $this->pendaftar->id,
                'status_seragam' => 'menunggu',
            ]);
        }

        // Decode nominal yang dipilih
        $nominalSeragamData = json_decode($this->nominalSeragam, true);

        $dataToUpdate = [
            'paket_seragam' => $this->paketSeragam,
            'nominal_seragam' => $nominalSeragamData,
            'opsi_pembayaran_seragam' => $this->opsiPembayaranSeragam,
            'bank_seragam' => $this->bankSeragam,
        ];

        if ($this->buktiSeragam) {
            $extension = FileNamingService::getFileExtension($this->buktiSeragam);
            $customFileName = FileNamingService::generateBuktiBayarFileName(
                'seragam',
                $this->pendaftar->nomor_pendaftaran,
                $extension
            );

            $path = $this->buktiSeragam->storeAs(
                'bukti_bayar/' . $this->pendaftar->id,
                $customFileName,
                'private'
            );

            $dataToUpdate['bukti_pembayaran'] = $path;
            $dataToUpdate['status_seragam'] = 'menunggu';
        }

        $this->seragam->update($dataToUpdate);

        session()->flash('message_seragam', 'Data daftar ulang seragam berhasil disimpan.');
        $this->buktiSeragam = null;
        $this->seragam = $this->seragam->fresh();
        $this->pendaftar->load('seragam');
    }

    public function saveUkuran()
    {
        $this->validate([
            'tinggi_badan' => 'nullable|integer|min:100|max:250',
            'berat_badan' => 'nullable|integer|min:20|max:200',
            'ukuran_baju' => 'nullable|string',
            'ukuran_celana' => 'nullable|string',
            'lingkar_dada' => 'nullable|integer|min:50|max:150',
            'lingkar_pinggang' => 'nullable|integer|min:50|max:150',
            'panjang_lengan' => 'nullable|integer|min:30|max:100',
            'ukuran_sepatu' => 'nullable|string',
            'ukuran_topi' => 'nullable|string',
            // Detailed uniform sizes
            'ukuran_jas' => 'nullable|string',
            'ukuran_baju_olahraga' => 'nullable|string',
            'ukuran_celana_olahraga' => 'nullable|string',
            'ukuran_baju_harian' => 'nullable|string',
            'ukuran_celana_rok_harian' => 'nullable|string',
            'ukuran_kopyah' => 'nullable|string',
        ], [
            'tinggi_badan.integer' => 'Tinggi badan harus berupa angka.',
            'tinggi_badan.min' => 'Tinggi badan minimal 100 cm.',
            'tinggi_badan.max' => 'Tinggi badan maksimal 250 cm.',
            'berat_badan.integer' => 'Berat badan harus berupa angka.',
            'berat_badan.min' => 'Berat badan minimal 20 kg.',
            'berat_badan.max' => 'Berat badan maksimal 200 kg.',
        ]);

        if (!$this->seragam) {
            $this->seragam = \App\Models\Seragam::create([
                'pendaftar_id' => $this->pendaftar->id,
                'status_ukuran' => 'menunggu',
            ]);
        }

        $dataToUpdate = [
            'tinggi_badan' => $this->tinggi_badan,
            'berat_badan' => $this->berat_badan,
            'ukuran_baju' => $this->ukuran_baju,
            'ukuran_celana' => $this->ukuran_celana,
            'lingkar_dada' => $this->lingkar_dada,
            'lingkar_pinggang' => $this->lingkar_pinggang,
            'panjang_lengan' => $this->panjang_lengan,
            'ukuran_sepatu' => $this->ukuran_sepatu,
            'ukuran_topi' => $this->ukuran_topi,
            // Detailed uniform sizes
            'ukuran_jas' => $this->ukuran_jas,
            'ukuran_baju_olahraga' => $this->ukuran_baju_olahraga,
            'ukuran_celana_olahraga' => $this->ukuran_celana_olahraga,
            'ukuran_baju_harian' => $this->ukuran_baju_harian,
            'ukuran_celana_rok_harian' => $this->ukuran_celana_rok_harian,
            'ukuran_kopyah' => $this->ukuran_kopyah,
            'status_ukuran' => 'menunggu',
        ];

        $this->seragam->update($dataToUpdate);

        session()->flash('message_ukuran', 'Data ukuran seragam berhasil disimpan.');
        $this->seragam = $this->seragam->fresh();
        $this->pendaftar->load('seragam');
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
