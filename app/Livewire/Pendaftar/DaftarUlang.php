<?php

namespace App\Livewire\Pendaftar;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Url;
use App\Models\DaftarUlang as DaftarUlangModel;
use App\Models\Setting;
use App\Services\FileNamingService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DaftarUlang extends Component
{
    use WithFileUploads;

    #[Url(keep: true)]
    public $activeTab = 'madrasah';

    public $pendaftar;
    public $daftarUlang;
    public $seragam;

    // Settings
    public $paymentInfoText;

    // Enable Opsi Lainnya Settings
    public $madrasahEnableOpsiLainnya = false;
    public $seragamEnableOpsiLainnya = false;
    public $ukuranJasEnableOpsiLainnya = false;
    public $ukuranBajuOlahragaEnableOpsiLainnya = false;
    public $ukuranCelanaOlahragaEnableOpsiLainnya = false;
    public $ukuranBajuHarianEnableOpsiLainnya = false;
    public $ukuranCelanaRokHarianEnableOpsiLainnya = false;
    public $ukuranKopyahEnableOpsiLainnya = false;

    // Custom Opsi Lainnya Values
    public $madrasahOpsiLainnyaNominal;
    public $seragamOpsiLainnyaNominal;
    public $ukuranJasOpsiLainnya;
    public $ukuranBajuOlahragaOpsiLainnya;
    public $ukuranCelanaOlahragaOpsiLainnya;
    public $ukuranBajuHarianOpsiLainnya;
    public $ukuranCelanaRokHarianOpsiLainnya;
    public $ukuranKopyahOpsiLainnya;

    // Payment Nominals Enable Opsi Lainnya
    public $madrasahPaymentNominalsEnableOpsiLainnya = false;
    public $seragamPaymentNominalsEnableOpsiLainnya = false;

    // Custom Opsi Lainnya Values for Payment Nominals
    public $konfirmasiNominalBiayaOpsiLainnya;
    public $konfirmasiNominalSeragamOpsiLainnya;

    // Countdown & Payment Deadline
    public $madrasahPaymentStart;
    public $madrasahPaymentEnd;
    public $seragamPaymentStart;
    public $seragamPaymentEnd;
    public $isMadrasahPaymentOpen = false;
    public $isSeragamPaymentOpen = false;

    // Form Inputs - Madrasah
    public $nominalBiaya; // JSON encoded nominal yang dipilih
    public $konfirmasiNominalBiaya; // JSON encoded nominal konfirmasi
    public $opsiPembayaran;
    public $bankPilihan;
    public $buktiPembayaran;
    public $suratPernyataan;

    // Form Inputs - Seragam
    public $paketSeragam;
    public $nominalSeragam; // JSON encoded nominal yang dipilih
    public $konfirmasiNominalSeragam; // JSON encoded nominal konfirmasi
    public $opsiPembayaranSeragam;
    public $bankSeragam;
    public $buktiSeragam;

    // Form Inputs - Ukuran (NEW - specific fields only)
    public $ukuran_jas;
    public $ukuran_baju_olahraga;
    public $ukuran_celana_olahraga;
    public $ukuran_baju_harian;
    public $ukuran_celana_rok_harian;
    public $ukuran_kopyah;

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

        // Load "Opsi Lainnya" enable settings
        $this->madrasahEnableOpsiLainnya = Setting::get('daftar_ulang_madrasah_enable_opsi_lainnya', false);
        $this->seragamEnableOpsiLainnya = Setting::get('daftar_ulang_seragam_enable_opsi_lainnya', false);
        $this->ukuranJasEnableOpsiLainnya = Setting::get('daftar_ulang_ukuran_jas_enable_opsi_lainnya', false);
        $this->ukuranBajuOlahragaEnableOpsiLainnya = Setting::get('daftar_ulang_ukuran_baju_olahraga_enable_opsi_lainnya', false);
        $this->ukuranCelanaOlahragaEnableOpsiLainnya = Setting::get('daftar_ulang_ukuran_celana_olahraga_enable_opsi_lainnya', false);
        $this->ukuranBajuHarianEnableOpsiLainnya = Setting::get('daftar_ulang_ukuran_baju_harian_enable_opsi_lainnya', false);
        $this->ukuranCelanaRokHarianEnableOpsiLainnya = Setting::get('daftar_ulang_ukuran_celana_rok_harian_enable_opsi_lainnya', false);
        $this->ukuranKopyahEnableOpsiLainnya = Setting::get('daftar_ulang_ukuran_kopyah_enable_opsi_lainnya', false);

        // Load payment nominals enable opsi lainnya settings
        $this->madrasahPaymentNominalsEnableOpsiLainnya = Setting::get('daftar_ulang_madrasah_payment_nominals_enable_opsi_lainnya', false);
        $this->seragamPaymentNominalsEnableOpsiLainnya = Setting::get('daftar_ulang_seragam_payment_nominals_enable_opsi_lainnya', false);

        // Load payment deadline settings
        $this->madrasahPaymentStart = Setting::get('daftar_ulang_madrasah_payment_start');
        $this->madrasahPaymentEnd = Setting::get('daftar_ulang_madrasah_payment_end');
        $this->seragamPaymentStart = Setting::get('daftar_ulang_seragam_payment_start');
        $this->seragamPaymentEnd = Setting::get('daftar_ulang_seragam_payment_end');

        // Check if payment is still open
        $this->checkPaymentStatus();

        // Initialize inputs if data exists
        if ($this->daftarUlang) {
            // Load nominal biaya awal (untuk surat pernyataan)
            if ($this->daftarUlang->nominal_biaya_awal) {
                $nominalAwal = $this->daftarUlang->nominal_biaya_awal;
                // Check if it's "Opsi Lainnya"
                if (isset($nominalAwal['name']) && $nominalAwal['name'] === 'Opsi Lainnya') {
                    $this->nominalBiaya = 'opsi_lainnya';
                    $this->madrasahOpsiLainnyaNominal = $nominalAwal['amount'] ?? null;
                } else {
                    $this->nominalBiaya = json_encode($nominalAwal);
                }
            }

            // Load konfirmasi nominal (untuk pembayaran)
            if ($this->daftarUlang->nominal_biaya) {
                $nominalKonfirmasi = $this->daftarUlang->nominal_biaya;
                // Check if it's "Opsi Lainnya"
                if (isset($nominalKonfirmasi['name']) && $nominalKonfirmasi['name'] === 'Opsi Lainnya') {
                    $this->konfirmasiNominalBiaya = 'opsi_lainnya';
                    $this->konfirmasiNominalBiayaOpsiLainnya = $nominalKonfirmasi['amount'] ?? null;
                } else {
                    $this->konfirmasiNominalBiaya = json_encode($nominalKonfirmasi);
                }
            }

            $this->opsiPembayaran = $this->daftarUlang->opsi_pembayaran;
            $this->bankPilihan = $this->daftarUlang->metode_pembayaran;
        }

        // Initialize seragam inputs if data exists
        if ($this->seragam) {
            $this->paketSeragam = $this->seragam->paket_seragam;

            // Load nominal seragam awal (untuk surat pernyataan)
            if ($this->seragam->nominal_seragam_awal) {
                $nominalSeragamAwal = $this->seragam->nominal_seragam_awal;
                // Check if it's "Opsi Lainnya"
                if (isset($nominalSeragamAwal['name']) && $nominalSeragamAwal['name'] === 'Opsi Lainnya') {
                    $this->nominalSeragam = 'opsi_lainnya';
                    $this->seragamOpsiLainnyaNominal = $nominalSeragamAwal['amount'] ?? null;
                } else {
                    $this->nominalSeragam = json_encode($nominalSeragamAwal);
                }
            }

            // Load konfirmasi nominal (untuk pembayaran)
            if ($this->seragam->nominal_seragam) {
                $nominalSeragamKonfirmasi = $this->seragam->nominal_seragam;
                // Check if it's "Opsi Lainnya"
                if (isset($nominalSeragamKonfirmasi['name']) && $nominalSeragamKonfirmasi['name'] === 'Opsi Lainnya') {
                    $this->konfirmasiNominalSeragam = 'opsi_lainnya';
                    $this->konfirmasiNominalSeragamOpsiLainnya = $nominalSeragamKonfirmasi['amount'] ?? null;
                } else {
                    $this->konfirmasiNominalSeragam = json_encode($nominalSeragamKonfirmasi);
                }
            }

            $this->opsiPembayaranSeragam = $this->seragam->opsi_pembayaran_seragam;
            $this->bankSeragam = $this->seragam->bank_seragam;

            // Ukuran detail seragam - with "Opsi Lainnya" detection
            // Ukuran Jas
            if ($this->seragam->ukuran_jas) {
                $ukuranJasOptions = Setting::get('daftar_ulang_ukuran_jas_options', []);
                $availableSizes = collect($ukuranJasOptions)->pluck('size')->toArray();

                if (in_array($this->seragam->ukuran_jas, $availableSizes)) {
                    $this->ukuran_jas = $this->seragam->ukuran_jas;
                } else {
                    // Custom value from "Opsi Lainnya"
                    $this->ukuran_jas = 'opsi_lainnya';
                    $this->ukuranJasOpsiLainnya = $this->seragam->ukuran_jas;
                }
            }

            // Ukuran Baju Olahraga
            if ($this->seragam->ukuran_baju_olahraga) {
                $ukuranBajuOlahragaOptions = Setting::get('daftar_ulang_ukuran_baju_olahraga_options', []);
                $availableSizes = collect($ukuranBajuOlahragaOptions)->pluck('size')->toArray();

                if (in_array($this->seragam->ukuran_baju_olahraga, $availableSizes)) {
                    $this->ukuran_baju_olahraga = $this->seragam->ukuran_baju_olahraga;
                } else {
                    $this->ukuran_baju_olahraga = 'opsi_lainnya';
                    $this->ukuranBajuOlahragaOpsiLainnya = $this->seragam->ukuran_baju_olahraga;
                }
            }

            // Ukuran Celana Olahraga
            if ($this->seragam->ukuran_celana_olahraga) {
                $ukuranCelanaOlahragaOptions = Setting::get('daftar_ulang_ukuran_celana_olahraga_options', []);
                $availableSizes = collect($ukuranCelanaOlahragaOptions)->pluck('size')->toArray();

                if (in_array($this->seragam->ukuran_celana_olahraga, $availableSizes)) {
                    $this->ukuran_celana_olahraga = $this->seragam->ukuran_celana_olahraga;
                } else {
                    $this->ukuran_celana_olahraga = 'opsi_lainnya';
                    $this->ukuranCelanaOlahragaOpsiLainnya = $this->seragam->ukuran_celana_olahraga;
                }
            }

            // Ukuran Baju Harian
            if ($this->seragam->ukuran_baju_harian) {
                $ukuranBajuHarianOptions = Setting::get('daftar_ulang_ukuran_baju_harian_options', []);
                $availableSizes = collect($ukuranBajuHarianOptions)->pluck('size')->toArray();

                if (in_array($this->seragam->ukuran_baju_harian, $availableSizes)) {
                    $this->ukuran_baju_harian = $this->seragam->ukuran_baju_harian;
                } else {
                    $this->ukuran_baju_harian = 'opsi_lainnya';
                    $this->ukuranBajuHarianOpsiLainnya = $this->seragam->ukuran_baju_harian;
                }
            }

            // Ukuran Celana/Rok Harian
            if ($this->seragam->ukuran_celana_rok_harian) {
                $ukuranCelanaRokHarianOptions = Setting::get('daftar_ulang_ukuran_celana_rok_harian_options', []);
                $availableSizes = collect($ukuranCelanaRokHarianOptions)->pluck('size')->toArray();

                if (in_array($this->seragam->ukuran_celana_rok_harian, $availableSizes)) {
                    $this->ukuran_celana_rok_harian = $this->seragam->ukuran_celana_rok_harian;
                } else {
                    $this->ukuran_celana_rok_harian = 'opsi_lainnya';
                    $this->ukuranCelanaRokHarianOpsiLainnya = $this->seragam->ukuran_celana_rok_harian;
                }
            }

            // Ukuran Kopyah
            if ($this->seragam->ukuran_kopyah) {
                $ukuranKopyahOptions = Setting::get('daftar_ulang_ukuran_kopyah_options', []);
                $availableSizes = collect($ukuranKopyahOptions)->pluck('size')->toArray();

                if (in_array($this->seragam->ukuran_kopyah, $availableSizes)) {
                    $this->ukuran_kopyah = $this->seragam->ukuran_kopyah;
                } else {
                    $this->ukuran_kopyah = 'opsi_lainnya';
                    $this->ukuranKopyahOpsiLainnya = $this->seragam->ukuran_kopyah;
                }
            }
        }
    }

    public function checkPaymentStatus()
    {
        $now = Carbon::now();

        // Check Madrasah Payment Status
        if ($this->madrasahPaymentStart && $this->madrasahPaymentEnd) {
            $start = Carbon::parse($this->madrasahPaymentStart);
            $end = Carbon::parse($this->madrasahPaymentEnd);
            $this->isMadrasahPaymentOpen = $now->between($start, $end);
        } else {
            // If no deadline set, payment is always open
            $this->isMadrasahPaymentOpen = true;
        }

        // Check Seragam Payment Status
        if ($this->seragamPaymentStart && $this->seragamPaymentEnd) {
            $start = Carbon::parse($this->seragamPaymentStart);
            $end = Carbon::parse($this->seragamPaymentEnd);
            $this->isSeragamPaymentOpen = $now->between($start, $end);
        } else {
            // If no deadline set, payment is always open
            $this->isSeragamPaymentOpen = true;
        }
    }

    public function saveMadrasah()
    {
        // Check if payment is still open
        $this->checkPaymentStatus();

        if (!$this->isMadrasahPaymentOpen && $this->buktiPembayaran) {
            session()->flash('error', 'Waktu pembayaran telah berakhir. Upload bukti pembayaran tidak dapat dilakukan.');
            return;
        }

        $this->validate([
            'nominalBiaya' => 'required|string',
            'konfirmasiNominalBiaya' => 'required|string',
            'opsiPembayaran' => 'nullable|string',
            'bankPilihan' => 'nullable|string',
            'buktiPembayaran' => 'nullable|mimes:pdf,jpg,jpeg,png|max:2048',
            'suratPernyataan' => 'nullable|mimes:pdf|max:2048',
        ], [
            'nominalBiaya.required' => 'Silakan pilih nominal biaya terlebih dahulu.',
            'konfirmasiNominalBiaya.required' => 'Silakan pilih nominal yang akan dibayarkan.',
            'buktiPembayaran.mimes' => 'Bukti pembayaran harus berupa file PDF atau Gambar (JPG, JPEG, PNG).',
            'buktiPembayaran.max' => 'Ukuran file bukti pembayaran maksimal 2MB.',
            'suratPernyataan.mimes' => 'Surat pernyataan harus berupa file PDF.',
            'suratPernyataan.max' => 'Ukuran file surat pernyataan maksimal 2MB.',
        ]);

        if (!$this->daftarUlang) {
            $this->daftarUlang = DaftarUlangModel::create([
                'pendaftar_id' => $this->pendaftar->id,
                'status' => 'menunggu',
            ]);
        }

        // Decode nominal yang dipilih atau gunakan custom nominal jika opsi lainnya
        if ($this->nominalBiaya === 'opsi_lainnya') {
            if (!$this->madrasahOpsiLainnyaNominal) {
                session()->flash('error', 'Silakan masukkan nominal untuk Opsi Lainnya.');
                return;
            }
            $nominalData = [
                'name' => 'Opsi Lainnya',
                'amount' => $this->madrasahOpsiLainnyaNominal
            ];
        } else {
            $nominalData = json_decode($this->nominalBiaya, true);
        }

        // Handle konfirmasi nominal - decode atau gunakan custom nominal
        if ($this->konfirmasiNominalBiaya === 'opsi_lainnya') {
            if (!$this->konfirmasiNominalBiayaOpsiLainnya) {
                session()->flash('error', 'Silakan masukkan nominal konfirmasi untuk Opsi Lainnya.');
                return;
            }
            $konfirmasiNominalData = [
                'name' => 'Opsi Lainnya',
                'amount' => $this->konfirmasiNominalBiayaOpsiLainnya
            ];
        } else {
            $konfirmasiNominalData = json_decode($this->konfirmasiNominalBiaya, true);
        }

        $dataToUpdate = [
            'nominal_biaya_awal' => $nominalData, // Nominal yang dipilih untuk surat pernyataan
            'nominal_biaya' => $konfirmasiNominalData, // Konfirmasi nominal yang dibayarkan
            'opsi_pembayaran' => $this->opsiPembayaran,
            'metode_pembayaran' => $this->bankPilihan,
            'waktu_pembayaran_madrasah' => now(), // Timestamp pembayaran
        ];

        if ($this->buktiPembayaran) {
            // Generate nama file sistematis: bukti_daftar_ulang-{nomor_pendaftaran}-{nama_pendaftar}-{timestamp}.{ext}
            $extension = FileNamingService::getFileExtension($this->buktiPembayaran);
            $customFileName = FileNamingService::generateBuktiBayarFileName(
                'daftar_ulang',
                $this->pendaftar->nomor_pendaftaran,
                $extension,
                $this->pendaftar->nama_lengkap
            );

            // Simpan ke private storage untuk keamanan dokumen keuangan
            $path = $this->buktiPembayaran->storeAs(
                'bukti_bayar/' . $this->pendaftar->id,
                $customFileName,
                'private'
            );

            $dataToUpdate['bukti_pembayaran_madrasah'] = $path;
            $dataToUpdate['status'] = 'menunggu';
        }

        if ($this->suratPernyataan) {
            // Generate nama file sistematis: surat_pernyataan-{nomor_pendaftaran}-{nama_pendaftar}-{timestamp}.pdf
            $extension = FileNamingService::getFileExtension($this->suratPernyataan);
            $customFileName = FileNamingService::generateFileName(
                'surat_pernyataan',
                $this->pendaftar->nomor_pendaftaran,
                $extension,
                $this->pendaftar->nama_lengkap
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
        // Check if payment is still open
        $this->checkPaymentStatus();

        if (!$this->isSeragamPaymentOpen && $this->buktiSeragam) {
            session()->flash('error_seragam', 'Waktu pembayaran telah berakhir. Upload bukti pembayaran tidak dapat dilakukan.');
            return;
        }

        $this->validate([
            'paketSeragam' => 'nullable|string',
            'nominalSeragam' => 'required|string',
            'konfirmasiNominalSeragam' => 'required|string',
            'opsiPembayaranSeragam' => 'nullable|string',
            'bankSeragam' => 'nullable|string',
            'buktiSeragam' => 'nullable|mimes:pdf,jpg,jpeg,png|max:5120',
        ], [
            'nominalSeragam.required' => 'Silakan pilih nominal biaya seragam terlebih dahulu.',
            'konfirmasiNominalSeragam.required' => 'Silakan pilih nominal yang akan dibayarkan.',
            'buktiSeragam.mimes' => 'Bukti pembayaran harus berupa file PDF atau Gambar (JPG, JPEG, PNG).',
            'buktiSeragam.max' => 'Ukuran file bukti pembayaran maksimal 5MB.',
        ]);

        if (!$this->seragam) {
            $this->seragam = \App\Models\Seragam::create([
                'pendaftar_id' => $this->pendaftar->id,
                'status_seragam' => 'menunggu',
            ]);
        }

        // Decode nominal yang dipilih atau gunakan custom nominal jika opsi lainnya
        if ($this->nominalSeragam === 'opsi_lainnya') {
            if (!$this->seragamOpsiLainnyaNominal) {
                session()->flash('error_seragam', 'Silakan masukkan nominal untuk Opsi Lainnya.');
                return;
            }
            $nominalSeragamData = [
                'name' => 'Opsi Lainnya',
                'amount' => $this->seragamOpsiLainnyaNominal
            ];
        } else {
            $nominalSeragamData = json_decode($this->nominalSeragam, true);
        }

        // Handle konfirmasi nominal - decode atau gunakan custom nominal
        if ($this->konfirmasiNominalSeragam === 'opsi_lainnya') {
            if (!$this->konfirmasiNominalSeragamOpsiLainnya) {
                session()->flash('error_seragam', 'Silakan masukkan nominal konfirmasi untuk Opsi Lainnya.');
                return;
            }
            $konfirmasiNominalSeragamData = [
                'name' => 'Opsi Lainnya',
                'amount' => $this->konfirmasiNominalSeragamOpsiLainnya
            ];
        } else {
            $konfirmasiNominalSeragamData = json_decode($this->konfirmasiNominalSeragam, true);
        }

        $dataToUpdate = [
            'paket_seragam' => $this->paketSeragam,
            'nominal_seragam_awal' => $nominalSeragamData, // Nominal yang dipilih untuk surat pernyataan
            'nominal_seragam' => $konfirmasiNominalSeragamData, // Konfirmasi nominal yang dibayarkan
            'opsi_pembayaran_seragam' => $this->opsiPembayaranSeragam,
            'bank_seragam' => $this->bankSeragam,
            'waktu_pembayaran_seragam' => now(), // Timestamp pembayaran
        ];

        if ($this->buktiSeragam) {
            $extension = FileNamingService::getFileExtension($this->buktiSeragam);
            $customFileName = FileNamingService::generateBuktiBayarFileName(
                'seragam',
                $this->pendaftar->nomor_pendaftaran,
                $extension,
                $this->pendaftar->nama_lengkap
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
            // Detailed uniform sizes (NEW - specific fields only)
            'ukuran_jas' => 'nullable|string',
            'ukuran_baju_olahraga' => 'nullable|string',
            'ukuran_celana_olahraga' => 'nullable|string',
            'ukuran_baju_harian' => 'nullable|string',
            'ukuran_celana_rok_harian' => 'nullable|string',
            'ukuran_kopyah' => 'nullable|string',
        ]);

        if (!$this->seragam) {
            $this->seragam = \App\Models\Seragam::create([
                'pendaftar_id' => $this->pendaftar->id,
                'status_ukuran' => 'menunggu',
            ]);
        }

        // Handle "opsi lainnya" custom values for each ukuran field
        $ukuran_jas_value = $this->ukuran_jas;
        if ($this->ukuran_jas === 'opsi_lainnya') {
            $ukuran_jas_value = $this->ukuranJasOpsiLainnya ?? '';
        }

        $ukuran_baju_olahraga_value = $this->ukuran_baju_olahraga;
        if ($this->ukuran_baju_olahraga === 'opsi_lainnya') {
            $ukuran_baju_olahraga_value = $this->ukuranBajuOlahragaOpsiLainnya ?? '';
        }

        $ukuran_celana_olahraga_value = $this->ukuran_celana_olahraga;
        if ($this->ukuran_celana_olahraga === 'opsi_lainnya') {
            $ukuran_celana_olahraga_value = $this->ukuranCelanaOlahragaOpsiLainnya ?? '';
        }

        $ukuran_baju_harian_value = $this->ukuran_baju_harian;
        if ($this->ukuran_baju_harian === 'opsi_lainnya') {
            $ukuran_baju_harian_value = $this->ukuranBajuHarianOpsiLainnya ?? '';
        }

        $ukuran_celana_rok_harian_value = $this->ukuran_celana_rok_harian;
        if ($this->ukuran_celana_rok_harian === 'opsi_lainnya') {
            $ukuran_celana_rok_harian_value = $this->ukuranCelanaRokHarianOpsiLainnya ?? '';
        }

        $ukuran_kopyah_value = $this->ukuran_kopyah;
        if ($this->ukuran_kopyah === 'opsi_lainnya') {
            $ukuran_kopyah_value = $this->ukuranKopyahOpsiLainnya ?? '';
        }

        $dataToUpdate = [
            // Detailed uniform sizes with custom value handling
            'ukuran_jas' => $ukuran_jas_value,
            'ukuran_baju_olahraga' => $ukuran_baju_olahraga_value,
            'ukuran_celana_olahraga' => $ukuran_celana_olahraga_value,
            'ukuran_baju_harian' => $ukuran_baju_harian_value,
            'ukuran_celana_rok_harian' => $ukuran_celana_rok_harian_value,
            'ukuran_kopyah' => $ukuran_kopyah_value,
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
