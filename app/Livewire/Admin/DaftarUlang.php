<?php

namespace App\Livewire\Admin;

use App\Models\DaftarUlang as DaftarUlangModel;
use App\Models\Pendaftar;
use App\Models\Pembayaran;
use App\Models\Seragam;
use App\Models\Dokumen;
use App\Models\RiwayatPesan;
use App\Traits\WithSorting;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Url;
use Illuminate\Support\Facades\Auth;

class DaftarUlang extends Component
{
    use WithPagination, WithSorting, WithFileUploads;

    #[Url(keep: true)]
    public $activeTab = 'madrasah';
    public $search = '';
    public $filterStatus = '';
    public $filterJenisKelamin = '';
    public $filterJalur = '';

    // For verification
    public $verificationStatus = '';
    public $verificationNote = '';

    // Properties for send message modal
    public $isSendMessageModalOpen = false;
    public $messageType = 'email'; // 'email' or 'whatsapp'
    public $selectedPendaftarId = null;
    public $selectedMessageTemplate = '';
    public $messageSubject = '';
    public $messageBody = '';
    public $messageNote = '';

    // Temporary variables for verification
    public $currentPendaftarId = null;
    public $currentDocumentType = null;
    public $currentPaymentType = null;

    // Modal catatan variables
    public $showCatatanModal = false;
    public $catatanType = '';
    public $catatanPendaftarId = null;
    public $catatanText = '';

    // Modal catatan internal variables (for admin/panitia only - NOT shown to pendaftar)
    public $showCatatanInternalModal = false;
    public $catatanInternalType = '';
    public $catatanInternalPendaftarId = null;
    public $catatanInternalText = '';

    // Settings variables
    public $madrasahContent = '';
    public $seragamContent = '';
    public $ukuranContent = '';
    public $madrasahInfo = '';  // Informasi untuk pendaftar - Madrasah
    public $seragamInfo = '';   // Informasi untuk pendaftar - Seragam
    public $ukuranInfo = '';    // Informasi untuk pendaftar - Ukuran
    public $paymentInfoText = ''; // Informasi Pembayaran untuk semua halaman daftar ulang
    public $showPernyataan = true;
    public $showPembayaranMadrasah = true;
    public $showPembayaranSeragam = true;
    public $showUkuranAtasan = true;
    public $showUkuranBawahan = true;
    public $showAtribut = true;

    // Madrasah Settings
    public $madrasahTemplateSuratPernyataan = null;
    public $existingMadrasahTemplate = null;
    public $madrasahPackages = []; // Hierarchical: packages with options and prices (NEW - replaces madrasahNominals & madrasahPaymentNominals)
    public $madrasahNominals = []; // DEPRECATED: Untuk surat pernyataan (backward compatibility)
    public $madrasahPaymentNominals = []; // DEPRECATED: Untuk konfirmasi nominal yang dibayarkan (backward compatibility)
    public $madrasahPaymentOptions = ['lunas' => false, 'angsuran' => false, 'potongan' => false];
    public $madrasahBanks = [];
    public $madrasahPaymentStart = null;
    public $madrasahPaymentEnd = null;
    public $madrasahRequirePaymentProof = false;
    public $madrasahEnableOpsiLainnya = false; // For manual input in konfirmasi nominal (unified)
    public $madrasahPackagesEnableOpsiLainnya = false; // For manual input in dropdown paket

    // Seragam Settings
    public $seragamPackages = [];  // Hierarchical: packages with options and prices
    public $seragamPaymentOptions = ['lunas' => false, 'angsuran' => false, 'potongan' => false];
    public $seragamBanks = [];
    public $seragamPaymentStart = null;
    public $seragamPaymentEnd = null;
    public $seragamRequirePaymentProof = false;
    public $seragamEnableOpsiLainnya = false;  // For manual input in konfirmasi nominal
    public $seragamPackagesEnableOpsiLainnya = false; // For manual input in dropdown paket

    // Ukuran Settings
    public $ukuranByGender = false;
    public $ukuranFieldsRequired = false;  // Kontrol apakah field ukuran wajib diisi semua (true) atau optional (false)
    public $ukuranAlertPerbedaanEnabled = true;  // Kontrol tampil/sembunyikan alert perbedaan ukuran
    public $ukuranAlertPerhitunganMode = 'terbesar';  // 'terbesar' atau 'baju_harian'
    public $ukuranJasImage = null;
    public $existingUkuranJasImage = null;
    public $ukuranJasOptions = [];
    public $ukuranJasEnableOpsiLainnya = false;
    public $ukuranBajuOlahragaImage = null;
    public $existingUkuranBajuOlahragaImage = null;
    public $ukuranBajuOlahragaOptions = [];
    public $ukuranBajuOlahragaEnableOpsiLainnya = false;
    public $ukuranCelanaOlahragaImage = null;
    public $existingUkuranCelanaOlahragaImage = null;
    public $ukuranCelanaOlahragaOptions = [];
    public $ukuranCelanaOlahragaEnableOpsiLainnya = false;
    public $ukuranBajuHarianImage = null;
    public $existingUkuranBajuHarianImage = null;
    public $ukuranBajuHarianOptions = [];
    public $ukuranBajuHarianEnableOpsiLainnya = false;
    public $ukuranCelanaRokHarianImage = null;
    public $existingUkuranCelanaRokHarianImage = null;
    public $ukuranCelanaRokHarianOptions = [];
    public $ukuranCelanaRokHarianEnableOpsiLainnya = false;
    public $ukuranKopyahImage = null;
    public $existingUkuranKopyahImage = null;
    public $ukuranKopyahOptions = [];
    public $ukuranKopyahEnableOpsiLainnya = false;
    // Legacy properties for backward compatibility
    public $ukuranAtasanImage = null;
    public $existingUkuranAtasanImage = null;
    public $ukuranAtasanOptions = [];
    public $ukuranBawahanImage = null;
    public $existingUkuranBawahanImage = null;
    public $ukuranBawahanOptions = [];

