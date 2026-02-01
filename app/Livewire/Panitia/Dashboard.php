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
    public $isPanitiaPsikologi = false; // Flag untuk panitia tes psikologi
    public $isPanitiaDaftarUlang = false; // Flag untuk panitia daftar ulang
    public $jalurSetujuDaftarUlang = []; // Data jalur dengan count setuju daftar ulang
    public $jalurDetailStats = []; // Data statistik detail per jalur

    public function mount()
    {
        // Check if user is panitia
        $user = Auth::user();

        if (!$user || !in_array($user->role, ['panitia_pendaftaran', 'panitia_daftarulang', 'panitia_tes_psikologi', 'panitia_seragam'])) {
            return redirect()->to('/login/admin');
        }

        // Detect panitia tes psikologi role
        $this->isPanitiaPsikologi = ($user->role === 'panitia_tes_psikologi');

        // Detect panitia daftar ulang role
        $this->isPanitiaDaftarUlang = ($user->role === 'panitia_daftarulang');

        $this->loadStats();
        $this->loadRecentPendaftar();
        $this->loadJalurStats();
        $this->loadJalurDetailStats(); // Load statistik detail per jalur

        // Load jalur setuju daftar ulang untuk panitia daftar ulang
        if ($this->isPanitiaDaftarUlang) {
            $this->loadJalurSetujuDaftarUlang();
        }
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

        // Pendaftar terbaru hari ini
        $pendaftarHariIni = Pendaftar::whereDate('tanggal_daftar', now()->toDateString())->count();

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
            'pendaftar_hari_ini' => $pendaftarHariIni,
        ];
    }

    public function loadRecentPendaftar()
    {
        // Untuk Panitia Daftar Ulang: Load siswa yang sudah upload madrasah (status menunggu, perlu_perbaikan)
        if ($this->isPanitiaDaftarUlang) {
            $recentData = Pendaftar::with(['jalurPendaftaran', 'daftarUlang'])
                ->whereIn('status_pendaftaran', ['data_disetujui', 'lulus', 'diterima'])
                ->whereHas('daftarUlang', function($query) {
                    // Hanya tampilkan yang sudah upload dan menunggu verifikasi
                    $query->whereIn('status', ['menunggu', 'perlu_perbaikan']);
                })
                ->orderBy('updated_at', 'desc')
                ->take(5)
                ->get();

            $this->recentPendaftar = $recentData->map(function ($pendaftar) {
                $statusMadrasah = 'Belum Isi';
                $statusColor = 'gray';

                if ($pendaftar->daftarUlang) {
                    $statusMadrasah = match($pendaftar->daftarUlang->status) {
                        'menunggu' => 'Menunggu Verifikasi',
                        'disetujui' => 'Disetujui',
                        'perlu_perbaikan' => 'Perlu Perbaikan',
                        'ditolak' => 'Ditolak',
                        default => 'Belum Isi'
                    };
                    $statusColor = match($pendaftar->daftarUlang->status) {
                        'menunggu' => 'yellow',
                        'disetujui' => 'green',
                        'perlu_perbaikan' => 'orange',
                        'ditolak' => 'red',
                        default => 'gray'
                    };
                }

                return [
                    'nomor_pendaftaran' => $pendaftar->nomor_pendaftaran ?? '-',
                    'nama' => $pendaftar->nama_lengkap,
                    'nisn' => $pendaftar->nisn ?? '-',
                    'jalur' => $pendaftar->jalurPendaftaran->nama ?? '-',
                    'jalur_warna' => $pendaftar->jalurPendaftaran->warna ?? 'blue',
                    'status' => $pendaftar->daftarUlang->status ?? null,
                    'status_label' => $statusMadrasah,
                    'status_color' => $statusColor,
                    'tanggal' => $pendaftar->updated_at->format('d M Y'),
                    'initials' => $this->getInitials($pendaftar->nama_lengkap),
                ];
            });
        } else {
            // Untuk role lain: Load pendaftar yang menunggu verifikasi atau baru mendaftar hari ini
            $recentData = Pendaftar::with('jalurPendaftaran')
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
                    'jalur' => $pendaftar->jalurPendaftaran->nama ?? '-',
                    'jalur_warna' => $pendaftar->jalurPendaftaran->warna ?? 'blue',
                    'status' => $pendaftar->status_pendaftaran,
                    'status_label' => $this->getStatusLabel($pendaftar->status_pendaftaran),
                    'status_color' => $this->getStatusColor($pendaftar->status_pendaftaran),
                    'tanggal' => \Carbon\Carbon::parse($pendaftar->tanggal_daftar)->format('d M Y'),
                    'initials' => $this->getInitials($pendaftar->nama_lengkap),
                ];
            });
        }
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

    public function loadJalurSetujuDaftarUlang()
    {
        // Load jalur aktif dengan count pendaftar yang setuju daftar ulang
        $jalurAktif = JalurPendaftaran::where('aktif', true)
            ->orderBy('urutan', 'asc')
            ->get();

        $this->jalurSetujuDaftarUlang = $jalurAktif->map(function ($jalur) {
            // Count pendaftar yang lulus DAN setuju daftar ulang di jalur ini
            $count = $jalur->pendaftar()
                ->whereIn('status_pendaftaran', ['data_disetujui', 'lulus', 'diterima'])
                ->where('status_persetujuan_daftar_ulang', 'setuju')
                ->count();

            return [
                'nama' => $jalur->nama,
                'count' => $count,
                'warna' => $jalur->warna,
            ];
        });

        // Hitung total setuju daftar ulang
        $totalSetuju = $this->jalurSetujuDaftarUlang->sum('count');
        $this->stats['total_setuju_daftar_ulang'] = $totalSetuju;
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
        $roleLabel = match($user->role) {
            'panitia_pendaftaran' => 'Panitia Pendaftaran',
            'panitia_daftarulang' => 'Panitia Daftar Ulang',
            'panitia_tes_psikologi' => 'Panitia Tes Psikologi',
            default => 'Panitia'
        };

        return view('livewire.panitia.dashboard')
            ->layout('components.layouts.dashboard', [
                'role' => $user->role,
                'pageTitle' => 'Dashboard ' . $roleLabel,
                'pageDescription' => 'Selamat datang di dashboard PPDB MTsN 2 Kota Malang'
            ]);
    }
}
