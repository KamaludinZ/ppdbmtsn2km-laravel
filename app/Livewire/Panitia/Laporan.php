<?php

namespace App\Livewire\Panitia;

use App\Models\Pendaftar;
use App\Models\JalurPendaftaran;
use App\Models\TesPsikologiPendaftar;
use Livewire\Component;
use Livewire\Attributes\Url;
use Illuminate\Support\Facades\Auth;
use App\Exports\PendaftarExport;
use App\Exports\DaftarUlangExport;
use App\Exports\SeragamExport;
use App\Exports\TesPsikologiExport;
use Maatwebsite\Excel\Facades\Excel;

class Laporan extends Component
{
    public $activeTab = 'pendaftaran'; // pendaftaran, data_lengkap, daftar_ulang, seragam, tes_psikologi
    public $filterJalur = '';
    public $filterStatus = '';
    public $filterStatusDU = ''; // Filter untuk tab daftar_ulang
    public $filterStatusTesPsikologi = ''; // Filter for tes_psikologi tab
    public $filterJenisKelamin = '';
    public $tanggalMulai = '';
    public $tanggalAkhir = '';
    public $isPanitiaPsikologi = false; // Flag untuk panitia tes psikologi
    public $isPanitiaPendaftaran = false; // Flag untuk panitia pendaftaran
    public $isPanitiaDaftarUlang = false; // Flag untuk panitia daftar ulang

    /**
     * Override queryString untuk exclude activeTab dari URL
     * untuk role panitia_daftarulang dan panitia_psikologi
     */
    protected function queryString()
    {
        $user = Auth::user();

        // Jika panitia daftar ulang atau panitia psikologi, jangan track activeTab di URL
        if ($user && in_array($user->role, ['panitia_daftarulang', 'panitia_tes_psikologi'])) {
            return [];
        }

        // Untuk role lain, track activeTab di URL
        return [
            'activeTab' => ['keep' => true],
        ];
    }

