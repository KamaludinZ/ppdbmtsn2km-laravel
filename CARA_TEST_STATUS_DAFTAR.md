# 🧪 Cara Test Fitur Status Daftar - STEP BY STEP

## ✅ Backend Sudah 100% BENAR!

Test backend menunjukkan query sudah berfungsi dengan sempurna:
- **Jalur Prestasi Akademik** (ID: 1) dengan status "Tampil di Daftar: ❌ Tidak" → **TIDAK MUNCUL** dalam query
- 5 jalur lainnya dengan status "Tampil di Daftar: ✅ Ya" → **MUNCUL** dalam query

## 📋 Langkah Testing di Browser

### Step 1: Clear Browser Cache
Masalah kemungkinan besar karena **browser cache**. Lakukan salah satu cara ini:

**Cara A: Hard Refresh (Paling Mudah)**
1. Buka halaman `/daftar` di browser
2. Tekan kombinasi tombol:
   - **Windows/Linux**: `Ctrl + Shift + R` atau `Ctrl + F5`
   - **Mac**: `Cmd + Shift + R`
3. Atau klik kanan pada tombol refresh → pilih **"Hard Reload"** atau **"Empty Cache and Hard Reload"**

**Cara B: Clear Browser Cache Manual**
1. **Chrome/Edge**:
   - Tekan `Ctrl + Shift + Delete`
   - Pilih "Cached images and files"
   - Klik "Clear data"

2. **Firefox**:
   - Tekan `Ctrl + Shift + Delete`
   - Pilih "Cache"
   - Klik "Clear Now"

3. **Safari**:
   - Tekan `Cmd + Option + E`
   - Atau Safari → Preferences → Advanced → Show Develop menu
   - Develop → Empty Caches

### Step 2: Logout dan Login Ulang
Session lama mungkin menyimpan data cache:
1. Logout dari akun admin
2. Close semua tab browser yang terbuka
3. Login kembali sebagai admin
4. Buka `/admin/jalur` untuk verifikasi status
5. Buka `/daftar` di **tab baru**

### Step 3: Test dengan Incognito/Private Mode
Paling reliable untuk test tanpa cache:
1. Buka browser dalam mode **Incognito/Private**:
   - Chrome/Edge: `Ctrl + Shift + N`
   - Firefox: `Ctrl + Shift + P`
   - Safari: `Cmd + Shift + N`
2. Login sebagai admin
3. Buka `/admin/jalur` → ubah status jalur
4. Buka `/daftar` di tab baru
5. Verifikasi jalur muncul/tidak sesuai status

### Step 4: Verifikasi di Database
Jika masih bermasalah, jalankan test script:
```bash
php test_daftar_jalur.php
```

Hasilnya harus menunjukkan:
- Jalur dengan "Tampil: Ya" → **MUNCUL** dalam list
- Jalur dengan "Tampil: Tidak" → **TIDAK MUNCUL** dalam list

## 🔍 Troubleshooting

### Masalah: Jalur masih muncul padahal Status Daftar = Tersembunyi

**Solusi 1: Clear All Cache**
```bash
php artisan optimize:clear
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

**Solusi 2: Restart Development Server**
Jika menggunakan `php artisan serve` atau `npm run dev`:
1. Stop server (Ctrl + C)
2. Jalankan ulang:
   ```bash
   php artisan serve
   ```
3. Hard refresh browser

**Solusi 3: Cek Database Langsung**
Jalankan:
```bash
php check_jalur_status.php
```
Pastikan kolom `tampil_di_daftar` sesuai dengan yang Anda set di admin.

### Masalah: Perubahan di Admin Tidak Tersimpan

1. Cek apakah muncul pesan sukses "Jalur pendaftaran berhasil diperbarui"
2. Refresh halaman `/admin/jalur`
3. Verifikasi badge "Status Daftar" berubah
4. Jika tidak berubah, cek console browser (F12) untuk error JavaScript

## ✅ Checklist Verifikasi

Pastikan semuanya sudah dilakukan:

- [ ] Cache Laravel sudah di-clear (`php artisan optimize:clear`)
- [ ] Browser cache sudah di-clear (Hard Refresh: `Ctrl + Shift + R`)
- [ ] Sudah logout dan login ulang
- [ ] Test di Incognito/Private mode
- [ ] Verifikasi status di database (`php test_daftar_jalur.php`)
- [ ] Restart development server jika perlu

## 📊 Hasil yang Diharapkan

### Di `/admin/jalur`:
```
| Nama Jalur              | Status  | Status Daftar |
|------------------------|---------|---------------|
| Jalur Prestasi Akademik| Aktif   | Tersembunyi   |
| Jalur Reguler          | Aktif   | Tampil        |
```

### Di `/daftar`:
```
Jalur yang MUNCUL:
✅ Jalur Reguler (karena Status Daftar = Tampil)

Jalur yang TIDAK MUNCUL:
❌ Jalur Prestasi Akademik (karena Status Daftar = Tersembunyi)
```

## 🎯 Konfirmasi Final

Jika setelah semua langkah di atas masih tidak berhasil:
1. Screenshot halaman `/admin/jalur` (tunjukkan kolom Status Daftar)
2. Screenshot halaman `/daftar` (tunjukkan jalur yang muncul)
3. Jalankan `php test_daftar_jalur.php` dan screenshot hasilnya
4. Cek console browser (F12) untuk error

---

**File Test:**
- `check_jalur_status.php` - Cek status semua jalur di database
- `test_daftar_jalur.php` - Test query yang digunakan di /daftar

**Update Terakhir:** 23 Januari 2026
