<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LinkTerkait extends Model
{
    protected $fillable = [
        'nama',
        'url',
        'urutan',
        'aktif'
    ];

    protected $casts = [
        'aktif' => 'boolean',
        'urutan' => 'integer'
    ];

    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan', 'asc');
    }
}