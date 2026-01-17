<?php

namespace App\Livewire\Admin;

use App\Models\TesPsikologiSetting;
use App\Models\TesPsikologiOpsi;
use App\Models\TesPsikologiBank;
use App\Models\TesPsikologiPendaftar;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TesPsikologi extends Component
{
    use WithPagination;

    #[Url(keep: true)]
    public $mainTab = 'data'; // Main tab: data or settings

    // Filter for data tab
    public $filterStatus = 'all';

    public $setting;
    public $informasi;
    public $rekening_bank_nama;
    public $rekening_nomor;
    public $rekening_atas_nama;
    public $rekening_keterangan;
    public $aktif;

    // Opsi CRUD
    public $opsiList;
    public $opsiNama;
    public $opsiDeskripsi;
    public $opsiUrutan;
    public $opsiAktif = true;
    public $editingOpsi = null;

    // Bank CRUD (Bank Pengirim / Metode Pembayaran)
    public $bankList;
    public $bankNamaBank;
    public $bankUrutan;
    public $bankAktif = true;
    public $editingBank = null;

    // Verification modal
    public $showVerificationModal = false;
    public $verifyingId = null;
    public $verificationStatus = null;
    public $verificationNote = '';

    // Message modal
    public $showMessageModal = false;
    public $messagingId = null;
    public $messagingPendaftar = null;
    public $messageContent = '';

    public function mount()
    {
        // Only load settings data if user is admin
        if (Auth::user()->role === 'admin') {
            $this->setting = TesPsikologiSetting::getSetting();
            $this->informasi = $this->setting->informasi;
            $this->rekening_bank_nama = $this->setting->rekening_bank_nama;
            $this->rekening_nomor = $this->setting->rekening_nomor;
            $this->rekening_atas_nama = $this->setting->rekening_atas_nama;
            $this->rekening_keterangan = $this->setting->rekening_keterangan;
            $this->aktif = $this->setting->aktif;

            $this->loadOpsi();
            $this->loadBank();
        }
    }

    public function loadOpsi()
    {
        $this->opsiList = TesPsikologiOpsi::ordered()->get();
    }

    public function loadBank()
    {
        $this->bankList = TesPsikologiBank::ordered()->get();
    }

    public function simpanSetting()
    {
        $this->validate([
            'informasi' => 'nullable|string',
            'rekening_bank_nama' => 'nullable|string|max:255',
            'rekening_nomor' => 'nullable|string|max:255',
            'rekening_atas_nama' => 'nullable|string|max:255',
            'rekening_keterangan' => 'nullable|string',
            'aktif' => 'required|boolean'
        ]);

        $this->setting->update([
            'informasi' => $this->informasi,
            'rekening_bank_nama' => $this->rekening_bank_nama,
            'rekening_nomor' => $this->rekening_nomor,
            'rekening_atas_nama' => $this->rekening_atas_nama,
            'rekening_keterangan' => $this->rekening_keterangan,
            'aktif' => $this->aktif
        ]);

        session()->flash('message', 'Pengaturan berhasil disimpan');
    }

    // Opsi CRUD Methods
    public function simpanOpsi()
    {
        $this->validate([
            'opsiNama' => 'required|string|max:255',
            'opsiDeskripsi' => 'nullable|string',
            'opsiUrutan' => 'required|integer|min:0',
            'opsiAktif' => 'required|boolean'
        ]);

        if ($this->editingOpsi) {
            $opsi = TesPsikologiOpsi::find($this->editingOpsi);
            $opsi->update([
                'nama' => $this->opsiNama,
                'deskripsi' => $this->opsiDeskripsi,
                'urutan' => $this->opsiUrutan,
                'aktif' => $this->opsiAktif
            ]);
            session()->flash('message', 'Opsi berhasil diupdate');
        } else {
            TesPsikologiOpsi::create([
                'nama' => $this->opsiNama,
                'deskripsi' => $this->opsiDeskripsi,
                'urutan' => $this->opsiUrutan,
                'aktif' => $this->opsiAktif
            ]);
            session()->flash('message', 'Opsi berhasil ditambahkan');
        }

        $this->resetOpsiForm();
        $this->loadOpsi();
    }

    public function editOpsi($id)
    {
        $opsi = TesPsikologiOpsi::find($id);
        $this->editingOpsi = $id;
        $this->opsiNama = $opsi->nama;
        $this->opsiDeskripsi = $opsi->deskripsi;
        $this->opsiUrutan = $opsi->urutan;
        $this->opsiAktif = $opsi->aktif;
    }

    public function hapusOpsi($id)
    {
        TesPsikologiOpsi::destroy($id);
        $this->loadOpsi();
        session()->flash('message', 'Opsi berhasil dihapus');
    }

    public function resetOpsiForm()
    {
        $this->editingOpsi = null;
        $this->opsiNama = '';
        $this->opsiDeskripsi = '';
        $this->opsiUrutan = 0;
        $this->opsiAktif = true;
    }

    // Bank CRUD Methods
    public function simpanBank()
    {
        $this->validate([
            'bankNamaBank' => 'required|string|max:255',
            'bankUrutan' => 'required|integer|min:0',
            'bankAktif' => 'required|boolean'
        ]);

        if ($this->editingBank) {
            $bank = TesPsikologiBank::find($this->editingBank);
            $bank->update([
                'nama_bank' => $this->bankNamaBank,
                'nomor_rekening' => '-', // Keep for compatibility
                'atas_nama' => '-', // Keep for compatibility
                'keterangan' => '-', // Keep for compatibility
                'urutan' => $this->bankUrutan,
                'aktif' => $this->bankAktif
            ]);
            session()->flash('message', 'Bank pengirim berhasil diupdate');
        } else {
            TesPsikologiBank::create([
                'nama_bank' => $this->bankNamaBank,
                'nomor_rekening' => '-', // Keep for compatibility
                'atas_nama' => '-', // Keep for compatibility
                'keterangan' => '-', // Keep for compatibility
                'urutan' => $this->bankUrutan,
                'aktif' => $this->bankAktif
            ]);
            session()->flash('message', 'Bank pengirim berhasil ditambahkan');
        }

        $this->resetBankForm();
        $this->loadBank();
    }

    public function editBank($id)
    {
        $bank = TesPsikologiBank::find($id);
        $this->editingBank = $id;
        $this->bankNamaBank = $bank->nama_bank;
        $this->bankUrutan = $bank->urutan;
        $this->bankAktif = $bank->aktif;
    }

    public function hapusBank($id)
    {
        TesPsikologiBank::destroy($id);
        $this->loadBank();
        session()->flash('message', 'Bank pengirim berhasil dihapus');
    }

    public function resetBankForm()
    {
        $this->editingBank = null;
        $this->bankNamaBank = '';
        $this->bankUrutan = 0;
        $this->bankAktif = true;
    }

    // Tab Management
    public function setMainTab($tab)
    {
        $this->mainTab = $tab;
        $this->resetPage();
    }

    public function updatedFilterStatus()
    {
        $this->resetPage();
    }

    // Verification Methods
    public function openVerificationModal($id, $status)
    {
        $this->verifyingId = $id;
        $this->verificationStatus = $status;
        $this->verificationNote = '';
        $this->showVerificationModal = true;
    }

    public function verifyPayment()
    {
        $this->validate([
            'verificationStatus' => 'required|in:disetujui,ditolak,perbaiki',
            'verificationNote' => 'nullable|string|max:500',
        ]);

        $tesPsikologi = TesPsikologiPendaftar::findOrFail($this->verifyingId);

        $tesPsikologi->update([
            'status_verifikasi' => $this->verificationStatus,
            'catatan_verifikasi' => $this->verificationNote,
            'tanggal_verifikasi' => now(),
            'verified_by' => Auth::id(),
        ]);

        // Send notification to pendaftar
        if ($tesPsikologi->pendaftar && $tesPsikologi->pendaftar->user) {
            $tesPsikologi->pendaftar->user->notify(
                new \App\Notifications\StatusChangeNotification(
                    'tes_psikologi',
                    $this->verificationStatus,
                    $this->verificationNote
                )
            );
        }

        $statusLabels = [
            'disetujui' => 'disetujui',
            'ditolak' => 'ditolak',
            'perbaiki' => 'diminta untuk diperbaiki'
        ];
        $statusLabel = $statusLabels[$this->verificationStatus] ?? $this->verificationStatus;
        session()->flash('message', "Bukti pembayaran berhasil {$statusLabel}.");

        $this->closeVerificationModal();
    }

    public function changeStatus($id, $status)
    {
        $tesPsikologi = TesPsikologiPendaftar::findOrFail($id);

        $tesPsikologi->update([
            'status_verifikasi' => $status,
            'tanggal_verifikasi' => now(),
            'verified_by' => Auth::id(),
        ]);

        // Notification is sent manually via "Kirim Pesan" button
        // Auto-notification removed as per user request

        $statusLabels = [
            'disetujui' => 'disetujui',
            'ditolak' => 'ditolak',
            'perbaiki' => 'diminta untuk diperbaiki',
            'menunggu' => 'dikembalikan ke menunggu verifikasi'
        ];
        $statusLabel = $statusLabels[$status] ?? $status;
        session()->flash('message', "Status psikologi berhasil diubah menjadi {$statusLabel}.");
    }

    public function closeVerificationModal()
    {
        $this->showVerificationModal = false;
        $this->verifyingId = null;
        $this->verificationStatus = null;
        $this->verificationNote = '';
        $this->resetValidation();
    }

    // Message Methods
    public function sendManualEmail($id)
    {
        $tesPsikologi = TesPsikologiPendaftar::with('pendaftar')->findOrFail($id);

        // TODO: Implement email sending logic here
        // Example: Mail::to($tesPsikologi->pendaftar->user->email)->send(new TesPsikologiEmail($tesPsikologi));

        // Save to message history
        \App\Models\MessageHistory::create([
            'pendaftar_id' => $tesPsikologi->pendaftar_id,
            'sender_id' => Auth::id(),
            'message_type' => 'email',
            'context' => 'tes_psikologi',
            'message_content' => 'Email dikirim untuk tes psikologi',
            'sent_at' => now(),
        ]);

        session()->flash('message', 'Email berhasil dikirim ke ' . $tesPsikologi->pendaftar->nama_lengkap);
    }

    public function sendManualWhatsApp($id)
    {
        $tesPsikologi = TesPsikologiPendaftar::with('pendaftar')->findOrFail($id);

        // TODO: Implement WhatsApp sending logic here
        // Example: Send WhatsApp message via API

        // Save to message history
        \App\Models\MessageHistory::create([
            'pendaftar_id' => $tesPsikologi->pendaftar_id,
            'sender_id' => Auth::id(),
            'message_type' => 'whatsapp',
            'context' => 'tes_psikologi',
            'message_content' => 'WhatsApp dikirim untuk tes psikologi',
            'sent_at' => now(),
        ]);

        session()->flash('message', 'WhatsApp berhasil dikirim ke ' . $tesPsikologi->pendaftar->nama_lengkap);
    }

    public function openMessageModal($id)
    {
        $this->messagingId = $id;
        $this->messagingPendaftar = TesPsikologiPendaftar::with('pendaftar')->findOrFail($id);
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

        $tesPsikologi = TesPsikologiPendaftar::findOrFail($this->messagingId);

        // Send notification to pendaftar
        if ($tesPsikologi->pendaftar && $tesPsikologi->pendaftar->user) {
            $tesPsikologi->pendaftar->user->notify(
                new \App\Notifications\CustomMessageNotification(
                    'Pesan Tes Psikologi',
                    $this->messageContent,
                    'tes_psikologi'
                )
            );
        }

        // Save to message history
        \App\Models\MessageHistory::create([
            'pendaftar_id' => $tesPsikologi->pendaftar_id,
            'sender_id' => Auth::id(),
            'message_type' => 'notification',
            'context' => 'tes_psikologi',
            'message_content' => $this->messageContent,
            'sent_at' => now(),
        ]);

        session()->flash('message', 'Pesan berhasil dikirim ke ' . $tesPsikologi->pendaftar->nama_lengkap);
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
        // Get statistics
        $stats = [
            'total' => TesPsikologiPendaftar::count(),
            'menunggu' => TesPsikologiPendaftar::menungguVerifikasi()->count(),
            'disetujui' => TesPsikologiPendaftar::disetujui()->count(),
            'ditolak' => TesPsikologiPendaftar::ditolak()->count(),
            'perbaiki' => TesPsikologiPendaftar::where('status_verifikasi', 'perbaiki')->count(),
        ];

        // Get data psikologi with pagination
        $dataPsikologi = [];
        if ($this->mainTab === 'data') {
            $query = TesPsikologiPendaftar::with(['pendaftar', 'opsi', 'bank', 'verifiedBy'])
                ->orderBy('created_at', 'desc');

            if ($this->filterStatus !== 'all') {
                $query->where('status_verifikasi', $this->filterStatus);
            }

            $dataPsikologi = $query->paginate(15);
        }

        return view('livewire.admin.tes-psikologi', compact('stats', 'dataPsikologi'))
            ->layout('components.layouts.dashboard', [
                'role' => 'admin',
                'pageTitle' => 'Manajemen Tes Psikologi',
                'pageDescription' => 'Kelola data dan pengaturan tes psikologi pendaftaran'
            ]);
    }
}
