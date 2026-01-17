<?php

namespace App\Livewire\Panitia;

use App\Models\Pendaftar;
use App\Models\JalurPendaftaran;
use App\Traits\WithSorting;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Kelulusan extends Component
{
    use WithPagination, WithSorting;

    public $search = '';
    public $filterJalur = '';
    public $filterStatus = '';

    public function mount()
    {
        $user = Auth::user();
        if (!$user || !in_array($user->role, ['panitia_pendaftaran', 'panitia_daftarulang', 'panitia'])) {
            return redirect()->to('/login/admin');
        }
    }

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
        $baseQuery = Pendaftar::query()->with(['jalurPendaftaran', 'tesPsikologiPendaftar']);
        $query = clone $baseQuery;

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

        $statsQuery = clone $query;

        // Stats untuk Status Kelulusan
        $stats = [
            'total' => $statsQuery->count(),
            'menunggu' => (clone $statsQuery)->where('status_pendaftaran', 'menunggu_verifikasi')->count(),
            'lulus' => (clone $statsQuery)->where('status_pendaftaran', 'data_disetujui')->count(),
            'cadangan' => (clone $statsQuery)->where('status_pendaftaran', 'lengkapi_data')->count(),
            'tidak_lulus' => (clone $statsQuery)->where('status_pendaftaran', 'data_ditolak')->count(),
        ];

        $pendaftar = $this->applySorting($query)->paginate(10);
        $jalurPendaftaran = JalurPendaftaran::orderBy('nama')->get();

        return view('livewire.panitia.kelulusan', [
            'pendaftar' => $pendaftar,
            'jalurPendaftaran' => $jalurPendaftaran,
            'stats' => $stats
        ])->layout('components.layouts.dashboard', [
            'role' => Auth::user()->role,
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
}
