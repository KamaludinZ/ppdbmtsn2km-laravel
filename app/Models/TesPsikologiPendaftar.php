<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TesPsikologiPendaftar extends Model
{
    protected $table = 'tes_psikologi_pendaftar';

    protected $fillable = [
        'pendaftar_id',
        'opsi_id',
        'waktu_transfer',
        'bank_id',
        'bukti_transfer',
        'tanggal_upload',
        'status_verifikasi',
        'catatan_verifikasi',
        'tanggal_verifikasi',
        'verified_by'
    ];

    protected $casts = [
        'waktu_transfer' => 'datetime',
        'tanggal_upload' => 'datetime',
        'tanggal_verifikasi' => 'datetime',
    ];

    // Relationships
    public function pendaftar()
    {
        return $this->belongsTo(Pendaftar::class);
    }

    public function opsi()
    {
        return $this->belongsTo(TesPsikologiOpsi::class, 'opsi_id');
    }

    public function bank()
    {
        return $this->belongsTo(TesPsikologiBank::class, 'bank_id');
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // Scopes
    public function scopeBelumUpload($query)
    {
        return $query->where('status_verifikasi', 'belum_upload');
    }

    public function scopeMenungguVerifikasi($query)
    {
        return $query->where('status_verifikasi', 'menunggu');
    }

    public function scopeDisetujui($query)
    {
        return $query->where('status_verifikasi', 'disetujui');
    }

    public function scopeDitolak($query)
    {
        return $query->where('status_verifikasi', 'ditolak');
    }

    public function scopePerbaiki($query)
    {
        return $query->where('status_verifikasi', 'perbaiki');
    }
}
