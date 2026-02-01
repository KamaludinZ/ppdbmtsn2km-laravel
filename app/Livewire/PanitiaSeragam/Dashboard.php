<?php

namespace App\Livewire\PanitiaSeragam;

use Livewire\Component;
use App\Models\Pendaftar;
use App\Models\DaftarUlang;
use App\Models\Seragam;
use App\Models\JalurPendaftaran;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{
    public $stats = [];
    public $recentPendaftar = [];
    public $jalurStats = [];
    public $jalurSetujuDaftarUlang = []; // Data jalur dengan count setuju daftar ulang
    public $jalurDetailStats = []; // Data statistik detail per jalur

    public function mount()
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'panitia_seragam') {
            return redirect()->to('/login/admin');
        }

        $this->loadStats();
        $this->loadRecentPendaftar();
        $this->loadJalurStats();
        $this->loadJalurSetujuDaftarUlang();
        $this->loadJalurDetailStats(); // Load statistik detail per jalur
    }

    public function loadStats()
    {
        // Total lulus
        $totalLulus = Pendaftar::whereIn('status_pendaftaran', ['data_disetujui', 'lulus', 'diterima'])->count();

        // Status Daftar Ulang Seragam (berdasarkan status_ukuran)
        $menungguVerifikasi = Seragam::where('status_ukuran', 'menunggu')->count();
        $disetujui = Seragam::where('status_ukuran', 'disetujui')->count();
        $perluPerbaikan = Seragam::where('status_ukuran', 'perlu_perbaikan')->count();
        $ditolak = Seragam::where('status_ukuran', 'ditolak')->count();

        // Belum isi - siswa lulus yang belum ada record seragam atau status_ukuran null
        $siswaSeragamIds = Seragam::whereNotNull('status_ukuran')->pluck('pendaftar_id')->unique();
        $belumIsi = Pendaftar::whereIn('status_pendaftaran', ['data_disetujui', 'lulus', 'diterima'])
            ->whereNotIn('id', $siswaSeragamIds)
            ->count();

        // Hitung persentase
        $menungguPercentage = $totalLulus > 0 ? ($menungguVerifikasi / $totalLulus) * 100 : 0;
        $disetujuiPercentage = $totalLulus > 0 ? ($disetujui / $totalLulus) * 100 : 0;
        $perluPerbaikanPercentage = $totalLulus > 0 ? ($perluPerbaikan / $totalLulus) * 100 : 0;
        $ditolakPercentage = $totalLulus > 0 ? ($ditolak / $totalLulus) * 100 : 0;
        $belumIsiPercentage = $totalLulus > 0 ? ($belumIsi / $totalLulus) * 100 : 0;

        $this->stats = [
            'total_lulus' => $totalLulus,
            'menunggu_verifikasi' => $menungguVerifikasi,
            'menunggu_percentage' => round($menungguPercentage, 1),
            'disetujui' => $disetujui,
            'disetujui_percentage' => round($disetujuiPercentage, 1),
            'perlu_perbaikan' => $perluPerbaikan,
            'perlu_perbaikan_percentage' => round($perluPerbaikanPercentage, 1),
            'ditolak' => $ditolak,
            'ditolak_percentage' => round($ditolakPercentage, 1),
            'belum_isi' => $belumIsi,
            'belum_isi_percentage' => round($belumIsiPercentage, 1),
        ];
    }

    public function loadRecentPendaftar()
    {
        // Load siswa yang baru upload seragam (status menunggu, perlu_perbaikan)
        $pendaftarList = Pendaftar::with(['jalurPendaftaran', 'seragam'])
            ->whereIn('status_pendaftaran', ['data_disetujui', 'lulus', 'diterima'])
            ->whereHas('seragam', function($query) {
                // Hanya tampilkan yang sudah upload dan menunggu verifikasi
                $query->whereIn('status_ukuran', ['menunggu', 'perlu_perbaikan']);
            })
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        $this->recentPendaftar = $pendaftarList->map(function($p) {
            $statusSeragam = 'Belum Isi';
            $statusColor = 'gray';

            if ($p->seragam) {
                $statusSeragam = match($p->seragam->status_ukuran) {
                    'menunggu' => 'Menunggu Verifikasi',
                    'disetujui' => 'Disetujui',
                    'perlu_perbaikan' => 'Perlu Perbaikan',
                    'ditolak' => 'Ditolak',
                    default => 'Belum Isi'
                };
                $statusColor = match($p->seragam->status_ukuran) {
                    'menunggu' => 'yellow',
                    'disetujui' => 'green',
                    'perlu_perbaikan' => 'orange',
                    'ditolak' => 'red',
                    default => 'gray'
                };
            }

            $names = explode(' ', $p->nama_lengkap);
            $initials = '';
            foreach (array_slice($names, 0, 2) as $name) {
                $initials .= strtoupper(substr($name, 0, 1));
            }

            // Get jalur warna untuk avatar
            $jalurWarna = 'emerald';
            if ($p->jalurPendaftaran && $p->jalurPendaftaran->warna) {
                $jalurWarna = $p->jalurPendaftaran->warna;
            }

            return [
                'nama' => $p->nama_lengkap,
                'nomor_pendaftaran' => $p->nomor_pendaftaran ?? '-',
                'nisn' => $p->nisn ?? '-',
                'jalur' => $p->jalurPendaftaran->nama ?? '-',
                'jalur_color' => $p->jalurPendaftaran->warna ?? '#6B7280',
                'jalur_warna' => $jalurWarna,
                'status_seragam_label' => $statusSeragam,
                'status_seragam_color' => $statusColor,
                'tanggal' => $p->updated_at->format('d M Y'),
                'initials' => $initials,
            ];
        })->toArray();
    }

    public function loadJalurStats()
    {
        $jalurList = JalurPendaftaran::withCount([
            'pendaftar' => function($query) {
                $query->whereIn('status_pendaftaran', ['data_disetujui', 'lulus', 'diterima']);
            }
        ])->get();

        $total = $jalurList->sum('pendaftar_count');

        $this->jalurStats = $jalurList->map(function($jalur) use ($total) {
            $percentage = $total > 0 ? round(($jalur->pendaftar_count / $total) * 100) : 0;
            return [
                'nama' => $jalur->nama,
                'count' => $jalur->pendaftar_count,
                'warna' => $jalur->warna ?? 'gray',
                'percentage' => $percentage,
            ];
        })->filter(function($jalur) {
            return $jalur['count'] > 0;
        })->toArray();
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

    public function render()
    {
        return view('livewire.panitia-seragam.dashboard')
            ->layout('components.layouts.dashboard', [
                'role' => 'panitia_seragam',
                'pageTitle' => 'Dashboard Panitia Seragam'
            ]);
    }
}
