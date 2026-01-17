<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoPanduan extends Model
{
    protected $table = 'video_panduan';

    protected $fillable = [
        'judul',
        'deskripsi',
        'url_video',
        'kategori',
        'aktif',
        'urutan',
    ];

    protected $casts = [
        'aktif' => 'boolean',
        'urutan' => 'integer',
    ];

    /**
     * Scope untuk video yang aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }

    /**
     * Scope untuk video diurutkan berdasarkan urutan
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan', 'asc');
    }

    /**
     * Scope untuk video berdasarkan kategori
     */
    public function scopeByKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }
}
