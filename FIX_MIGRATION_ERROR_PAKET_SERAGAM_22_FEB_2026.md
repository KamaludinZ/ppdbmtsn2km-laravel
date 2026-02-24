# FIX MIGRATION ERROR - PAKET SERAGAM NULL VALUES - 22 FEB 2026

## 🚨 ERROR YANG TERJADI

```
SQLSTATE[23502]: Not null violation: 7 ERROR:
column "paket_seragam" of relation "seragam" contains null values

Migration: 2026_02_21_100000_change_paket_seragam_to_text
```

---

## 🔍 ROOT CAUSE

Migration gagal karena:
1. Ada data di production dengan `paket_seragam` = NULL
2. Migration mencoba change column type ke TEXT dengan constraint NOT NULL
3. PostgreSQL reject operation karena ada NULL values

---

## ✅ SOLUTION - OPTION 1: Quick Fix (RECOMMENDED)

### Step 1: Update NULL Values di Production

```bash
# SSH ke server
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

# Connect to database
psql -U mtsnkot4 -d mtsnkot4_ppdb

# Check berapa banyak NULL values
SELECT COUNT(*) FROM seragam WHERE paket_seragam IS NULL;

# Update NULL values ke empty string
UPDATE seragam SET paket_seragam = '' WHERE paket_seragam IS NULL;

# Verify - should return 0
SELECT COUNT(*) FROM seragam WHERE paket_seragam IS NULL;

# Exit psql
\q
```

### Step 2: Re-run Migration

```bash
# Masih di server, jalankan migration lagi
php artisan migrate

# Expected output:
# Running: 2026_02_21_100000_change_paket_seragam_to_text
# Migrated: ... (XX ms) DONE

# Verify migration success
php artisan migrate:status | grep "2026_02_21_100000"
# Should show "Ran"
```

### Step 3: Continue with Other Migrations

```bash
# Migration berikutnya akan auto run
# 2026_02_21_210000_add_belum_diisi_to_seragam_status_enum

# Clear cache
php artisan optimize:clear
php artisan view:clear

# Exit
exit
```

---

## ✅ SOLUTION - OPTION 2: Replace Migration File (Alternative)

Jika Option 1 tidak berhasil, gunakan migration yang sudah di-fix:

### Step 1: Upload Fixed Migration

```bash
# Di local machine (Windows)
scp "database/migrations/2026_02_21_100000_change_paket_seragam_to_text_FIXED.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/database/migrations/2026_02_21_100000_change_paket_seragam_to_text.php
```

**Note**: File FIXED akan **replace** file original

### Step 2: Re-run Migration

```bash
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

php artisan migrate

# Clear cache
php artisan optimize:clear

exit
```

---

## 📊 WHAT'S DIFFERENT IN FIXED VERSION

### Original Migration (BROKEN):
```php
Schema::table('seragam', function (Blueprint $table) {
    $table->text('paket_seragam')->change();
});
```
**Problem**: Laravel infers NOT NULL from existing column definition

### Fixed Migration (WORKING):
```php
// STEP 1: Update NULL values first
DB::statement("UPDATE seragam SET paket_seragam = '' WHERE paket_seragam IS NULL");

// STEP 2: Change column with nullable()
Schema::table('seragam', function (Blueprint $table) {
    $table->text('paket_seragam')->nullable()->change();
});
```
**Solution**:
1. Handle NULL values before column change
2. Add `->nullable()` to allow flexibility

---

## ✅ VERIFICATION CHECKLIST

After fix:

- [ ] Migration `2026_02_21_100000_change_paket_seragam_to_text` shows "Ran"
- [ ] No NULL values in paket_seragam column
- [ ] Column type is TEXT
- [ ] Next migration runs successfully

### Verify Commands:

```bash
# Check migration status
php artisan migrate:status | grep "2026_02_21"

# Check column type
psql -U mtsnkot4 -d mtsnkot4_ppdb -c "\d seragam" | grep paket_seragam

# Check NULL values
psql -U mtsnkot4 -d mtsnkot4_ppdb -c "SELECT COUNT(*) FROM seragam WHERE paket_seragam IS NULL;"
```

---

## 🚨 IF BOTH OPTIONS FAIL

### Manual SQL Fix (Last Resort):

```sql
-- Connect to database
psql -U mtsnkot4 -d mtsnkot4_ppdb

-- Step 1: Update NULLs
UPDATE seragam SET paket_seragam = '' WHERE paket_seragam IS NULL;

-- Step 2: Change column type manually
ALTER TABLE seragam ALTER COLUMN paket_seragam TYPE TEXT;

-- Verify
\d seragam

-- Exit
\q
```

Then mark migration as run manually:

```bash
# Insert migration record
php artisan tinker

>>> DB::table('migrations')->insert([
    'migration' => '2026_02_21_100000_change_paket_seragam_to_text',
    'batch' => DB::table('migrations')->max('batch') + 1
]);

>>> exit
```

---

## 📝 AFTER FIX - CONTINUE DEPLOYMENT

Setelah migration berhasil, lanjutkan dengan:

```bash
# Run remaining migrations
php artisan migrate

# Expected output:
# 2026_02_21_210000_add_belum_diisi_to_seragam_status_enum ... DONE

# Clear all cache
php artisan optimize:clear
php artisan view:clear
php artisan config:clear
php artisan cache:clear

# Verify all 3 migrations
php artisan migrate:status | grep "2026_02"
# All should show "Ran"
```

---

## 📚 RELATED FILES

**Fixed Migration**: `database/migrations/2026_02_21_100000_change_paket_seragam_to_text_FIXED.php`

**Documentation**:
- `RANGKUMAN_FINAL_DEPLOY_MANUAL_21_FEB_2026.md` (main deployment guide)
- `QUICK_REFERENCE_COMPLETE_21_FEB_2026.txt` (quick commands)

---

## ✅ SUMMARY

**Problem**: NULL values in production database
**Solution**: Update NULL values BEFORE changing column type
**Status**: ✅ FIX READY

**Recommended**: Use **OPTION 1** (Quick Fix) - faster and safer

**Time Required**: ~2 minutes

---

**Date**: 22 Februari 2026
**Error Type**: Database Migration Constraint Violation
**Risk Level**: LOW (easy fix)
**Impact**: Blocks deployment (must fix before continuing)

---

END OF FIX GUIDE
