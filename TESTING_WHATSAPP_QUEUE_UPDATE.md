# Testing Guide - WhatsApp Queue Update

## Quick Test Checklist

### Test 1: Pesan Masuk Jam Malam (18:00 - 00:00)

**Tujuan**: Memastikan pesan dijadwalkan untuk besok jam 06:00

```bash
php artisan tinker
```

```php
// Set waktu test ke jam 19:30
Carbon\Carbon::setTestNow(now()->setTime(19, 30, 0));

$queueService = new \App\Services\WhatsAppQueueService();

// Queue 3 pesan
$queue1 = $queueService->queueMessage([
    'penerima' => '6281234567890',
    'isi_pesan' => 'Test pesan malam 1',
    'is_manual' => true,
]);

$queue2 = $queueService->queueMessage([
    'penerima' => '6281234567890',
    'isi_pesan' => 'Test pesan malam 2',
    'is_manual' => true,
]);

$queue3 = $queueService->queueMessage([
    'penerima' => '6281234567890',
    'isi_pesan' => 'Test pesan malam 3',
    'is_manual' => true,
]);

// Cek hasil
echo "Pesan 1: {$queue1->scheduled_at}\n";
echo "Pesan 2: {$queue2->scheduled_at}\n";
echo "Pesan 3: {$queue3->scheduled_at}\n";

// Expected:
// - Semua dijadwalkan untuk BESOK jam 06:00+
// - Interval antar pesan: 5-10 menit

// Reset waktu test
Carbon\Carbon::setTestNow();

// Cleanup
\App\Models\MessageQueue::whereIn('id', [$queue1->id, $queue2->id, $queue3->id])->delete();

exit
```

**Expected Output:**
```
Pesan 1: 2026-02-05 06:00:00
Pesan 2: 2026-02-05 06:07:00
Pesan 3: 2026-02-05 06:15:00
```

---

### Test 2: Pesan Masuk Dini Hari (00:01 - 05:59) + Ada Antrian Kemarin

**Tujuan**: Memastikan pesan melanjutkan antrian dari kemarin malam

```bash
php artisan tinker
```

```php
// Setup: Buat antrian "kemarin malam" yang dijadwalkan hari ini
$yesterdayNight1 = \App\Models\MessageQueue::create([
    'jenis_pesan' => 'whatsapp',
    'penerima' => '6281234567890',
    'isi_pesan' => 'Antrian kemarin malam 1',
    'status' => 'queued',
    'scheduled_at' => today()->setTime(6, 0, 0),
]);

$yesterdayNight2 = \App\Models\MessageQueue::create([
    'jenis_pesan' => 'whatsapp',
    'penerima' => '6281234567890',
    'isi_pesan' => 'Antrian kemarin malam 2',
    'status' => 'queued',
    'scheduled_at' => today()->setTime(6, 8, 0),
]);

echo "Setup antrian kemarin malam:\n";
echo "- Pesan 1: {$yesterdayNight1->scheduled_at}\n";
echo "- Pesan 2: {$yesterdayNight2->scheduled_at}\n\n";

// Set waktu test ke dini hari (02:00)
Carbon\Carbon::setTestNow(today()->setTime(2, 0, 0));

$queueService = new \App\Services\WhatsAppQueueService();

// Queue pesan baru di dini hari
$queue1 = $queueService->queueMessage([
    'penerima' => '6281234567890',
    'isi_pesan' => 'Pesan dini hari 1',
    'is_manual' => true,
]);

$queue2 = $queueService->queueMessage([
    'penerima' => '6281234567890',
    'isi_pesan' => 'Pesan dini hari 2',
    'is_manual' => true,
]);

// Cek hasil
echo "Pesan dini hari:\n";
echo "- Pesan 1: {$queue1->scheduled_at}\n";
echo "- Pesan 2: {$queue2->scheduled_at}\n";

// Expected:
// - Pesan dini hari dijadwalkan SETELAH antrian kemarin malam
// - Scheduled > 06:08:00

// Reset waktu test
Carbon\Carbon::setTestNow();

// Cleanup
\App\Models\MessageQueue::whereIn('id', [
    $yesterdayNight1->id,
    $yesterdayNight2->id,
    $queue1->id,
    $queue2->id
])->delete();

exit
```

