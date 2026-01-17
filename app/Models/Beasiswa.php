<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Beasiswa extends Model
{
    protected $fillable = [
        'pendaftar_id',
        'tahun',
        'kategori',
        'nama_beasiswa',
        'jenis_instansi_pemberi',
        'nama_instansi_pemberi',
        'jangka_waktu_bulan',
        'nominal_beasiswa',
    ];

    /**
     * Get the pendaftar that owns the beasiswa.
     */
    public function pendaftar(): BelongsTo
    {
        return $this->belongsTo(Pendaftar::class);
    }
}
