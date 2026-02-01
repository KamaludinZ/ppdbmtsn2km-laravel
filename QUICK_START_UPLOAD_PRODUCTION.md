# 🚀 QUICK START - UPLOAD PRODUCTION

**Panduan Cepat Upload Perbaikan 23-27 Januari 2026**

---

## ⏱️ ESTIMASI WAKTU: 15-20 Menit

---

## 📋 PERSIAPAN (5 menit)

### 1. Backup Database Production
```bash
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
php artisan backup:run --only-db
```

### 2. Pastikan File Siap
✅ Cek file di lokal: `C:\ppdbmtsn2km-laravel\UPLOAD_FILES_LIST.txt`

---

## 📤 UPLOAD FILES (10 menit)

### Opsi A: Via Git (RECOMMENDED) ⭐
```bash
# Di Windows (local)
cd C:\ppdbmtsn2km-laravel
git add .
git commit -m "Update 8 fitur baru (23-27 Jan 2026)"
git push origin main

# Di Server (SSH)
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
git pull origin main
```

### Opsi B: Via FileZilla/FTP
1. Buka FileZilla
2. Connect ke: `103.30.244.77`
3. Upload **71 files** sesuai list di `UPLOAD_FILES_LIST.txt`
4. Pastikan struktur folder sama!

---

## ⚙️ JALANKAN DI SERVER (5 menit)

### 1. Cleanup Duplikat Data (WAJIB!)
```bash
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
php cleanup_duplicates.php
```

**Output yang benar:**
```
Found X pendaftar with duplicate records
Cleanup done for pendaftar...
✓ CLEANUP COMPLETE
```

### 2. Jalankan Migrations
```bash
php artisan migrate
```

**Output yang diharapkan:**
```
Running migrations...
✓ 2026_01_15_051817_create_postal_codes_table
✓ 2026_01_15_055334_add_siswa_alamat_to_pendaftar_table
... (total 20 migrations)
✓ 2026_01_27_132623_add_unique_constraint_to_daftar_ulang_pendaftar_id
```

⚠️ **Jika migration gagal dengan error "duplicate key":**
```bash
# Jalankan cleanup lagi:
php cleanup_duplicates.php

# Lalu coba migrate lagi:
php artisan migrate
```

### 3. Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
php artisan optimize
```

---

## ✅ TESTING (5-10 menit)

### Test Checklist:

**1. Fitur Riwayat Pesan**
- [ ] Login sebagai Admin
- [ ] Buka halaman Pendaftar
- [ ] Klik tombol "Lihat Riwayat" pada satu pendaftar
- [ ] Harus muncul modal dengan daftar pesan yang pernah dikirim

**2. Template Pesan Kontekstual**
- [ ] Buka halaman Pengaturan Pesan
- [ ] Klik "Kirim Pesan" pada satu pendaftar
- [ ] Di dropdown "Template" harus muncul pilihan template
- [ ] Pilih satu template → text otomatis terisi
- [ ] Placeholder {nama}, {nisn}, {nomor_hp} harus terganti otomatis

**3. Fix Catatan Madrasah (PENTING!)**
- [ ] Buka Daftar Ulang → Tab "Daftar Ulang Madrasah"
- [ ] Klik "Edit" pada kolom Catatan
- [ ] Ubah catatan → Klik Simpan
- [ ] **HARUS**: Catatan baru langsung tampil (tidak cache lama lagi!) ✅

**4. Export Excel**
- [ ] Buka halaman Pendaftar
- [ ] Klik tombol "Export"
- [ ] Harus muncul loading bar progress
- [ ] Download berhasil → Buka file Excel
- [ ] Cek kolom EMIS sudah terstruktur dengan baik

**5. Daftar Ulang Seragam/Ukuran**
- [ ] Buka Daftar Ulang → Tab "Seragam"
- [ ] Cek kolom ukuran detail (baju, celana, dll) muncul
- [ ] Edit data → Simpan → Harus langsung update

**6. Statistik Dashboard**
- [ ] Buka Dashboard Admin
- [ ] Cek stats di semua tabs
- [ ] Statistik per jalur harus muncul

**7. Jalur Pendaftaran**
- [ ] Buka halaman Jalur
- [ ] Cek kolom "Tampil di Daftar"
- [ ] Toggle ON/OFF → Harus langsung update

---

## 🆘 TROUBLESHOOTING

### ❌ Migration Gagal: "duplicate key violation"
**Solusi:**
```bash
php cleanup_duplicates.php
php artisan migrate
```

### ❌ Catatan Masih Tidak Update
**Solusi:**
```bash
# Cek apakah ada duplicate:
php check_catatan.php

# Jika masih ada duplicate:
php cleanup_duplicates.php

# Clear cache:
php artisan view:clear
php artisan cache:clear
```

### ❌ Template Pesan Tidak Muncul
**Solusi:**
```bash
php artisan view:clear
php artisan cache:clear
```

### ❌ Export Error
**Solusi:**
```bash
# Cek file export class sudah terupload:
ls -la app/Exports/PendaftarExport.php
ls -la app/Exports/LaporanPendaftaranExport.php

# Clear cache:
php artisan optimize:clear
```

### ❌ Stats Tidak Muncul
**Solusi:**
```bash
# Cek tabel statistik sudah dibuat:
php artisan tinker
>>> \DB::table('statistik_tahun')->count();
>>> exit

# Jika tabel belum ada, jalankan migration lagi:
php artisan migrate
```

---

## 📞 CONTACT

Jika ada masalah yang tidak bisa diselesaikan:
- Screenshot error message
- Screenshot output migration
- Catat langkah yang sudah dilakukan

---

## ✅ CHECKLIST AKHIR

Setelah semua selesai, pastikan:
- [x] Database sudah di-backup
- [x] 71 files sudah terupload
- [x] cleanup_duplicates.php sudah dijalankan
- [x] Migrations semua sukses (20 migrations)
- [x] Cache sudah di-clear
- [x] Testing 7 fitur utama sudah dilakukan
- [x] Tidak ada error di log (`tail -50 storage/logs/laravel.log`)

---

## 🎉 SELAMAT!

Jika semua checklist di atas ✅, maka update production **BERHASIL**!

**8 Fitur Baru Sudah Live:**
1. ✅ Riwayat Pesan
2. ✅ Template Pesan Kontekstual
3. ✅ Status Daftar Jalur
4. ✅ Export Excel Improvements
5. ✅ Daftar Ulang Enhancements
6. ✅ Statistik Dashboard
7. ✅ Region Code Fixes
8. ✅ Fix Catatan Bug (SOLVED!)

---

**Dokumentasi Lengkap**: `PRODUCTION_UPLOAD_CHECKLIST_4_HARI.md`
**List File**: `UPLOAD_FILES_LIST.txt`
