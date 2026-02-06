# FIX UPLOAD BERKAS LOGIC - COMPLETE

## 🎯 Tujuan Fix

Memperbaiki logic untuk step 3 (Upload Berkas) di progress dashboard pendaftar agar lebih akurat dalam menentukan status berdasarkan kondisi dokumen.

## 📋 Logic Baru Upload Berkas

### Status: **SELESAI** (Hijau) ✅
**Kondisi:**
- Semua dokumen **WAJIB** sudah diupload **DAN**
- Semua dokumen yang diupload (wajib + tidak wajib) sudah **DISETUJUI**

**Contoh Skenario:**
```
Dokumen Wajib:
- Rapor: ✅ Disetujui
- Akta Kelahiran: ✅ Disetujui
- Kartu Keluarga: ✅ Disetujui
- NISN: ✅ Disetujui
- Ijazah: ✅ Disetujui

Dokumen Tidak Wajib (jika diupload):
- Sertifikat: ✅ Disetujui
- KIP: ✅ Disetujui

Status Upload Berkas: SELESAI ✅
```

### Status: **PERBAIKI** (Merah) ❌
**Kondisi:**
- Ada **salah satu** dokumen yang statusnya **"tidak_sesuai"** atau **"perlu_perbaikan"**

**Contoh Skenario:**
```
Dokumen Wajib:
- Rapor: ✅ Disetujui
- Akta Kelahiran: ⚠️ Perlu Perbaikan (foto tidak jelas)
- Kartu Keluarga: ✅ Disetujui
- NISN: ✅ Disetujui
- Ijazah: ✅ Disetujui

Status Upload Berkas: PERBAIKI ❌
```

### Status: **MENUNGGU** (Kuning) ⏳
**Kondisi:**
- Semua dokumen **WAJIB** sudah diupload **DAN**
- Ada **salah satu** dokumen yang statusnya **"menunggu_verifikasi"**

**Contoh Skenario:**
```
Dokumen Wajib:
- Rapor: ✅ Disetujui
- Akta Kelahiran: ✅ Disetujui
- Kartu Keluarga: ⏳ Menunggu Verifikasi
- NISN: ✅ Disetujui
- Ijazah: ⏳ Menunggu Verifikasi

Dokumen Tidak Wajib:
- Sertifikat: ⏳ Menunggu Verifikasi

Status Upload Berkas: MENUNGGU ⏳
```

### Status: **LENGKAPI** (Kuning) 📝
**Kondisi:**
- Belum semua dokumen **WAJIB** diupload

**Contoh Skenario:**
```
Dokumen Wajib:
- Rapor: ✅ Diupload
- Akta Kelahiran: ✅ Diupload
- Kartu Keluarga: ❌ Belum diupload
- NISN: ✅ Diupload
- Ijazah: ❌ Belum diupload

Status Upload Berkas: LENGKAPI 📝
```

## ✅ Perubahan yang Dilakukan

### 1. Method Baru yang Ditambahkan

#### **areAllUploadedDocumentsApproved()**
```php
/**
 * Check if all uploaded documents (wajib + tidak wajib) are approved
 * Returns true only if ALL uploaded documents have status 'disetujui'
 */
private function areAllUploadedDocumentsApproved(): bool
```

**Fungsi:**
- Mengecek SEMUA dokumen yang diupload (wajib + tidak wajib)
- Return `true` hanya jika SEMUA dokumen memiliki status `'disetujui'`
- Return `false` jika ada dokumen yang belum disetujui

#### **hasAnyDocumentWaiting()**
```php
/**
 * Check if any uploaded document is waiting for verification (menunggu_verifikasi)
 */
private function hasAnyDocumentWaiting(): bool
```

**Fungsi:**
- Mengecek apakah ada dokumen yang statusnya `'menunggu_verifikasi'`
- Return `true` jika minimal ada 1 dokumen yang menunggu
- Return `false` jika tidak ada dokumen yang menunggu

### 2. Update Logic di calculateProgress()

**File:** `app/Livewire/Pendaftar/Dashboard.php:335-376`

**Sebelum:**
```php
// UPLOAD BERKAS STEP
$berkasComplete = $this->isUploadBerkasComplete();
$hasDocumentNeedsFix = $this->hasDocumentNeedsFix();
$allDocumentsWaiting = $this->areAllDocumentsWaitingVerification();

if ($isVerified) {
    // After verification approved
    $this->progress['upload_berkas'] = [
        'status' => 'completed',
        'label' => 'Selesai',
        'color' => 'emerald'
    ];
} elseif ($hasDocumentNeedsFix) {
    // Any document has "tidak_sesuai" or "perlu_perbaikan" status
    $this->progress['upload_berkas'] = [
        'status' => 'current',
        'label' => 'Perbaiki',
        'color' => 'red'
    ];
} elseif ($berkasComplete && $allDocumentsWaiting) {
    // All required documents uploaded and have "menunggu_verifikasi" status
    $this->progress['upload_berkas'] = [
        'status' => 'current',
        'label' => 'Menunggu',
        'color' => 'yellow'
    ];
} else {
    // Incomplete - not all documents uploaded
    $this->progress['upload_berkas'] = [
        'status' => 'current',
        'label' => 'Lengkapi',
        'color' => 'yellow'
    ];
}
```

