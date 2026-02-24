# CHECKLIST UPLOAD 21 FEBRUARI 2026

## ✅ PRE-UPLOAD CHECKLIST

### Backup Database (Recommended)
- [ ] Backup database production sebelum upload
  ```bash
  ssh mtsnkot4@103.30.244.77
  cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
  pg_dump -U mtsnkot4 -d mtsnkot4_ppdb > backup_before_21feb2026.sql
  ```

---

## 📤 UPLOAD FILES CHECKLIST

### PHP Files (5 files)

- [ ] **File 1**: `daftar-ulang.blade.php`
  - Local: `C:\ppdbmtsn2km-laravel\resources\views\livewire\pendaftar\daftar-ulang.blade.php`
  - Remote: `/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/daftar-ulang.blade.php`
  - Verify: `ls -la resources/views/livewire/pendaftar/daftar-ulang.blade.php`

- [ ] **File 2**: `DaftarUlang.php`
  - Local: `C:\ppdbmtsn2km-laravel\app\Livewire\Pendaftar\DaftarUlang.php`
  - Remote: `/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Pendaftar/DaftarUlang.php`
  - Verify: `ls -la app/Livewire/Pendaftar/DaftarUlang.php`

- [ ] **File 3**: `DaftarUlangMadrasahSheet.php`
  - Local: `C:\ppdbmtsn2km-laravel\app\Exports\DaftarUlangMadrasahSheet.php`
  - Remote: `/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Exports/DaftarUlangMadrasahSheet.php`
  - Verify: `ls -la app/Exports/DaftarUlangMadrasahSheet.php`

- [ ] **File 4**: `DaftarUlangSeragamSheet.php`
  - Local: `C:\ppdbmtsn2km-laravel\app\Exports\DaftarUlangSeragamSheet.php`
  - Remote: `/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Exports/DaftarUlangSeragamSheet.php`
  - Verify: `ls -la app/Exports/DaftarUlangSeragamSheet.php`

- [ ] **File 5**: `DaftarUlangUkuranSheet.php`
  - Local: `C:\ppdbmtsn2km-laravel\app\Exports\DaftarUlangUkuranSheet.php`
  - Remote: `/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Exports/DaftarUlangUkuranSheet.php`
  - Verify: `ls -la app/Exports/DaftarUlangUkuranSheet.php`

### Migration Files (3 files) ⚠️

- [ ] **Migration 1**: `add_individual_opsi_lainnya_to_daftar_ulang_and_seragam.php`
  - Local: `C:\ppdbmtsn2km-laravel\database\migrations\2026_02_09_100000_add_individual_opsi_lainnya_to_daftar_ulang_and_seragam.php`
  - Remote: `/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/database/migrations/2026_02_09_100000_add_individual_opsi_lainnya_to_daftar_ulang_and_seragam.php`
  - Verify: `ls -la database/migrations/2026_02_09_100000_*.php`

- [ ] **Migration 2**: `change_paket_seragam_to_text.php`
  - Local: `C:\ppdbmtsn2km-laravel\database\migrations\2026_02_21_100000_change_paket_seragam_to_text.php`
  - Remote: `/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/database/migrations/2026_02_21_100000_change_paket_seragam_to_text.php`
  - Verify: `ls -la database/migrations/2026_02_21_100000_*.php`

- [ ] **Migration 3**: `add_belum_diisi_to_seragam_status_enum.php`
  - Local: `C:\ppdbmtsn2km-laravel\database\migrations\2026_02_21_210000_add_belum_diisi_to_seragam_status_enum.php`
  - Remote: `/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/database/migrations/2026_02_21_210000_add_belum_diisi_to_seragam_status_enum.php`
  - Verify: `ls -la database/migrations/2026_02_21_210000_*.php`

---

## 🗄️ RUN MIGRATIONS CHECKLIST

- [ ] **Connect to SSH**
  ```bash
  ssh mtsnkot4@103.30.244.77
  ```

- [ ] **Navigate to project directory**
  ```bash
  cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
  ```

- [ ] **Check migration status (before)**
  ```bash
  php artisan migrate:status | grep "2026_02"
  ```
  Expected: All should show "Pending"

- [ ] **Run migrations**
  ```bash
  php artisan migrate
  ```
  Expected output:
  ```
  Running: 2026_02_09_100000_add_individual_opsi_lainnya_to_daftar_ulang_and_seragam
  Migrated: 2026_02_09_100000_add_individual_opsi_lainnya_to_daftar_ulang_and_seragam (XX ms)
  Running: 2026_02_21_100000_change_paket_seragam_to_text
  Migrated: 2026_02_21_100000_change_paket_seragam_to_text (XX ms)
  Running: 2026_02_21_210000_add_belum_diisi_to_seragam_status_enum
  Migrated: 2026_02_21_210000_add_belum_diisi_to_seragam_status_enum (XX ms)
  ```