**Expected Output:**
```
Setup antrian kemarin malam:
- Pesan 1: 2026-02-04 06:00:00
- Pesan 2: 2026-02-04 06:08:00

Pesan dini hari:
- Pesan 1: 2026-02-04 06:15:00  (atau lebih, setelah 06:08)
- Pesan 2: 2026-02-04 06:23:00  (atau lebih)
```

---

### Test 3: Pesan Masuk Jam Kerja (06:00 - 17:59)

**Tujuan**: Memastikan pesan langsung dijadwalkan dengan interval normal

```bash
php artisan tinker
```

```php
// Set waktu test ke jam kerja (10:00)
Carbon\Carbon::setTestNow(now()->setTime(10, 0, 0));

$queueService = new \App\Services\WhatsAppQueueService();

// Queue 3 pesan
$queue1 = $queueService->queueMessage([
    'penerima' => '6281234567890',
    'isi_pesan' => 'Test pesan jam kerja 1',
    'is_manual' => true,
]);

$queue2 = $queueService->queueMessage([
    'penerima' => '6281234567890',
    'isi_pesan' => 'Test pesan jam kerja 2',
    'is_manual' => true,
]);

$queue3 = $queueService->queueMessage([
    'penerima' => '6281234567890',
    'isi_pesan' => 'Test pesan jam kerja 3',
    'is_manual' => true,
]);

// Cek hasil
echo "Pesan 1: {$queue1->scheduled_at}\n";
echo "Pesan 2: {$queue2->scheduled_at}\n";
echo "Pesan 3: {$queue3->scheduled_at}\n";

// Expected:
// - Semua dijadwalkan untuk HARI INI
// - Interval antar pesan: 5-10 menit
// - Waktu >= 10:00

// Reset waktu test
Carbon\Carbon::setTestNow();

// Cleanup
\App\Models\MessageQueue::whereIn('id', [$queue1->id, $queue2->id, $queue3->id])->delete();

exit
```

**Expected Output:**
```
Pesan 1: 2026-02-04 10:00:00  (atau lebih)
Pesan 2: 2026-02-04 10:07:00  (atau lebih, +5-10 menit)
Pesan 3: 2026-02-04 10:15:00  (atau lebih, +5-10 menit)
```

---

### Test 4: Reschedule Overdue Messages

**Tujuan**: Memastikan pesan overdue di-reschedule dengan interval delay

```bash
php artisan tinker
```

```php
// Setup: Buat pesan overdue (dijadwalkan hari ini tapi belum terkirim)
$overdue1 = \App\Models\MessageQueue::create([
    'jenis_pesan' => 'whatsapp',
    'penerima' => '6281234567890',
    'isi_pesan' => 'Pesan overdue 1',
    'status' => 'queued',
    'scheduled_at' => today()->setTime(17, 0, 0),
]);

$overdue2 = \App\Models\MessageQueue::create([
    'jenis_pesan' => 'whatsapp',
    'penerima' => '6281234567890',
    'isi_pesan' => 'Pesan overdue 2',
    'status' => 'queued',
    'scheduled_at' => today()->setTime(17, 10, 0),
]);

$overdue3 = \App\Models\MessageQueue::create([
    'jenis_pesan' => 'whatsapp',
    'penerima' => '6281234567890',
    'isi_pesan' => 'Pesan overdue 3',
    'status' => 'queued',
    'scheduled_at' => today()->setTime(17, 20, 0),
]);

echo "Setup pesan overdue:\n";
echo "- Pesan 1: {$overdue1->scheduled_at}\n";
echo "- Pesan 2: {$overdue2->scheduled_at}\n";
echo "- Pesan 3: {$overdue3->scheduled_at}\n\n";

// Set waktu test ke setelah jam kerja (18:05)
Carbon\Carbon::setTestNow(today()->setTime(18, 5, 0));

$queueService = new \App\Services\WhatsAppQueueService();

// Jalankan reschedule
$count = $queueService->rescheduleOverdueMessages();

echo "Rescheduled: {$count} messages\n\n";

// Cek hasil
$messages = \App\Models\MessageQueue::whereIn('id', [
    $overdue1->id,
    $overdue2->id,
    $overdue3->id
])->get();

foreach ($messages as $msg) {
    echo "Pesan: {$msg->isi_pesan}\n";
    echo "  Old scheduled: {$msg->getOriginal('scheduled_at')}\n";
    echo "  New scheduled: {$msg->scheduled_at}\n";
    echo "  Notes: {$msg->notes}\n\n";
}

// Expected:
// - Count = 3
// - Semua dijadwalkan untuk BESOK
// - Interval antar pesan: 20-70 menit
// - Notes berisi "[Overdue - Re-scheduled dengan interval delay]"

// Reset waktu test
Carbon\Carbon::setTestNow();

// Cleanup
\App\Models\MessageQueue::whereIn('id', [
    $overdue1->id,
    $overdue2->id,
    $overdue3->id
])->delete();

exit
```

