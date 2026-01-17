<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersyaratanUmum extends Model
{
    protected $table = 'persyaratan_umum';

    protected $fillable = [
        'persyaratan',
        'urutan',
        'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
        'urutan' => 'integer',
    ];

    /**
     * Scope untuk persyaratan yang aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }

    /**
     * Scope untuk persyaratan diurutkan berdasarkan urutan
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan', 'asc');
    }
}
