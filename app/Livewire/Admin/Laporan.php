<?php

namespace App\Livewire\Admin;

use App\Models\Pendaftar;
use App\Models\JalurPendaftaran;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Laporan extends Component
{
    public $activeTab = 'pendaftaran'; // pendaftaran, data_lengkap, daftar_ulang, seragam
    public $filterJalur = '';
    public $filterStatus = '';
    public $filterJenisKelamin = '';
    public $tanggalMulai = '';
    public $tanggalAkhir = '';

    public function mount()
    {
        // Check if user is admin
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            return redirect()->to('/login/admin');
        }

        // Set default date range
        $this->tanggalMulai = now()->startOfMonth()->format('Y-m-d');
        $this->tanggalAkhir = now()->endOfMonth()->format('Y-m-d');
    }

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
        // Reset filters when switching tabs
        $this->filterJalur = '';
        $this->filterStatus = '';
    }

    public function render()
    {
        // Get statistics
        $query = Pendaftar::query()
            ->when($this->filterJalur, function ($q) {
                $q->where('jalur_pendaftaran_id', $this->filterJalur);
            })
            ->when($this->filterStatus, function ($q) {
                $q->where('status_pendaftaran', $this->filterStatus);
            })
            ->when($this->tanggalMulai, function ($q) {
                $q->whereDate('tanggal_daftar', '>=', $this->tanggalMulai);
            })
            ->when($this->tanggalAkhir, function ($q) {
                $q->whereDate('tanggal_daftar', '<=', $this->tanggalAkhir);
            });

        $totalPendaftar = $query->count();

        $stats = [
            'total' => $totalPendaftar,
            'menunggu' => (clone $query)->where('status_pendaftaran', 'menunggu_verifikasi')->count(),
            'lulus' => (clone $query)->where('status_pendaftaran', 'data_disetujui')->count(),
            'tidak_lulus' => (clone $query)->where('status_pendaftaran', 'data_ditolak')->count(),
            'cadangan' => (clone $query)->where('status_pendaftaran', 'lengkapi_data')->count(),
        ];

        // Get per jalur statistics
        $jalurStats = JalurPendaftaran::withCount(['pendaftar' => function ($q) {
            $q->when($this->filterStatus, function ($query) {
                $query->where('status_pendaftaran', $this->filterStatus);
            })
            ->when($this->tanggalMulai, function ($query) {
                $query->whereDate('tanggal_daftar', '>=', $this->tanggalMulai);
            })
            ->when($this->tanggalAkhir, function ($query) {
                $query->whereDate('tanggal_daftar', '<=', $this->tanggalAkhir);
            });
        }])->get();

        $jalurPendaftaran = JalurPendaftaran::aktif()->ordered()->get();

        return view('livewire.admin.laporan', compact('stats', 'jalurStats', 'jalurPendaftaran'))
            ->layout('components.layouts.dashboard', [
                'role' => 'admin',
                'pageTitle' => 'Laporan PPDB',
                'pageDescription' => 'Laporan dan statistik pendaftaran siswa baru'
            ]);
    }

    public function exportExcel()
    {
        return redirect()->route('export.excel', [
            'jalur' => $this->filterJalur,
            'status' => $this->filterStatus,
            'start_date' => $this->tanggalMulai,
            'end_date' => $this->tanggalAkhir,
        ]);
    }

    public function exportPdf()
    {
        return redirect()->route('export.pdf', [
            'jalur' => $this->filterJalur,
            'status' => $this->filterStatus,
            'start_date' => $this->tanggalMulai,
            'end_date' => $this->tanggalAkhir,
        ]);
    }

    public function exportDataLengkap()
    {
        // Export complete data of applicants
        session()->flash('message', 'Export Data Lengkap sedang diproses...');

        // TODO: Implement actual export logic
        // This would typically generate an Excel file with all applicant data
    }

    public function exportDaftarUlang()
    {
        // Export re-registration data
        session()->flash('message', 'Export Daftar Ulang sedang diproses...');

        // TODO: Implement actual export logic
        // This would typically generate an Excel file with re-registration data
    }

    public function exportSeragam()
    {
        // Export uniform size data
        session()->flash('message', 'Export Data Seragam sedang diproses...');

        // TODO: Implement actual export logic
        // This would typically generate an Excel file with uniform sizes
    }
}
