<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Keahlian extends Model
{
    protected $table = 'keahlian';

    protected $fillable = [
        'pendaftar_id',
        'bidang_keahlian',
        'nama_keahlian',
        'sertifikasi',
        'lembaga_penyelenggara',
        'hasil_tingkat_skor',
        'file_bukti_sertifikat',
    ];

    /**
     * Get the pendaftar that owns the keahlian.
     */
    public function pendaftar(): BelongsTo
    {
        return $this->belongsTo(Pendaftar::class);
    }
}
