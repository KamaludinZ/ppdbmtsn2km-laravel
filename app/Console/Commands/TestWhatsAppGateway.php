<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\WhatsAppGatewayService;
use App\Models\Setting;

class TestWhatsAppGateway extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whatsapp:test {number?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test WhatsApp Gateway configuration and send test message';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('========================================');
        $this->info('WhatsApp Gateway Configuration Test');
        $this->info('========================================');
        $this->newLine();

        // Load settings
        $apiUrl = Setting::get('whatsapp_api_url');
        $apiKey = Setting::get('whatsapp_api_key');
        $senderNumber = Setting::get('whatsapp_sender_number');
        $enabled = Setting::get('whatsapp_enabled', false);

        // Display configuration
        $this->info('Current Configuration:');
        $this->line('API URL: ' . ($apiUrl ?: '<not set>'));
        $this->line('API Key: ' . ($apiKey ? substr($apiKey, 0, 10) . '...' : '<not set>'));
        $this->line('Sender Number: ' . ($senderNumber ?: '<not set>'));
        $this->line('Status: ' . ($enabled ? 'ENABLED' : 'DISABLED'));
        $this->newLine();

        // Check if configured
        if (!$apiUrl || !$apiKey) {
            $this->error('WhatsApp Gateway belum dikonfigurasi!');
            $this->line('Silakan setup di menu Settings terlebih dahulu.');
            return 1;
        }

        // Detect provider
        $provider = 'unknown';
        if (str_contains($apiUrl, 'fonnte.com')) $provider = 'fonnte';
        elseif (str_contains($apiUrl, 'wablas.com')) $provider = 'wablas';
        elseif (str_contains($apiUrl, 'mtsn2kotamalang.sch.id')) $provider = 'mtsn2malang';

        $this->info('Detected Provider: ' . strtoupper($provider));
        $this->newLine();

        // Get phone number
        $phoneNumber = $this->argument('number');
        if (!$phoneNumber) {
            $phoneNumber = $this->ask('Masukkan nomor WhatsApp tujuan (format: 628xxx)');
        }

        if (!$phoneNumber) {
            $this->error('Nomor WhatsApp tidak boleh kosong!');
            return 1;
        }

        // Send test message
        $this->info('Mengirim pesan test ke: ' . $phoneNumber);
        $this->line('Harap tunggu...');
        $this->newLine();

        try {
            $service = new WhatsAppGatewayService();
            $result = $service->sendMessage(
                $phoneNumber,
                "🔔 *Test Pesan dari Console*\n\nIni adalah pesan test dari command line.\n\nWaktu: " . now()->format('d/m/Y H:i:s')
            );

            if ($result['success']) {
                $this->info('✓ Pesan berhasil dikirim!');
                $this->newLine();
                $this->line('Response Data:');
                $this->line(json_encode($result['data'], JSON_PRETTY_PRINT));
                $this->newLine();
                $this->info('Silakan cek WhatsApp penerima.');
                return 0;
            } else {
                $this->error('✗ Gagal mengirim pesan!');
                $this->line('Error: ' . ($result['message'] ?? 'Unknown error'));
                return 1;
            }
        } catch (\Exception $e) {
            $this->error('✗ Exception occurred!');
            $this->error($e->getMessage());
            $this->newLine();
            $this->line('Stack trace:');
            $this->line($e->getTraceAsString());
            return 1;
        }
    }
}
