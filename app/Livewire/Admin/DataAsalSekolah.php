<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class DataAsalSekolah extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortBy = 'nama_sekolah';
    public $sortDirection = 'asc';

    // Modal detail
    public $showDetailModal = false;
    public $selectedSekolah = null;
    public $pendaftarList = [];

    public function mount()
    {
        // Check if user is admin
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            return redirect()->to('/login/admin');
        }
    }

    public function render()
    {
        // Langsung ambil data dari tabel pendaftar, bukan dari tabel asal_sekolah
        $query = \App\Models\Pendaftar::select(
            'asal_sekolah as nama_sekolah',
            'jenis_sekolah_asal',
            'nsm_sekolah_asal as nsm',
            'npsn_sekolah_asal as npsn',
            \Illuminate\Support\Facades\DB::raw('COUNT(*) as pendaftar_count')
        )
        ->whereNotNull('asal_sekolah')
        ->where('asal_sekolah', '!=', '')
        ->groupBy('asal_sekolah', 'jenis_sekolah_asal', 'nsm_sekolah_asal', 'npsn_sekolah_asal');

        if ($this->search) {
            $query->where(function($q) {
                $q->where('asal_sekolah', 'ilike', '%' . $this->search . '%')
                  ->orWhere('npsn_sekolah_asal', 'ilike', '%' . $this->search . '%')
                  ->orWhere('nsm_sekolah_asal', 'ilike', '%' . $this->search . '%');
            });
        }

        // Map sortBy fields
        $sortField = $this->sortBy;
        if ($sortField === 'nama_sekolah') {
            $sortField = 'asal_sekolah';
        } elseif ($sortField === 'npsn') {
            $sortField = 'npsn_sekolah_asal';
        }

        $asalSekolah = $query->orderBy($sortField, $this->sortDirection)
                             ->paginate($this->perPage);

        return view('livewire.admin.data-asal-sekolah', [
            'asalSekolah' => $asalSekolah
        ])->layout('components.layouts.dashboard', [
            'role' => 'admin',
            'pageTitle' => 'Data Asal Sekolah',
            'pageDescription' => 'Data asal sekolah dari pendaftar'
        ]);
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function showDetail($namaSekolah)
    {
        $this->selectedSekolah = $namaSekolah;

        // Load daftar pendaftar dari sekolah tersebut
        $this->pendaftarList = \App\Models\Pendaftar::where('asal_sekolah', $namaSekolah)
            ->orderBy('nama_lengkap', 'asc')
            ->get()
            ->map(function ($pendaftar) {
                return [
                    'id' => $pendaftar->id,
                    'nama' => $pendaftar->nama_lengkap,
                    'nisn' => $pendaftar->nisn,
                    'jalur' => $pendaftar->jalurPendaftaran->nama ?? '-',
                    'status' => $pendaftar->status_pendaftaran,
                    'status_label' => $this->getStatusLabel($pendaftar->status_pendaftaran),
                    'status_color' => $this->getStatusColor($pendaftar->status_pendaftaran),
                ];
            });

        $this->showDetailModal = true;
    }

    public function closeModal()
    {
        $this->showDetailModal = false;
        $this->selectedSekolah = null;
        $this->pendaftarList = [];
    }

    private function getStatusLabel($status)
    {
        $labels = [
            'menunggu' => 'Menunggu Verifikasi',
            'lulus' => 'Diterima',
            'tidak_lulus' => 'Tidak Lulus',
            'cadangan' => 'Cadangan',
        ];

        return $labels[$status] ?? $status;
    }

    private function getStatusColor($status)
    {
        $colors = [
            'menunggu' => 'yellow',
            'lulus' => 'green',
            'tidak_lulus' => 'red',
            'cadangan' => 'blue',
        ];

        return $colors[$status] ?? 'gray';
    }
}