    public function mount()
    {
        $user = Auth::user();
        if (!$user || !in_array($user->role, ['admin', 'panitia_daftarulang', 'panitia'])) {
            return redirect()->to('/login/admin');
        }

        // Text & Boolean Settings
        $this->madrasahContent = \App\Models\Setting::get('daftar_ulang_madrasah_content', '');
        $this->seragamContent = \App\Models\Setting::get('daftar_ulang_seragam_content', '');
        $this->ukuranContent = \App\Models\Setting::get('daftar_ulang_ukuran_content', '');
        $this->madrasahInfo = \App\Models\Setting::get('daftar_ulang_madrasah_info', '');
        $this->seragamInfo = \App\Models\Setting::get('daftar_ulang_seragam_info', '');
        $this->ukuranInfo = \App\Models\Setting::get('daftar_ulang_ukuran_info', '');
        $this->paymentInfoText = \App\Models\Setting::get('daftar_ulang_payment_info_text', 'Pembayaran disesuaikan dengan kemampuan orang tua/wali. Apabila ingin berkomunikasi perihal kompensasi waktu/biaya/lainnya lebih lanjut, dapat dibicarakan dengan komite madrasah untuk menemukan solusi secara kesepakatan bersama. Waktu sesuai jadwal yang disampaikan oleh pihak komite madrasah.');

        $this->showPernyataan = \App\Models\Setting::get('daftar_ulang_show_pernyataan', true);
        $this->showPembayaranMadrasah = \App\Models\Setting::get('daftar_ulang_show_pembayaran_madrasah', true);
        $this->showPembayaranSeragam = \App\Models\Setting::get('daftar_ulang_show_pembayaran_seragam', true);
        $this->showUkuranAtasan = \App\Models\Setting::get('daftar_ulang_show_ukuran_atasan', true);
        $this->showUkuranBawahan = \App\Models\Setting::get('daftar_ulang_show_ukuran_bawahan', true);
        $this->showAtribut = \App\Models\Setting::get('daftar_ulang_show_atribut', true);

        // Madrasah JSON/Array Settings
        $this->existingMadrasahTemplate = \App\Models\Setting::get('daftar_ulang_madrasah_template');
        $this->madrasahPackages = \App\Models\Setting::get('daftar_ulang_madrasah_packages', []); // NEW: Load hierarchical packages
        $this->madrasahNominals = \App\Models\Setting::get('daftar_ulang_madrasah_nominals', []); // DEPRECATED: backward compatibility
        $this->madrasahPaymentNominals = \App\Models\Setting::get('daftar_ulang_madrasah_payment_nominals', []); // DEPRECATED: backward compatibility
        $this->madrasahPaymentOptions = \App\Models\Setting::get('daftar_ulang_madrasah_payment_options', ['lunas' => false, 'angsuran' => false, 'potongan' => false]);
        $this->madrasahBanks = \App\Models\Setting::get('daftar_ulang_madrasah_banks', []);
        $this->madrasahPaymentStart = \App\Models\Setting::get('daftar_ulang_madrasah_payment_start');
        $this->madrasahPaymentEnd = \App\Models\Setting::get('daftar_ulang_madrasah_payment_end');
        $this->madrasahRequirePaymentProof = \App\Models\Setting::get('daftar_ulang_madrasah_require_payment_proof', false);
        $this->madrasahEnableOpsiLainnya = \App\Models\Setting::get('daftar_ulang_madrasah_enable_opsi_lainnya', false);
        $this->madrasahPackagesEnableOpsiLainnya = \App\Models\Setting::get('daftar_ulang_madrasah_packages_enable_opsi_lainnya', false);

        // Seragam JSON/Array Settings
        $this->seragamPackages = \App\Models\Setting::get('daftar_ulang_seragam_packages', []);
        $this->seragamPaymentOptions = \App\Models\Setting::get('daftar_ulang_seragam_payment_options', ['lunas' => false, 'angsuran' => false, 'potongan' => false]);
        $this->seragamBanks = \App\Models\Setting::get('daftar_ulang_seragam_banks', []);
        $this->seragamPaymentStart = \App\Models\Setting::get('daftar_ulang_seragam_payment_start');
        $this->seragamPaymentEnd = \App\Models\Setting::get('daftar_ulang_seragam_payment_end');
        $this->seragamRequirePaymentProof = \App\Models\Setting::get('daftar_ulang_seragam_require_payment_proof', false);
        $this->seragamEnableOpsiLainnya = \App\Models\Setting::get('daftar_ulang_seragam_enable_opsi_lainnya', false);
        $this->seragamPackagesEnableOpsiLainnya = \App\Models\Setting::get('daftar_ulang_seragam_packages_enable_opsi_lainnya', false);

        // Ukuran JSON/Array Settings
        $this->ukuranByGender = \App\Models\Setting::get('ukuran_by_gender', false);
        $this->ukuranFieldsRequired = \App\Models\Setting::get('ukuran_fields_required', false);
        $this->ukuranAlertPerbedaanEnabled = \App\Models\Setting::get('ukuran_alert_perbedaan_enabled', true);
        $this->ukuranAlertPerhitunganMode = \App\Models\Setting::get('ukuran_alert_perhitungan_mode', 'terbesar');
        $this->existingUkuranJasImage = \App\Models\Setting::get('daftar_ulang_ukuran_jas_image');
        $this->existingUkuranBajuOlahragaImage = \App\Models\Setting::get('daftar_ulang_ukuran_baju_olahraga_image');
        $this->existingUkuranCelanaOlahragaImage = \App\Models\Setting::get('daftar_ulang_ukuran_celana_olahraga_image');
        $this->existingUkuranBajuHarianImage = \App\Models\Setting::get('daftar_ulang_ukuran_baju_harian_image');
        $this->existingUkuranCelanaRokHarianImage = \App\Models\Setting::get('daftar_ulang_ukuran_celana_rok_harian_image');
        $this->existingUkuranKopyahImage = \App\Models\Setting::get('daftar_ulang_ukuran_kopyah_image');
        // Legacy compatibility
        $this->existingUkuranAtasanImage = \App\Models\Setting::get('daftar_ulang_ukuran_atasan_image');
        $this->existingUkuranBawahanImage = \App\Models\Setting::get('daftar_ulang_ukuran_bawahan_image');

        $this->ukuranJasOptions = \App\Models\Setting::get('daftar_ulang_ukuran_jas_options', []);
        $this->ukuranJasEnableOpsiLainnya = \App\Models\Setting::get('daftar_ulang_ukuran_jas_enable_opsi_lainnya', false);
        $this->ukuranBajuOlahragaOptions = \App\Models\Setting::get('daftar_ulang_ukuran_baju_olahraga_options', []);
        $this->ukuranBajuOlahragaEnableOpsiLainnya = \App\Models\Setting::get('daftar_ulang_ukuran_baju_olahraga_enable_opsi_lainnya', false);
        $this->ukuranCelanaOlahragaOptions = \App\Models\Setting::get('daftar_ulang_ukuran_celana_olahraga_options', []);
        $this->ukuranCelanaOlahragaEnableOpsiLainnya = \App\Models\Setting::get('daftar_ulang_ukuran_celana_olahraga_enable_opsi_lainnya', false);
        $this->ukuranBajuHarianOptions = \App\Models\Setting::get('daftar_ulang_ukuran_baju_harian_options', []);
        $this->ukuranBajuHarianEnableOpsiLainnya = \App\Models\Setting::get('daftar_ulang_ukuran_baju_harian_enable_opsi_lainnya', false);
        $this->ukuranCelanaRokHarianOptions = \App\Models\Setting::get('daftar_ulang_ukuran_celana_rok_harian_options', []);
        $this->ukuranCelanaRokHarianEnableOpsiLainnya = \App\Models\Setting::get('daftar_ulang_ukuran_celana_rok_harian_enable_opsi_lainnya', false);
        $this->ukuranKopyahOptions = \App\Models\Setting::get('daftar_ulang_ukuran_kopyah_options', []);
        $this->ukuranKopyahEnableOpsiLainnya = \App\Models\Setting::get('daftar_ulang_ukuran_kopyah_enable_opsi_lainnya', false);
        // Legacy compatibility
        $this->ukuranAtasanOptions = \App\Models\Setting::get('daftar_ulang_ukuran_atasan_options', []);
        $this->ukuranBawahanOptions = \App\Models\Setting::get('daftar_ulang_ukuran_bawahan_options', []);
    }

    // Livewire lifecycle hook - dipanggil otomatis saat selectedMessageTemplate berubah
    public function updatedSelectedMessageTemplate()
    {
        $this->loadMessageTemplate();
    }

    // Livewire lifecycle hook - dipanggil otomatis saat messageType berubah
    public function updatedMessageType()
    {
        // Reset template saat ganti jenis pesan
        $this->selectedMessageTemplate = '';
        $this->messageSubject = '';
        $this->messageBody = '';
    }

    /**
     * Get available message templates based on active tab
     * Context: Dynamic based on tab (madrasah/seragam/ukuran)
     */
    public function getAvailableTemplates()
    {
        switch ($this->activeTab) {
            case 'madrasah':
                return [
                    [
                        'label' => '🏫 Daftar Ulang Madrasah',
                        'options' => [
                            ['value' => 'daftar_ulang_madrasah_disetujui', 'label' => 'Daftar Ulang Madrasah Disetujui'],
                            ['value' => 'daftar_ulang_madrasah_ditolak', 'label' => 'Daftar Ulang Madrasah Ditolak'],
                            ['value' => 'daftar_ulang_madrasah_perbaiki', 'label' => 'Daftar Ulang Madrasah Perlu Perbaikan'],
                        ]
                    ]
                ];

            case 'seragam':
                return [
                    [
                        'label' => '👕 Daftar Ulang Seragam',
                        'options' => [
                            ['value' => 'daftar_ulang_seragam_disetujui', 'label' => 'Daftar Ulang Seragam Disetujui'],
                            ['value' => 'daftar_ulang_seragam_ditolak', 'label' => 'Daftar Ulang Seragam Ditolak'],
                            ['value' => 'daftar_ulang_seragam_perbaiki', 'label' => 'Daftar Ulang Seragam Perlu Perbaikan'],
                        ]
                    ]
                ];

            case 'ukuran':
                return [
                    [
                        'label' => '📏 Ukuran Seragam',
                        'options' => [
                            ['value' => 'ukuran_seragam_disetujui', 'label' => 'Ukuran Seragam Disetujui'],
                            ['value' => 'ukuran_seragam_ditolak', 'label' => 'Ukuran Seragam Ditolak'],
                            ['value' => 'ukuran_seragam_perbaiki', 'label' => 'Ukuran Seragam Perlu Perbaikan'],
                            ['value' => 'ukuran_seragam_cek_kembali', 'label' => 'Cek Kembali Ukuran Seragam'],
                        ]
                    ]
                ];

            default:
                // Fallback untuk tab yang tidak dikenali
                return [];
        }
    }

