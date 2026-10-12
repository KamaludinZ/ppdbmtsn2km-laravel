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

        // Statistik per jalur (termasuk jalur nonaktif yang masih punya pendaftar)
        $jalurStats = \App\Support\RekapPendaftaran::perJalur([
            'jalur' => $jalurId,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);

        $data = [
            'title' => 'Laporan Pendaftaran',
            'date' => date('d F Y'),
            'periode' => Setting::get('tahun_pelajaran', date('Y') . '/' . (date('Y') + 1)),
            'jalurStats' => $jalurStats,
            'persetujuanJalur' => \App\Support\RekapPendaftaran::persetujuanPerJalur([
                'jalur' => $jalurId,
                'start_date' => $startDate,
                'end_date' => $endDate,
            ]),
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
