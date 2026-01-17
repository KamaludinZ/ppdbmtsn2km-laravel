<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Panitia extends Model
{
    protected $table = 'panitia';

    protected $fillable = [
        'user_id',
        'nip',
        'nama_lengkap',
        'role',
        'telepon',
        'email',
        'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    /**
     * Get the user that owns this panitia
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all verifikasi berkas done by this panitia
     */
    public function verifikasiBerkas(): HasMany
    {
        return $this->hasMany(VerifikasiBerkas::class);
    }

    /**
     * Get all daftar ulang handled by this panitia
     */
    public function daftarUlang(): HasMany
    {
        return $this->hasMany(DaftarUlang::class);
    }

    /**
     * Get all payment verifications
     */
    public function pembayaranVerified(): HasMany
    {
        return $this->hasMany(Pembayaran::class, 'verifikator_id');
    }

    /**
     * Get all seragam measurements
     */
    public function seragam(): HasMany
    {
        return $this->hasMany(Seragam::class, 'petugas_id');
    }
}
