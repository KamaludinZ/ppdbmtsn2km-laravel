<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class DaftarUlangExport implements WithMultipleSheets
{
    protected $status;
    protected $search;

    public function __construct($status = null, $search = null)
    {
        $this->status = $status;
        $this->search = $search;
    }

    public function sheets(): array
    {
        return [
            new DaftarUlangMadrasahSheet($this->status, $this->search),
            new DaftarUlangSeragamSheet($this->status, $this->search),
            new DaftarUlangUkuranSheet($this->status, $this->search),
        ];
    }
}