**Sesudah:**
```php
// UPLOAD BERKAS STEP
$berkasComplete = $this->isUploadBerkasComplete();
$hasDocumentNeedsFix = $this->hasDocumentNeedsFix();
$allUploadedDocsApproved = $this->areAllUploadedDocumentsApproved();
$hasDocumentWaiting = $this->hasAnyDocumentWaiting();

if ($isVerified) {
    // After verification approved (lulus/tidak_lulus/cadangan)
    $this->progress['upload_berkas'] = [
        'status' => 'completed',
        'label' => 'Selesai',
        'color' => 'emerald'
    ];
} elseif ($hasDocumentNeedsFix) {
    // Any document has "tidak_sesuai" or "perlu_perbaikan" status
    $this->progress['upload_berkas'] = [
        'status' => 'current',
        'label' => 'Perbaiki',
        'color' => 'red'
    ];
} elseif ($berkasComplete && $allUploadedDocsApproved) {
    // All required documents uploaded AND all uploaded documents (wajib + tidak wajib) are approved
    $this->progress['upload_berkas'] = [
        'status' => 'completed',
        'label' => 'Selesai',
        'color' => 'emerald'
    ];
} elseif ($berkasComplete && $hasDocumentWaiting) {
    // All required documents uploaded but at least one is still waiting for verification
    $this->progress['upload_berkas'] = [
        'status' => 'current',
        'label' => 'Menunggu',
        'color' => 'yellow'
    ];
} else {
    // Incomplete - not all required documents uploaded
    $this->progress['upload_berkas'] = [
        'status' => 'current',
        'label' => 'Lengkapi',
        'color' => 'yellow'
    ];
}
```

## 🔄 Perbedaan Utama

### Logic Lama:
- Status **"Selesai"** hanya muncul jika `$isVerified = true` (sudah lulus/tidak_lulus/cadangan)
- Status **"Menunggu"** muncul jika semua dokumen wajib sudah upload dan statusnya `menunggu_verifikasi` **ATAU** `disetujui`
- **MASALAH:** Tidak ada kondisi untuk status "Selesai" sebelum tahap ujian

### Logic Baru:
- Status **"Selesai"** muncul jika:
  - Sudah lulus/tidak_lulus/cadangan **ATAU**
  - Semua dokumen wajib sudah upload **DAN** semua dokumen yang diupload sudah disetujui
- Status **"Menunggu"** muncul jika:
  - Semua dokumen wajib sudah upload **DAN** ada dokumen yang masih menunggu verifikasi
- **FIXED:** Ada kondisi "Selesai" sebelum tahap ujian (ketika semua dokumen disetujui)

## 🚀 Cara Upload ke Production

Jalankan batch file:
```cmd
upload_fix_upload_berkas_logic.bat
```

Batch file ini akan:
1. ✅ Upload `app/Livewire/Pendaftar/Dashboard.php`
2. ✅ Clear semua cache
3. ✅ Optimize aplikasi

## 🧪 Testing Checklist

### Test 1: Status SELESAI
**Setup:**
1. Login sebagai pendaftar
2. Upload semua dokumen wajib
3. Admin/Panitia setujui SEMUA dokumen (wajib + tidak wajib yang diupload)

**Expected Result:**
- ✅ Progress Upload Berkas = **SELESAI** (Hijau)
- ✅ Progress Verifikasi berubah dari "Terkunci" menjadi "Menunggu" atau "Selesai" (tergantung formulir & tes psikologi)

### Test 2: Status MENUNGGU
**Setup:**
1. Login sebagai pendaftar
2. Upload semua dokumen wajib
3. Admin/Panitia biarkan salah satu dokumen dengan status "menunggu_verifikasi"

**Expected Result:**
- ⏳ Progress Upload Berkas = **MENUNGGU** (Kuning)
- 🔒 Progress Verifikasi = "Menunggu" (jika formulir & tes psikologi juga menunggu)

### Test 3: Status PERBAIKI
**Setup:**
1. Login sebagai pendaftar
2. Upload semua dokumen wajib
3. Admin/Panitia set salah satu dokumen dengan status "perlu_perbaikan" atau "tidak_sesuai"

**Expected Result:**
- ❌ Progress Upload Berkas = **PERBAIKI** (Merah)
- ❌ Progress Verifikasi = "Perbaiki" (jika ada yang perlu diperbaiki)

### Test 4: Status LENGKAPI
**Setup:**
1. Login sebagai pendaftar
2. Upload hanya sebagian dokumen wajib (tidak lengkap)

**Expected Result:**
- 📝 Progress Upload Berkas = **LENGKAPI** (Kuning)
- 🔒 Progress Verifikasi = "Terkunci"

