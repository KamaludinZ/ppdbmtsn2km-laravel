# 📦 Panduan Upload ke Production - STATUS DAFTAR & PALING POPULER

## 🚀 Quick Start (Cara Tercepat)

### 1️⃣ Kumpulkan File untuk Upload
Double-click file ini di Windows:
```
prepare_production_files.bat
```

File akan dikumpulkan ke folder baru: `production_upload_YYYYMMDD_HHMM/`

### 2️⃣ Upload ke Server
- Buka **FileZilla** atau **WinSCP**
- Connect ke: `103.30.244.77` (user: `mtsnkot4`)
- Upload semua file dari folder `production_upload_xxx/` ke `/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/`
- **JANGAN LUPA**: Preserve folder structure!

### 3️⃣ Jalankan Migration di Server
```bash
# Via SSH (PuTTY atau terminal)
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
php artisan migrate
php artisan optimize:clear
```

### 4️⃣ Test di Browser
- Buka: `https://pmbm.mtsn2kotamalang.sch.id/admin/jalur`
- Verifikasi kolom baru muncul
- Test edit jalur dan ubah status

✅ **SELESAI!**

---

## 📋 File yang Tersedia

| File | Kegunaan |
|------|----------|
| **UPLOAD_TO_PRODUCTION.md** | Panduan lengkap upload (BACA INI DULU!) |
| **CHECKLIST_UPLOAD_PRODUCTION.txt** | Checklist untuk di-print/centang saat upload |
| **prepare_production_files.bat** | Script untuk kumpulkan semua file |
| **FIX_STATUS_DAFTAR_FINAL.md** | Dokumentasi perbaikan yang dilakukan |
| **FITUR_STATUS_DAFTAR_JALUR.md** | Dokumentasi fitur lengkap |

---

## 📁 File yang Akan Diupload (Total: 7 Wajib + 2 Opsional)

### WAJIB (7 files):
1. `database/migrations/2026_01_23_053343_add_tampil_di_daftar_to_jalur_pendaftaran_table.php`
2. `app/Models/JalurPendaftaran.php`
3. `app/Livewire/Admin/Jalur.php`
4. `app/Livewire/Auth/Register.php`
5. `app/Livewire/Auth/RegisterFixed.php`
6. `app/Livewire/Auth/RegisterOptimized.php`
7. `resources/views/livewire/admin/jalur.blade.php`

### OPSIONAL (2 files - untuk debugging):
8. `routes/test_jalur.php`
9. Edit `routes/web.php` (tambah 1 baris)

---

## ⚡ Command Cepat

### Untuk Copy-Paste di SSH:
```bash
# 1. Masuk ke directory
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

# 2. Migration + Clear cache (1 command)
php artisan migrate && php artisan optimize:clear

# 3. Test query
php artisan tinker --execute="echo \App\Models\JalurPendaftaran::tampilDiDaftar()->count() . ' jalur tampil di daftar';"
```

---

## 🎯 Yang Berubah di Production

### Di Admin (`/admin/jalur`):
- ✅ Kolom baru: **"Status Daftar"** (Tampil/Tersembunyi)
- ✅ Kolom baru: **"Paling Populer"** (⭐ Ya/Tidak)
- ✅ Checkbox baru di form: "Tampil di Halaman Daftar"

### Di Halaman Daftar (`/daftar`):
- ✅ Hanya jalur dengan **"Status Daftar = Tampil"** yang muncul
- ✅ Kolom "Status Aktif" **TIDAK LAGI BERPENGARUH** ke kemunculan jalur

### Di Database:
- ✅ Tabel `jalur_pendaftaran` dapat kolom baru: `tampil_di_daftar` (boolean, default: true)

---

## ⚠️ PENTING!

### Sebelum Upload:
- ✅ **BACKUP DATABASE PRODUCTION** (WAJIB!)
- ✅ Test di local development dulu
- ✅ Siapkan rollback plan

### Setelah Upload:
- ✅ Test semua fungsi jalur (tambah/edit/hapus)
- ✅ Test halaman `/daftar` di incognito mode
- ✅ Cek tidak ada error di console browser (F12)
- ✅ Cek log: `tail storage/logs/laravel.log`

### Default Behavior:
- Semua jalur existing akan otomatis `tampil_di_daftar = true`
- Jika ingin hide jalur tertentu, ubah via admin setelah upload

---

## 🆘 Troubleshooting

### Migration Error?
```bash
# Cek error detail
php artisan migrate --pretend

# Jika ada kolom yang sudah exist, rollback dulu
php artisan migrate:rollback --step=1
```

### Jalur Tidak Muncul di /daftar?
```bash
# 1. Clear cache
php artisan optimize:clear

# 2. Cek query
php artisan tinker
>>> \App\Models\JalurPendaftaran::tampilDiDaftar()->get()

# 3. Test page
https://pmbm.mtsn2kotamalang.sch.id/test-jalur-daftar
```

### Permission Error?
```bash
# Set permission yang benar
chmod -R 755 app/
chmod -R 755 database/
chmod -R 755 resources/
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/
```

---

## 📞 Support

Jika ada masalah:
1. Cek file `UPLOAD_TO_PRODUCTION.md` untuk panduan lengkap
2. Cek `CHECKLIST_UPLOAD_PRODUCTION.txt` untuk step-by-step
3. Cek error di `storage/logs/laravel.log`
4. Rollback jika perlu: `php artisan migrate:rollback --step=1`

---

## ✅ Verifikasi Sukses

Upload dianggap sukses jika:
- ✅ Migration berhasil tanpa error
- ✅ Kolom "Status Daftar" dan "Paling Populer" muncul di `/admin/jalur`
- ✅ Edit jalur berjalan normal
- ✅ Halaman `/daftar` menampilkan jalur sesuai "Status Daftar"
- ✅ Tidak ada error di browser console
- ✅ Tidak ada error di laravel.log

---

**Dibuat**: 23 Januari 2026
**Fitur**: Status Daftar + Paling Populer
**Total File**: 7 wajib + 2 opsional
**Estimasi Waktu Upload**: 10-15 menit

🎉 **Good Luck!**
