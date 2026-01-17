<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimelinePendaftaran extends Model
{
    protected $table = 'timeline_pendaftaran';

    protected $fillable = [
        'judul_kegiatan',
        'deskripsi',
        'tanggal_mulai',
        'tanggal_selesai',
        'waktu_mulai',
        'waktu_selesai',
        'tempat',
        'jalur_kode',
        'warna',
        'aktif',
        'urutan',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'aktif' => 'boolean',
        'urutan' => 'integer',
    ];

    /**
     * Scope untuk timeline yang aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }

    /**
     * Scope untuk timeline diurutkan berdasarkan urutan
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan', 'asc');
    }

    /**
     * Scope untuk timeline berdasarkan jalur
     */
    public function scopeByJalur($query, $jalurKode)
    {
        return $query->where('jalur_kode', $jalurKode);
    }
}
