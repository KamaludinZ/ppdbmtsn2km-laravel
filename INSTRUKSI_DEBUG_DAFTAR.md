# 🔍 Instruksi Debug Masalah Status Daftar

## ✅ Yang Sudah Dikerjakan

### 1. **Kolom Paling Populer Ditambahkan** ⭐
Halaman `/admin/jalur` sekarang memiliki kolom baru:
- **Status** - Status aktif/nonaktif jalur
- **Status Daftar** - Mengatur kemunculan di `/daftar`
- **Paling Populer** - ⭐ BARU! Badge kuning untuk jalur populer

### 2. **Halaman Test Debug Dibuat** 🧪
URL baru untuk debug: **`/test-jalur-daftar`**

## 🎯 LANGKAH TESTING - IKUTI INI!

### Step 1: Akses Halaman Test
1. Buka browser
2. Akses: **`http://localhost:8000/test-jalur-daftar`**
   (atau sesuai dengan URL development Anda)
3. Halaman ini akan menampilkan:
   - ✅ Jalur yang MUNCUL di `/daftar`
   - ❌ Jalur yang TIDAK MUNCUL di `/daftar`
   - Query yang digunakan
   - Waktu real-time

### Step 2: Verifikasi Data
Di halaman `/test-jalur-daftar` Anda akan melihat tabel seperti ini:

```
=== Jalur yang AKAN MUNCUL di /daftar ===
| ID | Nama Jalur              | Status Aktif | Status Daftar | Paling Populer |
|----|-------------------------|--------------|---------------|----------------|
| 2  | Jalur Prestasi Non-Aka  | ✅ Aktif    | ✅ Tampil     | Tidak          |
| 3  | Jalur Reguler           | ✅ Aktif    | ✅ Tampil     | ⭐ Ya          |

=== Jalur yang TIDAK AKAN MUNCUL di /daftar ===
| ID | Nama Jalur              | Status Aktif | Status Daftar |
|----|-------------------------|--------------|---------------|
| 1  | Jalur Prestasi Akademik | ✅ Aktif    | ❌ Tersembunyi|
```

### Step 3: Test Perubahan Status
1. Buka `/admin/jalur` di **tab baru**
2. Edit salah satu jalur
3. **Uncheck** "Tampil di Halaman Daftar"
4. Simpan
5. Kembali ke tab `/test-jalur-daftar`
6. **Refresh (F5)**
7. ✅ Jalur tersebut harus **PINDAH** ke tabel "TIDAK AKAN MUNCUL"

### Step 4: Test di Halaman /daftar ASLI
Jika `/test-jalur-daftar` sudah menunjukkan data yang benar:

1. Buka `/daftar` di **tab baru** atau **incognito mode**
2. **PENTING**: Tekan **`Ctrl + Shift + R`** (hard refresh)
3. Verifikasi jalur yang muncul sesuai dengan `/test-jalur-daftar`

## 🔧 Jika Masih Bermasalah

### Masalah A: `/test-jalur-daftar` sudah benar, tapi `/daftar` belum
**Ini masalah browser cache!**

✅ **Solusi:**
```bash
1. Close SEMUA tab browser yang terbuka
2. Buka browser baru dalam Incognito/Private mode:
   - Chrome: Ctrl + Shift + N
   - Firefox: Ctrl + Shift + P
3. Akses /daftar di incognito
4. Jika sudah benar di incognito, clear browser cache biasa:
   - Chrome: Ctrl + Shift + Delete
   - Pilih "Cached images and files"
   - Clear data
```

### Masalah B: `/test-jalur-daftar` tidak muncul (404)
**Route belum loaded.**

✅ **Solusi:**
```bash
php artisan route:clear
php artisan optimize:clear
```

Lalu coba akses `/test-jalur-daftar` lagi.

### Masalah C: Perubahan di admin tidak tersimpan
**Cek database langsung.**

✅ **Solusi:**
```bash
php check_jalur_status.php
```

Hasilnya harus menunjukkan perubahan yang Anda buat di admin.

## 📋 Checklist Troubleshooting

Lakukan secara berurutan:

- [ ] **1. Test `/test-jalur-daftar`** - Apakah data sudah benar?
  - Jika BELUM: Ada masalah di database/backend
  - Jika SUDAH: Lanjut ke step 2

- [ ] **2. Clear browser cache** - `Ctrl + Shift + R` di `/daftar`
  - Jika BELUM: Lanjut ke step 3
  - Jika SUDAH: ✅ Selesai!

- [ ] **3. Test di Incognito** - Buka `/daftar` di incognito mode
  - Jika BELUM: Ada masalah di Livewire component
  - Jika SUDAH: Masalah di browser cache biasa, clear manual

- [ ] **4. Clear semua cache server**
  ```bash
  php artisan optimize:clear
  php artisan view:clear
  php artisan route:clear
  ```

- [ ] **5. Restart development server**
  - Stop `php artisan serve` (Ctrl + C)
  - Jalankan ulang: `php artisan serve`

## 📸 Screenshot untuk Debug

Jika masih bermasalah, kirim screenshot:

1. **Screenshot `/test-jalur-daftar`** (tabel jalur)
2. **Screenshot `/admin/jalur`** (kolom Status Daftar)
3. **Screenshot `/daftar`** (jalur yang muncul)
4. **Screenshot Console Browser** (F12 → Console tab)

## 🎯 Hasil yang Diharapkan

### Di `/test-jalur-daftar`:
```
Jumlah Jalur yang MUNCUL di /daftar: 5

Daftar Jalur yang AKAN MUNCUL:
✅ Jalur Reguler
✅ Jalur Tahfidz
✅ Jalur Afirmasi
✅ Jalur Prestasi Non-Akademik
✅ Jalur Perpindahan Tugas Orang Tua

Jalur yang TIDAK AKAN MUNCUL:
❌ Jalur Prestasi Akademik (Status Daftar = Tersembunyi)
```

### Di `/daftar`:
Harus menampilkan **5 jalur yang sama** dengan `/test-jalur-daftar`

### Di `/admin/jalur`:
Kolom baru:
- **Status** (hijau/merah)
- **Status Daftar** (biru/abu-abu)
- **Paling Populer** (kuning/abu-abu) ⭐ BARU!

## 🚀 Quick Fix Commands

Jalankan semua ini sekaligus:

```bash
# Clear semua cache
php artisan optimize:clear

# Test query database
php test_daftar_jalur.php

# Test di browser
echo "Buka: http://localhost:8000/test-jalur-daftar"
```

---

**Update:** 23 Januari 2026
**Status:** ✅ Halaman test sudah siap, kolom Paling Populer sudah ditambahkan
