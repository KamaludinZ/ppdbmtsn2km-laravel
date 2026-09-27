<?php

namespace App\Livewire\Admin;

use App\Models\Pendaftar;
use App\Models\JalurPendaftaran;
use App\Models\StatistikTahun;
use App\Models\StatistikJalurTahun;
use App\Models\TesPsikologiPendaftar;
use Livewire\Component;
use Livewire\Attributes\Url;
use Illuminate\Support\Facades\Auth;
use App\Exports\PendaftarExport;
use App\Exports\DaftarUlangExport;
use App\Exports\DaftarUlangSeragamExport;
use App\Exports\SeragamExport;
use App\Exports\TesPsikologiExport;
use Maatwebsite\Excel\Facades\Excel;

class Laporan extends Component
{
    #[Url(keep: true)]
    public $activeTab = 'pendaftaran'; // pendaftaran, data_lengkap, daftar_ulang, daftar_ulang_seragam, seragam, tes_psikologi
    public $filterJalur = '';
    public $filterStatusKelulusan = ''; // Filter untuk tab data_lengkap
    public $filterStatusDU = ''; // Filter untuk tab daftar_ulang
    public $filterStatusSeragam = ''; // Filter untuk tab seragam
    public $filterStatusTesPsikologi = ''; // Filter untuk tab tes_psikologi
    public $filterJenisKelamin = '';
    public $tanggalMulai = '';
    public $tanggalAkhir = '';
    public $expandedYears = []; // Array untuk menyimpan tahun yang di-expand

    public function mount()
    {
        // Check if user is admin
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            return redirect()->to('/login/admin');
        }

