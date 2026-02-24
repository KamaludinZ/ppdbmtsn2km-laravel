# FIX: Browser Cache Issue - Login Panitia

## 🔍 Masalah

Error message yang muncul masih: **"Email atau password salah, atau akun tidak aktif."**

Ini adalah **error message LAMA**. File AdminLogin.php sudah diperbaiki, error message baru seharusnya: **"Email atau password salah."** (tanpa "atau akun tidak aktif")

**Root Cause:** Browser Anda masih me-load Livewire component yang lama (cached).

## ✅ Solusi - WAJIB LAKUKAN INI:

### Opsi 1: Hard Refresh (PALING MUDAH)
1. Buka halaman login: http://localhost:8000/admin/login
2. Tekan **Ctrl + Shift + R** (Windows) atau **Cmd + Shift + R** (Mac)
3. Atau tekan **Ctrl + F5**
4. Ini akan memaksa browser reload semua assets tanpa cache

### Opsi 2: Incognito/Private Mode (PALING RELIABLE)
1. Tutup semua window browser
2. Buka **Incognito/Private mode**:
   - Chrome: Ctrl + Shift + N
   - Firefox: Ctrl + Shift + P
   - Edge: Ctrl + Shift + N
3. Buka: http://localhost:8000/admin/login
4. Login dengan akun panitia

### Opsi 3: Clear Browser Cache Completely
1. Tekan **Ctrl + Shift + Delete**
2. Pilih:
   - ✅ Cookies and other site data
   - ✅ Cached images and files
3. Time range: **All time**
4. Klik **Clear data**
5. Restart browser
6. Buka: http://localhost:8000/admin/login

### Opsi 4: Clear Application Storage (DevTools)
1. Buka halaman login
2. Tekan **F12** (Developer Tools)
3. Tab **Application** (Chrome) atau **Storage** (Firefox)
4. Klik kanan pada domain (localhost:8000)
5. Pilih **Clear site data**
6. Refresh halaman (F5)

## 🧪 Cara Verifikasi Fix Berhasil

Setelah hard refresh / incognito, coba login dengan email SALAH:

**Before (LAMA - Masih Cached):**
```
Error: "Email atau password salah, atau akun tidak aktif."
```

**After (BARU - Sudah Fixed):**
```
Error: "Email atau password salah."
```

Jika error message berubah menjadi yang BARU, berarti fix berhasil!

## 🎯 Test Login Panitia

Setelah cache cleared, login dengan:

```
Panitia Pendaftaran:
Email: panitia@test.com
Password: panitia123

Panitia Seragam:
Email: panitia-seragam@test.com
Password: seragam123

Panitia Psikologi:
Email: panitia-psikologi@test.com
Password: psikologi123
```

**PENTING:** Isi captcha dengan benar (6 digit angka yang muncul di form)

## 📊 Troubleshooting

Jika masih gagal setelah clear cache:

1. **Cek apakah Laravel server running:**
   ```bash
   php artisan serve
   ```

2. **Restart Laravel server:**
   - Tekan Ctrl+C di terminal
   - Jalankan lagi: `php artisan serve`

3. **Test dengan curl (untuk bypass browser):**
   ```bash
   curl -X POST http://localhost:8000/livewire/update ^
   -H "Content-Type: application/json" ^
   --data-raw "{\"email\":\"panitia@test.com\",\"password\":\"panitia123\"}"
   ```

## ✅ Kesimpulan

- ✅ File AdminLogin.php sudah diperbaiki
- ✅ Laravel cache sudah di-clear
- ❌ Browser Anda masih pakai code lama

**SOLUSI: HARD REFRESH atau INCOGNITO MODE**
