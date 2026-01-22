<?php

namespace App\Livewire\Admin;

use App\Models\Pendaftar;
use App\Models\JalurPendaftaran;
use App\Traits\WithSorting;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class Kelulusan extends Component
{
    use WithPagination, WithSorting;

    public $search = '';
    public $filterJalur = '';
    public $filterStatus = '';

    // Properties for send message modal
    public $isSendMessageModalOpen = false;
    public $messageType = 'email'; // 'email' or 'whatsapp'
    public $selectedPendaftarId = null;
    public $selectedMessageTemplate = '';
    public $messageSubject = '';
    public $messageBody = '';
    public $messageNote = '';

    // Properties for bulk send message modal
    public $isBulkSendMessageModalOpen = false;
    public $bulkMessageType = 'email';
    public $bulkMessageTemplate = '';
    public $bulkMessageSubject = '';
    public $bulkMessageBody = '';
    public $bulkMessageNote = '';

    // Reset pagination when filters change
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterJalur()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function render()
    {
        // Build base query with optimized eager loading
        $query = Pendaftar::query()
            ->select('pendaftar.*') // Only select needed columns
            ->with(['jalurPendaftaran:id,nama,warna']);

        // Apply filters
        if ($this->search) {
            $query->where(function($q) {
                $q->where('nama_lengkap', 'like', '%' . $this->search . '%')
                  ->orWhere('nisn', 'like', '%' . $this->search . '%')
                  ->orWhere('nomor_pendaftaran', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filterJalur) {
            $query->where('jalur_pendaftaran_id', $this->filterJalur);
        }

        if ($this->filterStatus) {
            $query->where('status_pendaftaran', $this->filterStatus);
        }

        // Calculate statistics using single query with groupBy
        $statsData = Pendaftar::query()
            ->when($this->search, function ($q) {
                $q->where(function($subQ) {
                    $subQ->where('nama_lengkap', 'like', '%' . $this->search . '%')
                      ->orWhere('nisn', 'like', '%' . $this->search . '%')
                      ->orWhere('nomor_pendaftaran', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterJalur, fn($q) => $q->where('jalur_pendaftaran_id', $this->filterJalur))
            ->when($this->filterStatus, fn($q) => $q->where('status_pendaftaran', $this->filterStatus))
            ->selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN status_pendaftaran IN ('menunggu_verifikasi', 'data_disetujui') THEN 1 ELSE 0 END) as menunggu,
                SUM(CASE WHEN status_pendaftaran IN ('lulus', 'diterima') THEN 1 ELSE 0 END) as lulus,
                SUM(CASE WHEN status_pendaftaran IN ('cadangan', 'lengkapi_data') THEN 1 ELSE 0 END) as cadangan,
                SUM(CASE WHEN status_pendaftaran IN ('tidak_lulus', 'ditolak', 'data_ditolak') THEN 1 ELSE 0 END) as tidak_lulus
            ")
            ->first();

        $stats = [
            'total' => $statsData->total ?? 0,
            'menunggu' => $statsData->menunggu ?? 0,
            'lulus' => $statsData->lulus ?? 0,
            'cadangan' => $statsData->cadangan ?? 0,
            'tidak_lulus' => $statsData->tidak_lulus ?? 0,
        ];

        // Apply sorting and paginate results
        $pendaftar = $this->applySorting($query)->paginate(10);

        // Cache jalur pendaftaran
        $jalurPendaftaran = cache()->remember('jalur_pendaftaran_kelulusan', 3600, function () {
            return JalurPendaftaran::select('id', 'nama')->orderBy('nama')->get();
        });

        return view('livewire.admin.kelulusan', [
            'pendaftar' => $pendaftar,
            'jalurPendaftaran' => $jalurPendaftaran,
            'stats' => $stats
        ])->layout('components.layouts.dashboard', [
            'role' => 'admin',
            'pageTitle' => 'Manajemen Kelulusan',
            'pageDescription' => 'Kelola status kelulusan pendaftar'
        ]);
    }

    public function updateStatus($id, $status)
    {
        try {
            $pendaftar = Pendaftar::findOrFail($id);

            $oldStatus = $pendaftar->status_pendaftaran;
            $pendaftar->status_pendaftaran = $status;
            $pendaftar->tanggal_verifikasi = now();
            $pendaftar->save();

            // Success message with details
            $statusLabel = ucfirst(str_replace('_', ' ', $status));

            // Dispatch event untuk notifikasi
            $this->dispatch('statusUpdated', [
                'type' => 'success',
                'message' => "Status {$pendaftar->nama_lengkap} berhasil diubah menjadi {$statusLabel}"
            ]);

            // Flash session message
            session()->flash('success', "Status {$pendaftar->nama_lengkap} berhasil diubah menjadi {$statusLabel}");

        } catch (\Exception $e) {
            $this->dispatch('statusUpdated', [
                'type' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);

            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function bulkUpdateStatus($status)
    {
        try {
            $query = Pendaftar::query();

            if ($this->search) {
                $query->where(function($q) {
                    $q->where('nama_lengkap', 'like', '%' . $this->search . '%')
                      ->orWhere('nisn', 'like', '%' . $this->search . '%')
                      ->orWhere('nomor_pendaftaran', 'like', '%' . $this->search . '%');
                });
            }

            if ($this->filterJalur) {
                $query->where('jalur_pendaftaran_id', $this->filterJalur);
            }

            if ($this->filterStatus) {
                $query->where('status_pendaftaran', $this->filterStatus);
            }

            $count = $query->update([
                'status_pendaftaran' => $status,
                'tanggal_verifikasi' => now()
            ]);

            $statusLabel = ucfirst(str_replace('_', ' ', $status));

            // Dispatch event untuk notifikasi
            $this->dispatch('statusUpdated', [
                'type' => 'success',
                'message' => "{$count} pendaftar berhasil diubah statusnya menjadi {$statusLabel}"
            ]);

            // Flash session message
            session()->flash('success', "{$count} pendaftar berhasil diubah statusnya menjadi {$statusLabel}");

        } catch (\Exception $e) {
            $this->dispatch('statusUpdated', [
                'type' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);

            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
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

        // Replace placeholders
        $replacements = [
            '{nama}' => $pendaftar->nama_lengkap,
            '{nomor_pendaftaran}' => $pendaftar->nomor_pendaftaran,
            '{email}' => $pendaftar->email,
            '{telepon}' => $pendaftar->no_hp,
            '{jalur}' => $pendaftar->jalurPendaftaran->nama ?? '',
            '{status}' => $pendaftar->status_pendaftaran,
            '{catatan}' => $this->messageNote,
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
            session()->flash('error', 'Pendaftar tidak ditemukan.');
            return;
        }

        try {
            if ($this->messageType === 'email') {
                // Kirim email
                \Illuminate\Support\Facades\Mail::raw($this->messageBody, function ($message) use ($pendaftar) {
                    $message->to($pendaftar->email)
                        ->subject($this->messageSubject);
                });

                $this->dispatch('statusUpdated', [
                    'type' => 'success',
                    'message' => 'Email berhasil dikirim ke ' . $pendaftar->nama_lengkap
                ]);
            } else {
                // Kirim WhatsApp
                $phoneNumber = $pendaftar->no_hp;
                if (!$phoneNumber) {
                    $this->dispatch('statusUpdated', [
                        'type' => 'error',
                        'message' => 'Nomor WhatsApp tidak ditemukan.'
                    ]);
                    return;
                }

                // Format nomor telepon
                $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
                if (substr($phoneNumber, 0, 1) === '0') {
                    $phoneNumber = '62' . substr($phoneNumber, 1);
                }

                // Placeholder untuk integrasi WhatsApp API
                // TODO: Implementasikan sesuai dengan service yang digunakan

                $this->dispatch('statusUpdated', [
                    'type' => 'success',
                    'message' => 'WhatsApp berhasil dikirim ke ' . $pendaftar->nama_lengkap
                ]);
            }

            $this->closeSendMessageModal();

        } catch (\Exception $e) {
            $this->dispatch('statusUpdated', [
                'type' => 'error',
                'message' => 'Gagal mengirim pesan: ' . $e->getMessage()
            ]);
        }
    }

    // ========== BULK SEND MESSAGE METHODS ==========
    public function openBulkSendEmail()
    {
        $this->bulkMessageType = 'email';
        $this->bulkMessageTemplate = '';
        $this->bulkMessageSubject = '';
        $this->bulkMessageBody = '';
        $this->bulkMessageNote = '';
        $this->isBulkSendMessageModalOpen = true;
    }

    public function openBulkSendWhatsApp()
    {
        $this->bulkMessageType = 'whatsapp';
        $this->bulkMessageTemplate = '';
        $this->bulkMessageSubject = '';
        $this->bulkMessageBody = '';
        $this->bulkMessageNote = '';
        $this->isBulkSendMessageModalOpen = true;
    }

    public function closeBulkSendMessageModal()
    {
        $this->isBulkSendMessageModalOpen = false;
        $this->bulkMessageType = 'email';
        $this->bulkMessageTemplate = '';
        $this->bulkMessageSubject = '';
        $this->bulkMessageBody = '';
        $this->bulkMessageNote = '';
    }

    public function loadBulkMessageTemplate()
    {
        if (!$this->bulkMessageTemplate) {
            return;
        }

        // Load template dari settings
        if ($this->bulkMessageType === 'email') {
            $this->bulkMessageSubject = \App\Models\Setting::get("email_{$this->bulkMessageTemplate}_subject", '');
            $this->bulkMessageBody = \App\Models\Setting::get("email_{$this->bulkMessageTemplate}_body", '');
        } else {
            $this->bulkMessageBody = \App\Models\Setting::get("wa_{$this->bulkMessageTemplate}_body", '');
        }
    }

    public function sendBulkMessage()
    {
        $this->validate([
            'bulkMessageBody' => 'required|string',
        ]);

        if ($this->bulkMessageType === 'email') {
            $this->validate([
                'bulkMessageSubject' => 'required|string',
            ]);
        }

        try {
            // Get filtered pendaftar
            $query = Pendaftar::query();

            if ($this->search) {
                $query->where(function($q) {
                    $q->where('nama_lengkap', 'like', '%' . $this->search . '%')
                      ->orWhere('nisn', 'like', '%' . $this->search . '%')
                      ->orWhere('nomor_pendaftaran', 'like', '%' . $this->search . '%');
                });
            }

            if ($this->filterJalur) {
                $query->where('jalur_pendaftaran_id', $this->filterJalur);
            }

            if ($this->filterStatus) {
                $query->where('status_pendaftaran', $this->filterStatus);
            }

            $pendaftarList = $query->get();
            $successCount = 0;
            $failedCount = 0;

            foreach ($pendaftarList as $pendaftar) {
                try {
                    // Replace placeholders for each pendaftar
                    $replacements = [
                        '{nama}' => $pendaftar->nama_lengkap,
                        '{nomor_pendaftaran}' => $pendaftar->nomor_pendaftaran,
                        '{email}' => $pendaftar->email,
                        '{telepon}' => $pendaftar->no_hp,
                        '{jalur}' => $pendaftar->jalurPendaftaran->nama ?? '',
                        '{status}' => $pendaftar->status_pendaftaran,
                        '{catatan}' => $this->bulkMessageNote,
                        '{url_dashboard}' => url('/dashboard'),
                    ];

                    $subject = str_replace(array_keys($replacements), array_values($replacements), $this->bulkMessageSubject);
                    $body = str_replace(array_keys($replacements), array_values($replacements), $this->bulkMessageBody);

                    if ($this->bulkMessageType === 'email') {
                        // Kirim email
                        \Illuminate\Support\Facades\Mail::raw($body, function ($message) use ($pendaftar, $subject) {
                            $message->to($pendaftar->email)
                                ->subject($subject);
                        });
                        $successCount++;
                    } else {
                        // Kirim WhatsApp
                        if ($pendaftar->no_hp) {
                            // Placeholder untuk integrasi WhatsApp API
                            // TODO: Implementasikan sesuai dengan service yang digunakan
                            $successCount++;
                        } else {
                            $failedCount++;
                        }
                    }
                } catch (\Exception $e) {
                    $failedCount++;
                }
            }

            $messageType = $this->bulkMessageType === 'email' ? 'Email' : 'WhatsApp';
            $this->dispatch('statusUpdated', [
                'type' => 'success',
                'message' => "{$messageType} berhasil dikirim ke {$successCount} pendaftar" . ($failedCount > 0 ? ", {$failedCount} gagal" : "")
            ]);

            $this->closeBulkSendMessageModal();

        } catch (\Exception $e) {
            $this->dispatch('statusUpdated', [
                'type' => 'error',
                'message' => 'Gagal mengirim pesan massal: ' . $e->getMessage()
            ]);
        }
    }
}
