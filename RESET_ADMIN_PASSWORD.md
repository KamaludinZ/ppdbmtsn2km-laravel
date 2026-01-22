# Reset Admin Password - Emergency Fix

**Date:** 21 Januari 2026
**Issue:** Error 500 saat login admin setelah ubah email/password

---

## 🆘 Quick Fix - Reset Admin Password

### Option 1: Menggunakan Artisan Tinker (RECOMMENDED)

```bash
# Masuk ke direktori project
cd C:\ppdbmtsn2km-laravel

# Jalankan Tinker
php artisan tinker

# Paste command ini (ganti email dan password sesuai keinginan)
$admin = App\Models\User::where('role', 'admin')->first();
$admin->email = 'admin@example.com';
$admin->password = bcrypt('password123');
$admin->save();
echo "Admin updated! Email: " . $admin->email;
exit
```

### Option 2: Buat File PHP Temporary

Buat file `reset_admin.php` di root project:

```php
<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

// Find admin user
$admin = User::where('role', 'admin')->first();

if (!$admin) {
    echo "Admin user not found!\n";
    exit;
}

// Set new credentials
$newEmail = 'admin@mtsn2kotamalang.sch.id';  // GANTI SESUAI KEINGINAN
$newPassword = 'Admin123!@#';                 // GANTI SESUAI KEINGINAN

$admin->email = $newEmail;
$admin->password = bcrypt($newPassword);
$admin->save();

echo "✅ Admin password has been reset!\n";
echo "Email: " . $admin->email . "\n";
echo "Password: " . $newPassword . "\n";
echo "Name: " . $admin->name . "\n";
echo "Role: " . $admin->role . "\n";
?>
```

Jalankan:
```bash
php reset_admin.php
```

### Option 3: Direct Database Query

Jika kedua option di atas tidak work, gunakan database langsung:

```sql
-- Login ke PostgreSQL
psql -U postgres -d nama_database

-- Update admin user (ganti password hash-nya)
UPDATE users
SET
  email = 'admin@example.com',
  password = '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewY5Tx1JxC.K7YPO'  -- ini adalah hash untuk 'password'
WHERE role = 'admin';

-- Verify
SELECT id, name, email, role FROM users WHERE role = 'admin';
```

Password hash `$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewY5Tx1JxC.K7YPO` = `password`

---

## 🔧 Clear Cache After Reset

Setelah reset password, clear semua cache:

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan session:flush

# Restart server (jika pakai php artisan serve)
# Ctrl+C lalu php artisan serve lagi
```

---

## 🧪 Test Login

1. Navigate to `/login/admin`
2. Login dengan credentials baru:
   - Email: `admin@example.com` (atau sesuai yang Anda set)
   - Password: `password123` (atau sesuai yang Anda set)
3. Should login successfully ✅

---

## 🔍 Troubleshooting

### Jika masih error 500:

**1. Check specific error:**
```bash
# Check latest error
powershell -Command "Get-Content 'storage/logs/laravel.log' -Tail 50"

# Or check with specific date
dir storage/logs
```

**2. Check session & cache:**
```bash
# Clear everything
php artisan optimize:clear

# Check storage permissions
icacls storage /grant Everyone:(OI)(CI)F /T
icacls bootstrap/cache /grant Everyone:(OI)(CI)F /T
```

**3. Check .env file:**
```bash
# Make sure these are set correctly
APP_KEY=base64:...  # Should exist
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

**4. If APP_KEY is missing:**
```bash
php artisan key:generate
```

### Jika tidak bisa login dengan password baru:

**Check password hash:**
```bash
php artisan tinker

# Test password hash
$password = 'password123';
$hash = bcrypt($password);
echo $hash;

# Verify hash
var_dump(Hash::check('password123', $hash));  // should return true

exit
```

### Jika lupa email admin:

**List all admin users:**
```bash
php artisan tinker

App\Models\User::where('role', 'admin')->get(['id', 'name', 'email', 'role']);

exit
```

---

## 📋 Recommended Credentials

For production use, set strong credentials:

```
Email: admin@mtsn2kotamalang.sch.id
Password: MtsN2Malang2026!@#
```

For development/testing:

```
Email: admin@example.com
Password: password
```

---

## ⚠️ Important Notes

1. **Password Hash:** Laravel menggunakan bcrypt. JANGAN set password langsung tanpa bcrypt()!
   ```php
   // ✅ CORRECT
   $user->password = bcrypt('password');

   // ❌ WRONG
   $user->password = 'password';
   ```

2. **Email Unique:** Pastikan email baru tidak digunakan user lain
   ```bash
   php artisan tinker
   App\Models\User::where('email', 'admin@example.com')->count();  // should be 0 or 1
   exit
   ```

3. **Clear Browser Cache:** Setelah reset, clear browser cache atau gunakan Incognito mode

4. **Session:** Jika masih tidak bisa login, hapus semua session:
   ```bash
   del storage\framework\sessions\*
   php artisan session:flush
   ```

---

## 🚀 All-in-One Reset Script

Create file: `fix_admin_login.bat`

```batch
@echo off
echo ====================================
echo    RESET ADMIN LOGIN - PPDB
echo ====================================
echo.

cd C:\ppdbmtsn2km-laravel

echo [1/4] Resetting admin password...
php artisan tinker --execute="$admin = App\Models\User::where('role', 'admin')->first(); $admin->email = 'admin@example.com'; $admin->password = bcrypt('password'); $admin->save(); echo 'Admin email: ' . $admin->email;"

echo.
echo [2/4] Clearing cache...
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo.
echo [3/4] Clearing sessions...
del /Q storage\framework\sessions\*

echo.
echo [4/4] Done!
echo.
echo ====================================
echo   LOGIN CREDENTIALS:
echo   Email: admin@example.com
echo   Password: password
echo ====================================
echo.
pause
```

Run:
```bash
fix_admin_login.bat
```

---

**Status:** ✅ EMERGENCY FIX READY
**Risk:** Low (only resets admin credentials)
**Time:** < 1 minute
**Impact:** Immediate (restores admin access)

---

**End of Documentation**
