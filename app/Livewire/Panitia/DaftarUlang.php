<?php

namespace App\Livewire\Panitia;

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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DaftarUlang extends Component
{
    use WithPagination, WithSorting, WithFileUploads;

    public $search = '';
    public $filterStatus = '';
    public $filterJalur = ''; // Filter jalur
    public $filterRekap = ''; // kategori rekap daftar ulang

    public $verificationStatus = '';
    public $verificationNote = '';

    public $currentPendaftarId = null;
    public $currentDocumentType = null;
    public $currentPaymentType = null;

    // Message modal
    public $showMessageModal = false;
    public $messagingId = null;
    public $messagingPendaftar = null;
    public $messageContent = '';

    // Bulk message modal
    public $showBulkMessageModal = false;
    public $bulkMessageType = 'email';
    public $bulkMessageContent = '';
    public $bulkMessageSubject = '';
    public $bulkRecipientFilter = 'all';

    // Catatan modal
    public $showCatatanModal = false;
    public $catatanType = '';
    public $catatanPendaftarId = null;
    public $catatanText = '';

    // Catatan internal modal (for admin/panitia only - NOT shown to pendaftar)
    public $showCatatanInternalModal = false;
    public $catatanInternalType = '';
    public $catatanInternalPendaftarId = null;
    public $catatanInternalText = '';

    // Tab navigation
    public $activeTab = 'madrasah';

    // Query string bindings for URL
    protected $queryString = [
        'activeTab' => ['except' => 'madrasah'],
        'search' => ['except' => ''],
        'filterStatus' => ['except' => ''],
        'filterJalur' => ['except' => ''],
    ];

    // Settings variables (Panitia Daftar Ulang fokus ke Madrasah saja)
    public $madrasahContent = '';
    public $madrasahInfo = '';  // Informasi untuk pendaftar - Madrasah
    public $paymentInfoText = ''; // Informasi Pembayaran untuk semua halaman daftar ulang
    public $showPernyataan = true;
    public $showPembayaranMadrasah = true;

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

    public function mount()
    {
        $user = Auth::user();
        if (!$user || !in_array($user->role, ['panitia_pendaftaran', 'panitia_daftarulang', 'panitia_tes_psikologi', 'panitia_seragam'])) {
            return redirect()->to('/login/admin');
        }

        // Load MADRASAH settings only (Panitia Daftar Ulang fokus ke Madrasah)
        $this->loadSettings();
    }

    protected function loadSettings()
    {
        // Text & Boolean Settings
        $this->madrasahContent = \App\Models\Setting::get('daftar_ulang_madrasah_content', '');
        $this->madrasahInfo = \App\Models\Setting::get('daftar_ulang_madrasah_info', '');
        $this->paymentInfoText = \App\Models\Setting::get('daftar_ulang_payment_info_text', 'Pembayaran disesuaikan dengan kemampuan orang tua/wali. Apabila ingin berkomunikasi perihal kompensasi waktu/biaya/lainnya lebih lanjut, dapat dibicarakan dengan komite madrasah untuk menemukan solusi secara kesepakatan bersama. Waktu sesuai jadwal yang disampaikan oleh pihak komite madrasah.');
        $this->showPernyataan = \App\Models\Setting::get('daftar_ulang_show_pernyataan', true);
        $this->showPembayaranMadrasah = \App\Models\Setting::get('daftar_ulang_show_pembayaran_madrasah', true);

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
    }

    // Message Methods
    public function sendEmail($id)
    {
        $pendaftar = Pendaftar::findOrFail($id);

        // TODO: Implement email sending logic here
        // Example: Mail::to($pendaftar->user->email)->send(new DaftarUlangEmail($pendaftar));

        // Save to message history
        \App\Models\MessageHistory::create([
            'pendaftar_id' => $pendaftar->id,
            'sender_id' => auth()->id(),
            'message_type' => 'email',
            'context' => 'daftar_ulang_madrasah',
            'message_content' => 'Email dikirim untuk daftar ulang',
            'sent_at' => now(),
        ]);

        $this->dispatch('saved', message: 'Email berhasil dikirim ke ' . $pendaftar->nama_lengkap);
    }

    public function openMessageModal($id)
    {
        $this->messagingId = $id;
        $this->messagingPendaftar = Pendaftar::findOrFail($id);
        $this->messageContent = '';
        $this->showMessageModal = true;
    }

    public function sendMessage()
    {
        $this->validate([
            'messageContent' => 'required|string|max:1000',
        ], [
            'messageContent.required' => 'Pesan harus diisi',
            'messageContent.max' => 'Pesan maksimal 1000 karakter',
        ]);

        $pendaftar = Pendaftar::findOrFail($this->messagingId);

        $penerima = $pendaftar->email ?? 'notification';

        // Create log record with pending status
        $riwayatPesan = RiwayatPesan::create([
            'pendaftar_id' => $pendaftar->id,
            'user_id' => auth()->id(),
            'jenis_pesan' => 'email', // Using email as notification type
            'judul' => 'Pesan Daftar Ulang',
            'isi_pesan' => $this->messageContent,
            'penerima' => $penerima,
            'status' => 'pending',
        ]);

        try {
            // Send notification to pendaftar
            if ($pendaftar && $pendaftar->user) {
                $pendaftar->user->notify(
                    new \App\Notifications\CustomMessageNotification(
                        'Pesan Daftar Ulang',
                        $this->messageContent,
                        'daftar_ulang'
                    )
                );
            }

            // Update log to success
            $riwayatPesan->update([
                'status' => 'terkirim',
                'sent_at' => now(),
            ]);

            $this->dispatch('saved', message: 'Pesan berhasil dikirim ke ' . $pendaftar->nama_lengkap);
            $this->closeMessageModal();
        } catch (\Exception $e) {
            // Update log to failed with error message
            $riwayatPesan->update([
                'status' => 'gagal',
                'error_message' => $e->getMessage(),
            ]);

            $this->dispatch('saved', message: 'Gagal mengirim pesan: ' . $e->getMessage());
        }
    }

    public function closeMessageModal()
    {
        $this->showMessageModal = false;
        $this->messagingId = null;
        $this->messagingPendaftar = null;
        $this->messageContent = '';
        $this->resetValidation();
    }

    // Template Methods (Madrasah only)
    public function loadTemplateEmail()
    {
        $template = \App\Models\Setting::get('daftar_ulang_madrasah_template_email', '');
        $this->templateEmailMadrasah = $template;
    }

    public function loadTemplateWA()
    {
        $template = \App\Models\Setting::get('daftar_ulang_madrasah_template_wa', '');
        $this->templateWaMadrasah = $template;
    }

    public function resetTemplate($type)
    {
        // type: 'email', 'wa'
        if ($type === 'email') {
            $this->templateEmailMadrasah = '';
        } elseif ($type === 'wa') {
            $this->templateWaMadrasah = '';
        }

        $this->dispatch('saved', message: 'Template berhasil direset');
    }

    public function updatedFilterRekap()
    {
        $this->resetPage();
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

        // Filter jalur
        if ($this->filterJalur) {
            $query->where('jalur_pendaftaran_id', $this->filterJalur);
        }

        // Filter kategori rekap (belum konfirmasi, tidak melanjutkan, selesai, dst.)
        \App\Support\RekapDaftarUlang::filterKategori($query, $this->filterRekap, 'madrasah');

        $pendaftar = $this->applySorting($query)->paginate(10);

        // Rekap konsisten: setiap pendaftar lulus masuk tepat satu kategori (lihat App\Support\RekapDaftarUlang)
        $stats = \App\Support\RekapDaftarUlang::statsTab('madrasah', ['jalur' => $this->filterJalur]);

        // Load jalur pendaftaran untuk filter
        $jalurPendaftaran = \App\Models\JalurPendaftaran::where('aktif', true)
            ->orderBy('urutan')
            ->get();

        return view('livewire.panitia.daftar-ulang', [
            'pendaftar' => $pendaftar,
            'stats' => $stats,
            'jalurPendaftaran' => $jalurPendaftaran
        ])->layout('components.layouts.dashboard', [
            'role' => Auth::user()->role,
            'pageTitle' => 'Manajemen Daftar Ulang',
            'pageDescription' => 'Kelola proses daftar ulang pendaftar'
        ]);
    }

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
    }

    // ========== MADRASAH SETTINGS METHODS ==========
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

    public function prepareVerification($pendaftarId, $documentType = null, $paymentType = null)
    {
        $this->currentPendaftarId = $pendaftarId;
        $this->currentDocumentType = $documentType;
        $this->currentPaymentType = $paymentType;
        $this->verificationStatus = $documentType ? 'diterima' : 'lunas';
        $this->verificationNote = '';
    }

    public function confirmVerification()
    {
        if (!$this->currentPendaftarId) {
            return;
        }

        if ($this->currentDocumentType) {
            $this->verifyDocument($this->currentPendaftarId, $this->currentDocumentType, $this->verificationStatus, $this->verificationNote);
        } elseif ($this->currentPaymentType) {
            if ($this->currentPaymentType === 'daftar_ulang') {
                $this->verifyDaftarUlang($this->currentPendaftarId, $this->verificationStatus, $this->verificationNote);
            } elseif ($this->currentPaymentType === 'seragam') {
                $this->verifySeragam($this->currentPendaftarId, $this->verificationStatus, $this->verificationNote);
            }
        }

        $this->reset(['currentPendaftarId', 'currentDocumentType', 'currentPaymentType', 'verificationStatus', 'verificationNote']);
    }

    // Settings Management Methods - MADRASAH ONLY
    public function addNominalMadrasah()
    {
        if (!empty($this->nominalBayarMadrasah)) {
            $this->nominalsMadrasah[] = $this->nominalBayarMadrasah;
            $this->nominalBayarMadrasah = '';
        }
    }

    public function removeNominalMadrasah($index)
    {
        unset($this->nominalsMadrasah[$index]);
        $this->nominalsMadrasah = array_values($this->nominalsMadrasah);
    }

    public function addBankMadrasah()
    {
        if (!empty($this->bankMadrasah)) {
            $this->banksMadrasah[] = $this->bankMadrasah;
            $this->bankMadrasah = '';
        }
    }

    public function removeBankMadrasah($index)
    {
        unset($this->banksMadrasah[$index]);
        $this->banksMadrasah = array_values($this->banksMadrasah);
    }

    public function saveSettings()
    {
        $this->validate([
            'madrasahContent' => 'nullable|string',
        ]);

        // Save MADRASAH settings only
        \App\Models\Setting::set('daftar_ulang_madrasah_content', $this->madrasahContent, 'text', 'Konten Daftar Ulang Madrasah');
        \App\Models\Setting::set('daftar_ulang_madrasah_nominals', json_encode($this->nominalsMadrasah), 'json', 'Nominal Pembayaran Madrasah');
        \App\Models\Setting::set('daftar_ulang_madrasah_banks', json_encode($this->banksMadrasah), 'json', 'Bank Madrasah');
        \App\Models\Setting::set('daftar_ulang_madrasah_template_email', $this->templateEmailMadrasah, 'text', 'Template Email Madrasah');
        \App\Models\Setting::set('daftar_ulang_madrasah_template_wa', $this->templateWaMadrasah, 'text', 'Template WhatsApp Madrasah');
        \App\Models\Setting::set('daftar_ulang_payment_info_text', $this->paymentInfoText, 'text', 'Teks Informasi Pembayaran');
        \App\Models\Setting::set('daftar_ulang_show_pernyataan', $this->showPernyataan, 'boolean', 'Tampilkan Form Pernyataan');
        \App\Models\Setting::set('daftar_ulang_show_pembayaran_madrasah', $this->showPembayaranMadrasah, 'boolean', 'Tampilkan Form Pembayaran Madrasah');

        // Handle image upload for Madrasah
        if ($this->imageMadrasah) {
            $path = $this->imageMadrasah->store('daftar-ulang-images', 'public');
            \App\Models\Setting::set('daftar_ulang_madrasah_image', $path, 'text', 'Gambar Madrasah');
            $this->currentImageMadrasah = $path;
            $this->imageMadrasah = null;
        }

        // Handle template file upload for Madrasah
        if ($this->templateSuratMadrasah) {
            $path = $this->templateSuratMadrasah->store('templates', 'public');
            \App\Models\Setting::set('daftar_ulang_madrasah_template_surat', $path, 'text', 'Template Surat Madrasah');
            $this->templateSuratMadrasah = null;
        }

        $this->dispatch('saved', message: 'Pengaturan daftar ulang madrasah berhasil disimpan!');
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

        // Send notification to pendaftar
        $pendaftar = Pendaftar::find($pendaftarId);
        if ($pendaftar && $pendaftar->user) {
            $pendaftar->user->notify(
                new \App\Notifications\StatusChangeNotification(
                    'daftar_ulang_madrasah',
                    $status,
                    $daftarUlang->catatan ?? null
                )
            );
        }

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

        // Send notification to pendaftar
        $pendaftar = Pendaftar::find($pendaftarId);
        if ($pendaftar && $pendaftar->user) {
            $pendaftar->user->notify(
                new \App\Notifications\StatusChangeNotification(
                    'daftar_ulang_seragam',
                    $status,
                    $seragam->catatan_seragam ?? null
                )
            );
        }

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

        // Send notification to pendaftar
        $pendaftar = Pendaftar::find($pendaftarId);
        if ($pendaftar && $pendaftar->user) {
            $pendaftar->user->notify(
                new \App\Notifications\StatusChangeNotification(
                    'daftar_ulang_ukuran',
                    $status,
                    $seragam->catatan_ukuran ?? null
                )
            );
        }

        $statusLabels = [
            'disetujui' => 'disetujui',
            'ditolak' => 'ditolak',
            'perbaiki' => 'diminta untuk diperbaiki',
            'menunggu' => 'dikembalikan ke menunggu'
        ];
        $statusLabel = $statusLabels[$status] ?? $status;
        $this->dispatch('saved', message: "Status Ukuran berhasil diubah menjadi {$statusLabel}.");
    }

    /**
     * Save catatan for DU Madrasah only
     */
    public function saveCatatan($pendaftarId, $type, $catatan)
    {
        try {
            $pendaftar = Pendaftar::findOrFail($pendaftarId);

            if ($type === 'madrasah') {
                // Save to daftar_ulang table
                $daftarUlang = \App\Models\DaftarUlang::firstOrCreate(
                    ['pendaftar_id' => $pendaftarId],
                    [
                        'status' => 'menunggu',
                        'opsi_pembayaran' => null,
                        'metode_pembayaran' => null,
                    ]
                );

                $daftarUlang->update([
                    'catatan' => $catatan ?: null,
                ]);

                $this->dispatch('saved', message: 'Catatan DU Madrasah berhasil disimpan');
            }

        } catch (\Exception $e) {
            $this->dispatch('saved', message: 'Gagal menyimpan catatan: ' . $e->getMessage());
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
     * Save catatan from modal (wrapper that uses properties)
     */
    public function saveCatatanFromModal()
    {
        if (!$this->catatanPendaftarId || !$this->catatanType) {
            $this->dispatch('saved', message: 'Data tidak valid');
            return;
        }

        // Call the existing saveCatatan method with parameters
        $this->saveCatatan($this->catatanPendaftarId, $this->catatanType, $this->catatanText);

        // Close modal
        $this->closeCatatanModal();
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
            if (!$this->catatanInternalPendaftarId || !$this->catatanInternalType) {
                $this->dispatch('saved', message: 'Data tidak valid');
                return;
            }

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

                $this->dispatch('saved', message: 'Catatan Internal DU Madrasah berhasil disimpan');

            } elseif ($this->catatanInternalType === 'seragam') {
                // Find or create seragam
                $seragam = Seragam::firstOrNew(['pendaftar_id' => $this->catatanInternalPendaftarId]);

                // Update catatan_internal_seragam
                $seragam->catatan_internal_seragam = $this->catatanInternalText;
                $seragam->save();

                $this->dispatch('saved', message: 'Catatan Internal DU Seragam berhasil disimpan');

            } elseif ($this->catatanInternalType === 'ukuran') {
                // Find or create seragam
                $seragam = Seragam::firstOrNew(['pendaftar_id' => $this->catatanInternalPendaftarId]);

                // Update catatan_internal_ukuran
                $seragam->catatan_internal_ukuran = $this->catatanInternalText;
                $seragam->save();

                $this->dispatch('saved', message: 'Catatan Internal Ukuran Seragam berhasil disimpan');
            }

            // Close modal
            $this->closeCatatanInternalModal();

        } catch (\Exception $e) {
            $this->dispatch('saved', message: 'Gagal menyimpan catatan internal: ' . $e->getMessage());
            \Log::error('Error saving catatan internal (panitia): ' . $e->getMessage());
        }
    }

    /**
     * Reset status persetujuan daftar ulang
     */
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

    /**
     * Change status persetujuan daftar ulang
     */
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

    /**
     * Toggle Opsi Lainnya Dropdown Individual per Pendaftar
     * Allows panitia to enable "Opsi Lainnya" in dropdown for specific pendaftar
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
     * Toggle Opsi Lainnya Konfirmasi Individual per Pendaftar
     * Allows panitia to enable "Opsi Lainnya" in konfirmasi nominal for specific pendaftar
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
}
