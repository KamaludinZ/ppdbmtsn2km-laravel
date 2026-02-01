<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Pendaftar;
use App\Models\JalurPendaftaran;
use App\Models\Setting;
use App\Exports\LaporanPendaftaranExport;

class ExportController extends Controller
{
    /**
     * Generate Laporan Pendaftaran PDF
     */
    public function generatePDF(Request $request)
    {
        // Get filter parameters
        $jalurId = $request->get('jalur');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        // Get per jalur statistics (only active jalur)
        $jalurStats = JalurPendaftaran::aktif()
            ->withCount([
                'pendaftar' => function ($q) use ($startDate, $endDate) {
                    $q->when($startDate, function ($query) use ($startDate) {
                        $query->whereDate('tanggal_daftar', '>=', $startDate);
                    })
                    ->when($endDate, function ($query) use ($endDate) {
                        $query->whereDate('tanggal_daftar', '<=', $endDate);
                    });
                },
                'pendaftar as menunggu_count' => function ($q) use ($startDate, $endDate) {
                    $q->where('status_pendaftaran', 'menunggu_verifikasi')
                    ->when($startDate, function ($query) use ($startDate) {
                        $query->whereDate('tanggal_daftar', '>=', $startDate);
                    })
                    ->when($endDate, function ($query) use ($endDate) {
                        $query->whereDate('tanggal_daftar', '<=', $endDate);
                    });
                },
                'pendaftar as lengkapi_count' => function ($q) use ($startDate, $endDate) {
                    $q->where('status_pendaftaran', 'lengkapi_data')
                    ->when($startDate, function ($query) use ($startDate) {
                        $query->whereDate('tanggal_daftar', '>=', $startDate);
                    })
                    ->when($endDate, function ($query) use ($endDate) {
                        $query->whereDate('tanggal_daftar', '<=', $endDate);
                    });
                },
                'pendaftar as perbaiki_count' => function ($q) use ($startDate, $endDate) {
                    $q->where('status_pendaftaran', 'perbaiki_data')
                    ->when($startDate, function ($query) use ($startDate) {
                        $query->whereDate('tanggal_daftar', '>=', $startDate);
                    })
                    ->when($endDate, function ($query) use ($endDate) {
                        $query->whereDate('tanggal_daftar', '<=', $endDate);
                    });
                },
                'pendaftar as ditolak_count' => function ($q) use ($startDate, $endDate) {
                    $q->where('status_pendaftaran', 'data_ditolak')
                    ->when($startDate, function ($query) use ($startDate) {
                        $query->whereDate('tanggal_daftar', '>=', $startDate);
                    })
                    ->when($endDate, function ($query) use ($endDate) {
                        $query->whereDate('tanggal_daftar', '<=', $endDate);
                    });
                },
                'pendaftar as disetujui_count' => function ($q) use ($startDate, $endDate) {
                    $q->whereIn('status_pendaftaran', ['data_disetujui', 'lulus', 'diterima', 'tidak_lulus', 'cadangan', 'ditolak'])
                    ->when($startDate, function ($query) use ($startDate) {
                        $query->whereDate('tanggal_daftar', '>=', $startDate);
                    })
                    ->when($endDate, function ($query) use ($endDate) {
                        $query->whereDate('tanggal_daftar', '<=', $endDate);
                    });
                },
                'pendaftar as lulus_count' => function ($q) use ($startDate, $endDate) {
                    $q->whereIn('status_pendaftaran', ['lulus', 'diterima'])
                    ->when($startDate, function ($query) use ($startDate) {
                        $query->whereDate('tanggal_daftar', '>=', $startDate);
                    })
                    ->when($endDate, function ($query) use ($endDate) {
                        $query->whereDate('tanggal_daftar', '<=', $endDate);
                    });
                },
                'pendaftar as tidak_lulus_count' => function ($q) use ($startDate, $endDate) {
                    $q->where('status_pendaftaran', 'tidak_lulus')
                    ->when($startDate, function ($query) use ($startDate) {
                        $query->whereDate('tanggal_daftar', '>=', $startDate);
                    })
                    ->when($endDate, function ($query) use ($endDate) {
                        $query->whereDate('tanggal_daftar', '<=', $endDate);
                    });
                },
                'pendaftar as cadangan_count' => function ($q) use ($startDate, $endDate) {
                    $q->where('status_pendaftaran', 'cadangan')
                    ->when($startDate, function ($query) use ($startDate) {
                        $query->whereDate('tanggal_daftar', '>=', $startDate);
                    })
                    ->when($endDate, function ($query) use ($endDate) {
                        $query->whereDate('tanggal_daftar', '<=', $endDate);
                    });
                },
                'pendaftar as laki_laki_count' => function ($q) use ($startDate, $endDate) {
                    $q->where('jenis_kelamin', 'L')
                    ->when($startDate, function ($query) use ($startDate) {
                        $query->whereDate('tanggal_daftar', '>=', $startDate);
                    })
                    ->when($endDate, function ($query) use ($endDate) {
                        $query->whereDate('tanggal_daftar', '<=', $endDate);
                    });
                },
                'pendaftar as perempuan_count' => function ($q) use ($startDate, $endDate) {
                    $q->where('jenis_kelamin', 'P')
                    ->when($startDate, function ($query) use ($startDate) {
                        $query->whereDate('tanggal_daftar', '>=', $startDate);
                    })
                    ->when($endDate, function ($query) use ($endDate) {
                        $query->whereDate('tanggal_daftar', '<=', $endDate);
                    });
                },
                'pendaftar as madrasah_count' => function ($q) use ($startDate, $endDate) {
                    $q->where('jenis_sekolah_asal', 'madrasah')
                    ->when($startDate, function ($query) use ($startDate) {
                        $query->whereDate('tanggal_daftar', '>=', $startDate);
                    })
                    ->when($endDate, function ($query) use ($endDate) {
                        $query->whereDate('tanggal_daftar', '<=', $endDate);
                    });
                },
                'pendaftar as umum_count' => function ($q) use ($startDate, $endDate) {
                    $q->where('jenis_sekolah_asal', 'umum')
                    ->when($startDate, function ($query) use ($startDate) {
                        $query->whereDate('tanggal_daftar', '>=', $startDate);
                    })
                    ->when($endDate, function ($query) use ($endDate) {
                        $query->whereDate('tanggal_daftar', '<=', $endDate);
                    });
                }
            ])
            ->ordered()
            ->get();

        $data = [
            'title' => 'Laporan Pendaftaran',
            'date' => date('d F Y'),
            'periode' => Setting::get('tahun_pelajaran', date('Y') . '/' . (date('Y') + 1)),
            'jalurStats' => $jalurStats,
            // Identitas Cetak (Kop PDF)
            'printLogoLeft' => Setting::get('print_logo_left', ''),
            'printLogoRight' => Setting::get('print_logo_right', ''),
            'printInstitutionName' => Setting::get('print_institution_name', 'MADRASAH TSANAWIYAH NEGERI 2 KOTA MALANG'),
            'printInstitutionAddress' => Setting::get('print_institution_address', 'Jl. Bandung No. 7 Kota Malang, Jawa Timur 65117'),
            'printInstitutionPhone' => Setting::get('print_institution_phone', '(0341) 551276'),
            'printInstitutionEmail' => Setting::get('print_institution_email', 'ppdb@mtsn2kotamalang.sch.id'),
            'printInstitutionWebsite' => Setting::get('print_institution_website', 'www.mtsn2kotamalang.sch.id'),
        ];

        $pdf = Pdf::loadView('exports.laporan-pendaftaran-pdf', $data);
        $pdf->setPaper('a4', 'landscape');

        return $pdf->download('laporan-pendaftaran-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Generate Laporan Pendaftaran Excel
     */
    public function generateExcel(Request $request)
    {
        $jalurId = $request->get('jalur');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        return Excel::download(
            new LaporanPendaftaranExport($jalurId, null, $startDate, $endDate),
            'laporan-pendaftaran-' . date('Y-m-d') . '.xlsx'
        );
    }

    /**
     * Demo export page
     */
    public function index()
    {
        return view('exports.index');
    }
}