        // Set default date range to empty (show all data)
        $this->tanggalMulai = '';
        $this->tanggalAkhir = '';
    }

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
        // Reset filters when switching tabs
        $this->filterJalur = '';
    }

    public function resetFilters()
    {
        $this->filterJalur = '';
        $this->filterJenisKelamin = '';
        $this->tanggalMulai = '';
        $this->tanggalAkhir = '';
    }

    public function render()
    {
        // Base query for stats (without status filter, only jalur and date filters)
        $baseQuery = Pendaftar::query()
            ->when($this->filterJalur, function ($q) {
                $q->where('jalur_pendaftaran_id', $this->filterJalur);
            })
            ->when($this->tanggalMulai, function ($q) {
                $q->whereDate('tanggal_daftar', '>=', $this->tanggalMulai);
            })
            ->when($this->tanggalAkhir, function ($q) {
                $q->whereDate('tanggal_daftar', '<=', $this->tanggalAkhir);
            });

        $stats = [
            'total' => (clone $baseQuery)->count(),
            'menunggu_verifikasi' => (clone $baseQuery)->where('status_pendaftaran', 'menunggu_verifikasi')->count(),
            'lengkapi_data' => (clone $baseQuery)->where('status_pendaftaran', 'lengkapi_data')->count(),
            'perbaiki_data' => (clone $baseQuery)->where('status_pendaftaran', 'perbaiki_data')->count(),
            'data_ditolak' => (clone $baseQuery)->where('status_pendaftaran', 'data_ditolak')->count(),
            'data_disetujui' => (clone $baseQuery)->whereIn('status_pendaftaran', ['data_disetujui', 'lulus', 'diterima', 'tidak_lulus', 'cadangan', 'ditolak'])->count(),
        ];

        // Get per jalur statistics (only active jalur)
        $jalurStats = JalurPendaftaran::aktif()
            ->withCount([
                'pendaftar' => function ($q) {
                    $q->when($this->tanggalMulai, function ($query) {
                        $query->whereDate('tanggal_daftar', '>=', $this->tanggalMulai);
                    })
                    ->when($this->tanggalAkhir, function ($query) {
                        $query->whereDate('tanggal_daftar', '<=', $this->tanggalAkhir);
                    });
                },
                'pendaftar as menunggu_count' => function ($q) {
                    $q->where('status_pendaftaran', 'menunggu_verifikasi')
                    ->when($this->tanggalMulai, function ($query) {
                        $query->whereDate('tanggal_daftar', '>=', $this->tanggalMulai);
                    })
                    ->when($this->tanggalAkhir, function ($query) {
                        $query->whereDate('tanggal_daftar', '<=', $this->tanggalAkhir);
                    });
                },
                'pendaftar as lengkapi_count' => function ($q) {
                    $q->where('status_pendaftaran', 'lengkapi_data')
                    ->when($this->tanggalMulai, function ($query) {
                        $query->whereDate('tanggal_daftar', '>=', $this->tanggalMulai);
                    })
                    ->when($this->tanggalAkhir, function ($query) {
                        $query->whereDate('tanggal_daftar', '<=', $this->tanggalAkhir);
                    });
                },
                'pendaftar as perbaiki_count' => function ($q) {
                    $q->where('status_pendaftaran', 'perbaiki_data')
                    ->when($this->tanggalMulai, function ($query) {
                        $query->whereDate('tanggal_daftar', '>=', $this->tanggalMulai);
                    })
                    ->when($this->tanggalAkhir, function ($query) {
                        $query->whereDate('tanggal_daftar', '<=', $this->tanggalAkhir);
                    });
                },
                'pendaftar as ditolak_count' => function ($q) {
                    $q->where('status_pendaftaran', 'data_ditolak')
                    ->when($this->tanggalMulai, function ($query) {
                        $query->whereDate('tanggal_daftar', '>=', $this->tanggalMulai);
                    })
                    ->when($this->tanggalAkhir, function ($query) {
                        $query->whereDate('tanggal_daftar', '<=', $this->tanggalAkhir);
                    });
                },
                'pendaftar as disetujui_count' => function ($q) {
                    $q->whereIn('status_pendaftaran', ['data_disetujui', 'lulus', 'diterima', 'tidak_lulus', 'cadangan', 'ditolak'])
                    ->when($this->tanggalMulai, function ($query) {
                        $query->whereDate('tanggal_daftar', '>=', $this->tanggalMulai);
                    })
                    ->when($this->tanggalAkhir, function ($query) {
                        $query->whereDate('tanggal_daftar', '<=', $this->tanggalAkhir);
                    });
                },
                'pendaftar as lulus_count' => function ($q) {
                    $q->whereIn('status_pendaftaran', ['lulus', 'diterima'])
                    ->when($this->tanggalMulai, function ($query) {
                        $query->whereDate('tanggal_daftar', '>=', $this->tanggalMulai);
                    })
                    ->when($this->tanggalAkhir, function ($query) {
                        $query->whereDate('tanggal_daftar', '<=', $this->tanggalAkhir);
                    });
                },
                'pendaftar as tidak_lulus_count' => function ($q) {
                    $q->where('status_pendaftaran', 'tidak_lulus')
                    ->when($this->tanggalMulai, function ($query) {
                        $query->whereDate('tanggal_daftar', '>=', $this->tanggalMulai);
                    })
                    ->when($this->tanggalAkhir, function ($query) {
                        $query->whereDate('tanggal_daftar', '<=', $this->tanggalAkhir);
                    });
                },
                'pendaftar as cadangan_count' => function ($q) {
                    $q->where('status_pendaftaran', 'cadangan')
                    ->when($this->tanggalMulai, function ($query) {
                        $query->whereDate('tanggal_daftar', '>=', $this->tanggalMulai);
                    })
                    ->when($this->tanggalAkhir, function ($query) {
                        $query->whereDate('tanggal_daftar', '<=', $this->tanggalAkhir);
                    });
                },
                'pendaftar as laki_laki_count' => function ($q) {
                    $q->where('jenis_kelamin', 'L')
                    ->when($this->tanggalMulai, function ($query) {
                        $query->whereDate('tanggal_daftar', '>=', $this->tanggalMulai);
                    })
                    ->when($this->tanggalAkhir, function ($query) {
                        $query->whereDate('tanggal_daftar', '<=', $this->tanggalAkhir);
                    });
                },
                'pendaftar as perempuan_count' => function ($q) {
                    $q->where('jenis_kelamin', 'P')
                    ->when($this->tanggalMulai, function ($query) {
                        $query->whereDate('tanggal_daftar', '>=', $this->tanggalMulai);
                    })
                    ->when($this->tanggalAkhir, function ($query) {
                        $query->whereDate('tanggal_daftar', '<=', $this->tanggalAkhir);
                    });
                },
                'pendaftar as madrasah_count' => function ($q) {
                    $q->where('jenis_sekolah_asal', 'madrasah')
                    ->when($this->tanggalMulai, function ($query) {
                        $query->whereDate('tanggal_daftar', '>=', $this->tanggalMulai);
                    })
                    ->when($this->tanggalAkhir, function ($query) {
                        $query->whereDate('tanggal_daftar', '<=', $this->tanggalAkhir);
                    });
                },
                'pendaftar as umum_count' => function ($q) {
                    $q->where('jenis_sekolah_asal', 'umum')
                    ->when($this->tanggalMulai, function ($query) {
                        $query->whereDate('tanggal_daftar', '>=', $this->tanggalMulai);
                    })
                    ->when($this->tanggalAkhir, function ($query) {
                        $query->whereDate('tanggal_daftar', '<=', $this->tanggalAkhir);
                    });
                }
            ])
            ->ordered()
            ->get();

        $jalurPendaftaran = JalurPendaftaran::aktif()->ordered()->get();

        // Get statistik per tahun
        $statistikTahun = StatistikTahun::with('jalurStats')
            ->orderBy('tahun', 'desc')
            ->get();

        // Get statistik kelengkapan data untuk tab data_lengkap
        $dataLengkapStats = $this->getDataLengkapStats();

        // Get statistik daftar ulang madrasah untuk tab daftar_ulang
        $daftarUlangStats = $this->getDaftarUlangStats();

        // Get statistik seragam untuk tab daftar_ulang_seragam dan seragam
        $seragamStats = $this->getSeragamStats();

        // Get statistik ukuran untuk tab seragam
        $ukuranStats = $this->getUkuranStats();

        // Get statistik tes psikologi untuk tab tes_psikologi
        $tesPsikologiStats = $this->getTesPsikologiStats();

        return view('livewire.admin.laporan', compact('stats', 'jalurStats', 'jalurPendaftaran', 'statistikTahun', 'dataLengkapStats', 'daftarUlangStats', 'seragamStats', 'ukuranStats', 'tesPsikologiStats'))
            ->layout('components.layouts.dashboard', [
                'role' => 'admin',
                'pageTitle' => 'Laporan PPDB',
                'pageDescription' => 'Laporan dan statistik pendaftaran siswa baru'
            ]);
    }

    private function getDataLengkapStats()
    {
        // Base query with filters
        $query = Pendaftar::query()
            ->when($this->filterJalur, function ($q) {
                $q->where('jalur_pendaftaran_id', $this->filterJalur);
            })
            ->when($this->filterStatusKelulusan, function ($q) {
                if ($this->filterStatusKelulusan === 'lulus') {
                    $q->whereIn('status_pendaftaran', ['lulus', 'diterima']);
                } else {
                    $q->where('status_pendaftaran', $this->filterStatusKelulusan);
                }
            });

        $stats = [];

        // Get all pendaftar that match filter
        $pendaftars = (clone $query)->get();

        if ($pendaftars->isEmpty()) {
            return [
                'data_siswa' => 0,
                'data_ayah' => 0,
                'data_ibu' => 0,
                'data_wali' => 0,
                'data_alamat_ayah' => 0,
                'data_alamat_ibu' => 0,
                'data_alamat_wali' => 0,
                'data_alamat_siswa' => 0,
                'prestasi' => 0,
                'beasiswa' => 0,
                'pendidikan_lain' => 0,
            ];
        }

        // Count completed data for each section
        $stats['data_siswa'] = $pendaftars->filter(function($p) {
            $completeness = $p->getEmisCompletenessDetails();
            return isset($completeness['sections']['data_siswa']) &&
                   $completeness['sections']['data_siswa']['percentage'] == 100;
        })->count();

        $stats['data_ayah'] = $pendaftars->filter(function($p) {
            $completeness = $p->getEmisCompletenessDetails();
            return isset($completeness['sections']['data_ayah']) &&
                   $completeness['sections']['data_ayah']['percentage'] == 100;
        })->count();

        $stats['data_ibu'] = $pendaftars->filter(function($p) {
            $completeness = $p->getEmisCompletenessDetails();
            return isset($completeness['sections']['data_ibu']) &&
                   $completeness['sections']['data_ibu']['percentage'] == 100;
        })->count();

        $stats['data_wali'] = $pendaftars->filter(function($p) {
            $completeness = $p->getEmisCompletenessDetails();
            return isset($completeness['sections']['data_wali']) &&
                   $completeness['sections']['data_wali']['percentage'] == 100;
        })->count();

        $stats['data_alamat_ayah'] = $pendaftars->filter(function($p) {
            $completeness = $p->getEmisCompletenessDetails();
            return isset($completeness['sections']['data_alamat_ayah']) &&
                   $completeness['sections']['data_alamat_ayah']['percentage'] == 100;
        })->count();

        $stats['data_alamat_ibu'] = $pendaftars->filter(function($p) {
            $completeness = $p->getEmisCompletenessDetails();
            return isset($completeness['sections']['data_alamat_ibu']) &&
                   $completeness['sections']['data_alamat_ibu']['percentage'] == 100;
        })->count();

        $stats['data_alamat_wali'] = $pendaftars->filter(function($p) {
            $completeness = $p->getEmisCompletenessDetails();
            return isset($completeness['sections']['data_alamat_wali']) &&
                   $completeness['sections']['data_alamat_wali']['percentage'] == 100;
        })->count();

        $stats['data_alamat_siswa'] = $pendaftars->filter(function($p) {
            $completeness = $p->getEmisCompletenessDetails();
            return isset($completeness['sections']['data_alamat_siswa']) &&
                   $completeness['sections']['data_alamat_siswa']['percentage'] == 100;
        })->count();

        // Count prestasi, beasiswa, pendidikan_lain (count records that have at least one)
        $stats['prestasi'] = $pendaftars->filter(function($p) {
            $completeness = $p->getEmisCompletenessDetails();
            return isset($completeness['sections']['prestasi']) &&
                   $completeness['sections']['prestasi']['filled'] > 0;
        })->count();

        $stats['beasiswa'] = $pendaftars->filter(function($p) {
            $completeness = $p->getEmisCompletenessDetails();
            return isset($completeness['sections']['beasiswa']) &&
                   $completeness['sections']['beasiswa']['filled'] > 0;
        })->count();

        $stats['pendidikan_lain'] = $pendaftars->filter(function($p) {
            $completeness = $p->getEmisCompletenessDetails();
            return isset($completeness['sections']['pendidikan_lain']) &&
                   $completeness['sections']['pendidikan_lain']['filled'] > 0;
        })->count();

        // Add kelulusan stats
        $allPendaftar = Pendaftar::query()
            ->when($this->filterJalur, function ($q) {
                $q->where('jalur_pendaftaran_id', $this->filterJalur);
            })
            ->get();

        $stats['total_pendaftar'] = $allPendaftar->count();
        $stats['lulus'] = $allPendaftar->whereIn('status_pendaftaran', ['lulus', 'diterima'])->count();
        $stats['tidak_lulus'] = $allPendaftar->where('status_pendaftaran', 'tidak_lulus')->count();
        $stats['cadangan'] = $allPendaftar->where('status_pendaftaran', 'cadangan')->count();

        return $stats;
    }

    private function getDaftarUlangStats()
    {
        // Rekap konsisten (jumlah semua kategori = total wajib daftar ulang)
        return \App\Support\RekapDaftarUlang::statsTab('madrasah', ['jalur' => $this->filterJalur]);
    }

    private function getSeragamStats()
    {
        return \App\Support\RekapDaftarUlang::statsTab('seragam', ['jalur' => $this->filterJalur, 'jenis_kelamin' => $this->filterJenisKelamin]);
    }

    private function getUkuranStats()
    {
        return \App\Support\RekapDaftarUlang::statsTab('ukuran', ['jalur' => $this->filterJalur, 'jenis_kelamin' => $this->filterJenisKelamin]);
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
            'start_date' => $this->tanggalMulai,
            'end_date' => $this->tanggalAkhir,
        ]);
    }

    public function exportPdf()
    {
        return redirect()->route('export.pdf', [
            'jalur' => $this->filterJalur,
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
                null,
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
                $this->filterStatusDU ?: null, // kategori rekap (RekapDaftarUlang::KATEGORI)
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

    public function simpanStatistikTahun()
    {
        $tahunSekarang = date('Y');

        // Get current stats from jalurStats (reuse the same query logic)
        $jalurStats = JalurPendaftaran::aktif()
            ->withCount([
                'pendaftar' => function ($q) {
                    $q->when($this->filterJalur, function ($query) {
                        $query->where('jalur_pendaftaran_id', $this->filterJalur);
                    });
                },
                'pendaftar as menunggu_count' => function ($q) {
                    $q->where('status_pendaftaran', 'menunggu_verifikasi');
                },
                'pendaftar as lengkapi_count' => function ($q) {
                    $q->where('status_pendaftaran', 'lengkapi_data');
                },
                'pendaftar as perbaiki_count' => function ($q) {
                    $q->where('status_pendaftaran', 'perbaiki_data');
                },
                'pendaftar as ditolak_count' => function ($q) {
                    $q->where('status_pendaftaran', 'data_ditolak');
                },
                'pendaftar as disetujui_count' => function ($q) {
                    $q->whereIn('status_pendaftaran', ['data_disetujui', 'lulus', 'diterima', 'tidak_lulus', 'cadangan', 'ditolak']);
                },
                'pendaftar as lulus_count' => function ($q) {
                    $q->whereIn('status_pendaftaran', ['lulus', 'diterima']);
                },
                'pendaftar as tidak_lulus_count' => function ($q) {
                    $q->where('status_pendaftaran', 'tidak_lulus');
                },
                'pendaftar as cadangan_count' => function ($q) {
                    $q->where('status_pendaftaran', 'cadangan');
                },
                'pendaftar as laki_laki_count' => function ($q) {
                    $q->where('jenis_kelamin', 'L');
                },
                'pendaftar as perempuan_count' => function ($q) {
                    $q->where('jenis_kelamin', 'P');
                },
                'pendaftar as madrasah_count' => function ($q) {
                    $q->where('jenis_sekolah_asal', 'madrasah');
                },
                'pendaftar as umum_count' => function ($q) {
                    $q->where('jenis_sekolah_asal', 'umum');
                }
            ])
            ->ordered()
            ->get();

        // Calculate totals
        $totalKuota = $jalurStats->sum('kuota');
        $totalPendaftar = $jalurStats->sum('pendaftar_count');
        $totalMenunggu = $jalurStats->sum('menunggu_count');
        $totalLengkapi = $jalurStats->sum('lengkapi_count');
        $totalPerbaiki = $jalurStats->sum('perbaiki_count');
        $totalDitolak = $jalurStats->sum('ditolak_count');
        $totalDisetujui = $jalurStats->sum('disetujui_count');
        $totalLulus = $jalurStats->sum('lulus_count');
        $totalTidakLulus = $jalurStats->sum('tidak_lulus_count');
        $totalCadangan = $jalurStats->sum('cadangan_count');
        $totalLakiLaki = $jalurStats->sum('laki_laki_count');
        $totalPerempuan = $jalurStats->sum('perempuan_count');
        $totalMadrasah = $jalurStats->sum('madrasah_count');
        $totalUmum = $jalurStats->sum('umum_count');

        // Create or update statistik tahun
        $statistikTahun = StatistikTahun::updateOrCreate(
            ['tahun' => $tahunSekarang],
            [
                'total_kuota' => $totalKuota,
                'total_pendaftar' => $totalPendaftar,
                'menunggu' => $totalMenunggu,
                'lengkapi' => $totalLengkapi,
                'perbaiki' => $totalPerbaiki,
                'ditolak' => $totalDitolak,
                'disetujui' => $totalDisetujui,
                'lulus' => $totalLulus,
                'tidak_lulus' => $totalTidakLulus,
                'cadangan' => $totalCadangan,
                'laki_laki' => $totalLakiLaki,
                'perempuan' => $totalPerempuan,
                'madrasah' => $totalMadrasah,
                'umum' => $totalUmum,
            ]
        );

        // Delete old jalur stats for this year
        StatistikJalurTahun::where('statistik_tahun_id', $statistikTahun->id)->delete();

        // Save jalur statistics
        $urutan = 1;
        foreach ($jalurStats as $jalur) {
            StatistikJalurTahun::create([
                'statistik_tahun_id' => $statistikTahun->id,
                'nama_jalur' => $jalur->nama,
                'warna' => $jalur->warna,
                'kuota' => $jalur->kuota,
                'total' => $jalur->pendaftar_count,
                'menunggu' => $jalur->menunggu_count,
                'lengkapi' => $jalur->lengkapi_count,
                'perbaiki' => $jalur->perbaiki_count,
                'ditolak' => $jalur->ditolak_count,
                'disetujui' => $jalur->disetujui_count,
                'lulus' => $jalur->lulus_count,
                'tidak_lulus' => $jalur->tidak_lulus_count,
                'cadangan' => $jalur->cadangan_count,
                'laki_laki' => $jalur->laki_laki_count,
                'perempuan' => $jalur->perempuan_count,
                'madrasah' => $jalur->madrasah_count,
                'umum' => $jalur->umum_count,
                'urutan' => $urutan++,
            ]);
        }

        session()->flash('message', 'Statistik tahun ' . $tahunSekarang . ' berhasil disimpan!');
    }

    public function toggleTahunDetail($tahun)
    {
        if (in_array($tahun, $this->expandedYears)) {
            $this->expandedYears = array_diff($this->expandedYears, [$tahun]);
        } else {
            $this->expandedYears[] = $tahun;
        }
    }
}
