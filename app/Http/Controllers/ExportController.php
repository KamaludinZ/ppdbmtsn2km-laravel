<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    /**
     * Generate sample PDF
     */
    public function generatePDF()
    {
        $data = [
            'title' => 'Contoh Laporan PDF',
            'date' => date('d/m/Y'),
            'items' => [
                ['no' => 1, 'nama' => 'Ahmad Zainudin', 'nisn' => '0012345678', 'kelas' => '7A'],
                ['no' => 2, 'nama' => 'Siti Nurhaliza', 'nisn' => '0012345679', 'kelas' => '7A'],
                ['no' => 3, 'nama' => 'Muhammad Rizki', 'nisn' => '0012345680', 'kelas' => '7B'],
                ['no' => 4, 'nama' => 'Fatimah Zahra', 'nisn' => '0012345681', 'kelas' => '7B'],
                ['no' => 5, 'nama' => 'Abdul Rahman', 'nisn' => '0012345682', 'kelas' => '7C'],
            ],
        ];

        $pdf = Pdf::loadView('exports.sample-pdf', $data);

        // Set paper size dan orientasi
        $pdf->setPaper('a4', 'portrait');

        // Download PDF
        return $pdf->download('laporan-' . date('Y-m-d') . '.pdf');

        // Atau tampilkan di browser (uncomment baris di bawah)
        // return $pdf->stream('laporan-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Generate sample Excel
     */
    public function generateExcel()
    {
        return Excel::download(new \App\Exports\SampleExport, 'laporan-' . date('Y-m-d') . '.xlsx');
    }

    /**
     * Demo export page
     */
    public function index()
    {
        return view('exports.index');
    }
}
