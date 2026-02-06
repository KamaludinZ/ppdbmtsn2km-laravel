# Sistem Antrian WhatsApp (WhatsApp Queue System)

## 📋 Daftar Isi

- [Overview](#overview)
- [Fitur Utama](#fitur-utama)
- [Cara Kerja Sistem](#cara-kerja-sistem)
- [Instalasi & Setup](#instalasi--setup)
- [Konfigurasi](#konfigurasi)
- [Penggunaan](#penggunaan)
- [Monitoring & Troubleshooting](#monitoring--troubleshooting)
- [API Reference](#api-reference)

---

## Overview

Sistem Antrian WhatsApp adalah fitur yang mengelola pengiriman pesan WhatsApp secara otomatis dengan mempertimbangkan:
- **Jam Kerja**: Pesan hanya dikirim antara jam 06:00 - 18:00
- **Interval Acak**: Setiap pesan memiliki jeda 5-10 menit untuk menghindari spam
- **Retry Mechanism**: Pesan yang gagal akan dicoba ulang hingga 3 kali
- **History Tracking**: Semua pesan terekam dalam history dengan status detail

## Fitur Utama

### 1. **Smart Scheduling**
- Pesan yang dikirim di luar jam kerja akan otomatis dijadwalkan untuk besok jam 06:00
- Setiap pesan memiliki interval random 5-10 menit dari pesan sebelumnya

### 2. **Queue Management**
- Status tracking: `queued`, `processing`, `sent`, `failed`, `cancelled`
- Priority levels: `low`, `normal`, `high`
- Membedakan pesan manual vs otomatis

### 3. **Error Handling**
- Automatic retry hingga 3 kali untuk pesan gagal
- Error logging lengkap dengan trace
- Notifikasi error di dashboard

### 4. **Admin Dashboard**
- Tab **History Email**: Melihat riwayat email terkirim
- Tab **History WhatsApp**: Melihat antrian dan riwayat WhatsApp
- Statistik real-time: Antrian, Terkirim, Gagal, Hari Ini
- Filter berdasarkan status, tanggal, pencarian
- Action buttons: Retry, Cancel

---

## Cara Kerja Sistem

### Flow Diagram

```
┌─────────────────────┐
│ Trigger Pengiriman  │
│ (Manual/Otomatis)   │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│ Queue Message       │
│ - Calculate         │
│   scheduled_at      │
│ - Apply interval    │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│ Cek Jam Kerja       │
│ 06:00 - 18:00?      │
└──────────┬──────────┘
           │
    ┌──────┴──────┐
    │             │
    ▼             ▼
   YA            TIDAK
    │             │
    │             ▼
    │      ┌─────────────┐
    │      │ Schedule    │
    │      │ Besok 06:00 │
    │      └──────┬──────┘
    │             │
    └──────┬──────┘
           │
           ▼
┌─────────────────────┐
│ Scheduler           │
│ (Every Minute)      │
│ 06:00 - 18:00       │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│ Process Queue       │
│ - Get ready msgs    │
│ - Send via API      │
│ - Update status     │
└──────────┬──────────┘
           │
    ┌──────┴──────┐
    │             │
    ▼             ▼
 SUCCESS       FAILED
    │             │
    │             ▼
    │      ┌─────────────┐
    │      │ Retry?      │
    │      │ < 3x        │
    │      └──────┬──────┘
    │             │
    └──────┬──────┘
           │
           ▼
┌─────────────────────┐
│ Log to History      │
│ (riwayat_pesan)     │
└─────────────────────┘
```

### Komponen Sistem

1. **Migration**: `2026_02_03_210000_create_message_queue_table.php`
   - Tabel `message_queue` untuk tracking antrian

2. **Model**: `MessageQueue.php`
   - Eloquent model dengan helper methods
   - Scopes untuk query filtering
   - Relationship dengan Pendaftar dan User

3. **Service**: `WhatsAppQueueService.php`
   - Business logic untuk queue management
   - Scheduling algorithm
   - WhatsApp API integration

4. **Command**: `ProcessWhatsAppQueue.php`
   - Artisan command untuk memproses antrian
   - Dipanggil oleh scheduler setiap menit

5. **Controller**: `PengaturanPesan.php`
   - Admin interface untuk melihat history
   - Actions: retry, cancel

6. **Views**:
   - `history-email-tab.blade.php`
   - `history-whatsapp-tab.blade.php`

---

## Instalasi & Setup

### 1. Run Migration

```bash
php artisan migrate
```

Migration akan membuat tabel `message_queue` dengan struktur:
- `id`, `pendaftar_id`, `user_id`
- `jenis_pesan`, `penerima`, `isi_pesan`, `template_name`
- `status`, `priority`, `is_manual`
- `scheduled_at`, `processed_at`
- `retry_count`, `max_retries`
- `error_message`, `error_trace`, `response_data`
- `metadata`, `notes`

### 2. Konfigurasi WhatsApp API

Edit file `.env`:

```env
WHATSAPP_API_URL=https://api.whatsapp.example.com/send
WHATSAPP_API_KEY=your_api_key_here
```

Atau tambahkan ke `config/services.php`:

```php
'whatsapp' => [
    'api_url' => env('WHATSAPP_API_URL'),
    'api_key' => env('WHATSAPP_API_KEY'),
],
```

### 3. Setup Scheduler

Scheduler sudah dikonfigurasi di `routes/console.php`:

```php
Schedule::command('whatsapp:process-queue')
    ->everyMinute()
    ->between('06:00', '18:00')
    ->withoutOverlapping()
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/whatsapp-queue.log'));
```

### 4. Setup Cron Job

Tambahkan ke crontab server:

```bash
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```

---

## Konfigurasi

### Mengubah Jam Kerja

Edit file `app/Services/WhatsAppQueueService.php`:

```php
const WORKING_HOUR_START = 6;  // Jam mulai (default: 06:00)
const WORKING_HOUR_END = 18;   // Jam selesai (default: 18:00)
```

### Mengubah Interval Pesan

Edit file `app/Services/WhatsAppQueueService.php`:

```php
const MIN_INTERVAL_MINUTES = 5;  // Interval minimum (default: 5 menit)
const MAX_INTERVAL_MINUTES = 10; // Interval maksimum (default: 10 menit)
```

### Mengubah Max Retry

Edit file `app/Models/MessageQueue.php`:

```php
protected $attributes = [
    'max_retries' => 3,  // Default: 3x retry
];
```

---

## Penggunaan

### 1. Menggunakan Service (Programmatic)

```php
use App\Services\WhatsAppQueueService;

$service = app(WhatsAppQueueService::class);

// Queue pesan manual
$service->queueMessage([
    'pendaftar_id' => $pendaftar->id,
    'penerima' => '628123456789',
    'isi_pesan' => 'Halo {nama}, pendaftaran Anda telah disetujui!',
    'template_name' => 'verifikasi_disetujui',
    'is_manual' => true,
    'priority' => 'high',
    'metadata' => json_encode(['nama' => $pendaftar->nama_lengkap]),
]);

// Queue pesan otomatis
$service->queueMessage([
    'pendaftar_id' => $pendaftar->id,
    'penerima' => $pendaftar->no_hp,
    'isi_pesan' => $messageBody,
    'is_manual' => false,
    'priority' => 'normal',
]);
```

### 2. Melalui Admin Dashboard

1. Login sebagai Admin/Panitia
2. Buka menu **Pengaturan Pesan**
3. Klik tab **History WhatsApp**
4. Di sini Anda dapat:
   - Melihat statistik antrian
   - Filter berdasarkan status/tanggal
   - Retry pesan yang gagal
   - Cancel pesan yang masih di antrian
   - Melihat error detail

### 3. Manual Command Execution

Untuk testing atau manual processing:

```bash
# Process queue sekali
php artisan whatsapp:process-queue

# Process dengan limit
php artisan whatsapp:process-queue --limit=10

# Force process (outside working hours)
php artisan whatsapp:process-queue --force
```

---

## Monitoring & Troubleshooting

### 1. Cek Status Antrian

Via Dashboard:
- Buka `/admin/pengaturan-pesan`
- Klik tab "History WhatsApp"
- Lihat statistik cards di atas

Via Command:
```bash
php artisan whatsapp:process-queue
# Output akan menampilkan statistik
```

### 2. Cek Log Files

```bash
# Log queue processing
tail -f storage/logs/whatsapp-queue.log

# Log Laravel general
tail -f storage/logs/laravel.log
```

### 3. Common Issues

#### Pesan Tidak Terkirim

**Penyebab**: Outside working hours
- **Solusi**: Tunggu hingga jam 06:00, atau gunakan `--force` flag

**Penyebab**: WhatsApp API error
- **Solusi**: Cek konfigurasi API di `.env`, cek log error

**Penyebab**: Nomor HP format salah
- **Solusi**: Service otomatis convert ke format 62xxx, tapi pastikan nomor valid

#### Pesan Stuck di Queue

**Penyebab**: Scheduler tidak running
- **Solusi**: Pastikan cron job sudah setup

**Penyebab**: Processing overlap
- **Solusi**: Hapus lock file `storage/framework/schedule-*`

#### Retry Tidak Bekerja

**Penyebab**: Max retry tercapai
- **Solusi**: Cek `retry_count`, manual retry via dashboard

**Penyebab**: Error permanent (bad phone number)
- **Solusi**: Perbaiki data, manual retry

### 4. Database Queries untuk Debug

```sql
-- Cek antrian aktif
SELECT * FROM message_queue WHERE status = 'queued' ORDER BY scheduled_at;

-- Cek pesan gagal
SELECT * FROM message_queue WHERE status = 'failed' ORDER BY created_at DESC;

-- Statistik per status
SELECT status, COUNT(*) as total FROM message_queue GROUP BY status;

-- Pesan hari ini
SELECT * FROM message_queue WHERE DATE(created_at) = CURRENT_DATE;
```

---

## API Reference

### WhatsAppQueueService Methods

#### `queueMessage(array $data): MessageQueue`

Queue a new WhatsApp message.

**Parameters**:
```php
[
    'pendaftar_id' => int|null,           // ID pendaftar
    'penerima' => string,                  // Phone number
    'isi_pesan' => string,                 // Message body
    'template_name' => string|null,        // Template identifier
    'is_manual' => bool,                   // Manual vs automatic
    'priority' => 'low'|'normal'|'high',  // Priority level
    'metadata' => string|null,             // JSON metadata
    'notes' => string|null,                // Additional notes
]
```

**Returns**: `MessageQueue` instance

---

#### `processQueue(): array`

Process pending messages in queue.

**Returns**:
```php
[
    'processed' => int,  // Total messages processed
    'sent' => int,       // Successfully sent
    'failed' => int,     // Failed to send
    'skipped' => int,    // Skipped (outside working hours)
]
```

---

#### `getStatistics(): array`

Get queue statistics.

**Returns**:
```php
[
    'total' => int,      // Total messages in queue
    'queued' => int,     // Pending messages
    'processing' => int, // Currently processing
    'sent' => int,       // Successfully sent
    'failed' => int,     // Failed messages
    'today' => int,      // Messages created today
    'manual' => int,     // Manual messages
    'automatic' => int,  // Automatic messages
]
```

---

#### `cancelMessage(int $queueId): bool`

Cancel a queued message.

**Returns**: `true` if cancelled, `false` if not found or already processed

---

#### `retryMessage(int $queueId): bool`

Retry a failed/cancelled message.

**Returns**: `true` if requeued, `false` if not eligible

---

### MessageQueue Model Methods

#### `markAsSent(?array $responseData = null): void`

Mark message as successfully sent.

---

#### `markAsFailed(string $errorMessage, ?string $errorTrace = null): void`

Mark message as failed with error details.

---

#### `canRetry(): bool`

Check if message can be retried (retry_count < max_retries).

---

#### `requeue(int $delayMinutes = 5): void`

Requeue message with delay.

---

#### `isScheduledForFuture(): bool`

Check if scheduled for future.

---

#### `isOverdue(): bool`

Check if scheduled time has passed.

---

## Testing Checklist

### Pre-Upload Checklist

- [ ] Migration file exists and runs successfully
- [ ] `.env` has WHATSAPP_API_URL and WHATSAPP_API_KEY configured
- [ ] Scheduler configured in `routes/console.php`
- [ ] Cron job setup on server
- [ ] Test WhatsApp API connection
- [ ] Log files writable (`storage/logs/whatsapp-queue.log`)

### Functional Testing

- [ ] Queue pesan manual dari admin panel
- [ ] Queue pesan otomatis dari sistem (e.g., verifikasi disetujui)
- [ ] Cek pesan dijadwalkan dengan benar (jam 6 jika outside working hours)
- [ ] Cek interval 5-10 menit antar pesan
- [ ] Test retry mechanism untuk pesan gagal
- [ ] Test cancel untuk pesan queued
- [ ] Cek history tab menampilkan data dengan benar
- [ ] Test filter dan search di history tab
- [ ] Cek pagination bekerja
- [ ] Test statistik cards update real-time

### Integration Testing

- [ ] Send test message via WhatsApp API
- [ ] Verify message received correctly
- [ ] Check phone number formatting (62xxx)
- [ ] Test with invalid phone number (should fail gracefully)
- [ ] Test with API timeout
- [ ] Test with API error response

### Performance Testing

- [ ] Queue 100+ messages, verify scheduler processes correctly
- [ ] Check no memory leaks during long-running processes
- [ ] Verify withoutOverlapping works (no duplicate processing)
- [ ] Check log file size management

---

## File Structure

```
app/
├── Console/
│   └── Commands/
│       └── ProcessWhatsAppQueue.php
├── Livewire/
│   └── Admin/
│       └── PengaturanPesan.php
├── Models/
│   ├── MessageQueue.php
│   └── RiwayatPesan.php
└── Services/
    └── WhatsAppQueueService.php

database/
└── migrations/
    └── 2026_02_03_210000_create_message_queue_table.php

resources/
└── views/
    └── livewire/
        └── admin/
            ├── pengaturan-pesan.blade.php
            └── partials/
                ├── history-email-tab.blade.php
                └── history-whatsapp-tab.blade.php

routes/
└── console.php (scheduler configuration)

storage/
└── logs/
    └── whatsapp-queue.log
```

---

## Support & Maintenance

### Backup Recommendations

1. **Database**: Backup `message_queue` dan `riwayat_pesan` tables secara berkala
2. **Logs**: Archive `whatsapp-queue.log` setiap bulan
3. **Configuration**: Backup `.env` file

### Monitoring Recommendations

1. Setup alert untuk failed messages > 10 per hari
2. Monitor queue size (should not grow indefinitely)
3. Check scheduler is running (cron job status)
4. Monitor API response time

### Maintenance Tasks

- **Daily**: Check failed messages, retry if needed
- **Weekly**: Review queue statistics, check for anomalies
- **Monthly**: Archive old logs, clean up old queue entries (>90 days)

---

## Credits

Developed for PPDB MTsN 2 Kota Malang
Version: 1.0.0
Date: 3 Februari 2026

---

**Catatan Penting**:
- Pastikan WhatsApp API sudah dikonfigurasi dengan benar
- Jangan lupa setup cron job di server production
- Monitor log files secara berkala
- Backup database sebelum deploy ke production
