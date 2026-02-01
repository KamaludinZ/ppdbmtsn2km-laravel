# 📋 CHECKLIST UPLOAD PRODUCTION - 4 HARI TERAKHIR
**Tanggal**: 23-27 Januari 2026
**Total Fitur Baru**: 8 fitur utama + berbagai perbaikan

---

## 🎯 RINGKASAN FITUR BARU

1. ✅ **Fitur Riwayat Pesan** - Track semua pesan yang dikirim ke pendaftar
2. ✅ **Statistik Per Jalur & Tahun** - Dashboard statistik yang lebih detail
3. ✅ **Template Pesan Kontekstual** - Template pesan dengan placeholder dinamis
4. ✅ **Status Daftar Jalur** - Kolom "Tampil di Daftar" untuk jalur pendaftaran
5. ✅ **Export Excel Improvements** - Restructure kolom EMIS, loading bar, fix bugs
6. ✅ **Daftar Ulang Enhancements** - Payment tracking, seragam ukuran detail, stats
7. ✅ **Region Code Fixes** - Fix 7-digit codes, trailing spaces, code to name conversion
8. ✅ **Fix Catatan Duplikat** - Fix bug catatan tidak update (duplicate records)

---

## 📦 DAFTAR FILE YANG PERLU DI-UPLOAD

### 1️⃣ DATABASE MIGRATIONS (20 files)
**⚠️ PENTING: Jalankan migration sesuai urutan timestamp!**

```
database/migrations/2026_01_15_051817_create_postal_codes_table.php
database/migrations/2026_01_15_055334_add_siswa_alamat_to_pendaftar_table.php
database/migrations/2026_01_16_131212_add_unique_to_users_email_and_performance_indexes.php
database/migrations/2026_01_16_140214_add_status_fields_to_seragam_table.php
database/migrations/2026_01_16_180155_add_catatan_to_kartu_kendali_table.php
database/migrations/2026_01_16_203729_add_graduation_statuses_to_pendaftar_enum.php
database/migrations/2026_01_17_000001_add_missing_status_to_pendaftar_enum.php
database/migrations/2026_01_17_000002_update_old_status_values.php
database/migrations/2026_01_19_optimize_register_performance.php
database/migrations/2026_01_20_000001_add_payment_and_document_fields_to_daftar_ulang_and_seragam.php
database/migrations/2026_01_21_212140_add_brosur_settings_to_settings_table.php
database/migrations/2026_01_22_065858_add_payment_info_text_to_settings_table.php
database/migrations/2026_01_22_205942_add_nominal_biaya_columns_to_daftar_ulang_and_seragam_tables.php
database/migrations/2026_01_22_211220_add_detailed_uniform_size_columns_to_seragam_table.php
database/migrations/2026_01_23_053343_add_tampil_di_daftar_to_jalur_pendaftaran_table.php
database/migrations/2026_01_23_102510_create_riwayat_pesan_table.php
database/migrations/2026_01_23_195806_create_statistik_tahun_table.php
database/migrations/2026_01_25_215507_add_alasan_tidak_setuju_to_pendaftars_table.php
database/migrations/2026_01_27_124505_update_status_enum_in_daftar_ulang_table.php
database/migrations/2026_01_27_132623_add_unique_constraint_to_daftar_ulang_pendaftar_id.php
```

### 2️⃣ MODELS - New Files (3 files)

```
app/Models/RiwayatPesan.php                    ← BARU (Fitur Riwayat Pesan)
app/Models/StatistikJalurTahun.php             ← BARU (Statistik per Jalur)
app/Models/StatistikTahun.php                  ← BARU (Statistik per Tahun)
```

### 3️⃣ MODELS - Updated Files (2 files)

```
app/Models/JalurPendaftaran.php                ← UPDATE (relasi + tampil_di_daftar)
app/Models/Pendaftar.php                       ← UPDATE (relasi riwayatPesan)
```

### 4️⃣ EXPORT CLASSES (7 files)

**New Files:**
```
app/Exports/DaftarUlangSeragamExport.php       ← BARU (Export seragam terpisah)
app/Exports/LaporanPendaftaranExport.php       ← BARU (Export laporan PDF)
```