    public function render()
    {
        $query = Pendaftar::with(['daftarUlang', 'pembayaran', 'seragam', 'dokumen', 'jalurPendaftaran'])
            ->whereIn('status_pendaftaran', ['data_disetujui', 'lulus', 'diterima']); // Hanya yang data disetujui (lulus)

        if ($this->search) {
            $query->where(function($q) {
                $q->where('nama_lengkap', 'ilike', '%' . $this->search . '%')
                  ->orWhere('nisn', 'ilike', '%' . $this->search . '%')
                  ->orWhere('nomor_pendaftaran', 'ilike', '%' . $this->search . '%');
            });
        }

        if ($this->filterStatus) {
            $query->whereHas('daftarUlang', function($q) {
                $q->where('status', $this->filterStatus);
            });
        }

        // Filter jenis kelamin (untuk tab seragam dan ukuran)
        if ($this->filterJenisKelamin && in_array($this->activeTab, ['seragam', 'ukuran'])) {
            $query->where('jenis_kelamin', $this->filterJenisKelamin);
        }

        // Filter jalur
        if ($this->filterJalur) {
            $query->where('jalur_pendaftaran_id', $this->filterJalur);
        }

        $pendaftar = $this->applySorting($query)->paginate(10);

        // Calculate stats based on active tab
        // Get ALL pendaftar for total count
        $allPendaftar = Pendaftar::with(['daftarUlang', 'pembayaran', 'seragam'])->get();

        // Get only lulus pendaftar for lulus count
        $lulusPendaftar = $allPendaftar->filter(function($p) {
            return in_array($p->status_pendaftaran, ['lulus', 'diterima']);
        });

        $stats = [];
        if ($this->activeTab === 'madrasah') {
            $stats = [
                // Total ALL pendaftar
                'total_pendaftar' => $allPendaftar->count(),

                // Total pendaftar lulus (from kelulusan menu)
                'lulus' => $lulusPendaftar->count(),

                // Backward compatibility
                'total_lulus' => $lulusPendaftar->count(),

                // Status persetujuan daftar ulang (dari pendaftar.status_persetujuan_daftar_ulang)
                'setuju' => $allPendaftar->filter(function($p) {
                    return $p->status_persetujuan_daftar_ulang === 'setuju';
                })->count(),
                'tidak_setuju' => $allPendaftar->filter(function($p) {
                    return $p->status_persetujuan_daftar_ulang === 'tidak_setuju';
                })->count(),

                // Status proses daftar ulang (dari daftar_ulang.status)
                'menunggu' => $allPendaftar->filter(function($p) {
                    return !$p->daftarUlang || $p->daftarUlang->status === 'menunggu' || !$p->daftarUlang->status;
                })->count(),
                'disetujui' => $allPendaftar->filter(function($p) {
                    return $p->daftarUlang && $p->daftarUlang->status === 'disetujui';
                })->count(),
                'ditolak' => $allPendaftar->filter(function($p) {
                    return $p->daftarUlang && $p->daftarUlang->status === 'ditolak';
                })->count(),
                'perlu_perbaikan' => $allPendaftar->filter(function($p) {
                    return $p->daftarUlang && $p->daftarUlang->status === 'perbaiki';
                })->count(),

                // Status upload surat pernyataan
                'upload_surat' => $allPendaftar->filter(function($p) {
                    return $p->daftarUlang && $p->daftarUlang->surat_pernyataan_path;
                })->count(),
            ];
        } elseif ($this->activeTab === 'seragam') {
            $stats = [
                // Total ALL pendaftar
                'total_pendaftar' => $allPendaftar->count(),

                // Total pendaftar lulus (from kelulusan menu)
                'lulus' => $lulusPendaftar->count(),

                // Backward compatibility
                'total_lulus' => $lulusPendaftar->count(),

                // Status seragam (dari seragam.status_seragam)
                'menunggu' => $allPendaftar->filter(function($p) {
                    return !$p->seragam || $p->seragam->status_seragam === 'menunggu' || !$p->seragam->status_seragam;
                })->count(),
                'disetujui' => $allPendaftar->filter(function($p) {
                    return $p->seragam && $p->seragam->status_seragam === 'disetujui';
                })->count(),
                'ditolak' => $allPendaftar->filter(function($p) {
                    return $p->seragam && $p->seragam->status_seragam === 'ditolak';
                })->count(),
                'perlu_perbaikan' => $allPendaftar->filter(function($p) {
                    return $p->seragam && $p->seragam->status_seragam === 'perbaiki';
                })->count(),

                // Status ukuran (dari seragam.status_ukuran)
                'ukuran_menunggu' => $allPendaftar->filter(function($p) {
                    return !$p->seragam || $p->seragam->status_ukuran === 'menunggu' || !$p->seragam->status_ukuran;
                })->count(),
                'ukuran_disetujui' => $allPendaftar->filter(function($p) {
                    return $p->seragam && $p->seragam->status_ukuran === 'disetujui';
                })->count(),
                'ukuran_ditolak' => $allPendaftar->filter(function($p) {
                    return $p->seragam && $p->seragam->status_ukuran === 'ditolak';
                })->count(),
            ];
        } elseif ($this->activeTab === 'ukuran') {
            $stats = [
                // Total ALL pendaftar
                'total_pendaftar' => $allPendaftar->count(),

                // Total pendaftar lulus (from kelulusan menu)
                'lulus' => $lulusPendaftar->count(),

                // Backward compatibility
                'total_lulus' => $lulusPendaftar->count(),

                // Status ukuran (dari seragam.status_ukuran)
                'menunggu' => $allPendaftar->filter(function($p) {
                    return !$p->seragam || $p->seragam->status_ukuran === 'menunggu' || !$p->seragam->status_ukuran;
                })->count(),
                'disetujui' => $allPendaftar->filter(function($p) {
                    return $p->seragam && $p->seragam->status_ukuran === 'disetujui';
                })->count(),
                'ditolak' => $allPendaftar->filter(function($p) {
                    return $p->seragam && $p->seragam->status_ukuran === 'ditolak';
                })->count(),
                'perlu_perbaikan' => $allPendaftar->filter(function($p) {
                    return $p->seragam && $p->seragam->status_ukuran === 'perbaiki';
                })->count(),
            ];
        }

        // Load jalur pendaftaran untuk filter
        $jalurPendaftaran = \App\Models\JalurPendaftaran::where('aktif', true)
            ->orderBy('urutan')
            ->get();

        return view('livewire.admin.daftar-ulang', [
            'pendaftar' => $pendaftar,
            'stats' => $stats,
            'jalurPendaftaran' => $jalurPendaftaran
        ])->layout('components.layouts.dashboard', [
            'role' => 'admin',
            'pageTitle' => 'Manajemen Daftar Ulang',
            'pageDescription' => 'Kelola proses daftar ulang pendaftar'
        ]);
    }

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function prepareVerification($pendaftarId, $documentType = null, $paymentType = null)
    {
        $this->currentPendaftarId = $pendaftarId;
        $this->currentDocumentType = $documentType;
        $this->currentPaymentType = $paymentType;
        $this->verificationStatus = $documentType ? 'diterima' : 'lunas'; // Default status
        $this->verificationNote = '';
    }

    public function confirmVerification()
    {
        if (!$this->currentPendaftarId) {
            return;
        }

        if ($this->currentDocumentType) {
            // Verify document
            $this->verifyDocument($this->currentPendaftarId, $this->currentDocumentType, $this->verificationStatus, $this->verificationNote);
        } elseif ($this->currentPaymentType) {
            // Verify payment based on type
            if ($this->currentPaymentType === 'daftar_ulang') {
                $this->verifyDaftarUlang($this->currentPendaftarId, $this->verificationStatus, $this->verificationNote);
            } elseif ($this->currentPaymentType === 'seragam') {
                $this->verifySeragam($this->currentPendaftarId, $this->verificationStatus, $this->verificationNote);
            }
        }

        // Reset the verification form
        $this->reset(['currentPendaftarId', 'currentDocumentType', 'currentPaymentType', 'verificationStatus', 'verificationNote']);
    }

