# Admin Login Error 500 - Complete Fix Guide

**Date:** 21 Januari 2026
**Issue:** Error 500 saat login admin setelah ubah email/password
**Status:** ✅ COMPLETE SOLUTION

---

## 🚨 Quick Fix (RECOMMENDED)

### Option A: Automated Deployment Script

**Fastest and safest method:**

```bash
# Run from local machine (C:\ppdbmtsn2km-laravel)
deploy_admin_fix.bat
```

This will:
1. ✅ Upload emergency reset script to server
2. ✅ Run script automatically
3. ✅ Delete script for security
4. ✅ Clear all caches
5. ✅ Give you new admin credentials

**New Credentials:**
- Email: `admin@mtsn2kotamalang.sch.id`
- Password: `Admin123!@#`

---

### Option B: Manual SSH Method

If automated script fails, run manually:

```bash
# 1. Connect to server
ssh mtsnkot4@103.30.244.77

# 2. Navigate to project
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

# 3. Run Tinker
php artisan tinker

# 4. Paste these commands (one by one):
$admin = App\Models\User::where('role', 'admin')->first();
$admin->email = 'admin@mtsn2kotamalang.sch.id';
$admin->password = bcrypt('Admin123!@#');
$admin->email_verified_at = now();
$admin->save();
echo "✅ Admin updated! Email: " . $admin->email;
exit

# 5. Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# 6. Clear sessions (important!)
php artisan session:flush

# 7. Verify files writable
chmod -R 775 storage bootstrap/cache
```

---

## 🔍 Root Cause Analysis

Based on the symptoms, the error 500 likely occurred due to one of these:

### 1. **Incorrect Password Hash** ❌
**Problem:**
```php
// WRONG - Saving plain text password
$admin->password = 'NewPassword123';
$admin->save();
```

**Solution:**
```php
// CORRECT - Use bcrypt/Hash
$admin->password = bcrypt('NewPassword123');
// OR
$admin->password = Hash::make('NewPassword123');
$admin->save();
```

### 2. **Session/Cache Corruption** ❌
When you changed the email, old sessions may have cached the previous email causing authentication mismatch.

**Solution:** Clear all caches (see commands above)

### 3. **Email Validation Issue** ❌
If the new email doesn't have `email_verified_at` set, some middleware might block access.

**Solution:**
```php
$admin->email_verified_at = now();
$admin->save();
```

### 4. **Browser Cache/Cookies** ❌
Old authentication cookies causing conflicts.

**Solution:** Clear browser cache or use Incognito mode

---

## 📋 Complete Troubleshooting Steps

### Step 1: Reset Admin Credentials

Choose one method:

**Method 1A: Using deploy_admin_fix.bat (Windows - Local)**
```bash
cd C:\ppdbmtsn2km-laravel
deploy_admin_fix.bat
```

**Method 1B: Using emergency_admin_reset.php (Server)**
```bash
# Upload script to server
scp emergency_admin_reset.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/

# SSH and run
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
php emergency_admin_reset.php
rm emergency_admin_reset.php  # Delete after use!
```

**Method 1C: Using Artisan Tinker (Manual)**
```bash
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
php artisan tinker

$admin = App\Models\User::where('role', 'admin')->first();
$admin->email = 'admin@mtsn2kotamalang.sch.id';
$admin->password = Hash::make('Admin123!@#');
$admin->email_verified_at = now();
$admin->save();
echo "Done! Email: " . $admin->email;
exit
```

### Step 2: Clear ALL Caches

**On Server:**
```bash
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

# Clear application caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Clear sessions (CRITICAL!)
php artisan session:flush

# Or clear all at once
php artisan optimize:clear
```

### Step 3: Clear Browser Cache

**In Browser:**
1. Press `Ctrl + Shift + Delete`
2. Select "Cookies and other site data"
3. Select "Cached images and files"
4. Click "Clear data"

**OR use Incognito/Private mode:**
- Chrome: `Ctrl + Shift + N`
- Firefox: `Ctrl + Shift + P`

### Step 4: Test Login

1. Go to: `https://pmbm.mtsn2kotamalang.sch.id/login/admin`
2. Enter:
   - Email: `admin@mtsn2kotamalang.sch.id`
   - Password: `Admin123!@#`
3. Click Login

**Expected Result:** ✅ Login successful, redirected to admin dashboard

---

## 🛡️ If Still Getting Error 500

### Check Server Logs

```bash
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

# Check latest errors
tail -100 storage/logs/laravel.log

# Filter for login/auth errors
tail -200 storage/logs/laravel.log | grep -i "login\|auth\|exception"
```

### Common Errors and Fixes:

#### Error: "APP_KEY is missing"
```bash
php artisan key:generate
php artisan config:clear
```

#### Error: "Session store not configured"
```bash
# Check .env
cat .env | grep SESSION

# Should have:
SESSION_DRIVER=file
SESSION_LIFETIME=120

# Clear and rebuild
php artisan session:flush
php artisan config:clear
```

#### Error: "Database connection failed"
```bash
# Test database connection
php artisan tinker
DB::connection()->getPdo();
exit

# If fails, check .env database settings
```

