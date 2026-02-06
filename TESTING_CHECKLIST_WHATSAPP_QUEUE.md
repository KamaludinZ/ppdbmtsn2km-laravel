# Testing Checklist - WhatsApp Queue System

## 📋 Informasi Testing
- **Tanggal Testing**: _____________
- **Tester**: _____________
- **Environment**: ☐ Local  ☐ Staging  ☐ Production
- **Laravel Version**: 12.47.0
- **PHP Version**: 8.2.12

---

## ✅ Pre-Deployment Checklist

### 1. File Upload Verification
- [ ] Migration file uploaded: `2026_02_03_210000_create_message_queue_table.php`
- [ ] Model uploaded: `MessageQueue.php`
- [ ] Service uploaded: `WhatsAppQueueService.php`
- [ ] Command uploaded: `ProcessWhatsAppQueue.php`
- [ ] Controller uploaded: `PengaturanPesan.php`
- [ ] Views uploaded:
  - [ ] `pengaturan-pesan.blade.php`
  - [ ] `history-email-tab.blade.php`
  - [ ] `history-whatsapp-tab.blade.php`
- [ ] Scheduler config uploaded: `routes/console.php`

### 2. Database Migration
```bash
php artisan migrate
```
- [ ] Migration berhasil dijalankan tanpa error
- [ ] Tabel `message_queue` terbuat dengan kolom lengkap
- [ ] Indexes terbuat dengan benar

**Verify dengan**:
```sql
DESCRIBE message_queue;
SHOW INDEXES FROM message_queue;
```

### 3. Configuration Setup
- [ ] `.env` file sudah dikonfigurasi:
  ```env
  WHATSAPP_API_URL=___________________________
  WHATSAPP_API_KEY=___________________________
  ```
- [ ] Config cache cleared:
  ```bash
  php artisan config:clear
  php artisan cache:clear
  php artisan view:clear
  php artisan optimize:clear
  ```

### 4. Scheduler Setup
- [ ] Cron job sudah setup di server
  ```bash
  crontab -l
  ```
  Harus ada:
  ```
  * * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
  ```
- [ ] Scheduler list menampilkan whatsapp queue:
  ```bash
  php artisan schedule:list
  ```

### 5. Permissions
- [ ] Storage logs writable:
  ```bash
  chmod -R 775 storage/logs
  touch storage/logs/whatsapp-queue.log
  ```
- [ ] Cache writable:
  ```bash
  chmod -R 775 storage/framework/cache
  chmod -R 775 storage/framework/sessions
  ```

---

## 🧪 Functional Testing

### Test 1: Queue Message - Manual Send (Dalam Jam Kerja)

**Time**: Test antara jam 06:00 - 18:00

**Steps**:
1. Login sebagai Admin
2. Buka `/admin/pengaturan-pesan`
3. Klik tab "History WhatsApp"
4. (Gunakan code untuk queue manual message)

**Expected Result**:
- [ ] Pesan masuk ke queue dengan status `queued`
- [ ] `scheduled_at` = sekarang + interval random 5-10 menit
- [ ] `is_manual` = true
- [ ] Muncul di tabel "Antrian Pesan"

**Verification**:
```sql
SELECT * FROM message_queue ORDER BY id DESC LIMIT 1;
```

---

### Test 2: Queue Message - Manual Send (Luar Jam Kerja)

**Time**: Test sebelum jam 06:00 atau setelah jam 18:00

**Steps**:
1. Queue message seperti Test 1
2. Catat waktu saat ini

**Expected Result**:
- [ ] Pesan masuk ke queue dengan status `queued`
- [ ] `scheduled_at` = besok jam 06:00
- [ ] Terdapat icon "🕒 Terjadwal" di kolom Jadwal Kirim

**Verification**:
```sql
SELECT scheduled_at FROM message_queue ORDER BY id DESC LIMIT 1;
-- scheduled_at harus besok jam 06:00
```

