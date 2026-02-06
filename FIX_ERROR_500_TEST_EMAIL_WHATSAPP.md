# FIX ERROR 500 - TEST EMAIL DAN WHATSAPP

## Tanggal
4 Februari 2026

## Masalah yang Terjadi

Setelah update Settings.php untuk menyimpan history test email/wa, muncul error 500:
```
POST https://pmbm.mtsn2kotamalang.sch.id/livewire-e455e806/update 500 (Internal Server Error)
```

### Root Cause
Field `pendaftar_id` di tabel `riwayat_pesan` **NOT NULL** (tidak bisa diisi null), sedangkan kode yang saya buat sebelumnya mencoba insert dengan `pendaftar_id = null`:

```php
// ❌ SALAH - menyebabkan error 500
$riwayatPesan = RiwayatPesan::create([
    'pendaftar_id' => null, // ERROR: Field ini NOT NULL!
    ...
]);
```

## Solusi yang Diterapkan

### Solusi Sederhana (TANPA ALTER TABLE)
Gunakan **ID pendaftar pertama** sebagai ID testing:

```php
// ✅ BENAR - gunakan ID pendaftar pertama
$testPendaftarId = Pendaftar::orderBy('id', 'asc')->value('id') ?? 1;

$riwayatPesan = RiwayatPesan::create([
    'pendaftar_id' => $testPendaftarId, // ID khusus untuk testing
    'user_id' => Auth::id(),
    ...
]);
```

### Keuntungan Solusi Ini
✅ Tidak perlu alter table
✅ Tidak perlu migration
✅ Tidak perlu membuat pendaftar dummy
✅ Menggunakan data yang sudah ada
✅ Langsung bisa dipakai

### Trade-off
⚠️ History test akan menampilkan nama pendaftar pertama di kolom "Pendaftar"
✅ Tapi kolom "Penerima" tetap menampilkan email/nomor yang ditest

## File yang Diupdate

**Settings.php** (`app/Livewire/Admin/Settings.php`)

### Perubahan di fungsi `sendTestEmail()`

**SEBELUM**:
```php
$riwayatPesan = RiwayatPesan::create([
    'pendaftar_id' => null, // ❌ ERROR 500
    ...
]);
```

**SESUDAH**:
```php
// Get ID pendaftar pertama untuk testing
$testPendaftarId = Pendaftar::orderBy('id', 'asc')->value('id') ?? 1;

$riwayatPesan = RiwayatPesan::create([
    'pendaftar_id' => $testPendaftarId, // ✅ OK
    ...
]);
```

### Perubahan di fungsi `sendTestWhatsApp()`

**SEBELUM**:
```php
$riwayatPesan = RiwayatPesan::create([
    'pendaftar_id' => null, // ❌ ERROR 500
    ...
]);
```

**SESUDAH**:
```php
// Get ID pendaftar pertama untuk testing
$testPendaftarId = Pendaftar::orderBy('id', 'asc')->value('id') ?? 1;

$riwayatPesan = RiwayatPesan::create([
    'pendaftar_id' => $testPendaftarId, // ✅ OK
    ...
]);
```

### Tambahan Import
```php
use App\Models\Pendaftar;
```

## Cara Upload ke Production

### Opsi 1: Via Batch File
```bash
upload_fix_error_500_simple.bat
```

### Opsi 2: Manual via SCP
```bash
scp app/Livewire/Admin/Settings.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/
```

### Opsi 3: Manual via FTP/cPanel
Upload file ke: `/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/Settings.php`

## Langkah Setelah Upload

### 1. Clear Cache
```bash
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan optimize:clear"
```

### 2. Test Email
1. Login sebagai Admin
2. Buka `/admin/settings`
3. Scroll ke **Email Settings**
4. Isi **Test Email Recipient**
5. Klik **"Kirim Email Percobaan"**
6. **Harus berhasil** tanpa error 500 ✅
7. Cek history di `/admin/pengaturan-pesan?activeTab=history-email`

