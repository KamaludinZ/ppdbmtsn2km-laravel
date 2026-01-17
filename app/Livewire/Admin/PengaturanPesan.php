<?php

namespace App\Livewire\Admin;

use App\Models\Setting;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class PengaturanPesan extends Component
{
    public $activeTab = 'email';

    // Email Templates
    public $email_pendaftaran_subject;
    public $email_pendaftaran_body;
    public $email_verifikasi_disetujui_subject;
    public $email_verifikasi_disetujui_body;
    public $email_verifikasi_ditolak_subject;
    public $email_verifikasi_ditolak_body;
    public $email_verifikasi_perbaiki_subject;
    public $email_verifikasi_perbaiki_body;
    public $email_kelulusan_lulus_subject;
    public $email_kelulusan_lulus_body;
    public $email_kelulusan_tidak_lulus_subject;
    public $email_kelulusan_tidak_lulus_body;
    public $email_kelulusan_cadangan_subject;
    public $email_kelulusan_cadangan_body;
    public $email_daftar_ulang_disetujui_subject;
    public $email_daftar_ulang_disetujui_body;
    public $email_daftar_ulang_ditolak_subject;
    public $email_daftar_ulang_ditolak_body;
    public $email_daftar_ulang_perbaiki_subject;
    public $email_daftar_ulang_perbaiki_body;
    public $email_verifikasi_transfer_disetujui_subject;
    public $email_verifikasi_transfer_disetujui_body;
    public $email_verifikasi_transfer_ditolak_subject;
    public $email_verifikasi_transfer_ditolak_body;
    public $email_verifikasi_transfer_perbaiki_subject;
    public $email_verifikasi_transfer_perbaiki_body;

    // WhatsApp Templates
    public $wa_pendaftaran_body;
    public $wa_verifikasi_disetujui_body;
    public $wa_verifikasi_ditolak_body;
    public $wa_verifikasi_perbaiki_body;
    public $wa_kelulusan_lulus_body;
    public $wa_kelulusan_tidak_lulus_body;
    public $wa_kelulusan_cadangan_body;
    public $wa_daftar_ulang_disetujui_body;
    public $wa_daftar_ulang_ditolak_body;
    public $wa_daftar_ulang_perbaiki_body;
    public $wa_verifikasi_transfer_disetujui_body;
    public $wa_verifikasi_transfer_ditolak_body;
    public $wa_verifikasi_transfer_perbaiki_body;

    public function mount()
    {
        $user = Auth::user();
        if (!$user || !in_array($user->role, ['admin', 'super_admin'])) {
            return redirect()->to('/login/admin');
        }

        $this->loadSettings();
    }

    public function loadSettings()
    {
        // Load Email Templates
        $this->email_pendaftaran_subject = Setting::get('email_pendaftaran_subject', 'Pendaftaran Berhasil - MTsN 2 Kota Malang');
        $this->email_pendaftaran_body = Setting::get('email_pendaftaran_body', $this->getDefaultEmailPendaftaran());

        $this->email_verifikasi_disetujui_subject = Setting::get('email_verifikasi_disetujui_subject', 'Berkas Pendaftaran Disetujui');
        $this->email_verifikasi_disetujui_body = Setting::get('email_verifikasi_disetujui_body', $this->getDefaultEmailVerifikasiDisetujui());

        $this->email_verifikasi_ditolak_subject = Setting::get('email_verifikasi_ditolak_subject', 'Berkas Pendaftaran Ditolak');
        $this->email_verifikasi_ditolak_body = Setting::get('email_verifikasi_ditolak_body', $this->getDefaultEmailVerifikasiDitolak());

        $this->email_verifikasi_perbaiki_subject = Setting::get('email_verifikasi_perbaiki_subject', 'Berkas Pendaftaran Perlu Perbaikan');
        $this->email_verifikasi_perbaiki_body = Setting::get('email_verifikasi_perbaiki_body', $this->getDefaultEmailVerifikasiPerbaiki());

        $this->email_kelulusan_lulus_subject = Setting::get('email_kelulusan_lulus_subject', 'Selamat! Anda Lulus Seleksi');
        $this->email_kelulusan_lulus_body = Setting::get('email_kelulusan_lulus_body', $this->getDefaultEmailKelulusanLulus());

        $this->email_kelulusan_tidak_lulus_subject = Setting::get('email_kelulusan_tidak_lulus_subject', 'Pengumuman Hasil Seleksi');
        $this->email_kelulusan_tidak_lulus_body = Setting::get('email_kelulusan_tidak_lulus_body', $this->getDefaultEmailKelulusanTidakLulus());

        $this->email_kelulusan_cadangan_subject = Setting::get('email_kelulusan_cadangan_subject', 'Pengumuman Kelulusan - Cadangan');
        $this->email_kelulusan_cadangan_body = Setting::get('email_kelulusan_cadangan_body', $this->getDefaultEmailKelulusanCadangan());

        $this->email_daftar_ulang_disetujui_subject = Setting::get('email_daftar_ulang_disetujui_subject', 'Daftar Ulang Disetujui');
        $this->email_daftar_ulang_disetujui_body = Setting::get('email_daftar_ulang_disetujui_body', $this->getDefaultEmailDaftarUlangDisetujui());

        $this->email_daftar_ulang_ditolak_subject = Setting::get('email_daftar_ulang_ditolak_subject', 'Daftar Ulang Ditolak');
        $this->email_daftar_ulang_ditolak_body = Setting::get('email_daftar_ulang_ditolak_body', $this->getDefaultEmailDaftarUlangDitolak());

        $this->email_daftar_ulang_perbaiki_subject = Setting::get('email_daftar_ulang_perbaiki_subject', 'Daftar Ulang Perlu Perbaikan');
        $this->email_daftar_ulang_perbaiki_body = Setting::get('email_daftar_ulang_perbaiki_body', $this->getDefaultEmailDaftarUlangPerbaiki());

        $this->email_verifikasi_transfer_disetujui_subject = Setting::get('email_verifikasi_transfer_disetujui_subject', 'Bukti Transfer Disetujui');
        $this->email_verifikasi_transfer_disetujui_body = Setting::get('email_verifikasi_transfer_disetujui_body', $this->getDefaultEmailVerifikasiTransferDisetujui());

        $this->email_verifikasi_transfer_ditolak_subject = Setting::get('email_verifikasi_transfer_ditolak_subject', 'Bukti Transfer Ditolak');
        $this->email_verifikasi_transfer_ditolak_body = Setting::get('email_verifikasi_transfer_ditolak_body', $this->getDefaultEmailVerifikasiTransferDitolak());

        $this->email_verifikasi_transfer_perbaiki_subject = Setting::get('email_verifikasi_transfer_perbaiki_subject', 'Bukti Transfer Perlu Perbaikan');
        $this->email_verifikasi_transfer_perbaiki_body = Setting::get('email_verifikasi_transfer_perbaiki_body', $this->getDefaultEmailVerifikasiTransferPerbaiki());

        // Load WhatsApp Templates
        $this->wa_pendaftaran_body = Setting::get('wa_pendaftaran_body', $this->getDefaultWAPendaftaran());
        $this->wa_verifikasi_disetujui_body = Setting::get('wa_verifikasi_disetujui_body', $this->getDefaultWAVerifikasiDisetujui());
        $this->wa_verifikasi_ditolak_body = Setting::get('wa_verifikasi_ditolak_body', $this->getDefaultWAVerifikasiDitolak());
        $this->wa_verifikasi_perbaiki_body = Setting::get('wa_verifikasi_perbaiki_body', $this->getDefaultWAVerifikasiPerbaiki());
        $this->wa_kelulusan_lulus_body = Setting::get('wa_kelulusan_lulus_body', $this->getDefaultWAKelulusanLulus());
        $this->wa_kelulusan_tidak_lulus_body = Setting::get('wa_kelulusan_tidak_lulus_body', $this->getDefaultWAKelulusanTidakLulus());
        $this->wa_kelulusan_cadangan_body = Setting::get('wa_kelulusan_cadangan_body', $this->getDefaultWAKelulusanCadangan());
        $this->wa_daftar_ulang_disetujui_body = Setting::get('wa_daftar_ulang_disetujui_body', $this->getDefaultWADaftarUlangDisetujui());
        $this->wa_daftar_ulang_ditolak_body = Setting::get('wa_daftar_ulang_ditolak_body', $this->getDefaultWADaftarUlangDitolak());
        $this->wa_daftar_ulang_perbaiki_body = Setting::get('wa_daftar_ulang_perbaiki_body', $this->getDefaultWADaftarUlangPerbaiki());
        $this->wa_verifikasi_transfer_disetujui_body = Setting::get('wa_verifikasi_transfer_disetujui_body', $this->getDefaultWAVerifikasiTransferDisetujui());
        $this->wa_verifikasi_transfer_ditolak_body = Setting::get('wa_verifikasi_transfer_ditolak_body', $this->getDefaultWAVerifikasiTransferDitolak());
        $this->wa_verifikasi_transfer_perbaiki_body = Setting::get('wa_verifikasi_transfer_perbaiki_body', $this->getDefaultWAVerifikasiTransferPerbaiki());
    }

    public function saveEmailSettings()
    {
        $this->validate([
            'email_pendaftaran_subject' => 'required|string|max:255',
            'email_pendaftaran_body' => 'required|string',
            'email_verifikasi_disetujui_subject' => 'required|string|max:255',
            'email_verifikasi_disetujui_body' => 'required|string',
            'email_verifikasi_ditolak_subject' => 'required|string|max:255',
            'email_verifikasi_ditolak_body' => 'required|string',
            'email_verifikasi_perbaiki_subject' => 'required|string|max:255',
            'email_verifikasi_perbaiki_body' => 'required|string',
            'email_kelulusan_lulus_subject' => 'required|string|max:255',
            'email_kelulusan_lulus_body' => 'required|string',
            'email_kelulusan_tidak_lulus_subject' => 'required|string|max:255',
            'email_kelulusan_tidak_lulus_body' => 'required|string',
            'email_kelulusan_cadangan_subject' => 'required|string|max:255',
            'email_kelulusan_cadangan_body' => 'required|string',
            'email_daftar_ulang_disetujui_subject' => 'required|string|max:255',
            'email_daftar_ulang_disetujui_body' => 'required|string',
            'email_daftar_ulang_ditolak_subject' => 'required|string|max:255',
            'email_daftar_ulang_ditolak_body' => 'required|string',
            'email_daftar_ulang_perbaiki_subject' => 'required|string|max:255',
            'email_daftar_ulang_perbaiki_body' => 'required|string',
            'email_verifikasi_transfer_disetujui_subject' => 'required|string|max:255',
            'email_verifikasi_transfer_disetujui_body' => 'required|string',
            'email_verifikasi_transfer_ditolak_subject' => 'required|string|max:255',
            'email_verifikasi_transfer_ditolak_body' => 'required|string',
            'email_verifikasi_transfer_perbaiki_subject' => 'required|string|max:255',
            'email_verifikasi_transfer_perbaiki_body' => 'required|string',
        ]);

        Setting::set('email_pendaftaran_subject', $this->email_pendaftaran_subject, 'text', 'Subject Email Pendaftaran Berhasil');
        Setting::set('email_pendaftaran_body', $this->email_pendaftaran_body, 'textarea', 'Body Email Pendaftaran Berhasil');

        Setting::set('email_verifikasi_disetujui_subject', $this->email_verifikasi_disetujui_subject, 'text', 'Subject Email Verifikasi Disetujui');
        Setting::set('email_verifikasi_disetujui_body', $this->email_verifikasi_disetujui_body, 'textarea', 'Body Email Verifikasi Disetujui');

        Setting::set('email_verifikasi_ditolak_subject', $this->email_verifikasi_ditolak_subject, 'text', 'Subject Email Verifikasi Ditolak');
        Setting::set('email_verifikasi_ditolak_body', $this->email_verifikasi_ditolak_body, 'textarea', 'Body Email Verifikasi Ditolak');

        Setting::set('email_verifikasi_perbaiki_subject', $this->email_verifikasi_perbaiki_subject, 'text', 'Subject Email Verifikasi Perbaiki');
        Setting::set('email_verifikasi_perbaiki_body', $this->email_verifikasi_perbaiki_body, 'textarea', 'Body Email Verifikasi Perbaiki');

        Setting::set('email_kelulusan_lulus_subject', $this->email_kelulusan_lulus_subject, 'text', 'Subject Email Kelulusan Lulus');
        Setting::set('email_kelulusan_lulus_body', $this->email_kelulusan_lulus_body, 'textarea', 'Body Email Kelulusan Lulus');

        Setting::set('email_kelulusan_tidak_lulus_subject', $this->email_kelulusan_tidak_lulus_subject, 'text', 'Subject Email Kelulusan Tidak Lulus');
        Setting::set('email_kelulusan_tidak_lulus_body', $this->email_kelulusan_tidak_lulus_body, 'textarea', 'Body Email Kelulusan Tidak Lulus');

        Setting::set('email_kelulusan_cadangan_subject', $this->email_kelulusan_cadangan_subject, 'text', 'Subject Email Kelulusan Cadangan');
        Setting::set('email_kelulusan_cadangan_body', $this->email_kelulusan_cadangan_body, 'textarea', 'Body Email Kelulusan Cadangan');

        Setting::set('email_daftar_ulang_disetujui_subject', $this->email_daftar_ulang_disetujui_subject, 'text', 'Subject Email Daftar Ulang Disetujui');
        Setting::set('email_daftar_ulang_disetujui_body', $this->email_daftar_ulang_disetujui_body, 'textarea', 'Body Email Daftar Ulang Disetujui');

        Setting::set('email_daftar_ulang_ditolak_subject', $this->email_daftar_ulang_ditolak_subject, 'text', 'Subject Email Daftar Ulang Ditolak');
        Setting::set('email_daftar_ulang_ditolak_body', $this->email_daftar_ulang_ditolak_body, 'textarea', 'Body Email Daftar Ulang Ditolak');

        Setting::set('email_daftar_ulang_perbaiki_subject', $this->email_daftar_ulang_perbaiki_subject, 'text', 'Subject Email Daftar Ulang Perbaiki');
        Setting::set('email_daftar_ulang_perbaiki_body', $this->email_daftar_ulang_perbaiki_body, 'textarea', 'Body Email Daftar Ulang Perbaiki');

        Setting::set('email_verifikasi_transfer_disetujui_subject', $this->email_verifikasi_transfer_disetujui_subject, 'text', 'Subject Email Verifikasi Transfer Disetujui');
        Setting::set('email_verifikasi_transfer_disetujui_body', $this->email_verifikasi_transfer_disetujui_body, 'textarea', 'Body Email Verifikasi Transfer Disetujui');

        Setting::set('email_verifikasi_transfer_ditolak_subject', $this->email_verifikasi_transfer_ditolak_subject, 'text', 'Subject Email Verifikasi Transfer Ditolak');
        Setting::set('email_verifikasi_transfer_ditolak_body', $this->email_verifikasi_transfer_ditolak_body, 'textarea', 'Body Email Verifikasi Transfer Ditolak');

        Setting::set('email_verifikasi_transfer_perbaiki_subject', $this->email_verifikasi_transfer_perbaiki_subject, 'text', 'Subject Email Verifikasi Transfer Perbaiki');
        Setting::set('email_verifikasi_transfer_perbaiki_body', $this->email_verifikasi_transfer_perbaiki_body, 'textarea', 'Body Email Verifikasi Transfer Perbaiki');

        session()->flash('message', 'Template email berhasil disimpan!');
    }

    public function saveWhatsAppSettings()
    {
        $this->validate([
            'wa_pendaftaran_body' => 'required|string',
            'wa_verifikasi_disetujui_body' => 'required|string',
            'wa_verifikasi_ditolak_body' => 'required|string',
            'wa_verifikasi_perbaiki_body' => 'required|string',
            'wa_kelulusan_lulus_body' => 'required|string',
            'wa_kelulusan_tidak_lulus_body' => 'required|string',
            'wa_kelulusan_cadangan_body' => 'required|string',
            'wa_daftar_ulang_disetujui_body' => 'required|string',
            'wa_daftar_ulang_ditolak_body' => 'required|string',
            'wa_daftar_ulang_perbaiki_body' => 'required|string',
            'wa_verifikasi_transfer_disetujui_body' => 'required|string',
            'wa_verifikasi_transfer_ditolak_body' => 'required|string',
            'wa_verifikasi_transfer_perbaiki_body' => 'required|string',
        ]);

        Setting::set('wa_pendaftaran_body', $this->wa_pendaftaran_body, 'textarea', 'Template WA Pendaftaran Berhasil');
        Setting::set('wa_verifikasi_disetujui_body', $this->wa_verifikasi_disetujui_body, 'textarea', 'Template WA Verifikasi Disetujui');
        Setting::set('wa_verifikasi_ditolak_body', $this->wa_verifikasi_ditolak_body, 'textarea', 'Template WA Verifikasi Ditolak');
        Setting::set('wa_verifikasi_perbaiki_body', $this->wa_verifikasi_perbaiki_body, 'textarea', 'Template WA Verifikasi Perbaiki');
        Setting::set('wa_kelulusan_lulus_body', $this->wa_kelulusan_lulus_body, 'textarea', 'Template WA Kelulusan Lulus');
        Setting::set('wa_kelulusan_tidak_lulus_body', $this->wa_kelulusan_tidak_lulus_body, 'textarea', 'Template WA Kelulusan Tidak Lulus');
        Setting::set('wa_kelulusan_cadangan_body', $this->wa_kelulusan_cadangan_body, 'textarea', 'Template WA Kelulusan Cadangan');
        Setting::set('wa_daftar_ulang_disetujui_body', $this->wa_daftar_ulang_disetujui_body, 'textarea', 'Template WA Daftar Ulang Disetujui');
        Setting::set('wa_daftar_ulang_ditolak_body', $this->wa_daftar_ulang_ditolak_body, 'textarea', 'Template WA Daftar Ulang Ditolak');
        Setting::set('wa_daftar_ulang_perbaiki_body', $this->wa_daftar_ulang_perbaiki_body, 'textarea', 'Template WA Daftar Ulang Perbaiki');
        Setting::set('wa_verifikasi_transfer_disetujui_body', $this->wa_verifikasi_transfer_disetujui_body, 'textarea', 'Template WA Verifikasi Transfer Disetujui');
        Setting::set('wa_verifikasi_transfer_ditolak_body', $this->wa_verifikasi_transfer_ditolak_body, 'textarea', 'Template WA Verifikasi Transfer Ditolak');
        Setting::set('wa_verifikasi_transfer_perbaiki_body', $this->wa_verifikasi_transfer_perbaiki_body, 'textarea', 'Template WA Verifikasi Transfer Perbaiki');

        session()->flash('message', 'Template WhatsApp berhasil disimpan!');
    }

    public function resetToDefault()
    {
        $this->loadSettings();
        session()->flash('message', 'Template telah direset ke default!');
    }

    // Default Templates
    private function getDefaultEmailPendaftaran()
    {
        return "Kepada Yth. {nama},\n\nSelamat! Akun pendaftaran Anda telah berhasil dibuat.\n\n=== DATA PENDAFTARAN ANDA ===\nNomor Pendaftaran: {nomor_pendaftaran}\nNama Lengkap: {nama}\nNISN: {nisn}\nTempat, Tanggal Lahir: {tempat_lahir}, {tanggal_lahir}\nJenis Kelamin: {jenis_kelamin}\nAlamat: {alamat}\nTelepon: {telepon}\nEmail: {email}\nAsal Sekolah: {asal_sekolah}\nJalur Pendaftaran: {jalur}\n\n=== AKUN LOGIN ===\nEmail: {email}\nPassword: {password}\n\nSilakan login ke dashboard Anda di: {url_dashboard}\n\nTim kami akan segera meninjau berkas pendaftaran Anda. Pantau terus dashboard untuk update status terbaru.\n\nSalam,\nPanitia PPDB MTsN 2 Kota Malang";
    }

    private function getDefaultEmailVerifikasiDisetujui()
    {
        return "Kepada {nama},\n\nSelamat! Berkas pendaftaran Anda dengan nomor {nomor_pendaftaran} telah diverifikasi dan disetujui.\n\nSilakan cek dashboard Anda untuk informasi lebih lanjut.\n\nSalam,\nPanitia PPDB MTsN 2 Kota Malang";
    }

    private function getDefaultEmailVerifikasiDitolak()
    {
        return "Kepada {nama},\n\nMohon maaf, berkas pendaftaran Anda dengan nomor {nomor_pendaftaran} tidak dapat disetujui.\n\nCatatan: {catatan}\n\nSilakan cek dashboard Anda untuk informasi lebih lanjut.\n\nSalam,\nPanitia PPDB MTsN 2 Kota Malang";
    }

    private function getDefaultEmailVerifikasiPerbaiki()
    {
        return "Kepada {nama},\n\nBerkas pendaftaran Anda dengan nomor {nomor_pendaftaran} perlu diperbaiki.\n\nCatatan: {catatan}\n\nSilakan lengkapi/perbaiki berkas Anda melalui dashboard.\n\nSalam,\nPanitia PPDB MTsN 2 Kota Malang";
    }

    private function getDefaultWAPendaftaran()
    {
        return "*MTsN 2 Kota Malang*\n\nKepada Yth. Bapak/Ibu *{nama}*\n\nSelamat! Akun pendaftaran Anda telah berhasil dibuat.\n\n*=== DATA PENDAFTARAN ===*\nNomor Pendaftaran: *{nomor_pendaftaran}*\nNama: {nama}\nNISN: {nisn}\nTempat, Tgl Lahir: {tempat_lahir}, {tanggal_lahir}\nJenis Kelamin: {jenis_kelamin}\nAlamat: {alamat}\nTelepon: {telepon}\nEmail: {email}\nAsal Sekolah: {asal_sekolah}\nJalur: {jalur}\n\n*=== AKUN LOGIN ===*\nEmail: {email}\nPassword: {password}\n\nSilakan login ke: {url_dashboard}\n\nTim kami akan segera meninjau berkas pendaftaran Anda. Pantau terus dashboard untuk update status terbaru.\n\nTerima kasih.";
    }

    private function getDefaultWAVerifikasiDisetujui()
    {
        return "*MTsN 2 Kota Malang*\n\nKepada Bapak/Ibu {nama} ({nomor_pendaftaran}),\n\nBerkas pendaftaran Anda telah *diterima* dan lolos tahap seleksi.\n\nSilakan cek dashboard Anda di: {url_dashboard}";
    }

    private function getDefaultWAVerifikasiDitolak()
    {
        return "*MTsN 2 Kota Malang*\n\nKepada Bapak/Ibu {nama} ({nomor_pendaftaran}),\n\nMohon maaf, berkas pendaftaran Anda *ditolak*.\n\nAlasan: {catatan}\n\nSilakan cek dashboard Anda di: {url_dashboard}";
    }

    private function getDefaultWAVerifikasiPerbaiki()
    {
        return "*MTsN 2 Kota Malang*\n\nKepada Bapak/Ibu {nama} ({nomor_pendaftaran}),\n\nBerkas pendaftaran Anda perlu *diperbaiki*.\n\nCatatan: {catatan}\n\nSilakan lengkapi berkas Anda di: {url_dashboard}";
    }

    private function getDefaultWADaftarUlangDisetujui()
    {
        return "*MTsN 2 Kota Malang*\n\nKepada Bapak/Ibu {nama} ({nomor_pendaftaran}),\n\nVerifikasi daftar ulang Anda telah *disetujui*, dan Anda resmi menjadi siswa/i MTsN 2 Kota Malang.\n\nSelamat bergabung!\n\nSilakan cek dashboard Anda di: {url_dashboard}";
    }

    private function getDefaultEmailKelulusanLulus()
    {
        return "Kepada {nama},\n\nSelamat! Kami dengan bangga mengumumkan bahwa Anda telah LULUS seleksi PPDB MTsN 2 Kota Malang dengan nomor pendaftaran {nomor_pendaftaran}.\n\nSilakan login ke dashboard Anda untuk melihat informasi lebih lanjut dan melanjutkan proses daftar ulang.\n\nSalam,\nPanitia PPDB MTsN 2 Kota Malang";
    }

    private function getDefaultEmailKelulusanTidakLulus()
    {
        return "Kepada {nama},\n\nTerima kasih atas partisipasi Anda dalam seleksi PPDB MTsN 2 Kota Malang dengan nomor pendaftaran {nomor_pendaftaran}.\n\nDengan berat hati kami sampaikan bahwa Anda belum dapat kami terima pada tahap seleksi ini. Kami mendoakan kesuksesan Anda di kesempatan lainnya.\n\nSalam,\nPanitia PPDB MTsN 2 Kota Malang";
    }

    private function getDefaultEmailDaftarUlangDisetujui()
    {
        return "Kepada {nama},\n\nSelamat! Verifikasi daftar ulang Anda dengan nomor pendaftaran {nomor_pendaftaran} telah disetujui.\n\nAnda resmi terdaftar sebagai calon siswa/i MTsN 2 Kota Malang. Silakan cek dashboard Anda untuk informasi lebih lanjut.\n\nSalam,\nPanitia PPDB MTsN 2 Kota Malang";
    }

    private function getDefaultEmailDaftarUlangDitolak()
    {
        return "Kepada {nama},\n\nMohon maaf, verifikasi daftar ulang Anda dengan nomor pendaftaran {nomor_pendaftaran} tidak dapat disetujui.\n\nCatatan: {catatan}\n\nSilakan hubungi panitia untuk informasi lebih lanjut.\n\nSalam,\nPanitia PPDB MTsN 2 Kota Malang";
    }

    private function getDefaultEmailKelulusanCadangan()
    {
        return "Kepada {nama},\n\nTerima kasih atas partisipasi Anda dalam seleksi PPDB MTsN 2 Kota Malang dengan nomor pendaftaran {nomor_pendaftaran}.\n\nAnda masuk dalam daftar CADANGAN. Kami akan menghubungi Anda kembali jika ada kesempatan.\n\nTetap semangat dan semoga sukses!\n\nSalam,\nPanitia PPDB MTsN 2 Kota Malang";
    }

    private function getDefaultEmailDaftarUlangPerbaiki()
    {
        return "Kepada {nama},\n\nDokumen daftar ulang Anda dengan nomor pendaftaran {nomor_pendaftaran} perlu diperbaiki.\n\nCatatan: {catatan}\n\nSilakan perbaiki dan upload ulang dokumen Anda melalui dashboard.\n\nSalam,\nPanitia PPDB MTsN 2 Kota Malang";
    }

    private function getDefaultEmailVerifikasiTransferDisetujui()
    {
        return "Kepada {nama},\n\nBukti transfer biaya tes psikologi Anda dengan nomor pendaftaran {nomor_pendaftaran} telah disetujui.\n\nAnda dapat melanjutkan ke tahap selanjutnya. Silakan cek dashboard Anda untuk informasi lebih lanjut.\n\nSalam,\nPanitia PPDB MTsN 2 Kota Malang";
    }

    private function getDefaultEmailVerifikasiTransferDitolak()
    {
        return "Kepada {nama},\n\nMohon maaf, bukti transfer biaya tes psikologi Anda dengan nomor pendaftaran {nomor_pendaftaran} tidak dapat disetujui.\n\nCatatan: {catatan}\n\nSilakan hubungi panitia atau upload ulang bukti transfer yang benar.\n\nSalam,\nPanitia PPDB MTsN 2 Kota Malang";
    }

    private function getDefaultEmailVerifikasiTransferPerbaiki()
    {
        return "Kepada {nama},\n\nBukti transfer biaya tes psikologi Anda dengan nomor pendaftaran {nomor_pendaftaran} perlu diperbaiki.\n\nCatatan: {catatan}\n\nSilakan upload ulang bukti transfer yang benar melalui dashboard Anda.\n\nSalam,\nPanitia PPDB MTsN 2 Kota Malang";
    }

    private function getDefaultWAKelulusanLulus()
    {
        return "*MTsN 2 Kota Malang*\n\nKepada Bapak/Ibu {nama} ({nomor_pendaftaran}),\n\n🎉 *SELAMAT!* 🎉\n\nAnda telah *LULUS* seleksi PPDB MTsN 2 Kota Malang!\n\nSilakan segera login ke dashboard untuk melanjutkan proses daftar ulang: {url_dashboard}";
    }

    private function getDefaultWAKelulusanTidakLulus()
    {
        return "*MTsN 2 Kota Malang*\n\nKepada Bapak/Ibu {nama} ({nomor_pendaftaran}),\n\nTerima kasih atas partisipasi Anda dalam seleksi PPDB MTsN 2 Kota Malang.\n\nDengan berat hati kami sampaikan bahwa Anda *belum dapat kami terima* pada tahap seleksi ini.\n\nKami mendoakan kesuksesan Anda di kesempatan lainnya.";
    }

    private function getDefaultWADaftarUlangDitolak()
    {
        return "*MTsN 2 Kota Malang*\n\nKepada Bapak/Ibu {nama} ({nomor_pendaftaran}),\n\nMohon maaf, verifikasi daftar ulang Anda *ditolak*.\n\nAlasan: {catatan}\n\nSilakan cek dashboard Anda di: {url_dashboard}";
    }

    private function getDefaultWAKelulusanCadangan()
    {
        return "*MTsN 2 Kota Malang*\n\nKepada Bapak/Ibu {nama} ({nomor_pendaftaran}),\n\nAnda masuk dalam daftar *CADANGAN* seleksi PPDB MTsN 2 Kota Malang.\n\nKami akan menghubungi Anda kembali jika ada kesempatan. Tetap semangat! 💪";
    }

    private function getDefaultWADaftarUlangPerbaiki()
    {
        return "*MTsN 2 Kota Malang*\n\nKepada Bapak/Ibu {nama} ({nomor_pendaftaran}),\n\nDokumen daftar ulang Anda *perlu diperbaiki*.\n\nCatatan: {catatan}\n\nSilakan perbaiki dan upload ulang di: {url_dashboard}";
    }

    private function getDefaultWAVerifikasiTransferDisetujui()
    {
        return "*MTsN 2 Kota Malang*\n\nKepada Bapak/Ibu {nama} ({nomor_pendaftaran}),\n\nBukti transfer biaya tes psikologi Anda telah *disetujui* ✅\n\nSilakan cek dashboard untuk informasi selanjutnya: {url_dashboard}";
    }

    private function getDefaultWAVerifikasiTransferDitolak()
    {
        return "*MTsN 2 Kota Malang*\n\nKepada Bapak/Ibu {nama} ({nomor_pendaftaran}),\n\nMohon maaf, bukti transfer biaya tes psikologi Anda *ditolak* ❌\n\nCatatan: {catatan}\n\nSilakan hubungi panitia atau upload ulang bukti yang benar.";
    }

    private function getDefaultWAVerifikasiTransferPerbaiki()
    {
        return "*MTsN 2 Kota Malang*\n\nKepada Bapak/Ibu {nama} ({nomor_pendaftaran}),\n\nBukti transfer biaya tes psikologi Anda *perlu diperbaiki*.\n\nCatatan: {catatan}\n\nSilakan upload ulang bukti yang benar di: {url_dashboard}";
    }

    public function render()
    {
        return view('livewire.admin.pengaturan-pesan')->layout('components.layouts.dashboard', [
            'role' => Auth::user()->role,
            'pageTitle' => 'Pengaturan Pesan Notifikasi',
            'pageDescription' => 'Kelola template pesan email dan WhatsApp'
        ]);
    }
}
