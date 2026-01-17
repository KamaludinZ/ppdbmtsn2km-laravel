<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StatusChangeNotification extends Notification
{
    use Queueable;

    public $type;
    public $status;
    public $catatan;
    public $data;

    /**
     * Create a new notification instance.
     *
     * @param string $type Type of notification (tes_psikologi, formulir, upload_berkas, daftar_ulang, etc.)
     * @param string $status New status (disetujui, ditolak, perbaiki, etc.)
     * @param string|null $catatan Notes from admin/panitia
     * @param array $data Additional data
     */
    public function __construct(string $type, string $status, ?string $catatan = null, array $data = [])
    {
        $this->type = $type;
        $this->status = $status;
        $this->catatan = $catatan;
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => $this->type,
            'status' => $this->status,
            'catatan' => $this->catatan,
            'message' => $this->getMessage(),
            'data' => $this->data,
            'created_at' => now()->toDateTimeString(),
        ];
    }

    /**
     * Get notification message based on type and status
     */
    protected function getMessage(): string
    {
        $statusLabels = [
            'disetujui' => 'disetujui',
            'ditolak' => 'ditolak',
            'perbaiki' => 'perlu diperbaiki',
            'menunggu' => 'sedang diverifikasi',
        ];

        $typeLabels = [
            'tes_psikologi' => 'Pembayaran Tes Psikologi',
            'formulir' => 'Formulir Pendaftaran',
            'upload_berkas' => 'Dokumen Upload',
            'daftar_ulang_madrasah' => 'Daftar Ulang Madrasah',
            'daftar_ulang_seragam' => 'Daftar Ulang Seragam',
            'daftar_ulang_ukuran' => 'Ukuran Seragam & Atribut',
        ];

        $typeLabel = $typeLabels[$this->type] ?? $this->type;
        $statusLabel = $statusLabels[$this->status] ?? $this->status;

        return "{$typeLabel} Anda telah {$statusLabel}";
    }
}