---

### Test 3: Automatic Queue Processing

**Time**: Dalam jam kerja (06:00 - 18:00)

**Steps**:
1. Queue 3 pesan dengan interval 1 menit
2. Tunggu hingga `scheduled_at` lewat
3. Manual run command:
   ```bash
   php artisan whatsapp:process-queue
   ```

**Expected Result**:
- [ ] Command menampilkan tabel hasil:
  ```
  Processed: 3
  Sent: 3 (atau 0 jika API belum dikonfigurasi)
  Failed: 0
  ```
- [ ] Status pesan berubah menjadi `sent` atau `failed`
- [ ] `processed_at` terisi
- [ ] Log di `storage/logs/whatsapp-queue.log` mencatat proses

**Verification**:
```bash
tail -f storage/logs/whatsapp-queue.log
```

---

### Test 4: Interval Random 5-10 Menit

**Steps**:
1. Queue 10 pesan secara berurutan (dalam jam kerja)
2. Cek `scheduled_at` setiap pesan

**Expected Result**:
- [ ] Pesan pertama: `scheduled_at` = sekarang
- [ ] Pesan kedua: `scheduled_at` = pesan pertama + 5-10 menit
- [ ] Pesan ketiga: `scheduled_at` = pesan kedua + 5-10 menit
- [ ] Dan seterusnya...

**Verification**:
```sql
SELECT
    id,
    scheduled_at,
    TIMESTAMPDIFF(MINUTE, LAG(scheduled_at) OVER (ORDER BY id), scheduled_at) as interval_minutes
FROM message_queue
WHERE status = 'queued'
ORDER BY id;
-- interval_minutes harus antara 5-10
```

---

### Test 5: Retry Mechanism

**Steps**:
1. Queue pesan dengan nomor HP invalid (misal: "123")
2. Run queue processor
3. Pesan akan gagal
4. Klik tombol "Retry" di dashboard

**Expected Result**:
- [ ] Setelah gagal, status = `failed`
- [ ] `error_message` terisi
- [ ] `retry_count` = 1
- [ ] Setelah klik Retry:
  - [ ] Status kembali ke `queued`
  - [ ] `scheduled_at` diupdate (sekarang + 15 menit)
  - [ ] `error_message` = null

**Verification**:
```sql
SELECT status, retry_count, error_message, scheduled_at
FROM message_queue
WHERE id = [pesan_yang_gagal];
```

---

### Test 6: Max Retry Limit

**Steps**:
1. Buat pesan yang pasti gagal (nomor HP invalid)
2. Manual retry 3x menggunakan `retryMessage()`

**Expected Result**:
- [ ] Retry ke-1: `retry_count` = 1, status = `queued`
- [ ] Retry ke-2: `retry_count` = 2, status = `queued`
- [ ] Retry ke-3: `retry_count` = 3, status = `queued`
- [ ] Retry ke-4: Method `canRetry()` return false, tidak bisa retry lagi
- [ ] Tombol "Retry" disabled di UI

**Verification**:
```sql
SELECT retry_count, max_retries FROM message_queue WHERE id = [id];
```

---

### Test 7: Cancel Queued Message

**Steps**:
1. Queue pesan (status = `queued`)
2. Klik tombol "Cancel" di dashboard
3. Konfirmasi cancel

**Expected Result**:
- [ ] Alert konfirmasi muncul
- [ ] Setelah konfirmasi, status berubah menjadi `cancelled`
- [ ] Pesan tidak lagi muncul di tabel "Antrian Pesan"
- [ ] Muncul flash message "Pesan berhasil dibatalkan!"

**Verification**:
```sql
SELECT status FROM message_queue WHERE id = [id];
-- status harus 'cancelled'
```

---

### Test 8: History Email Tab

**Steps**:
1. Buka `/admin/pengaturan-pesan`
2. Klik tab "History Email"
3. Test filter:
   - [ ] Filter by search (nama, email, subject)
   - [ ] Filter by status (terkirim, gagal, pending)
   - [ ] Filter by date range
   - [ ] Click "Clear Filters"

