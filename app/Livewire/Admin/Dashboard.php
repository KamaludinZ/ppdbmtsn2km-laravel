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
    public $jalurDetailStats = []; // Data statistik detail per jalur

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
        $this->loadJalurDetailStats(); // Load statistik detail per jalur
    }

    public function loadStats()
    {
        // Query langsung dari database untuk akurasi real-time
        $totalPendaftar = Pendaftar::count();

        // Hitung berdasarkan status_pendaftaran
        $menungguVerifikasi = Pendaftar::where('status_pendaftaran', 'menunggu_verifikasi')->count();
        $lengkapiData = Pendaftar::where('status_pendaftaran', 'lengkapi_data')->count();
        $perbaikiData = Pendaftar::where('status_pendaftaran', 'perbaiki_data')->count();
        $dataDisetujui = Pendaftar::where('status_pendaftaran', 'data_disetujui')->count();
        $lulus = Pendaftar::where('status_pendaftaran', 'lulus')->count();
        $tidakLulus = Pendaftar::where('status_pendaftaran', 'tidak_lulus')->count();
        $cadangan = Pendaftar::where('status_pendaftaran', 'cadangan')->count();
        $dataDitolak = Pendaftar::where('status_pendaftaran', 'data_ditolak')->count();

        // User aktif (sedang login dalam 15 menit terakhir)
        $userAktif = DB::table('sessions')
            ->whereNotNull('user_id')
            ->where('last_activity', '>=', now()->subMinutes(15)->timestamp)
            ->distinct()
            ->count('user_id');

        // Calculate percentage vs last year (mocked for now)
        $lastYearTotal = 1084; // This should come from last year's data
        $increasePercentage = $lastYearTotal > 0 ? round((($totalPendaftar - $lastYearTotal) / $lastYearTotal) * 100, 1) : 0;

        $dataDisetujuiPercentage = $totalPendaftar > 0 ? round(($dataDisetujui / $totalPendaftar) * 100, 1) : 0;
        $lengkapiDataPercentage = $totalPendaftar > 0 ? round(($lengkapiData / $totalPendaftar) * 100, 1) : 0;
        $perbaikiDataPercentage = $totalPendaftar > 0 ? round(($perbaikiData / $totalPendaftar) * 100, 1) : 0;

        $this->stats = [
            'total_pendaftar' => $totalPendaftar,
            'total_pendaftar_increase' => $increasePercentage,
            'menunggu_verifikasi' => $menungguVerifikasi,
            'lengkapi_data' => $lengkapiData,
            'lengkapi_data_percentage' => $lengkapiDataPercentage,
            'perbaiki_data' => $perbaikiData,
            'perbaiki_data_percentage' => $perbaikiDataPercentage,
            'data_disetujui' => $dataDisetujui,
            'data_disetujui_percentage' => $dataDisetujuiPercentage,
            'lulus' => $lulus,
            'tidak_lulus' => $tidakLulus,
            'cadangan' => $cadangan,
            'data_ditolak' => $dataDitolak,
            'user_aktif' => $userAktif,
        ];
    }

    public function loadRecentPendaftar()
    {
        // Load pendaftar yang menunggu verifikasi atau baru mendaftar hari ini
        $recentData = Pendaftar::with('jalur')
            ->where(function($query) {
                $query->where('status_pendaftaran', 'menunggu_verifikasi')
                      ->orWhereDate('tanggal_daftar', now()->toDateString());
            })
            ->orderBy('tanggal_daftar', 'desc')
            ->take(5)
            ->get();

        $this->recentPendaftar = $recentData->map(function ($pendaftar) {
            return [
                'nomor_pendaftaran' => $pendaftar->nomor_pendaftaran ?? '-',
                'nama' => $pendaftar->nama_lengkap,
                'nisn' => $pendaftar->nisn ?? '-',
                'jalur' => $pendaftar->jalur->nama ?? '-',
                'jalur_warna' => $pendaftar->jalur->warna ?? 'blue',
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
        // Hanya load jalur yang aktif dengan jumlah pendaftar
        $jalurAktif = JalurPendaftaran::where('aktif', true)
            ->orderBy('urutan', 'asc')
            ->get();

        $totalPendaftar = Pendaftar::count();

        $this->jalurStats = $jalurAktif->map(function ($jalur) use ($totalPendaftar) {
            $count = $jalur->pendaftar()->count();
            $percentage = $totalPendaftar > 0 ? round(($count / $totalPendaftar) * 100) : 0;

            return [
                'nama' => $jalur->nama,
                'count' => $count,
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

    public function loadJalurDetailStats()
    {
        // Load statistik detail per jalur - tampilkan semua jalur aktif
        $jalurAktif = JalurPendaftaran::where('aktif', true)
            ->orderBy('urutan', 'asc')
            ->get();

        $this->jalurDetailStats = $jalurAktif->map(function ($jalur) {
            // Query semua pendaftar di jalur ini
            $allPendaftar = $jalur->pendaftar();

            // Total pendaftar
            $total = $allPendaftar->count();

            // Lulus tes
            $lulusTes = (clone $allPendaftar)->whereIn('status_pendaftaran', ['lulus', 'diterima'])->count();

            // Tidak lulus tes
            $tidakLulusTes = (clone $allPendaftar)->where('status_pendaftaran', 'tidak_lulus')->count();

            // Query pendaftar yang lulus untuk gender dan persetujuan
            $pendaftarLulus = $jalur->pendaftar()
                ->whereIn('status_pendaftaran', ['lulus', 'diterima']);

            // Jumlah laki-laki (yang lulus tes) - 'L' adalah nilai di database
            $lakiLaki = (clone $pendaftarLulus)->where('jenis_kelamin', 'L')->count();

            // Jumlah perempuan (yang lulus tes) - 'P' adalah nilai di database
            $perempuan = (clone $pendaftarLulus)->where('jenis_kelamin', 'P')->count();

            // Setuju daftar ulang
            $setuju = (clone $pendaftarLulus)->where('status_persetujuan_daftar_ulang', 'setuju')->count();

            // Tidak setuju daftar ulang
            $tidakSetuju = (clone $pendaftarLulus)->where('status_persetujuan_daftar_ulang', 'tidak_setuju')->count();

            return [
                'nama' => $jalur->nama,
                'warna' => $jalur->warna,
                'total' => $total,
                'lulus_tes' => $lulusTes,
                'tidak_lulus_tes' => $tidakLulusTes,
                'laki_laki' => $lakiLaki,
                'perempuan' => $perempuan,
                'setuju' => $setuju,
                'tidak_setuju' => $tidakSetuju,
            ];
        });
        // Tidak ada filter - tampilkan semua jalur aktif
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
            'menunggu_verifikasi' => 'Menunggu Verifikasi',
            'lengkapi_data' => 'Lengkapi Data',
            'perbaiki_data' => 'Perbaiki Data',
            'data_ditolak' => 'Data Ditolak',
            'data_disetujui' => 'Data Disetujui',
            'lulus' => 'Lulus',
            'tidak_lulus' => 'Tidak Lulus',
            'cadangan' => 'Cadangan',
            'diterima' => 'Diterima',
            'ditolak' => 'Ditolak',
        ];

        return $labels[$status] ?? ucfirst(str_replace('_', ' ', $status));
    }

    private function getStatusColor($status)
    {
        $colors = [
            'menunggu_verifikasi' => 'yellow',
            'lengkapi_data' => 'orange',
            'perbaiki_data' => 'orange',
            'data_ditolak' => 'red',
            'data_disetujui' => 'blue',
            'lulus' => 'green',
            'tidak_lulus' => 'red',
            'cadangan' => 'blue',
            'diterima' => 'green',
            'ditolak' => 'red',
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
