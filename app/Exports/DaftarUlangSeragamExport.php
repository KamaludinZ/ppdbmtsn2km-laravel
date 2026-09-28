<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class DaftarUlangSeragamExport implements WithMultipleSheets
{
    protected $status;
    protected $search;
    protected $jalur;
    protected $jenisKelamin;

    /**
     * @param  string|null  $status  kategori rekap daftar ulang SERAGAM
     */
    public function __construct($status = null, $search = null, $jalur = null, $jenisKelamin = null)
    {
        $this->status = $status;
        $this->search = $search;
        $this->jalur = $jalur;
        $this->jenisKelamin = $jenisKelamin;
    }

    public function sheets(): array
    {
        return [
            new DaftarUlangSeragamSheet($this->status, $this->search, $this->jalur, 'seragam', $this->jenisKelamin),
        ];
    }
}
