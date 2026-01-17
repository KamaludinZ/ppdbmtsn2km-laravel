<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KontakPanitia extends Model
{
    protected $fillable = [
        'nama',
        'nomor_wa',
        'keterangan',
        'urutan',
        'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    // Scope untuk mengambil kontak yang aktif
    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }

    // Scope untuk mengurutkan berdasarkan urutan
    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan', 'asc');
    }
}
