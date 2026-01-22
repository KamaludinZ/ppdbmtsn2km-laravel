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
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Dashboard extends Component
{
    public $stats = [];
    public $recentPendaftar = [];
    public $jalurStats = [];
    public $emisCompletenessStats = [];

    public function mount()
    {
        try {
            // Check if user is admin
            $user = Auth::user();

            if (!$user || $user->role !== 'admin') {
                return redirect()->to('/login/admin');
            }

            $this->loadStats();
            $this->loadRecentPendaftar();
            $this->loadJalurStats();
            $this->loadEmisCompletenessStats();
        } catch (\Exception $e) {
            // Log error but don't crash
            Log::error('Dashboard mount error: ' . $e->getMessage());

            // Set default empty values
            $this->stats = $this->getDefaultStats();
            $this->recentPendaftar = collect([]);
            $this->jalurStats = collect([]);
            $this->emisCompletenessStats = $this->getDefaultEmisStats();
        }
    }

    public function loadStats()
    {
        try {
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

            // Panitia aktif
            $panitiaAktif = User::whereIn('role', ['panitia', 'panitia_pendaftaran', 'panitia_daftarulang'])->count();

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
                'panitia_aktif' => $panitiaAktif,
            ];
        } catch (\Exception $e) {
            Log::error('loadStats error: ' . $e->getMessage());
            $this->stats = $this->getDefaultStats();
        }
    }

    public function loadRecentPendaftar()
    {
        try {
            // Load directly from database dengan join untuk mendapatkan info jalur
            $recentData = Pendaftar::with('jalurPendaftaran:id,nama,warna')
                ->orderBy('tanggal_daftar', 'desc')
                ->take(5)
                ->get();

            $this->recentPendaftar = $recentData->map(function ($pendaftar) {
                return [
                    'nomor_pendaftaran' => $pendaftar->nomor_pendaftaran ?? '-',
                    'nama' => $pendaftar->nama_lengkap,
                    'nisn' => $pendaftar->nisn ?? '-',
                    'jalur' => $pendaftar->jalurPendaftaran->nama ?? '-',
                    'jalur_warna' => $pendaftar->jalurPendaftaran->warna ?? 'blue',
                    'status' => $pendaftar->status_pendaftaran,
                    'status_label' => $this->getStatusLabel($pendaftar->status_pendaftaran),
                    'status_color' => $this->getStatusColor($pendaftar->status_pendaftaran),
                    'tanggal' => \Carbon\Carbon::parse($pendaftar->tanggal_daftar)->format('d M Y'),
                    'initials' => $this->getInitials($pendaftar->nama_lengkap),
                ];
            });
        } catch (\Exception $e) {
            Log::error('loadRecentPendaftar error: ' . $e->getMessage());
            $this->recentPendaftar = collect([]);
        }
    }

    public function loadJalurStats()
    {
        try {
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
        } catch (\Exception $e) {
            Log::error('loadJalurStats error: ' . $e->getMessage());
            $this->jalurStats = collect([]);
        }
    }

    public function loadEmisCompletenessStats()
    {
        try {
            // SIMPLIFIED VERSION - Skip complex calculation to avoid timeout
            // Just show basic counts
            $totalPendaftar = Pendaftar::count();

            // Simple check: count how many have EMIS data filled
            // (This is much faster than calculating completeness percentage for each)
            $this->emisCompletenessStats = [
                'total_pendaftar' => $totalPendaftar,
                'lengkap' => 0, // Placeholder - can be calculated later if needed
                'sebagian' => 0,
                'kurang_lengkap' => 0,
                'belum_diisi' => 0,
                'lengkap_percentage' => 0,
                'average_completion' => 0,
                'section_averages' => [],
            ];

            // Note: For production with large dataset, EMIS stats should be:
            // 1. Calculated by background job
            // 2. Stored in separate table
            // 3. Updated periodically (not on every page load)
        } catch (\Exception $e) {
            Log::error('loadEmisCompletenessStats error: ' . $e->getMessage());
            $this->emisCompletenessStats = $this->getDefaultEmisStats();
        }
    }

    private function getDefaultStats(): array
    {
        return [
            'total_pendaftar' => 0,
            'total_pendaftar_increase' => 0,
            'menunggu_verifikasi' => 0,
            'lengkapi_data' => 0,
            'lengkapi_data_percentage' => 0,
            'perbaiki_data' => 0,
            'perbaiki_data_percentage' => 0,
            'data_disetujui' => 0,
            'data_disetujui_percentage' => 0,
            'lulus' => 0,
            'tidak_lulus' => 0,
            'cadangan' => 0,
            'data_ditolak' => 0,
            'panitia_aktif' => 0,
        ];
    }

    private function getDefaultEmisStats(): array
    {
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
