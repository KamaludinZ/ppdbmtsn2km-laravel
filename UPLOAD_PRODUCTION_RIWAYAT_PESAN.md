# 📦 PANDUAN UPLOAD KE PRODUCTION - FITUR RIWAYAT PESAN

**Tanggal**: 23 Januari 2026
**Fitur**:
1. Riwayat Pesan + Template Pesan Auto-fill
2. Update Template Dropdown (7 Kategori, 24 Template)

**Status**: ✅ READY TO UPLOAD

---

## 📋 RINGKASAN FITUR

### Yang Diperbaiki:
1. ✅ **Riwayat Pesan** - Log otomatis semua pesan email/WhatsApp
2. ✅ **Template Placeholder** - Auto-replace {nisn}, {tempat_lahir}, dll
3. ✅ **Auto-load Template** - Template terisi otomatis saat dipilih
4. ✅ **Kolom Status Pesan** - Badge terkirim/gagal/pending
5. ✅ **Support Admin & Panitia** - Fitur berfungsi di kedua role
6. ✅ **Template Dropdown Update** - 7 kategori dengan 24 template (dari 8 template)

---

## 📁 DAFTAR FILE YANG PERLU DIUPLOAD

### 🆕 FILE BARU (2 files)

#### 1. Migration File
```
database/migrations/2026_01_23_102510_create_riwayat_pesan_table.php
```
**Fungsi**: Membuat tabel `riwayat_pesan` di database

#### 2. Model RiwayatPesan
```
app/Models/RiwayatPesan.php
```
**Fungsi**: Model untuk handle riwayat pesan dengan relationships & helper methods

---

### 📝 FILE YANG DIMODIFIKASI (11 files)

#### 3. Model Pendaftar
```
app/Models/Pendaftar.php
```
**Perubahan**:
- Ditambah relasi `riwayatPesan()` (line ~428)

#### 4. Admin Pendaftar Component
```
app/Livewire/Admin/Pendaftar.php
```
**Perubahan**:
- Import `RiwayatPesan` model (line ~7)
- Update `fillMessageTemplate()` dengan 27 placeholder (line 351-380)
- Tambah `updatedSelectedMessageTemplate()` lifecycle hook (line 332-335)
- Tambah `updatedMessageType()` lifecycle hook (line 338-344)
- Update `sendMessage()` dengan logging (line 386-459)

#### 5. Admin Pendaftar View
```
resources/views/livewire/admin/pendaftar.blade.php
```
**Perubahan**:
- Update kolom "Riwayat Pesan" pakai `RiwayatPesan` model (line ~824-874)
- Ganti `MessageHistory` → `RiwayatPesan`
- Ganti field `message_type` → `jenis_pesan`
- Tambah status badge display
- ⭐ **UPDATE TEMPLATE DROPDOWN** - 7 kategori dengan 24 template (line 1037-1090)

#### 6. Admin DaftarUlang Component
```
app/Livewire/Admin/DaftarUlang.php
```
**Perubahan**:
- Import `RiwayatPesan` model
- Tambah lifecycle hooks (`updatedSelectedMessageTemplate`, `updatedMessageType`)
- Update `loadMessageTemplate()` dengan 27 placeholder
- Update `sendMessage()` dengan RiwayatPesan logging

#### 7. Admin DaftarUlang View
```
resources/views/livewire/admin/daftar-ulang.blade.php
```
**Perubahan**:
- Update kolom "Riwayat Pesan" pakai `RiwayatPesan` model (line ~1301-1345)
- Ganti `MessageHistory` → `RiwayatPesan`
- Tambah status badge display

#### 8. Admin Kelulusan Component
```
app/Livewire/Admin/Kelulusan.php
```
**Perubahan**:
- Import `RiwayatPesan` model & `Auth` facade
- Tambah lifecycle hooks (`updatedSelectedMessageTemplate`, `updatedMessageType`)
- Update `loadMessageTemplate()` dengan 27 placeholder
- Update `sendMessage()` dengan RiwayatPesan logging

#### 9. Admin TesPsikologi Component
```
app/Livewire/Admin/TesPsikologi.php
```
**Perubahan**:
- Import `RiwayatPesan` model
- Update `sendMessage()` dengan RiwayatPesan logging (notification type)

#### 10. Panitia DaftarUlang Component
```
app/Livewire/Panitia/DaftarUlang.php
```
**Perubahan**:
- Import `RiwayatPesan` model
- Update `sendMessage()` dengan RiwayatPesan logging

#### 11. Panitia TesPsikologi Component
```
app/Livewire/Panitia/TesPsikologi.php
```
**Perubahan**:
- Import `RiwayatPesan` model
- Update `sendMessage()` dengan RiwayatPesan logging

#### 12. Panitia Daftar Ulang View
```
resources/views/livewire/panitia/daftar-ulang.blade.php
```
**Perubahan**:
- Update kolom "Riwayat Pesan" pakai `RiwayatPesan` model (line ~330-371)
- Sama seperti admin view

