<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Setting;

class WhatsAppGatewayService
{
    protected $baseUrl;
    protected $accessToken;
    protected $provider;
    protected $senderNumber;

    public function __construct()
    {
        // Get from database settings first, fallback to env
        $this->baseUrl = Setting::get('whatsapp_api_url', env('WHATSAPP_GATEWAY_URL'));
        $this->accessToken = Setting::get('whatsapp_api_key', env('WHATSAPP_ACCESS_TOKEN'));
        $this->senderNumber = Setting::get('whatsapp_sender_number', env('WHATSAPP_SENDER_NUMBER', ''));
        $this->provider = $this->detectProvider($this->baseUrl);
    }

    /**
     * Detect WhatsApp provider from URL
     */
    protected function detectProvider($url)
    {
        if (!$url) return 'unknown';

        if (str_contains($url, 'fonnte.com')) return 'fonnte';
        if (str_contains($url, 'wablas.com')) return 'wablas';
        if (str_contains($url, 'waapi.app')) return 'waapi';
        if (str_contains($url, 'graph.facebook.com')) return 'meta';
        if (str_contains($url, 'mtsn2kotamalang.sch.id')) return 'mtsn2malang';

        return 'custom';
    }

    /**
     * Send WhatsApp message
     *
     * @param string $phoneNumber
     * @param string $message
     * @return array
     */
    public function sendMessage($phoneNumber, $message)
    {
        try {
            // Validate configuration
            if (!$this->baseUrl || !$this->accessToken) {
                throw new \Exception('WhatsApp API belum dikonfigurasi. Silakan setup di menu Settings.');
            }

            // Format phone number
            $phoneNumber = $this->formatPhoneNumber($phoneNumber);

            // Send based on provider
            $response = match($this->provider) {
                'fonnte' => $this->sendViaFonnte($phoneNumber, $message),
                'wablas' => $this->sendViaWablas($phoneNumber, $message),
                'waapi' => $this->sendViaWaapi($phoneNumber, $message),
                'meta' => $this->sendViaMeta($phoneNumber, $message),
                'mtsn2malang' => $this->sendViaMtsn2Malang($phoneNumber, $message),
                default => $this->sendViaCustom($phoneNumber, $message)
            };

            Log::info('WhatsApp Message Sent:', [
                'provider' => $this->provider,
                'to' => $phoneNumber,
                'status' => $response['success'] ?? false
            ]);

            return $response;

        } catch (\Exception $e) {
            Log::error('Failed to send WhatsApp message:', [
                'provider' => $this->provider,
                'error' => $e->getMessage(),
                'to' => $phoneNumber ?? 'unknown'
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Format phone number to international format
     */
    protected function formatPhoneNumber($phoneNumber)
    {
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

        if (substr($phoneNumber, 0, 1) === '0') {
            $phoneNumber = '62' . substr($phoneNumber, 1);
        } elseif (substr($phoneNumber, 0, 2) !== '62') {
            $phoneNumber = '62' . $phoneNumber;
        }

        return $phoneNumber;
    }

    /**
     * Send via Fonnte
     */
    protected function sendViaFonnte($phoneNumber, $message)
    {
        $response = Http::withHeaders([
            'Authorization' => $this->accessToken,
        ])->post($this->baseUrl . '/send', [
            'target' => $phoneNumber,
            'message' => $message,
        ]);

        if ($response->successful()) {
            return ['success' => true, 'data' => $response->json()];
        }

        throw new \Exception('Fonnte API Error: ' . ($response->json()['reason'] ?? $response->body()));
    }

    /**
     * Send via Wablas
     */
    protected function sendViaWablas($phoneNumber, $message)
    {
        $response = Http::withHeaders([
            'Authorization' => $this->accessToken,
        ])->post($this->baseUrl . '/send-message', [
            'phone' => $phoneNumber,
            'message' => $message,
        ]);

        if ($response->successful()) {
            return ['success' => true, 'data' => $response->json()];
        }

        throw new \Exception('Wablas API Error: ' . ($response->json()['message'] ?? $response->body()));
    }

    /**
     * Send via WA API
     */
    protected function sendViaWaapi($phoneNumber, $message)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken,
            'Content-Type' => 'application/json'
        ])->post($this->baseUrl . '/messages/send', [
            'to' => $phoneNumber,
            'body' => $message,
        ]);

        if ($response->successful()) {
            return ['success' => true, 'data' => $response->json()];
        }

        throw new \Exception('WA API Error: ' . ($response->json()['error'] ?? $response->body()));
    }

    /**
     * Send via Meta WhatsApp Business API
     */
    protected function sendViaMeta($phoneNumber, $message)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken,
            'Content-Type' => 'application/json'
        ])->post($this->baseUrl, [
            'messaging_product' => 'whatsapp',
            'to' => $phoneNumber,
            'type' => 'text',
            'text' => ['body' => $message]
        ]);

        if ($response->successful()) {
            return ['success' => true, 'data' => $response->json()];
        }

        throw new \Exception('Meta API Error: ' . ($response->json()['error']['message'] ?? $response->body()));
    }

    /**
     * Send via MTsN 2 Malang Custom Gateway
     *
     * API Documentation:
     * - Endpoint: /send-message
     * - Method: POST or GET
     * - Parameters: api_key, sender, number, message
     */
    protected function sendViaMtsn2Malang($phoneNumber, $message)
    {
        // Validate sender number
        if (empty($this->senderNumber)) {
            throw new \Exception('Sender number belum dikonfigurasi. Silakan setup Nomor Pengirim di menu Settings.');
        }

        // Format request sesuai dokumentasi API MTsN 2 Malang
        $requestData = [
            'api_key' => $this->accessToken,
            'sender' => $this->senderNumber,
            'number' => $phoneNumber,
            'message' => $message
        ];

        try {
            // Determine endpoint - jangan tambahkan /send-message jika baseUrl sudah mengandungnya
            $endpoint = str_ends_with($this->baseUrl, '/send-message')
                ? $this->baseUrl
                : $this->baseUrl . '/send-message';

            // Log request data untuk debugging
            Log::info('MTsN 2 Gateway Request:', [
                'endpoint' => $endpoint,
                'data' => [
                    'api_key' => substr($this->accessToken, 0, 10) . '...',
                    'sender' => $this->senderNumber,
                    'number' => $phoneNumber,
                    'message_length' => strlen($message)
                ]
            ]);

            // Try POST request dengan form data (bukan JSON)
            $response = Http::asForm()
                ->timeout(30)
                ->withOptions(['verify' => false]) // Skip SSL verification untuk compatibility
                ->post($endpoint, $requestData);

            // Hanya fallback ke GET jika 404 atau 405 (Method Not Allowed)
            // Jangan fallback jika 400 (Bad Request) karena itu error parameter, bukan connection
            if (!$response->successful() && in_array($response->status(), [404, 405])) {
                Log::info('POST failed with method error, trying GET method...', ['status' => $response->status()]);

                // Build query string
                $queryString = http_build_query($requestData);
                $getEndpoint = $endpoint . '?' . $queryString;

                Log::info('Trying GET request:', ['url' => $getEndpoint]);

                $response = Http::timeout(30)
                    ->withOptions(['verify' => false])
                    ->get($getEndpoint);
            }

            if ($response->successful()) {
                // Log raw response
                Log::info('MTsN 2 Gateway Raw Response:', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'headers' => $response->headers()
                ]);

                // Try to parse as JSON
                try {
                    $data = $response->json();
                } catch (\Exception $e) {
                    // If not JSON, treat as success if status is 200
                    Log::info('Response is not JSON, treating as success', ['body' => $response->body()]);
                    return ['success' => true, 'data' => ['message' => $response->body()]];
                }

                // Log parsed response
                Log::info('MTsN 2 Gateway Parsed Response:', ['response' => $data]);

                // Check berbagai format success response
                if (isset($data['success']) && $data['success'] === true) {
                    return ['success' => true, 'data' => $data];
                }

                if (isset($data['status']) && $data['status'] === true) {
                    return ['success' => true, 'data' => $data];
                }

                if (isset($data['status']) && in_array(strtolower($data['status']), ['success', 'sent', 'queued', 'pending'])) {
                    return ['success' => true, 'data' => $data];
                }

                // Jika status false, lempar exception dengan pesan yang jelas
                if (isset($data['status']) && $data['status'] === false) {
                    $errorMsg = $data['msg'] ?? $data['message'] ?? 'Unknown error from gateway';
                    throw new \Exception('Gateway merespons error: ' . $errorMsg . '. Kemungkinan: Device WhatsApp tidak tersambung, API Key tidak valid, atau kuota habis.');
                }

                // Jika response code 200 tapi tidak ada field success yang jelas
                // Anggap berhasil karena tidak ada error
                return ['success' => true, 'data' => $data];
            }

            // Handle error responses
            // Log full response untuk debugging
            Log::error('MTsN 2 Gateway Error Response:', [
                'status' => $response->status(),
                'body' => $response->body(),
                'headers' => $response->headers()
            ]);

            if ($response->status() === 404) {
                // Check jika ada duplikasi endpoint di URL
                if (str_contains($endpoint, '/send-message/send-message')) {
                    throw new \Exception('URL salah! Jangan tambahkan /send-message di akhir URL. Gunakan: https://wgw.mtsn2kotamalang.sch.id (tanpa /send-message)');
                }
                throw new \Exception('Endpoint tidak ditemukan. URL yang digunakan: ' . $endpoint . '. Pastikan URL sudah benar.');
            }

            if ($response->status() === 401 || $response->status() === 403) {
                throw new \Exception('API Key tidak valid. Periksa konfigurasi API Key Anda.');
            }

            if ($response->status() === 400) {
                // Parse response body untuk mendapatkan error message yang lebih detail
                try {
                    $errorData = $response->json();

                    Log::error('MTsN 2 Gateway Bad Request Details:', [
                        'parsed_data' => $errorData,
                        'raw_body' => $response->body()
                    ]);

                    // Check jika ada format {status: false, msg: "..."}
                    if (isset($errorData['status']) && $errorData['status'] === false) {
                        $errorMsg = $errorData['msg'] ?? $errorData['message'] ?? 'Unknown error';

                        // Berikan pesan yang lebih helpful
                        if (str_contains(strtolower($errorMsg), 'failed to send')) {
                            throw new \Exception('Gateway gagal mengirim pesan: ' . $errorMsg . '. SOLUSI: 1) Pastikan device WhatsApp tersambung di dashboard gateway, 2) Periksa API Key masih aktif, 3) Pastikan nomor pengirim terdaftar di gateway, 4) Cek kuota API Anda.');
                        } else {
                            throw new \Exception('Gateway error: ' . $errorMsg);
                        }
                    }

                    $errorMsg = $errorData['message'] ?? $errorData['error'] ?? $errorData['msg'] ?? $response->body();
                } catch (\Illuminate\Http\Client\RequestException $e) {
                    $errorMsg = $response->body();
                }

                throw new \Exception('Bad Request (400): ' . $errorMsg);
            }

            // Generic error
            try {
                $errorData = $response->json();
                $errorMessage = $errorData['message'] ?? $errorData['error'] ?? 'Unknown error';
            } catch (\Exception $e) {
                $errorMessage = $response->body() ?: 'Unknown error';
            }

            throw new \Exception('MTsN 2 Gateway Error (Status ' . $response->status() . '): ' . $errorMessage);

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            throw new \Exception('Tidak dapat terhubung ke gateway MTsN 2. Periksa koneksi internet atau URL gateway.');
        }
    }

    /**
     * Send via custom endpoint
     */
    protected function sendViaCustom($phoneNumber, $message)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken,
            'Content-Type' => 'application/json'
        ])->timeout(10)->post($this->baseUrl . '/send-message', [
            'phone_number' => $phoneNumber,
            'message' => $message,
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return ['success' => true, 'data' => $data];
        }

        // Better error message for 404
        if ($response->status() === 404) {
            throw new \Exception('Endpoint tidak ditemukan. Periksa URL API WhatsApp Anda. URL saat ini: ' . $this->baseUrl);
        }

        throw new \Exception('API Error (Status ' . $response->status() . '): ' .
            ($response->json()['message'] ?? substr(strip_tags($response->body()), 0, 100)));
    }
}