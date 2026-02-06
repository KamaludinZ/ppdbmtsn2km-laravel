<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class MessageQueue extends Model
{
    protected $table = 'message_queue';

    protected $fillable = [
        'pendaftar_id',
        'user_id',
        'jenis_pesan',
        'penerima',
        'judul',
        'isi_pesan',
        'template_name',
        'status',
        'priority',
        'is_manual',
        'scheduled_at',
        'processed_at',
        'retry_count',
        'max_retries',
        'error_message',
        'error_trace',
        'response_data',
        'metadata',
        'notes',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'processed_at' => 'datetime',
        'metadata' => 'array',
        'is_manual' => 'boolean',
        'retry_count' => 'integer',
        'max_retries' => 'integer',
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
     * Relasi ke User (admin yang queue)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope untuk pesan yang siap dikirim
     */
    public function scopeReadyToSend($query)
    {
        return $query->where('status', 'queued')
            ->where('scheduled_at', '<=', now())
            ->orderBy('priority', 'desc')
            ->orderBy('scheduled_at', 'asc');
    }

    /**
     * Scope untuk filter WhatsApp only
     */
    public function scopeWhatsAppOnly($query)
    {
        return $query->where('jenis_pesan', 'whatsapp');
    }

    /**
     * Scope untuk filter Email only
     */
    public function scopeEmailOnly($query)
    {
        return $query->where('jenis_pesan', 'email');
    }

    /**
     * Scope untuk filter berdasarkan status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk pesan manual
     */
    public function scopeManual($query)
    {
        return $query->where('is_manual', true);
    }

    /**
     * Scope untuk pesan otomatis
     */
    public function scopeAutomatic($query)
    {
        return $query->where('is_manual', false);
    }

    /**
     * Get badge class berdasarkan status
     */
    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            'sent' => 'bg-green-100 text-green-800',
            'failed' => 'bg-red-100 text-red-800',
            'queued' => 'bg-yellow-100 text-yellow-800',
            'processing' => 'bg-blue-100 text-blue-800',
            'cancelled' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Get status label
     */
    public function getStatusLabel(): string
    {
        return match($this->status) {
            'sent' => 'Terkirim',
            'failed' => 'Gagal',
            'queued' => 'Antrian',
            'processing' => 'Memproses',
            'cancelled' => 'Dibatalkan',
            default => 'Unknown',
        };
    }

    /**
     * Get priority badge
     */
    public function getPriorityBadgeClass(): string
    {
        return match($this->priority) {
            'high' => 'bg-red-100 text-red-800',
            'normal' => 'bg-blue-100 text-blue-800',
            'low' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Get priority label
     */
    public function getPriorityLabel(): string
    {
        return match($this->priority) {
            'high' => 'Tinggi',
            'normal' => 'Normal',
            'low' => 'Rendah',
            default => 'Normal',
        };
    }

    /**
     * Mark as sent
     */
    public function markAsSent($responseData = null): void
    {
        $this->update([
            'status' => 'sent',
            'processed_at' => now(),
            'response_data' => $responseData,
        ]);

        // Log ke riwayat pesan
        $this->logToRiwayat('terkirim', null);
    }

    /**
     * Mark as failed
     */
    public function markAsFailed(string $errorMessage, ?string $errorTrace = null): void
    {
        $this->update([
            'status' => 'failed',
            'processed_at' => now(),
            'error_message' => $errorMessage,
            'error_trace' => $errorTrace,
            'retry_count' => $this->retry_count + 1,
        ]);

        // Log ke riwayat pesan
        $this->logToRiwayat('gagal', $errorMessage);
    }

    /**
     * Mark as processing
     */
    public function markAsProcessing(): void
    {
        $this->update([
            'status' => 'processing',
        ]);
    }

    /**
     * Retry if possible
     */
    public function canRetry(): bool
    {
        return $this->retry_count < $this->max_retries;
    }

    /**
     * Requeue for retry
     */
    public function requeue($delayMinutes = 5): void
    {
        if ($this->canRetry()) {
            $this->update([
                'status' => 'queued',
                'scheduled_at' => now()->addMinutes($delayMinutes),
            ]);
        }
    }

    /**
     * Log to riwayat_pesan table
     */
    private function logToRiwayat(string $status, ?string $errorMessage = null): void
    {
        RiwayatPesan::create([
            'pendaftar_id' => $this->pendaftar_id,
            'user_id' => $this->user_id,
            'jenis_pesan' => $this->jenis_pesan,
            'judul' => $this->judul,
            'isi_pesan' => $this->isi_pesan,
            'penerima' => $this->penerima,
            'status' => $status,
            'error_message' => $errorMessage,
            'sent_at' => $status === 'terkirim' ? now() : null,
        ]);
    }

    /**
     * Check if message is scheduled for future
     */
    public function isScheduledForFuture(): bool
    {
        return $this->scheduled_at && $this->scheduled_at->isFuture();
    }

    /**
     * Check if message is overdue
     */
    public function isOverdue(): bool
    {
        return $this->status === 'queued' &&
               $this->scheduled_at &&
               $this->scheduled_at->isPast();
    }

    /**
     * Get remaining time until scheduled
     * Returns human-readable format
     */
    public function getRemainingTime(): string
    {
        if (!$this->scheduled_at) {
            return '-';
        }

        if ($this->status !== 'queued') {
            return '-';
        }

        $now = now();
        $scheduled = $this->scheduled_at;

        // Jika sudah lewat
        if ($scheduled->isPast()) {
            return 'Terlambat ' . $scheduled->diffForHumans($now, true);
        }

        // Jika dijadwalkan besok atau lebih
        if ($scheduled->isNextDay()) {
            return 'Besok ' . $scheduled->format('H:i');
        }

        if ($scheduled->gt($now->copy()->addDay())) {
            return $scheduled->format('d/m/Y H:i');
        }

        // Jika hari ini, tampilkan countdown
        $diff = $now->diff($scheduled);

        if ($diff->h > 0) {
            return sprintf('%d jam %d menit', $diff->h, $diff->i);
        } elseif ($diff->i > 0) {
            return sprintf('%d menit', $diff->i);
        } else {
            return 'Segera';
        }
    }

    /**
     * Get remaining time badge class
     */
    public function getRemainingTimeBadgeClass(): string
    {
        if (!$this->scheduled_at || $this->status !== 'queued') {
            return 'bg-gray-100 text-gray-800';
        }

        $now = now();
        $scheduled = $this->scheduled_at;

        // Terlambat
        if ($scheduled->isPast()) {
            return 'bg-red-100 text-red-800';
        }

        // Kurang dari 10 menit
        if ($scheduled->diffInMinutes($now) < 10) {
            return 'bg-orange-100 text-orange-800';
        }

        // Besok atau lebih
        if ($scheduled->isNextDay() || $scheduled->gt($now->copy()->addDay())) {
            return 'bg-blue-100 text-blue-800';
        }

        // Normal (hari ini, lebih dari 10 menit)
        return 'bg-green-100 text-green-800';
    }
}