    public function saveSettings()
    {
        $this->validate([
            'madrasahContent' => 'nullable|string',
            'seragamContent' => 'nullable|string',
            'ukuranContent' => 'nullable|string',
            'paymentInfoText' => 'nullable|string',
        ]);

        \App\Models\Setting::set('daftar_ulang_madrasah_content', $this->madrasahContent, 'text', 'Konten Daftar Ulang Madrasah');
        \App\Models\Setting::set('daftar_ulang_seragam_content', $this->seragamContent, 'text', 'Konten Daftar Ulang Seragam');
        \App\Models\Setting::set('daftar_ulang_ukuran_content', $this->ukuranContent, 'text', 'Konten Ukuran Seragam & Atribut');
        \App\Models\Setting::set('daftar_ulang_payment_info_text', $this->paymentInfoText, 'text', 'Informasi Pembayaran Daftar Ulang');
        \App\Models\Setting::set('daftar_ulang_show_pernyataan', $this->showPernyataan, 'boolean', 'Tampilkan Form Pernyataan');
        \App\Models\Setting::set('daftar_ulang_show_pembayaran_madrasah', $this->showPembayaranMadrasah, 'boolean', 'Tampilkan Form Pembayaran Madrasah');
        \App\Models\Setting::set('daftar_ulang_show_pembayaran_seragam', $this->showPembayaranSeragam, 'boolean', 'Tampilkan Form Pembayaran Seragam');
        \App\Models\Setting::set('daftar_ulang_show_ukuran_atasan', $this->showUkuranAtasan, 'boolean', 'Tampilkan Form Ukuran Atasan');
        \App\Models\Setting::set('daftar_ulang_show_ukuran_bawahan', $this->showUkuranBawahan, 'boolean', 'Tampilkan Form Ukuran Bawahan');
        \App\Models\Setting::set('daftar_ulang_show_atribut', $this->showAtribut, 'boolean', 'Tampilkan Form Atribut');

        $this->dispatch('saved', message: 'Pengaturan daftar ulang berhasil disimpan!');
    }

    public function verifyDocument($pendaftarId, $documentType, $status, $note = null)
    {
        $dokumen = Dokumen::where('pendaftar_id', $pendaftarId)
                         ->where('jenis_dokumen', $documentType)
                         ->first();

        if ($dokumen) {
            $dokumen->update([
                'status_verifikasi' => $status,
                'catatan' => $note
            ]);

            // Also update the corresponding status in verifikasi_berkas
            $verifikasiBerkas = \App\Models\VerifikasiBerkas::firstOrCreate(
                ['pendaftar_id' => $pendaftarId],
                ['status' => 'menunggu']
            );

            $statusField = 'status_' . $documentType;
            $catatanField = 'catatan_' . $documentType;

            $verifikasiBerkas->update([
                $statusField => $status,
                $catatanField => $note
            ]);

            $this->dispatch('saved', message: 'Status verifikasi dokumen berhasil diperbarui.');
        }
    }

    public function verifyDaftarUlang($pendaftarId, $status, $note = null)
    {
        $daftarUlang = DaftarUlangModel::firstOrCreate(
            ['pendaftar_id' => $pendaftarId],
            [
                'status' => $status,
                'catatan' => $note
            ]
        );

        $daftarUlang->update([
            'status' => $status,
            'catatan' => $note
        ]);

        $this->dispatch('saved', message: 'Status daftar ulang berhasil diperbarui.');
    }

    public function verifySeragam($pendaftarId, $status, $note = null)
    {
        $seragam = Seragam::firstOrCreate(
            ['pendaftar_id' => $pendaftarId],
            [
                'sudah_dipesan' => $status === 'diterima',
                'catatan' => $note
            ]
        );

        $seragam->update([
            'sudah_dipesan' => $status === 'diterima',
            'catatan' => $note
        ]);

        $this->dispatch('saved', message: 'Status seragam berhasil diperbarui.');
    }

    // ========== MADRASAH SETTINGS METHODS ==========

    /**
     * Add new madrasah package (hierarchical structure)
     */
    public function addMadrasahPackage()
    {
        $this->madrasahPackages[] = [
            'id' => 'pkg_' . uniqid(),
            'name' => '',
            'description' => '',
            'options' => []
        ];
    }

    /**
     * Remove madrasah package by index
     */
    public function removeMadrasahPackage($index)
    {
        unset($this->madrasahPackages[$index]);
        $this->madrasahPackages = array_values($this->madrasahPackages);
    }

    /**
     * Add option to a specific madrasah package
     */
    public function addMadrasahPackageOption($packageIndex)
    {
        if (!isset($this->madrasahPackages[$packageIndex]['options'])) {
            $this->madrasahPackages[$packageIndex]['options'] = [];
        }

        $this->madrasahPackages[$packageIndex]['options'][] = [
            'id' => 'opt_' . uniqid(),
            'name' => '',
            'amount' => ''
        ];
    }

    /**
     * Remove option from a specific madrasah package
     */
    public function removeMadrasahPackageOption($packageIndex, $optionIndex)
    {
        unset($this->madrasahPackages[$packageIndex]['options'][$optionIndex]);
        $this->madrasahPackages[$packageIndex]['options'] = array_values($this->madrasahPackages[$packageIndex]['options']);
    }

    // DEPRECATED: Old flat structure methods (kept for backward compatibility, but not used in new UI)
    public function addMadrasahNominal()
    {
        $this->madrasahNominals[] = ['name' => '', 'amount' => ''];
    }

    public function removeMadrasahNominal($index)
    {
        unset($this->madrasahNominals[$index]);
        $this->madrasahNominals = array_values($this->madrasahNominals);
    }

    public function addMadrasahPaymentNominal()
    {
        $this->madrasahPaymentNominals[] = ['name' => '', 'amount' => ''];
    }

    public function removeMadrasahPaymentNominal($index)
    {
        unset($this->madrasahPaymentNominals[$index]);
        $this->madrasahPaymentNominals = array_values($this->madrasahPaymentNominals);
    }

    public function addMadrasahBank()
    {
        $this->madrasahBanks[] = ['name' => ''];
    }

    public function removeMadrasahBank($index)
    {
        unset($this->madrasahBanks[$index]);
        $this->madrasahBanks = array_values($this->madrasahBanks);
    }

    public function saveMadrasahSettings()
    {
        // Handle Template Upload
        if ($this->madrasahTemplateSuratPernyataan) {
            $path = $this->madrasahTemplateSuratPernyataan->store('templates', 'public');
            \App\Models\Setting::set('daftar_ulang_madrasah_template', $path);
            $this->existingMadrasahTemplate = $path;
            $this->madrasahTemplateSuratPernyataan = null;
        }

        // Save settings (NEW: hierarchical packages)
        \App\Models\Setting::set('daftar_ulang_madrasah_info', $this->madrasahInfo);
        \App\Models\Setting::set('daftar_ulang_madrasah_packages', $this->madrasahPackages, 'json'); // NEW: Save hierarchical packages
        \App\Models\Setting::set('daftar_ulang_madrasah_payment_options', $this->madrasahPaymentOptions, 'json');
        \App\Models\Setting::set('daftar_ulang_madrasah_banks', $this->madrasahBanks, 'json');
        \App\Models\Setting::set('daftar_ulang_madrasah_payment_start', $this->madrasahPaymentStart);
        \App\Models\Setting::set('daftar_ulang_madrasah_payment_end', $this->madrasahPaymentEnd);
        \App\Models\Setting::set('daftar_ulang_madrasah_require_payment_proof', $this->madrasahRequirePaymentProof, 'boolean');
        \App\Models\Setting::set('daftar_ulang_madrasah_enable_opsi_lainnya', $this->madrasahEnableOpsiLainnya, 'boolean'); // Unified flag for konfirmasi nominal
        \App\Models\Setting::set('daftar_ulang_madrasah_packages_enable_opsi_lainnya', $this->madrasahPackagesEnableOpsiLainnya, 'boolean'); // Flag for dropdown paket

        // DEPRECATED: Old flat structures (kept for backward compatibility)
        \App\Models\Setting::set('daftar_ulang_madrasah_nominals', $this->madrasahNominals, 'json');
        \App\Models\Setting::set('daftar_ulang_madrasah_payment_nominals', $this->madrasahPaymentNominals, 'json');

        $this->dispatch('saved', message: 'Pengaturan daftar ulang madrasah berhasil disimpan.');
    }