**Expected Result**:
- [ ] Tabel menampilkan history email dari `riwayat_pesan`
- [ ] Kolom tampil: Waktu, Penerima, Subject, Pendaftar, Status, Kendala
- [ ] Filter bekerja dengan benar
- [ ] Pagination bekerja
- [ ] Kolom "Kendala" menampilkan `error_message` jika ada

---

### Test 9: History WhatsApp Tab - Statistics

**Steps**:
1. Buka tab "History WhatsApp"
2. Cek 4 cards statistik di atas

**Expected Result**:
- [ ] Card "Antrian" = jumlah pesan dengan status `queued`
- [ ] Card "Terkirim" = jumlah pesan dengan status `sent`
- [ ] Card "Gagal" = jumlah pesan dengan status `failed`
- [ ] Card "Hari Ini" = jumlah pesan dibuat hari ini

**Verification**:
```sql
SELECT
    COUNT(CASE WHEN status = 'queued' THEN 1 END) as queued,
    COUNT(CASE WHEN status = 'sent' THEN 1 END) as sent,
    COUNT(CASE WHEN status = 'failed' THEN 1 END) as failed,
    COUNT(CASE WHEN DATE(created_at) = CURRENT_DATE THEN 1 END) as today
FROM message_queue
WHERE jenis_pesan = 'whatsapp';
```

---

### Test 10: History WhatsApp Tab - Queue Table

**Steps**:
1. Scroll ke section "Antrian Pesan"
2. Cek tabel queue

**Expected Result**:
- [ ] Kolom tampil: Jadwal Kirim, Penerima, Pesan, Tipe, Status, Kendala, Aksi
- [ ] Tipe badge:
  - [ ] "Manual" dengan warna purple untuk `is_manual = true`
  - [ ] "Otomatis" dengan warna blue untuk `is_manual = false`
- [ ] Status badge dengan warna sesuai:
  - [ ] Antrian = yellow
  - [ ] Memproses = blue
  - [ ] Terkirim = green
  - [ ] Gagal = red
  - [ ] Dibatalkan = gray
- [ ] Icon jadwal:
  - [ ] "🕒 Terjadwal" jika `scheduled_at` > now
  - [ ] "⏰ Terlambat" jika `scheduled_at` < now dan status masih queued
- [ ] Tombol Aksi:
  - [ ] "Retry" button muncul untuk status `failed` atau `cancelled`
  - [ ] "Cancel" button muncul untuk status `queued`
- [ ] Retry count tampil jika > 0: "Retry: 2/3"

---

### Test 11: History WhatsApp Tab - History Table

**Steps**:
1. Scroll ke section "History Terkirim"
2. Cek tabel history

**Expected Result**:
- [ ] Menampilkan data dari `riwayat_pesan` dengan `jenis_pesan = 'whatsapp'`
- [ ] Kolom tampil: Waktu, Penerima, Pesan, Pendaftar, Status, Kendala
- [ ] Jika ada `sent_at`, gunakan itu, jika tidak ada gunakan `created_at`
- [ ] Pesan di-truncate 50 karakter dengan tooltip full text
- [ ] Info pendaftar lengkap: nama + nomor pendaftaran

---

### Test 12: Filter Functionality

**Steps**:
1. Di tab "History WhatsApp", test semua filter:
   - [ ] Search: ketik nomor HP partial
   - [ ] Status: pilih "Gagal"
   - [ ] Dari Tanggal: pilih tanggal kemarin
   - [ ] Sampai Tanggal: pilih hari ini
   - [ ] Click "Clear Filters"

**Expected Result**:
- [ ] Setiap filter langsung update hasil (wire:model.live)
- [ ] Multiple filters bisa dikombinasikan
- [ ] "Clear Filters" mereset semua filter
- [ ] Pagination reset ke page 1 saat filter berubah

