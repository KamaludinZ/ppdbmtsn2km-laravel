# Upload Manual - 3 Februari 2026 (LENGKAP - ADMIN & PANITIA)

## 🎯 RINGKASAN SINGKAT

**Total Fixes:** 7 issues
**Files Modified:** 7 files (5 Admin + 2 Panitia)
**Status:** ✅ READY TO UPLOAD

---

## 📁 FILE YANG HARUS DIUPLOAD

### 1️⃣ CONTROLLERS (2 files)

#### File 1: Admin Controller
```
app/Livewire/Admin/DetailPendaftar.php
```

**Changes:**
- ✅ Fix Tab Prestasi exit edit mode (line 1555, 2426)
- ✅ Remove Tab Alamat validation from Tab Siswa (line 1089-1092, 1130-1133)

#### File 2: Panitia Controller ⭐ NEW
```
app/Livewire/Panitia/DetailPendaftar.php
```

**Changes:**
- ✅ Fix Tab Prestasi exit edit mode (line 555)

**Upload Commands:**
```bash
scp app/Livewire/Admin/DetailPendaftar.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/

scp app/Livewire/Panitia/DetailPendaftar.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Panitia/
```

---

### 2️⃣ TAB TEMPLATES (3 files) - SHARED ANTARA ADMIN & PANITIA

#### File 1: tab-data-orangtua.blade.php
```
resources/views/tab_templates/tab-data-orangtua.blade.php
```

**Changes:**
- ✅ Fix property name: `$editModeOrangTua` → `$editModeOrangtua` (line 4)

---

#### File 2: tab-data-siswa.blade.php
```
resources/views/tab_templates/tab-data-siswa.blade.php
```

**Changes:**
- ✅ Add validation error summary box (line 43-65)
- ✅ Remove `uppercase` class from Nama Lengkap (line 105)
- ✅ Update placeholder & helper text

---

#### File 3: tab-survey.blade.php
```
resources/views/tab_templates/tab-survey.blade.php
```

**Changes:**
- ✅ Add role check (line 2-12)
- ✅ Conditional buttons: Admin/Panitia = "Mode Lihat", Pendaftar = "Edit Data"
- ✅ Prevent MethodNotFoundException

**Upload Commands:**
```bash
scp resources/views/tab_templates/tab-data-orangtua.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/tab_templates/

scp resources/views/tab_templates/tab-data-siswa.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/tab_templates/

scp resources/views/tab_templates/tab-survey.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/tab_templates/
```

---

### 3️⃣ ADMIN & PANITIA VIEWS (2 files)

#### File 1: Admin Detail Pendaftar
```
resources/views/livewire/admin/detail-pendaftar.blade.php
```

**Changes:**
- ✅ Fix loading state per button in Tab Dokumen (line 957-977)
- ✅ Specific `wire:target` with document key parameter

---

#### File 2: Panitia Detail Pendaftar ⭐ NEW
```
resources/views/livewire/panitia/detail-pendaftar.blade.php
```

**Changes:**
- ✅ Fix loading state per button in Tab Dokumen (line 960, 963, 969)
- ✅ Specific `wire:target` with document key parameter

**Upload Commands:**
```bash
scp resources/views/livewire/admin/detail-pendaftar.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/

scp resources/views/livewire/panitia/detail-pendaftar.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/panitia/
```

---

## 🚀 UPLOAD SEQUENCE (COPY & PASTE)

### Step 1: Upload Semua Files
```bash
cd C:\ppdbmtsn2km-laravel

# Controllers (2 files)
scp app/Livewire/Admin/DetailPendaftar.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/

scp app/Livewire/Panitia/DetailPendaftar.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Panitia/

# Tab Templates (3 files - shared)
scp resources/views/tab_templates/tab-data-orangtua.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/tab_templates/

scp resources/views/tab_templates/tab-data-siswa.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/tab_templates/

scp resources/views/tab_templates/tab-survey.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/tab_templates/

# Admin & Panitia Views (2 files)
scp resources/views/livewire/admin/detail-pendaftar.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/

scp resources/views/livewire/panitia/detail-pendaftar.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/panitia/
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
- [ ] Upload Admin controller (1 file)
- [ ] Upload Panitia controller (1 file) ⭐
- [ ] Upload tab templates (3 files - shared)
- [ ] Upload Admin view (1 file)
- [ ] Upload Panitia view (1 file) ⭐
- [ ] Clear view cache
- [ ] Clear optimize cache
- [ ] Delete compiled views

### Post-Upload:
- [ ] Hard refresh browser (Ctrl + Shift + R)
- [ ] Test Admin tabs
- [ ] Test Panitia tabs ⭐

---

## 🧪 TESTING CHECKLIST

### ADMIN - Test All Tabs

**URL Base:** `https://pmbm.mtsn2kotamalang.sch.id/admin/pendaftar/27`

#### Tab Prestasi (`?activeTab=prestasi`)
- [ ] Klik "Edit Data" → Masuk edit mode
- [ ] Klik "Simpan" → **KELUAR dari edit mode** ✅