    // ========== SERAGAM SETTINGS METHODS ==========
    public function addSeragamPackage()
    {
        $this->seragamPackages[] = [
            'id' => 'pkg_' . uniqid(),
            'name' => '',
            'description' => '',
            'options' => []
        ];
    }

    public function removeSeragamPackage($index)
    {
        unset($this->seragamPackages[$index]);
        $this->seragamPackages = array_values($this->seragamPackages);
    }

    public function addSeragamPackageOption($packageIndex)
    {
        if (!isset($this->seragamPackages[$packageIndex]['options'])) {
            $this->seragamPackages[$packageIndex]['options'] = [];
        }

        $this->seragamPackages[$packageIndex]['options'][] = [
            'id' => 'opt_' . uniqid(),
            'name' => '',
            'amount' => ''
        ];
    }

    public function removeSeragamPackageOption($packageIndex, $optionIndex)
    {
        unset($this->seragamPackages[$packageIndex]['options'][$optionIndex]);
        $this->seragamPackages[$packageIndex]['options'] = array_values($this->seragamPackages[$packageIndex]['options']);
    }

    public function addSeragamBank()
    {
        $this->seragamBanks[] = ['name' => ''];
    }

    public function removeSeragamBank($index)
    {
        unset($this->seragamBanks[$index]);
        $this->seragamBanks = array_values($this->seragamBanks);
    }

    public function saveSeragamSettings()
    {
        // Save settings
        \App\Models\Setting::set('daftar_ulang_seragam_info', $this->seragamInfo);
        \App\Models\Setting::set('daftar_ulang_seragam_packages', $this->seragamPackages, 'json');
        \App\Models\Setting::set('daftar_ulang_seragam_payment_options', $this->seragamPaymentOptions, 'json');
        \App\Models\Setting::set('daftar_ulang_seragam_banks', $this->seragamBanks, 'json');
        \App\Models\Setting::set('daftar_ulang_seragam_payment_start', $this->seragamPaymentStart);
        \App\Models\Setting::set('daftar_ulang_seragam_payment_end', $this->seragamPaymentEnd);
        \App\Models\Setting::set('daftar_ulang_seragam_require_payment_proof', $this->seragamRequirePaymentProof, 'boolean');
        \App\Models\Setting::set('daftar_ulang_seragam_enable_opsi_lainnya', $this->seragamEnableOpsiLainnya, 'boolean');
        \App\Models\Setting::set('daftar_ulang_seragam_packages_enable_opsi_lainnya', $this->seragamPackagesEnableOpsiLainnya, 'boolean'); // Flag for dropdown paket

        $this->dispatch('saved', message: 'Pengaturan daftar ulang seragam berhasil disimpan.');
    }

    // ========== UKURAN SETTINGS METHODS ==========
    public function addUkuranJas()
    {
        $this->ukuranJasOptions[] = ['size' => '', 'measurement' => ''];
    }

    public function removeUkuranJas($index)
    {
        unset($this->ukuranJasOptions[$index]);
        $this->ukuranJasOptions = array_values($this->ukuranJasOptions);
    }

    public function addUkuranAtasan()
    {
        $this->ukuranAtasanOptions[] = ['size' => '', 'measurement' => ''];
    }

    public function removeUkuranAtasan($index)
    {
        unset($this->ukuranAtasanOptions[$index]);
        $this->ukuranAtasanOptions = array_values($this->ukuranAtasanOptions);
    }

    public function addUkuranBawahan()
    {
        $this->ukuranBawahanOptions[] = ['size' => '', 'measurement' => ''];
    }

    public function removeUkuranBawahan($index)
    {
        unset($this->ukuranBawahanOptions[$index]);
        $this->ukuranBawahanOptions = array_values($this->ukuranBawahanOptions);
    }

    public function addUkuranKopyah()
    {
        $this->ukuranKopyahOptions[] = ['size' => '', 'measurement' => ''];
    }

    public function removeUkuranKopyah($index)
    {
        unset($this->ukuranKopyahOptions[$index]);
        $this->ukuranKopyahOptions = array_values($this->ukuranKopyahOptions);
    }

    public function addUkuranBajuOlahraga()
    {
        $this->ukuranBajuOlahragaOptions[] = ['size' => '', 'measurement' => ''];
    }

    public function removeUkuranBajuOlahraga($index)
    {
        unset($this->ukuranBajuOlahragaOptions[$index]);
        $this->ukuranBajuOlahragaOptions = array_values($this->ukuranBajuOlahragaOptions);
    }

    public function addUkuranCelanaOlahraga()
    {
        $this->ukuranCelanaOlahragaOptions[] = ['size' => '', 'measurement' => ''];
    }

    public function removeUkuranCelanaOlahraga($index)
    {
        unset($this->ukuranCelanaOlahragaOptions[$index]);
        $this->ukuranCelanaOlahragaOptions = array_values($this->ukuranCelanaOlahragaOptions);
    }

    public function addUkuranBajuHarian()
    {
        $this->ukuranBajuHarianOptions[] = ['size' => '', 'measurement' => ''];
    }

    public function removeUkuranBajuHarian($index)
    {
        unset($this->ukuranBajuHarianOptions[$index]);
        $this->ukuranBajuHarianOptions = array_values($this->ukuranBajuHarianOptions);
    }

    public function addUkuranCelanaRokHarian()
    {
        $this->ukuranCelanaRokHarianOptions[] = ['size' => '', 'measurement' => ''];
    }

    public function removeUkuranCelanaRokHarian($index)
    {
        unset($this->ukuranCelanaRokHarianOptions[$index]);
        $this->ukuranCelanaRokHarianOptions = array_values($this->ukuranCelanaRokHarianOptions);
    }