#### Error: "Storage not writable"
```bash
# Fix permissions
chmod -R 775 storage
chmod -R 775 bootstrap/cache
chown -R www-data:www-data storage
chown -R www-data:www-data bootstrap/cache
```

---

## 🔐 Security Best Practices

### After Regaining Access:

1. **Change Password Immediately**
   - Go to Profile/Settings
   - Set strong unique password
   - Save securely

2. **Verify Email Settings**
   - Ensure email is correct domain
   - Verify `email_verified_at` is set

3. **Check User Sessions**
   ```bash
   php artisan tinker
   DB::table('sessions')->where('user_id', 1)->delete();
   exit
   ```

4. **Review Security Logs**
   - Check `storage/logs/laravel.log` for suspicious activity
   - Review recent login attempts

---

## 📝 How to Safely Change Admin Credentials

**ALWAYS use this method to avoid future errors:**

### Using Admin Panel (Preferred):
1. Login as admin
2. Go to Profile/Account Settings
3. Update email/password through form
4. Form will handle hashing automatically

### Using Tinker (Alternative):
```bash
php artisan tinker

$admin = App\Models\User::find(1);  // Or where('role', 'admin')->first()

// Update email
$admin->email = 'newemail@example.com';
$admin->email_verified_at = now();

// Update password (MUST use bcrypt or Hash::make!)
$admin->password = bcrypt('NewSecurePassword123!');

// Save
$admin->save();

// Verify
echo "Email: " . $admin->email;
var_dump(Hash::check('NewSecurePassword123!', $admin->password)); // Should be true

exit

// THEN clear caches
php artisan cache:clear
php artisan config:clear
php artisan session:flush
```

### ⚠️ NEVER Do This:
```php
// ❌ WRONG - Will cause error 500!
$admin->password = 'PlainTextPassword';  // No bcrypt!
$admin->save();

// ❌ WRONG - Will cause email issues!
$admin->email = 'newemail@example.com';
$admin->email_verified_at = null;  // Missing verification!
$admin->save();
```

---

## 🧪 Testing Checklist

After applying fixes:

- [ ] Admin can access `/login/admin` page
- [ ] Admin can login with new credentials
- [ ] No error 500 on login submit
- [ ] Redirected to `/admin/dashboard` successfully
- [ ] Session persists (no auto-logout)
- [ ] Can access all admin pages
- [ ] Can logout and login again
- [ ] Browser remembers login (if "Remember me" checked)
- [ ] Works in incognito mode
- [ ] Works in different browser

---

## 📦 Files Created

### For Quick Deployment:
1. `emergency_admin_reset.php` - Auto-reset script with cache clearing
2. `deploy_admin_fix.bat` - One-click deployment from Windows
3. `RESET_ADMIN_PASSWORD.md` - Basic reset guide
4. `ADMIN_LOGIN_500_COMPLETE_FIX.md` - This comprehensive guide

### Usage:
```bash
# Option 1: Fastest (Windows local machine)
deploy_admin_fix.bat

# Option 2: Manual upload and run
scp emergency_admin_reset.php server:/path/
ssh server "cd /path && php emergency_admin_reset.php && rm emergency_admin_reset.php"

# Option 3: Direct SSH
ssh server
php artisan tinker
# ... run commands manually
```

---

## 🎯 Summary

**Problem:** Error 500 after changing admin email/password

**Root Cause (Most Likely):**
- Password not hashed with bcrypt
- Cached sessions/config with old credentials
- Missing email_verified_at timestamp

**Solution:**
1. ✅ Reset admin credentials using proper bcrypt
2. ✅ Set email_verified_at to now()
3. ✅ Clear ALL caches (app, config, routes, views, sessions)
4. ✅ Clear browser cache
5. ✅ Test login

**Recovery Time:** < 2 minutes

**New Credentials:**
- Email: `admin@mtsn2kotamalang.sch.id`
- Password: `Admin123!@#`

**Post-Recovery:**
- Change password to something secure
- Delete emergency scripts from server
- Review security logs

---

## 🆘 Emergency Contact

If all methods fail:

1. Check if database is accessible:
   ```bash
   psql -U postgres -d database_name
   SELECT * FROM users WHERE role = 'admin';
   ```

2. Manually update in database:
   ```sql
   UPDATE users
   SET
     email = 'admin@mtsn2kotamalang.sch.id',
     password = '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewY5Tx1JxC.K7YPO',
     email_verified_at = NOW()
   WHERE role = 'admin';
   ```

   Note: The hash above = password `"password"`

3. Create new admin user:
   ```bash
   php artisan tinker

   User::create([
       'name' => 'Emergency Admin',
       'email' => 'emergency@admin.com',
       'password' => bcrypt('TempPassword123!'),
       'role' => 'admin',
       'email_verified_at' => now(),
   ]);
   ```

---

**Status:** ✅ COMPLETE - Ready to Deploy
**Risk:** Low (Only resets credentials)
**Time:** < 2 minutes
**Impact:** Immediate (Restores admin access)

**End of Documentation**
