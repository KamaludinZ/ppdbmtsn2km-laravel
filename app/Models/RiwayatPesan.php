<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiwayatPesan extends Model
{
    protected $table = 'riwayat_pesan';

    protected $fillable = [
        'pendaftar_id',
        'user_id',
        'jenis_pesan',
        'judul',
        'isi_pesan',
        'penerima',
        'status',
        'error_message',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relasi ke Pendaftar
     */
    public function pendaftar(): BelongsTo
    {
        return $this->belongsTo(Pendaftar::class);
    }

    /**
     * Relasi ke User (admin yang kirim)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope untuk filter berdasarkan pendaftar
     */
    public function scopeForPendaftar($query, $pendaftarId)
    {
        return $query->where('pendaftar_id', $pendaftarId);
    }

    /**
     * Scope untuk filter berdasarkan jenis pesan
     */
    public function scopeByJenis($query, $jenis)
    {
        return $query->where('jenis_pesan', $jenis);
    }

    /**
     * Scope untuk filter berdasarkan status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk get latest
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Get badge class berdasarkan status
     */
    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            'terkirim' => 'bg-green-100 text-green-800',
            'gagal' => 'bg-red-100 text-red-800',
            'pending' => 'bg-yellow-100 text-yellow-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Get status label
     */
    public function getStatusLabel(): string
    {
        return match($this->status) {
            'terkirim' => 'Terkirim',
            'gagal' => 'Gagal',
            'pending' => 'Pending',
            default => 'Unknown',
        };
    }

    /**
     * Get jenis pesan label
     */
    public function getJenisPesanLabel(): string
    {
        return match($this->jenis_pesan) {
            'email' => 'Email',
            'whatsapp' => 'WhatsApp',
            default => $this->jenis_pesan,
        };
    }
}
