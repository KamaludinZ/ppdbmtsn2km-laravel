# Update Logika Sistem Antrian WhatsApp - 4 Februari 2026

## Summary

Sistem antrian WhatsApp telah diperbarui dengan logika yang lebih spesifik berdasarkan waktu masuk pesan untuk mengoptimalkan pengiriman dan menghindari spam.

## Perubahan Utama

### 1. **Logika Antrian Berdasarkan Waktu**

#### **Sebelumnya:**
- Pesan di luar jam kerja (sebelum 06:00 atau setelah 18:00) → Dijadwalkan besok jam 06:00
- Interval acak: 5-10 menit untuk semua pesan

#### **Sekarang:**
- **18:00 - 00:00**: Pesan dijadwalkan untuk esok hari jam 06:00
- **00:01 - 05:59**: Pesan dijadwalkan hari ini jam 06:00, tapi **SETELAH** antrian dari jam 18:00-00:00 kemarin malam
- **06:00 - 17:59**: Pesan langsung dijadwalkan dengan interval 5-10 menit
- **Pesan Overdue**: Pesan yang belum terkirim hingga jam kerja selesai akan di-reschedule dengan interval 20-70 menit

### 2. **Konstanta Baru**

```php
// Interval untuk pesan overdue
const MIN_DELAYED_INTERVAL_MINUTES = 20;
const MAX_DELAYED_INTERVAL_MINUTES = 70;
```

### 3. **Method Baru**

#### `getEarlyMorningSchedule(Carbon $from)`
- Menangani penjadwalan pesan yang masuk di dini hari (00:01 - 05:59)
- Memastikan pesan melanjutkan antrian dari jam 18:00-00:00 kemarin malam
- Mencari pesan terakhir yang dijadwalkan untuk hari ini
- Menambahkan interval acak 5-10 menit dari pesan terakhir

#### `rescheduleOverdueMessages()`
- Menangani pesan yang belum terkirim hingga jam kerja selesai
- Otomatis di-reschedule untuk besok dengan interval delay 20-70 menit
- Menambahkan catatan "[Overdue - Re-scheduled dengan interval delay]"
- Return count berapa banyak pesan yang di-reschedule

### 4. **Update Method Existing**

#### `calculateScheduledTime(bool $isManual)`
- Menambahkan logika berdasarkan 3 rentang waktu:
  - `$hour >= 18`: Jam 18:00-23:59
  - `$hour < 6`: Jam 00:00-05:59
  - Selainnya: Jam kerja 06:00-17:59
- Memanggil method yang tepat sesuai rentang waktu

#### `getNextAvailableSlot(Carbon $from, bool $useDelayedInterval)`
- Menambahkan parameter `$useDelayedInterval`
- Jika `true`, gunakan interval 20-70 menit (untuk overdue)
- Jika `false`, gunakan interval 5-10 menit (normal)

#### `processQueue()`
- Menambahkan panggilan ke `rescheduleOverdueMessages()` saat di luar jam kerja
- Menambahkan counter `rescheduled` di results

### 5. **Scheduler Baru**

Ditambahkan 2 schedule tasks baru di `routes/console.php`:

```php
// Reschedule overdue jam 18:05 (setelah jam kerja)
Schedule::call(function () {
    $queueService = app(\App\Services\WhatsAppQueueService::class);
    $count = $queueService->rescheduleOverdueMessages();
})
    ->dailyAt('18:05')
    ->name('reschedule-overdue-whatsapp-evening');

// Reschedule overdue jam 00:05 (tengah malam)
Schedule::call(function () {
    $queueService = app(\App\Services\WhatsAppQueueService::class);
    $count = $queueService->rescheduleOverdueMessages();
})
    ->dailyAt('00:05')
    ->name('reschedule-overdue-whatsapp-midnight');
```

## File yang Diubah

1. **app/Services/WhatsAppQueueService.php**
   - Tambah konstanta `MIN_DELAYED_INTERVAL_MINUTES` dan `MAX_DELAYED_INTERVAL_MINUTES`
   - Update method `calculateScheduledTime()`
   - Update method `getNextAvailableSlot()` dengan parameter `$useDelayedInterval`
   - Update method `getNextWorkingDayStart()`
   - Tambah method baru `getEarlyMorningSchedule()`
   - Tambah method baru `rescheduleOverdueMessages()`
   - Update method `processQueue()` untuk memanggil reschedule

