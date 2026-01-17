<?php

namespace App\Livewire\Panitia;

use App\Models\DaftarUlang as DaftarUlangModel;
use App\Models\Pendaftar;
use App\Models\Pembayaran;
use App\Models\Seragam;
use App\Models\Dokumen;
use App\Traits\WithSorting;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class DaftarUlang extends Component
{
    use WithPagination, WithSorting;

    public $activeTab = 'madrasah';
    public $search = '';
    public $filterStatus = '';

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

    public $madrasahContent = '';
    public $seragamContent = '';
    public $ukuranContent = '';
    public $showPernyataan = true;
    public $showPembayaranMadrasah = true;
    public $showPembayaranSeragam = true;
    public $showUkuranAtasan = true;
    public $showUkuranBawahan = true;
    public $showAtribut = true;

    public function mount()
    {
        $user = Auth::user();
        if (!$user || !in_array($user->role, ['panitia_daftarulang', 'panitia'])) {
            return redirect()->to('/login/admin');
        }

        $this->madrasahContent = \App\Models\Setting::get('daftar_ulang_madrasah_content', '');
        $this->seragamContent = \App\Models\Setting::get('daftar_ulang_seragam_content', '');
        $this->ukuranContent = \App\Models\Setting::get('daftar_ulang_ukuran_content', '');
        $this->showPernyataan = \App\Models\Setting::get('daftar_ulang_show_pernyataan', true);
        $this->showPembayaranMadrasah = \App\Models\Setting::get('daftar_ulang_show_pembayaran_madrasah', true);
        $this->showPembayaranSeragam = \App\Models\Setting::get('daftar_ulang_show_pembayaran_seragam', true);
        $this->showUkuranAtasan = \App\Models\Setting::get('daftar_ulang_show_ukuran_atasan', true);
        $this->showUkuranBawahan = \App\Models\Setting::get('daftar_ulang_show_ukuran_bawahan', true);
        $this->showAtribut = \App\Models\Setting::get('daftar_ulang_show_atribut', true);
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
            'context' => 'daftar_ulang_' . $this->activeTab,
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

        // Save to message history
        \App\Models\MessageHistory::create([
            'pendaftar_id' => $pendaftar->id,
            'sender_id' => auth()->id(),
            'message_type' => 'notification',
            'context' => 'daftar_ulang_' . $this->activeTab,
            'message_content' => $this->messageContent,
            'sent_at' => now(),
        ]);

        $this->dispatch('saved', message: 'Pesan berhasil dikirim ke ' . $pendaftar->nama_lengkap);
        $this->closeMessageModal();
    }

    public function closeMessageModal()
    {
        $this->showMessageModal = false;
        $this->messagingId = null;
        $this->messagingPendaftar = null;
        $this->messageContent = '';
        $this->resetValidation();
    }

    public function render()
    {
        $query = Pendaftar::with(['daftarUlang', 'pembayaran', 'seragam', 'dokumen'])
            ->where('status_pendaftaran', 'data_disetujui'); // Semua pendaftar dengan data disetujui (lulus) otomatis muncul

        if ($this->search) {
            $query->where(function($q) {
                $q->where('nama_lengkap', 'like', '%' . $this->search . '%')
                  ->orWhere('nisn', 'like', '%' . $this->search . '%')
                  ->orWhere('nomor_pendaftaran', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filterStatus) {
            $query->whereHas('daftarUlang', function($q) {
                $q->where('status', $this->filterStatus);
            });
        }

        $pendaftar = $this->applySorting($query)->paginate(10);

        // Calculate stats based on active tab
        $allPendaftar = Pendaftar::with(['daftarUlang', 'pembayaran', 'seragam'])
            ->where('status_pendaftaran', 'data_disetujui')
            ->get();

        $stats = [];
        if ($this->activeTab === 'madrasah') {
            $stats = [
                'total' => $allPendaftar->count(),
                'disetujui' => $allPendaftar->filter(function($p) {
                    return $p->daftarUlang && $p->daftarUlang->status === 'disetujui';
                })->count(),
                'ditolak' => $allPendaftar->filter(function($p) {
                    return $p->daftarUlang && $p->daftarUlang->status === 'ditolak';
                })->count(),
                'perbaiki' => $allPendaftar->filter(function($p) {
                    return $p->daftarUlang && $p->daftarUlang->status === 'perbaiki';
                })->count(),
                'menunggu' => $allPendaftar->filter(function($p) {
                    return !$p->daftarUlang || $p->daftarUlang->status === 'menunggu';
                })->count(),
            ];
        } elseif ($this->activeTab === 'seragam') {
            $stats = [
                'total' => $allPendaftar->count(),
                'disetujui' => $allPendaftar->filter(function($p) {
                    return $p->seragam && $p->seragam->status_seragam === 'disetujui';
                })->count(),
                'ditolak' => $allPendaftar->filter(function($p) {
                    return $p->seragam && $p->seragam->status_seragam === 'ditolak';
                })->count(),
                'perbaiki' => $allPendaftar->filter(function($p) {
                    return $p->seragam && $p->seragam->status_seragam === 'perbaiki';
                })->count(),
                'menunggu' => $allPendaftar->filter(function($p) {
                    return !$p->seragam || $p->seragam->status_seragam === 'menunggu' || !$p->seragam->status_seragam;
                })->count(),
            ];
        } elseif ($this->activeTab === 'ukuran') {
            $stats = [
                'total' => $allPendaftar->count(),
                'disetujui' => $allPendaftar->filter(function($p) {
                    return $p->seragam && $p->seragam->status_ukuran === 'disetujui';
                })->count(),
                'ditolak' => $allPendaftar->filter(function($p) {
                    return $p->seragam && $p->seragam->status_ukuran === 'ditolak';
                })->count(),
                'perbaiki' => $allPendaftar->filter(function($p) {
                    return $p->seragam && $p->seragam->status_ukuran === 'perbaiki';
                })->count(),
                'menunggu' => $allPendaftar->filter(function($p) {
                    return !$p->seragam || $p->seragam->status_ukuran === 'menunggu' || !$p->seragam->status_ukuran;
                })->count(),
            ];
        }

        return view('livewire.panitia.daftar-ulang', [
            'pendaftar' => $pendaftar,
            'stats' => $stats
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

    public function saveSettings()
    {
        $this->validate([
            'madrasahContent' => 'nullable|string',
            'seragamContent' => 'nullable|string',
            'ukuranContent' => 'nullable|string',
        ]);

        \App\Models\Setting::set('daftar_ulang_madrasah_content', $this->madrasahContent, 'text', 'Konten Daftar Ulang Madrasah');
        \App\Models\Setting::set('daftar_ulang_seragam_content', $this->seragamContent, 'text', 'Konten Daftar Ulang Seragam');
        \App\Models\Setting::set('daftar_ulang_ukuran_content', $this->ukuranContent, 'text', 'Konten Ukuran Seragam & Atribut');
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
}