---

### Test 13: Scheduler Automatic Run

**Steps**:
1. Queue 2 pesan dengan `scheduled_at` 2 menit dari sekarang (dalam jam kerja)
2. Tunggu 5 menit
3. Cek database dan log

**Expected Result**:
- [ ] Setelah 2 menit, scheduler otomatis run command
- [ ] Pesan terproses otomatis tanpa manual intervention
- [ ] Log `storage/logs/whatsapp-queue.log` tercatat
- [ ] Status berubah menjadi `sent` atau `failed`

**Verification**:
```bash
tail -20 storage/logs/whatsapp-queue.log
```

---

## 🔌 Integration Testing

### Test 14: WhatsApp API Integration

**Prerequisites**:
- WhatsApp API sudah dikonfigurasi di `.env`
- API endpoint valid

**Steps**:
1. Queue pesan dengan nomor HP valid
2. Run processor

**Expected Result**:
- [ ] HTTP request terkirim ke API endpoint
- [ ] Headers:
  - [ ] `Authorization: Bearer [API_KEY]`
  - [ ] `Content-Type: application/json`
- [ ] Body:
  ```json
  {
    "phone": "628123456789",
    "message": "...",
    "metadata": {}
  }
  ```
- [ ] Response 200 = status `sent`, `response_data` terisi
- [ ] Response error = status `failed`, `error_message` terisi

**Verification**:
```bash
# Cek Laravel log
tail -50 storage/logs/laravel.log | grep WhatsApp

# Cek response data di database
SELECT response_data FROM message_queue WHERE status = 'sent' LIMIT 1;
```

---

### Test 15: Phone Number Formatting

**Steps**:
Test berbagai format nomor HP:
1. "08123456789" → harus jadi "628123456789"
2. "+628123456789" → harus jadi "628123456789"
3. "8123456789" → harus jadi "628123456789"
4. "628123456789" → tetap "628123456789"

**Expected Result**:
- [ ] Semua format di-normalize ke "62xxx"
- [ ] Karakter non-numeric dihapus (spaces, dashes, +)

**Verification**:
```sql
SELECT penerima FROM message_queue ORDER BY id DESC LIMIT 4;
-- Semua harus format 62xxx
```

---

### Test 16: Error Handling - API Timeout

**Steps**:
1. Simulasi API timeout (set timeout sangat pendek atau matikan API server)
2. Queue dan process pesan

**Expected Result**:
- [ ] Catch timeout exception
- [ ] Status = `failed`
- [ ] `error_message` = "Connection timeout" atau similar
- [ ] Automatic requeue untuk retry

---

### Test 17: Error Handling - Invalid API Key

**Steps**:
1. Set WHATSAPP_API_KEY ke value invalid
2. Queue dan process pesan

**Expected Result**:
- [ ] Response 401 Unauthorized
- [ ] Status = `failed`
- [ ] `error_message` menjelaskan authentication error
- [ ] Tidak auto-retry (karena credentials issue)

---

## 🚀 Performance Testing

### Test 18: Bulk Queue Processing

**Steps**:
1. Queue 100 pesan sekaligus
2. Monitor processing

**Expected Result**:
- [ ] Semua 100 pesan masuk queue
- [ ] Scheduler process max 50 per run (limit di code)
- [ ] Tidak ada memory leak
- [ ] Processing selesai dalam waktu wajar
- [ ] Tidak ada duplikat processing (withoutOverlapping works)

**Verification**:
```bash
# Monitor memory usage
php artisan whatsapp:process-queue --limit=100

# Check queue statistics
php artisan tinker
>>> app(App\Services\WhatsAppQueueService::class)->getStatistics()
```

---

### Test 19: Concurrent Scheduler Runs

**Steps**:
1. Coba run 2 command bersamaan:
   ```bash
   # Terminal 1
   php artisan whatsapp:process-queue

   # Terminal 2 (bersamaan)
   php artisan whatsapp:process-queue
   ```

