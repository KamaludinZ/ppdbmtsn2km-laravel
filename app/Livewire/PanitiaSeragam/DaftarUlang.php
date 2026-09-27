<?php

namespace App\Livewire\PanitiaSeragam;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Url;
use App\Models\Pendaftar;
use App\Models\Seragam;
use App\Models\RiwayatPesan;
use App\Traits\WithSorting;
use Illuminate\Support\Facades\Auth;

class DaftarUlang extends Component
{
    use WithPagination, WithSorting, WithFileUploads;

    public $activeTab = 'seragam';
    public $search = '';
    public $filterStatusSeragam = '';
    public $filterStatusUkuran = '';
    public $filterJenisKelamin = '';
    public $filterJalur = '';

    // Query string bindings for URL
    protected $queryString = [
        'activeTab' => ['except' => 'seragam'],
        'search' => ['except' => ''],
        'filterStatusSeragam' => ['except' => ''],
        'filterStatusUkuran' => ['except' => ''],
        'filterJenisKelamin' => ['except' => ''],
        'filterJalur' => ['except' => ''],
    ];

    // Properties for send message modal
    public $isSendMessageModalOpen = false;
    public $messageType = 'email'; // 'email' or 'whatsapp'
    public $selectedPendaftarId = null;
    public $selectedMessageTemplate = '';
    public $messageSubject = '';
    public $messageBody = '';
    public $messageNote = '';

    // Modal catatan variables
    public $showCatatanModal = false;
    public $catatanType = '';
    public $catatanPendaftarId = null;
    public $catatanText = '';

    // Modal catatan internal variables (for panitia only - NOT shown to pendaftar)
    public $showCatatanInternalModal = false;
    public $catatanInternalType = '';
    public $catatanInternalPendaftarId = null;
    public $catatanInternalText = '';

    // ========== SETTINGS PROPERTIES ==========
    // Seragam Settings
    public $seragamInfo = '';
    public $seragamPackages = [];
    public $seragamPaymentOptions = ['lunas' => false, 'angsuran' => false, 'potongan' => false];
    public $seragamBanks = [];
    public $seragamPaymentStart = null;
    public $seragamPaymentEnd = null;
    public $seragamRequirePaymentProof = false;
    public $seragamEnableOpsiLainnya = false;
    public $seragamPackagesEnableOpsiLainnya = false;

    // Ukuran Settings
    public $ukuranInfo = '';
    public $ukuranByGender = false;
    public $ukuranJasImage = null;
    public $ukuranJasOptions = [];
    public $ukuranJasEnableOpsiLainnya = false;
    public $ukuranBajuHarianImage = null;
    public $ukuranBajuHarianOptions = [];
    public $ukuranBajuHarianEnableOpsiLainnya = false;
    public $ukuranCelanaRokHarianImage = null;
    public $ukuranCelanaRokHarianOptions = [];
    public $ukuranCelanaRokHarianEnableOpsiLainnya = false;
    public $ukuranBajuOlahragaImage = null;
    public $ukuranBajuOlahragaOptions = [];
    public $ukuranBajuOlahragaEnableOpsiLainnya = false;
    public $ukuranCelanaOlahragaImage = null;
    public $ukuranCelanaOlahragaOptions = [];
    public $ukuranCelanaOlahragaEnableOpsiLainnya = false;
    public $ukuranKopyahImage = null;
    public $ukuranKopyahOptions = [];
    public $ukuranKopyahEnableOpsiLainnya = false;

    // Existing uploaded images (for display)
    public $existingUkuranJasImage = null;
    public $existingUkuranBajuOlahragaImage = null;
    public $existingUkuranCelanaOlahragaImage = null;
    public $existingUkuranBajuHarianImage = null;
    public $existingUkuranCelanaRokHarianImage = null;
    public $existingUkuranKopyahImage = null;

    // Legacy
    public $ukuranAtasanOptions = [];
    public $ukuranBawahanOptions = [];

