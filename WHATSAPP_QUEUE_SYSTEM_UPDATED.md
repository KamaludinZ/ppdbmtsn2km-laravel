# Sistem Antrian WhatsApp - Dokumentasi Lengkap

## Overview

Sistem antrian WhatsApp yang telah diperbarui dengan logika pengiriman berdasarkan waktu masuk pesan untuk menghindari spam dan mengoptimalkan pengiriman.

## Logika Antrian Berdasarkan Waktu

### 1. **Jam 18:00 - 00:00 (Sore hingga Tengah Malam)**
- **Status**: Di luar jam kerja
- **Perilaku**: Pesan yang masuk akan dijadwalkan untuk dikirim esok hari mulai jam **06:00**
- **Interval**: 5-10 menit (normal)
- **Contoh**:
  - Pesan masuk jam 19:30 → Dijadwalkan besok jam 06:00
  - Pesan masuk jam 23:45 → Dijadwalkan besok jam 06:05

### 2. **Jam 00:01 - 05:59 (Dini Hari)**
- **Status**: Di luar jam kerja (masih hari yang sama)
- **Perilaku**: Pesan akan masuk antrian hari ini jam **06:00**, tapi **SETELAH** antrian yang masuk dari jam 18:00-00:00 tadi malam
- **Interval**: 5-10 menit (normal)
- **Contoh**:
  - Pesan masuk tadi malam jam 19:00 → Dijadwalkan hari ini 06:00
  - Pesan masuk tadi malam jam 20:00 → Dijadwalkan hari ini 06:07
  - Pesan masuk dini hari jam 02:00 → Dijadwalkan hari ini 06:15 (setelah 2 pesan di atas)

### 3. **Jam 06:00 - 17:59 (Jam Kerja)**
- **Status**: Dalam jam kerja
- **Perilaku**: Pesan langsung dijadwalkan dengan interval acak
- **Interval**: 5-10 menit (normal)
- **Contoh**:
  - Pesan masuk jam 08:00 → Langsung dijadwalkan jam 08:07
  - Pesan masuk jam 15:00 → Langsung dijadwalkan jam 15:05

### 4. **Pesan Overdue (Belum Terkirim Hingga Jam Kerja Selesai)**
- **Status**: Pesan yang terjadwal hari ini tapi belum terkirim sampai jam 18:00
- **Perilaku**: Otomatis di-reschedule untuk besok dengan interval lebih lama
- **Interval**: 20-70 menit (delayed)
- **Waktu Reschedule**: Otomatis jam 18:05
- **Contoh**:
  - Pesan dijadwalkan jam 17:00 tapi belum terkirim
  - Jam 18:05 otomatis di-reschedule untuk besok jam 06:00 dengan interval 20-70 menit

## Konstanta Konfigurasi

```php
// Jam kerja
const WORKING_HOUR_START = 6; // 06:00
const WORKING_HOUR_END = 18; // 18:00

// Interval normal (5-10 menit)
const MIN_INTERVAL_MINUTES = 5;
const MAX_INTERVAL_MINUTES = 10;

// Interval untuk pesan overdue (20-70 menit)
const MIN_DELAYED_INTERVAL_MINUTES = 20;
const MAX_DELAYED_INTERVAL_MINUTES = 70;
```

## Flowchart Logika Antrian

```
                    ┌─────────────────┐
                    │   Pesan Masuk   │
                    └────────┬────────┘
                             │
                    ┌────────▼────────┐
                    │  Cek Jam Saat   │
                    │      Ini        │
                    └────────┬────────┘
                             │
              ┌──────────────┼──────────────┐
              │              │              │
      ┌───────▼───────┐  ┌──▼──────┐  ┌───▼────────┐
      │   18:00-00:00 │  │00:01-   │  │ 06:00-17:59│
      │               │  │ 05:59   │  │            │
      └───────┬───────┘  └──┬──────┘  └────┬───────┘
              │              │              │
      ┌───────▼───────┐  ┌──▼──────────────▼─────┐
      │   Jadwalkan   │  │ Cari Antrian Terakhir │
      │ Besok 06:00   │  │                        │
      │ + Interval    │  │                        │
      └───────┬───────┘  └──┬─────────────────────┘
              │              │
              │        ┌─────▼────────┐
              │        │ + Interval   │
              │        │   Acak       │
              │        │              │
              │        └─────┬────────┘
              │              │
              │        ┌─────▼────────┐
              │        │ Cek Melewati │
              │        │  Jam Kerja?  │
              │        └─────┬────────┘
              │              │
              │         ┌────┴────┐
              │         │         │
              │     ┌───▼───┐ ┌──▼────┐
              │     │  Ya   │ │ Tidak │
              │     └───┬───┘ └──┬────┘
              │         │         │
              └─────────┼─────────┘
                        │
                ┌───────▼────────┐
                │   Pesan Siap   │
                │   Dikirim      │
                └────────────────┘
```

## Schedule Tasks

### 1. Process Queue (Jam Kerja)
```bash
# Berjalan setiap menit dari jam 06:00 - 18:00
* 6-17 * * * cd /path/to/project && php artisan whatsapp:process-queue
```

### 2. Reschedule Overdue Messages
```bash
# Berjalan jam 18:05 (setelah jam kerja selesai)
5 18 * * * cd /path/to/project && php artisan schedule:run

# Berjalan jam 00:05 (tengah malam)
5 0 * * * cd /path/to/project && php artisan schedule:run
```

## Penggunaan

