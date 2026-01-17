<?php

namespace App\Livewire\Admin;

use App\Models\DashboardStats;
use App\Models\JalurPendaftaran;
use App\Models\JalurStats;
use App\Models\PanitiaStats;
use App\Models\Pendaftar;
use App\Models\RecentPendaftar;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Dashboard extends Component
{
    public $stats = [];
    public $recentPendaftar = [];
    public $jalurStats = [];
    public $emisCompletenessStats = [];

    public function mount()
    {
        // Check if user is admin
        $user = Auth::user();

        if (!$user || $user->role !== 'admin') {
            return redirect()->to('/login/admin');
        }

        $this->loadStats();
        $this->loadRecentPendaftar();
        $this->loadJalurStats();
        $this->loadEmisCompletenessStats();
    }

    public function loadStats()
    {
        // Load data from materialized views for better performance
        $dashboardStats = DashboardStats::getStats();
        $panitiaStats = PanitiaStats::getStats();

        $totalPendaftar = $dashboardStats['total_pendaftar'] ?? 0;
        $menungguVerifikasi = $dashboardStats['menunggu_verifikasi'] ?? 0;
        $diterima = $dashboardStats['data_disetujui'] ?? 0; // Use data_disetujui instead of diterima
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

    public function loadEmisCompletenessStats()
    {
        // Cache results for 30 minutes to prevent timeout on large datasets
        // Cache key includes total count to auto-invalidate when data changes significantly
        $cacheKey = 'dashboard_emis_stats_' . Pendaftar::count();

        $this->emisCompletenessStats = Cache::remember($cacheKey, 1800, function () {
            return $this->calculateEmisCompletenessStats();
        });
    }

    /**
     * Calculate EMIS completeness statistics (cached to prevent N+1 queries)
     * Uses chunking to avoid memory issues with large datasets
     */
    private function calculateEmisCompletenessStats(): array
    {
        $totalPendaftar = Pendaftar::count();

        if ($totalPendaftar === 0) {
            return [
                'total_pendaftar' => 0,
                'lengkap' => 0,
                'sebagian' => 0,
                'kurang_lengkap' => 0,
                'belum_diisi' => 0,
                'lengkap_percentage' => 0,
                'average_completion' => 0,
                'section_averages' => [],
            ];
        }

        // Count by completeness categories
        $lengkap = 0; // >= 80%
        $sebagian = 0; // 50-79%
        $kurangLengkap = 0; // 1-49%
        $belumDiisi = 0; // 0%

        $totalCompletion = 0;
        $sectionTotals = [];

        // Use chunking to avoid memory issues and timeout
        // Process 100 records at a time instead of loading all into memory
        Pendaftar::chunk(100, function ($pendaftarChunk) use (&$lengkap, &$sebagian, &$kurangLengkap, &$belumDiisi, &$totalCompletion, &$sectionTotals) {
            foreach ($pendaftarChunk as $pendaftar) {
                $percentage = $pendaftar->getEmisCompletenessPercentage();
                $details = $pendaftar->getEmisCompletenessDetails();

                $totalCompletion += $percentage;

                // Accumulate section totals
                foreach ($details['sections'] as $key => $section) {
                    if (!isset($sectionTotals[$key])) {
                        $sectionTotals[$key] = [
                            'label' => $section['label'],
                            'total_percentage' => 0,
                            'count' => 0,
                        ];
                    }
                    $sectionTotals[$key]['total_percentage'] += $section['percentage'];
                    $sectionTotals[$key]['count']++;
                }

                // Categorize
                if ($percentage >= 80) {
                    $lengkap++;
                } elseif ($percentage >= 50) {
                    $sebagian++;
                } elseif ($percentage > 0) {
                    $kurangLengkap++;
                } else {
                    $belumDiisi++;
                }
            }
        });

        // Calculate averages
        $averageCompletion = $totalPendaftar > 0 ? round($totalCompletion / $totalPendaftar) : 0;
        $lengkapPercentage = $totalPendaftar > 0 ? round(($lengkap / $totalPendaftar) * 100) : 0;

        // Calculate section averages
        $sectionAverages = [];
        foreach ($sectionTotals as $key => $section) {
            $sectionAverages[$key] = [
                'label' => $section['label'],
                'average' => $section['count'] > 0 ? round($section['total_percentage'] / $section['count']) : 0,
            ];
        }

        return [
            'total_pendaftar' => $totalPendaftar,
            'lengkap' => $lengkap,
            'sebagian' => $sebagian,
            'kurang_lengkap' => $kurangLengkap,
            'belum_diisi' => $belumDiisi,
            'lengkap_percentage' => $lengkapPercentage,
            'average_completion' => $averageCompletion,
            'section_averages' => $sectionAverages,
        ];
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
        return view('livewire.admin.dashboard')
            ->layout('components.layouts.dashboard', [
                'role' => 'admin',
                'pageTitle' => 'Dashboard Admin',
                'pageDescription' => 'Selamat datang di dashboard admin PPDB MTsN 2 Kota Malang'
            ]);
    }
}
