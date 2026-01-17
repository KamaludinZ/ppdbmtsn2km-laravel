<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VerifikasiBerkas extends Model
{
    protected $table = 'verifikasi_berkas';

    protected $fillable = [
        'pendaftar_id',
        'panitia_id',
        'status',
        'catatan',
        'tanggal_verifikasi',
        // Status dokumen individual
        'status_rapor',
        'catatan_rapor',
        'status_akte',
        'catatan_akte',
        'status_kk',
        'catatan_kk',
        'status_nisn',
        'catatan_nisn',
        'status_ijazah',
        'catatan_ijazah',
        'status_sertifikat',
        'catatan_sertifikat',
        'status_kip',
        'catatan_kip',
        'status_pkh',
        'catatan_pkh',
        'status_kks',
        'catatan_kks',
    ];

    protected $casts = [
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
     * Get the panitia who verified
     */
    public function panitia(): BelongsTo
    {
        return $this->belongsTo(Panitia::class);
    }

    /**
     * Send notification after saving
     * DISABLED: Auto-notification dinonaktifkan, gunakan manual send notification dari admin panel
     */
    protected static function booted()
    {
        // Auto-notification dinonaktifkan, gunakan manual send notification
        // static::created(function ($verifikasi) {
        //     if ($verifikasi->pendaftar) {
        //         $verifikasi->pendaftar->sendVerifikasiBerkasNotification($verifikasi->status, $verifikasi->catatan);
        //     }
        // });

        // static::updated(function ($verifikasi) {
        //     if ($verifikasi->pendaftar) {
        //         $verifikasi->pendaftar->sendVerifikasiBerkasNotification($verifikasi->status, $verifikasi->catatan);
        //     }
        // });
    }
}