- [ ] **Verify migrations ran successfully**
  ```bash
  php artisan migrate:status | grep "2026_02"
  ```
  Expected: All should show "Ran"

- [ ] **Check new columns exist**
  ```bash
  php artisan tinker
  ```
  ```php
  \DB::select("SELECT column_name FROM information_schema.columns WHERE table_name = 'daftar_ulang' AND column_name LIKE '%opsi_lainnya%'");
  ```
  Expected: 2 rows
  ```php
  exit
  ```

- [ ] **Check paket_seragam is TEXT**
  ```bash
  php artisan tinker
  ```
  ```php
  \DB::select("SELECT column_name, data_type FROM information_schema.columns WHERE table_name = 'seragam' AND column_name = 'paket_seragam'");
  ```
  Expected: `data_type = "text"`
  ```php
  exit
  ```

---

## 🧹 CLEAR CACHE CHECKLIST

- [ ] **Clear all cache**
  ```bash
  php artisan optimize:clear
  ```

- [ ] **Clear view cache**
  ```bash
  php artisan view:clear
  ```

- [ ] **Clear config cache**
  ```bash
  php artisan config:clear
  ```

- [ ] **Clear application cache**
  ```bash
  php artisan cache:clear
  ```

- [ ] **Verify cache cleared**
  ```bash
  ls storage/framework/views/ | wc -l
  ```
  Expected: 0 or very few files

- [ ] **Exit SSH**
  ```bash
  exit
  ```

---

## 🧪 TESTING CHECKLIST

### Test 1: Tab Madrasah - Paket Lainnya

- [ ] Login sebagai pendaftar
- [ ] Akses: `/pendaftar/daftarulang?activeTab=madrasah`
- [ ] Pilih dropdown "Pilih Paket Madrasah" → "Paket Lainnya (Input Manual)"
- [ ] Isi "Nama Paket Custom": "Test Madrasah Custom"
- [ ] Isi "Nominal Biaya (Rp)": 1500000
- [ ] **VERIFY**: Section "Konfirmasi Nominal yang Dibayarkan" **MUNCUL**
- [ ] **VERIFY**: Opsi "Bayar Penuh" menampilkan nama dan nominal yang benar
- [ ] Pilih "Bayar Penuh"
- [ ] Lengkapi form (opsi pembayaran, bank, bukti, nama pengirim)
- [ ] Klik "Simpan dan Lanjutkan"
- [ ] **VERIFY**: Tidak ada error, redirect berhasil
- [ ] **VERIFY Database**:
  ```sql
  SELECT paket_madrasah, nominal_biaya_awal, nominal_biaya
  FROM daftar_ulang
  WHERE pendaftar_id = [your_id]
  ORDER BY created_at DESC
  LIMIT 1;
  ```
  Expected:
  - `paket_madrasah`: "paket_lainnya"
  - `nominal_biaya_awal`: JSON dengan nama dan amount custom
  - `nominal_biaya`: JSON dengan nama dan amount custom

### Test 2: Tab Seragam - Paket Lainnya

- [ ] Login sebagai pendaftar (sama seperti Test 1)
- [ ] Akses: `/pendaftar/daftarulang?activeTab=seragam`
- [ ] Pilih "Paket Lainnya (Input Manual)"
- [ ] Isi "Nama Paket Custom": "Test Seragam Custom"
- [ ] Isi "Nominal Biaya (Rp)": 2500000
- [ ] **VERIFY**: Section konfirmasi **MUNCUL**
- [ ] **VERIFY**: Bayar Penuh menampilkan nama dan nominal
- [ ] Lengkapi dan simpan
- [ ] **VERIFY**: Berhasil tanpa error
- [ ] **VERIFY Database**:
  ```sql
  SELECT paket_seragam, opsi_seragam, nominal_seragam_awal
  FROM seragam
  WHERE pendaftar_id = [your_id]
  ORDER BY created_at DESC
  LIMIT 1;
  ```
  Expected: Data tersimpan dengan benar

### Test 3: Excel Export - Madrasah