#### 13. Panitia Tes Psikologi View
```
resources/views/livewire/panitia/tes-psikologi.blade.php
```
**Perubahan**:
- Update kolom "Riwayat Pesan" pakai `RiwayatPesan` model (line ~107-149)
- Sama seperti admin view

---

## 🚀 LANGKAH-LANGKAH UPLOAD

### PERSIAPAN

#### 1. Backup Database Production (WAJIB!)
```bash
# Via SSH
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

# Backup database
php artisan db:backup
# atau via phpMyAdmin: Export → SQL
```

#### 2. Test di Development
- ✅ Test kirim email - cek riwayat muncul
- ✅ Test kirim WhatsApp - cek riwayat muncul
- ✅ Test pilih template - auto terisi
- ✅ Test placeholder - semua ter-replace
- ✅ Test di Admin & Panitia (semua halaman)

---

### UPLOAD VIA FTP/SFTP

#### 3. Upload File Baru (2 files)

**Via FileZilla / WinSCP:**
```
LOCAL                                          →  REMOTE
-----------------------------------------------------------------------
database/migrations/                           →  /home/mtsnkot4/pmbm.../database/migrations/
  2026_01_23_102510_create_riwayat_pesan_table.php

app/Models/                                    →  /home/mtsnkot4/pmbm.../app/Models/
  RiwayatPesan.php
```

#### 4. Upload File yang Dimodifikasi (11 files)

```
LOCAL                                          →  REMOTE
-----------------------------------------------------------------------
app/Models/                                    →  /home/mtsnkot4/pmbm.../app/Models/
  Pendaftar.php

app/Livewire/Admin/                            →  /home/mtsnkot4/pmbm.../app/Livewire/Admin/
  Pendaftar.php
  DaftarUlang.php
  Kelulusan.php
  TesPsikologi.php

app/Livewire/Panitia/                          →  /home/mtsnkot4/pmbm.../app/Livewire/Panitia/
  DaftarUlang.php
  TesPsikologi.php

resources/views/livewire/admin/                →  /home/mtsnkot4/pmbm.../resources/views/livewire/admin/
  pendaftar.blade.php
  daftar-ulang.blade.php

resources/views/livewire/panitia/              →  /home/mtsnkot4/pmbm.../resources/views/livewire/panitia/
  daftar-ulang.blade.php
  tes-psikologi.blade.php
```

---

### EKSEKUSI DI SERVER

#### 5. SSH ke Server
```bash
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
```

#### 6. Jalankan Migration
```bash
php artisan migrate
```

**Output yang diharapkan:**
```
Migrating: 2026_01_23_102510_create_riwayat_pesan_table
Migrated:  2026_01_23_102510_create_riwayat_pesan_table (XX.XXms)
```

#### 7. Clear All Cache
```bash
php artisan optimize:clear
```

**atau manual:**
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

#### 8. Set Permissions (jika perlu)
```bash
chmod -R 755 app/
chmod -R 755 database/
chmod -R 755 resources/
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/
```

---

## 🧪 TESTING DI PRODUCTION

### Test 1: Verifikasi Migration
```bash
# Via SSH
php artisan tinker

# Cek tabel ada
>>> \Schema::hasTable('riwayat_pesan')
=> true

# Cek struktur
>>> \DB::select('DESCRIBE riwayat_pesan')

# Exit
>>> exit
```

### Test 2: Test Kirim Email

1. **Login sebagai Admin**
2. **Buka**: `https://pmbm.mtsn2kotamalang.sch.id/admin/pendaftar`
3. **Klik "Kirim Pesan"** ke pendaftar
4. **Pilih template** → otomatis terisi ✅
5. **Kirim email**
6. **Cek kolom "Riwayat Pesan"** → muncul ✅

### Test 3: Test di Panitia

1. **Login sebagai Panitia**
2. **Buka**: `https://pmbm.mtsn2kotamalang.sch.id/panitia/daftar-ulang`
3. **Cek kolom "Riwayat Pesan"** → data muncul ✅

### Test 4: Cek Database
```bash
# Via SSH
php artisan tinker

# Cek riwayat pesan
>>> \App\Models\RiwayatPesan::count()
=> X (jumlah record)

>>> \App\Models\RiwayatPesan::latest()->first()
=> RiwayatPesan {#xxx}
```

---

## ✅ CHECKLIST VERIFIKASI

### Pre-Upload
- [ ] Backup database production
- [ ] Test lengkap di development
- [ ] Siapkan semua file (13 files: 2 baru + 11 update)
- [ ] Koneksi FTP/SSH ready

### Upload
- [ ] Upload migration file
- [ ] Upload RiwayatPesan model
- [ ] Upload Pendaftar model (update)
- [ ] Upload Admin Pendaftar component
- [ ] Upload Admin Pendaftar view
- [ ] Upload Admin DaftarUlang component
- [ ] Upload Admin DaftarUlang view
- [ ] Upload Admin Kelulusan component
- [ ] Upload Admin TesPsikologi component
- [ ] Upload Panitia DaftarUlang component
- [ ] Upload Panitia TesPsikologi component
- [ ] Upload Panitia daftar-ulang view
- [ ] Upload Panitia tes-psikologi view

