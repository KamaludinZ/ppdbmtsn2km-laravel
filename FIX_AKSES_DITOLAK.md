# ✅ Fix "Akses Ditolak" - Field `aktif` Missing

**Date:** 21 Januari 2026
**Issue:** Admin baru bisa dibuat tapi login ditolak dengan pesan "akses ditolak"
**Root Cause:** Field `aktif` tidak di-set ke `true` saat create admin baru
**Status:** ✅ FIXED & TESTED

---

## 🔍 Problem Analysis

### What Happened:

1. **Admin baru dibuat** dengan script `create_new_admin.php`
2. **Email dan password benar** (Hash::check() returns true ✅)
3. **Role set to 'admin'** ✅
4. **Tapi login ditolak** ❌ dengan pesan "akses ditolak"

### Root Cause:

**AdminLogin.php Line 70:**
```php
Auth::attempt(['email' => $this->email, 'password' => $this->password, 'aktif' => true], ...)
```

Login requires `'aktif' => true` but our script didn't set this field!

**User Model Line 48:**
```php
'aktif' => 'boolean',  // Cast to boolean
```

Field exists in database and is checked during login.

**User Model Line 22-26:**
```php
protected $fillable = [
    'name',
    'email',
    'password',
];
```

Field `aktif` is NOT in fillable array (by design for security), so it must be set explicitly after creation.

---

## ✅ Solution Applied

### Fix 1: create_new_admin.php

**Before:**
```php
$newAdmin = User::create([
    'name' => 'Super Administrator',
    'email' => $newEmail,
    'password' => Hash::make($newPassword),
    'role' => 'admin',  // ❌ Won't work - not in fillable
    'email_verified_at' => now(),
]);
```

**After:**
```php
// Create with fillable fields only
$newAdmin = User::create([
    'name' => 'Super Administrator',
    'email' => $newEmail,
    'password' => Hash::make($newPassword),
]);

// Set protected fields explicitly
$newAdmin->role = 'admin';
$newAdmin->aktif = true;  // ✅ CRITICAL: Required for login!
$newAdmin->email_verified_at = now();
$newAdmin->save();
```

### Fix 2: emergency_admin_reset.php

**Updated both code paths:**

1. Create new admin (if not exists)
2. Reset existing admin

Both now include `$admin->aktif = true;`

---

## 🧪 Testing Results

### Before Fix:
```
Admin: Super Admin
  Email: superadmin@mtsn2kotamalang.sch.id
  Role: admin
  Aktif: ❌ FALSE
  Login Check: ❌ CANNOT LOGIN
  ⚠️  WARNING: aktif field is FALSE - login will be blocked!
```

### After Fix:
```
Admin: Super Admin
  Email: superadmin@mtsn2kotamalang.sch.id
  Role: admin
  Aktif: ✅ TRUE
  Email Verified: Yes
  Password Test: ✅ VALID
  Login Check: ✅ CAN LOGIN
```

**Summary:**
- Admins that can login: 2 / 2 ✅
- All checks passed ✅

---

## 🚀 Deploy Fixed Script

### Option 1: Automated (FASTEST) ⭐

```bash
cd C:\ppdbmtsn2km-laravel
deploy_new_admin.bat
```

Script akan:
1. Upload `create_new_admin.php` (FIXED VERSION) ke server
2. Jalankan otomatis
3. Set `aktif = true` untuk admin baru
4. Clear all caches
5. Delete script dari server

**Time:** ~30 seconds

---

### Option 2: Manual SSH

```bash
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
php artisan tinker
```

Paste command ini (dengan fix aktif field):
```php
use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Create admin
$admin = User::create([
    'name' => 'Super Administrator',
    'email' => 'superadmin@mtsn2kotamalang.sch.id',
    'password' => Hash::make('SuperAdmin2026!@#'),
]);

// CRITICAL: Set protected fields
$admin->role = 'admin';
$admin->aktif = true;  // ← THIS IS CRITICAL!
$admin->email_verified_at = now();
$admin->save();

echo "✅ Admin created! ID: " . $admin->id;
echo "\nAktif: " . ($admin->aktif ? "TRUE" : "FALSE");
exit
```