**Expected Result**:
- [ ] Salah satu command exit dengan message "Overlapping"
- [ ] Tidak ada pesan yang diproses 2x
- [ ] Lock file terbuat di `storage/framework/schedule-*`

---

### Test 20: Log File Size Management

**Steps**:
1. Process banyak pesan (100+)
2. Check log file size

**Expected Result**:
- [ ] Log file size reasonable (<10MB untuk 100 messages)
- [ ] Log rotation berjalan (jika dikonfigurasi)
- [ ] Tidak menghabiskan disk space

**Verification**:
```bash
ls -lh storage/logs/whatsapp-queue.log
```

---

## 🔒 Security Testing

### Test 21: Authorization

**Steps**:
1. Logout
2. Coba akses `/admin/pengaturan-pesan` tanpa login
3. Login sebagai Pendaftar (bukan Admin)
4. Coba akses `/admin/pengaturan-pesan`

**Expected Result**:
- [ ] Redirect ke login jika belum login
- [ ] Access denied jika role bukan Admin/Panitia
- [ ] Tab History hanya visible untuk Admin/Panitia

---

### Test 22: SQL Injection Protection

**Steps**:
1. Coba search dengan input: `'; DROP TABLE message_queue; --`
2. Coba filter dengan malicious input

**Expected Result**:
- [ ] Input di-escape dengan benar
- [ ] Tidak ada SQL execution
- [ ] Query tetap aman (menggunakan parameter binding)

---

### Test 23: XSS Protection

**Steps**:
1. Queue pesan dengan content: `<script>alert('XSS')</script>`
2. View di history tab

**Expected Result**:
- [ ] Script tidak tereksekusi
- [ ] Content di-escape: `&lt;script&gt;...`
- [ ] Blade {{ }} otomatis escape HTML

---

## 📊 UI/UX Testing

### Test 24: Responsive Design

**Steps**:
1. Buka History tabs di berbagai ukuran layar:
   - [ ] Desktop (1920x1080)
   - [ ] Tablet (768x1024)
   - [ ] Mobile (375x667)

**Expected Result**:
- [ ] Tabel responsive, bisa scroll horizontal di mobile
- [ ] Cards statistik stack vertical di mobile
- [ ] Filter form responsive
- [ ] Buttons tidak overlap
- [ ] Text readable di semua ukuran

---

### Test 25: Loading States

**Steps**:
1. Test actions dengan slow network:
   - [ ] Click Retry button
   - [ ] Click Cancel button
   - [ ] Change filter

**Expected Result**:
- [ ] Loading indicator muncul (wire:loading)
- [ ] Button disabled saat loading
- [ ] Clear feedback setelah action complete
- [ ] No double-click issues

---

### Test 26: Empty States

**Steps**:
1. Buat scenario:
   - [ ] Tidak ada pesan di queue
   - [ ] Tidak ada history WhatsApp
   - [ ] Filter tidak match apapun

**Expected Result**:
- [ ] Empty state message muncul
- [ ] Icon placeholder tampil
- [ ] Message informatif: "Tidak ada pesan dalam antrian"

---

### Test 27: Error Messages

**Steps**:
1. Test berbagai error scenario:
   - [ ] Cancel pesan yang sudah sent
   - [ ] Retry pesan yang sudah max retry
   - [ ] Network error saat action

**Expected Result**:
- [ ] Error message jelas dan informatif
- [ ] Flash message dengan warna merah
- [ ] User tahu apa yang salah
- [ ] Saran action selanjutnya (jika applicable)

---

## 🐛 Regression Testing

### Test 28: Existing Email Features

**Steps**:
1. Test semua fitur email yang sudah ada:
   - [ ] Template Email tab masih berfungsi
   - [ ] Save template email
   - [ ] Send test email

