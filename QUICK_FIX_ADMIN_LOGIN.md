# ⚡ Quick Fix - Admin Login Error 500

**Problem:** Cannot login to admin account, getting error 500
**Time to Fix:** < 2 minutes

---

## 🚀 Fastest Method (One Command)

### From Your Local Machine (Windows):

```bash
cd C:\ppdbmtsn2km-laravel
deploy_admin_fix.bat
```

**Done!** New credentials:
- Email: `admin@mtsn2kotamalang.sch.id`
- Password: `Admin123!@#`

---

## 🔧 Alternative: Manual SSH Method

### Step 1: Connect and Reset

```bash
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
php artisan tinker
```

### Step 2: Paste These Commands

```php
$admin = App\Models\User::where('role', 'admin')->first();
$admin->email = 'admin@mtsn2kotamalang.sch.id';
$admin->password = bcrypt('Admin123!@#');
$admin->email_verified_at = now();
$admin->save();
echo "✅ Done! Email: " . $admin->email;
exit
```

### Step 3: Clear Caches

```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan session:flush
```

### Step 4: Test Login

Go to: `https://pmbm.mtsn2kotamalang.sch.id/login/admin`

Login with:
- Email: `admin@mtsn2kotamalang.sch.id`
- Password: `Admin123!@#`

---

## ✅ Success Checklist

- [ ] Ran reset commands
- [ ] Cleared all caches
- [ ] Cleared browser cache (Ctrl+Shift+Delete)
- [ ] Can access /login/admin
- [ ] Login successful
- [ ] Redirected to dashboard

---

## 🆘 Still Not Working?

### Check Server Logs:
```bash
ssh mtsnkot4@103.30.244.77
tail -50 /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/storage/logs/laravel.log
```

### Clear Browser:
- Use Incognito mode (Ctrl+Shift+N)
- Or clear all cookies/cache

### Nuclear Option:
```bash
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
php artisan optimize:clear
chmod -R 775 storage bootstrap/cache
```

---

## 📚 Full Documentation

See `ADMIN_LOGIN_500_COMPLETE_FIX.md` for:
- Detailed troubleshooting
- Root cause analysis
- Security best practices
- Emergency recovery methods

---

**Quick Support:**
- Automated script: `deploy_admin_fix.bat`
- Emergency script: `emergency_admin_reset.php`
- Full guide: `ADMIN_LOGIN_500_COMPLETE_FIX.md`