    public function mount()
    {
        // Check if user is panitia
        $user = Auth::user();
        if (!$user || !in_array($user->role, ['panitia_pendaftaran', 'panitia_daftarulang', 'panitia_tes_psikologi', 'panitia_seragam'])) {
            return redirect()->to('/login/admin');
        }

        // Detect panitia tes psikologi role
        $this->isPanitiaPsikologi = ($user->role === 'panitia_tes_psikologi');

        // Detect panitia pendaftaran role
        $this->isPanitiaPendaftaran = ($user->role === 'panitia_pendaftaran');

        // Detect panitia daftar ulang role
        $this->isPanitiaDaftarUlang = ($user->role === 'panitia_daftarulang');

        // Jika panitia tes psikologi, langsung set ke tab tes_psikologi
        if ($this->isPanitiaPsikologi) {
            $this->activeTab = 'tes_psikologi';
        }

        // Jika panitia daftar ulang, langsung set ke tab daftar_ulang
        if ($this->isPanitiaDaftarUlang) {
            $this->activeTab = 'daftar_ulang';
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
        // Jika panitia tes psikologi, hanya load data tes psikologi
        if ($this->isPanitiaPsikologi) {
            $jalurPendaftaran = JalurPendaftaran::aktif()->ordered()->get();
            $tesPsikologiStats = $this->getTesPsikologiStats();

            return view('livewire.panitia.laporan', [
                'stats' => [],
                'jalurStats' => [],
                'jalurPendaftaran' => $jalurPendaftaran,
                'tesPsikologiStats' => $tesPsikologiStats,
                'isPanitiaPsikologi' => $this->isPanitiaPsikologi,
                'isPanitiaPendaftaran' => $this->isPanitiaPendaftaran,
                'isPanitiaDaftarUlang' => $this->isPanitiaDaftarUlang
            ])
            ->layout('components.layouts.dashboard', [
                'role' => Auth::user()->role,
                'pageTitle' => 'Laporan Tes Psikologi',
                'pageDescription' => 'Laporan dan statistik tes psikologi'
            ]);
        }

        // Jika panitia daftar ulang, hanya load data daftar ulang
        if ($this->isPanitiaDaftarUlang) {
            $jalurPendaftaran = JalurPendaftaran::aktif()->ordered()->get();
            $daftarUlangStats = $this->getDaftarUlangStats();

            return view('livewire.panitia.laporan', [
                'stats' => [],
                'jalurStats' => [],
                'jalurPendaftaran' => $jalurPendaftaran,
                'tesPsikologiStats' => [],
                'daftarUlangStats' => $daftarUlangStats,
                'isPanitiaPsikologi' => $this->isPanitiaPsikologi,
                'isPanitiaPendaftaran' => $this->isPanitiaPendaftaran,
                'isPanitiaDaftarUlang' => $this->isPanitiaDaftarUlang
            ])
            ->layout('components.layouts.dashboard', [
                'role' => Auth::user()->role,
                'pageTitle' => 'Laporan Daftar Ulang',
                'pageDescription' => 'Laporan dan statistik daftar ulang madrasah'
            ]);
        }

        // Get statistics untuk panitia lainnya
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

        // Get statistik tes psikologi untuk tab tes_psikologi
        $tesPsikologiStats = $this->getTesPsikologiStats();

        return view('livewire.panitia.laporan', [
            'stats' => $stats,
            'jalurStats' => $jalurStats,
            'jalurPendaftaran' => $jalurPendaftaran,
            'tesPsikologiStats' => $tesPsikologiStats,
            'isPanitiaPsikologi' => $this->isPanitiaPsikologi,
            'isPanitiaPendaftaran' => $this->isPanitiaPendaftaran,
            'isPanitiaDaftarUlang' => $this->isPanitiaDaftarUlang
        ])
        ->layout('components.layouts.dashboard', [
            'role' => Auth::user()->role,
            'pageTitle' => 'Laporan PPDB',
            'pageDescription' => 'Laporan dan statistik pendaftaran siswa baru'
        ]);
    }

    private function getTesPsikologiStats()
    {
        // Base query with filters (tanggal filters removed as requested)
        $query = TesPsikologiPendaftar::with(['pendaftar.jalur'])
            ->whereHas('pendaftar', function ($q) {
                $q->when($this->filterJalur, function ($query) {
                    $query->where('jalur_pendaftaran_id', $this->filterJalur);
                });
            })
            ->when($this->filterStatusTesPsikologi, function ($q) {
                $q->where('status_verifikasi', $this->filterStatusTesPsikologi);
            });

        $allData = (clone $query)->get();

        $stats = [
            'total' => $allData->count(),
            'belum_upload' => (clone $query)->where('status_verifikasi', 'belum_upload')->count(),
            'menunggu' => (clone $query)->where('status_verifikasi', 'menunggu')->count(),
            'disetujui' => (clone $query)->where('status_verifikasi', 'disetujui')->count(),
            'ditolak' => (clone $query)->where('status_verifikasi', 'ditolak')->count(),
            'perbaiki' => (clone $query)->where('status_verifikasi', 'perbaiki')->count(),
        ];

        // Get per jalur statistics
        $jalurStats = [];
        $jalurs = JalurPendaftaran::aktif()->ordered()->get();

        foreach ($jalurs as $jalur) {
            $jalurData = $allData->where('pendaftar.jalur_pendaftaran_id', $jalur->id);

            $jalurStats[] = [
                'nama' => $jalur->nama,
                'total' => $jalurData->count(),
                'belum_upload' => $jalurData->where('status_verifikasi', 'belum_upload')->count(),
                'menunggu' => $jalurData->where('status_verifikasi', 'menunggu')->count(),
                'disetujui' => $jalurData->where('status_verifikasi', 'disetujui')->count(),
                'ditolak' => $jalurData->where('status_verifikasi', 'ditolak')->count(),
                'perbaiki' => $jalurData->where('status_verifikasi', 'perbaiki')->count(),
            ];
        }

        return [
            'stats' => $stats,
            'jalur_stats' => $jalurStats,
            'data' => $allData
        ];
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

    public function exportTesPsikologi()
    {
        // Export tes psikologi data
        $fileName = 'laporan_tes_psikologi_' . date('Y-m-d_His') . '.xlsx';

        return Excel::download(
            new TesPsikologiExport(
                $this->filterJalur,
                $this->filterStatusTesPsikologi
            ),
            $fileName
        );
    }

    private function getDaftarUlangStats()
    {
        // Get all pendaftar lulus
        $allPendaftar = Pendaftar::with(['daftarUlang'])
            ->whereIn('status_pendaftaran', ['data_disetujui', 'lulus', 'diterima'])
            ->when($this->filterJalur, function ($q) {
                $q->where('jalur_pendaftaran_id', $this->filterJalur);
            })
            ->get();

        $stats = [
            // Total pendaftar lulus
            'total_lulus' => $allPendaftar->count(),

            // Status persetujuan daftar ulang (dari pendaftar.status_persetujuan_daftar_ulang)
            'setuju' => $allPendaftar->filter(function($p) {
                return $p->status_persetujuan_daftar_ulang === 'setuju';
            })->count(),
            'tidak_setuju' => $allPendaftar->filter(function($p) {
                return $p->status_persetujuan_daftar_ulang === 'tidak_setuju';
            })->count(),

            // Status proses daftar ulang (dari daftar_ulang.status)
            'menunggu' => $allPendaftar->filter(function($p) {
                return !$p->daftarUlang || $p->daftarUlang->status === 'menunggu' || !$p->daftarUlang->status;
            })->count(),
            'disetujui' => $allPendaftar->filter(function($p) {
                return $p->daftarUlang && $p->daftarUlang->status === 'disetujui';
            })->count(),
            'ditolak' => $allPendaftar->filter(function($p) {
                return $p->daftarUlang && $p->daftarUlang->status === 'ditolak';
            })->count(),
            'perlu_perbaikan' => $allPendaftar->filter(function($p) {
                return $p->daftarUlang && $p->daftarUlang->status === 'perlu_perbaikan';
            })->count(),

            // Status upload surat pernyataan
            'upload_surat' => $allPendaftar->filter(function($p) {
                return $p->daftarUlang && $p->daftarUlang->surat_pernyataan_path;
            })->count(),
        ];

        return $stats;
    }
}
