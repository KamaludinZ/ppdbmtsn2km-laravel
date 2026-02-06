# QUICK GUIDE - OPTION ID FIX 🚀

## Masalah yang Diperbaiki

❌ **Sebelum:**
- Semua jawaban muncul sama / `[VALUE NOT FOUND: xxx]`
- VALUE sama antar pertanyaan → ambiguity

✅ **Sesudah:**
- Setiap pertanyaan tampil label yang **BERBEDA** dan **SESUAI**
- Sistem menggunakan OPTION ID (unique reference)

---

## File yang Perlu Diupload (4 files)

### 1. View Files (2 files)
```
resources/views/tab_templates/tab-survey.blade.php
resources/views/livewire/pendaftar/tes-psikologi.blade.php
```

### 2. Logic Files (2 files)
```
app/Livewire/Admin/TesPsikologi.php
app/Livewire/Panitia/TesPsikologi.php
```

---

## Upload via cPanel (3 Steps)

### Step 1: Upload Files

1. Login cPanel → File Manager
2. Navigate ke folder tujuan
3. Upload & overwrite 4 files di atas

**Folder tujuan:**
- `resources/views/tab_templates/` → upload `tab-survey.blade.php`
- `resources/views/livewire/pendaftar/` → upload `tes-psikologi.blade.php`
- `app/Livewire/Admin/` → upload `TesPsikologi.php`
- `app/Livewire/Panitia/` → upload `TesPsikologi.php`

### Step 2: Clear Cache

Via cPanel Terminal:
```bash
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
php artisan optimize:clear
php artisan view:clear
php artisan config:clear
```

### Step 3: Test

1. Buka **Incognito mode**
2. Login sebagai **Pendaftar**
3. **Submit survey baru**
4. Login sebagai **Admin**
5. Buka `/admin/tes-psikologi?mainTab=jawaban-survey`
6. Hard Refresh: **Ctrl+F5**

---

## Expected Result ✅

Setiap pertanyaan harus tampil label yang **BERBEDA**:

```
| Pertanyaan 2 | Pertanyaan 3      | Pertanyaan 5         |
|--------------|-------------------|----------------------|
| Ya           | Guru              | Setuju               |
| Ya           | Sosial Media      | Sangat Tidak Setuju  |
| Ya           | Kepala Sekolah    | Sangat Setuju        |
```

**BUKAN semua "Sangat Tidak Setuju"!**

---

## Troubleshooting

### Jika masih error:

1. **Verifikasi upload:** Cek file production punya comment `ARCHITECTURAL FIX`
2. **Clear cache lagi:** `php artisan optimize:clear`
3. **Hard refresh browser:** `Ctrl+F5` atau Incognito mode
4. **Check log:** `tail -50 storage/logs/laravel.log | grep Survey`

---

## What's Different?

### OLD System (Buggy):
```html
<input value="{{ $option->value }}">  <!-- VALUE bisa sama! -->
```

### NEW System (Fixed):
```html
<input value="{{ $option->id }}">  <!-- ID unik! -->
```

**Result:**
- Data baru: Simpan Option ID → Lookup by ID (fast & accurate) ✅
- Data lama: Fallback ke VALUE/LABEL matching (backward compatible) ✅

---

📄 **Full Documentation:** `ARCHITECTURAL_FIX_OPTION_ID_SYSTEM.md`

Good luck! 🎯