    public function saveUkuranSettings()
    {
        // Handle Image Uploads
        if ($this->ukuranJasImage) {
            $path = $this->ukuranJasImage->store('panduan-ukuran', 'public');
            \App\Models\Setting::set('daftar_ulang_ukuran_jas_image', $path);
            $this->existingUkuranJasImage = $path;
            $this->ukuranJasImage = null;
        }
        if ($this->ukuranBajuOlahragaImage) {
            $path = $this->ukuranBajuOlahragaImage->store('panduan-ukuran', 'public');
            \App\Models\Setting::set('daftar_ulang_ukuran_baju_olahraga_image', $path);
            $this->existingUkuranBajuOlahragaImage = $path;
            $this->ukuranBajuOlahragaImage = null;
        }
        if ($this->ukuranCelanaOlahragaImage) {
            $path = $this->ukuranCelanaOlahragaImage->store('panduan-ukuran', 'public');
            \App\Models\Setting::set('daftar_ulang_ukuran_celana_olahraga_image', $path);
            $this->existingUkuranCelanaOlahragaImage = $path;
            $this->ukuranCelanaOlahragaImage = null;
        }
        if ($this->ukuranBajuHarianImage) {
            $path = $this->ukuranBajuHarianImage->store('panduan-ukuran', 'public');
            \App\Models\Setting::set('daftar_ulang_ukuran_baju_harian_image', $path);
            $this->existingUkuranBajuHarianImage = $path;
            $this->ukuranBajuHarianImage = null;
        }
        if ($this->ukuranCelanaRokHarianImage) {
            $path = $this->ukuranCelanaRokHarianImage->store('panduan-ukuran', 'public');
            \App\Models\Setting::set('daftar_ulang_ukuran_celana_rok_harian_image', $path);
            $this->existingUkuranCelanaRokHarianImage = $path;
            $this->ukuranCelanaRokHarianImage = null;
        }
        if ($this->ukuranKopyahImage) {
            $path = $this->ukuranKopyahImage->store('panduan-ukuran', 'public');
            \App\Models\Setting::set('daftar_ulang_ukuran_kopyah_image', $path);
            $this->existingUkuranKopyahImage = $path;
            $this->ukuranKopyahImage = null;
        }
        // Legacy compatibility
        if ($this->ukuranAtasanImage) {
            $path = $this->ukuranAtasanImage->store('panduan-ukuran', 'public');
            \App\Models\Setting::set('daftar_ulang_ukuran_atasan_image', $path);
            $this->existingUkuranAtasanImage = $path;
            $this->ukuranAtasanImage = null;
        }
        if ($this->ukuranBawahanImage) {
            $path = $this->ukuranBawahanImage->store('panduan-ukuran', 'public');
            \App\Models\Setting::set('daftar_ulang_ukuran_bawahan_image', $path);
            $this->existingUkuranBawahanImage = $path;
            $this->ukuranBawahanImage = null;
        }

        // Save Settings
        \App\Models\Setting::set('daftar_ulang_ukuran_info', $this->ukuranInfo);
        \App\Models\Setting::set('ukuran_by_gender', $this->ukuranByGender, 'boolean');
        \App\Models\Setting::set('ukuran_fields_required', $this->ukuranFieldsRequired, 'boolean');
        \App\Models\Setting::set('ukuran_alert_perbedaan_enabled', $this->ukuranAlertPerbedaanEnabled, 'boolean');
        \App\Models\Setting::set('ukuran_alert_perhitungan_mode', $this->ukuranAlertPerhitunganMode, 'string');
        \App\Models\Setting::set('daftar_ulang_ukuran_jas_options', $this->ukuranJasOptions, 'json');
        \App\Models\Setting::set('daftar_ulang_ukuran_jas_enable_opsi_lainnya', $this->ukuranJasEnableOpsiLainnya, 'boolean');
        \App\Models\Setting::set('daftar_ulang_ukuran_baju_olahraga_options', $this->ukuranBajuOlahragaOptions, 'json');
        \App\Models\Setting::set('daftar_ulang_ukuran_baju_olahraga_enable_opsi_lainnya', $this->ukuranBajuOlahragaEnableOpsiLainnya, 'boolean');
        \App\Models\Setting::set('daftar_ulang_ukuran_celana_olahraga_options', $this->ukuranCelanaOlahragaOptions, 'json');
        \App\Models\Setting::set('daftar_ulang_ukuran_celana_olahraga_enable_opsi_lainnya', $this->ukuranCelanaOlahragaEnableOpsiLainnya, 'boolean');
        \App\Models\Setting::set('daftar_ulang_ukuran_baju_harian_options', $this->ukuranBajuHarianOptions, 'json');
        \App\Models\Setting::set('daftar_ulang_ukuran_baju_harian_enable_opsi_lainnya', $this->ukuranBajuHarianEnableOpsiLainnya, 'boolean');
        \App\Models\Setting::set('daftar_ulang_ukuran_celana_rok_harian_options', $this->ukuranCelanaRokHarianOptions, 'json');
        \App\Models\Setting::set('daftar_ulang_ukuran_celana_rok_harian_enable_opsi_lainnya', $this->ukuranCelanaRokHarianEnableOpsiLainnya, 'boolean');
        \App\Models\Setting::set('daftar_ulang_ukuran_kopyah_options', $this->ukuranKopyahOptions, 'json');
        \App\Models\Setting::set('daftar_ulang_ukuran_kopyah_enable_opsi_lainnya', $this->ukuranKopyahEnableOpsiLainnya, 'boolean');
        // Legacy compatibility
        \App\Models\Setting::set('daftar_ulang_ukuran_atasan_options', $this->ukuranAtasanOptions, 'json');
        \App\Models\Setting::set('daftar_ulang_ukuran_bawahan_options', $this->ukuranBawahanOptions, 'json');

        $this->dispatch('saved', message: 'Pengaturan ukuran seragam berhasil disimpan.');
    }

    public function resetPersetujuan($pendaftarId)
    {
        try {
            $pendaftar = Pendaftar::findOrFail($pendaftarId);

            // Reset status persetujuan ke belum_memilih
            $pendaftar->update([
                'status_persetujuan_daftar_ulang' => 'belum_memilih',
                'setuju_daftar_ulang' => false,
                'tanggal_pilihan_daftar_ulang' => null,
            ]);

            // Hapus record daftar ulang jika ada (agar bisa dibuat ulang)
            if ($pendaftar->daftarUlang) {
                $pendaftar->daftarUlang->delete();
            }

            $this->dispatch('saved', message: 'Persetujuan pendaftar berhasil direset. Pendaftar dapat memilih lagi.');

        } catch (\Exception $e) {
            $this->dispatch('saved', message: 'Gagal reset persetujuan: ' . $e->getMessage());
        }
    }

    public function changeStatusPersetujuan($pendaftarId, $status)
    {
        try {
            $pendaftar = Pendaftar::findOrFail($pendaftarId);

            // Update status persetujuan
            $pendaftar->update([
                'status_persetujuan_daftar_ulang' => $status,
                'setuju_daftar_ulang' => $status === 'setuju',
                'tanggal_pilihan_daftar_ulang' => now(),
            ]);

            // Create or update daftar ulang record if approving
            if ($status === 'setuju') {
                DaftarUlangModel::firstOrCreate(
                    ['pendaftar_id' => $pendaftarId],
                    ['status' => 'menunggu']
                );
            } elseif ($status === 'tidak_setuju' && $pendaftar->daftarUlang) {
                // Delete daftar ulang record if rejecting
                $pendaftar->daftarUlang->delete();
            }

            $statusLabels = [
                'belum_memilih' => 'Belum Memilih',
                'setuju' => 'Setuju',
                'tidak_setuju' => 'Tidak Melanjutkan'
            ];
            $statusLabel = $statusLabels[$status] ?? $status;

            $this->dispatch('saved', message: "Status persetujuan berhasil diubah menjadi {$statusLabel}.");

        } catch (\Exception $e) {
            $this->dispatch('saved', message: 'Gagal mengubah status persetujuan: ' . $e->getMessage());
        }
    }

    public function changeStatusMadrasah($pendaftarId, $status)
    {
        // Validasi status tidak boleh kosong
        if (empty($status)) {
            $this->dispatch('saved', message: 'Silakan pilih status yang valid.');
            return;
        }

        $daftarUlang = DaftarUlangModel::firstOrCreate(
            ['pendaftar_id' => $pendaftarId],
            ['status' => $status]
        );

        $daftarUlang->update(['status' => $status]);

        // Notification is sent manually via "Kirim Pesan" button
        // Auto-notification removed as per user request

        $statusLabels = [
            'disetujui' => 'disetujui',
            'ditolak' => 'ditolak',
            'perbaiki' => 'diminta untuk diperbaiki',
            'menunggu' => 'dikembalikan ke menunggu'
        ];
        $statusLabel = $statusLabels[$status] ?? $status;
        $this->dispatch('saved', message: "Status DU Madrasah berhasil diubah menjadi {$statusLabel}.");
    }

    public function changeStatusSeragam($pendaftarId, $status)
    {
        // Validasi status tidak boleh kosong
        if (empty($status)) {
            $this->dispatch('saved', message: 'Silakan pilih status yang valid.');
            return;
        }

        $seragam = Seragam::firstOrCreate(
            ['pendaftar_id' => $pendaftarId],
            ['status_seragam' => $status]
        );

        $seragam->update(['status_seragam' => $status]);

        // Notification is sent manually via "Kirim Pesan" button
        // Auto-notification removed as per user request

        $statusLabels = [
            'disetujui' => 'disetujui',
            'ditolak' => 'ditolak',
            'perbaiki' => 'diminta untuk diperbaiki',
            'menunggu' => 'dikembalikan ke menunggu'
        ];
        $statusLabel = $statusLabels[$status] ?? $status;
        $this->dispatch('saved', message: "Status DU Seragam berhasil diubah menjadi {$statusLabel}.");
    }

    public function changeStatusUkuran($pendaftarId, $status)
    {
        $seragam = Seragam::firstOrCreate(
            ['pendaftar_id' => $pendaftarId],
            ['status_ukuran' => $status]
        );

        $seragam->update(['status_ukuran' => $status]);

        // Notification is sent manually via "Kirim Pesan" button
        // Auto-notification removed as per user request

        $statusLabels = [
            'disetujui' => 'disetujui',
            'ditolak' => 'ditolak',
            'perbaiki' => 'diminta untuk diperbaiki',
            'menunggu' => 'dikembalikan ke menunggu'
        ];
        $statusLabel = $statusLabels[$status] ?? $status;
        $this->dispatch('saved', message: "Status Ukuran berhasil diubah menjadi {$statusLabel}.");
    }