    public function mount()
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'panitia_seragam') {
            return redirect()->to('/login/admin');
        }

        // Load settings for tab pengaturan
        $this->loadSettings();
    }

    private function loadSettings()
    {
        // Load Seragam Settings
        $this->seragamInfo = \App\Models\Setting::get('daftar_ulang_seragam_info', '');
        $this->seragamPackages = \App\Models\Setting::get('daftar_ulang_seragam_packages', []);
        $this->seragamPaymentOptions = \App\Models\Setting::get('daftar_ulang_seragam_payment_options', ['lunas' => false, 'angsuran' => false, 'potongan' => false]);
        $this->seragamBanks = \App\Models\Setting::get('daftar_ulang_seragam_banks', []);
        $this->seragamPaymentStart = \App\Models\Setting::get('daftar_ulang_seragam_payment_start');
        $this->seragamPaymentEnd = \App\Models\Setting::get('daftar_ulang_seragam_payment_end');
        $this->seragamRequirePaymentProof = \App\Models\Setting::get('daftar_ulang_seragam_require_payment_proof', false);
        $this->seragamEnableOpsiLainnya = \App\Models\Setting::get('daftar_ulang_seragam_enable_opsi_lainnya', false);
        $this->seragamPackagesEnableOpsiLainnya = \App\Models\Setting::get('daftar_ulang_seragam_packages_enable_opsi_lainnya', false);

        // Load Ukuran Settings
        $this->ukuranInfo = \App\Models\Setting::get('daftar_ulang_ukuran_info', '');
        $this->ukuranByGender = \App\Models\Setting::get('daftar_ulang_ukuran_by_gender', false);
        $this->ukuranJasOptions = \App\Models\Setting::get('daftar_ulang_ukuran_jas_options', []);
        $this->ukuranJasEnableOpsiLainnya = \App\Models\Setting::get('daftar_ulang_ukuran_jas_enable_opsi_lainnya', false);
        $this->ukuranBajuHarianOptions = \App\Models\Setting::get('daftar_ulang_ukuran_baju_harian_options', []);
        $this->ukuranBajuHarianEnableOpsiLainnya = \App\Models\Setting::get('daftar_ulang_ukuran_baju_harian_enable_opsi_lainnya', false);
        $this->ukuranCelanaRokHarianOptions = \App\Models\Setting::get('daftar_ulang_ukuran_celana_rok_harian_options', []);
        $this->ukuranCelanaRokHarianEnableOpsiLainnya = \App\Models\Setting::get('daftar_ulang_ukuran_celana_rok_harian_enable_opsi_lainnya', false);
        $this->ukuranBajuOlahragaOptions = \App\Models\Setting::get('daftar_ulang_ukuran_baju_olahraga_options', []);
        $this->ukuranBajuOlahragaEnableOpsiLainnya = \App\Models\Setting::get('daftar_ulang_ukuran_baju_olahraga_enable_opsi_lainnya', false);
        $this->ukuranCelanaOlahragaOptions = \App\Models\Setting::get('daftar_ulang_ukuran_celana_olahraga_options', []);
        $this->ukuranCelanaOlahragaEnableOpsiLainnya = \App\Models\Setting::get('daftar_ulang_ukuran_celana_olahraga_enable_opsi_lainnya', false);
        $this->ukuranKopyahOptions = \App\Models\Setting::get('daftar_ulang_ukuran_kopyah_options', []);
        $this->ukuranKopyahEnableOpsiLainnya = \App\Models\Setting::get('daftar_ulang_ukuran_kopyah_enable_opsi_lainnya', false);

        // Load existing uploaded images
        $this->existingUkuranJasImage = \App\Models\Setting::get('daftar_ulang_ukuran_jas_image');
        $this->existingUkuranBajuOlahragaImage = \App\Models\Setting::get('daftar_ulang_ukuran_baju_olahraga_image');
        $this->existingUkuranCelanaOlahragaImage = \App\Models\Setting::get('daftar_ulang_ukuran_celana_olahraga_image');
        $this->existingUkuranBajuHarianImage = \App\Models\Setting::get('daftar_ulang_ukuran_baju_harian_image');
        $this->existingUkuranCelanaRokHarianImage = \App\Models\Setting::get('daftar_ulang_ukuran_celana_rok_harian_image');
        $this->existingUkuranKopyahImage = \App\Models\Setting::get('daftar_ulang_ukuran_kopyah_image');

        // Legacy
        $this->ukuranAtasanOptions = \App\Models\Setting::get('daftar_ulang_ukuran_atasan_options', []);
        $this->ukuranBawahanOptions = \App\Models\Setting::get('daftar_ulang_ukuran_bawahan_options', []);
    }

    public function switchTab($tab)
    {
        if (in_array($tab, ['seragam', 'ukuran', 'settings'])) {
            $this->activeTab = $tab;
            $this->resetPage();
        }
    }

    private function getSeragamStats()
    {
        // Calculate stats based on active tab (same pattern as Admin)
        // Get ALL pendaftar for total count (no status filter for stats)
        $allPendaftar = Pendaftar::with(['seragam'])->get();

        // Get only lulus pendaftar for lulus count
        $lulusPendaftar = $allPendaftar->filter(function($p) {
            return in_array($p->status_pendaftaran, ['lulus', 'diterima']);
        });

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
                return $p->seragam && $p->seragam->status_seragam === 'perlu_perbaikan';
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

        return $stats;
    }

    private function getUkuranStats()
    {
        // Calculate stats based on active tab (same pattern as Admin)
        // Get ALL pendaftar for total count (no status filter for stats)
        $allPendaftar = Pendaftar::with(['seragam'])->get();

        // Get only lulus pendaftar for lulus count
        $lulusPendaftar = $allPendaftar->filter(function($p) {
            return in_array($p->status_pendaftaran, ['lulus', 'diterima']);
        });

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
                return $p->seragam && $p->seragam->status_ukuran === 'perlu_perbaikan';
            })->count(),
        ];

        return $stats;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function updatingFilterJenisKelamin()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Pendaftar::with(['jalurPendaftaran', 'seragam'])
            ->whereIn('status_pendaftaran', ['data_disetujui', 'lulus', 'diterima']);

        // Search filter
        if ($this->search) {
            $query->where(function($q) {
                $q->where('nama_lengkap', 'ILIKE', '%' . $this->search . '%')
                  ->orWhere('nisn', 'ILIKE', '%' . $this->search . '%')
                  ->orWhere('nomor_pendaftaran', 'ILIKE', '%' . $this->search . '%');
            });
        }

        // Gender filter
        if ($this->filterJenisKelamin) {
            $query->where('jenis_kelamin', $this->filterJenisKelamin);
        }

        // Jalur filter
        if ($this->filterJalur) {
            $query->where('jalur_pendaftaran_id', $this->filterJalur);
        }

        // Status DU Seragam filter - lihat kolom status_seragam
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

        // Status Ukuran filter - lihat kolom status_ukuran
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

        $pendaftar = $query->orderBy('updated_at', 'desc')->paginate(20);

        // Calculate stats based on active tab
        $stats = [];
        if ($this->activeTab === 'seragam') {
            $stats = $this->getSeragamStats();
        } elseif ($this->activeTab === 'ukuran') {
            $stats = $this->getUkuranStats();
        }

        // Load jalur pendaftaran untuk filter
        $jalurPendaftaran = \App\Models\JalurPendaftaran::where('aktif', true)
            ->orderBy('urutan')
            ->get();

        return view('livewire.panitia-seragam.daftar-ulang', [
            'pendaftar' => $pendaftar,
            'stats' => $stats,
            'jalurPendaftaran' => $jalurPendaftaran
        ])->layout('components.layouts.dashboard', [
            'role' => 'panitia_seragam',
            'pageTitle' => 'Daftar Ulang Seragam',
            'pageDescription' => 'Kelola data seragam dan ukuran siswa baru'
        ]);
    }

    public function changeStatusSeragam($pendaftarId, $status)
    {
        $seragam = Seragam::firstOrCreate(
            ['pendaftar_id' => $pendaftarId],
            ['status_seragam' => $status]
        );

        $seragam->update(['status_seragam' => $status]);

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

        $statusLabels = [
            'disetujui' => 'disetujui',
            'ditolak' => 'ditolak',
            'perbaiki' => 'diminta untuk diperbaiki',
            'menunggu' => 'dikembalikan ke menunggu'
        ];
        $statusLabel = $statusLabels[$status] ?? $status;
        $this->dispatch('saved', message: "Status Ukuran berhasil diubah menjadi {$statusLabel}.");
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

    public function updatedSelectedMessageTemplate()
    {
        $this->loadMessageTemplate();
    }

    public function updatedMessageType()
    {
        $this->selectedMessageTemplate = '';
        $this->messageSubject = '';
        $this->messageBody = '';
    }

    /**
     * Get available message templates based on active tab
     */
    public function getAvailableTemplates()
    {
        if ($this->activeTab === 'seragam') {
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
        } elseif ($this->activeTab === 'ukuran') {
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
        }

        return [];
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

        // Replace placeholders
        $replacements = [
            '{nama}' => $pendaftar->nama_lengkap ?? '',
            '{nomor_pendaftaran}' => $pendaftar->nomor_pendaftaran ?? '',
            '{nisn}' => $pendaftar->nisn ?? '',
            '{jenis_kelamin}' => $pendaftar->jenis_kelamin === 'L' ? 'Laki-laki' : ($pendaftar->jenis_kelamin === 'P' ? 'Perempuan' : ''),
            '{jalur}' => $pendaftar->jalurPendaftaran->nama ?? '',
            '{email}' => $pendaftar->email ?? '',
            '{no_hp}' => $pendaftar->no_hp ?? '',
            '{catatan}' => $this->messageNote ?? '',
            '{url_dashboard}' => url('/dashboard'),
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

        // Create log record
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
                \Illuminate\Support\Facades\Mail::raw($this->messageBody, function ($message) use ($pendaftar) {
                    $message->to($pendaftar->email)->subject($this->messageSubject);
                });

                $riwayatPesan->update([
                    'status' => 'terkirim',
                    'sent_at' => now(),
                ]);

                $this->dispatch('saved', message: 'Email berhasil dikirim ke ' . $pendaftar->nama_lengkap);
            } else {
                $phoneNumber = $pendaftar->no_hp;
                if (!$phoneNumber) {
                    $riwayatPesan->update([
                        'status' => 'gagal',
                        'error_message' => 'Nomor WhatsApp tidak ditemukan',
                    ]);
                    $this->dispatch('saved', message: 'Nomor WhatsApp tidak ditemukan.');
                    return;
                }

                $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
                if (substr($phoneNumber, 0, 1) === '0') {
                    $phoneNumber = '62' . substr($phoneNumber, 1);
                }

                // TODO: Implementasikan WhatsApp API

                $riwayatPesan->update([
                    'status' => 'terkirim',
                    'sent_at' => now(),
                ]);

                $this->dispatch('saved', message: 'WhatsApp berhasil dikirim ke ' . $pendaftar->nama_lengkap);
            }

            $this->closeSendMessageModal();

        } catch (\Exception $e) {
            $riwayatPesan->update([
                'status' => 'gagal',
                'error_message' => $e->getMessage(),
            ]);

            $this->dispatch('saved', message: 'Gagal mengirim pesan: ' . $e->getMessage());
        }
    }

    // ========== CATATAN METHODS ==========
    public function openCatatanModal($type, $pendaftarId)
    {
        $this->catatanType = $type;
        $this->catatanPendaftarId = $pendaftarId;
        
        // Fetch current catatan from database based on type
        $currentCatatan = '';
        if ($type === 'seragam') {
            $seragam = Seragam::where('pendaftar_id', $pendaftarId)->first();
            $currentCatatan = $seragam?->catatan_seragam ?? '';
        } elseif ($type === 'ukuran') {
            $seragam = Seragam::where('pendaftar_id', $pendaftarId)->first();
            $currentCatatan = $seragam?->catatan_ukuran ?? '';
        }
        
        $this->catatanText = $currentCatatan;
        $this->showCatatanModal = true;
    }

    public function closeCatatanModal()
    {
        $this->showCatatanModal = false;
        $this->catatanType = '';
        $this->catatanPendaftarId = null;
        $this->catatanText = '';
    }

    public function saveCatatan()
    {
        try {
            if ($this->catatanType === 'seragam') {
                $seragam = Seragam::firstOrNew(['pendaftar_id' => $this->catatanPendaftarId]);
                $seragam->catatan_seragam = $this->catatanText;
                $seragam->save();

                $pendaftar = Pendaftar::find($this->catatanPendaftarId);
                if ($pendaftar) {
                    $pendaftar->touch();
                }

                $this->dispatch('saved', message: 'Catatan DU Seragam berhasil disimpan');

            } elseif ($this->catatanType === 'ukuran') {
                $seragam = Seragam::firstOrNew(['pendaftar_id' => $this->catatanPendaftarId]);
                $seragam->catatan_ukuran = $this->catatanText;
                $seragam->save();

                $pendaftar = Pendaftar::find($this->catatanPendaftarId);
                if ($pendaftar) {
                    $pendaftar->touch();
                }

                $this->dispatch('saved', message: 'Catatan Ukuran Seragam berhasil disimpan');
            }

            $this->closeCatatanModal();

        } catch (\Exception $e) {
            $this->dispatch('saved', message: 'Gagal menyimpan catatan: ' . $e->getMessage());
            \Log::error('Error saving catatan: ' . $e->getMessage());
        }
    }

    // ========== CATATAN INTERNAL METHODS ==========
    public function openCatatanInternalModal($type, $pendaftarId)
    {
        $this->catatanInternalType = $type;
        $this->catatanInternalPendaftarId = $pendaftarId;
        
        // Fetch current catatan_internal from database based on type
        $currentCatatan = '';
        if ($type === 'seragam') {
            $seragam = Seragam::where('pendaftar_id', $pendaftarId)->first();
            $currentCatatan = $seragam?->catatan_internal_seragam ?? '';
        } elseif ($type === 'ukuran') {
            $seragam = Seragam::where('pendaftar_id', $pendaftarId)->first();
            $currentCatatan = $seragam?->catatan_internal_ukuran ?? '';
        }
        
        $this->catatanInternalText = $currentCatatan;
        $this->showCatatanInternalModal = true;
    }

    public function closeCatatanInternalModal()
    {
        $this->showCatatanInternalModal = false;
        $this->catatanInternalType = '';
        $this->catatanInternalPendaftarId = null;
        $this->catatanInternalText = '';
    }

    public function saveCatatanInternal()
    {
        try {
            if ($this->catatanInternalType === 'seragam') {
                $seragam = Seragam::firstOrNew(['pendaftar_id' => $this->catatanInternalPendaftarId]);
                $seragam->catatan_internal_seragam = $this->catatanInternalText;
                $seragam->save();

                $pendaftar = Pendaftar::find($this->catatanInternalPendaftarId);
                if ($pendaftar) {
                    $pendaftar->touch();
                }

                $this->dispatch('saved', message: 'Catatan Internal DU Seragam berhasil disimpan');

            } elseif ($this->catatanInternalType === 'ukuran') {
                $seragam = Seragam::firstOrNew(['pendaftar_id' => $this->catatanInternalPendaftarId]);
                $seragam->catatan_internal_ukuran = $this->catatanInternalText;
                $seragam->save();

                $pendaftar = Pendaftar::find($this->catatanInternalPendaftarId);
                if ($pendaftar) {
                    $pendaftar->touch();
                }

                $this->dispatch('saved', message: 'Catatan Internal Ukuran Seragam berhasil disimpan');
            }

            $this->closeCatatanInternalModal();

        } catch (\Exception $e) {
            $this->dispatch('saved', message: 'Gagal menyimpan catatan internal: ' . $e->getMessage());
            \Log::error('Error saving catatan internal: ' . $e->getMessage());
        }
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
        \App\Models\Setting::set('daftar_ulang_seragam_packages_enable_opsi_lainnya', $this->seragamPackagesEnableOpsiLainnya, 'boolean');

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

    public function addUkuranKopyah()
    {
        $this->ukuranKopyahOptions[] = ['size' => '', 'measurement' => ''];
    }

    public function removeUkuranKopyah($index)
    {
        unset($this->ukuranKopyahOptions[$index]);
        $this->ukuranKopyahOptions = array_values($this->ukuranKopyahOptions);
    }

    public function saveUkuranSettings()
    {
        // Handle image uploads
        if ($this->ukuranJasImage) {
            $path = $this->ukuranJasImage->store('ukuran', 'public');
            \App\Models\Setting::set('daftar_ulang_ukuran_jas_image', $path);
            $this->ukuranJasImage = null;
        }

        if ($this->ukuranBajuHarianImage) {
            $path = $this->ukuranBajuHarianImage->store('ukuran', 'public');
            \App\Models\Setting::set('daftar_ulang_ukuran_baju_harian_image', $path);
            $this->ukuranBajuHarianImage = null;
        }

        if ($this->ukuranCelanaRokHarianImage) {
            $path = $this->ukuranCelanaRokHarianImage->store('ukuran', 'public');
            \App\Models\Setting::set('daftar_ulang_ukuran_celana_rok_harian_image', $path);
            $this->ukuranCelanaRokHarianImage = null;
        }

        if ($this->ukuranBajuOlahragaImage) {
            $path = $this->ukuranBajuOlahragaImage->store('ukuran', 'public');
            \App\Models\Setting::set('daftar_ulang_ukuran_baju_olahraga_image', $path);
            $this->ukuranBajuOlahragaImage = null;
        }

        if ($this->ukuranCelanaOlahragaImage) {
            $path = $this->ukuranCelanaOlahragaImage->store('ukuran', 'public');
            \App\Models\Setting::set('daftar_ulang_ukuran_celana_olahraga_image', $path);
            $this->ukuranCelanaOlahragaImage = null;
        }

        if ($this->ukuranKopyahImage) {
            $path = $this->ukuranKopyahImage->store('ukuran', 'public');
            \App\Models\Setting::set('daftar_ulang_ukuran_kopyah_image', $path);
            $this->ukuranKopyahImage = null;
        }

        // Save settings
        \App\Models\Setting::set('daftar_ulang_ukuran_info', $this->ukuranInfo);
        \App\Models\Setting::set('daftar_ulang_ukuran_by_gender', $this->ukuranByGender, 'boolean');
        \App\Models\Setting::set('daftar_ulang_ukuran_jas_options', $this->ukuranJasOptions, 'json');
        \App\Models\Setting::set('daftar_ulang_ukuran_jas_enable_opsi_lainnya', $this->ukuranJasEnableOpsiLainnya, 'boolean');
        \App\Models\Setting::set('daftar_ulang_ukuran_baju_harian_options', $this->ukuranBajuHarianOptions, 'json');
        \App\Models\Setting::set('daftar_ulang_ukuran_baju_harian_enable_opsi_lainnya', $this->ukuranBajuHarianEnableOpsiLainnya, 'boolean');
        \App\Models\Setting::set('daftar_ulang_ukuran_celana_rok_harian_options', $this->ukuranCelanaRokHarianOptions, 'json');
        \App\Models\Setting::set('daftar_ulang_ukuran_celana_rok_harian_enable_opsi_lainnya', $this->ukuranCelanaRokHarianEnableOpsiLainnya, 'boolean');
        \App\Models\Setting::set('daftar_ulang_ukuran_baju_olahraga_options', $this->ukuranBajuOlahragaOptions, 'json');
        \App\Models\Setting::set('daftar_ulang_ukuran_baju_olahraga_enable_opsi_lainnya', $this->ukuranBajuOlahragaEnableOpsiLainnya, 'boolean');
        \App\Models\Setting::set('daftar_ulang_ukuran_celana_olahraga_options', $this->ukuranCelanaOlahragaOptions, 'json');
        \App\Models\Setting::set('daftar_ulang_ukuran_celana_olahraga_enable_opsi_lainnya', $this->ukuranCelanaOlahragaEnableOpsiLainnya, 'boolean');
        \App\Models\Setting::set('daftar_ulang_ukuran_kopyah_options', $this->ukuranKopyahOptions, 'json');
        \App\Models\Setting::set('daftar_ulang_ukuran_kopyah_enable_opsi_lainnya', $this->ukuranKopyahEnableOpsiLainnya, 'boolean');
        // Legacy
        \App\Models\Setting::set('daftar_ulang_ukuran_atasan_options', $this->ukuranAtasanOptions, 'json');
        \App\Models\Setting::set('daftar_ulang_ukuran_bawahan_options', $this->ukuranBawahanOptions, 'json');

        $this->dispatch('saved', message: 'Pengaturan ukuran seragam berhasil disimpan.');
    }

    /**
     * Toggle Opsi Lainnya Dropdown Individual per Pendaftar (Seragam)
     * Allows panitia seragam to enable "Opsi Lainnya" in dropdown for specific pendaftar
     */
    public function toggleOpsiLainnyaDropdownIndividual($pendaftarId, $tab)
    {
        try {
            if ($tab === 'seragam' || $tab === 'ukuran') {
                $seragam = Seragam::firstOrCreate(
                    ['pendaftar_id' => $pendaftarId],
                    ['opsi_lainnya_dropdown_individual' => false]
                );

                $newValue = !$seragam->opsi_lainnya_dropdown_individual;
                $seragam->update(['opsi_lainnya_dropdown_individual' => $newValue]);

                $statusText = $newValue ? 'diaktifkan' : 'dinonaktifkan';
                $tabLabel = $tab === 'seragam' ? 'Seragam' : 'Ukuran';
                $this->dispatch('saved', message: "Opsi Lainnya Dropdown ({$tabLabel}) untuk pendaftar ini berhasil {$statusText}.");
            }
        } catch (\Exception $e) {
            $this->dispatch('saved', message: 'Gagal mengubah toggle: ' . $e->getMessage());
        }
    }

    /**
     * Toggle Opsi Lainnya Konfirmasi Individual per Pendaftar (Seragam)
     * Allows panitia seragam to enable "Opsi Lainnya" in konfirmasi nominal for specific pendaftar
     */
    public function toggleOpsiLainnyaKonfirmasiIndividual($pendaftarId, $tab)
    {
        try {
            if ($tab === 'seragam' || $tab === 'ukuran') {
                $seragam = Seragam::firstOrCreate(
                    ['pendaftar_id' => $pendaftarId],
                    ['opsi_lainnya_konfirmasi_individual' => false]
                );

                $newValue = !$seragam->opsi_lainnya_konfirmasi_individual;
                $seragam->update(['opsi_lainnya_konfirmasi_individual' => $newValue]);

                $statusText = $newValue ? 'diaktifkan' : 'dinonaktifkan';
                $tabLabel = $tab === 'seragam' ? 'Seragam' : 'Ukuran';
                $this->dispatch('saved', message: "Opsi Lainnya Konfirmasi ({$tabLabel}) untuk pendaftar ini berhasil {$statusText}.");
            }
        } catch (\Exception $e) {
            $this->dispatch('saved', message: 'Gagal mengubah toggle: ' . $e->getMessage());
        }
    }
}
