<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    protected $table = 'pengumuman';

    protected $fillable = [
        'judul',
        'konten',
        'tanggal_publikasi',
        'kategori',
        'is_penting',
        'urutan',
        'aktif',
    ];

    protected $casts = [
        'tanggal_publikasi' => 'date',
        'is_penting' => 'boolean',
        'aktif' => 'boolean',
    ];

    // Scope untuk pengumuman yang aktif
    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }

    // Scope untuk pengumuman penting
    public function scopePenting($query)
    {
        return $query->where('is_penting', true);
    }

    // Scope untuk urutan
    public function scopeOrdered($query)
    {
        return $query->orderBy('tanggal_publikasi', 'desc')->orderBy('urutan', 'asc');
    }

    // Scope untuk filter by kategori
    public function scopeKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    // Scope untuk mengambil pengumuman non-pendaftar
    public function scopeNonPendaftar($query)
    {
        return $query->where('kategori', '!=', 'pendaftar');
    }
}
