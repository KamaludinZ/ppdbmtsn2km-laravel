<?php

namespace App\Livewire\Admin;

use App\Models\Pendaftar;
use App\Models\StatistikJalurTahun;
use App\Models\StatistikTahun;
use App\Support\RekapDaftarUlang;
use App\Support\RekapPendaftaran;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Url;
use Livewire\Component;

class Laporan extends Component
{
    public const TABS = [
        'pendaftaran' => 'Laporan Pendaftaran',
        'data_lengkap' => 'Data Pendaftar Lengkap',
        'daftar_ulang' => 'Daftar Ulang Madrasah',
        'daftar_ulang_seragam' => 'Daftar Ulang Seragam',
        'seragam' => 'Seragam & Ukuran',
        'tes_psikologi' => 'Tes Psikologi',
    ];

    /** Bagian formulir yang dihitung di tab Data Lengkap (kunci dari getEmisCompletenessDetails). */
    public const BAGIAN_FORMULIR = [
        'data_siswa' => 'Data Siswa',
        'data_ayah' => 'Data Ayah',
        'data_ibu' => 'Data Ibu',
        'data_wali' => 'Data Wali',
        'data_alamat_siswa' => 'Alamat Siswa',
        'data_alamat_ayah' => 'Alamat Ayah',
        'data_alamat_ibu' => 'Alamat Ibu',
        'data_alamat_wali' => 'Alamat Wali',
    ];

    public const BAGIAN_OPSIONAL = [
        'prestasi' => 'Prestasi',
        'beasiswa' => 'Beasiswa & Bantuan',
        'pendidikan_lain' => 'Pendidikan Lain',
    ];

    public const STATUS_PSIKOLOGI = [
        'belum_upload' => 'Belum Upload',
        'menunggu' => 'Menunggu Verifikasi',
        'disetujui' => 'Disetujui',
        'ditolak' => 'Ditolak',
        'perbaiki' => 'Perlu Perbaikan',
    ];

    #[Url(keep: true)]
    public $activeTab = 'pendaftaran';
    public $filterJalur = '';
    public $filterStatusKelulusan = ''; // tab data_lengkap
    public $filterStatusDU = '';        // tab daftar_ulang (kategori rekap madrasah)
    public $filterStatusDUSeragam = ''; // tab daftar_ulang_seragam (kategori rekap seragam)
    public $filterStatusSeragam = '';   // tab seragam (kategori rekap ukuran)
    public $filterStatusTesPsikologi = '';
    public $filterJenisKelamin = '';
    public $tanggalMulai = '';
    public $tanggalAkhir = '';
    public $expandedYears = [];