#### Tab Orangtua (`?activeTab=orangtua`)
- [ ] Klik "Edit Data" → **MASUK edit mode** ✅
- [ ] Klik "Simpan" → Keluar dari edit mode ✅

#### Tab Siswa (`?activeTab=siswa`)
- [ ] Klik "Edit Data" → Masuk edit mode
- [ ] Ada validation error → **Tampil summary box di atas** ✅
- [ ] Field "Nama Lengkap" tampil **proper case** (bukan UPPERCASE) ✅
- [ ] **TIDAK ada error** tentang "waktu tempuh" ✅
- [ ] Data bisa disimpan

#### Tab Survey (`?activeTab=survey`)
- [ ] Badge **"Mode Lihat"** tampil ✅
- [ ] **TIDAK ada tombol "Edit Data"** ✅
- [ ] **TIDAK ada error** MethodNotFoundException ✅

#### Tab Dokumen (`?activeTab=dokumen`)
- [ ] Klik "Simpan Status" dokumen KK → **Hanya button KK** loading ✅
- [ ] Button dokumen lain (Akta, Ijazah, dll) tetap normal ✅
- [ ] Klik "Simpan Status" dokumen Akta → **Hanya button Akta** loading ✅

---

### PANITIA - Test All Tabs ⭐

**URL Base:** `https://pmbm.mtsn2kotamalang.sch.id/panitia/pendaftar/27`

#### Tab Prestasi (`?activeTab=prestasi`)
- [ ] Klik "Edit Data" → Masuk edit mode
- [ ] Klik "Simpan" → **KELUAR dari edit mode** ✅

#### Tab Orangtua (`?activeTab=orangtua`)
- [ ] Klik "Edit Data" → **MASUK edit mode** ✅
- [ ] Klik "Simpan" → Keluar dari edit mode ✅

#### Tab Siswa (`?activeTab=siswa`)
- [ ] Klik "Edit Data" → Masuk edit mode
- [ ] Ada validation error → **Tampil summary box di atas** ✅
- [ ] Field "Nama Lengkap" tampil **proper case** (bukan UPPERCASE) ✅
- [ ] Data bisa disimpan

#### Tab Survey (`?activeTab=survey`)
- [ ] Badge **"Mode Lihat"** tampil ✅
- [ ] **TIDAK ada tombol "Edit Data"** ✅
- [ ] **TIDAK ada error** MethodNotFoundException ✅

#### Tab Dokumen (`?activeTab=dokumen`)
- [ ] Klik "Simpan Status" dokumen KK → **Hanya button KK** loading ✅
- [ ] Button dokumen lain tetap normal ✅

---

## 📋 SUMMARY ISSUES FIXED

| # | Tab | Issue | Admin | Panitia |
|---|-----|-------|-------|---------|
| 1 | Prestasi | Tidak keluar dari edit mode setelah simpan | ✅ | ✅ |
| 2 | Orangtua | Tidak bisa masuk edit mode | ✅ | ✅ |
| 3 | Siswa | Validation errors tidak terlihat | ✅ | ✅ |
| 4 | Siswa | Error "waktu tempuh" dari Tab Alamat | ✅ | N/A |
| 5 | Siswa | Nama lengkap tampil UPPERCASE | ✅ | ✅ |
| 6 | Survey | MethodNotFoundException | ✅ | ✅ |
| 7 | Dokumen | Loading state di semua tombol | ✅ | ✅ |

**Note:** Issue #4 (waktu tempuh validation) hanya ada di Admin controller, Panitia tidak punya issue ini.

---

## 🎯 QUICK REFERENCE

**7 Files Total:**
1. ✅ `app/Livewire/Admin/DetailPendaftar.php`
2. ✅ `app/Livewire/Panitia/DetailPendaftar.php` ⭐
3. ✅ `resources/views/tab_templates/tab-data-orangtua.blade.php`
4. ✅ `resources/views/tab_templates/tab-data-siswa.blade.php`
5. ✅ `resources/views/tab_templates/tab-survey.blade.php`
6. ✅ `resources/views/livewire/admin/detail-pendaftar.blade.php`
7. ✅ `resources/views/livewire/panitia/detail-pendaftar.blade.php` ⭐

**Tab Templates Shared:** Tab Orangtua, Tab Siswa, Tab Survey (fixes berlaku untuk Admin & Panitia)

**After Upload:**
- Clear cache: `php artisan view:clear && php artisan optimize:clear`
- Delete compiled: `rm -rf storage/framework/views/*.php`
- Hard refresh: `Ctrl + Shift + R`

---

## 📊 COMPARISON: ADMIN vs PANITIA

| Component | Admin | Panitia | Note |
|-----------|-------|---------|------|
| **Controllers** | DetailPendaftar.php | DetailPendaftar.php | Separate files |
| **Tab Templates** | Shared | Shared | Same files untuk kedua role |
| **Detail Views** | admin/detail-pendaftar | panitia/detail-pendaftar | Separate files |
| **Fixes Applied** | 7 issues | 6 issues | Panitia tidak punya waktu_tempuh issue |

---

**Prepared:** 3 Februari 2026
**Ready to Deploy:** ✅ YES (Admin & Panitia)
