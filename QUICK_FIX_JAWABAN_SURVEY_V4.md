# QUICK FIX - JAWABAN SURVEY V4 📋

## Status: ⚠️ SSH Down - Upload Manual Required

---

## 🎯 Apa yang Diperbaiki?

**Problem:**
```
[VALUE NOT FOUND: Sangat Tidak Setuju]
[VALUE NOT FOUND: Sangat Tidak Setuju]
Sangat Tidak Setuju
Sangat Tidak Setuju
```

**Root Cause:**
- Database menyimpan **LABEL** ("Sangat Tidak Setuju")
- Bukan **VALUE** ("1", "2", "3", "4")
- Method 1 & 2 hanya cari VALUE → Gagal
- Method 3 perlu enhancement

**Solution V4:**
- Enhanced Method 3: Case-insensitive label matching
- Added Method 4: Partial/fuzzy matching untuk typo

---

## 📁 File yang Perlu Diupload

### 1. Admin/TesPsikologi.php
```
Local:      C:\ppdbmtsn2km-laravel\app\Livewire\Admin\TesPsikologi.php
Production: /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/TesPsikologi.php
```

### 2. Panitia/TesPsikologi.php
```
Local:      C:\ppdbmtsn2km-laravel\app\Livewire\Panitia\TesPsikologi.php
Production: /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Panitia/TesPsikologi.php
```

---

## 🚀 CARA UPLOAD (3 Langkah)

### Step 1: Upload Files via cPanel

1. Login cPanel → File Manager
2. Navigate: `/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/`
3. Upload 2 files:
   - Ke folder `Admin/` → upload `Admin/TesPsikologi.php`
   - Ke folder `Panitia/` → upload `Panitia/TesPsikologi.php`
4. **Overwrite** file yang ada

### Step 2: Clear Cache

Via cPanel Terminal, jalankan:
```bash
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
php artisan optimize:clear
php artisan view:clear
php artisan config:clear
```

### Step 3: Test

1. Buka browser **Incognito mode**
2. Login sebagai **Admin**
3. Buka: `/admin/tes-psikologi?mainTab=jawaban-survey`
4. Hard Refresh: **Ctrl+F5**

---

## ✅ Expected Result

Setiap pertanyaan harus tampil dengan LABEL yang **BERBEDA**:

```
| Pertanyaan 2 | Pertanyaan 3      | Pertanyaan 5         |
|--------------|-------------------|----------------------|
| Ya           | Guru              | Setuju               |
| Ya           | Sosial Media      | Sangat Tidak Setuju  |
| Ya           | Kepala Sekolah    | Sangat Setuju        |
```

**BUKAN semua "Sangat Tidak Setuju"!**

---

## 🔍 Quick Troubleshooting

### Jika masih `[VALUE NOT FOUND: xxx]`

**Check 1:** File terupload benar?
```
Buka file production → Cari: "// Method 4: Partial match by label"
Harus ada!
```

**Check 2:** Cache sudah clear?
```bash
php artisan optimize:clear
```

**Check 3:** Browser cache?
```
Hard Refresh: Ctrl+F5
Atau pakai Incognito mode
```

**Check 4:** Data mismatch?
Jalankan diagnostic:
```bash
C:\ppdbmtsn2km-laravel\check_jawaban_database.bat
```

---

## 📞 Jika Masih Error

Report dengan info:
1. Screenshot hasil di browser
2. Output dari diagnostic script
3. Log error (jika ada)

---

## 📄 Full Documentation

Lihat dokumentasi lengkap di:
```
UPLOAD_MANUAL_FIX_JAWABAN_SURVEY_V4.md
```

---

**Good luck! 🚀**
