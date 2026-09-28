<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class DaftarUlangExport implements WithMultipleSheets
{
    protected $status;
    protected $search;
    protected $jalur;

    /**
     * @param  string|null  $status  kategori rekap daftar ulang MADRASAH; ketiga sheet memuat pendaftar yang sama
     */
    public function __construct($status = null, $search = null, $jalur = null)
    {
        $this->status = $status;
        $this->search = $search;
        $this->jalur = $jalur;
    }

    public function sheets(): array
    {
        return [
            new DaftarUlangMadrasahSheet($this->status, $this->search, $this->jalur),
            new DaftarUlangSeragamSheet($this->status, $this->search, $this->jalur, 'madrasah'),
            new DaftarUlangUkuranSheet($this->status, $this->search, $this->jalur, 'madrasah'),
        ];
    }
}
