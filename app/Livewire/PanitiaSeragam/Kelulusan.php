<?php

namespace App\Livewire\PanitiaSeragam;

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
        if (!$user || $user->role !== 'panitia_seragam') {
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
                    $subQuery->where('nama_lengkap', 'like', '%' . $this->search . '%')
                        ->orWhere('nisn', 'like', '%' . $this->search . '%')
                        ->orWhere('nomor_pendaftaran', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterJalur, function($q) {
                $q->where('jalur_pendaftaran_id', $this->filterJalur);
            })
            ->when($this->filterStatus, function($q) {
                $q->where('status_pendaftaran', $this->filterStatus);
            });

            // Optimize stats queries - use single query with groupBy
            $statsData = Pendaftar::selectRaw('status_pendaftaran, COUNT(*) as count')
                ->when($this->search, function($q) {
                    $q->where(function($subQuery) {
                        $subQuery->where('nama_lengkap', 'like', '%' . $this->search . '%')
                            ->orWhere('nisn', 'like', '%' . $this->search . '%')
                            ->orWhere('nomor_pendaftaran', 'like', '%' . $this->search . '%');
                    });
                })
                ->when($this->filterJalur, function($q) {
                    $q->where('jalur_pendaftaran_id', $this->filterJalur);
                })
                ->groupBy('status_pendaftaran')
                ->pluck('count', 'status_pendaftaran');

            $stats = [
                'total' => $statsData->sum(),
                'menunggu' => $statsData->get('menunggu_verifikasi', 0),
                'lulus' => $statsData->get('data_disetujui', 0),
                'cadangan' => $statsData->get('lengkapi_data', 0),
                'tidak_lulus' => $statsData->get('data_ditolak', 0),
            ];

            $pendaftar = $this->applySorting($query)->paginate(10);
            $jalurPendaftaran = JalurPendaftaran::orderBy('nama')->get();

            return view('livewire.panitia-seragam.kelulusan', [
                'pendaftar' => $pendaftar,
                'jalurPendaftaran' => $jalurPendaftaran,
                'stats' => $stats
            ])->layout('components.layouts.dashboard', [
                'role' => 'panitia_seragam',
                'pageTitle' => 'Manajemen Kelulusan',
                'pageDescription' => 'Kelola status kelulusan pendaftar'
            ]);
        } catch (\Exception $e) {
            \Log::error('Kelulusan render error: ' . $e->getMessage());

            return view('livewire.panitia-seragam.kelulusan', [
                'pendaftar' => collect(),
                'jalurPendaftaran' => collect(),
                'stats' => [
                    'total' => 0,
                    'menunggu' => 0,
                    'lulus' => 0,
                    'cadangan' => 0,
                    'tidak_lulus' => 0,
                ]
            ])->layout('components.layouts.dashboard', [
                'role' => 'panitia_seragam',
                'pageTitle' => 'Manajemen Kelulusan',
                'pageDescription' => 'Kelola status kelulusan pendaftar'
            ]);
        }
    }
}
