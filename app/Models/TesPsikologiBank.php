<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TesPsikologiBank extends Model
{
    protected $table = 'tes_psikologi_bank';

    protected $fillable = [
        'nama_bank',
        'nomor_rekening',
        'atas_nama',
        'keterangan',
        'urutan',
        'aktif'
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    // Scope for active banks
    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }

    // Scope for ordered banks
    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan')->orderBy('nama_bank');
    }
}
