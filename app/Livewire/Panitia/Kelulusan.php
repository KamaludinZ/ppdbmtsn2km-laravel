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
        if (!$user || !in_array($user->role, ['panitia_pendaftaran', 'panitia_daftarulang', 'panitia_tes_psikologi', 'panitia_seragam'])) {
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
        try {
            $query = Pendaftar::query()->with(['jalurPendaftaran', 'tesPsikologiPendaftar']);

            // Apply filters
            $query->when($this->search, function($q) {
                $q->where(function($subQuery) {
                    $subQuery->where('nama_lengkap', 'ilike', '%' . $this->search . '%')
                        ->orWhere('nisn', 'ilike', '%' . $this->search . '%')
                        ->orWhere('nomor_pendaftaran', 'ilike', '%' . $this->search . '%');
                });
            })
            ->when($this->filterJalur, function($q) {
                $q->where('jalur_pendaftaran_id', $this->filterJalur);
            })
            ->when($this->filterStatus, function($q) {
                $q->where('status_pendaftaran', $this->filterStatus);
            });

            // Calculate statistics WITHOUT ANY FILTERS
            // Stats should show TOTAL OVERALL DATA regardless of ANY filter (search, jalur, status)
            // This gives accurate overview of ALL data in system
            $statsData = Pendaftar::selectRaw('status_pendaftaran, COUNT(*) as count')
                // NOTE: Do NOT apply any filters here (no search, no jalur, no status)
                // Stats must show TOTAL OVERALL for accurate overview
                ->groupBy('status_pendaftaran')
                ->pluck('count', 'status_pendaftaran');

            $stats = [
                'total' => $statsData->sum(),
                'data_disetujui' => $statsData->get('data_disetujui', 0) + $statsData->get('lulus', 0) + $statsData->get('diterima', 0) + $statsData->get('tidak_lulus', 0) + $statsData->get('cadangan', 0) + $statsData->get('ditolak', 0),
                'lulus' => $statsData->get('lulus', 0) + $statsData->get('diterima', 0),
                'cadangan' => $statsData->get('cadangan', 0),
                'tidak_lulus' => $statsData->get('tidak_lulus', 0) + $statsData->get('ditolak', 0) + $statsData->get('data_ditolak', 0),
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
        } catch (\Exception $e) {
            \Log::error('Kelulusan render error: ' . $e->getMessage());

            return view('livewire.panitia.kelulusan', [
                'pendaftar' => collect(),
                'jalurPendaftaran' => collect(),
                'stats' => [
                    'total' => 0,
                    'data_disetujui' => 0,
                    'lulus' => 0,
                    'cadangan' => 0,
                    'tidak_lulus' => 0,
                ]
            ])->layout('components.layouts.dashboard', [
                'role' => Auth::user()->role,
                'pageTitle' => 'Manajemen Kelulusan',
                'pageDescription' => 'Kelola status kelulusan pendaftar'
            ]);
        }
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
                    $q->where('nama_lengkap', 'ilike', '%' . $this->search . '%')
                      ->orWhere('nisn', 'ilike', '%' . $this->search . '%')
                      ->orWhere('nomor_pendaftaran', 'ilike', '%' . $this->search . '%');
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
