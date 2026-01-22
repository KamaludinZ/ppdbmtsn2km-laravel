<?php

namespace App\Livewire\Panitia;

use App\Models\Pendaftar;
use App\Models\JalurPendaftaran;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Exports\PendaftarExport;
use App\Exports\DaftarUlangExport;
use App\Exports\SeragamExport;
use Maatwebsite\Excel\Facades\Excel;

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
        // Check if user is panitia
        $user = Auth::user();
        if (!$user || !in_array($user->role, ['panitia_pendaftaran', 'panitia_daftarulang', 'panitia'])) {
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
            'disetujui' => (clone $query)->where('status_pendaftaran', 'data_disetujui')->count(),
            'lulus' => (clone $query)->where('status_pendaftaran', 'lulus')->count(),
            'tidak_lulus' => (clone $query)->where('status_pendaftaran', 'tidak_lulus')->count(),
            'cadangan' => (clone $query)->where('status_pendaftaran', 'cadangan')->count(),
            'diterima' => (clone $query)->where('status_pendaftaran', 'diterima')->count(),
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

        return view('livewire.panitia.laporan', compact('stats', 'jalurStats', 'jalurPendaftaran'))
            ->layout('components.layouts.dashboard', [
                'role' => Auth::user()->role,
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
        $fileName = 'data_pendaftar_lengkap_' . date('Y-m-d_His') . '.xlsx';

        return Excel::download(
            new PendaftarExport(
                $this->filterJalur,
                $this->filterStatus,
                null
            ),
            $fileName
        );
    }

    public function exportDaftarUlang()
    {
        // Export re-registration data
        $fileName = 'data_daftar_ulang_' . date('Y-m-d_His') . '.xlsx';

        return Excel::download(
            new DaftarUlangExport(
                $this->filterStatus,
                null
            ),
            $fileName
        );
    }

    public function exportSeragam()
    {
        // Export uniform size data
        $fileName = 'data_seragam_' . date('Y-m-d_His') . '.xlsx';

        return Excel::download(
            new SeragamExport(
                $this->filterStatus,
                null
            ),
            $fileName
        );
    }
}
