<?php

namespace App\Livewire\Panitia;

use App\Models\DashboardStats;
use App\Models\JalurPendaftaran;
use App\Models\JalurStats;
use App\Models\PanitiaStats;
use App\Models\Pendaftar;
use App\Models\RecentPendaftar;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Dashboard extends Component
{
    public $stats = [];
    public $recentPendaftar = [];
    public $jalurStats = [];

    public function mount()
    {
        // Check if user is panitia
        $user = Auth::user();

        if (!$user || !in_array($user->role, ['panitia_pendaftaran', 'panitia_daftarulang', 'panitia'])) {
            return redirect()->to('/login/admin');
        }

        $this->loadStats();
        $this->loadRecentPendaftar();
        $this->loadJalurStats();
    }

    public function loadStats()
    {
        // Load data from materialized views for better performance
        $dashboardStats = DashboardStats::getStats();
        $panitiaStats = PanitiaStats::getStats();

        $totalPendaftar = $dashboardStats['total_pendaftar'] ?? 0;
        $menungguVerifikasi = $dashboardStats['menunggu_verifikasi'] ?? 0;
        $diterima = $dashboardStats['diterima'] ?? 0;
        $panitiaAktif = $panitiaStats['panitia_aktif'] ?? 0;

        // Calculate percentage vs last year (mocked for now)
        $lastYearTotal = 1084; // This should come from last year's data
        $increasePercentage = $lastYearTotal > 0 ? round((($totalPendaftar - $lastYearTotal) / $lastYearTotal) * 100, 1) : 0;

        $diterimaPercentage = $totalPendaftar > 0 ? round(($diterima / $totalPendaftar) * 100, 1) : 0;

        $this->stats = [
            'total_pendaftar' => $totalPendaftar,
            'total_pendaftar_increase' => $increasePercentage,
            'menunggu_verifikasi' => $menungguVerifikasi,
            'diterima' => $diterima,
            'diterima_percentage' => $diterimaPercentage,
            'panitia_aktif' => $panitiaAktif,
        ];
    }

    public function loadRecentPendaftar()
    {
        // Load from materialized view for better performance
        $recentData = RecentPendaftar::getRecent(5);

        $this->recentPendaftar = $recentData->map(function ($pendaftar) {
            return [
                'nomor_pendaftaran' => $pendaftar->nomor_pendaftaran ?? '-',
                'nama' => $pendaftar->nama_lengkap,
                'nisn' => $pendaftar->nisn,
                'jalur' => $pendaftar->jalur_nama ?? '-',
                'jalur_warna' => $pendaftar->jalur_warna ?? 'blue',
                'status' => $pendaftar->status_pendaftaran,
                'status_label' => $this->getStatusLabel($pendaftar->status_pendaftaran),
                'status_color' => $this->getStatusColor($pendaftar->status_pendaftaran),
                'tanggal' => \Carbon\Carbon::parse($pendaftar->tanggal_daftar)->format('d M Y'),
                'initials' => $this->getInitials($pendaftar->nama_lengkap),
            ];
        });
    }

    public function loadJalurStats()
    {
        // Load from materialized view for better performance
        $dashboardStats = DashboardStats::getStats();
        $totalPendaftar = $dashboardStats['total_pendaftar'] ?? 0;

        $jalurStatsData = JalurStats::getAll();

        $this->jalurStats = $jalurStatsData->map(function ($jalur) use ($totalPendaftar) {
            $percentage = $totalPendaftar > 0 ? round(($jalur->pendaftar_count / $totalPendaftar) * 100) : 0;
            return [
                'nama' => $jalur->nama,
                'count' => $jalur->pendaftar_count,
                'percentage' => $percentage,
                'warna' => $jalur->warna,
            ];
        });
    }

    private function getInitials($name)
    {
        $words = explode(' ', $name);
        $initials = '';
        foreach (array_slice($words, 0, 2) as $word) {
            $initials .= strtoupper(substr($word, 0, 1));
        }
        return $initials;
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

    private function getColorClasses($color)
    {
        $classes = [
            'blue' => 'bg-blue-600',
            'emerald' => 'bg-emerald-600',
            'purple' => 'bg-purple-600',
            'yellow' => 'bg-yellow-600',
            'red' => 'bg-red-600',
            'orange' => 'bg-orange-600',
        ];

        return $classes[$color] ?? 'bg-blue-600';
    }

    public function render()
    {
        $user = Auth::user();
        $roleLabel = $user->role === 'panitia_pendaftaran' ? 'Panitia Pendaftaran' :
                     ($user->role === 'panitia_daftarulang' ? 'Panitia Daftar Ulang' : 'Panitia');

        return view('livewire.panitia.dashboard')
            ->layout('components.layouts.dashboard', [
                'role' => $user->role,
                'pageTitle' => 'Dashboard ' . $roleLabel,
                'pageDescription' => 'Selamat datang di dashboard PPDB MTsN 2 Kota Malang'
            ]);
    }
}