    // ========== INDIVIDUAL OPSI LAINNYA TOGGLE METHODS ==========

    /**
     * Toggle "Opsi Lainnya Dropdown" for specific pendaftar
     * This allows admin to enable dropdown opsi lainnya for individual pendaftar
     * even if global setting is OFF
     */
    public function toggleOpsiLainnyaDropdownIndividual($pendaftarId, $tab)
    {
        try {
            if ($tab === 'madrasah') {
                $daftarUlang = DaftarUlangModel::firstOrCreate(
                    ['pendaftar_id' => $pendaftarId],
                    ['opsi_lainnya_dropdown_individual' => false]
                );

                $newValue = !$daftarUlang->opsi_lainnya_dropdown_individual;
                $daftarUlang->update(['opsi_lainnya_dropdown_individual' => $newValue]);

                $statusText = $newValue ? 'diaktifkan' : 'dinonaktifkan';
                $this->dispatch('saved', message: "Opsi Lainnya Dropdown (Madrasah) untuk pendaftar ini berhasil {$statusText}.");
            }
            elseif ($tab === 'seragam') {
                $seragam = Seragam::firstOrCreate(
                    ['pendaftar_id' => $pendaftarId],
                    ['opsi_lainnya_dropdown_individual' => false]
                );

                $newValue = !$seragam->opsi_lainnya_dropdown_individual;
                $seragam->update(['opsi_lainnya_dropdown_individual' => $newValue]);

                $statusText = $newValue ? 'diaktifkan' : 'dinonaktifkan';
                $this->dispatch('saved', message: "Opsi Lainnya Dropdown (Seragam) untuk pendaftar ini berhasil {$statusText}.");
            }
        } catch (\Exception $e) {
            $this->dispatch('saved', message: 'Gagal mengubah toggle: ' . $e->getMessage());
        }
    }

    /**
     * Toggle "Opsi Lainnya Konfirmasi" for specific pendaftar
     * This allows admin to enable konfirmasi nominal opsi lainnya for individual pendaftar
     * even if global setting is OFF
     */
    public function toggleOpsiLainnyaKonfirmasiIndividual($pendaftarId, $tab)
    {
        try {
            if ($tab === 'madrasah') {
                $daftarUlang = DaftarUlangModel::firstOrCreate(
                    ['pendaftar_id' => $pendaftarId],
                    ['opsi_lainnya_konfirmasi_individual' => false]
                );

                $newValue = !$daftarUlang->opsi_lainnya_konfirmasi_individual;
                $daftarUlang->update(['opsi_lainnya_konfirmasi_individual' => $newValue]);

                $statusText = $newValue ? 'diaktifkan' : 'dinonaktifkan';
                $this->dispatch('saved', message: "Opsi Lainnya Konfirmasi (Madrasah) untuk pendaftar ini berhasil {$statusText}.");
            }
            elseif ($tab === 'seragam') {
                $seragam = Seragam::firstOrCreate(
                    ['pendaftar_id' => $pendaftarId],
                    ['opsi_lainnya_konfirmasi_individual' => false]
                );

                $newValue = !$seragam->opsi_lainnya_konfirmasi_individual;
                $seragam->update(['opsi_lainnya_konfirmasi_individual' => $newValue]);

                $statusText = $newValue ? 'diaktifkan' : 'dinonaktifkan';
                $this->dispatch('saved', message: "Opsi Lainnya Konfirmasi (Seragam) untuk pendaftar ini berhasil {$statusText}.");
            }
        } catch (\Exception $e) {
            $this->dispatch('saved', message: 'Gagal mengubah toggle: ' . $e->getMessage());
        }
    }

    // ========== SEND MESSAGE METHODS ==========
    public function sendManualEmail($id)
    {
        $this->selectedPendaftarId = $id;
        $this->messageType = 'email';
        $this->selectedMessageTemplate = '';
        $this->messageSubject = '';
        $this->messageBody = '';
        $this->messageNote = '';
        $this->isSendMessageModalOpen = true;
    }

    public function sendManualWhatsApp($id)
    {
        $this->selectedPendaftarId = $id;
        $this->messageType = 'whatsapp';
        $this->selectedMessageTemplate = '';
        $this->messageSubject = '';
        $this->messageBody = '';
        $this->messageNote = '';
        $this->isSendMessageModalOpen = true;
    }

    public function closeSendMessageModal()
    {
        $this->isSendMessageModalOpen = false;
        $this->selectedPendaftarId = null;
        $this->messageType = 'email';
        $this->selectedMessageTemplate = '';
        $this->messageSubject = '';
        $this->messageBody = '';
        $this->messageNote = '';
    }

    public function loadMessageTemplate()
    {
        if (!$this->selectedMessageTemplate) {
            return;
        }

        $pendaftar = Pendaftar::with(['jalurPendaftaran', 'user'])->find($this->selectedPendaftarId);
        if (!$pendaftar) {
            return;
        }

        // Load template dari settings
        if ($this->messageType === 'email') {
            $this->messageSubject = \App\Models\Setting::get("email_{$this->selectedMessageTemplate}_subject", '');
            $this->messageBody = \App\Models\Setting::get("email_{$this->selectedMessageTemplate}_body", '');
        } else {
            $this->messageBody = \App\Models\Setting::get("wa_{$this->selectedMessageTemplate}_body", '');
        }

        // Replace placeholders (27 total)
        $replacements = [
            '{nama}' => $pendaftar->nama_lengkap ?? '',
            '{nomor_pendaftaran}' => $pendaftar->nomor_pendaftaran ?? '',
            '{nisn}' => $pendaftar->nisn ?? '',
            '{tempat_lahir}' => $pendaftar->tempat_lahir ?? '',
            '{tanggal_lahir}' => $pendaftar->tanggal_lahir ? $pendaftar->tanggal_lahir->format('d/m/Y') : '',
            '{jenis_kelamin}' => $pendaftar->jenis_kelamin === 'L' ? 'Laki-laki' : ($pendaftar->jenis_kelamin === 'P' ? 'Perempuan' : ''),
            '{alamat}' => $pendaftar->alamat ?? '',
            '{email}' => $pendaftar->email ?? '',
            '{telepon}' => $pendaftar->no_hp ?? '',
            '{no_hp}' => $pendaftar->no_hp ?? '',
            '{asal_sekolah}' => $pendaftar->asal_sekolah ?? '',
            '{jalur}' => $pendaftar->jalurPendaftaran->nama ?? '',
            '{status}' => $pendaftar->status_pendaftaran ?? '',
            '{catatan}' => $this->messageNote ?? '',
            '{url_dashboard}' => url('/dashboard'),
            '{password}' => '[Password telah dienkripsi, silakan reset jika lupa]',
            // Additional fields
            '{agama}' => $pendaftar->agama ?? '',
            '{nama_ayah}' => $pendaftar->nama_ayah ?? '',
            '{nama_ibu}' => $pendaftar->nama_ibu ?? '',
            '{pekerjaan_ayah}' => $pendaftar->pekerjaan_ayah ?? '',
            '{pekerjaan_ibu}' => $pendaftar->pekerjaan_ibu ?? '',
            '{provinsi}' => $pendaftar->provinsi ?? '',
            '{kabupaten}' => $pendaftar->kabupaten ?? '',
            '{kecamatan}' => $pendaftar->kecamatan ?? '',
            '{desa}' => $pendaftar->desa ?? '',
            '{kode_pos}' => $pendaftar->kode_pos ?? '',
            '{nilai_rata_rata}' => $pendaftar->nilai_rata_rata ?? '',
        ];

        $this->messageSubject = str_replace(array_keys($replacements), array_values($replacements), $this->messageSubject);
        $this->messageBody = str_replace(array_keys($replacements), array_values($replacements), $this->messageBody);
    }