Clear caches:
```bash
php artisan optimize:clear
```

---

## 🔐 Credentials

Setelah deployment:

```
Email:    superadmin@mtsn2kotamalang.sch.id
Password: SuperAdmin2026!@#
```

**Login URL:**
```
https://pmbm.mtsn2kotamalang.sch.id/login/admin
```

---

## ✅ Post-Deployment Checklist

- [ ] Run `deploy_new_admin.bat` (or manual SSH)
- [ ] Clear browser cache (Ctrl+Shift+Delete)
- [ ] Navigate to `/login/admin`
- [ ] Enter credentials
- [ ] **Should redirect to admin dashboard** ✅
- [ ] No more "akses ditolak" error ✅
- [ ] Can access all admin pages
- [ ] Session persists (no auto-logout)
- [ ] Can logout and login again

---

## 🆘 If Still "Akses Ditolak"

### Verify aktif Field on Server:

```bash
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
php artisan tinker
```

Check aktif status:
```php
$admin = App\Models\User::where('email', 'superadmin@mtsn2kotamalang.sch.id')->first();
echo "Aktif: " . ($admin->aktif ? "TRUE" : "FALSE") . "\n";
echo "Role: " . $admin->role . "\n";
echo "Email Verified: " . ($admin->email_verified_at ? "Yes" : "No") . "\n";
exit
```

If aktif is still FALSE:
```php
$admin = App\Models\User::where('email', 'superadmin@mtsn2kotamalang.sch.id')->first();
$admin->aktif = true;
$admin->save();
echo "✅ Fixed! Aktif set to TRUE";
exit
```

---

## 📊 Field Requirements for Admin Login

Based on `AdminLogin.php` line 70:

```php
Auth::attempt([
    'email' => $this->email,
    'password' => $this->password,
    'aktif' => true  // ← REQUIRED!
], $this->remember)
```

**Required Fields:**
1. ✅ `email` - Must match
2. ✅ `password` - Must match (bcrypt hash)
3. ✅ `aktif` - Must be `true` ← THIS WAS MISSING!
4. ✅ `role` - Must be 'admin' or 'panitia*'

**All 4 must be correct for login to succeed!**

---

## 🎯 Why This Happened

### Security Design:

From User Model comments:
```php
/**
 * NOTE: 'role' and 'aktif' are intentionally excluded to prevent privilege escalation.
 * These must be set explicitly in code, not from user input.
 */
protected $fillable = [
    'name',
    'email',
    'password',
];
```

Fields `role` and `aktif` are protected for security - cannot be mass assigned.

### Our Mistake:

Original script only set `role`, forgot to set `aktif`:
```php
$newAdmin->role = 'admin';  // ✅ Set
$newAdmin->aktif = true;    // ❌ Missing - FIXED NOW!
```

---

## 📚 Summary

**Issue:** Login ditolak untuk admin baru
**Root Cause:** Field `aktif` not set to true
**Fix Applied:** Updated both scripts to explicitly set `aktif = true`
**Testing:** ✅ Passed - Both admins can now login
**Status:** ✅ PRODUCTION READY

**Files Updated:**
- ✅ `create_new_admin.php` (fixed)
- ✅ `emergency_admin_reset.php` (fixed)
- ✅ `deploy_new_admin.bat` (ready)

**Verification:**
- ✅ Local testing passed
- ✅ aktif field confirmed TRUE
- ✅ Login check returns TRUE
- ✅ All credentials valid

---

**Deploy sekarang dengan: `deploy_new_admin.bat`**

Atau manual SSH dengan commands di atas.

Setelah deploy, admin baru pasti bisa login! ✅

---

**End of Documentation**
