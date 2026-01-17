<?php

namespace App\Notifications;

use App\Services\WhatsAppGatewayService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class VerifikasiBerkasNotification extends Notification
{
    use Queueable;

    protected $pendaftar;
    protected $status;
    protected $catatan;
    protected $jenis_verifikasi; // 'berkas' atau 'daftar_ulang'

    /**
     * Create a new notification instance.
     */
    public function __construct($pendaftar, $status, $catatan = null, $jenis_verifikasi = 'berkas')
    {
        $this->pendaftar = $pendaftar;
        $this->status = $status;
        $this->catatan = $catatan;
        $this->jenis_verifikasi = $jenis_verifikasi;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $subject = $this->jenis_verifikasi === 'berkas' ?
                  'Status Verifikasi Berkas Pendaftaran' :
                  'Status Verifikasi Daftar Ulang';

        $pesan = $this->getStatusMessage($notifiable);

        return (new MailMessage)
            ->subject($subject)
            ->line($pesan)
            ->when($this->catatan, function ($message) {
                return $message->line('Catatan: ' . $this->catatan);
            })
            ->line('Silakan masuk ke akun Anda untuk melihat status terbaru.')
            ->action('Lihat Dashboard', url('/pendaftar/dashboard'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'judul' => $this->jenis_verifikasi === 'berkas' ?
                      'Status Verifikasi Berkas' :
                      'Status Verifikasi Daftar Ulang',
            'pesan' => $this->getStatusMessage($notifiable),
            'catatan' => $this->catatan,
            'status' => $this->status,
            'jenis_verifikasi' => $this->jenis_verifikasi,
            'tanggal' => now(),
        ];
    }

    /**
     * Get status message based on verification status
     */
    private function getStatusMessage($notifiable)
    {
        $base_message = "Kepada " . $notifiable->nama_lengkap . " ({$this->pendaftar->nomor_pendaftaran}), ";

        if ($this->status === 'diterima') {
            if ($this->jenis_verifikasi === 'berkas') {
                $base_message .= "berkas pendaftaran Anda telah **diterima** dan lolos tahap seleksi.";
            } else {
                $base_message .= "verifikasi daftar ulang Anda telah **disetujui**.";
            }
        } else {
            if ($this->jenis_verifikasi === 'berkas') {
                $base_message .= "maaf, berkas pendaftaran Anda **ditolak**.";
            } else {
                $base_message .= "maaf, verifikasi daftar ulang Anda **ditolak**.";
            }

            if ($this->catatan) {
                $base_message .= " Alasan: " . $this->catatan;
            }
        }

        return $base_message;
    }
}
