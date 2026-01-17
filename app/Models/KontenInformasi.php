<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KontenInformasi extends Model
{
    protected $table = 'konten_informasi';

    protected $fillable = [
        'judul',
        'isi',
        'urutan',
        'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
        'urutan' => 'integer',
    ];

    /**
     * Scope untuk konten yang aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }

    /**
     * Scope untuk konten diurutkan berdasarkan urutan
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan', 'asc');
    }
}
