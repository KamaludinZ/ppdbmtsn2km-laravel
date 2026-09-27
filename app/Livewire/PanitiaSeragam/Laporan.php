<?php

namespace App\Livewire\PanitiaSeragam;

use Livewire\Component;
use Livewire\Attributes\Url;
use App\Models\Pendaftar;
use App\Models\DaftarUlang;
use App\Models\Seragam;
use App\Models\JalurPendaftaran;
use Illuminate\Support\Facades\Auth;
use App\Exports\SeragamExport;
use App\Exports\DaftarUlangSeragamExport;
use Maatwebsite\Excel\Facades\Excel;

class Laporan extends Component
{
    public $activeTab = 'daftar_ulang_seragam';
    public $filterJalur = '';
    public $filterStatus = '';
    public $filterStatusSeragam = '';
    public $filterJenisKelamin = '';

    /**
     * Override queryString untuk exclude activeTab dari URL
     */
    protected function queryString()
    {
        return [];
    }

    public function mount()
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'panitia_seragam') {
            return redirect()->to('/login/admin');
        }
    }

    public function switchTab($tab)
    {
        if (in_array($tab, ['daftar_ulang_seragam', 'seragam'])) {
            $this->activeTab = $tab;
        }
    }

    private function getSeragamStats()
    {
        return \App\Support\RekapDaftarUlang::statsTab('seragam', ['jalur' => $this->filterJalur, 'jenis_kelamin' => $this->filterJenisKelamin]);
    }

    private function getUkuranStats()
    {
        return \App\Support\RekapDaftarUlang::statsTab('ukuran', ['jalur' => $this->filterJalur, 'jenis_kelamin' => $this->filterJenisKelamin]);
    }

    public function exportSeragam()
    {
        // Export uniform size data
        $fileName = 'data_seragam_' . date('Y-m-d_His') . '.xlsx';

        return Excel::download(
            new SeragamExport(
                $this->filterStatusSeragam,
                null,
                $this->filterJenisKelamin,
                $this->filterJalur
            ),
            $fileName
        );
    }

    public function exportDaftarUlangSeragam()
    {
        // Export re-registration uniform data
        $fileName = 'data_daftar_ulang_seragam_' . date('Y-m-d_His') . '.xlsx';

        return Excel::download(
            new DaftarUlangSeragamExport(
                null,
                null
            ),
            $fileName
        );
    }

    public function render()
    {
        $jalurPendaftaran = JalurPendaftaran::aktif()->ordered()->get();
        $seragamStats = $this->getSeragamStats();
        $ukuranStats = $this->getUkuranStats();

        return view('livewire.panitia-seragam.laporan', [
            'jalurPendaftaran' => $jalurPendaftaran,
            'seragamStats' => $seragamStats,
            'ukuranStats' => $ukuranStats,
        ])->layout('components.layouts.dashboard', [
            'role' => 'panitia_seragam',
            'pageTitle' => 'Laporan Seragam',
            'pageDescription' => 'Laporan dan statistik seragam siswa baru'
        ]);
    }
}
