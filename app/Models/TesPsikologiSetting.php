<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TesPsikologiSetting extends Model
{
    protected $fillable = [
        'informasi',
        'rekening_bank_nama',
        'rekening_nomor',
        'rekening_atas_nama',
        'rekening_keterangan',
        'aktif'
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    // Static method to get single setting
    public static function getSetting()
    {
        return self::first() ?? self::create([
            'informasi' => 'Informasi mengenai tes psikologi akan ditampilkan di sini.',
            'rekening_bank_nama' => null,
            'rekening_nomor' => null,
            'rekening_atas_nama' => null,
            'rekening_keterangan' => null,
            'aktif' => false
        ]);
    }
}
