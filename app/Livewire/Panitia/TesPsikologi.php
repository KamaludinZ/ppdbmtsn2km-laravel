<?php

namespace App\Livewire\Panitia;

use App\Models\TesPsikologiPendaftar;
use App\Models\Pendaftar;
use Livewire\Component;
use Livewire\WithPagination;

class TesPsikologi extends Component
{
    use WithPagination;

    public $search = '';
    public $filterStatus = '';
    public $detailModal = false;
    public $selectedData = null;
    public $catatanVerifikasi = '';

    // Message modal
    public $showMessageModal = false;
    public $messagingId = null;
    public $messagingPendaftar = null;
    public $messageContent = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openDetail($id)
    {
        $this->selectedData = TesPsikologiPendaftar::with(['pendaftar', 'opsi', 'bank', 'verifiedBy'])->find($id);
        $this->catatanVerifikasi = $this->selectedData->catatan_verifikasi ?? '';
        $this->detailModal = true;
    }

    public function closeDetail()
    {
        $this->detailModal = false;
        $this->selectedData = null;
        $this->catatanVerifikasi = '';
    }

    public function setujui($id)
    {
        $data = TesPsikologiPendaftar::find($id);
        $data->update([
            'status_verifikasi' => 'disetujui',
            'catatan_verifikasi' => $this->catatanVerifikasi,
            'tanggal_verifikasi' => now(),
            'verified_by' => auth()->id()
        ]);

        session()->flash('message', 'Bukti transfer berhasil disetujui');
        $this->closeDetail();
    }

    public function tolak($id)
    {
        $data = TesPsikologiPendaftar::find($id);
        $data->update([
            'status_verifikasi' => 'ditolak',
            'catatan_verifikasi' => $this->catatanVerifikasi,
            'tanggal_verifikasi' => now(),
            'verified_by' => auth()->id()
        ]);

        session()->flash('message', 'Bukti transfer ditolak');
        $this->closeDetail();
    }

    // Message Methods
    public function sendEmail($id)
    {
        $tesPsikologi = TesPsikologiPendaftar::with('pendaftar')->findOrFail($id);

        // TODO: Implement email sending logic here
        // Example: Mail::to($tesPsikologi->pendaftar->user->email)->send(new TesPsikologiEmail($tesPsikologi));

        // Save to message history
        \App\Models\MessageHistory::create([
            'pendaftar_id' => $tesPsikologi->pendaftar_id,
            'sender_id' => auth()->id(),
            'message_type' => 'email',
            'context' => 'tes_psikologi',
            'message_content' => 'Email dikirim untuk tes psikologi',
            'sent_at' => now(),
        ]);

        session()->flash('message', 'Email berhasil dikirim ke ' . $tesPsikologi->pendaftar->nama_lengkap);
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
            'sender_id' => auth()->id(),
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
        $query = TesPsikologiPendaftar::with(['pendaftar', 'opsi', 'bank'])
            ->whereHas('pendaftar', function($q) {
                $q->where('nama_lengkap', 'like', '%'.$this->search.'%')
                  ->orWhere('nisn', 'like', '%'.$this->search.'%');
            });

        if ($this->filterStatus) {
            $query->where('status_verifikasi', $this->filterStatus);
        }

        $data = $query->latest()->paginate(20);

        return view('livewire.panitia.tes-psikologi', [
            'data' => $data
        ])->layout('components.layouts.dashboard', [
            'role' => 'panitia',
            'pageTitle' => 'Data Tes Psikologi',
            'pageDescription' => 'Verifikasi bukti transfer tes psikologi pendaftar'
        ]);
    }
}
