# 📦 Daftar File untuk Upload ke Production

## 🎯 Fitur yang Ditambahkan
1. ✅ Kolom "Status Daftar" untuk mengatur kemunculan jalur di halaman /daftar
2. ✅ Kolom "Paling Populer" di halaman admin jalur
3. ✅ Query filter berdasarkan `tampil_di_daftar` di semua halaman registrasi

---

## 📁 WAJIB UPLOAD - File Backend

### 1. Migration (Database)
```
database/migrations/2026_01_23_053343_add_tampil_di_daftar_to_jalur_pendaftaran_table.php
```
**PENTING**: Migration ini menambahkan kolom `tampil_di_daftar` ke tabel `jalur_pendaftaran`

### 2. Model
```
app/Models/JalurPendaftaran.php
```
**Perubahan**:
- Tambah `tampil_di_daftar` di `$fillable`
- Tambah cast `tampil_di_daftar` sebagai boolean
- Tambah scope `scopeTampilDiDaftar()`

### 3. Livewire Components (Backend)

#### Admin Jalur
```
app/Livewire/Admin/Jalur.php
```
**Perubahan**:
- Tambah property `$tampil_di_daftar`
- Update method `edit()`, `store()`, `update()`, `resetFields()`

#### Register Components (3 file)
```
app/Livewire/Auth/Register.php
app/Livewire/Auth/RegisterFixed.php
app/Livewire/Auth/RegisterOptimized.php
```
**Perubahan**:
- Update query dari `JalurPendaftaran::aktif()` menjadi `JalurPendaftaran::tampilDiDaftar()`

---

## 📁 WAJIB UPLOAD - File Frontend (Views)

### 1. View Admin Jalur
```
resources/views/livewire/admin/jalur.blade.php
```
**Perubahan**:
- Tambah kolom header "Status Daftar"
- Tambah kolom header "Paling Populer" ⭐
- Tambah badge status untuk kedua kolom
- Tambah checkbox "Tampil di Halaman Daftar" di form modal

---

## 📁 OPSIONAL - File untuk Testing/Debug

### 1. Route Test (Untuk debugging di production)
```
routes/test_jalur.php
```
**Fungsi**: Halaman `/test-jalur-daftar` untuk debug query

### 2. Update routes/web.php
**Tambahkan di akhir file:**
```php
// Test route untuk debug jalur daftar
require __DIR__ . '/test_jalur.php';
```

**CATATAN**: File ini opsional, hanya untuk debugging jika ada masalah di production

### 3. Script PHP untuk Testing
```
check_jalur_status.php
test_daftar_jalur.php
```
**Fungsi**: Script command-line untuk cek status jalur di database
**CATATAN**: Opsional, hanya untuk debugging via SSH

---

## 🚀 LANGKAH UPLOAD KE PRODUCTION

### Step 1: Backup Database Production
```bash
# Via SSH ke server production
ssh mtsnkot4@103.30.244.77

cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

# Backup database
php artisan db:backup
# ATAU manual backup via phpMyAdmin
```

### Step 2: Upload File ke Server

**Via FTP/SFTP** (FileZilla, WinSCP, dll):

1. **Upload Migration**
   - Upload ke: `/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/database/migrations/`
   - File: `2026_01_23_053343_add_tampil_di_daftar_to_jalur_pendaftaran_table.php`

2. **Upload Model**
   - Upload ke: `/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Models/`
   - File: `JalurPendaftaran.php`

3. **Upload Livewire Admin**
   - Upload ke: `/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/`
   - File: `Jalur.php`

4. **Upload Livewire Auth (3 files)**
   - Upload ke: `/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Auth/`
   - File: `Register.php`
   - File: `RegisterFixed.php`
   - File: `RegisterOptimized.php`

5. **Upload View**
   - Upload ke: `/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/`
   - File: `jalur.blade.php`

6. **Upload Route Test (Opsional)**
   - Upload ke: `/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/routes/`
   - File: `test_jalur.php`
   - Edit `routes/web.php` tambahkan: `require __DIR__ . '/test_jalur.php';`

### Step 3: Jalankan Migration di Production
```bash
# Via SSH
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

# Jalankan migration
php artisan migrate

# Jika diminta konfirmasi, ketik: yes
```

