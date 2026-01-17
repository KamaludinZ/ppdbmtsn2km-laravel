<?php

namespace App\Livewire\Admin;

use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class Settings extends Component
{
    use WithFileUploads;

    // ... (rest of properties)
    // Email Settings
    public $email_from_address;
    public $email_from_name;
    public $smtp_host;
    public $smtp_port;
    public $smtp_username;
    public $smtp_password;
    public $smtp_encryption;
    public $email_enabled = false;

    // WhatsApp Gateway Settings
    public $whatsapp_api_url;
    public $whatsapp_api_key;
    public $whatsapp_sender_number;
    public $whatsapp_enabled = false;

    // General Settings
    public $registration_phase;
    public $registration_phase_label;
    public $registration_phase_description;
    public $registration_start_date;
    public $registration_end_date;

    // App Identity Settings
    public $app_name;
    public $app_acronym;
    public $app_full_name;
    public $tahun_pelajaran;
    public $app_logo;
    public $app_favicon;
    public $app_logo_url;
    public $app_favicon_url;
    public $app_footer_copyright;
    public $app_footer_description;

    // Print Identity Settings (Kop PDF)
    public $print_logo_left;
    public $print_logo_right;
    public $print_logo_left_url;
    public $print_logo_right_url;
    public $print_institution_name;
    public $print_institution_address;
    public $print_institution_phone;
    public $print_institution_email;
    public $print_institution_website;

    // Test Variables
    public $test_email_recipient;
    public $test_whatsapp_number;

    public function mount()
    {
        // Check if user is admin
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            return redirect()->to('/login/admin');
        }

        $this->loadSettings();
        $this->test_email_recipient = $user->email;
    }

    public function loadSettings()
    {
        // ... (previous content of loadSettings)
        $this->email_from_address = Setting::get('email_from_address', '');
        $this->email_from_name = Setting::get('email_from_name', 'MTsN 2 Kota Malang');
        $this->smtp_host = Setting::get('smtp_host', '');
        $this->smtp_port = Setting::get('smtp_port', '587');
        $this->smtp_username = Setting::get('smtp_username', '');
        $this->smtp_password = Setting::get('smtp_password', '');
        $this->smtp_encryption = Setting::get('smtp_encryption', 'tls');
        $this->email_enabled = Setting::get('email_enabled', false);

        // Load WhatsApp Settings
        $this->whatsapp_api_url = Setting::get('whatsapp_api_url', '');
        $this->whatsapp_api_key = Setting::get('whatsapp_api_key', '');
        $this->whatsapp_sender_number = Setting::get('whatsapp_sender_number', '');
        $this->whatsapp_enabled = Setting::get('whatsapp_enabled', false);

        // Load General Settings
        $this->registration_phase = Setting::get('registration_phase', 'belum_dibuka');
        $this->registration_phase_label = Setting::get('registration_phase_label', 'Pendaftaran Belum Dibuka');
        $this->registration_phase_description = Setting::get('registration_phase_description', 'Pendaftaran akan segera dibuka.');
        $this->registration_start_date = Setting::get('registration_start_date');
        $this->registration_end_date = Setting::get('registration_end_date');
        
        // Load App Identity
        $this->app_name = Setting::get('app_name', 'MTsN 2 Kota Malang');
        $this->app_acronym = Setting::get('app_acronym', 'PMBM');
        $this->app_full_name = Setting::get('app_full_name', 'Penerimaan Murid Baru Madrasah');
        $this->tahun_pelajaran = Setting::get('tahun_pelajaran', '2025/2026');
        $this->app_logo_url = Setting::get('app_logo', '');
        $this->app_favicon_url = Setting::get('app_favicon', '');
        $this->app_footer_copyright = Setting::get('app_footer_copyright', '© ' . date('Y') . ' MTsN 2 Kota Malang. All rights reserved.');
        $this->app_footer_description = Setting::get('app_footer_description', 'Madrasah Tsanawiyah Negeri 2 Kota Malang adalah lembaga pendidikan Islam yang berkomitmen untuk menghasilkan generasi yang berakhlak mulia dan berprestasi.');

        // Load Print Identity
        $this->print_logo_left_url = Setting::get('print_logo_left', '');
        $this->print_logo_right_url = Setting::get('print_logo_right', '');
        $this->print_institution_name = Setting::get('print_institution_name', 'MADRASAH TSANAWIYAH NEGERI 2 KOTA MALANG');
        $this->print_institution_address = Setting::get('print_institution_address', 'Jl. Bandung No. 7 Kota Malang, Jawa Timur 65117');
        $this->print_institution_phone = Setting::get('print_institution_phone', '(0341) 551276');
        $this->print_institution_email = Setting::get('print_institution_email', 'ppdb@mtsn2kotamalang.sch.id');
        $this->print_institution_website = Setting::get('print_institution_website', 'www.mtsn2kotamalang.sch.id');

        // Format dates for input
        if ($this->registration_start_date instanceof \Carbon\Carbon) {
            $this->registration_start_date = $this->registration_start_date->format('Y-m-d');
        }
        if ($this->registration_end_date instanceof \Carbon\Carbon) {
            $this->registration_end_date = $this->registration_end_date->format('Y-m-d');
        }
    }

    public function testEmailConnection()
    {
        $this->validate([
            'smtp_host' => 'required|string',
            'smtp_port' => 'required|numeric',
        ]);

        try {
            $connection = @fsockopen($this->smtp_host, $this->smtp_port, $errno, $errstr, 5);
            if (is_resource($connection)) {
                fclose($connection);
                $this->dispatch('settings-saved', message: 'Koneksi SMTP berhasil!');
            } else {
                throw new \Exception($errstr);
            }
        } catch (\Exception $e) {
            $this->addError('smtp_host', 'Koneksi gagal: ' . $e->getMessage());
        }
    }

    public function sendTestEmail()
    {
        $this->validate([
            'test_email_recipient' => 'required|email',
            'smtp_host' => 'required',
            'smtp_port' => 'required',
            'smtp_username' => 'required',
            'smtp_password' => 'required',
            'email_from_address' => 'required|email',
        ]);

        try {
            // Set SMTP as default mailer
            config(['mail.default' => 'smtp']);

            // Apply settings dynamically
            config([
                'mail.mailers.smtp.transport' => 'smtp',
                'mail.mailers.smtp.host' => $this->smtp_host,
                'mail.mailers.smtp.port' => $this->smtp_port,
                'mail.mailers.smtp.username' => $this->smtp_username,
                'mail.mailers.smtp.password' => $this->smtp_password,
                'mail.mailers.smtp.encryption' => $this->smtp_encryption ?: null,
                'mail.from.address' => $this->email_from_address,
                'mail.from.name' => $this->email_from_name,
            ]);

            // Reset the mail manager to pick up new configuration
            app('mail.manager')->forgetMailers();

            // Send test email using SMTP mailer explicitly
            \Illuminate\Support\Facades\Mail::mailer('smtp')->raw(
                'Ini adalah email percobaan dari sistem PPDB MTsN 2 Kota Malang. ' .
                'Jika Anda menerima email ini, berarti konfigurasi SMTP Anda sudah benar.',
                function ($message) {
                    $message->to($this->test_email_recipient)
                        ->subject('Test Email - Sistem PPDB MTsN 2 Kota Malang')
                        ->priority(1);
                }
            );

            $this->dispatch('settings-saved', message: 'Email percobaan berhasil dikirim ke ' . $this->test_email_recipient . '. Silakan cek inbox atau folder spam.');
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();

            // Provide more helpful error messages
            if (str_contains($errorMessage, 'Connection refused') || str_contains($errorMessage, 'Connection timed out')) {
                $errorMessage = 'Tidak dapat terhubung ke server SMTP. Periksa Host dan Port SMTP Anda.';
            } elseif (str_contains($errorMessage, 'Username') || str_contains($errorMessage, 'Authentication')) {
                $errorMessage = 'Autentikasi gagal. Periksa Username dan Password SMTP Anda.';
            } elseif (str_contains($errorMessage, 'certificate')) {
                $errorMessage = 'Masalah SSL/TLS. Coba ubah enkripsi atau periksa sertifikat server.';
            }

            $this->addError('test_email_recipient', 'Gagal mengirim email: ' . $errorMessage);

            // Log the full error for debugging
            \Log::error('Test email failed', [
                'error' => $e->getMessage(),
                'smtp_host' => $this->smtp_host,
                'smtp_port' => $this->smtp_port,
                'encryption' => $this->smtp_encryption,
            ]);
        }
    }

    public function testWhatsAppConnection()
    {
        $this->validate([
            'whatsapp_api_url' => 'required|url',
            'whatsapp_api_key' => 'required',
        ]);

        try {
            // Detect provider from URL
            $provider = 'unknown';
            if (str_contains($this->whatsapp_api_url, 'fonnte.com')) $provider = 'fonnte';
            elseif (str_contains($this->whatsapp_api_url, 'wablas.com')) $provider = 'wablas';
            elseif (str_contains($this->whatsapp_api_url, 'mtsn2kotamalang.sch.id')) $provider = 'mtsn2malang';

            // Test connection based on provider
            $response = match($provider) {
                'fonnte' => \Illuminate\Support\Facades\Http::withHeaders([
                    'Authorization' => $this->whatsapp_api_key
                ])->timeout(10)->get($this->whatsapp_api_url . '/device'),

                'wablas' => \Illuminate\Support\Facades\Http::withHeaders([
                    'Authorization' => $this->whatsapp_api_key
                ])->timeout(10)->get($this->whatsapp_api_url . '/device-status'),

                'mtsn2malang' => \Illuminate\Support\Facades\Http::timeout(10)
                    ->get($this->whatsapp_api_url . '/check'),

                default => \Illuminate\Support\Facades\Http::withHeaders([
                    'Authorization' => 'Bearer ' . $this->whatsapp_api_key
                ])->timeout(10)->get($this->whatsapp_api_url . '/status')
            };

            if ($response->successful()) {
                $this->dispatch('settings-saved', message: 'Koneksi WhatsApp API berhasil! Provider: ' . ucfirst($provider));
            } elseif ($response->status() === 404) {
                // 404 might be okay if endpoint doesn't exist but server is reachable
                $this->dispatch('settings-saved', message: 'Server WhatsApp dapat dijangkau. Silakan lanjutkan kirim tes pesan untuk verifikasi penuh.');
            } else {
                throw new \Exception('Server merespon dengan status: ' . $response->status());
            }
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            $this->addError('whatsapp_api_url', 'Tidak dapat terhubung ke server. Periksa URL dan koneksi internet Anda.');
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            if (str_contains($errorMessage, 'timeout') || str_contains($errorMessage, 'timed out')) {
                $errorMessage = 'Koneksi timeout. Periksa URL atau coba lagi.';
            }
            $this->addError('whatsapp_api_url', 'Koneksi gagal: ' . $errorMessage);
        }
    }

    public function sendTestWhatsApp()
    {
        // Detect provider to check if sender number is required
        $provider = 'unknown';
        if (str_contains($this->whatsapp_api_url, 'mtsn2kotamalang.sch.id')) {
            $provider = 'mtsn2malang';
        }

        // Validate based on provider
        $rules = [
            'test_whatsapp_number' => 'required',
            'whatsapp_api_url' => 'required|url',
            'whatsapp_api_key' => 'required',
        ];

        // Add sender number validation for mtsn2malang provider
        if ($provider === 'mtsn2malang') {
            $rules['whatsapp_sender_number'] = 'required|string';
        }

        $this->validate($rules);

        try {
            // Save settings first to make sure WhatsAppGatewayService uses latest config
            \App\Models\Setting::set('whatsapp_api_url', $this->whatsapp_api_url, 'string', 'WhatsApp API URL');
            \App\Models\Setting::set('whatsapp_api_key', $this->whatsapp_api_key, 'string', 'WhatsApp API Key');
            \App\Models\Setting::set('whatsapp_sender_number', $this->whatsapp_sender_number, 'string', 'WhatsApp Sender Number');

            // Log the attempt
            \Log::info('Testing WhatsApp message', [
                'provider' => $provider,
                'api_url' => $this->whatsapp_api_url,
                'to' => $this->test_whatsapp_number,
                'sender' => $this->whatsapp_sender_number,
            ]);

            // Use WhatsAppGatewayService
            $whatsappService = new \App\Services\WhatsAppGatewayService();
            $result = $whatsappService->sendMessage(
                $this->test_whatsapp_number,
                "🔔 *Test Pesan PPDB MTsN 2 Kota Malang*\n\nIni adalah pesan percobaan dari sistem PPDB MTsN 2 Kota Malang.\n\nJika Anda menerima pesan ini, berarti konfigurasi WhatsApp Gateway Anda sudah benar dan siap digunakan! ✅\n\n_Pesan ini dikirim secara otomatis untuk testing._"
            );

            if ($result['success']) {
                $this->dispatch('settings-saved', message: 'Pesan WhatsApp berhasil dikirim ke ' . $this->test_whatsapp_number . '. Silakan cek WhatsApp penerima.');

                // Log success
                \Log::info('WhatsApp test message sent successfully', [
                    'to' => $this->test_whatsapp_number,
                    'provider' => $provider
                ]);
            } else {
                throw new \Exception($result['message'] ?? 'Gagal mengirim pesan. Tidak ada informasi error.');
            }
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();

            // Log the full error
            \Log::error('WhatsApp test failed', [
                'error' => $errorMessage,
                'api_url' => $this->whatsapp_api_url,
                'to' => $this->test_whatsapp_number,
                'provider' => $provider
            ]);

            // Provide helpful error messages
            if (str_contains($errorMessage, '404') || str_contains($errorMessage, 'Not Found') || str_contains($errorMessage, 'Endpoint tidak ditemukan')) {
                $errorMessage = 'Endpoint API tidak ditemukan (404). ' .
                    ($provider === 'mtsn2malang'
                        ? 'Gunakan URL: https://wgw.mtsn2kotamalang.sch.id (tanpa /send-message)'
                        : 'Pastikan URL API WhatsApp Anda benar.');
            } elseif (str_contains($errorMessage, 'Unauthorized') || str_contains($errorMessage, '401') || str_contains($errorMessage, '403')) {
                $errorMessage = 'API Key tidak valid atau tidak memiliki akses. Periksa kembali API Key Anda.';
            } elseif (str_contains($errorMessage, 'timeout') || str_contains($errorMessage, 'Connection') || str_contains($errorMessage, 'terhubung')) {
                $errorMessage = 'Koneksi timeout atau gagal. Periksa koneksi internet atau URL API Anda.';
            } elseif (str_contains($errorMessage, 'Sender number belum dikonfigurasi')) {
                $errorMessage = 'Nomor pengirim (Sender Number) wajib diisi untuk gateway MTsN 2 Malang.';
            } elseif (str_contains($errorMessage, 'belum dikonfigurasi')) {
                $errorMessage = 'Konfigurasi WhatsApp belum lengkap. Pastikan semua field sudah diisi dengan benar.';
            }

            $this->addError('test_whatsapp_number', 'Gagal mengirim WhatsApp: ' . $errorMessage);
        }
    }

    public function saveIdentitySettings()
    {
        $this->validate([
            'app_name' => 'required|string',
            'app_acronym' => 'required|string|max:20',
            'app_full_name' => 'required|string|max:255',
            'tahun_pelajaran' => 'required|string',
            'app_logo' => 'nullable|image|max:1024',
            'app_favicon' => 'nullable|image|max:512',
            'app_footer_copyright' => 'required|string',
            'app_footer_description' => 'required|string',
        ]);

        if ($this->app_logo) {
            $logoPath = $this->app_logo->store('assets', 'public');
            $this->app_logo_url = \Illuminate\Support\Facades\Storage::url($logoPath);
            Setting::set('app_logo', $this->app_logo_url, 'string', 'Logo Aplikasi');
            $this->app_logo = null; // Reset file upload
        }

        if ($this->app_favicon) {
            $faviconPath = $this->app_favicon->store('assets', 'public');
            $this->app_favicon_url = \Illuminate\Support\Facades\Storage::url($faviconPath);
            Setting::set('app_favicon', $this->app_favicon_url, 'string', 'Favicon Aplikasi');
            $this->app_favicon = null; // Reset file upload
        }

        Setting::set('app_name', $this->app_name, 'string', 'Nama Aplikasi/Sekolah');
        Setting::set('app_acronym', $this->app_acronym, 'string', 'Singkatan/Akronim Sistem (misal: PMBM, PPDB)');
        Setting::set('app_full_name', $this->app_full_name, 'string', 'Nama Lengkap Sistem (misal: Penerimaan Murid Baru Madrasah)');
        Setting::set('tahun_pelajaran', $this->tahun_pelajaran, 'string', 'Tahun Pelajaran Aktif');
        Setting::set('app_footer_copyright', $this->app_footer_copyright, 'string', 'Teks Copyright Footer');
        Setting::set('app_footer_description', $this->app_footer_description, 'string', 'Deskripsi Lembaga Footer');

        // Clear all setting caches
        \Illuminate\Support\Facades\Cache::flush();

        $this->dispatch('settings-saved', message: 'Identitas Aplikasi berhasil disimpan! Silakan refresh halaman (Ctrl+Shift+R) untuk melihat perubahan.');
    }

    public function updatedEmailEnabled($value)
    {
        Setting::set('email_enabled', $value, 'boolean', 'Email Enabled Status');
        $status = $value ? 'diaktifkan' : 'dinonaktifkan';
        $this->dispatch('settings-saved', message: "Notifikasi Email telah {$status}!");
    }

    public function saveEmailSettings()
    {
        $this->validate([
            'email_from_address' => 'required|email',
            'email_from_name' => 'required|string',
            'smtp_host' => 'required|string',
            'smtp_port' => 'required|numeric',
            'smtp_username' => 'nullable|string',
            'smtp_password' => 'nullable|string',
            'smtp_encryption' => 'nullable|string',
            'email_enabled' => 'boolean',
        ]);

        Setting::set('email_from_address', $this->email_from_address, 'string', 'Alamat Email Pengirim');
        Setting::set('email_from_name', $this->email_from_name, 'string', 'Nama Pengirim Email');
        Setting::set('smtp_host', $this->smtp_host, 'string', 'SMTP Host');
        Setting::set('smtp_port', $this->smtp_port, 'integer', 'SMTP Port');
        Setting::set('smtp_username', $this->smtp_username, 'string', 'SMTP Username');
        Setting::set('smtp_password', $this->smtp_password, 'string', 'SMTP Password');
        Setting::set('smtp_encryption', $this->smtp_encryption, 'string', 'SMTP Encryption');
        Setting::set('email_enabled', $this->email_enabled, 'boolean', 'Email Enabled Status');

        $this->dispatch('settings-saved', message: 'Pengaturan Email berhasil disimpan!');
    }

    public function updatedWhatsappEnabled($value)
    {
        Setting::set('whatsapp_enabled', $value, 'boolean', 'WhatsApp Enabled Status');
        $status = $value ? 'diaktifkan' : 'dinonaktifkan';
        $this->dispatch('settings-saved', message: "Notifikasi WhatsApp telah {$status}!");
    }

    public function saveWhatsAppSettings()
    {
        $this->validate([
            'whatsapp_api_url' => 'required|url',
            'whatsapp_api_key' => 'required|string',
            'whatsapp_sender_number' => 'required|string',
            'whatsapp_enabled' => 'boolean',
        ]);

        Setting::set('whatsapp_api_url', $this->whatsapp_api_url, 'string', 'WhatsApp API URL');
        Setting::set('whatsapp_api_key', $this->whatsapp_api_key, 'string', 'WhatsApp API Key');
        Setting::set('whatsapp_sender_number', $this->whatsapp_sender_number, 'string', 'WhatsApp Sender Number');
        Setting::set('whatsapp_enabled', $this->whatsapp_enabled, 'boolean', 'WhatsApp Enabled Status');

        $this->dispatch('settings-saved', message: 'Pengaturan WhatsApp berhasil disimpan!');
    }

    public function saveGeneralSettings()
    {
        $this->validate([
            'registration_phase' => 'required|string',
            'registration_phase_label' => 'required|string',
            'registration_phase_description' => 'nullable|string',
            'registration_start_date' => 'required|date',
            'registration_end_date' => 'required|date|after_or_equal:registration_start_date',
        ]);

        Setting::set('registration_phase', $this->registration_phase, 'string', 'Fase Pendaftaran');
        Setting::set('registration_phase_label', $this->registration_phase_label, 'string', 'Label Fase Pendaftaran');
        Setting::set('registration_phase_description', $this->registration_phase_description, 'string', 'Deskripsi Fase Pendaftaran');
        Setting::set('registration_start_date', $this->registration_start_date, 'date', 'Tanggal Mulai Pendaftaran');
        Setting::set('registration_end_date', $this->registration_end_date, 'date', 'Tanggal Selesai Pendaftaran');

        $this->dispatch('settings-saved', message: 'Pengaturan Umum berhasil disimpan!');
    }

    public function savePrintIdentitySettings()
    {
        $this->validate([
            'print_logo_left' => 'nullable|image|max:1024',
            'print_logo_right' => 'nullable|image|max:1024',
            'print_institution_name' => 'required|string',
            'print_institution_address' => 'required|string',
            'print_institution_phone' => 'nullable|string',
            'print_institution_email' => 'nullable|email',
            'print_institution_website' => 'nullable|string',
        ]);

        if ($this->print_logo_left) {
            $logoLeftPath = $this->print_logo_left->store('print-assets', 'public');
            $this->print_logo_left_url = \Illuminate\Support\Facades\Storage::url($logoLeftPath);
            Setting::set('print_logo_left', $this->print_logo_left_url, 'string', 'Logo Kiri Kop PDF');
        }

        if ($this->print_logo_right) {
            $logoRightPath = $this->print_logo_right->store('print-assets', 'public');
            $this->print_logo_right_url = \Illuminate\Support\Facades\Storage::url($logoRightPath);
            Setting::set('print_logo_right', $this->print_logo_right_url, 'string', 'Logo Kanan Kop PDF');
        }

        Setting::set('print_institution_name', $this->print_institution_name, 'string', 'Nama Lembaga (Kop PDF)');
        Setting::set('print_institution_address', $this->print_institution_address, 'string', 'Alamat Lembaga (Kop PDF)');
        Setting::set('print_institution_phone', $this->print_institution_phone, 'string', 'Telepon Lembaga (Kop PDF)');
        Setting::set('print_institution_email', $this->print_institution_email, 'string', 'Email Lembaga (Kop PDF)');
        Setting::set('print_institution_website', $this->print_institution_website, 'string', 'Website Lembaga (Kop PDF)');

        $this->dispatch('settings-saved', message: 'Identitas Cetak berhasil disimpan!');
    }

    public function render()
    {
        return view('livewire.admin.settings')
            ->layout('components.layouts.dashboard', [
                'role' => 'admin',
                'pageTitle' => 'Pengaturan Sistem',
                'pageDescription' => 'Kelola konfigurasi sistem PPDB'
            ]);
    }
}