# Fix Dashboard Error 500 - Quick Solution

**Problem:** `/admin/dashboard` error 500, tapi halaman lain (seperti `/admin/tentang`) work
**Root Cause:** Dashboard load EMIS completeness stats yang complex dan bisa timeout/error
**Solution:** Simplify/disable EMIS stats calculation

---

## ⚡ Quick Fix via Database

### Option 1: Clear Dashboard Cache

```bash
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

# Clear specific dashboard cache
php -r "require 'vendor/autoload.php'; \$app = require 'bootstrap/app.php'; \$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap(); Cache::forget('dashboard_emis_stats_' . App\Models\Pendaftar::count());"

# Clear all cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

---

## 🔧 Fix via File Edit

### Disable EMIS Stats Temporarily

Edit file: `app/Livewire/Admin/Dashboard.php`

**Find line 36:**
```php
$this->loadEmisCompletenessStats();
```

**Comment it out:**
```php
// TEMPORARY FIX: Comment this line to skip EMIS stats
// $this->loadEmisCompletenessStats();
```

---

## 📝 SQL Query to Check Data Issues

```sql
-- Check if there are pendaftar without jalur_pendaftaran_id
SELECT COUNT(*) as count_without_jalur
FROM pendaftar
WHERE jalur_pendaftaran_id IS NULL;

-- Check if all jalur_pendaftaran_id exist in jalur_pendaftaran table
SELECT COUNT(*) as count_invalid_jalur
FROM pendaftar p
LEFT JOIN jalur_pendaftaran jp ON p.jalur_pendaftaran_id = jp.id
WHERE p.jalur_pendaftaran_id IS NOT NULL
  AND jp.id IS NULL;
```

If any count > 0, fix with:

```sql
-- Temporarily set default jalur for records without valid jalur
UPDATE pendaftar
SET jalur_pendaftaran_id = (SELECT id FROM jalur_pendaftaran ORDER BY id LIMIT 1)
WHERE jalur_pendaftaran_id IS NULL
   OR jalur_pendaftaran_id NOT IN (SELECT id FROM jalur_pendaftaran);
```

---

## 🛠️ Full Fix - Replace Dashboard.php

Replace `loadEmisCompletenessStats()` method with simpler version:

```php
public function loadEmisCompletenessStats()
{
    try {
        // Simplified version - just count without complex calculation
        $totalPendaftar = Pendaftar::count();

        $this->emisCompletenessStats = [
            'total_pendaftar' => $totalPendaftar,
            'lengkap' => 0,
            'sebagian' => 0,
            'kurang_lengkap' => 0,
            'belum_diisi' => 0,
            'lengkap_percentage' => 0,
            'average_completion' => 0,
            'section_averages' => [],
        ];
    } catch (\Exception $e) {
        // Fallback to empty stats if error
        $this->emisCompletenessStats = [
            'total_pendaftar' => 0,
            'lengkap' => 0,
            'sebagian' => 0,
            'kurang_lengkap' => 0,
            'belum_diisi' => 0,
            'lengkap_percentage' => 0,
            'average_completion' => 0,
            'section_averages' => [],
        ];
    }
}
```

---

## 🔍 Check Error Logs

Via SSH or cPanel Terminal:

```bash
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
tail -100 storage/logs/laravel.log | grep -B5 -A20 "admin/dashboard"
```

This will show the exact error causing dashboard to fail.

---

## ✅ Test Steps

1. Apply one of the fixes above
2. Clear browser cache
3. Navigate to `/admin/dashboard`
4. Should load successfully now

---

**Recommended:** Use Option 1 (Clear Cache) first, then Option 2 if still error.
