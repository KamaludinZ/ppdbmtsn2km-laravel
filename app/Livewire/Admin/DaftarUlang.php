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
    public $filterStatusSeragam = '';
    public $filterStatusUkuran = '';
    public $filterRekap = ''; // kategori rekap daftar ulang (lihat App\Support\RekapDaftarUlang)

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

    // For edit modal - store current record for computed property access
    public $currentEditDaftarUlang = null;
    public $currentEditSeragam = null;

    // Edit mode toggles
    public $madrasahEditMode = false;
    public $seragamEditMode = false;
    public $ukuranEditMode = false;

    // Edit modal properties for Madrasah
    public $showEditSuratPernyataanModal = false;
    public $showEditStatusMadrasahModal = false;
    public $editPendaftarId = null;
    public $editSuratPernyataanFile = null;
    public $editMadrasahPaket = null;
    public $editMadrasahPaketCustomName = null;
    public $editMadrasahPaketCustomAmount = null;
    public $editMadrasahOpsiPembayaran = null;
    public $editMadrasahNominalKonfirmasi = null;
    public $editMadrasahNominalKonfirmasiFinal = null;
    public $editMadrasahNominalKonfirmasiOpsiLainnya = null;
    public $editMadrasahMetodePembayaran = null;
    public $editMadrasahNamaPengirim = null;
    public $editMadrasahBuktiPembayaran = null;
    public $editMadrasahWaktuPembayaran = null;

    // Edit modal properties for Seragam
    public $showEditStatusSeragamModal = false;
    public $editSeragamPaket = null;
    public $editSeragamPaketCustomName = null;
    public $editSeragamPaketCustomAmount = null;
    public $editSeragamOpsiPembayaran = null;
    public $editSeragamNominalKonfirmasi = null;
    public $editSeragamNominalKonfirmasiFinal = null;
    public $editSeragamNominalKonfirmasiOpsiLainnya = null;
    public $editSeragamMetodePembayaran = null;
    public $editSeragamNamaPengirim = null;
    public $editSeragamBuktiPembayaran = null;
    public $editSeragamWaktuPembayaran = null;

    // Edit modal properties for Ukuran
    public $showEditStatusUkuranModal = false;
    public $editUkuranJas = null;
    public $editUkuranBajuOlahraga = null;
    public $editUkuranCelanaOlahraga = null;
    public $editUkuranBajuHarian = null;
    public $editUkuranCelanaRokHarian = null;
    public $editUkuranKopyah = null;

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
    public function updatedFilterRekap()
    {
        $this->resetPage();
    }

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
            ->whereIn('status_pendaftaran', \App\Support\RekapDaftarUlang::STATUS_WAJIB); // Hanya yang lulus (sama dengan rekap)

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

        // Filter Status DU Seragam - berlaku untuk semua tab seragam/ukuran
        if ($this->filterStatusSeragam) {
            if ($this->filterStatusSeragam === 'belum_diisi') {
                // Filter yang tidak punya record seragam ATAU status_seragam kosong/null
                $query->whereDoesntHave('seragam', function($q) {
                    $q->whereNotNull('status_seragam')
                      ->where('status_seragam', '!=', '')
                      ->where('status_seragam', '!=', 'belum_diisi');
                });
            } else {
                $query->whereHas('seragam', function($q) {
                    $q->where('status_seragam', $this->filterStatusSeragam);
                });
            }
        }

        // Filter Status Ukuran - berlaku untuk semua tab seragam/ukuran
        if ($this->filterStatusUkuran) {
            if ($this->filterStatusUkuran === 'belum_diisi') {
                // Filter yang tidak punya record seragam ATAU status_ukuran kosong/null
                $query->whereDoesntHave('seragam', function($q) {
                    $q->whereNotNull('status_ukuran')
                      ->where('status_ukuran', '!=', '')
                      ->where('status_ukuran', '!=', 'belum_diisi');
                });
            } else {
                $query->whereHas('seragam', function($q) {
                    $q->where('status_ukuran', $this->filterStatusUkuran);
                });
            }
        }

        // Filter jalur
        if ($this->filterJalur) {
            $query->where('jalur_pendaftaran_id', $this->filterJalur);
        }

        // Filter kategori rekap (belum konfirmasi, tidak melanjutkan, selesai, dst.)
        \App\Support\RekapDaftarUlang::filterKategori($query, $this->filterRekap, in_array($this->activeTab, ['seragam', 'ukuran']) ? $this->activeTab : 'madrasah');

        $pendaftar = $this->applySorting($query)->paginate(10);

        // Rekap konsisten: setiap pendaftar lulus masuk tepat satu kategori
        // (termasuk yang belum konfirmasi / tidak melanjutkan), lihat App\Support\RekapDaftarUlang.
        $stats = in_array($this->activeTab, ['madrasah', 'seragam', 'ukuran'])
            ? \App\Support\RekapDaftarUlang::statsTab($this->activeTab, [
                'jalur' => $this->filterJalur,
                'jenis_kelamin' => in_array($this->activeTab, ['seragam', 'ukuran']) ? $this->filterJenisKelamin : null,
            ])
            : [];

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
            \App\Support\PersetujuanDaftarUlang::reset(Pendaftar::findOrFail($pendaftarId));
            $this->dispatch('saved', message: 'Persetujuan pendaftar berhasil direset. Pendaftar dapat memilih lagi.');
        } catch (\Throwable $e) {
            \Log::error('Gagal reset persetujuan daftar ulang', ['pendaftar_id' => $pendaftarId, 'error' => $e->getMessage()]);
            $this->dispatch('saved', message: 'Gagal reset persetujuan. Silakan coba lagi.');
        }
    }

    public function changeStatusPersetujuan($pendaftarId, $status)
    {
        try {
            \App\Support\PersetujuanDaftarUlang::ubahOlehPetugas(Pendaftar::findOrFail($pendaftarId), $status);
            $label = \App\Support\PersetujuanDaftarUlang::label($status);
            $this->dispatch('saved', message: "Status persetujuan berhasil diubah menjadi {$label}.");
        } catch (\RuntimeException $e) {
            $this->dispatch('saved', message: $e->getMessage());
        } catch (\Throwable $e) {
            \Log::error('Gagal mengubah persetujuan daftar ulang', ['pendaftar_id' => $pendaftarId, 'status' => $status, 'error' => $e->getMessage()]);
            $this->dispatch('saved', message: 'Gagal mengubah status persetujuan. Silakan coba lagi.');
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

    public function resetStatusMadrasah($pendaftarId)
    {
        $daftarUlang = DaftarUlangModel::where('pendaftar_id', $pendaftarId)->first();
        
        if ($daftarUlang) {
            // Reset status dan semua data terkait
            $daftarUlang->update([
                'status' => 'belum_diisi',
                'surat_pernyataan_path' => null,
                'nominal_biaya_awal' => null,
                'nominal_biaya' => null,
                'opsi_pembayaran' => null,
                'metode_pembayaran' => null,
                'nama_pengirim' => null,
                'bukti_pembayaran_madrasah' => null,
                'waktu_pembayaran_madrasah' => null,
                'catatan' => null,
                'catatan_internal' => null,
                'opsi_lainnya_dropdown_individual' => false,
                'opsi_lainnya_konfirmasi_individual' => false,
            ]);
            $this->dispatch('saved', message: 'Status DU Madrasah berhasil direset ke Belum Diisi dan semua data terkait telah dikosongkan.');
        } else {
            $this->dispatch('saved', message: 'Data daftar ulang tidak ditemukan.');
        }
    }

    public function resetStatusSeragam($pendaftarId)
    {
        $seragam = Seragam::where('pendaftar_id', $pendaftarId)->first();
        
        if ($seragam) {
            // Reset status dan semua data terkait
            $seragam->update([
                'status_seragam' => 'belum_diisi',
                'paket_seragam' => '', // Empty string instead of null
                'paket_seragam_custom_name' => null,
                'paket_seragam_custom_amount' => null,
                'opsi_seragam' => '', // Empty string instead of null
                'nominal_seragam_awal' => null,
                'nominal_seragam' => null,
                'opsi_pembayaran_seragam' => null,
                'bank_seragam' => null,
                'nama_pengirim' => null,
                'bukti_pembayaran' => null,
                'waktu_pembayaran_seragam' => null,
                'ukuran_baju_harian' => null,
                'ukuran_celana_rok_harian' => null,
                'catatan_seragam' => null,
                'catatan_internal_seragam' => null,
                'opsi_lainnya_dropdown_individual' => false,
                'opsi_lainnya_konfirmasi_individual' => false,
            ]);
            $this->dispatch('saved', message: 'Status DU Seragam berhasil direset ke Belum Diisi dan semua data terkait telah dikosongkan.');
        } else {
            $this->dispatch('saved', message: 'Data seragam tidak ditemukan.');
        }
    }

    public function resetStatusUkuran($pendaftarId)
    {
        $seragam = Seragam::where('pendaftar_id', $pendaftarId)->first();
        
        if ($seragam) {
            // Reset status dan semua data ukuran terkait
            $seragam->update([
                'status_ukuran' => 'belum_diisi',
                'ukuran_jas' => null,
                'ukuran_baju_olahraga' => null,
                'ukuran_celana_olahraga' => null,
                'ukuran_baju_harian' => null,
                'ukuran_celana_rok_harian' => null,
                'ukuran_kopyah' => null,
                'sudah_diukur' => false,
                'sudah_dipesan' => false,
                'sudah_diterima' => false,
                'tanggal_ukur' => null,
                'tanggal_pesan' => null,
                'tanggal_terima' => null,
                'catatan_ukuran' => null,
                'catatan_internal_ukuran' => null,
            ]);
            $this->dispatch('saved', message: 'Status Ukuran berhasil direset ke Belum Diisi dan semua data ukuran telah dikosongkan.');
        } else {
            $this->dispatch('saved', message: 'Data ukuran tidak ditemukan.');
        }
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
    public function openCatatanModal($type, $pendaftarId)
    {
        $this->catatanType = $type;
        $this->catatanPendaftarId = $pendaftarId;
        
        // Fetch current catatan from database based on type
        $currentCatatan = '';
        if ($type === 'madrasah') {
            $daftarUlang = DaftarUlangModel::where('pendaftar_id', $pendaftarId)->first();
            $currentCatatan = $daftarUlang?->catatan ?? '';
        } elseif ($type === 'seragam') {
            $seragam = Seragam::where('pendaftar_id', $pendaftarId)->first();
            $currentCatatan = $seragam?->catatan_seragam ?? '';
        } elseif ($type === 'ukuran') {
            $seragam = Seragam::where('pendaftar_id', $pendaftarId)->first();
            $currentCatatan = $seragam?->catatan_ukuran ?? '';
        }
        
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
                    $daftarUlang->status = 'belum_diisi';
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
    public function openCatatanInternalModal($type, $pendaftarId)
    {
        $this->catatanInternalType = $type;
        $this->catatanInternalPendaftarId = $pendaftarId;
        
        // Fetch current catatan_internal from database based on type
        $currentCatatan = '';
        if ($type === 'madrasah') {
            $daftarUlang = DaftarUlangModel::where('pendaftar_id', $pendaftarId)->first();
            $currentCatatan = $daftarUlang?->catatan_internal ?? '';
        } elseif ($type === 'seragam') {
            $seragam = Seragam::where('pendaftar_id', $pendaftarId)->first();
            $currentCatatan = $seragam?->catatan_internal_seragam ?? '';
        } elseif ($type === 'ukuran') {
            $seragam = Seragam::where('pendaftar_id', $pendaftarId)->first();
            $currentCatatan = $seragam?->catatan_internal_ukuran ?? '';
        }
        
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
                    $daftarUlang->status = 'belum_diisi';
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

    // ========== COMPUTED PROPERTIES FOR OPSI LAINNYA ==========
    
    /**
     * Check if "Opsi Lainnya" dropdown should be shown for Madrasah edit
     * Logic: Global Setting OR Individual Dropdown Setting
     */
    public function getShouldShowMadrasahOpsiLainnyaDropdownProperty()
    {
        $globalSetting = \App\Models\Setting::get('daftar_ulang_madrasah_packages_enable_opsi_lainnya', false);
        $individualSetting = false;

        try {
            if ($this->currentEditDaftarUlang) {
                $individualSetting = $this->currentEditDaftarUlang->opsi_lainnya_dropdown_individual ?? false;
            }
        } catch (\Exception $e) {
            // Silently fail
        }

        return $globalSetting || $individualSetting;
    }

    /**
     * Check if "Opsi Lainnya" konfirmasi should be shown for Madrasah edit
     * Logic: Global Setting OR Individual Konfirmasi Setting
     */
    public function getShouldShowMadrasahOpsiLainnyaKonfirmasiProperty()
    {
        $globalSetting = \App\Models\Setting::get('daftar_ulang_madrasah_enable_opsi_lainnya', false);
        $individualSetting = false;

        try {
            if ($this->currentEditDaftarUlang) {
                $individualSetting = $this->currentEditDaftarUlang->opsi_lainnya_konfirmasi_individual ?? false;
            }
        } catch (\Exception $e) {
            // Silently fail
        }

        return $globalSetting || $individualSetting;
    }

    /**
     * Check if "Opsi Lainnya" dropdown should be shown for Seragam edit
     * Logic: Global Setting OR Individual Dropdown Setting
     */
    public function getShouldShowSeragamOpsiLainnyaDropdownProperty()
    {
        $globalSetting = \App\Models\Setting::get('daftar_ulang_seragam_packages_enable_opsi_lainnya', false);
        $individualSetting = false;

        try {
            if ($this->currentEditSeragam) {
                $individualSetting = $this->currentEditSeragam->opsi_lainnya_dropdown_individual ?? false;
            }
        } catch (\Exception $e) {
            // Silently fail
        }

        return $globalSetting || $individualSetting;
    }

    /**
     * Check if "Opsi Lainnya" konfirmasi should be shown for Seragam edit
     * Logic: Global Setting OR Individual Konfirmasi Setting
     */
    public function getShouldShowSeragamOpsiLainnyaKonfirmasiProperty()
    {
        $globalSetting = \App\Models\Setting::get('daftar_ulang_seragam_enable_opsi_lainnya', false);
        $individualSetting = false;

        try {
            if ($this->currentEditSeragam) {
                $individualSetting = $this->currentEditSeragam->opsi_lainnya_konfirmasi_individual ?? false;
            }
        } catch (\Exception $e) {
            // Silently fail
        }

        return $globalSetting || $individualSetting;
    }

    // ========== EDIT MODE METHODS ==========

    /**
     * Toggle edit mode for Madrasah tab
     */
    public function toggleMadrasahEditMode()
    {
        $this->madrasahEditMode = !$this->madrasahEditMode;
    }

    /**
     * Toggle edit mode for Seragam tab
     */
    public function toggleSeragamEditMode()
    {
        $this->seragamEditMode = !$this->seragamEditMode;
    }

    /**
     * Toggle edit mode for Ukuran tab
     */
    public function toggleUkuranEditMode()
    {
        $this->ukuranEditMode = !$this->ukuranEditMode;
    }

    /**
     * Open edit surat pernyataan modal for Madrasah
     */
    public function openEditSuratPernyataanModal($pendaftarId)
    {
        $this->editPendaftarId = $pendaftarId;
        $this->editSuratPernyataanFile = null;
        $this->showEditSuratPernyataanModal = true;
    }

    /**
     * Close edit surat pernyataan modal
     */
    public function closeEditSuratPernyataanModal()
    {
        $this->showEditSuratPernyataanModal = false;
        $this->editPendaftarId = null;
        $this->editSuratPernyataanFile = null;
    }

    /**
     * Save edit surat pernyataan
     */
    public function saveEditSuratPernyataan()
    {
        try {
            if (!$this->editPendaftarId) {
                $this->dispatch('saved', message: 'Error: Pendaftar ID tidak ditemukan');
                return;
            }

            $pendaftar = Pendaftar::find($this->editPendaftarId);
            if (!$pendaftar) {
                $this->dispatch('saved', message: 'Error: Pendaftar tidak ditemukan');
                return;
            }

            $daftarUlang = DaftarUlangModel::firstOrNew(['pendaftar_id' => $this->editPendaftarId]);
            
            // Handle file upload
            if ($this->editSuratPernyataanFile) {
                // Delete old file if exists
                if ($daftarUlang->surat_pernyataan_path) {
                    \Storage::disk('private')->delete($daftarUlang->surat_pernyataan_path);
                }

                // Generate nama file sistematis seperti pendaftar
                $extension = $this->editSuratPernyataanFile->getClientOriginalExtension();
                $customFileName = 'surat_pernyataan-' . $pendaftar->nomor_pendaftaran . '-' . \Str::slug($pendaftar->nama_lengkap) . '-' . now()->timestamp . '.' . $extension;

                // Upload ke private storage seperti pendaftar
                $path = $this->editSuratPernyataanFile->storeAs(
                    'dokumen/' . $pendaftar->id,
                    $customFileName,
                    'private'
                );
                
                $daftarUlang->surat_pernyataan_path = $path;
            }

            // Set default status if new
            if (!$daftarUlang->exists) {
                $daftarUlang->status = 'menunggu';
            }

            $daftarUlang->save();

            // Touch parent to trigger Livewire refresh
            $pendaftar->touch();

            $this->closeEditSuratPernyataanModal();
            $this->dispatch('saved', message: 'Surat pernyataan berhasil diupdate');

        } catch (\Exception $e) {
            $this->dispatch('saved', message: 'Gagal menyimpan surat pernyataan: ' . $e->getMessage());
            \Log::error('Error saving surat pernyataan: ' . $e->getMessage());
        }
    }

    /**
     * Open edit status Madrasah modal (Step 1 & 2 like pendaftar form)
     */
    public function openEditStatusMadrasahModal($pendaftarId)
    {
        $this->editPendaftarId = $pendaftarId;

        // Load existing data
        $pendaftar = Pendaftar::find($pendaftarId);
        if ($pendaftar && $pendaftar->daftarUlang) {
            $du = $pendaftar->daftarUlang;
            
            // Store the daftarUlang instance for computed property access
            $this->currentEditDaftarUlang = $du;

            // Load nominal_biaya_awal (opsi yang dipilih)
            $nominalBiayaAwal = $du->nominal_biaya_awal ?? null;
            
            \Log::info('Debug Madrasah Edit Load', [
                'pendaftar_id' => $pendaftarId,
                'nominal_biaya_awal' => $nominalBiayaAwal,
                'type' => gettype($nominalBiayaAwal),
            ]);
            
            if ($nominalBiayaAwal) {
                // Check if multi-select (array) or single select (object)
                if (is_array($nominalBiayaAwal) && isset($nominalBiayaAwal[0])) {
                    // Multi-select mode - set as array
                    $this->editMadrasahNominalKonfirmasi = $nominalBiayaAwal;
                    
                    // Try to find which package the first option belongs to
                    $firstOptionId = $nominalBiayaAwal[0]['id'] ?? null;
                    \Log::info('Multi-select mode', ['first_option_id' => $firstOptionId]);
                    
                    if ($firstOptionId) {
                        $madrasahPackages = \App\Models\Setting::get('daftar_ulang_madrasah_packages', []);
                        foreach ($madrasahPackages as $package) {
                            if (isset($package['options'])) {
                                foreach ($package['options'] as $option) {
                                    if (($option['id'] ?? '') === $firstOptionId) {
                                        // Found matching package - store as array for Livewire
                                        $this->editMadrasahPaket = $package;
                                        \Log::info('Found package', ['package' => $package['name']]);
                                        break 2;
                                    }
                                }
                            }
                        }
                    }
                } else {
                    // Single select mode - set as object
                    $this->editMadrasahNominalKonfirmasi = $nominalBiayaAwal;
                    
                    // Try to find which package this option belongs to
                    $optionId = $nominalBiayaAwal['id'] ?? null;
                    \Log::info('Single-select mode', ['option_id' => $optionId]);
                    
                    if ($optionId) {
                        $madrasahPackages = \App\Models\Setting::get('daftar_ulang_madrasah_packages', []);
                        foreach ($madrasahPackages as $package) {
                            if (isset($package['options'])) {
                                foreach ($package['options'] as $option) {
                                    if (($option['id'] ?? '') === $optionId) {
                                        // Found matching package - store as array for Livewire
                                        $this->editMadrasahPaket = $package;
                                        \Log::info('Found package', ['package' => $package['name']]);
                                        break 2;
                                    }
                                }
                            }
                        }
                    }
                }
            }

            // Load nominal_biaya (konfirmasi nominal yang dibayar)
            $nominalBiaya = $du->nominal_biaya ?? null;
            if ($nominalBiaya) {
                // Check if this is "Bayar Tidak Penuh" (opsi lainnya)
                if (isset($nominalBiaya['name']) && ($nominalBiaya['name'] === 'Bayar Tidak Penuh' || $nominalBiaya['name'] === 'Opsi Lainnya (Manual)')) {
                    $this->editMadrasahNominalKonfirmasiFinal = 'opsi_lainnya';
                    $this->editMadrasahNominalKonfirmasiOpsiLainnya = $nominalBiaya['amount'] ?? 0;
                } else {
                    // Bayar Penuh
                    $this->editMadrasahNominalKonfirmasiFinal = $nominalBiaya; // Store as array, not JSON string
                }
            }

            $this->editMadrasahOpsiPembayaran = $du->opsi_pembayaran ?? null;
            $this->editMadrasahMetodePembayaran = $du->metode_pembayaran ?? null;
            $this->editMadrasahNamaPengirim = $du->nama_pengirim ?? null;
            $this->editMadrasahWaktuPembayaran = $du->waktu_pembayaran_madrasah ? $du->waktu_pembayaran_madrasah->format('Y-m-d\TH:i') : null;
        }

        $this->editMadrasahBuktiPembayaran = null;
        $this->showEditStatusMadrasahModal = true;
    }

    /**
     * Close edit status Madrasah modal
     */
    public function closeEditStatusMadrasahModal()
    {
        $this->showEditStatusMadrasahModal = false;
        $this->editPendaftarId = null;
        $this->editMadrasahPaket = null;
        $this->editMadrasahOpsiPembayaran = null;
        $this->editMadrasahNominalKonfirmasi = null;
        $this->editMadrasahMetodePembayaran = null;
        $this->editMadrasahNamaPengirim = null;
        $this->editMadrasahBuktiPembayaran = null;
        $this->editMadrasahWaktuPembayaran = null;
    }

    /**
     * Save edit status Madrasah
     */
    public function saveEditStatusMadrasah()
    {
        try {
            if (!$this->editPendaftarId) {
                $this->dispatch('saved', message: 'Error: Pendaftar ID tidak ditemukan');
                return;
            }

            $pendaftar = Pendaftar::find($this->editPendaftarId);
            if (!$pendaftar) {
                $this->dispatch('saved', message: 'Error: Pendaftar tidak ditemukan');
                return;
            }

            $daftarUlang = DaftarUlangModel::firstOrNew(['pendaftar_id' => $this->editPendaftarId]);
            
            // Handle nominal_biaya_awal (dari opsi yang dipilih, bukan paket)
            $nominalBiayaAwal = null;
            
            // Cek apakah memilih opsi dari paket atau paket custom
            if (is_string($this->editMadrasahPaket) && $this->editMadrasahPaket === 'paket_lainnya') {
                // Paket custom - tidak ada opsi, langsung gunakan custom amount
                if ($this->editMadrasahPaketCustomName && $this->editMadrasahPaketCustomAmount) {
                    $nominalBiayaAwal = [
                        'name' => $this->editMadrasahPaketCustomName,
                        'amount' => (int) $this->editMadrasahPaketCustomAmount
                    ];
                }
            } else {
                // Paket dari dropdown - ambil opsi yang dipilih di editMadrasahNominalKonfirmasi
                if ($this->editMadrasahNominalKonfirmasi) {
                    // Check if multi-select (checkbox mode) - simpan sebagai array
                    if (is_array($this->editMadrasahNominalKonfirmasi) && isset($this->editMadrasahNominalKonfirmasi[0])) {
                        // Multi-select - simpan sebagai array untuk nominal_biaya_awal
                        $nominalBiayaAwal = $this->editMadrasahNominalKonfirmasi;
                    } else {
                        // Single select (radio mode) - simpan sebagai object
                        $nominalBiayaAwal = is_array($this->editMadrasahNominalKonfirmasi) 
                            ? $this->editMadrasahNominalKonfirmasi 
                            : json_decode($this->editMadrasahNominalKonfirmasi, true);
                    }
                }
            }
            
            // Handle nominal_biaya (yang dibayar - dari konfirmasi final)
            $nominalBiaya = null;
            if (is_string($this->editMadrasahNominalKonfirmasiFinal) && $this->editMadrasahNominalKonfirmasiFinal === 'opsi_lainnya') {
                // Opsi lainnya - manual input (Bayar Tidak Penuh)
                if ($this->editMadrasahNominalKonfirmasiOpsiLainnya) {
                    $nominalBiaya = [
                        'name' => 'Bayar Tidak Penuh',
                        'amount' => (int) $this->editMadrasahNominalKonfirmasiOpsiLainnya
                    ];
                }
            } else {
                // Bayar penuh dari final konfirmasi
                $nominalBiaya = is_array($this->editMadrasahNominalKonfirmasiFinal) 
                    ? $this->editMadrasahNominalKonfirmasiFinal 
                    : json_decode($this->editMadrasahNominalKonfirmasiFinal, true);
            }
            
            // Update data
            $daftarUlang->nominal_biaya = $nominalBiaya;
            $daftarUlang->nominal_biaya_awal = $nominalBiayaAwal;
            $daftarUlang->opsi_pembayaran = $this->editMadrasahOpsiPembayaran;
            $daftarUlang->metode_pembayaran = $this->editMadrasahMetodePembayaran;
            $daftarUlang->nama_pengirim = $this->editMadrasahNamaPengirim;
            
            // Debug logging
            \Log::info('Save Madrasah Edit', [
                'pendaftar_id' => $this->editPendaftarId,
                'nominal_biaya_awal' => $nominalBiayaAwal,
                'nominal_biaya' => $nominalBiaya,
                'editMadrasahPaket' => $this->editMadrasahPaket,
                'editMadrasahNominalKonfirmasi' => $this->editMadrasahNominalKonfirmasi,
                'editMadrasahNominalKonfirmasiFinal' => $this->editMadrasahNominalKonfirmasiFinal,
            ]);
            
            if ($this->editMadrasahWaktuPembayaran) {
                $daftarUlang->waktu_pembayaran_madrasah = $this->editMadrasahWaktuPembayaran;
            }

            // Handle file upload
            if ($this->editMadrasahBuktiPembayaran) {
                // Delete old file if exists
                if ($daftarUlang->bukti_pembayaran_madrasah) {
                    \Storage::disk('private')->delete($daftarUlang->bukti_pembayaran_madrasah);
                }

                // Generate nama file sistematis
                $extension = $this->editMadrasahBuktiPembayaran->getClientOriginalExtension();
                $customFileName = 'bukti_pembayaran_madrasah-' . $pendaftar->nomor_pendaftaran . '-' . \Str::slug($pendaftar->nama_lengkap) . '-' . now()->timestamp . '.' . $extension;

                // Upload ke private storage
                $path = $this->editMadrasahBuktiPembayaran->storeAs(
                    'dokumen/' . $pendaftar->id,
                    $customFileName,
                    'private'
                );
                $daftarUlang->bukti_pembayaran_madrasah = $path;
            }

            // Set status
            if (!$daftarUlang->exists) {
                $daftarUlang->status = 'menunggu';
            } elseif ($daftarUlang->status === 'belum_diisi') {
                $daftarUlang->status = 'menunggu';
            }

            $daftarUlang->save();

            // Touch parent to trigger Livewire refresh
            $pendaftar->touch();

            $this->closeEditStatusMadrasahModal();
            $this->dispatch('saved', message: 'Data daftar ulang madrasah berhasil diupdate');

        } catch (\Exception $e) {
            $this->dispatch('saved', message: 'Gagal menyimpan data: ' . $e->getMessage());
            \Log::error('Error saving edit status madrasah: ' . $e->getMessage());
        }
    }

    /**
     * Open edit status Seragam modal (Step 1 & 2 like pendaftar form)
     */
    public function openEditStatusSeragamModal($pendaftarId)
    {
        $this->editPendaftarId = $pendaftarId;

        // Load existing data
        $pendaftar = Pendaftar::find($pendaftarId);
        if ($pendaftar && $pendaftar->seragam) {
            $seragam = $pendaftar->seragam;
            
            // Store the seragam instance for computed property access
            $this->currentEditSeragam = $seragam;
            
            // Load paket_seragam (dropdown paket)
            $this->editSeragamPaket = $seragam->paket_seragam ?? null;
            
            // Load opsi_seragam (opsi yang dipilih dari paket)
            $opsiSeragam = $seragam->opsi_seragam ?? null;
            if ($opsiSeragam) {
                $this->editSeragamNominalKonfirmasi = is_string($opsiSeragam) 
                    ? json_decode($opsiSeragam, true) 
                    : $opsiSeragam;
            }
            
            // Load nominal_seragam (konfirmasi nominal yang dibayar)
            $nominalBiaya = $seragam->nominal_seragam ?? null;
            if ($nominalBiaya) {
                // Check if this is "Bayar Tidak Penuh" (opsi lainnya)
                if (isset($nominalBiaya['name']) && ($nominalBiaya['name'] === 'Bayar Tidak Penuh' || $nominalBiaya['name'] === 'Opsi Lainnya (Manual)')) {
                    $this->editSeragamNominalKonfirmasiFinal = 'opsi_lainnya';
                    $this->editSeragamNominalKonfirmasiOpsiLainnya = $nominalBiaya['amount'] ?? 0;
                } else {
                    // Bayar Penuh
                    $this->editSeragamNominalKonfirmasiFinal = json_encode($nominalBiaya);
                }
            }
            
            $this->editSeragamOpsiPembayaran = $seragam->opsi_pembayaran_seragam ?? null;
            $this->editSeragamMetodePembayaran = $seragam->bank_seragam ?? null;
            $this->editSeragamNamaPengirim = $seragam->nama_pengirim_seragam ?? null;
            $this->editSeragamWaktuPembayaran = $seragam->waktu_pembayaran_seragam ? $seragam->waktu_pembayaran_seragam->format('Y-m-d\TH:i') : null;
        }

        $this->editSeragamBuktiPembayaran = null;
        $this->showEditStatusSeragamModal = true;
    }

    /**
     * Close edit status Seragam modal
     */
    public function closeEditStatusSeragamModal()
    {
        $this->showEditStatusSeragamModal = false;
        $this->editPendaftarId = null;
        $this->editSeragamPaket = null;
        $this->editSeragamOpsiPembayaran = null;
        $this->editSeragamNominalKonfirmasi = null;
        $this->editSeragamMetodePembayaran = null;
        $this->editSeragamNamaPengirim = null;
        $this->editSeragamBuktiPembayaran = null;
        $this->editSeragamWaktuPembayaran = null;
    }

    /**
     * Save edit status Seragam
     */
    public function saveEditStatusSeragam()
    {
        try {
            if (!$this->editPendaftarId) {
                $this->dispatch('saved', message: 'Error: Pendaftar ID tidak ditemukan');
                return;
            }

            $pendaftar = Pendaftar::find($this->editPendaftarId);
            if (!$pendaftar) {
                $this->dispatch('saved', message: 'Error: Pendaftar tidak ditemukan');
                return;
            }

            $seragam = Seragam::firstOrNew(['pendaftar_id' => $this->editPendaftarId]);
            
            // Handle nominal_biaya (dari opsi yang dipilih, bukan paket)
            $nominalBiayaPaket = null;
            
            // Cek apakah memilih opsi dari paket atau paket custom
            if (is_string($this->editSeragamPaket) && $this->editSeragamPaket === 'paket_lainnya') {
                // Paket custom - tidak ada opsi, langsung gunakan custom amount
                if ($this->editSeragamPaketCustomName && $this->editSeragamPaketCustomAmount) {
                    $nominalBiayaPaket = [
                        'name' => $this->editSeragamPaketCustomName,
                        'amount' => (int) $this->editSeragamPaketCustomAmount
                    ];
                }
            } else {
                // Paket dari dropdown - ambil opsi yang dipilih di editSeragamNominalKonfirmasi
                if ($this->editSeragamNominalKonfirmasi) {
                    // Check if multi-select (checkbox mode) - simpan sebagai array
                    if (is_array($this->editSeragamNominalKonfirmasi) && isset($this->editSeragamNominalKonfirmasi[0])) {
                        // Multi-select - simpan sebagai array untuk nominal_seragam_awal
                        $nominalBiayaPaket = $this->editSeragamNominalKonfirmasi;
                    } else {
                        // Single select (radio mode) - simpan sebagai object
                        $nominalBiayaPaket = is_array($this->editSeragamNominalKonfirmasi) 
                            ? $this->editSeragamNominalKonfirmasi 
                            : json_decode($this->editSeragamNominalKonfirmasi, true);
                    }
                }
            }
            
            // Handle nominal_biaya (yang dibayar - dari konfirmasi final)
            $nominalBiaya = null;
            if (is_string($this->editSeragamNominalKonfirmasiFinal) && $this->editSeragamNominalKonfirmasiFinal === 'opsi_lainnya') {
                // Opsi lainnya - manual input (Bayar Tidak Penuh)
                if ($this->editSeragamNominalKonfirmasiOpsiLainnya) {
                    $nominalBiaya = [
                        'name' => 'Bayar Tidak Penuh',
                        'amount' => (int) $this->editSeragamNominalKonfirmasiOpsiLainnya
                    ];
                }
            } else {
                // Bayar penuh dari final konfirmasi
                $nominalBiaya = is_array($this->editSeragamNominalKonfirmasiFinal) 
                    ? $this->editSeragamNominalKonfirmasiFinal 
                    : json_decode($this->editSeragamNominalKonfirmasiFinal, true);
            }
            
            // Update data
            $seragam->paket_seragam = $this->editSeragamPaket;  // Package JSON
            $seragam->opsi_seragam = is_array($this->editSeragamNominalKonfirmasi) 
                ? json_encode($this->editSeragamNominalKonfirmasi) 
                : $this->editSeragamNominalKonfirmasi;  // Selected option JSON
            $seragam->nominal_seragam_awal = $nominalBiayaPaket;  // Original option
            $seragam->nominal_seragam = $nominalBiaya;  // Confirmation nominal
            $seragam->opsi_pembayaran_seragam = $this->editSeragamOpsiPembayaran;
            $seragam->bank_seragam = $this->editSeragamMetodePembayaran;
            $seragam->nama_pengirim_seragam = $this->editSeragamNamaPengirim;
            
            if ($this->editSeragamWaktuPembayaran) {
                $seragam->waktu_pembayaran_seragam = $this->editSeragamWaktuPembayaran;
            }

            // Handle file upload
            if ($this->editSeragamBuktiPembayaran) {
                // Delete old file if exists
                if ($seragam->bukti_pembayaran) {
                    \Storage::disk('private')->delete($seragam->bukti_pembayaran);
                }

                // Generate nama file sistematis
                $extension = $this->editSeragamBuktiPembayaran->getClientOriginalExtension();
                $customFileName = 'bukti_pembayaran_seragam-' . $pendaftar->nomor_pendaftaran . '-' . \Str::slug($pendaftar->nama_lengkap) . '-' . now()->timestamp . '.' . $extension;

                // Upload ke private storage
                $path = $this->editSeragamBuktiPembayaran->storeAs(
                    'dokumen/' . $pendaftar->id,
                    $customFileName,
                    'private'
                );
                $seragam->bukti_pembayaran = $path;
            }

            // Set status
            if (!$seragam->exists) {
                $seragam->status_seragam = 'menunggu';
            } elseif ($seragam->status_seragam === 'belum_diisi') {
                $seragam->status_seragam = 'menunggu';
            }

            $seragam->save();

            // Touch parent to trigger Livewire refresh
            $pendaftar->touch();

            $this->closeEditStatusSeragamModal();
            $this->dispatch('saved', message: 'Data daftar ulang seragam berhasil diupdate');

        } catch (\Exception $e) {
            $this->dispatch('saved', message: 'Gagal menyimpan data: ' . $e->getMessage());
            \Log::error('Error saving edit status seragam: ' . $e->getMessage());
        }
    }

    /**
     * Open edit status Ukuran modal
     */
    public function openEditStatusUkuranModal($pendaftarId)
    {
        $this->editPendaftarId = $pendaftarId;
        
        // Load existing data
        $pendaftar = Pendaftar::find($pendaftarId);
        if ($pendaftar && $pendaftar->seragam) {
            $seragam = $pendaftar->seragam;
            $this->editUkuranJas = $seragam->ukuran_jas ?? null;
            $this->editUkuranBajuOlahraga = $seragam->ukuran_baju_olahraga ?? null;
            $this->editUkuranCelanaOlahraga = $seragam->ukuran_celana_olahraga ?? null;
            $this->editUkuranBajuHarian = $seragam->ukuran_baju_harian ?? null;
            $this->editUkuranCelanaRokHarian = $seragam->ukuran_celana_rok_harian ?? null;
            $this->editUkuranKopyah = $seragam->ukuran_kopyah ?? null;
        }
        
        $this->showEditStatusUkuranModal = true;
    }

    /**
     * Close edit status Ukuran modal
     */
    public function closeEditStatusUkuranModal()
    {
        $this->showEditStatusUkuranModal = false;
        $this->editPendaftarId = null;
        $this->editUkuranJas = null;
        $this->editUkuranBajuOlahraga = null;
        $this->editUkuranCelanaOlahraga = null;
        $this->editUkuranBajuHarian = null;
        $this->editUkuranCelanaRokHarian = null;
        $this->editUkuranKopyah = null;
    }

    /**
     * Save edit status Ukuran
     */
    public function saveEditStatusUkuran()
    {
        try {
            if (!$this->editPendaftarId) {
                $this->dispatch('saved', message: 'Error: Pendaftar ID tidak ditemukan');
                return;
            }

            $pendaftar = Pendaftar::find($this->editPendaftarId);
            if (!$pendaftar) {
                $this->dispatch('saved', message: 'Error: Pendaftar tidak ditemukan');
                return;
            }

            $seragam = Seragam::firstOrNew(['pendaftar_id' => $this->editPendaftarId]);
            
            // Update ukuran data
            $seragam->ukuran_jas = $this->editUkuranJas;
            $seragam->ukuran_baju_olahraga = $this->editUkuranBajuOlahraga;
            $seragam->ukuran_celana_olahraga = $this->editUkuranCelanaOlahraga;
            $seragam->ukuran_baju_harian = $this->editUkuranBajuHarian;
            $seragam->ukuran_celana_rok_harian = $this->editUkuranCelanaRokHarian;
            $seragam->ukuran_kopyah = $this->editUkuranKopyah;

            // Set status
            if (!$seragam->exists) {
                $seragam->status_ukuran = 'menunggu';
            } elseif ($seragam->status_ukuran === 'belum_diisi') {
                $seragam->status_ukuran = 'menunggu';
            }

            $seragam->save();

            // Touch parent to trigger Livewire refresh
            $pendaftar->touch();

            $this->closeEditStatusUkuranModal();
            $this->dispatch('saved', message: 'Data ukuran seragam berhasil diupdate');

        } catch (\Exception $e) {
            $this->dispatch('saved', message: 'Gagal menyimpan data: ' . $e->getMessage());
            \Log::error('Error saving edit status ukuran: ' . $e->getMessage());
        }
    }
}