**Updated Files:**
```
app/Exports/DaftarUlangMadrasahSheet.php       ← UPDATE (payment columns)
app/Exports/DaftarUlangSeragamSheet.php        ← UPDATE (ukuran detail)
app/Exports/DaftarUlangUkuranSheet.php         ← UPDATE (grid layout)
app/Exports/PendaftarExport.php                ← UPDATE (EMIS restructure, loading bar)
app/Exports/SeragamExport.php                  ← UPDATE (ukuran kolom detail)
```

### 5️⃣ CONTROLLERS (1 file)

```
app/Http/Controllers/ExportController.php      ← UPDATE (loading bar support)
```

### 6️⃣ LIVEWIRE COMPONENTS - Admin (9 files)

```
app/Livewire/Admin/DaftarUlang.php             ← UPDATE (catatan fix, stats, payment)
app/Livewire/Admin/Jadwal.php                  ← UPDATE (minor improvements)
app/Livewire/Admin/Jalur.php                   ← UPDATE (tampil_di_daftar column)
app/Livewire/Admin/Kelulusan.php               ← UPDATE (stats improvements)
app/Livewire/Admin/Laporan.php                 ← UPDATE (export improvements)
app/Livewire/Admin/Pendaftar.php               ← UPDATE (riwayat pesan integration)
app/Livewire/Admin/PengaturanPesan.php         ← UPDATE (template kontekstual)
app/Livewire/Admin/TesPsikologi.php            ← UPDATE (stats layout)
```

### 7️⃣ LIVEWIRE COMPONENTS - Auth (3 files)

```
app/Livewire/Auth/Register.php                 ← UPDATE (performance optimization)
app/Livewire/Auth/RegisterFixed.php            ← UPDATE (backup version)
app/Livewire/Auth/RegisterOptimized.php        ← UPDATE (optimized version)
```

### 8️⃣ LIVEWIRE COMPONENTS - Panitia (2 files)

```
app/Livewire/Panitia/DaftarUlang.php           ← UPDATE (sync dengan Admin)
app/Livewire/Panitia/TesPsikologi.php          ← UPDATE (stats layout)
```

### 9️⃣ LIVEWIRE COMPONENTS - Pendaftar (3 files)

```
app/Livewire/Pendaftar/Dashboard.php           ← UPDATE (minor improvements)
app/Livewire/Pendaftar/DaftarUlang.php         ← UPDATE (payment info display)
app/Livewire/Pendaftar/Dokumen.blade.php       ← UPDATE (UI improvements)
```

### 🔟 VIEWS - Admin Livewire (10 files)

```
resources/views/livewire/admin/daftar-ulang.blade.php                    ← UPDATE (3 tabs improvements)
resources/views/livewire/admin/jalur.blade.php                           ← UPDATE (tampil_di_daftar toggle)
resources/views/livewire/admin/laporan.blade.php                         ← UPDATE (export improvements)
resources/views/livewire/admin/pendaftar.blade.php                       ← UPDATE (riwayat pesan modal)
resources/views/livewire/admin/pengaturan-pesan.blade.php                ← UPDATE (template dropdown)
resources/views/livewire/admin/tes-psikologi.blade.php                   ← UPDATE (stats display)
resources/views/livewire/admin/partials/bulk-send-message-modal.blade.php  ← UPDATE (template support)
resources/views/livewire/admin/partials/daftar-ulang-stats.blade.php       ← UPDATE (all tabs stats)
resources/views/livewire/admin/partials/data-lengkap-stats.blade.php       ← BARU (data lengkap stats)
resources/views/livewire/admin/partials/send-message-modal.blade.php       ← UPDATE (template support)
resources/views/livewire/admin/partials/tes-psikologi-data.blade.php       ← UPDATE (layout)
```

### 1️⃣1️⃣ VIEWS - Panitia Livewire (2 files)

```
resources/views/livewire/panitia/daftar-ulang.blade.php                 ← UPDATE (sync dengan Admin)
resources/views/livewire/panitia/tes-psikologi.blade.php                ← UPDATE (stats layout)
```