    public function mount()
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            return redirect()->to('/login/admin');
        }

        if (!isset(self::TABS[$this->activeTab])) {
            $this->activeTab = 'pendaftaran';
        }
    }

    public function switchTab($tab)
    {
        if (!isset(self::TABS[$tab])) {
            return;
        }

        $this->activeTab = $tab;
        $this->filterJalur = '';
        $this->filterJenisKelamin = '';
    }

    public function resetFilters()
    {
        $this->reset(['filterJalur', 'filterJenisKelamin', 'tanggalMulai', 'tanggalAkhir', 'filterStatusKelulusan',
            'filterStatusDU', 'filterStatusDUSeragam', 'filterStatusSeragam', 'filterStatusTesPsikologi']);
    }

    public function render()
    {
        if (!isset(self::TABS[$this->activeTab])) {
            $this->activeTab = 'pendaftaran';
        }

        // Hanya data tab yang sedang dibuka yang dihitung (dulu semua tab dihitung setiap klik)
        $data = match ($this->activeTab) {
            'pendaftaran' => $this->dataPendaftaran(),
            'data_lengkap' => ['dataLengkapStats' => $this->getDataLengkapStats()],
            'daftar_ulang' => [
                'rekapStats' => RekapDaftarUlang::statsTab('madrasah', $this->filterRekap()),
                'tidakMelanjutkan' => RekapDaftarUlang::tidakMelanjutkan($this->filterRekap()),
                'diLuarWajib' => RekapDaftarUlang::queryDiLuarWajib($this->filterRekap())
                    ->with('jalurPendaftaran:id,nama')
                    ->orderBy('nama_lengkap')
                    ->limit(50)
                    ->get(['id', 'nomor_pendaftaran', 'nama_lengkap', 'status_pendaftaran', 'status_persetujuan_daftar_ulang', 'setuju_daftar_ulang', 'jalur_pendaftaran_id']),
            ],
            'daftar_ulang_seragam' => ['rekapStats' => RekapDaftarUlang::statsTab('seragam', $this->filterRekap())],
            'seragam' => ['rekapStats' => RekapDaftarUlang::statsTab('ukuran', $this->filterRekap())],
            'tes_psikologi' => ['tesPsikologiStats' => $this->getTesPsikologiStats()],
        };

        return view('livewire.admin.laporan', $data + [
            'tabs' => self::TABS,
            'jalurPendaftaran' => RekapPendaftaran::pilihanJalur(),
            'exportUrl' => $this->exportUrl(),
        ])->layout('components.layouts.dashboard', [
            'role' => 'admin',
            'pageTitle' => 'Laporan PPDB',
            'pageDescription' => 'Laporan dan statistik pendaftaran siswa baru',
        ]);
    }

    private function filterRekap(): array
    {
        return ['jalur' => $this->filterJalur, 'jenis_kelamin' => $this->filterJenisKelamin];
    }

    private function filterPendaftaran(): array
    {
        return ['jalur' => $this->filterJalur, 'start_date' => $this->tanggalMulai, 'end_date' => $this->tanggalAkhir];
    }

    private function dataPendaftaran(): array
    {
        return [
            'stats' => RekapPendaftaran::ringkasan($this->filterPendaftaran()),
            'jalurStats' => RekapPendaftaran::perJalur($this->filterPendaftaran()),
            'statistikTahun' => StatistikTahun::with('jalurStats')->orderBy('tahun', 'desc')->get(),
        ];
    }

    /** Link unduh Excel sesuai tab & filter yang aktif. */
    private function exportUrl(): ?string
    {
        [$jenis, $status] = match ($this->activeTab) {
            'data_lengkap' => ['data-lengkap', $this->filterStatusKelulusan],
            'daftar_ulang' => ['daftar-ulang', $this->filterStatusDU],
            'daftar_ulang_seragam' => ['daftar-ulang-seragam', $this->filterStatusDUSeragam],
            'seragam' => ['seragam', $this->filterStatusSeragam],
            'tes_psikologi' => ['tes-psikologi', $this->filterStatusTesPsikologi],
            default => [null, null],
        };

        if (!$jenis) {
            return null;
        }

        return route('admin.laporan.export', array_filter([
            'jenis' => $jenis,
            'jalur' => $this->filterJalur,
            'status' => $status,
            'jk' => $this->filterJenisKelamin,
        ], fn ($v) => $v !== '' && $v !== null));
    }

    /**
     * Kelengkapan formulir: berapa pendaftar yang tiap bagiannya sudah 100% terisi.
     * Satu kali hitung per pendaftar + withExists (dulu 11x hitung & 33 query per pendaftar).
     */
    private function getDataLengkapStats(): array
    {
        $statusFilter = match ($this->filterStatusKelulusan) {
            'lulus' => RekapPendaftaran::STATUS_LULUS,
            'tidak_lulus', 'cadangan' => [$this->filterStatusKelulusan],
            default => null,
        };

        $bagian = array_fill_keys(array_keys(self::BAGIAN_FORMULIR + self::BAGIAN_OPSIONAL), 0);
        // Jumlah pendaftar yang bagian itu memang berlaku (mis. alamat wali hanya bila ada wali)
        $berlaku = array_fill_keys(array_keys(self::BAGIAN_FORMULIR), 0);
        $total = 0;
        $semuaLengkap = 0;
        $totalPersen = 0;

        $query = Pendaftar::query()
            ->when($this->filterJalur, fn ($q) => $q->where('jalur_pendaftaran_id', $this->filterJalur))
            ->when($statusFilter, fn ($q) => $q->whereIn('status_pendaftaran', $statusFilter))
            ->withExists(['prestasi', 'beasiswa', 'pendidikan_lain']);

        foreach ($query->lazyById(300) as $pendaftar) {
            $detail = $pendaftar->getEmisCompletenessDetails();
            $total++;
            $totalPersen += $detail['percentage'];
            if ($detail['percentage'] >= 100) {
                $semuaLengkap++;
            }

            foreach (self::BAGIAN_FORMULIR as $key => $label) {
                $section = $detail['sections'][$key] ?? null;
                if (!$section || ($section['total'] ?? 0) === 0) {
                    continue; // tidak ada isian wajib di bagian ini untuk pendaftar tsb
                }
                $berlaku[$key]++;
                if ($section['filled'] >= $section['total']) {
                    $bagian[$key]++;
                }
            }
            foreach (self::BAGIAN_OPSIONAL as $key => $label) {
                if (($detail['sections'][$key]['filled'] ?? 0) > 0) {
                    $bagian[$key]++;
                }
            }
        }

        $kelulusan = RekapPendaftaran::ringkasan(['jalur' => $this->filterJalur]);

        return [
            'total' => $total,
            'bagian' => $bagian,
            'berlaku' => $berlaku,
            'semua_lengkap' => $semuaLengkap,
            'rata_rata' => $total > 0 ? (int) round($totalPersen / $total) : 0,
            'total_pendaftar' => $kelulusan['total'],
            'lulus' => $kelulusan['lulus'],
            'tidak_lulus' => $kelulusan['tidak_lulus'],
            'cadangan' => $kelulusan['cadangan'],
        ];
    }

    private function getTesPsikologiStats(): array
    {
        $baris = DB::table('tes_psikologi_pendaftar as t')
            ->join('pendaftar as p', 'p.id', '=', 't.pendaftar_id')
            ->when($this->filterJalur, fn ($q) => $q->where('p.jalur_pendaftaran_id', $this->filterJalur))
            ->when($this->filterStatusTesPsikologi, fn ($q) => $q->where('t.status_verifikasi', $this->filterStatusTesPsikologi))
            ->select('p.jalur_pendaftaran_id', 't.status_verifikasi', DB::raw('COUNT(*) AS jumlah'))
            ->groupBy('p.jalur_pendaftaran_id', 't.status_verifikasi')
            ->get();

        $kosong = array_fill_keys(array_keys(self::STATUS_PSIKOLOGI), 0) + ['total' => 0];
        $stats = $kosong;
        $perJalur = [];

        foreach ($baris as $b) {
            $status = isset(self::STATUS_PSIKOLOGI[$b->status_verifikasi]) ? $b->status_verifikasi : 'belum_upload';
            $stats[$status] += $b->jumlah;
            $stats['total'] += $b->jumlah;
            $perJalur[$b->jalur_pendaftaran_id] ??= $kosong;
            $perJalur[$b->jalur_pendaftaran_id][$status] += $b->jumlah;
            $perJalur[$b->jalur_pendaftaran_id]['total'] += $b->jumlah;
        }

        $jalurStats = RekapPendaftaran::pilihanJalur()
            ->when($this->filterJalur, fn ($c) => $c->where('id', (int) $this->filterJalur))
            ->map(fn ($jalur) => ['nama' => $jalur->nama, 'nonaktif' => !$jalur->aktif] + ($perJalur[$jalur->id] ?? $kosong))
            ->values()
            ->all();

        return ['stats' => $stats, 'jalur_stats' => $jalurStats];
    }

    public function simpanStatistikTahun()
    {
        $tahunSekarang = date('Y');
        $jalurStats = RekapPendaftaran::perJalur();

        $statistikTahun = StatistikTahun::updateOrCreate(
            ['tahun' => $tahunSekarang],
            [
                'total_kuota' => $jalurStats->sum('kuota'),
                'total_pendaftar' => $jalurStats->sum('pendaftar_count'),
                'menunggu' => $jalurStats->sum('menunggu_count'),
                'lengkapi' => $jalurStats->sum('lengkapi_count'),
                'perbaiki' => $jalurStats->sum('perbaiki_count'),
                'ditolak' => $jalurStats->sum('ditolak_count'),
                'disetujui' => $jalurStats->sum('disetujui_count'),
                'lulus' => $jalurStats->sum('lulus_count'),
                'tidak_lulus' => $jalurStats->sum('tidak_lulus_count'),
                'cadangan' => $jalurStats->sum('cadangan_count'),
                'laki_laki' => $jalurStats->sum('laki_laki_count'),
                'perempuan' => $jalurStats->sum('perempuan_count'),
                'madrasah' => $jalurStats->sum('madrasah_count'),
                'umum' => $jalurStats->sum('umum_count'),
            ]
        );

        StatistikJalurTahun::where('statistik_tahun_id', $statistikTahun->id)->delete();

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
            $this->expandedYears = array_values(array_diff($this->expandedYears, [$tahun]));
        } else {
            $this->expandedYears[] = $tahun;
        }
    }
}
