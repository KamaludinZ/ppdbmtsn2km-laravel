# Panduan Test - Fix Bug Tab Separation

## ✅ Bug yang Sudah Diperbaiki

**Masalah Sebelumnya:**
- Klik "Simpan" di tab Ukuran → Redirect ke tab Seragam ❌
- Tab Seragam ikut tersimpan padahal belum diisi ❌
- Status tab lain ikut berubah padahal belum diisi ❌

**Setelah Fix:**
- Klik "Simpan" di tab Ukuran → Tetap di tab Ukuran ✅
- Hanya tab yang diisi yang tersimpan ✅
- Status hanya berubah di tab yang benar-benar diisi ✅

---

## 🧪 Cara Test

### Test 1: Simpan Ukuran Dulu (Tanpa Isi Seragam)

**Steps:**
1. Login sebagai pendaftar baru
2. Langsung ke tab **Ukuran**: `/pendaftar/daftarulang?activeTab=ukuran`
3. Isi semua ukuran:
   - Ukuran Jas: (pilih)
   - Ukuran Baju Olahraga: (pilih)
   - Ukuran Celana Olahraga: (pilih)
   - Ukuran Baju Harian: (pilih)
   - Ukuran Celana/Rok Harian: (pilih)
   - Ukuran Kopyah: (pilih)
4. **Klik "Simpan"**

**Yang Harus Terjadi:**
- ✅ Muncul notifikasi: "Data ukuran seragam berhasil disimpan"
- ✅ **Halaman TETAP di tab Ukuran** (TIDAK pindah ke tab Seragam)
- ✅ URL tetap: `/pendaftar/daftarulang?activeTab=ukuran`
- ✅ Status tab Ukuran: **"Menunggu Verifikasi"** (ada badge kuning/biru)

**Sekarang Cek Tab Seragam:**
5. Klik tab **"Seragam"**
6. **Lihat status tab Seragam**

**Yang Harus Terjadi:**
- ✅ Tab Seragam masih **KOSONG** (form masih bisa diisi)
- ✅ **TIDAK ADA** status "Menunggu Verifikasi" di tab Seragam
- ✅ Form masih dalam keadaan bisa diisi (belum locked)

---

### Test 2: Simpan Seragam Dulu (Tanpa Isi Ukuran)

**Steps:**
1. Login sebagai pendaftar baru (atau gunakan akun lain)
2. Langsung ke tab **Seragam**: `/pendaftar/daftarulang?activeTab=seragam`
3. Isi form seragam:
   - Pilih paket seragam
   - Pilih opsi dari paket
   - Pilih nominal konfirmasi
   - Pilih opsi pembayaran
   - Pilih bank
   - Upload bukti pembayaran
   - Isi nama pengirim
4. **Klik "Simpan"**

**Yang Harus Terjadi:**
- ✅ Muncul notifikasi: "Data daftar ulang seragam berhasil disimpan"
- ✅ Halaman TETAP di tab Seragam
- ✅ Status tab Seragam: **"Menunggu Verifikasi"**

**Sekarang Cek Tab Ukuran:**
5. Klik tab **"Ukuran"**
6. **Lihat status tab Ukuran**

**Yang Harus Terjadi:**
- ✅ Tab Ukuran masih **KOSONG** (form masih bisa diisi)
- ✅ **TIDAK ADA** status "Menunggu Verifikasi" di tab Ukuran
- ✅ Form masih dalam keadaan bisa diisi (belum locked)

---

### Test 3: Simpan Ukuran, Lalu Simpan Seragam

**Steps:**
1. Login sebagai pendaftar baru
2. Ke tab **Ukuran**, isi semua dan simpan
3. **Verify:** Tetap di tab Ukuran setelah simpan
4. **Verify:** Status tab Ukuran = "Menunggu Verifikasi"
5. Klik tab **Seragam**
6. **Verify:** Tab Seragam masih kosong, belum ada status
7. Isi form seragam lengkap
8. **Klik "Simpan"**

**Yang Harus Terjadi:**
- ✅ Status tab Ukuran TETAP "Menunggu Verifikasi" (tidak berubah)
- ✅ Status tab Seragam SEKARANG "Menunggu Verifikasi"
- ✅ Kedua tab punya status masing-masing

---

### Test 4: Simpan Seragam, Lalu Simpan Ukuran

**Steps:**
1. Login sebagai pendaftar baru
2. Ke tab **Seragam**, isi semua dan simpan
3. **Verify:** Status tab Seragam = "Menunggu Verifikasi"
4. Klik tab **Ukuran**
5. **Verify:** Tab Ukuran masih kosong, belum ada status
6. Isi semua ukuran
7. **Klik "Simpan"**
8. **PENTING:** Perhatikan halaman setelah klik simpan

**Yang Harus Terjadi:**
- ✅ Halaman **TETAP di tab Ukuran** (TIDAK pindah ke tab Seragam!)
- ✅ URL tetap: `?activeTab=ukuran` (BUKAN `activeTab=seragam`)
- ✅ Status tab Seragam TETAP "Menunggu Verifikasi" (tidak berubah)
- ✅ Status tab Ukuran SEKARANG "Menunggu Verifikasi"
- ✅ Kedua tab punya status masing-masing

---

## ✅ Checklist Lengkap

