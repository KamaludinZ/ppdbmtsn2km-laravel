# Manual Deployment Guide - Cek Pendaftaran Fix
**Tanggal:** 24 Februari 2026
**Status:** ✅ Implementasi Selesai - Menunggu Upload

---

## ✅ Pekerjaan yang Sudah Selesai

### 1. Implementasi Fitur
- ✅ 6 status cards dengan warna dan icon yang jelas
- ✅ Conditional rendering (daftar ulang section hanya muncul jika lulus)
- ✅ Fix mapping status (Verif Pendaftaran tetap "Data Disetujui" saat lulus)
- ✅ Responsive design (mobile & desktop)
- ✅ Eager loading untuk performance

### 2. Files yang Sudah Diubah
- ✅ `app/Livewire/Public/CekPendaftaran.php`
- ✅ `resources/views/livewire/public/cek-pendaftaran.blade.php`

### 3. Testing Lokal
- ✅ Cache cleared (`php artisan view:clear && cache:clear`)
- ✅ Code review selesai
- ✅ Dokumentasi lengkap

---

## 📤 Upload ke Production (MANUAL)

**Catatan:** Automated upload mengalami issue koneksi SSH. Silakan upload manual menggunakan salah satu metode berikut:

### Metode 1: Via FileZilla / WinSCP

**Server Details:**
- Host: `103.30.244.77`
- Username: `mtsnkot4`
- Port: `22`
- Protocol: SFTP

**Files to Upload:**

1. **File 1:**
   - Local: `C:\ppdbmtsn2km-laravel\app\Livewire\Public\CekPendaftaran.php`
   - Remote: `/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Public/CekPendaftaran.php`

2. **File 2:**
   - Local: `C:\ppdbmtsn2km-laravel\resources\views\livewire\public\cek-pendaftaran.blade.php`
   - Remote: `/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/public/cek-pendaftaran.blade.php`

### Metode 2: Via Command Line (Manual SCP)

Jika koneksi SSH Anda stabil, jalankan command ini satu per satu:

```bash
# Upload File 1
scp "C:\ppdbmtsn2km-laravel\app\Livewire\Public\CekPendaftaran.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Public/

# Upload File 2
scp "C:\ppdbmtsn2km-laravel\resources\views\livewire\public\cek-pendaftaran.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/public/
```

### Metode 3: Via cPanel File Manager

1. Login ke cPanel: https://103.30.244.77:2083
2. Buka File Manager
3. Navigate ke:
   - `/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Public/`
   - Upload `CekPendaftaran.php`
4. Navigate ke:
   - `/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/public/`
   - Upload `cek-pendaftaran.blade.php`

---

## 🧹 Clear Cache di Production

**PENTING:** Setelah upload files, WAJIB clear cache!

### Via SSH:
```bash
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan view:clear && php artisan cache:clear && php artisan config:clear"
```

### Via Terminal/cPanel:
```bash
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
php artisan view:clear
php artisan cache:clear
php artisan config:clear
```

---

## 🧪 Testing di Production

### URL Production:
```
https://pmbm.mtsn2kotamalang.sch.id/cek-pendaftaran
```

### Test Scenarios:

**Test 1: Pendaftar Status Draft**
- Search dengan nomor pendaftaran yang status = 'draft'
- Expected:
  - ✅ 3 status cards muncul
  - ✅ Verif Pendaftaran: 🟦 "Draft"
  - ✅ Status Daftar Ulang section TIDAK muncul

**Test 2: Pendaftar Status Menunggu**
- Search dengan status = 'menunggu'
- Expected:
  - ✅ Verif Pendaftaran: 🟨 "Menunggu Verifikasi"
  - ✅ Verif Psikologi: 🟨 "Menunggu Verifikasi"
  - ✅ Status Daftar Ulang section TIDAK muncul