### Queue Pesan Baru
```php
use App\Services\WhatsAppQueueService;

$queueService = new WhatsAppQueueService();

$queueService->queueMessage([
    'pendaftar_id' => 123,
    'penerima' => '6281234567890',
    'isi_pesan' => 'Halo, ini adalah pesan WhatsApp',
    'template_name' => 'pengumuman',
    'priority' => 'normal', // low, normal, high
    'is_manual' => true, // true = manual, false = automatic
]);
```

### Manual Process Queue
```bash
php artisan whatsapp:process-queue
```

### Manual Reschedule Overdue
```php
use App\Services\WhatsAppQueueService;

$queueService = new WhatsAppQueueService();
$count = $queueService->rescheduleOverdueMessages();

echo "Rescheduled {$count} overdue messages";
```

## Contoh Skenario

### Skenario 1: Pesan Malam Hari
```
Waktu: 19:30 (Malam)
Pesan masuk: 5 pesan

Hasil:
- Pesan 1: Dijadwalkan besok 06:00
- Pesan 2: Dijadwalkan besok 06:07 (interval 7 menit)
- Pesan 3: Dijadwalkan besok 06:15 (interval 8 menit)
- Pesan 4: Dijadwalkan besok 06:21 (interval 6 menit)
- Pesan 5: Dijadwalkan besok 06:30 (interval 9 menit)
```

### Skenario 2: Pesan Dini Hari + Pesan Kemarin Malam
```
Kemarin malam (23:00):
- Pesan A: Dijadwalkan hari ini 06:00
- Pesan B: Dijadwalkan hari ini 06:08

Dini hari (03:00):
- Pesan C: Dijadwalkan hari ini 06:16 (setelah Pesan B)
- Pesan D: Dijadwalkan hari ini 06:23 (setelah Pesan C)
```

### Skenario 3: Pesan Overdue
```
Jam 16:00:
- Pesan X: Dijadwalkan jam 17:00
- Pesan Y: Dijadwalkan jam 17:10
- Pesan Z: Dijadwalkan jam 17:20

Jam 18:00 (belum terkirim):
- Server down atau API error

Jam 18:05 (Auto reschedule):
- Pesan X: Re-schedule besok 06:00 (interval 45 menit)
- Pesan Y: Re-schedule besok 06:45 (interval 55 menit)
- Pesan Z: Re-schedule besok 07:40 (interval 35 menit)
```

## Log Monitoring

Semua aktivitas antrian dicatat di:
```
storage/logs/whatsapp-queue.log
```

Format log:
```
[2026-02-04 19:30:00] WhatsApp message queued - Queue ID: 123, Scheduled: 2026-02-05 06:00:00
[2026-02-05 06:00:15] Message sent successfully - Queue ID: 123
[2026-02-05 18:05:00] Rescheduled 3 overdue messages with delayed interval
```

## Status Pesan

| Status | Deskripsi |
|--------|-----------|
| `queued` | Pesan dalam antrian, menunggu waktu pengiriman |
| `processing` | Pesan sedang diproses untuk dikirim |
| `sent` | Pesan berhasil dikirim |
| `failed` | Pesan gagal dikirim |
| `cancelled` | Pesan dibatalkan oleh admin |

## Database Schema

```sql
CREATE TABLE message_queue (
    id BIGINT PRIMARY KEY,
    pendaftar_id BIGINT,
    user_id BIGINT,
    jenis_pesan ENUM('email', 'whatsapp'),
    penerima VARCHAR(255),
    isi_pesan TEXT,
    template_name VARCHAR(255),
    status ENUM('queued', 'processing', 'sent', 'failed', 'cancelled'),
    priority ENUM('low', 'normal', 'high'),
    is_manual BOOLEAN,
    scheduled_at TIMESTAMP,
    processed_at TIMESTAMP,
    retry_count INT,
    max_retries INT,
    error_message TEXT,
    notes TEXT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

## API Response

### Success Response
```json
{
    "success": true,
    "data": {
        "message_id": "wamid.xxx",
        "status": "sent"
    }
}
```

### Error Response
```json
{
    "success": false,
    "error": "API request failed: Invalid phone number"
}
```

## Troubleshooting

### Pesan Tidak Terkirim
1. Cek log di `storage/logs/whatsapp-queue.log`
2. Pastikan cron job berjalan: `crontab -l`
3. Cek konfigurasi WhatsApp API di `.env`
4. Manual retry: `php artisan whatsapp:process-queue`

### Antrian Menumpuk
1. Cek jam kerja sistem: `date`
2. Cek status antrian: Query database `message_queue`
3. Manual reschedule: Jalankan `rescheduleOverdueMessages()`

### Interval Tidak Sesuai
1. Periksa konstanta di `WhatsAppQueueService.php`
2. Pastikan random interval menggunakan `rand()` dengan benar
3. Cek log untuk melihat interval yang dihitung

## Keamanan

- Semua pesan dienkripsi di database
- API key disimpan di environment variable
- Rate limiting diterapkan untuk mencegah spam
- Nomor telepon divalidasi sebelum dikirim
- Log error tidak menyimpan data sensitif

## Update & Maintenance

Untuk mengupdate sistem:

1. Backup database terlebih dahulu
2. Pull perubahan code dari repository
3. Jalankan migrations jika ada
4. Restart cron jobs
5. Test dengan pesan manual

---

**Versi**: 2.0
**Last Updated**: 4 Februari 2026
**Author**: System Administrator
