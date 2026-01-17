<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';

    protected $fillable = [
        'pendaftar_id',
        'jenis_pembayaran',
        'jumlah',
        'cicilan_ke',
        'total_cicilan',
        'metode_pembayaran',
        'nomor_referensi',
        'status',
        'catatan',
        'bukti_pembayaran',
        'tanggal_bayar',
        'tanggal_verifikasi',
        'verifikator_id',
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
        'cicilan_ke' => 'integer',
        'total_cicilan' => 'integer',
        'tanggal_bayar' => 'datetime',
        'tanggal_verifikasi' => 'datetime',
    ];

    /**
     * Get the pendaftar
     */
    public function pendaftar(): BelongsTo
    {
        return $this->belongsTo(Pendaftar::class);
    }

    /**
     * Get the verifikator (panitia)
     */
    public function verifikator(): BelongsTo
    {
        return $this->belongsTo(Panitia::class, 'verifikator_id');
    }
}