    public function sendMessage()
    {
        $this->validate([
            'messageBody' => 'required|string',
        ]);

        if ($this->messageType === 'email') {
            $this->validate([
                'messageSubject' => 'required|string',
            ]);
        }

        $pendaftar = Pendaftar::with(['jalurPendaftaran', 'user'])->find($this->selectedPendaftarId);
        if (!$pendaftar) {
            $this->dispatch('saved', message: 'Pendaftar tidak ditemukan.');
            return;
        }

        $penerima = $this->messageType === 'email' ? $pendaftar->email : $pendaftar->no_hp;

        // Create log record with pending status
        $riwayatPesan = RiwayatPesan::create([
            'pendaftar_id' => $pendaftar->id,
            'user_id' => Auth::id(),
            'jenis_pesan' => $this->messageType,
            'judul' => $this->messageType === 'email' ? $this->messageSubject : null,
            'isi_pesan' => $this->messageBody,
            'penerima' => $penerima,
            'status' => 'pending',
        ]);

        try {
            if ($this->messageType === 'email') {
                // Kirim email
                \Illuminate\Support\Facades\Mail::raw($this->messageBody, function ($message) use ($pendaftar) {
                    $message->to($pendaftar->email)
                        ->subject($this->messageSubject);
                });

                // Update log to success
                $riwayatPesan->update([
                    'status' => 'terkirim',
                    'sent_at' => now(),
                ]);

                $this->dispatch('saved', message: 'Email berhasil dikirim ke ' . $pendaftar->nama_lengkap);
            } else {
                // Kirim WhatsApp
                $phoneNumber = $pendaftar->no_hp;
                if (!$phoneNumber) {
                    $riwayatPesan->update([
                        'status' => 'gagal',
                        'error_message' => 'Nomor WhatsApp tidak ditemukan',
                    ]);
                    $this->dispatch('saved', message: 'Nomor WhatsApp tidak ditemukan.');
                    return;
                }

                // Format nomor telepon
                $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
                if (substr($phoneNumber, 0, 1) === '0') {
                    $phoneNumber = '62' . substr($phoneNumber, 1);
                }

                // Placeholder untuk integrasi WhatsApp API
                // TODO: Implementasikan sesuai dengan service yang digunakan

                // Update log to success
                $riwayatPesan->update([
                    'status' => 'terkirim',
                    'sent_at' => now(),
                ]);

                $this->dispatch('saved', message: 'WhatsApp berhasil dikirim ke ' . $pendaftar->nama_lengkap);
            }

            $this->closeSendMessageModal();

        } catch (\Exception $e) {
            // Update log to failed with error message
            $riwayatPesan->update([
                'status' => 'gagal',
                'error_message' => $e->getMessage(),
            ]);

            $this->dispatch('saved', message: 'Gagal mengirim pesan: ' . $e->getMessage());
        }
    }

    /**
     * Open catatan modal
     */
    public function openCatatanModal($type, $pendaftarId, $currentCatatan = '')
    {
        $this->catatanType = $type;
        $this->catatanPendaftarId = $pendaftarId;
        $this->catatanText = $currentCatatan;
        $this->showCatatanModal = true;
    }

    /**
     * Close catatan modal
     */
    public function closeCatatanModal()
    {
        $this->showCatatanModal = false;
        $this->catatanType = '';
        $this->catatanPendaftarId = null;
        $this->catatanText = '';
    }

    /**
     * Save catatan for DU Madrasah, DU Seragam, or Ukuran Seragam
     */
    public function saveCatatan()
    {
        try {
            if ($this->catatanType === 'madrasah') {
                // Find or create daftar ulang
                $daftarUlang = DaftarUlangModel::firstOrNew(['pendaftar_id' => $this->catatanPendaftarId]);

                // Only set default status if it's a new record
                if (!$daftarUlang->exists) {
                    $daftarUlang->status = 'belum';
                }

                // Update catatan
                $daftarUlang->catatan = $this->catatanText;
                $daftarUlang->save();

                // Touch parent to trigger Livewire refresh
                $pendaftar = Pendaftar::find($this->catatanPendaftarId);
                if ($pendaftar) {
                    $pendaftar->touch();
                }

                $this->dispatch('saved', message: 'Catatan DU Madrasah berhasil disimpan');

            } elseif ($this->catatanType === 'seragam') {
                // Find or create seragam
                $seragam = Seragam::firstOrNew(['pendaftar_id' => $this->catatanPendaftarId]);

                // Update catatan
                $seragam->catatan_seragam = $this->catatanText;
                $seragam->save();

                // Touch parent to trigger Livewire refresh
                $pendaftar = Pendaftar::find($this->catatanPendaftarId);
                if ($pendaftar) {
                    $pendaftar->touch();
                }

                $this->dispatch('saved', message: 'Catatan DU Seragam berhasil disimpan');

            } elseif ($this->catatanType === 'ukuran') {
                // Find or create seragam
                $seragam = Seragam::firstOrNew(['pendaftar_id' => $this->catatanPendaftarId]);

                // Update catatan
                $seragam->catatan_ukuran = $this->catatanText;
                $seragam->save();

                // Touch parent to trigger Livewire refresh
                $pendaftar = Pendaftar::find($this->catatanPendaftarId);
                if ($pendaftar) {
                    $pendaftar->touch();
                }

                $this->dispatch('saved', message: 'Catatan Ukuran Seragam berhasil disimpan');
            }

            // Close modal
            $this->closeCatatanModal();

        } catch (\Exception $e) {
            $this->dispatch('saved', message: 'Gagal menyimpan catatan: ' . $e->getMessage());
            \Log::error('Error saving catatan: ' . $e->getMessage());
        }
    }

    /**
     * Open catatan internal modal (for admin/panitia only - NOT shown to pendaftar)
     */
    public function openCatatanInternalModal($type, $pendaftarId, $currentCatatan = '')
    {
        $this->catatanInternalType = $type;
        $this->catatanInternalPendaftarId = $pendaftarId;
        $this->catatanInternalText = $currentCatatan;
        $this->showCatatanInternalModal = true;
    }

    /**
     * Close catatan internal modal
     */
    public function closeCatatanInternalModal()
    {
        $this->showCatatanInternalModal = false;
        $this->catatanInternalType = '';
        $this->catatanInternalPendaftarId = null;
        $this->catatanInternalText = '';
    }

    /**
     * Save catatan internal for DU Madrasah, DU Seragam, or Ukuran Seragam
     * IMPORTANT: Catatan internal is for admin/panitia only and NOT shown to pendaftar
     */
    public function saveCatatanInternal()
    {
        try {
            if ($this->catatanInternalType === 'madrasah') {
                // Find or create daftar ulang
                $daftarUlang = DaftarUlangModel::firstOrNew(['pendaftar_id' => $this->catatanInternalPendaftarId]);

                // Only set default status if it's a new record
                if (!$daftarUlang->exists) {
                    $daftarUlang->status = 'belum';
                }

                // Update catatan_internal
                $daftarUlang->catatan_internal = $this->catatanInternalText;
                $daftarUlang->save();

                // Touch parent to trigger Livewire refresh
                $pendaftar = Pendaftar::find($this->catatanInternalPendaftarId);
                if ($pendaftar) {
                    $pendaftar->touch();
                }

                $this->dispatch('saved', message: 'Catatan Internal DU Madrasah berhasil disimpan');

            } elseif ($this->catatanInternalType === 'seragam') {
                // Find or create seragam
                $seragam = Seragam::firstOrNew(['pendaftar_id' => $this->catatanInternalPendaftarId]);

                // Update catatan_internal_seragam
                $seragam->catatan_internal_seragam = $this->catatanInternalText;
                $seragam->save();

                // Touch parent to trigger Livewire refresh
                $pendaftar = Pendaftar::find($this->catatanInternalPendaftarId);
                if ($pendaftar) {
                    $pendaftar->touch();
                }

                $this->dispatch('saved', message: 'Catatan Internal DU Seragam berhasil disimpan');

            } elseif ($this->catatanInternalType === 'ukuran') {
                // Find or create seragam
                $seragam = Seragam::firstOrNew(['pendaftar_id' => $this->catatanInternalPendaftarId]);

                // Update catatan_internal_ukuran
                $seragam->catatan_internal_ukuran = $this->catatanInternalText;
                $seragam->save();

                // Touch parent to trigger Livewire refresh
                $pendaftar = Pendaftar::find($this->catatanInternalPendaftarId);
                if ($pendaftar) {
                    $pendaftar->touch();
                }

                $this->dispatch('saved', message: 'Catatan Internal Ukuran Seragam berhasil disimpan');
            }

            // Close modal
            $this->closeCatatanInternalModal();

        } catch (\Exception $e) {
            $this->dispatch('saved', message: 'Gagal menyimpan catatan internal: ' . $e->getMessage());
            \Log::error('Error saving catatan internal: ' . $e->getMessage());
        }
    }
}