**Output yang diharapkan:**
```
INFO  Running migrations.

2026_01_23_053343_add_tampil_di_daftar_to_jalur_pendaftaran_table .... DONE
```

### Step 4: Clear Cache Production
```bash
# Masih di SSH
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# ATAU sekaligus:
php artisan optimize:clear
```

### Step 5: Verifikasi di Production

1. **Cek Database**
   ```bash
   php artisan tinker
   >>> \App\Models\JalurPendaftaran::first()->tampil_di_daftar
   # Harus return: true atau false (tidak error)
   >>> exit
   ```

2. **Test di Browser**
   - Buka: `https://pmbm.mtsn2kotamalang.sch.id/admin/jalur`
   - Verifikasi kolom "Status Daftar" dan "Paling Populer" muncul
   - Edit jalur, verifikasi checkbox "Tampil di Halaman Daftar" ada

3. **Test Halaman Daftar**
   - Buka: `https://pmbm.mtsn2kotamalang.sch.id/daftar`
   - Hard refresh: Ctrl + Shift + R
   - Verifikasi jalur yang muncul sesuai "Status Daftar"

4. **Test Debug (Jika upload route test)**
   - Buka: `https://pmbm.mtsn2kotamalang.sch.id/test-jalur-daftar`
   - Lihat jalur yang muncul, harus sama dengan `/daftar`

---

## 📝 Checklist Upload

Centang saat sudah upload:

### File Wajib (Backend)
- [ ] `database/migrations/2026_01_23_053343_add_tampil_di_daftar_to_jalur_pendaftaran_table.php`
- [ ] `app/Models/JalurPendaftaran.php`
- [ ] `app/Livewire/Admin/Jalur.php`
- [ ] `app/Livewire/Auth/Register.php`
- [ ] `app/Livewire/Auth/RegisterFixed.php`
- [ ] `app/Livewire/Auth/RegisterOptimized.php`

### File Wajib (Frontend)
- [ ] `resources/views/livewire/admin/jalur.blade.php`

### File Opsional (Testing)
- [ ] `routes/test_jalur.php` (untuk debug)
- [ ] Edit `routes/web.php` (tambah require test_jalur.php)

### Eksekusi di Server
- [ ] Backup database production
- [ ] Upload semua file ke server
- [ ] Jalankan `php artisan migrate`
- [ ] Jalankan `php artisan optimize:clear`
- [ ] Test di browser production

---

## ⚠️ PENTING - Catatan Production

### 1. **Set Semua Jalur Existing ke Tampil**
Setelah migration, semua jalur akan otomatis `tampil_di_daftar = true` (default).

Jika ada jalur yang ingin disembunyikan, ubah via admin:
- Buka `/admin/jalur`
- Edit jalur yang ingin disembunyikan
- **Uncheck** "Tampil di Halaman Daftar"
- Simpan

### 2. **Testing Sebelum Deployment**
Pastikan test di local dulu:
```bash
# Di local development
php test_daftar_jalur.php
```
Hasilnya harus menunjukkan jalur yang benar.

### 3. **Rollback Plan**
Jika ada masalah di production:

**Rollback Migration:**
```bash
php artisan migrate:rollback --step=1
```

**Restore File Lama:**
- Restore `JalurPendaftaran.php` versi lama
- Restore `Jalur.php` versi lama
- Restore `Register*.php` versi lama
- Restore `jalur.blade.php` versi lama
- Clear cache: `php artisan optimize:clear`

---

## 🎯 Quick Command untuk Copy-Paste

```bash
# 1. SSH ke server
ssh mtsnkot4@103.30.244.77

# 2. Masuk ke directory project
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

# 3. Jalankan migration
php artisan migrate

# 4. Clear cache
php artisan optimize:clear

# 5. Test query
php artisan tinker
>>> \App\Models\JalurPendaftaran::tampilDiDaftar()->count()
>>> exit

# 6. Done! Test di browser
```

---

## 📞 Support

Jika ada error saat migration atau upload:
1. Cek error message di terminal
2. Cek log: `storage/logs/laravel.log`
3. Pastikan file permissions benar (755 untuk folder, 644 untuk file)
4. Hubungi hosting support jika ada masalah database

---

**Dibuat**: 23 Januari 2026
**Status**: ✅ Siap untuk Production
**Total File**: 7 file wajib + 2 file opsional
