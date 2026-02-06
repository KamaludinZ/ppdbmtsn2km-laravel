# Upload Manual - 3 Februari 2026

## 🎯 RINGKASAN SINGKAT

**Total Fixes:** 7 issues
**Files Modified:** 5 files
**Status:** ✅ READY TO UPLOAD

---

## 📁 FILE YANG HARUS DIUPLOAD

### 1️⃣ CONTROLLER (1 file)

```
app/Livewire/Admin/DetailPendaftar.php
```

**Changes:**
- ✅ Fix Tab Prestasi exit edit mode (line 1555, 2426)
- ✅ Remove Tab Alamat validation from Tab Siswa (line 1089-1092, 1130-1133)

**Upload Command:**
```bash
scp app/Livewire/Admin/DetailPendaftar.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/
```

---

### 2️⃣ TAB TEMPLATES (3 files)

#### File 1: tab-data-orangtua.blade.php
```
resources/views/tab_templates/tab-data-orangtua.blade.php
```

**Changes:**
- ✅ Fix property name: `$editModeOrangTua` → `$editModeOrangtua` (line 4)

**Upload Command:**
```bash
scp resources/views/tab_templates/tab-data-orangtua.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/tab_templates/
```

---

#### File 2: tab-data-siswa.blade.php
```
resources/views/tab_templates/tab-data-siswa.blade.php
```

**Changes:**
- ✅ Add validation error summary box (line 43-65)
- ✅ Remove `uppercase` class from Nama Lengkap (line 105)
- ✅ Update placeholder & helper text

**Upload Command:**
```bash
scp resources/views/tab_templates/tab-data-siswa.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/tab_templates/
```

---

#### File 3: tab-survey.blade.php
```
resources/views/tab_templates/tab-survey.blade.php
```

**Changes:**
- ✅ Add role check (line 2-12)
- ✅ Conditional buttons: Admin/Panitia = "Mode Lihat", Pendaftar = "Edit Data"
- ✅ Prevent MethodNotFoundException

**Upload Command:**
```bash
scp resources/views/tab_templates/tab-survey.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/tab_templates/
```

---

### 3️⃣ ADMIN VIEW (1 file)

```
resources/views/livewire/admin/detail-pendaftar.blade.php
```

**Changes:**
- ✅ Fix loading state per button in Tab Dokumen (line 957-977)
- ✅ Specific `wire:target` with document key parameter

**Upload Command:**
```bash
scp resources/views/livewire/admin/detail-pendaftar.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/
```

---

## 🚀 UPLOAD SEQUENCE (COPY & PASTE)

### Step 1: Upload Semua Files
```bash
cd C:\ppdbmtsn2km-laravel

# Controller
scp app/Livewire/Admin/DetailPendaftar.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/

# Tab Templates
scp resources/views/tab_templates/tab-data-orangtua.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/tab_templates/

scp resources/views/tab_templates/tab-data-siswa.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/tab_templates/

scp resources/views/tab_templates/tab-survey.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/tab_templates/

# Admin View
scp resources/views/livewire/admin/detail-pendaftar.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/
```

### Step 2: Clear Cache
```bash
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan view:clear && php artisan optimize:clear && rm -rf storage/framework/views/*.php"
```

---

## ✅ CHECKLIST UPLOAD

### Pre-Upload:
- [ ] Backup production files (optional)
- [ ] Pastikan koneksi SSH aktif
- [ ] Working directory: `C:\ppdbmtsn2km-laravel`

### Upload Process:
- [ ] Upload controller (1 file)
- [ ] Upload tab templates (3 files)
- [ ] Upload admin view (1 file)
- [ ] Clear view cache
- [ ] Clear optimize cache
- [ ] Delete compiled views

### Post-Upload:
- [ ] Hard refresh browser (Ctrl + Shift + R)
- [ ] Test setiap tab (checklist di bawah)

---

## 🧪 TESTING CHECKLIST

**URL Base:** `https://pmbm.mtsn2kotamalang.sch.id/admin/pendaftar/27`

### Tab Prestasi (`?activeTab=prestasi`)
- [ ] Klik "Edit Data" → Masuk edit mode
- [ ] Klik "Simpan" → **KELUAR dari edit mode** ✅

### Tab Orangtua (`?activeTab=orangtua`)
- [ ] Klik "Edit Data" → **MASUK edit mode** ✅
- [ ] Klik "Simpan" → Keluar dari edit mode ✅

### Tab Siswa (`?activeTab=siswa`)
- [ ] Klik "Edit Data" → Masuk edit mode
- [ ] Ada validation error → **Tampil summary box di atas** ✅
- [ ] Field "Nama Lengkap" tampil **proper case** (bukan UPPERCASE) ✅
- [ ] **TIDAK ada error** tentang "waktu tempuh" ✅
- [ ] Data bisa disimpan

### Tab Survey (`?activeTab=survey`)
- [ ] Login as Admin → Badge **"Mode Lihat"** tampil ✅
- [ ] Login as Admin → **TIDAK ada tombol "Edit Data"** ✅
- [ ] Login as Panitia → Badge "Mode Lihat" tampil ✅
- [ ] **TIDAK ada error** MethodNotFoundException ✅

### Tab Dokumen (`?activeTab=dokumen`)
- [ ] Klik "Simpan Status" dokumen KK → **Hanya button KK** loading ✅
- [ ] Button dokumen lain (Akta, Ijazah, dll) tetap normal ✅
- [ ] Klik "Simpan Status" dokumen Akta → **Hanya button Akta** loading ✅

---

## 📋 SUMMARY ISSUES FIXED

| # | Tab | Issue | Status |
|---|-----|-------|--------|
| 1 | Prestasi | Tidak keluar dari edit mode setelah simpan | ✅ FIXED |
| 2 | Orangtua | Tidak bisa masuk edit mode | ✅ FIXED |
| 3 | Siswa | Validation errors tidak terlihat | ✅ FIXED |
| 4 | Siswa | Error "waktu tempuh" dari Tab Alamat | ✅ FIXED |
| 5 | Siswa | Nama lengkap tampil UPPERCASE | ✅ FIXED |
| 6 | Survey | MethodNotFoundException untuk Admin/Panitia | ✅ FIXED |
| 7 | Dokumen | Loading state di semua tombol | ✅ FIXED |

---

## 🎯 QUICK REFERENCE

**5 Files Total:**
1. ✅ `app/Livewire/Admin/DetailPendaftar.php`
2. ✅ `resources/views/tab_templates/tab-data-orangtua.blade.php`
3. ✅ `resources/views/tab_templates/tab-data-siswa.blade.php`
4. ✅ `resources/views/tab_templates/tab-survey.blade.php`
5. ✅ `resources/views/livewire/admin/detail-pendaftar.blade.php`

**After Upload:**
- Clear cache: `php artisan view:clear && php artisan optimize:clear`
- Delete compiled: `rm -rf storage/framework/views/*.php`
- Hard refresh: `Ctrl + Shift + R`

---

**Prepared:** 3 Februari 2026
**Ready to Deploy:** ✅ YES