**Expected Result**:
- [ ] Tidak ada breaking changes
- [ ] Email features tetap work seperti sebelumnya
- [ ] History Email tab menampilkan data lama

---

### Test 29: Existing WhatsApp Templates

**Steps**:
1. Buka tab "Template WhatsApp"
2. Edit dan save template

**Expected Result**:
- [ ] Tab masih accessible
- [ ] Templates bisa diedit
- [ ] Save berhasil
- [ ] Tidak conflict dengan History tab

---

### Test 30: Backward Compatibility

**Steps**:
1. Cek data lama di `riwayat_pesan` table
2. Verify masih tampil di History tabs

**Expected Result**:
- [ ] Data lama masih visible
- [ ] Format compatible
- [ ] Tidak ada data corruption

---

## 📝 Documentation Testing

### Test 31: Documentation Accuracy

**Steps**:
1. Follow dokumentasi `WHATSAPP_QUEUE_SYSTEM.md`
2. Test setiap code example
3. Verify API reference

**Expected Result**:
- [ ] Semua code examples executable
- [ ] Parameter descriptions akurat
- [ ] Return values sesuai documented
- [ ] No outdated information

---

### Test 32: Inline Comments

**Steps**:
1. Review code comments di:
   - [ ] WhatsAppQueueService.php
   - [ ] MessageQueue.php
   - [ ] ProcessWhatsAppQueue.php

**Expected Result**:
- [ ] Comments jelas dan helpful
- [ ] No misleading comments
- [ ] Complex logic dijelaskan
- [ ] TODO items addressed

---

## ✅ Final Checklist

### Pre-Production Sign-off

- [ ] All tests passed (minimum 90% pass rate)
- [ ] Critical bugs fixed
- [ ] Performance acceptable
- [ ] Security vulnerabilities addressed
- [ ] Documentation complete and accurate
- [ ] Backup created
- [ ] Rollback plan documented
- [ ] Team trained on new features
- [ ] Monitoring setup (alerts, logs)
- [ ] Go-live communication sent

### Production Deployment

- [ ] Migration executed successfully
- [ ] Cache cleared
- [ ] Scheduler verified running
- [ ] API credentials configured
- [ ] Permissions set correctly
- [ ] Log files created and writable
- [ ] Smoke test passed
- [ ] Monitoring active

---

## 🔍 Post-Deployment Monitoring (First 24 Hours)

- [ ] Check queue statistics every hour
- [ ] Monitor failed messages count
- [ ] Review error logs
- [ ] Verify scheduler running
- [ ] Check API success rate
- [ ] Monitor response times
- [ ] User feedback collected

---

## 📞 Support Contacts

- **Developer**: __________________
- **DevOps**: __________________
- **PIC Application**: __________________

---

## 🎯 Test Results Summary

| Test Category | Total Tests | Passed | Failed | N/A |
|---------------|-------------|--------|--------|-----|
| Pre-Deployment | 5 | ___ | ___ | ___ |
| Functional | 13 | ___ | ___ | ___ |
| Integration | 4 | ___ | ___ | ___ |
| Performance | 3 | ___ | ___ | ___ |
| Security | 3 | ___ | ___ | ___ |
| UI/UX | 4 | ___ | ___ | ___ |
| Regression | 3 | ___ | ___ | ___ |
| Documentation | 2 | ___ | ___ | ___ |
| **TOTAL** | **32** | ___ | ___ | ___ |

**Pass Rate**: ____%

**Overall Status**: ☐ READY FOR PRODUCTION  ☐ NEEDS FIXES  ☐ BLOCKED

**Sign-off**:
- Tester: ______________ Date: __________
- Developer: ______________ Date: __________
- Project Manager: ______________ Date: __________

---

**Notes**:
- Prioritas: Test 1-13 (Functional) harus 100% pass
- Security tests (21-23) critical, must pass
- Performance acceptable jika response time < 5s untuk 100 messages
- Documentation bisa update post-deployment jika needed