- [ ] Login sebagai admin/panitia
- [ ] Akses: `/admin/daftarulang` (tab: Daftar Ulang Madrasah)
- [ ] Klik "Export Excel"
- [ ] Download file
- [ ] Buka Excel
- [ ] **VERIFY**: Ada kolom "Opsi yang Dipilih"
- [ ] **VERIFY**: Ada kolom "Nominal Opsi"
- [ ] **VERIFY**: Ada kolom "Konfirmasi Nominal"
- [ ] **VERIFY**: Ada kolom "Nominal yang Dibayarkan"
- [ ] **VERIFY**: Data paket lainnya menampilkan nama custom
- [ ] **VERIFY**: Data paket normal menampilkan nama opsi dengan benar

### Test 4: Excel Export - Seragam

- [ ] Login sebagai admin/panitia
- [ ] Akses: `/admin/daftarulang` (tab: Daftar Ulang Seragam)
- [ ] Klik "Export Excel"
- [ ] Download file
- [ ] Buka Excel
- [ ] **VERIFY**: Kolom "Paket Seragam" menampilkan nama readable (bukan JSON)
- [ ] **VERIFY**: Paket lainnya → "Paket Lainnya (Manual)"
- [ ] **VERIFY**: Ada kolom "Opsi yang Dipilih"
- [ ] **VERIFY**: Multi-select ditampilkan dengan " + " separator
- [ ] **VERIFY**: Ada kolom "Nominal Opsi" dengan total yang benar

### Test 5: Excel Export - Ukuran

- [ ] Login sebagai admin/panitia
- [ ] Akses: `/admin/daftarulang` (tab: Ukuran Seragam & Atribut)
- [ ] Klik "Export Excel"
- [ ] Download file
- [ ] Buka Excel
- [ ] **VERIFY**: Kolom "Paket Seragam" readable (bukan JSON)
- [ ] **VERIFY**: Paket lainnya → "Paket Lainnya (Manual)"

### Test 6: Database Schema

- [ ] SSH ke server
- [ ] Run tinker:
  ```bash
  php artisan tinker
  ```
- [ ] **VERIFY**: opsi_lainnya columns exist di daftar_ulang
  ```php
  \DB::select("SELECT column_name FROM information_schema.columns WHERE table_name = 'daftar_ulang' AND column_name LIKE '%opsi_lainnya%'");
  ```
  Expected: 2 rows

- [ ] **VERIFY**: opsi_lainnya columns exist di seragam
  ```php
  \DB::select("SELECT column_name FROM information_schema.columns WHERE table_name = 'seragam' AND column_name LIKE '%opsi_lainnya%'");
  ```
  Expected: 2 rows

- [ ] **VERIFY**: paket_seragam is TEXT
  ```php
  \DB::select("SELECT data_type FROM information_schema.columns WHERE table_name = 'seragam' AND column_name = 'paket_seragam'");
  ```
  Expected: "text"

- [ ] Exit tinker

### Test 7: Long JSON Storage

- [ ] Create pendaftar baru
- [ ] Pilih paket seragam dengan banyak items (checkbox mode)
- [ ] Pilih 5+ items
- [ ] Simpan
- [ ] **VERIFY**: Data tersimpan lengkap (tidak terpotong)
- [ ] Export Excel
- [ ] **VERIFY**: Multi-select ditampilkan dengan " + " separator
- [ ] **VERIFY**: Total nominal benar (SUM of all items)

---

## ✅ POST-DEPLOYMENT CHECKLIST

- [ ] All 8 files uploaded successfully
- [ ] All 3 migrations ran successfully
- [ ] Cache cleared
- [ ] Tab Madrasah paket lainnya works
- [ ] Tab Seragam paket lainnya works
- [ ] Excel export Madrasah shows complete data
- [ ] Excel export Seragam shows readable paket
- [ ] Excel export Ukuran consistent
- [ ] Database columns exist
- [ ] No errors in Laravel log
- [ ] No errors in browser console

---

## 📝 NOTES

- Jangan panic kalau ada warning tentang constraint saat migration
- Migrations aman, tidak akan hilangkan data
- Jika ada error migration, jangan run rollback - buat migration baru untuk fix
- Backup database sudah dibuat di awal untuk recovery jika needed

---

## 🚨 IF SOMETHING GOES WRONG

### Migration Failed
1. Check error message di console
2. Check Laravel log: `tail -f storage/logs/laravel.log`
3. Jangan run rollback di production
4. Contact developer atau buat migration baru untuk fix

### Data Tidak Tersimpan
1. Check validation error di browser console (F12)
2. Check Laravel log
3. Verify file DaftarUlang.php ter-upload dengan benar
4. Clear cache lagi

### Excel Export Kosong
1. Clear cache: `php artisan optimize:clear`
2. Verify export files ter-upload dengan benar
3. Check file timestamps

---

**Date**: 21 Februari 2026
**Total Files**: 8 (5 PHP + 3 Migrations)
**Status**: Ready for Deployment
