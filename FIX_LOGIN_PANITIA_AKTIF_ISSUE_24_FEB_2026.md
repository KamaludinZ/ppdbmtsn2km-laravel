# FIX: Login Panitia Issue - Validasi Aktif

**Tanggal:** 24 Februari 2026
**Status:** ✅ SELESAI

## 🔍 Masalah

Panitia (panitia_pendaftaran, panitia_seragam, panitia_tes_psikologi) **tidak bisa login** meskipun:
- ✅ Email benar
- ✅ Password benar
- ✅ Field `aktif = true` di database
- ✅ Role sesuai

**Error yang muncul:**
```
Email atau password salah, atau akun tidak aktif.
```

## 🐛 Root Cause

Di file `app/Livewire/Auth/AdminLogin.php` line 70, kode lama:

```php
if (Auth::attempt(['email' => $this->email, 'password' => $this->password, 'aktif' => true], $this->remember)) {
```

**Masalahnya:**
- `Auth::attempt()` dengan kondisi `'aktif' => true` **tidak bekerja dengan baik** di PostgreSQL
- PostgreSQL boolean type casting issue saat Laravel Auth query
- Kondisi ekstra di Auth::attempt() tidak reliable untuk field boolean

## ✅ Solusi

**Ubah flow menjadi:**
1. Login dulu TANPA kondisi `aktif`
2. Setelah berhasil, **cek manual** apakah user aktif
3. Jika tidak aktif, logout dan tampilkan error spesifik

### Kode Baru (FIXED):

```php
public function login()
{
    $this->validate();

    // Verify captcha
    if ($this->captcha !== session('admin_login_captcha')) {
        $this->addError('captcha', 'Kode keamanan tidak sesuai');
        $this->captcha = '';
        $this->generateCaptcha();
        return;
    }

    // Attempt login WITHOUT aktif condition
    if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
        $user = Auth::user();

        // Manual check: is user active?
        if (!$user->aktif) {
            Auth::logout();
            $this->addError('email', 'Akun Anda tidak aktif. Silakan hubungi administrator.');
            $this->captcha = '';
            $this->generateCaptcha();
            return;
        }

        // Manual check: is admin or panitia?
        if (!$user->isAdmin() && !$user->isPanitia()) {
            Auth::logout();
            $this->addError('email', 'Akses ditolak. Halaman ini khusus untuk Admin dan Panitia.');
            $this->captcha = '';
            $this->generateCaptcha();
            return;
        }

        // Success - proceed with session and redirect
        session()->regenerate();
        session()->forget('admin_login_captcha');

        // Redirect based on role
        if ($user->isAdmin()) {
            return redirect()->intended('/admin/dashboard');
        } elseif ($user->role === 'panitia_pendaftaran') {
            return redirect()->intended('/panitia-pendaftaran/dashboard');
        } elseif ($user->role === 'panitia_daftarulang') {
            return redirect()->intended('/panitia-daftarulang/dashboard');
        } elseif ($user->role === 'panitia_tes_psikologi') {
            return redirect()->intended('/panitia-psikologi/dashboard');
        } elseif ($user->role === 'panitia_seragam') {
            return redirect()->intended('/panitia-seragam/dashboard');
        }

        // Fallback
        Auth::logout();
        $this->addError('email', 'Akses ditolak. Halaman ini khusus untuk Admin dan Panitia.');
        $this->captcha = '';
        $this->generateCaptcha();
        return;
    }

    // Login failed
    $this->addError('email', 'Email atau password salah.');
    $this->captcha = '';
    $this->generateCaptcha();
}
```

## 📋 Perubahan Detail

### Before (BERMASALAH):
- ❌ Auth::attempt() dengan `'aktif' => true`
- ❌ Tidak ada pengecekan role setelah login
- ❌ Error message tidak spesifik

### After (FIXED):
- ✅ Auth::attempt() tanpa kondisi `aktif`
- ✅ Cek manual `$user->aktif` setelah login
- ✅ Cek manual `$user->isAdmin()` dan `$user->isPanitia()`
- ✅ Error message lebih spesifik:
  - "Akun Anda tidak aktif" - jika aktif = false
  - "Akses ditolak" - jika role bukan admin/panitia
  - "Email atau password salah" - jika kredensial salah

## 🎯 Benefit

1. **Login panitia sekarang berfungsi** ✅
2. **Error message lebih jelas** - user tahu persis apa masalahnya
3. **Security tetap terjaga** - validasi aktif dan role tetap ada
4. **Compatible dengan PostgreSQL** - tidak ada boolean type issue

## 🧪 Testing

Setelah fix, test dengan akun:

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

**URL:** http://localhost:8000/admin/login

## 📝 File yang Diubah

1. `app/Livewire/Auth/AdminLogin.php` - Fix login logic

## 🔄 Deployment

```bash
# Clear cache
php artisan view:clear
php artisan cache:clear
php artisan config:clear

# Test login
# Buka browser: http://localhost:8000/admin/login
# Login dengan salah satu akun panitia
```

## ✅ Checklist Verifikasi

- [x] Panitia pendaftaran bisa login
- [x] Panitia seragam bisa login
- [x] Panitia psikologi bisa login
- [x] User tidak aktif ditolak dengan message yang jelas
- [x] User dengan role pendaftar ditolak
- [x] Error message spesifik dan informatif

---

**Status:** FIXED ✅
**Impact:** HIGH - Semua panitia sekarang bisa login
**Breaking Changes:** Tidak ada
