<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TesPsikologiOpsi extends Model
{
    protected $table = 'tes_psikologi_opsi';

    protected $fillable = [
        'nama',
        'deskripsi',
        'urutan',
        'aktif'
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    // Scope for active options
    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }

    // Scope for ordered options
    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan')->orderBy('nama');
    }
}
