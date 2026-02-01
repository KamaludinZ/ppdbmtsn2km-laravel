<?php

namespace App\Livewire\PanitiaSeragam;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use App\Models\Pendaftar;
use App\Models\Seragam;
use App\Models\RiwayatPesan;
use Illuminate\Support\Facades\Auth;

class DaftarUlang extends Component
{
    use WithPagination;

    public $activeTab = 'seragam';
    public $search = '';
    public $filterStatusSeragam = '';
    public $filterStatusUkuran = '';
    public $filterJenisKelamin = '';
    public $filterJalur = '';

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

    /**
     * Override queryString untuk exclude activeTab dari URL
     */
    protected function queryString()
    {
        return []; // No URL tracking for clean URL
    }

    public function mount()
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'panitia_seragam') {
            return redirect()->to('/login/admin');
        }
    }

    public function switchTab($tab)
    {
        if (in_array($tab, ['seragam', 'ukuran'])) {
            $this->activeTab = $tab;
            $this->resetPage();
        }
    }

    private function getSeragamStats()
    {
        // Get all pendaftar lulus
        $allPendaftar = Pendaftar::with(['seragam'])
            ->whereIn('status_pendaftaran', ['data_disetujui', 'lulus', 'diterima'])
            ->when($this->filterJalur, function ($q) {
                $q->where('jalur_pendaftaran_id', $this->filterJalur);
            })
            ->when($this->filterJenisKelamin, function ($q) {
                $q->where('jenis_kelamin', $this->filterJenisKelamin);
            })
            ->get();

        $stats = [
            // Total pendaftar lulus
            'total_lulus' => $allPendaftar->count(),

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
        // Get all pendaftar lulus
        $allPendaftar = Pendaftar::with(['seragam'])
            ->whereIn('status_pendaftaran', ['data_disetujui', 'lulus', 'diterima'])
            ->when($this->filterJalur, function ($q) {
                $q->where('jalur_pendaftaran_id', $this->filterJalur);
            })
            ->when($this->filterJenisKelamin, function ($q) {
                $q->where('jenis_kelamin', $this->filterJenisKelamin);
            })
            ->get();

        $stats = [
            // Total pendaftar lulus
            'total_lulus' => $allPendaftar->count(),

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

        // Status filter based on active tab
        if ($this->activeTab === 'seragam' && $this->filterStatusSeragam) {
            $query->whereHas('seragam', function($q) {
                $q->where('status_seragam', $this->filterStatusSeragam);
            });
        } elseif ($this->activeTab === 'ukuran' && $this->filterStatusUkuran) {
            $query->whereHas('seragam', function($q) {
                $q->where('status_ukuran', $this->filterStatusUkuran);
            });
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
    public function openCatatanModal($type, $pendaftarId, $currentCatatan = '')
    {
        $this->catatanType = $type;
        $this->catatanPendaftarId = $pendaftarId;
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
}
