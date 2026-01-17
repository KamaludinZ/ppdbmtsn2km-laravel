<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Prestasi extends Model
{
    protected $fillable = [
        'pendaftar_id',
        'tahun',
        'nama_lomba',
        'bidang_lomba',
        'nama_penyelenggara',
        'tingkat_lomba',
        'peringkat',
        'kategori_lomba',
        'file_sertifikat',
    ];

    /**
     * Get the pendaftar that owns the prestasi.
     */
    public function pendaftar(): BelongsTo
    {
        return $this->belongsTo(Pendaftar::class);
    }
}
