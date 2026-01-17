<?php

namespace App\Listeners;

use App\Events\PendaftarNotificationSent;
use App\Models\Setting;
use App\Services\WhatsAppGatewayService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendWhatsAppNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PendaftarNotificationSent $event): void
    {
        // Check if phone number exists
        if (empty($event->pendaftar->telepon)) {
            Log::warning('No phone number available for pendaftar: ' . $event->pendaftar->id);
            return;
        }

        $whatsappService = new WhatsAppGatewayService();

        // Create personalized message based on notification type
        $message = $this->generateMessage($event);

        // Send the message
        $result = $whatsappService->sendMessage($event->pendaftar->telepon, $message);

        Log::info('WhatsApp notification sent for event: ' . $event->notificationType, [
            'pendaftar_id' => $event->pendaftar->id,
            'nomor_pendaftaran' => $event->pendaftar->nomor_pendaftaran,
            'to' => $event->pendaftar->telepon,
            'message' => $message,
            'result' => $result
        ]);
    }

    /**
     * Generate message based on notification type
     */
    private function generateMessage($event)
    {
        $nama = $event->pendaftar->nama_lengkap;
        $nomorPendaftaran = $event->pendaftar->nomor_pendaftaran;
        $catatan = $event->catatan ?? '';
        $jalurNama = $event->pendaftar->jalurPendaftaran->nama ?? '-';

        // Variable replacements
        $replacements = [
            '{nama}' => $nama,
            '{nomor_pendaftaran}' => $nomorPendaftaran,
            '{email}' => $event->pendaftar->email ?? '-',
            '{telepon}' => $event->pendaftar->telepon ?? '-',
            '{catatan}' => $catatan,
            '{url_dashboard}' => url('/pendaftar/dashboard'),
            '{jalur}' => $jalurNama,
        ];

        // Get template from database based on notification type
        $template = match ($event->notificationType) {
            'pendaftaran_selesai' => Setting::get('wa_pendaftaran_body', $this->getDefaultPendaftaran()),
            'verifikasi_berkas_disetujui' => Setting::get('wa_verifikasi_disetujui_body', $this->getDefaultVerifikasiDisetujui()),
            'verifikasi_berkas_ditolak' => Setting::get('wa_verifikasi_ditolak_body', $this->getDefaultVerifikasiDitolak()),
            'verifikasi_berkas_perbaiki' => Setting::get('wa_verifikasi_perbaiki_body', $this->getDefaultVerifikasiPerbaiki()),
            'verifikasi_daftar_ulang_disetujui' => Setting::get('wa_daftar_ulang_disetujui_body', $this->getDefaultDaftarUlangDisetujui()),
            'verifikasi_daftar_ulang_ditolak' => Setting::get('wa_daftar_ulang_ditolak_body', $this->getDefaultDaftarUlangDitolak()),
            default => $this->getDefaultGeneral(),
        };

        // Replace variables in template
        return str_replace(array_keys($replacements), array_values($replacements), $template);
    }

    // Default templates
    private function getDefaultPendaftaran()
    {
        return "*MTsN 2 Kota Malang*\n\nKepada Bapak/Ibu {nama} ({nomor_pendaftaran}),\n\nPendaftaran Anda telah berhasil. Mohon bersabar sementara tim kami memverifikasi berkas pendaftaran Anda.\n\nSilakan cek dashboard Anda di: {url_dashboard}";
    }

    private function getDefaultVerifikasiDisetujui()
    {
        return "*MTsN 2 Kota Malang*\n\nKepada Bapak/Ibu {nama} ({nomor_pendaftaran}),\n\nBerkas pendaftaran Anda telah *diterima* dan lolos tahap seleksi.\n\nSilakan cek dashboard Anda di: {url_dashboard}";
    }

    private function getDefaultVerifikasiDitolak()
    {
        return "*MTsN 2 Kota Malang*\n\nKepada Bapak/Ibu {nama} ({nomor_pendaftaran}),\n\nMohon maaf, berkas pendaftaran Anda *ditolak*.\n\nAlasan: {catatan}\n\nSilakan cek dashboard Anda di: {url_dashboard}";
    }

    private function getDefaultVerifikasiPerbaiki()
    {
        return "*MTsN 2 Kota Malang*\n\nKepada Bapak/Ibu {nama} ({nomor_pendaftaran}),\n\nBerkas pendaftaran Anda perlu *diperbaiki*.\n\nCatatan: {catatan}\n\nSilakan lengkapi berkas Anda di: {url_dashboard}";
    }

    private function getDefaultDaftarUlangDisetujui()
    {
        return "*MTsN 2 Kota Malang*\n\nKepada Bapak/Ibu {nama} ({nomor_pendaftaran}),\n\nVerifikasi daftar ulang Anda telah *disetujui*, dan Anda resmi menjadi siswa/i MTsN 2 Kota Malang.\n\nSelamat bergabung!\n\nSilakan cek dashboard Anda di: {url_dashboard}";
    }

    private function getDefaultDaftarUlangDitolak()
    {
        return "*MTsN 2 Kota Malang*\n\nKepada Bapak/Ibu {nama} ({nomor_pendaftaran}),\n\nMohon maaf, verifikasi daftar ulang Anda *ditolak*.\n\nAlasan: {catatan}\n\nSilakan cek dashboard Anda di: {url_dashboard}";
    }

    private function getDefaultGeneral()
    {
        return "*MTsN 2 Kota Malang*\n\nKepada Bapak/Ibu {nama} ({nomor_pendaftaran}),\n\nAnda menerima notifikasi terbaru. Silakan cek dashboard Anda di: {url_dashboard}";
    }
}
