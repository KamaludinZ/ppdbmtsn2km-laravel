# QUICK GUIDE: STEP-BY-STEP FORM

**Tanggal:** 5 Februari 2026

---

## ✅ APA YANG SUDAH DILAKUKAN?

### 1. Implementasi Tampilan Bertahap (Progressive Disclosure)

Form daftar ulang sekarang ditampilkan **bertahap** dengan urutan:

```
┌─────────────────────────────────────────┐
│ ① Upload Surat Pernyataan               │  ← SELALU MUNCUL
│   - Download template                   │
│   - Upload file PDF                     │
│   - Tombol "Upload"                     │
└─────────────────────────────────────────┘
              ↓ (setelah upload)
┌─────────────────────────────────────────┐
│ ② Pilih Nominal Biaya                   │  ← MUNCUL SETELAH STEP 1
│   - Radio buttons nominal               │
│   - Opsi Lainnya (optional)            │
└─────────────────────────────────────────┘
              ↓ (setelah pilih)
┌─────────────────────────────────────────┐
│ ③ Konfirmasi Nominal yang Dibayarkan   │  ← MUNCUL SETELAH STEP 2
│   - Radio buttons konfirmasi            │
│   - Opsi Lainnya (optional)            │
└─────────────────────────────────────────┘
              ↓ (setelah konfirmasi)
┌─────────────────────────────────────────┐
│ ④ Detail Pembayaran                     │  ← MUNCUL SETELAH STEP 3
│   - Pilih Opsi Pembayaran               │
│   - Pilih Bank/Metode                   │
│   - Upload Bukti Pembayaran             │
│   - Input Nama Pengirim                 │
└─────────────────────────────────────────┘
              ↓ (setelah lengkap)
┌─────────────────────────────────────────┐
│         Tombol "Simpan Data"            │
└─────────────────────────────────────────┘
```

---

## 🎯 MANFAAT

### User Experience:
- ✅ Tidak overwhelmed dengan banyak form sekaligus
- ✅ Proses yang jelas dan terstruktur
- ✅ Fokus pada satu task per waktu
- ✅ Lebih mudah dipahami

### Visual Design:
- ✅ Badge dengan nomor step (1, 2, 3, 4)
- ✅ Warna berbeda untuk setiap step
- ✅ Clear visual hierarchy

---

## 🔧 FIX YANG DILAKUKAN

### 1. Upload Button Behavior

**BEFORE (SALAH):**
```
User klik "Upload" → File terupload + Status berubah ke "menunggu"
```

**AFTER (BENAR):**
```
User klik "Upload" → File terupload saja
User klik "Simpan Data" → Status berubah ke "menunggu"
```

### 2. Methods yang Diperbaiki

**File:** `app/Livewire/Pendaftar/DaftarUlang.php`

#### Method `uploadBuktiPembayaran()`:
```php
// BEFORE
$this->daftarUlang->update([
    'bukti_pembayaran_madrasah' => $path,
    'waktu_pembayaran_madrasah' => now(),
    'status' => 'menunggu',  // ❌ SALAH
]);

// AFTER
$this->daftarUlang->update([
    'bukti_pembayaran_madrasah' => $path,
    'waktu_pembayaran_madrasah' => now(),
    // Status tidak diubah ✅
]);
```

#### Method `uploadBuktiSeragam()`:
```php
// BEFORE
$this->seragam->update([
    'bukti_pembayaran' => $path,
    'waktu_pembayaran_seragam' => now(),
    'status_seragam' => 'menunggu',  // ❌ SALAH
]);

// AFTER
$this->seragam->update([
    'bukti_pembayaran' => $path,
    'waktu_pembayaran_seragam' => now(),
    // Status tidak diubah ✅
]);
```

---

## 🚀 CARA UPLOAD KE PRODUCTION

### Opsi 1: Gunakan Batch Script (Recommended)
```batch
upload_fix_step_by_step_form.bat
```

### Opsi 2: Manual
```bash
# 1. Upload View
scp resources/views/livewire/pendaftar/daftar-ulang.blade.php \
    mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/

# 2. Upload Controller
scp app/Livewire/Pendaftar/DaftarUlang.php \
    mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Pendaftar/

# 3. Clear Cache
ssh mtsnkot4@103.30.244.77 \
    "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan optimize:clear"
```

---

## ✅ TESTING CHECKLIST

### Step 1: Upload Surat Pernyataan
- [ ] Badge "1" ditampilkan
- [ ] Section selalu muncul (tidak ada kondisi hide)
- [ ] Upload button berfungsi
- [ ] Loading indicator muncul saat upload
- [ ] "Lihat Bukti" button muncul setelah upload
- [ ] Status TIDAK berubah setelah upload

### Step 2: Pilih Nominal
- [ ] Badge "2" ditampilkan
- [ ] Section TIDAK muncul sebelum Step 1 selesai
- [ ] Section muncul SETELAH Surat Pernyataan diupload
- [ ] Radio buttons berfungsi
- [ ] Preview nominal ditampilkan

### Step 3: Konfirmasi Nominal
- [ ] Badge "3" ditampilkan
- [ ] Section TIDAK muncul sebelum Step 2 selesai
- [ ] Section muncul SETELAH nominal dipilih
- [ ] Radio buttons berfungsi

### Step 4: Detail Pembayaran
- [ ] Badge "4" ditampilkan
- [ ] Section TIDAK muncul sebelum Step 3 selesai
- [ ] Section muncul SETELAH konfirmasi nominal dipilih
- [ ] Upload Bukti berfungsi
- [ ] "Lihat Bukti" button muncul setelah upload
- [ ] Input Nama Pengirim berfungsi
- [ ] Status TIDAK berubah setelah upload

### Final Submit
- [ ] Tombol "Simpan Data" berfungsi
- [ ] Status berubah ke "menunggu" HANYA setelah klik "Simpan Data"
- [ ] Success message ditampilkan

---

## 📋 FILES MODIFIED

```
✅ resources/views/livewire/pendaftar/daftar-ulang.blade.php
   - Reorganisasi section
   - Tambah step indicators
   - Tambah conditional wrappers
   - Hapus duplicate section

✅ app/Livewire/Pendaftar/DaftarUlang.php
   - Fix uploadBuktiPembayaran() method
   - Fix uploadBuktiSeragam() method
   - Remove status updates from upload methods
```

---

## ⚠️ CATATAN PENTING

1. **Upload Button ≠ Submit Button**
   - Upload button hanya untuk upload file
   - Submit button ("Simpan Data") untuk mengubah status

2. **Progressive Disclosure**
   - User tidak bisa skip step
   - Setiap step harus selesai untuk membuka step berikutnya

3. **Backward Compatibility**
   - Data yang sudah ada tetap bisa diedit
   - Form fields yang sudah terisi tetap muncul

4. **Status Workflow**
   ```
   belum → menunggu → perbaiki/ditolak → disetujui
     ↑        ↑
     |        └─ Saat klik "Simpan Data"
     └─ Saat create record baru
   ```

---

## 📞 SUPPORT

Jika ada masalah setelah upload:
1. Cek browser console untuk error JavaScript
2. Cek Laravel log di production: `storage/logs/laravel.log`
3. Clear browser cache jika tampilan tidak berubah
4. Test dengan incognito mode

---

**Status:** Ready for Production ✅
**Impact:** Major UX improvement
**Risk Level:** Low (no breaking changes)
