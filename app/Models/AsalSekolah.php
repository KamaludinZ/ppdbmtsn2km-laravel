<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AsalSekolah extends Model
{
    protected $table = 'asal_sekolah';

    protected $fillable = [
        'nama_sekolah',
        'npsn',
        'nsm',
        'jenis_sekolah_asal',
        'alamat',
        'kecamatan',
        'kabupaten_kota',
        'provinsi',
        'status_sekolah',
        'jenjang',
        'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    /**
     * Scope to get only active schools
     */
    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }

    /**
     * Scope to search schools by name
     */
    public function scopeCariNama($query, $nama)
    {
        return $query->where('nama_sekolah', 'like', "%{$nama}%");
    }

    /**
     * Scope to filter by province
     */
    public function scopeProvinsi($query, $provinsi)
    {
        return $query->where('provinsi', $provinsi);
    }

    /**
     * Scope to filter by status (negeri/swasta)
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status_sekolah', $status);
    }

    /**
     * Get the pendaftar (students) associated with this school
     */
    public function pendaftar()
    {
        return $this->hasMany(Pendaftar::class, 'asal_sekolah_id');
    }

    /**
     * Get the pendaftar records that match this school by name
     */
    public function pendaftarByName()
    {
        return $this->hasMany(Pendaftar::class, 'asal_sekolah', 'nama_sekolah');
    }
}