### Test 5: Dokumen Tidak Wajib
**Setup:**
1. Login sebagai pendaftar
2. Upload semua dokumen wajib + dokumen tidak wajib (sertifikat, KIP, dll)
3. Admin/Panitia:
   - Setujui semua dokumen wajib
   - Biarkan dokumen tidak wajib "menunggu_verifikasi"

**Expected Result:**
- ⏳ Progress Upload Berkas = **MENUNGGU** (Kuning)
- Karena ada dokumen tidak wajib yang masih menunggu

### Test 6: Semua Dokumen Disetujui (Wajib + Tidak Wajib)
**Setup:**
1. Login sebagai pendaftar
2. Upload semua dokumen wajib + dokumen tidak wajib
3. Admin/Panitia setujui SEMUA dokumen

**Expected Result:**
- ✅ Progress Upload Berkas = **SELESAI** (Hijau)
- ✅ Jika formulir & tes psikologi juga selesai, Progress Verifikasi = "Selesai"

## 📊 Flowchart Logic

```
┌─────────────────────────────────────────┐
│  Cek Status Upload Berkas (Step 3)     │
└─────────────────┬───────────────────────┘
                  │
                  ▼
        ┌─────────────────────┐
        │ $isVerified = true? │ (lulus/tidak_lulus/cadangan)
        └─────────┬───────────┘
                  │
         ┌────────┴────────┐
         │ YA              │ TIDAK
         ▼                 ▼
    ┌─────────┐      ┌──────────────────────┐
    │ SELESAI │      │ Ada dokumen perlu    │
    └─────────┘      │ perbaiki?            │
                     └──────┬───────────────┘
                            │
                   ┌────────┴────────┐
                   │ YA              │ TIDAK
                   ▼                 ▼
              ┌─────────┐      ┌──────────────────────┐
              │ PERBAIKI│      │ Semua wajib upload   │
              └─────────┘      │ + semua disetujui?   │
                               └──────┬───────────────┘
                                      │
                             ┌────────┴────────┐
                             │ YA              │ TIDAK
                             ▼                 ▼
                        ┌─────────┐      ┌──────────────────────┐
                        │ SELESAI │      │ Semua wajib upload   │
                        └─────────┘      │ + ada yang menunggu? │
                                         └──────┬───────────────┘
                                                │
                                       ┌────────┴────────┐
                                       │ YA              │ TIDAK
                                       ▼                 ▼
                                  ┌─────────┐      ┌─────────┐
                                  │ MENUNGGU│      │ LENGKAPI│
                                  └─────────┘      └─────────┘
```

## 🔍 Verifikasi di Production

### Cek File Terupload:
```bash
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

# Cek file Dashboard.php
ls -lh app/Livewire/Pendaftar/Dashboard.php

# Cek method baru ada
grep -n "areAllUploadedDocumentsApproved" app/Livewire/Pendaftar/Dashboard.php
grep -n "hasAnyDocumentWaiting" app/Livewire/Pendaftar/Dashboard.php
```

### Cek Error di Logs:
```bash
tail -50 storage/logs/laravel.log | grep "Dashboard"
```

## 📝 Catatan Penting

1. **Dokumen Wajib vs Tidak Wajib:**
   - Dokumen wajib: `wajib => true` di `$documentLabels`
   - Dokumen tidak wajib: `wajib => false` di `$documentLabels`
   - Dokumen conditional: Dokumen tidak wajib yang jadi wajib jika field tertentu diisi (KIP, PKH, KKS)

2. **Status Verifikasi Dokumen:**
   - `menunggu_verifikasi`: Baru diupload, belum dicek admin/panitia
   - `disetujui`: Sudah dicek dan disetujui
   - `tidak_sesuai`: Dokumen ditolak (harus upload ulang)
   - `perlu_perbaikan`: Dokumen perlu diperbaiki (harus upload ulang)

3. **Impact ke Verifikasi Step:**
   - Jika Upload Berkas = "Selesai", maka Verifikasi Step bisa menjadi "Selesai" (jika formulir & tes psikologi juga selesai)
   - Jika Upload Berkas = "Menunggu", maka Verifikasi Step menjadi "Menunggu"
   - Jika Upload Berkas = "Perbaiki", maka Verifikasi Step menjadi "Perbaiki"
   - Jika Upload Berkas = "Lengkapi", maka Verifikasi Step menjadi "Terkunci"

## ✨ Summary

- ✅ Logic Upload Berkas diperbaiki untuk lebih akurat
- ✅ Status "Selesai" bisa muncul sebelum tahap ujian
- ✅ Dokumen tidak wajib juga diperhitungkan dalam status
- ✅ Added 2 new helper methods untuk check status dokumen
- ✅ Impact positif ke step Verifikasi

---
**Fixed by:** Claude Code
**Date:** 6 Februari 2026
**Files Changed:** 1 file
**Lines Added:** ~60 lines
**Impact:** Improvement - Better document status tracking