**Expected Output:**
```
Setup pesan overdue:
- Pesan 1: 2026-02-04 17:00:00
- Pesan 2: 2026-02-04 17:10:00
- Pesan 3: 2026-02-04 17:20:00

Rescheduled: 3 messages

Pesan: Pesan overdue 1
  Old scheduled: 2026-02-04 17:00:00
  New scheduled: 2026-02-05 06:00:00
  Notes: [Overdue - Re-scheduled dengan interval delay]

Pesan: Pesan overdue 2
  Old scheduled: 2026-02-04 17:10:00
  New scheduled: 2026-02-05 06:45:00  (interval 20-70 menit dari pesan 1)
  Notes: [Overdue - Re-scheduled dengan interval delay]

Pesan: Pesan overdue 3
  Old scheduled: 2026-02-04 17:20:00
  New scheduled: 2026-02-05 07:20:00  (interval 20-70 menit dari pesan 2)
  Notes: [Overdue - Re-scheduled dengan interval delay]
```

---

### Test 5: Process Queue (Simulasi)

**Tujuan**: Memastikan processQueue() berjalan dengan benar

```bash
php artisan tinker
```

```php
// Setup: Buat pesan yang siap dikirim
$ready1 = \App\Models\MessageQueue::create([
    'jenis_pesan' => 'whatsapp',
    'penerima' => '6281234567890',
    'isi_pesan' => 'Pesan siap kirim 1',
    'status' => 'queued',
    'scheduled_at' => now()->subMinutes(5), // 5 menit yang lalu
]);

$ready2 = \App\Models\MessageQueue::create([
    'jenis_pesan' => 'whatsapp',
    'penerima' => '6281234567890',
    'isi_pesan' => 'Pesan siap kirim 2',
    'status' => 'queued',
    'scheduled_at' => now()->subMinutes(3), // 3 menit yang lalu
]);

echo "Setup pesan siap kirim:\n";
echo "- Pesan 1: {$ready1->scheduled_at}\n";
echo "- Pesan 2: {$ready2->scheduled_at}\n\n";

// CATATAN: processQueue() akan gagal jika API WhatsApp tidak terkonfigurasi
// Ini hanya untuk test logika, bukan test actual sending

$queueService = new \App\Services\WhatsAppQueueService();

// Cek dalam jam kerja
$now = now();
echo "Current time: {$now}\n";
echo "Is within working hours: " . ($queueService->isWithinWorkingHours($now) ? 'Yes' : 'No') . "\n\n";

// Cleanup (jangan run processQueue() di test)
\App\Models\MessageQueue::whereIn('id', [$ready1->id, $ready2->id])->delete();

echo "Test completed. Pesan dihapus.\n";

exit
```

---

### Test 6: Cek Scheduler

**Tujuan**: Memastikan scheduler terdaftar dengan benar

```bash
php artisan schedule:list
```

**Expected Output:**
```
  0 6-17 * * *  whatsapp:process-queue ............ Next Due: 12 hours from now
  5 18 * * *    reschedule-overdue-whatsapp-evening  Next Due: 6 hours from now
  5 0 * * *     reschedule-overdue-whatsapp-midnight Next Due: 18 hours from now
```

---

## Production Testing

### Test di Production (Setelah Upload)