### 1️⃣2️⃣ VIEWS - Pendaftar Livewire (3 files)

```
resources/views/livewire/pendaftar/daftar-ulang.blade.php               ← UPDATE (payment info)
resources/views/livewire/pendaftar/dashboard.blade.php                  ← UPDATE (UI improvements)
resources/views/livewire/pendaftar/dokumen.blade.php                    ← UPDATE (file upload)
```

### 1️⃣3️⃣ VIEWS - Tab Templates (3 files)

```
resources/views/tab_templates/tab-data-alamat.blade.php                 ← UPDATE (region codes)
resources/views/tab_templates/tab-data-orangtua.blade.php               ← UPDATE (region codes)
resources/views/tab_templates/tab-data-siswa.blade.php                  ← UPDATE (siswa_alamat)
```

### 1️⃣4️⃣ VIEWS - Export Templates (1 file)

```
resources/views/exports/laporan-pendaftaran-pdf.blade.php               ← BARU (PDF export)
```

### 1️⃣5️⃣ ROUTES (1 file)

```
routes/web.php                                                          ← UPDATE (new routes)
```

### 1️⃣6️⃣ UTILITY SCRIPTS (Untuk Maintenance - OPSIONAL)

```
cleanup_duplicates.php                         ← Cleanup duplicate daftar_ulang (sudah dijalankan)
check_catatan.php                              ← Diagnostic script
check_data_completeness.php                    ← Check data integrity
check_jalur_status.php                         ← Check jalur status
```

---

## 🚀 LANGKAH-LANGKAH UPLOAD

### STEP 1: Backup Production Database
```bash
# SSH ke server production
ssh mtsnkot4@103.30.244.77

# Masuk ke direktori
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

# Backup database
php artisan backup:run --only-db
```

### STEP 2: Upload File ke Server

**Metode A: Via FTP/SFTP**
Upload semua file di atas ke direktori yang sesuai di production.

**Metode B: Via Git (Recommended)**
```bash
# Di local
git add .
git commit -m "Update: 8 fitur baru + fixes (23-27 Jan 2026)"
git push origin main

# Di server
git pull origin main
```

### STEP 3: Install Dependencies (Jika Ada Perubahan)
```bash
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

# Update composer jika perlu
composer install --no-dev --optimize-autoloader
```

### STEP 4: Jalankan Migrations
```bash
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

# Jalankan migration
php artisan migrate

# Output yang diharapkan:
# - 20 migrations akan dijalankan
# - Semua harus sukses
```

### STEP 5: Cleanup Duplicate Records (PENTING!)
```bash
# Upload cleanup_duplicates.php ke server
# Jalankan:
php cleanup_duplicates.php

# Output yang diharapkan:
# Found X pendaftar with duplicate records
# Cleanup complete
```

### STEP 6: Clear Cache
```bash
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
php artisan optimize
```

### STEP 7: Set Permissions (Jika Perlu)
```bash
chmod -R 755 storage bootstrap/cache
chown -R mtsnkot4:mtsnkot4 storage bootstrap/cache
```

### STEP 8: Testing di Production

**Test Checklist:**
- [ ] Login sebagai Admin
- [ ] Test Fitur Riwayat Pesan (Pendaftar → Lihat Riwayat)
- [ ] Test Template Pesan (Pengaturan Pesan → Pilih Template)
- [ ] Test Export Excel (Pendaftar → Export)
- [ ] Test Daftar Ulang Madrasah (Edit Catatan → Harus langsung update)
- [ ] Test Daftar Ulang Seragam (Payment, Ukuran)
- [ ] Test Statistik Dashboard (Semua tabs)
- [ ] Test Jalur → Toggle "Tampil di Daftar"
- [ ] Test Laporan PDF Export

---

## ⚠️ PERHATIAN KHUSUS

### 1. Migration 2026_01_27_132623 (Unique Constraint)
**PENTING**: Migration ini menambahkan UNIQUE constraint pada `pendaftar_id` di tabel `daftar_ulang`.

**Jika migration gagal dengan error "duplicate key":**
```bash
# Jalankan cleanup script terlebih dahulu:
php cleanup_duplicates.php

# Kemudian jalankan migration lagi:
php artisan migrate
```