2. **routes/console.php**
   - Tambah 2 schedule tasks untuk reschedule overdue messages
   - Schedule jam 18:05 (setelah jam kerja)
   - Schedule jam 00:05 (tengah malam)

3. **WHATSAPP_QUEUE_SYSTEM_UPDATED.md** (Baru)
   - Dokumentasi lengkap sistem antrian WhatsApp
   - Flowchart logika
   - Contoh skenario
   - Troubleshooting guide

## Testing Scenarios

### Skenario 1: Pesan Masuk Jam 19:30 (Malam)
**Input:**
- 3 pesan masuk jam 19:30

**Expected Output:**
- Pesan 1: Dijadwalkan besok 06:00
- Pesan 2: Dijadwalkan besok 06:07 (random 5-10 menit)
- Pesan 3: Dijadwalkan besok 06:14 (random 5-10 menit)

**Cara Test:**
```php
// Di Tinker atau Test
$queueService = new WhatsAppQueueService();

for ($i = 1; $i <= 3; $i++) {
    $queue = $queueService->queueMessage([
        'penerima' => '6281234567890',
        'isi_pesan' => "Test message {$i}",
        'is_manual' => true,
    ]);

    echo "Pesan {$i}: {$queue->scheduled_at}\n";
}
```

### Skenario 2: Pesan Masuk Dini Hari (02:00) + Ada Antrian Kemarin Malam
**Setup:**
- Kemarin jam 23:00, ada 2 pesan yang dijadwalkan untuk hari ini jam 06:00 dan 06:08
- Sekarang jam 02:00, masuk 2 pesan baru

**Expected Output:**
- Pesan baru 1: Dijadwalkan hari ini 06:15 (setelah antrian kemarin malam)
- Pesan baru 2: Dijadwalkan hari ini 06:23

**Cara Test:**
```php
// Setup antrian kemarin malam
MessageQueue::create([
    'jenis_pesan' => 'whatsapp',
    'penerima' => '6281234567890',
    'isi_pesan' => 'Pesan kemarin malam 1',
    'status' => 'queued',
    'scheduled_at' => today()->setTime(6, 0, 0),
]);

MessageQueue::create([
    'jenis_pesan' => 'whatsapp',
    'penerima' => '6281234567890',
    'isi_pesan' => 'Pesan kemarin malam 2',
    'status' => 'queued',
    'scheduled_at' => today()->setTime(6, 8, 0),
]);

// Test pesan dini hari
Carbon::setTestNow(today()->setTime(2, 0, 0));

$queueService = new WhatsAppQueueService();
$queue1 = $queueService->queueMessage([
    'penerima' => '6281234567890',
    'isi_pesan' => 'Pesan dini hari 1',
]);

$queue2 = $queueService->queueMessage([
    'penerima' => '6281234567890',
    'isi_pesan' => 'Pesan dini hari 2',
]);

echo "Pesan dini hari 1: {$queue1->scheduled_at}\n";
echo "Pesan dini hari 2: {$queue2->scheduled_at}\n";

Carbon::setTestNow(); // Reset
```

### Skenario 3: Pesan Overdue
**Setup:**
- Jam 17:00, ada 3 pesan yang dijadwalkan untuk jam 17:00, 17:10, 17:20
- Pesan belum terkirim (status masih 'queued')
- Jam 18:05, scheduler reschedule berjalan

**Expected Output:**
- 3 pesan di-reschedule untuk besok jam 06:00 dengan interval 20-70 menit

**Cara Test:**
```php
// Setup pesan overdue
MessageQueue::create([
    'jenis_pesan' => 'whatsapp',
    'penerima' => '6281234567890',
    'isi_pesan' => 'Pesan overdue 1',
    'status' => 'queued',
    'scheduled_at' => today()->setTime(17, 0, 0),
]);

MessageQueue::create([
    'jenis_pesan' => 'whatsapp',
    'penerima' => '6281234567890',
    'isi_pesan' => 'Pesan overdue 2',
    'status' => 'queued',
    'scheduled_at' => today()->setTime(17, 10, 0),
]);

// Test reschedule
Carbon::setTestNow(today()->setTime(18, 5, 0));

$queueService = new WhatsAppQueueService();
$count = $queueService->rescheduleOverdueMessages();

echo "Rescheduled: {$count} messages\n";

// Cek hasil
$messages = MessageQueue::where('status', 'queued')
    ->where('notes', 'like', '%Overdue%')
    ->get();

foreach ($messages as $msg) {
    echo "Pesan: {$msg->isi_pesan} - Scheduled: {$msg->scheduled_at}\n";
}

Carbon::setTestNow(); // Reset
```

