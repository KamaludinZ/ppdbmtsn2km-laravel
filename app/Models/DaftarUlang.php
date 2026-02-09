<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DaftarUlang extends Model
{
    protected $table = 'daftar_ulang';

    protected $fillable = [
        'pendaftar_id',
        'nominal_biaya',
        'nominal_biaya_awal',
        'waktu_pembayaran_madrasah',
        'status',
        'opsi_pembayaran',
        'metode_pembayaran',
        'bukti_pembayaran_madrasah',
        'nama_pengirim',
        'surat_pernyataan_path',
        'pembayaran_lunas',
        'seragam_diukur',
        'berkas_lengkap',
        'catatan',
        'catatan_internal',
        'tanggal_daftar_ulang',
        'batas_daftar_ulang',
        'panitia_id',
        'opsi_lainnya_dropdown_individual',
        'opsi_lainnya_konfirmasi_individual',
    ];

    protected $casts = [
        'nominal_biaya' => 'array',
        'nominal_biaya_awal' => 'array',
        'waktu_pembayaran_madrasah' => 'datetime',
        'pembayaran_lunas' => 'boolean',
        'seragam_diukur' => 'boolean',
        'berkas_lengkap' => 'boolean',
        'tanggal_daftar_ulang' => 'datetime',
        'batas_daftar_ulang' => 'datetime',
        'opsi_lainnya_dropdown_individual' => 'boolean',
        'opsi_lainnya_konfirmasi_individual' => 'boolean',
    ];

    /**
     * Get the pendaftar
     */
    public function pendaftar(): BelongsTo
    {
        return $this->belongsTo(Pendaftar::class);
    }

    /**
     * Get the panitia
     */
    public function panitia(): BelongsTo
    {
        return $this->belongsTo(Panitia::class);
    }

    /**
     * Send notification after saving
     */
    protected static function booted()
    {
        // Notification on created removed as per request (user choice doesn't need auto-notification)
        /*
        static::created(function ($daftarUlang) {
            if ($daftarUlang->pendaftar) {
                $daftarUlang->pendaftar->sendVerifikasiDaftarUlangNotification($daftarUlang->status, $daftarUlang->catatan);
            }
        });
        */

        static::updated(function ($daftarUlang) {
            // Only send notification if status actually changed and is significant
            if ($daftarUlang->isDirty('status') && $daftarUlang->pendaftar) {
                $daftarUlang->pendaftar->sendVerifikasiDaftarUlangNotification($daftarUlang->status, $daftarUlang->catatan);
            }
        });
    }
}
