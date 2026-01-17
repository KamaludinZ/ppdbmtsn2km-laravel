<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Seragam extends Model
{
    protected $table = 'seragam';

    protected $fillable = [
        'pendaftar_id',
        'tinggi_badan',
        'berat_badan',
        'ukuran_baju',
        'ukuran_celana',
        'lingkar_dada',
        'lingkar_pinggang',
        'panjang_lengan',
        'ukuran_sepatu',
        'ukuran_topi',
        'sudah_diukur',
        'sudah_dipesan',
        'sudah_diterima',
        'status_seragam',
        'status_ukuran',
        'catatan',
        'tanggal_ukur',
        'tanggal_pesan',
        'tanggal_terima',
        'petugas_id',
    ];

    protected $casts = [
        'tinggi_badan' => 'integer',
        'berat_badan' => 'integer',
        'lingkar_dada' => 'integer',
        'lingkar_pinggang' => 'integer',
        'panjang_lengan' => 'integer',
        'sudah_diukur' => 'boolean',
        'sudah_dipesan' => 'boolean',
        'sudah_diterima' => 'boolean',
        'tanggal_ukur' => 'datetime',
        'tanggal_pesan' => 'datetime',
        'tanggal_terima' => 'datetime',
    ];

    /**
     * Get the pendaftar
     */
    public function pendaftar(): BelongsTo
    {
        return $this->belongsTo(Pendaftar::class);
    }

    /**
     * Get the petugas (panitia)
     */
    public function petugas(): BelongsTo
    {
        return $this->belongsTo(Panitia::class, 'petugas_id');
    }
}
