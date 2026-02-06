<?php

namespace App\Services;

use App\Models\MessageQueue;
use App\Models\Pendaftar;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppQueueService
{
    /**
     * Jam kerja mulai
     */
    const WORKING_HOUR_START = 6; // 06:00

    /**
     * Jam kerja selesai
     */
    const WORKING_HOUR_END = 18; // 18:00

    /**
     * Interval minimum antar pesan (menit) - normal
     */
    const MIN_INTERVAL_MINUTES = 5;

    /**
     * Interval maksimum antar pesan (menit) - normal
     */
    const MAX_INTERVAL_MINUTES = 10;

    /**
     * Interval minimum untuk pesan yang overdue (menit)
     */
    const MIN_DELAYED_INTERVAL_MINUTES = 20;

    /**
     * Interval maksimum untuk pesan yang overdue (menit)
     */
    const MAX_DELAYED_INTERVAL_MINUTES = 70;

    /**
     * Queue WhatsApp message
     *
     * @param array $data
     * @return MessageQueue
     */
    public function queueMessage(array $data): MessageQueue
    {
        // Tentukan scheduled_at berdasarkan jam kerja
        $scheduledAt = $this->calculateScheduledTime($data['is_manual'] ?? false);

        // Buat queue entry
        $queue = MessageQueue::create([
            'pendaftar_id' => $data['pendaftar_id'] ?? null,
            'user_id' => $data['user_id'] ?? auth()->id(),
            'jenis_pesan' => 'whatsapp',
            'penerima' => $data['penerima'],
            'isi_pesan' => $data['isi_pesan'],
            'template_name' => $data['template_name'] ?? null,
            'status' => 'queued',
            'priority' => $data['priority'] ?? 'normal',
            'is_manual' => $data['is_manual'] ?? false,
            'scheduled_at' => $scheduledAt,
            'metadata' => $data['metadata'] ?? null,
            'notes' => $data['notes'] ?? null,
        ]);

        Log::info('WhatsApp message queued', [
            'queue_id' => $queue->id,
            'penerima' => $queue->penerima,
            'scheduled_at' => $scheduledAt->format('Y-m-d H:i:s'),
            'is_manual' => $queue->is_manual,
        ]);

        return $queue;
    }

    /**
     * Calculate scheduled time based on working hours
     *
     * @param bool $isManual
     * @return Carbon
     */
    private function calculateScheduledTime(bool $isManual): Carbon
    {
        $now = now();
        $hour = $now->hour;

        // Logika berdasarkan jam:
        // 1. Jam 18:00 - 00:00 (23:59): Antrian untuk besok jam 06:00
        // 2. Jam 00:01 - 05:59: Antrian hari ini, setelah antrian kemarin malam
        // 3. Jam 06:00 - 17:59: Kirim langsung dengan interval 5-10 menit

        if ($hour >= self::WORKING_HOUR_END) {
            // Jam 18:00 - 23:59: Schedule untuk besok pagi jam 6
            $scheduledAt = $this->getNextWorkingDayStart($now);
        } elseif ($hour < self::WORKING_HOUR_START) {
            // Jam 00:00 - 05:59: Schedule hari ini, tapi setelah antrian kemarin malam
            $scheduledAt = $this->getEarlyMorningSchedule($now);
        } else {
            // Jam 06:00 - 17:59 (jam kerja): Schedule dengan interval normal
            $scheduledAt = $this->getNextAvailableSlot($now, false);
        }

        Log::info('Scheduled time calculated', [
            'current_time' => $now->format('Y-m-d H:i:s'),
            'scheduled_at' => $scheduledAt->format('Y-m-d H:i:s'),
            'is_manual' => $isManual,
            'hour' => $hour,
        ]);

        return $scheduledAt;
    }

    /**
     * Check if current time is within working hours
     *
     * @param Carbon $time
     * @return bool
     */
    private function isWithinWorkingHours(Carbon $time): bool
    {
        $hour = $time->hour;
        return $hour >= self::WORKING_HOUR_START && $hour < self::WORKING_HOUR_END;
    }

    /**
     * Get next working day start time (6:00 AM)
     *
     * @param Carbon $from
     * @return Carbon
     */
    private function getNextWorkingDayStart(Carbon $from): Carbon
    {
        $nextDay = $from->copy();

        // Jika sudah lewat jam kerja hari ini, mulai dari besok
        if ($from->hour >= self::WORKING_HOUR_END) {
            $nextDay->addDay();
        }

        // Set ke jam 6 pagi, lalu cari slot available
        $startTime = $nextDay->setTime(self::WORKING_HOUR_START, 0, 0);

        return $this->getNextAvailableSlot($startTime, false);
    }

    /**
     * Get schedule untuk pesan dini hari (00:01 - 05:59)
     * Harus melanjutkan antrian dari jam 18:00-00:00 kemarin malam
     *
     * @param Carbon $from
     * @return Carbon
     */
    private function getEarlyMorningSchedule(Carbon $from): Carbon
    {
        // Jam 6 pagi hari ini
        $todayStart = $from->copy()->setTime(self::WORKING_HOUR_START, 0, 0);

        // Cari pesan terakhir yang dijadwalkan untuk hari ini
        // (pesan dari jam 18:00-00:00 kemarin malam dijadwalkan untuk hari ini jam 6)
        $lastYesterdayNightMessage = MessageQueue::where('jenis_pesan', 'whatsapp')
            ->where('status', 'queued')
            ->where('scheduled_at', '>=', $todayStart)
            ->orderBy('scheduled_at', 'desc')
            ->first();

        if ($lastYesterdayNightMessage) {
            // Ada antrian dari kemarin malam, lanjutkan setelah yang terakhir
            $randomInterval = rand(self::MIN_INTERVAL_MINUTES, self::MAX_INTERVAL_MINUTES);
            $nextSlot = $lastYesterdayNightMessage->scheduled_at->copy()->addMinutes($randomInterval);

            // Pastikan masih dalam jam kerja
            if ($nextSlot->hour >= self::WORKING_HOUR_END) {
                // Kalau melewati jam kerja, pindah ke besok
                return $this->getNextWorkingDayStart($nextSlot);
            }

            return $nextSlot;
        }

        // Tidak ada antrian dari kemarin malam, mulai dari jam 6
        return $this->getNextAvailableSlot($todayStart, false);
    }

    /**
     * Get next available time slot with random interval
     *
     * @param Carbon $from
     * @param bool $useDelayedInterval - Gunakan interval lebih lama untuk pesan overdue
     * @return Carbon
     */
    private function getNextAvailableSlot(Carbon $from, bool $useDelayedInterval = false): Carbon
    {
        // Ambil pesan terakhir yang di-queue
        $lastMessage = MessageQueue::where('jenis_pesan', 'whatsapp')
            ->where('status', 'queued')
            ->orderBy('scheduled_at', 'desc')
            ->first();

        if (!$lastMessage) {
            // Tidak ada antrian, kirim sekarang
            return $from->copy();
        }

        // Tentukan interval berdasarkan apakah ini delayed atau normal
        if ($useDelayedInterval) {
            // Interval lebih lama untuk pesan yang overdue (20-70 menit)
            $randomInterval = rand(self::MIN_DELAYED_INTERVAL_MINUTES, self::MAX_DELAYED_INTERVAL_MINUTES);
        } else {
            // Interval normal (5-10 menit)
            $randomInterval = rand(self::MIN_INTERVAL_MINUTES, self::MAX_INTERVAL_MINUTES);
        }

        // Tambahkan interval dari pesan terakhir
        $nextSlot = $lastMessage->scheduled_at->copy()->addMinutes($randomInterval);

        // Jika nextSlot melewati jam kerja, pindah ke besok pagi dengan interval delayed
        if ($nextSlot->hour >= self::WORKING_HOUR_END) {
            return $this->getNextWorkingDayStart($nextSlot);
        }

        // Jika nextSlot sudah lewat sekarang, gunakan sekarang + interval random
        if ($nextSlot->isPast()) {
            $nextSlot = $from->copy()->addMinutes($randomInterval);

            // Check lagi apakah melewati jam kerja
            if ($nextSlot->hour >= self::WORKING_HOUR_END) {
                return $this->getNextWorkingDayStart($nextSlot);
            }
        }

        return $nextSlot;
    }

    /**
     * Process queue and send WhatsApp messages
     *
     * @return array
     */
    public function processQueue(): array
    {
        $results = [
            'processed' => 0,
            'sent' => 0,
            'failed' => 0,
            'skipped' => 0,
            'rescheduled' => 0,
        ];

        // Hanya proses jika dalam jam kerja
        if (!$this->isWithinWorkingHours(now())) {
            Log::info('Outside working hours, skipping queue processing');

            // Re-schedule pesan yang overdue untuk besok dengan interval delay
            $this->rescheduleOverdueMessages();

            return $results;
        }

        // Ambil pesan yang siap dikirim
        $messages = MessageQueue::readyToSend()
            ->whatsAppOnly()
            ->limit(50) // Proses max 50 pesan per run
            ->get();

        foreach ($messages as $message) {
            $results['processed']++;

            try {
                // Mark as processing
                $message->markAsProcessing();

                // Send WhatsApp
                $response = $this->sendWhatsApp($message);

                if ($response['success']) {
                    $message->markAsSent($response['data'] ?? null);
                    $results['sent']++;
                } else {
                    $message->markAsFailed($response['error'] ?? 'Unknown error');

                    // Retry jika masih bisa
                    if ($message->canRetry()) {
                        $message->requeue(15); // Retry setelah 15 menit
                    }

                    $results['failed']++;
                }

            } catch (\Exception $e) {
                Log::error('Failed to process message queue', [
                    'queue_id' => $message->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);

                $message->markAsFailed($e->getMessage(), $e->getTraceAsString());

                // Retry jika masih bisa
                if ($message->canRetry()) {
                    $message->requeue(15);
                }

                $results['failed']++;
            }

            // Sleep untuk interval (sudah di-handle oleh scheduled_at, tapi tambah safety)
            sleep(2); // 2 detik antar proses
        }

        Log::info('Queue processing completed', $results);

        return $results;
    }

    /**
     * Re-schedule pesan yang overdue (belum terkirim hingga jam kerja selesai)
     * dengan interval delay yang lebih lama (20-70 menit)
     *
     * @return int
     */
    public function rescheduleOverdueMessages(): int
    {
        $now = now();

        // Hanya jalankan di luar jam kerja
        if ($this->isWithinWorkingHours($now)) {
            return 0;
        }

        // Cari pesan yang masih queued tapi scheduled_at-nya sudah lewat jam kerja hari ini
        $today = $now->copy()->startOfDay();
        $endOfWorkingHours = $today->copy()->setTime(self::WORKING_HOUR_END, 0, 0);

        $overdueMessages = MessageQueue::where('jenis_pesan', 'whatsapp')
            ->where('status', 'queued')
            ->where('scheduled_at', '<', $endOfWorkingHours)
            ->whereDate('scheduled_at', '<=', $today)
            ->get();

        $rescheduledCount = 0;

        foreach ($overdueMessages as $message) {
            // Re-schedule untuk besok dengan interval delay (20-70 menit)
            $nextWorkingDay = $now->copy()->addDay()->setTime(self::WORKING_HOUR_START, 0, 0);
            $newScheduledAt = $this->getNextAvailableSlot($nextWorkingDay, true); // true = use delayed interval

            $message->update([
                'scheduled_at' => $newScheduledAt,
                'notes' => ($message->notes ?? '') . ' [Overdue - Re-scheduled dengan interval delay]',
            ]);

            $rescheduledCount++;

            Log::info('Overdue message rescheduled', [
                'queue_id' => $message->id,
                'old_scheduled_at' => $message->scheduled_at->format('Y-m-d H:i:s'),
                'new_scheduled_at' => $newScheduledAt->format('Y-m-d H:i:s'),
            ]);
        }

        if ($rescheduledCount > 0) {
            Log::info("Rescheduled {$rescheduledCount} overdue messages with delayed interval");
        }

        return $rescheduledCount;
    }

    /**
     * Send WhatsApp message via API
     *
     * @param MessageQueue $message
     * @return array
     */
    private function sendWhatsApp(MessageQueue $message): array
    {
        try {
            // Get WhatsApp API config from settings
            $apiUrl = config('services.whatsapp.api_url') ?? env('WHATSAPP_API_URL');
            $apiKey = config('services.whatsapp.api_key') ?? env('WHATSAPP_API_KEY');

            if (!$apiUrl || !$apiKey) {
                throw new \Exception('WhatsApp API credentials not configured');
            }

            // Format nomor telepon (remove +, spaces, dashes)
            $phone = preg_replace('/[^0-9]/', '', $message->penerima);

            // Pastikan format 62xxx
            if (!str_starts_with($phone, '62')) {
                // Jika format 08xx, ubah ke 62
                if (str_starts_with($phone, '0')) {
                    $phone = '62' . substr($phone, 1);
                } else {
                    $phone = '62' . $phone;
                }
            }

            // Send via API
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(30)->post($apiUrl, [
                'phone' => $phone,
                'message' => $message->isi_pesan,
                'metadata' => $message->metadata,
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json(),
                ];
            }

            return [
                'success' => false,
                'error' => $response->json()['message'] ?? 'API request failed',
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get queue statistics
     *
     * @return array
     */
    public function getStatistics(): array
    {
        return [
            'total' => MessageQueue::whatsAppOnly()->count(),
            'queued' => MessageQueue::whatsAppOnly()->byStatus('queued')->count(),
            'processing' => MessageQueue::whatsAppOnly()->byStatus('processing')->count(),
            'sent' => MessageQueue::whatsAppOnly()->byStatus('sent')->count(),
            'failed' => MessageQueue::whatsAppOnly()->byStatus('failed')->count(),
            'today' => MessageQueue::whatsAppOnly()->whereDate('created_at', today())->count(),
            'manual' => MessageQueue::whatsAppOnly()->manual()->count(),
            'automatic' => MessageQueue::whatsAppOnly()->automatic()->count(),
        ];
    }

    /**
     * Cancel queued message
     *
     * @param int $queueId
     * @return bool
     */
    public function cancelMessage(int $queueId): bool
    {
        $message = MessageQueue::find($queueId);

        if (!$message || $message->status !== 'queued') {
            return false;
        }

        $message->update(['status' => 'cancelled']);

        Log::info('Message cancelled', ['queue_id' => $queueId]);

        return true;
    }

    /**
     * Retry failed message
     *
     * @param int $queueId
     * @return bool
     */
    public function retryMessage(int $queueId): bool
    {
        $message = MessageQueue::find($queueId);

        if (!$message || !in_array($message->status, ['failed', 'cancelled'])) {
            return false;
        }

        $message->update([
            'status' => 'queued',
            'scheduled_at' => $this->calculateScheduledTime($message->is_manual),
            'error_message' => null,
            'error_trace' => null,
        ]);

        Log::info('Message requeued for retry', ['queue_id' => $queueId]);

        return true;
    }
}
