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
use Illuminate\Support\Facades\Cache;
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
    }

    public function loadRecentPendaftar()
    {
        // Load directly from database dengan join untuk mendapatkan info jalur
        $recentData = Pendaftar::with('jalur')
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
