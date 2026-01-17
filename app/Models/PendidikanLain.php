<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PendidikanLain extends Model
{
    protected $fillable = [
        'pendaftar_id',
        'nama_lembaga',
        'jenis_lembaga',
        'mulai_belajar',
        'frekuensi_belajar',
        'lokasi_lembaga',
    ];

    protected $casts = [
        'mulai_belajar' => 'date',
    ];

    /**
     * Get the pendaftar that owns the pendidikan lain.
     */
    public function pendaftar(): BelongsTo
    {
        return $this->belongsTo(Pendaftar::class);
    }
}
