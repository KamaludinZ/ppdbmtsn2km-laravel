<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Seragam extends Model
{
    protected $table = 'seragam';

    protected $fillable = [
        'pendaftar_id',
        'nominal_seragam',
        'nominal_seragam_awal',
        'waktu_pembayaran_seragam',
        'paket_seragam',
        'opsi_seragam',  // NEW: Selected option from package (JSON)
        'opsi_pembayaran_seragam',
        'bank_seragam',
        'bukti_pembayaran',
        'nama_pengirim_seragam',
        // Detailed uniform sizes (NEW - active fields only)
        'ukuran_jas',
        'ukuran_baju_olahraga',
        'ukuran_celana_olahraga',
        'ukuran_baju_harian',
        'ukuran_celana_rok_harian',
        'ukuran_kopyah',
        'sudah_diukur',
        'sudah_dipesan',
        'sudah_diterima',
        'status_seragam',
        'status_ukuran',
        'catatan',
        'catatan_seragam',
        'catatan_internal_seragam',
        'catatan_ukuran',
        'catatan_internal_ukuran',
        'tanggal_ukur',
        'tanggal_pesan',
        'tanggal_terima',
        'petugas_id',
        'opsi_lainnya_dropdown_individual',
        'opsi_lainnya_konfirmasi_individual',
    ];

    protected $casts = [
        'nominal_seragam' => 'array',
        'nominal_seragam_awal' => 'array',
        'waktu_pembayaran_seragam' => 'datetime',
        'sudah_diukur' => 'boolean',
        'sudah_dipesan' => 'boolean',
        'sudah_diterima' => 'boolean',
        'tanggal_ukur' => 'datetime',
        'tanggal_pesan' => 'datetime',
        'tanggal_terima' => 'datetime',
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
     * Get the petugas (panitia)
     */
    public function petugas(): BelongsTo
    {
        return $this->belongsTo(Panitia::class, 'petugas_id');
    }
}
