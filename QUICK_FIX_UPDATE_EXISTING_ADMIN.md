# ⚡ Quick Fix - Update Existing Admin

**Problem:** Email `superadmin@mtsn2kotamalang.sch.id` sudah ada tapi `aktif=false`
**Solution:** UPDATE admin yang sudah ada instead of creating new

---

## 🚀 Fix via SSH Tinker

```bash
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
php artisan tinker
```

**Paste command ini:**

```php
use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Find existing admin
$admin = User::where('email', 'superadmin@mtsn2kotamalang.sch.id')->first();

if ($admin) {
    echo "✓ Found existing admin: {$admin->name}\n";
    echo "  Current aktif: " . ($admin->aktif ? "TRUE" : "FALSE") . "\n\n";

    // Update credentials and aktif
    $admin->role = 'admin';
    $admin->aktif = true;  // SET TO TRUE!
    $admin->password = Hash::make('SuperAdmin2026!@#');
    $admin->email_verified_at = now();
    $admin->save();

    echo "✅ UPDATED!\n";
    echo "  Role: {$admin->role}\n";
    echo "  Aktif: " . ($admin->aktif ? "TRUE" : "FALSE") . "\n";
    echo "  Email: {$admin->email}\n\n";
} else {
    echo "❌ Admin not found\n";
}

exit
```

---

## 🧹 Clear Caches

```bash
php artisan optimize:clear
```

---

## ✅ Test Login

```
URL: https://pmbm.mtsn2kotamalang.sch.id/login/admin
Email: superadmin@mtsn2kotamalang.sch.id
Password: SuperAdmin2026!@#
```

**Should work now!** ✅

---

## 🆘 Alternative - Use Admin Lama

Kalau masih tidak bisa, gunakan admin lama:

```bash
php artisan tinker
```

```php
$admin = App\Models\User::where('email', 'admin@mtsn2kotamalang.sch.id')->first();
$admin->aktif = true;
$admin->password = Hash::make('Admin123!@#');
$admin->email_verified_at = now();
$admin->save();
echo "✅ Admin lama fixed!";
exit
```

**Credentials:**
```
Email: admin@mtsn2kotamalang.sch.id
Password: Admin123!@#
```