### Eksekusi Server
- [ ] SSH berhasil
- [ ] Migration berhasil
- [ ] Cache cleared
- [ ] Permissions OK

### Testing Production
- [ ] Tabel `riwayat_pesan` ada
- [ ] Kirim email berhasil + log tersimpan
- [ ] Kolom riwayat pesan muncul di Admin
- [ ] Kolom riwayat pesan muncul di Panitia
- [ ] Template auto-fill berfungsi
- [ ] Placeholder ter-replace semua
- [ ] Tidak ada error di console browser
- [ ] Tidak ada error di `storage/logs/laravel.log`

---

## ⚠️ ROLLBACK PLAN (Jika Ada Masalah)

### 1. Rollback Migration
```bash
# Via SSH
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
php artisan migrate:rollback --step=1
```

### 2. Restore File Lama
- Download backup file dari server
- Upload kembali via FTP

### 3. Clear Cache Lagi
```bash
php artisan optimize:clear
```

### 4. Restore Database (jika perlu)
```bash
# Import backup SQL via phpMyAdmin
# atau via command line
```

---

## 📊 DATABASE SCHEMA

### Tabel: `riwayat_pesan`

```sql
CREATE TABLE `riwayat_pesan` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pendaftar_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NULL,
  `jenis_pesan` enum('email','whatsapp') NOT NULL,
  `judul` varchar(255) NULL,
  `isi_pesan` text NOT NULL,
  `penerima` varchar(255) NOT NULL,
  `status` enum('pending','terkirim','gagal') DEFAULT 'pending',
  `error_message` text NULL,
  `sent_at` timestamp NULL,
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL,
  PRIMARY KEY (`id`),
  KEY `riwayat_pesan_pendaftar_id_index` (`pendaftar_id`),
  KEY `riwayat_pesan_jenis_pesan_index` (`jenis_pesan`),
  KEY `riwayat_pesan_status_index` (`status`),
  KEY `riwayat_pesan_created_at_index` (`created_at`),
  CONSTRAINT `riwayat_pesan_pendaftar_id_foreign` FOREIGN KEY (`pendaftar_id`)
    REFERENCES `pendaftar` (`id`) ON DELETE CASCADE,
  CONSTRAINT `riwayat_pesan_user_id_foreign` FOREIGN KEY (`user_id`)
    REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## 🔧 TROUBLESHOOTING

### Migration Error: "Table already exists"
```bash
# Cek apakah tabel sudah ada
php artisan tinker
>>> \Schema::hasTable('riwayat_pesan')

# Jika true, skip migration atau drop table dulu
>>> \Schema::dropIfExists('riwayat_pesan')
>>> exit

php artisan migrate
```

### Error: "Class RiwayatPesan not found"
```bash
# Pastikan file sudah diupload dengan benar
# Clear cache
php artisan optimize:clear

# Regenerate autoload
composer dump-autoload
```

### Kolom Riwayat Pesan Tidak Muncul
```bash
# Clear view cache
php artisan view:clear

# Refresh browser dengan hard reload
# Ctrl + Shift + R (Chrome/Firefox)
```

### Placeholder Tidak Ter-replace
```bash
# Clear config & cache
php artisan config:clear
php artisan cache:clear

# Refresh halaman
```

---

## 📞 KONTAK SUPPORT

Jika ada masalah saat upload:

1. **Cek log error** terlebih dahulu:
   ```bash
   tail -100 storage/logs/laravel.log
   ```

2. **Cek permission** file:
   ```bash
   ls -la app/Models/RiwayatPesan.php
   ls -la database/migrations/2026_01_23_102510_*
   ```

3. **Test connection** database:
   ```bash
   php artisan tinker
   >>> \DB::connection()->getPdo()
   ```

---

## 📝 CATATAN PENTING

### Compatibility
- ✅ Laravel 10.x
- ✅ PHP 8.1+
- ✅ MySQL 5.7+ / MariaDB 10.3+

### Dependencies
- Tidak ada dependency baru
- Menggunakan Laravel core saja

### Performance
- Index ditambahkan untuk query cepat
- Minimal impact pada performa existing

### Security
- ✅ Mass assignment protection
- ✅ Foreign key constraints
- ✅ Cascade delete untuk data integrity

---

## 🎉 KESIMPULAN

**Total file upload**: 13 files (2 baru + 11 update)
**Estimasi waktu**: 20-30 menit
**Downtime**: Tidak ada (migration ringan)
**Risk level**: LOW (fitur tambahan, tidak mengubah existing)

**READY FOR PRODUCTION!** ✅

---

**Dibuat**: 23 Januari 2026
**Untuk**: Production Server `pmbm.mtsn2kotamalang.sch.id`
**Developer**: Claude Code
**Approved by**: _____________________
**Uploaded by**: _____________________
**Date**: _____________________