#### 1. Test Service Loaded
```bash
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

php artisan tinker
```

```php
// Test load service
$service = new \App\Services\WhatsAppQueueService();
echo "WhatsAppQueueService loaded successfully!\n";

// Cek konstanta
echo "Working hours: " . \App\Services\WhatsAppQueueService::WORKING_HOUR_START . ":00 - " . \App\Services\WhatsAppQueueService::WORKING_HOUR_END . ":00\n";
echo "Normal interval: " . \App\Services\WhatsAppQueueService::MIN_INTERVAL_MINUTES . "-" . \App\Services\WhatsAppQueueService::MAX_INTERVAL_MINUTES . " minutes\n";
echo "Delayed interval: " . \App\Services\WhatsAppQueueService::MIN_DELAYED_INTERVAL_MINUTES . "-" . \App\Services\WhatsAppQueueService::MAX_DELAYED_INTERVAL_MINUTES . " minutes\n";

exit
```

#### 2. Test Queue Message (Dry Run)
```bash
php artisan tinker
```

```php
$queueService = new \App\Services\WhatsAppQueueService();

// Queue test message
$queue = $queueService->queueMessage([
    'penerima' => '6281234567890',
    'isi_pesan' => '[TEST] Pesan test dari production - JANGAN KIRIM',
    'is_manual' => true,
]);

echo "Pesan di-queue:\n";
echo "- ID: {$queue->id}\n";
echo "- Scheduled: {$queue->scheduled_at}\n";
echo "- Status: {$queue->status}\n";

// HAPUS test message (JANGAN LUPA!)
$queue->delete();
echo "\nTest message deleted.\n";

exit
```

#### 3. Monitor Log
```bash
# Monitor real-time
tail -f storage/logs/whatsapp-queue.log

# Cek log hari ini
grep "$(date +%Y-%m-%d)" storage/logs/whatsapp-queue.log | tail -50
```

#### 4. Cek Antrian
```bash
php artisan tinker
```

```php
// Cek jumlah antrian
$queued = \App\Models\MessageQueue::where('status', 'queued')->count();
$sent = \App\Models\MessageQueue::where('status', 'sent')->whereDate('created_at', today())->count();
$failed = \App\Models\MessageQueue::where('status', 'failed')->whereDate('created_at', today())->count();

echo "Queued: {$queued}\n";
echo "Sent today: {$sent}\n";
echo "Failed today: {$failed}\n";

// Cek pesan overdue
$overdue = \App\Models\MessageQueue::where('status', 'queued')
    ->where('scheduled_at', '<', now()->subDay())
    ->count();

echo "Overdue: {$overdue}\n";

exit
```

---

## Troubleshooting

### Issue: Pesan tidak dijadwalkan dengan benar

**Solusi:**
1. Cek timezone server:
```bash
php -r "echo date_default_timezone_get();"
```

2. Cek konfigurasi timezone di `.env`:
```
APP_TIMEZONE=Asia/Jakarta
```

3. Clear config cache:
```bash
php artisan config:clear
```

### Issue: Scheduler tidak berjalan

**Solusi:**
1. Cek crontab:
```bash
crontab -l
```

2. Tambahkan jika belum ada:
```bash
* * * * * cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan schedule:run >> /dev/null 2>&1
```

3. Test manual:
```bash
php artisan schedule:run
```

### Issue: Pesan overdue tidak di-reschedule

**Solusi:**
1. Test manual reschedule:
```bash
php artisan tinker
```

```php
$queueService = new \App\Services\WhatsAppQueueService();
$count = $queueService->rescheduleOverdueMessages();
echo "Rescheduled: {$count} messages\n";
exit
```

2. Cek log:
```bash
grep "reschedule" storage/logs/whatsapp-queue.log
```

---

## Checklist Sebelum Production

- [ ] Semua test di local passed
- [ ] Backup database production
- [ ] Backup code production
- [ ] Upload file ke production
- [ ] Test service loaded di production
- [ ] Test queue message (dry run)
- [ ] Cek scheduler terdaftar
- [ ] Monitor log selama 1 jam
- [ ] Dokumentasi lengkap tersedia

---

**Created**: 4 Februari 2026
**Author**: System Administrator