### 2. Enum Status Updates
Migration `2026_01_27_124505_update_status_enum_in_daftar_ulang_table.php` mengupdate enum values:
- Menambah: `disetujui`, `ditolak`, `perbaiki`, `menunggu`, `batal`
- Convert: `proses` → `menunggu`

### 3. Region Codes
Pastikan data region codes sudah benar (7 digit, tanpa trailing spaces).
Jika ada masalah, jalankan:
```bash
php check_data_completeness.php
```

---

## 📊 SUMMARY CHANGES BY FILE TYPE

| File Type              | New | Updated | Total |
|------------------------|-----|---------|-------|
| Migrations             | 20  | 0       | 20    |
| Models                 | 3   | 2       | 5     |
| Exports                | 2   | 5       | 7     |
| Controllers            | 0   | 1       | 1     |
| Livewire Components    | 0   | 17      | 17    |
| Blade Views (Livewire) | 1   | 15      | 16    |
| Tab Templates          | 0   | 3       | 3     |
| Export Templates       | 1   | 0       | 1     |
| Routes                 | 0   | 1       | 1     |
| **TOTAL**              | **27** | **44** | **71** |

---

## 📝 DOKUMENTASI FITUR BARU

Dokumentasi lengkap tersedia di:

1. `FITUR_RIWAYAT_PESAN_COMPLETE.md` - Fitur riwayat pesan
2. `CONTEXTUAL_TEMPLATE_COMPLETE_IMPLEMENTATION.md` - Template kontekstual
3. `FITUR_STATUS_DAFTAR_JALUR.md` - Status daftar jalur
4. `EXCEL_EXPORT_EMIS_RESTRUCTURE_COMPLETE.md` - Export improvements
5. `DAFTAR_ULANG_IMPROVEMENTS_COMPLETE.md` - Daftar ulang enhancements
6. `FIX_REGION_CODES_TO_NAMES_COMPLETE.md` - Region code fixes
7. `FIX_CATATAN_MADRASAH_COMPLETE.md` - Fix catatan bug
8. `ALL_TABS_STATS_COMPLETE.md` - Statistik improvements

---

## ✅ POST-DEPLOYMENT VERIFICATION

Setelah upload selesai, verifikasi:

1. **Database Schema**
   ```sql
   -- Cek tabel baru
   SELECT table_name FROM information_schema.tables
   WHERE table_schema = 'public'
   AND table_name IN ('riwayat_pesan', 'statistik_tahun', 'statistik_jalur_tahun');

   -- Cek unique constraint
   SELECT constraint_name FROM information_schema.table_constraints
   WHERE table_name = 'daftar_ulang'
   AND constraint_type = 'UNIQUE';
   ```

2. **File Integrity**
   ```bash
   # Cek file penting ada
   ls -la app/Models/RiwayatPesan.php
   ls -la app/Exports/LaporanPendaftaranExport.php
   ls -la resources/views/livewire/admin/partials/data-lengkap-stats.blade.php
   ```

3. **Application Health**
   ```bash
   php artisan about
   php artisan route:list | grep riwayat
   ```

---

## 🆘 TROUBLESHOOTING

### Issue: Migration Gagal - Duplicate Key
**Solusi**: Jalankan `php cleanup_duplicates.php` lalu `php artisan migrate`

### Issue: Catatan Tidak Update
**Solusi**: Pastikan migration `2026_01_27_132623` sudah jalan dan tidak ada duplicate records

### Issue: Template Pesan Tidak Muncul
**Solusi**: Clear cache dengan `php artisan view:clear && php artisan cache:clear`

### Issue: Export Error
**Solusi**: Pastikan `app/Exports/` semua file terupload dengan benar

### Issue: Stats Tidak Muncul
**Solusi**:
- Cek migration `2026_01_23_195806_create_statistik_tahun_table.php` sudah jalan
- Jalankan `php artisan optimize:clear`

---

## 📞 KONTAK

Jika ada masalah saat upload production, silakan hubungi tim development.

**Dibuat**: 27 Januari 2026
**Versi**: 1.0
**Status**: ✅ Ready for Production Upload