**Simpan Tab Ukuran:**
- [ ] Notifikasi sukses muncul
- [ ] Halaman TETAP di tab Ukuran (TIDAK pindah ke Seragam)
- [ ] URL tetap `?activeTab=ukuran`
- [ ] Status tab Ukuran berubah jadi "Menunggu Verifikasi"
- [ ] Tab Seragam TIDAK terpengaruh (masih kosong)

**Simpan Tab Seragam:**
- [ ] Notifikasi sukses muncul
- [ ] Halaman TETAP di tab Seragam
- [ ] URL tetap `?activeTab=seragam`
- [ ] Status tab Seragam berubah jadi "Menunggu Verifikasi"
- [ ] Tab Ukuran TIDAK terpengaruh (masih kosong)

**Simpan Kedua Tab (Urutan Bebas):**
- [ ] Kedua tab bisa diisi dalam urutan apapun
- [ ] Status hanya muncul di tab yang sudah diisi
- [ ] Simpan satu tab tidak mengubah status tab lain

---

## 🎯 Yang HARUS Anda Perhatikan

### 1. Redirect Harus Benar

**BEFORE (Bug):**
- Simpan tab Ukuran → Pindah ke tab Seragam ❌

**AFTER (Fixed):**
- Simpan tab Ukuran → Tetap di tab Ukuran ✅

**Cara Verify:**
- Setelah klik "Simpan" di tab Ukuran
- Lihat URL di browser
- Harus tetap: `?activeTab=ukuran`
- BUKAN: `?activeTab=seragam`

---

### 2. Status Tidak Boleh "Bocor"

**BEFORE (Bug):**
- Simpan tab Ukuran
- Tab Seragam ikut dapat status "Menunggu" ❌

**AFTER (Fixed):**
- Simpan tab Ukuran
- Hanya tab Ukuran yang dapat status ✅
- Tab Seragam tetap kosong ✅

**Cara Verify:**
- Setelah simpan tab Ukuran
- Klik tab Seragam
- Lihat apakah ada badge/status "Menunggu Verifikasi"
- Harus TIDAK ADA status jika memang belum diisi

---

### 3. Form Harus Tetap Bisa Diisi

**BEFORE (Bug - Kemungkinan):**
- Simpan tab Ukuran
- Tab Seragam locked/disabled padahal belum diisi ❌

**AFTER (Fixed):**
- Simpan tab Ukuran
- Tab Seragam tetap bisa diisi (form aktif) ✅

**Cara Verify:**
- Setelah simpan tab Ukuran
- Klik tab Seragam
- Coba klik dropdown "Pilih Paket Seragam"
- Harus masih bisa diklik dan dipilih

---

## 🐛 Jika Masih Ada Bug

### Bug 1: Redirect Salah

**Gejala:**
- Simpan tab Ukuran → Pindah ke tab Seragam

**Fix yang Sudah Dilakukan:**
- Line 718 di `app/Livewire/Pendaftar/DaftarUlang.php`
- Changed redirect dari `activeTab=seragam` ke `activeTab=ukuran`

**Jika Masih Terjadi:**
- Clear cache browser: `Ctrl + Shift + F5`
- Clear cache Laravel: `php artisan optimize:clear`
- Check file sudah terupload dengan benar

---

### Bug 2: Status Tab Lain Ikut Berubah

**Gejala:**
- Simpan tab Ukuran
- Status tab Seragam jadi "Menunggu" padahal belum diisi

**Fix yang Sudah Dilakukan:**
- Line 666: Explicitly set `status_seragam = null` ketika create dari tab Ukuran
- Line 586: Explicitly set `status_ukuran = null` ketika create dari tab Seragam

**Jika Masih Terjadi:**
1. Check database langsung:
   ```sql
   SELECT id, pendaftar_id, status_ukuran, status_seragam
   FROM seragam
   WHERE pendaftar_id = [ID_PENDAFTAR];
   ```
2. Verify:
   - Jika hanya isi Ukuran: `status_ukuran = 'menunggu'`, `status_seragam = NULL`
   - Jika hanya isi Seragam: `status_seragam = 'menunggu'`, `status_ukuran = NULL`

---

## 📝 Catatan untuk Testing

### Fresh Account

**Gunakan akun pendaftar baru untuk test:**
- Akun yang belum pernah isi apapun
- Tidak ada data daftar ulang sama sekali
- Ini untuk memastikan fix bekerja untuk kasus "create record baru"

### Clear Data (Optional)

**Jika ingin test ulang dengan akun yang sama:**
```sql
-- HATI-HATI! Ini akan hapus data daftar ulang
DELETE FROM seragam WHERE pendaftar_id = [ID_PENDAFTAR];
```

Atau buat akun pendaftar baru saja untuk testing.

---

## ✅ Test Sukses Jika...

1. ✅ Simpan tab Ukuran → Tetap di tab Ukuran
2. ✅ Simpan tab Seragam → Tetap di tab Seragam
3. ✅ Hanya tab yang diisi yang dapat status
4. ✅ Tab lain tidak terpengaruh
5. ✅ Bisa isi tab dalam urutan apapun
6. ✅ Tidak ada error di console browser
7. ✅ Tidak ada error di Laravel logs

---

**Date:** 9 Februari 2026
**Status:** Ready to Test
**File yang Diubah:** `app/Livewire/Pendaftar/DaftarUlang.php`

**Jika semua test ✅ → Bug sudah fixed!** 🎉