**Test 3: Pendaftar Status LULUS (CRITICAL TEST)**
- Search dengan status = 'lulus'
- Expected:
  - ✅ Verif Pendaftaran: 🟩 "Data Disetujui" (BUKAN "LULUS")
  - ✅ Verif Psikologi: 🟩 "Disetujui"
  - ✅ Kelulusan: 🟩 "LULUS"
  - ✅ Status Daftar Ulang section MUNCUL (3 cards)
  - ✅ DU Madrasah: status sesuai database
  - ✅ Ukuran Seragam: status sesuai database
  - ✅ DU Seragam: status sesuai database

**Test 4: Pendaftar Status Tidak Lulus**
- Search dengan status = 'tidak_lulus'
- Expected:
  - ✅ Verif Pendaftaran: 🟥 "Data Ditolak"
  - ✅ Kelulusan: 🟥 "Tidak Lulus"
  - ✅ Status Daftar Ulang section TIDAK muncul

**Test 5: Responsive Design**
- Buka di Mobile (< 768px)
  - ✅ Cards stacked (1 column)
- Buka di Desktop (> 768px)
  - ✅ Cards side-by-side (3 columns)

**Test 6: Browser Cache**
- Test di incognito/private mode
- Hard refresh: `Ctrl + Shift + R`
- Test di browser berbeda (Chrome, Firefox, Edge)

---

## 📊 What Changed (Summary)

### Before:
```
┌────────────────────────────────────────┐
│ Status Verifikasi Pendaftaran: LULUS  │ ❌ SALAH (duplikat)
│ Status Kelulusan: LULUS                │
└────────────────────────────────────────┘
```

### After:
```
┌────────────────────────────────────────┐
│ Status Verifikasi Pendaftaran:        │
│ Data Disetujui ✅ (jelas & berbeda)   │
│                                        │
│ Status Kelulusan: LULUS ✅             │
└────────────────────────────────────────┘
```

**Key Improvements:**
1. ✅ Separation of concerns (verifikasi vs kelulusan)
2. ✅ 6 status types dengan visual yang jelas
3. ✅ Conditional rendering untuk daftar ulang
4. ✅ Color coding (gray, yellow, green, red, orange, blue)
5. ✅ Icons untuk setiap status
6. ✅ Responsive design
7. ✅ Performance optimization (eager loading)

---

## 📁 Related Documentation

- `FIX_CEK_PENDAFTARAN_COMPREHENSIVE_STATUS_24_FEB_2026.md` - Dokumentasi lengkap fix
- `FIX_STATUS_VERIFIKASI_PENDAFTARAN_24_FEB_2026.md` - Dokumentasi fix status mapping
- `TESTING_GUIDE_CEK_PENDAFTARAN_24_FEB_2026.md` - Panduan testing lengkap
- `SUMMARY_PERBAIKAN_LENGKAP_24_FEB_2026.md` - Summary semua perubahan

---

## ⚠️ Troubleshooting

### Issue: Tampilan masih lama
**Solution:**
```bash
# Clear cache production
php artisan view:clear
php artisan cache:clear
php artisan config:clear

# Browser
Ctrl + Shift + R (hard refresh)
Test di incognito mode
```

### Issue: Error "Undefined property"
**Solution:** Pastikan eager loading sudah terupload dengan benar di `CekPendaftaran.php`

### Issue: Section Daftar Ulang tidak muncul untuk yang lulus
**Solution:** Cek di blade file ada conditional `@if($isLulus)`

---

## ✅ Deployment Checklist

- [ ] Upload `CekPendaftaran.php` ke production
- [ ] Upload `cek-pendaftaran.blade.php` ke production
- [ ] Clear cache di production
- [ ] Test dengan pendaftar status draft
- [ ] Test dengan pendaftar status menunggu
- [ ] Test dengan pendaftar status LULUS (critical)
- [ ] Test dengan pendaftar status tidak lulus
- [ ] Test responsive design (mobile & desktop)
- [ ] Test di multiple browsers
- [ ] Verify no console errors

---

**Status:** ✅ Ready for Deployment
**Breaking Changes:** Tidak ada
**Database Changes:** Tidak ada
**Migration Required:** Tidak

**Next Action:** Upload 2 files ke production dan clear cache.