## Migration & Deployment

### Step 1: Backup
```bash
# Backup database
php artisan backup:run --only-db

# Backup code
git add .
git commit -m "Backup before WhatsApp queue logic update"
```

### Step 2: Update Code
```bash
# Upload file yang sudah diubah
scp app/Services/WhatsAppQueueService.php user@server:/path/to/project/app/Services/
scp routes/console.php user@server:/path/to/project/routes/
```

### Step 3: Test di Server
```bash
# SSH ke server
ssh user@server

# Test manual reschedule
cd /path/to/project
php artisan tinker

# Di Tinker:
$queueService = new \App\Services\WhatsAppQueueService();
$count = $queueService->rescheduleOverdueMessages();
echo "Rescheduled: {$count}";
exit
```

### Step 4: Restart Services
```bash
# Restart queue worker (jika ada)
php artisan queue:restart

# Clear cache
php artisan cache:clear
php artisan config:clear

# Pastikan scheduler berjalan
php artisan schedule:list
```

### Step 5: Monitor
```bash
# Monitor log
tail -f storage/logs/whatsapp-queue.log

# Cek antrian
php artisan tinker
MessageQueue::where('status', 'queued')->count();
```

## Rollback Plan

Jika terjadi masalah, rollback dengan:

```bash
# 1. Restore code dari backup
git checkout <commit-hash-before-update>

# 2. Restart services
php artisan cache:clear
php artisan config:clear

# 3. Manual reschedule pesan yang terdampak
php artisan tinker

# Di Tinker:
MessageQueue::where('status', 'queued')
    ->where('notes', 'like', '%Overdue%')
    ->update(['notes' => null, 'scheduled_at' => now()]);
```

## Monitoring & Alerts

### Cek Kesehatan Sistem
```bash
# Cek jumlah antrian
php artisan tinker
MessageQueue::where('status', 'queued')->count();

# Cek pesan overdue
MessageQueue::where('status', 'queued')
    ->where('scheduled_at', '<', now()->subDay())
    ->count();

# Cek pesan failed
MessageQueue::where('status', 'failed')
    ->whereDate('created_at', today())
    ->count();
```

### Alert Jika:
- Antrian > 1000 pesan
- Pesan overdue > 100
- Pesan failed hari ini > 50

## FAQ

**Q: Bagaimana jika ada pesan yang masuk jam 23:59?**
A: Pesan akan dijadwalkan untuk besok jam 06:00 dengan interval normal 5-10 menit.

**Q: Bagaimana jika ada pesan yang masuk jam 00:01?**
A: Pesan akan dijadwalkan untuk hari ini jam 06:00, tapi setelah semua antrian dari jam 18:00-00:00 kemarin malam.

**Q: Apa bedanya interval normal vs delayed?**
A:
- Normal: 5-10 menit (untuk pesan yang masuk di jam normal)
- Delayed: 20-70 menit (untuk pesan overdue yang belum terkirim hingga jam kerja selesai)

**Q: Bagaimana cara manual reschedule pesan?**
A: Jalankan di Tinker:
```php
$queueService = new \App\Services\WhatsAppQueueService();
$count = $queueService->rescheduleOverdueMessages();
```

**Q: Bagaimana cara membatalkan pesan yang sudah di-queue?**
A: Gunakan method `cancelMessage()`:
```php
$queueService = new \App\Services\WhatsAppQueueService();
$queueService->cancelMessage($queueId);
```

## Next Steps

1. ✅ Update logika antrian
2. ✅ Tambah method reschedule overdue
3. ✅ Tambah scheduler untuk auto reschedule
4. ✅ Buat dokumentasi lengkap
5. ⬜ Test di production
6. ⬜ Monitor selama 1 minggu
7. ⬜ Optimasi jika diperlukan

---

**Updated By**: System Administrator
**Date**: 4 Februari 2026
**Version**: 2.0
