# FIX HISTORY EMAIL DAN WHATSAPP - COMPLETE

## Tanggal
4 Februari 2026

## Masalah yang Ditemukan
History email dan WhatsApp tidak muncul di halaman `/admin/pengaturan-pesan?activeTab=history-email` dan `/admin/pengaturan-pesan?activeTab=history-whatsapp` karena **pengiriman otomatis saat registrasi tidak menyimpan history ke tabel `riwayat_pesan`**.

### Analisis
1. **Pengiriman Manual** (dari Admin/Panitia) ✅ Sudah menyimpan history
   - `Admin/Pendaftar.php`
   - `Admin/TesPsikologi.php`
   - `Admin/Kelulusan.php`
   - `Admin/DaftarUlang.php`

2. **Pengiriman Otomatis** (saat registrasi) ❌ TIDAK menyimpan history
   - `Auth/Register.php`
   - `Auth/RegisterFixed.php`
   - `Auth/RegisterOptimized.php`

## Solusi yang Diterapkan

### 1. Update File Register.php
**File**: `app/Livewire/Auth/Register.php`

**Perubahan**:
- Menambahkan `use App\Models\RiwayatPesan;`
- Di fungsi `sendRegistrationNotification()`:
  - Membuat record `RiwayatPesan` untuk email dengan status 'pending'
  - Setelah kirim email, update status menjadi 'terkirim' atau 'gagal'
  - Membuat record `RiwayatPesan` untuk WhatsApp dengan status 'pending'
  - Setelah kirim WA, update status menjadi 'terkirim' atau 'gagal'

**Kode yang ditambahkan**:
```php
// Create log record for email
$emailRiwayat = RiwayatPesan::create([
    'pendaftar_id' => $pendaftar->id,
    'user_id' => null, // Otomatis dari sistem
    'jenis_pesan' => 'email',
    'judul' => $emailSubject,
    'isi_pesan' => $emailBody,
    'penerima' => $pendaftar->email,
    'status' => 'pending',
]);

// Send Email
$emailService = new EmailService();
$emailResult = $emailService->sendEmail($pendaftar->email, $emailSubject, $emailBody);

// Update email log status
if ($emailResult['success'] ?? false) {
    $emailRiwayat->update([
        'status' => 'terkirim',
        'sent_at' => now(),
    ]);
} else {
    $emailRiwayat->update([
        'status' => 'gagal',
        'error_message' => $emailResult['message'] ?? 'Unknown error',
    ]);
}
```

### 2. Update File RegisterFixed.php
**File**: `app/Livewire/Auth/RegisterFixed.php`

**Perubahan**: Sama seperti Register.php

### 3. Update File RegisterOptimized.php
**File**: `app/Livewire/Auth/RegisterOptimized.php`

**Perubahan**: Sama seperti Register.php

## File yang Diupdate
1. ✅ `app/Livewire/Auth/Register.php`
2. ✅ `app/Livewire/Auth/RegisterFixed.php`
3. ✅ `app/Livewire/Auth/RegisterOptimized.php`

## File Migration
- ✅ `database/migrations/2026_01_23_102510_create_riwayat_pesan_table.php` (sudah ada)

## Struktur Tabel riwayat_pesan
```sql
CREATE TABLE riwayat_pesan (
    id BIGSERIAL PRIMARY KEY,
    pendaftar_id BIGINT NOT NULL REFERENCES pendaftar(id) ON DELETE CASCADE,
    user_id BIGINT NULL REFERENCES users(id) ON DELETE SET NULL,
    jenis_pesan VARCHAR(255) NOT NULL CHECK (jenis_pesan IN ('email', 'whatsapp')),
    judul VARCHAR(255) NULL,
    isi_pesan TEXT NOT NULL,
    penerima VARCHAR(255) NOT NULL,
    status VARCHAR(255) NOT NULL DEFAULT 'pending' CHECK (status IN ('pending', 'terkirim', 'gagal')),
    error_message TEXT NULL,
    sent_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT NOW(),
    updated_at TIMESTAMP DEFAULT NOW()
);
```

## Cara Upload ke Production

### Langkah 1: Upload File
Jalankan batch file:
```bash
upload_fix_history_email_whatsapp.bat
```

### Langkah 2: Verifikasi Tabel di Production
```bash
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan migrate:status"
```

### Langkah 3: Jalankan Migration (jika tabel belum ada)
```bash
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan migrate"
```

### Langkah 4: Clear Cache
```bash
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan optimize:clear"
```

## Testing

### 1. Test Registrasi Baru
1. Buka halaman registrasi di production
2. Daftar sebagai pendaftar baru
3. Cek email dan WhatsApp terkirim

### 2. Test History Muncul
1. Login sebagai Admin
2. Buka `/admin/pengaturan-pesan?activeTab=history-email`
3. **Harus muncul** history email yang dikirim saat registrasi
4. Buka `/admin/pengaturan-pesan?activeTab=history-whatsapp`
5. **Harus muncul** history WhatsApp yang dikirim saat registrasi

### 3. Test Filter
1. Di tab history-email, coba filter berdasarkan:
   - Pencarian nama/email
   - Status (terkirim/gagal/pending)
   - Tanggal
2. Di tab history-whatsapp, coba filter yang sama

## Lokasi History di Dashboard

### Tab History Email
**URL**: `/admin/pengaturan-pesan?activeTab=history-email`

**Menampilkan**:
- Total Pesan
- Pending
- Terkirim
- Gagal
- Hari Ini
- Tabel history dengan kolom:
  - Waktu
  - Penerima (email)
  - Subject
  - Pendaftar
  - Status
  - Kendala

### Tab History WhatsApp
**URL**: `/admin/pengaturan-pesan?activeTab=history-whatsapp`

**Menampilkan**:
- Antrian Pesan (untuk message queue system)
- History Terkirim dengan kolom:
  - Waktu
  - Penerima (nomor HP)
  - Pesan
  - Pendaftar
  - Status
  - Kendala

## Kesimpulan
Sekarang **SEMUA pengiriman email dan WhatsApp** (baik otomatis maupun manual) akan tersimpan di tabel `riwayat_pesan` dan bisa dilihat historynya di dashboard admin.

### Otomatis dari Sistem
- Saat registrasi berhasil → Email & WA otomatis → Tersimpan di history ✅

### Manual dari Admin/Panitia
- Kirim pesan dari menu Data Pendaftar → Tersimpan di history ✅
- Kirim pesan dari menu Tes Psikologi → Tersimpan di history ✅
- Kirim pesan dari menu Kelulusan → Tersimpan di history ✅
- Kirim pesan dari menu Daftar Ulang → Tersimpan di history ✅

## Troubleshooting

### Jika History Tidak Muncul
1. **Cek tabel ada atau tidak**:
   ```sql
   \d riwayat_pesan
   ```

2. **Cek data di tabel**:
   ```sql
   SELECT * FROM riwayat_pesan ORDER BY created_at DESC LIMIT 10;
   ```

3. **Cek migration status**:
   ```bash
   php artisan migrate:status
   ```

4. **Clear cache**:
   ```bash
   php artisan optimize:clear
   ```

### Jika Error "Table doesn't exist"
Jalankan migration:
```bash
php artisan migrate
```

## Catatan Penting
- Field `user_id` di tabel `riwayat_pesan` bisa NULL untuk pesan otomatis dari sistem
- Status: `pending`, `terkirim`, `gagal`
- Jenis pesan: `email`, `whatsapp`
- Filter sudah ada di view untuk memudahkan pencarian history