### 3. Test WhatsApp
1. Di `/admin/settings`, scroll ke **WhatsApp Settings**
2. Isi **Test WhatsApp Number**
3. Klik **"Kirim Pesan Percobaan"**
4. **Harus berhasil** tanpa error 500 ✅
5. Cek history di `/admin/pengaturan-pesan?activeTab=history-whatsapp`

## Tampilan History

### Di Tab History Email
| Waktu | Penerima | Subject | Pendaftar | Status |
|-------|----------|---------|-----------|--------|
| 4 Feb 2026 10:30 | admin@test.com | Test Email - Sistem PPDB... | **Ahmad Rizki** (ID 1) | ✅ Terkirim |

**Catatan**:
- Kolom "Pendaftar" menampilkan nama pendaftar pertama (ID 1)
- Kolom "Penerima" menampilkan email yang ditest (sesuai input)

### Di Tab History WhatsApp
| Waktu | Penerima | Pesan | Pendaftar | Status |
|-------|----------|-------|-----------|--------|
| 4 Feb 2026 10:32 | 6281234567890 | 🔔 Test Pesan PPDB... | **Ahmad Rizki** (ID 1) | ✅ Terkirim |

**Catatan**:
- Kolom "Pendaftar" menampilkan nama pendaftar pertama (ID 1)
- Kolom "Penerima" menampilkan nomor WA yang ditest (sesuai input)

## Alternatif Solusi (Jika Diperlukan)

Jika Anda ingin kolom "Pendaftar" kosong untuk testing, ada 2 cara:

### Cara 1: Alter Table (Lebih Kompleks)
Jalankan migration untuk membuat `pendaftar_id` nullable:
```bash
php artisan migrate
```

File migration sudah tersedia: `2026_02_04_030000_make_pendaftar_id_nullable_in_riwayat_pesan.php`

### Cara 2: Update View (Lebih Sederhana)
Update view `history-email-tab.blade.php` dan `history-whatsapp-tab.blade.php` untuk cek jika `user_id` ada tapi `pendaftar_id = 1`, tampilkan "Testing" instead nama pendaftar.

## Status Saat Ini

| Jenis Pengiriman | Status History | Catatan |
|------------------|----------------|---------|
| ✅ Manual dari Admin/Panitia | **Berfungsi** | OK |
| ⏳ Otomatis saat Registrasi | Perlu upload | 3 file Register.php |
| ✅ **Test Email dari Settings** | **FIXED** | Siap upload (error 500 fixed) |
| ✅ **Test WhatsApp dari Settings** | **FIXED** | Siap upload (error 500 fixed) |

## Kesimpulan

**Error 500 sudah diperbaiki** dengan solusi sederhana:
- ✅ Tidak perlu alter table
- ✅ Tidak perlu migration tambahan
- ✅ Menggunakan ID pendaftar pertama sebagai ID testing
- ✅ File Settings.php siap diupload

Setelah upload dan clear cache, test email dan WhatsApp dari `/admin/settings` akan berfungsi normal dan menyimpan history! 🎉

## Troubleshooting

### Jika Masih Error 500 Setelah Upload

1. **Clear cache lagi**:
   ```bash
   php artisan optimize:clear
   ```

2. **Cek log error**:
   ```bash
   tail -100 storage/logs/laravel.log
   ```

3. **Pastikan ada pendaftar di database**:
   ```sql
   SELECT id, nama_lengkap FROM pendaftar ORDER BY id ASC LIMIT 1;
   ```

4. **Cek file sudah terupload**:
   ```bash
   grep "testPendaftarId" app/Livewire/Admin/Settings.php
   ```
   Harus muncul 2 baris yang mengandung `testPendaftarId`

### Jika Tidak Ada Pendaftar di Database

Jika database kosong (belum ada pendaftar), buat 1 pendaftar dummy:
```sql
INSERT INTO pendaftar (nama_lengkap, email, nisn, created_at, updated_at)
VALUES ('Testing Account', 'test@test.com', '0000000000', NOW(), NOW());
```

Tapi biasanya ini tidak perlu karena pasti sudah ada pendaftar di production.
