<?php

namespace App\Notifications;

use App\Models\Setting;
use App\Services\WhatsAppGatewayService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class PendaftaranSelesaiNotification extends Notification
{
    use Queueable;

    protected $pendaftar;

    /**
     * Create a new notification instance.
     */
    public function __construct($pendaftar)
    {
        $this->pendaftar = $pendaftar;
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
        // Get template from database
        $subject = Setting::get('email_pendaftaran_subject', 'Pendaftaran Berhasil - MTsN 2 Kota Malang');
        $bodyTemplate = Setting::get('email_pendaftaran_body', $this->getDefaultEmailBody());

        // Replace variables
        $body = $this->replaceVariables($bodyTemplate, $notifiable);

        $lines = explode("\n", $body);

        $mail = (new MailMessage)->subject($subject);

        foreach ($lines as $line) {
            if (trim($line)) {
                $mail->line($line);
            }
        }

        $mail->action('Lihat Dashboard', url('/pendaftar/dashboard'));

        return $mail;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'judul' => 'Pendaftaran Berhasil',
            'pesan' => $this->getStatusMessage($notifiable),
            'tanggal' => now(),
        ];
    }

    /**
     * Replace variables in template
     */
    private function replaceVariables($template, $notifiable)
    {
        $jalurNama = $this->pendaftar->jalurPendaftaran->nama ?? '-';

        $replacements = [
            '{nama}' => $notifiable->nama_lengkap,
            '{nomor_pendaftaran}' => $this->pendaftar->nomor_pendaftaran,
            '{email}' => $notifiable->email,
            '{telepon}' => $notifiable->telepon ?? '-',
            '{jalur}' => $jalurNama,
            '{url_dashboard}' => url('/pendaftar/dashboard'),
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $template);
    }

    /**
     * Get status message when registration is completed
     */
    private function getStatusMessage($notifiable)
    {
        return "Kepada " . $notifiable->nama_lengkap . " ({$this->pendaftar->nomor_pendaftaran}), "
             . "pendaftaran Anda telah berhasil. Mohon bersabar sementara tim kami memverifikasi berkas pendaftaran Anda.";
    }

    /**
     * Default email body template
     */
    private function getDefaultEmailBody()
    {
        return "Kepada {nama},\n\nTerima kasih telah mendaftar di MTsN 2 Kota Malang dengan nomor pendaftaran {nomor_pendaftaran}.\n\nTim kami akan segera meninjau berkas pendaftaran Anda. Silakan masuk ke akun Anda untuk melihat status terbaru.\n\nSalam,\nPanitia PPDB MTsN 2 Kota Malang";
    }
}
