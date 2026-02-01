# ⚠️ Database Migrations - 30 Januari 2026

## CRITICAL: Database Changes Not Yet Documented

**Status**: ⚠️ **REQUIRES ATTENTION BEFORE PRODUCTION**
**Date**: 30 Januari 2026
**Total Migrations**: 3 files (2 RAN, 1 PENDING)

---

## 📋 Migration Status Overview

```bash
$ php artisan migrate:status | grep "2026_01_30"

✅ [RAN]     2026_01_30_000001_add_nominal_awal_and_waktu_pembayaran_fields
✅ [RAN]     2026_01_30_123759_update_status_proses_to_menunggu_in_daftar_ulang
⚠️ [PENDING] 2026_01_30_000000_remove_old_ukuran_fields_from_seragam_table
```

---

## Migration #1: Remove Old Ukuran Fields ⚠️ PENDING

### File: `2026_01_30_000000_remove_old_ukuran_fields_from_seragam_table.php`
### Status: ⚠️ **PENDING** (NOT YET RUN)

### What It Does:
Menghapus field lama/tidak terpakai dari tabel `seragam`

### Fields to be REMOVED (9 fields):
```sql
✂️ tinggi_badan           -- Not used anymore
✂️ berat_badan            -- Not used anymore
✂️ ukuran_baju            -- Replaced by ukuran_baju_olahraga & ukuran_baju_harian
✂️ ukuran_celana          -- Replaced by ukuran_celana_olahraga & ukuran_celana_rok_harian
✂️ lingkar_dada           -- Not used anymore
✂️ lingkar_pinggang       -- Not used anymore
✂️ panjang_lengan         -- Not used anymore
✂️ ukuran_sepatu          -- Not used anymore
✂️ ukuran_topi            -- Replaced by ukuran_kopyah
```

### Reason:
Field-field ini telah digantikan dengan field yang lebih spesifik:
- `ukuran_jas`
- `ukuran_baju_olahraga`
- `ukuran_celana_olahraga`
- `ukuran_baju_harian`
- `ukuran_celana_rok_harian`
- `ukuran_kopyah`

### ⚠️ IMPORTANT BEFORE RUNNING:

**Data Check Required**:
```sql
-- Check if old fields contain data
SELECT
    COUNT(*) as total_records,
    COUNT(tinggi_badan) as has_tinggi_badan,
    COUNT(berat_badan) as has_berat_badan,
    COUNT(ukuran_baju) as has_ukuran_baju,
    COUNT(ukuran_celana) as has_ukuran_celana,
    COUNT(lingkar_dada) as has_lingkar_dada,
    COUNT(lingkar_pinggang) as has_lingkar_pinggang,
    COUNT(panjang_lengan) as has_panjang_lengan,
    COUNT(ukuran_sepatu) as has_ukuran_sepatu,
    COUNT(ukuran_topi) as has_ukuran_topi
FROM seragam;
```

**If Data Exists in Old Fields**:
1. ⚠️ **DO NOT RUN THIS MIGRATION YET**
2. First migrate data to new fields
3. Verify all data migrated correctly
4. Then run this migration

**If No Data or Data Already Migrated**:
```bash
# Safe to run
php artisan migrate
```

### Rollback:
```bash
# If needed (will restore old fields)
php artisan migrate:rollback --step=1
```

---

## Migration #2: Add Nominal Awal & Waktu Pembayaran ✅ RAN

### File: `2026_01_30_000001_add_nominal_awal_and_waktu_pembayaran_fields.php`
### Status: ✅ **RAN** (Already Applied)

### What It Does:
Menambahkan field untuk tracking nominal awal dan waktu pembayaran

### Changes to `daftar_ulang` table:
```sql
✅ nominal_biaya_awal          JSON        -- Stores original nominal before any changes
✅ waktu_pembayaran_madrasah   TIMESTAMP   -- When payment was made
```

### Changes to `seragam` table:
```sql
✅ nominal_seragam_awal        JSON        -- Stores original nominal before any changes
✅ waktu_pembayaran_seragam    TIMESTAMP   -- When payment was made
```

### Purpose:
**Audit Trail & History Tracking**:
- Track original nominal values before admin changes
- Record exact payment timestamp
- Enable payment history analysis
- Support financial reporting

### Example Usage:
```php
// When admin confirms payment
$daftarUlang->nominal_biaya_awal = $daftarUlang->nominal_biaya; // Save original
$daftarUlang->waktu_pembayaran_madrasah = now(); // Record payment time
$daftarUlang->save();

// Later, can compare:
$original = $daftarUlang->nominal_biaya_awal;
$current = $daftarUlang->nominal_biaya;
$wasChanged = $original !== $current;
```

### Model Updates:
```php
// DaftarUlang.php
protected $fillable = [
    // ... existing fields
    'nominal_biaya_awal',            // ✅ ADDED
    'waktu_pembayaran_madrasah',     // ✅ ADDED
];

protected $casts = [
    'nominal_biaya_awal' => 'array',         // ✅ ADDED
    'waktu_pembayaran_madrasah' => 'datetime', // ✅ ADDED
];

// Seragam.php
protected $fillable = [
    // ... existing fields
    'nominal_seragam_awal',          // ✅ ADDED
    'waktu_pembayaran_seragam',      // ✅ ADDED
];

protected $casts = [
    'nominal_seragam_awal' => 'array',       // ✅ ADDED
    'waktu_pembayaran_seragam' => 'datetime', // ✅ ADDED
];
```

---

## Migration #3: Update Status 'proses' to 'menunggu' ✅ RAN

### File: `2026_01_30_123759_update_status_proses_to_menunggu_in_daftar_ulang.php`
### Status: ✅ **RAN** (Already Applied)

### What It Does:
Update semua record dengan status 'proses' menjadi 'menunggu'

### SQL Executed:
```sql
UPDATE daftar_ulang
SET status = 'menunggu'
WHERE status = 'proses';
```

### Reason:
**Status Terminology Consistency**:
- **Old**: 'proses' (in progress) - misleading for uploaded files waiting review
- **New**: 'menunggu' (waiting) - more accurate for pending review state

### Status Flow (Updated):
```
📤 Upload Bukti Transfer
    ↓
⏳ Status: 'menunggu' (waiting for admin review)
    ↓
👨‍💼 Admin Reviews
    ↓
    ├─ ✅ Approved → Status: 'diterima'
    └─ ❌ Rejected → Status: 'ditolak'
```

### Impact:
- All existing 'proses' records → changed to 'menunggu'
- UI labels should use "Menunggu Verifikasi" instead of "Dalam Proses"
- More accurate status representation

---

## 📊 Summary of Changes

### Database Schema Changes:

| Table | Action | Fields | Status |
|-------|--------|--------|--------|
| `seragam` | DROP | 9 old fields | ⚠️ PENDING |
| `daftar_ulang` | ADD | 2 new fields | ✅ RAN |
| `seragam` | ADD | 2 new fields | ✅ RAN |
| `daftar_ulang` | UPDATE | status values | ✅ RAN |

### Model Updates:

| Model | Changes | Status |
|-------|---------|--------|
| `DaftarUlang` | +2 fillable, +2 casts | ✅ Updated |
| `Seragam` | -9 old fields (pending), +2 fillable, +2 casts | ✅ Updated |

---

## 🚨 PRODUCTION DEPLOYMENT CHECKLIST

### Before Deploying:

#### 1. Check Migration #1 (PENDING)
```bash
# On LOCAL first:
php artisan migrate:status | grep "2026_01_30_000000"

# If PENDING:
# Check if safe to run (no data in old fields)
SELECT * FROM seragam
WHERE tinggi_badan IS NOT NULL
   OR berat_badan IS NOT NULL
   OR ukuran_baju IS NOT NULL
   OR ukuran_celana IS NOT NULL;

# If returns 0 rows: SAFE to run
# If returns data: MIGRATE DATA FIRST before running
```

#### 2. Backup Database
```bash
# CRITICAL: Backup before migrations
pg_dump -U mtsnkot4_uspmbm mtsnkot4_dbpmbm > backup_before_migration_$(date +%Y%m%d).sql
```

#### 3. Run Migrations on Production
```bash
# Connect to server
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

# Check status
php artisan migrate:status | grep "2026_01_30"

# Run pending migrations (if safe)
php artisan migrate

# Verify
php artisan migrate:status | grep "2026_01_30"
# All should show [RAN]
```

#### 4. Upload Model Files
```bash
# Upload updated model files:
app/Models/DaftarUlang.php
app/Models/Seragam.php
```

#### 5. Clear Caches
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

#### 6. Verify Data
```sql
-- Check new fields exist
\d daftar_ulang  -- Should show nominal_biaya_awal, waktu_pembayaran_madrasah
\d seragam       -- Should show nominal_seragam_awal, waktu_pembayaran_seragam

-- Check old fields removed (if migration #1 ran)
\d seragam       -- Should NOT show tinggi_badan, berat_badan, etc.

-- Check status updates
SELECT status, COUNT(*)
FROM daftar_ulang
GROUP BY status;
-- Should NOT have 'proses', should have 'menunggu'
```

---

## 📝 Code Changes Required

### Update Livewire Components:

#### Admin/DaftarUlang.php
```php
// When admin confirms payment, track original nominal and time
public function terimaSeragam($id)
{
    $seragam = Seragam::findOrFail($id);

    // Save original nominal if not yet saved
    if (!$seragam->nominal_seragam_awal) {
        $seragam->nominal_seragam_awal = $seragam->nominal_seragam;
    }

    // Record payment time
    $seragam->waktu_pembayaran_seragam = now();
    $seragam->status_seragam = 'diterima';
    $seragam->save();
}
```

#### Panitia/DaftarUlang.php
```php
// Same logic for panitia confirmation
```

#### Admin/Pendaftar.php
```php
// Update status label display
public function getStatusLabelProperty()
{
    return match($this->status) {
        'menunggu' => 'Menunggu Verifikasi',  // ✅ UPDATED (was 'proses')
        'diterima' => 'Diterima',
        'ditolak' => 'Ditolak',
        default => ucfirst($this->status),
    };
}
```

### Update Blade Views:

#### Status Badge Display
```blade
{{-- Update status display --}}
@if($daftarUlang->status === 'menunggu')
    <span class="badge bg-warning">Menunggu Verifikasi</span>
@elseif($daftarUlang->status === 'diterima')
    <span class="badge bg-success">Diterima</span>
@elseif($daftarUlang->status === 'ditolak')
    <span class="badge bg-danger">Ditolak</span>
@endif
```

---

## 🔍 Testing Checklist

### After Migration:

#### Test 1: Check Schema
```bash
# Should succeed without errors
php artisan tinker

>>> Schema::hasColumn('daftar_ulang', 'nominal_biaya_awal')
=> true

>>> Schema::hasColumn('daftar_ulang', 'waktu_pembayaran_madrasah')
=> true

>>> Schema::hasColumn('seragam', 'nominal_seragam_awal')
=> true

>>> Schema::hasColumn('seragam', 'waktu_pembayaran_seragam')
=> true

# If migration #1 ran:
>>> Schema::hasColumn('seragam', 'tinggi_badan')
=> false  // Should be false (removed)

>>> Schema::hasColumn('seragam', 'ukuran_baju')
=> false  // Should be false (removed)
```

#### Test 2: Check Data Integrity
```sql
-- All daftar_ulang records should have valid status
SELECT DISTINCT status FROM daftar_ulang;
-- Should return: menunggu, diterima, ditolak (NO 'proses')

-- New fields should accept data
UPDATE seragam
SET nominal_seragam_awal = '{"madrasah":1850000,"seragam":1750000}',
    waktu_pembayaran_seragam = NOW()
WHERE id = 1;
-- Should succeed

SELECT nominal_seragam_awal, waktu_pembayaran_seragam
FROM seragam
WHERE id = 1;
-- Should return JSON and timestamp
```

#### Test 3: Test UI
```
✅ Visit /admin/daftar-ulang
✅ Check status labels show "Menunggu Verifikasi" not "Dalam Proses"
✅ Upload bukti transfer → status should be 'menunggu'
✅ Admin approve → status should be 'diterima'
✅ Check nominal_awal and waktu_pembayaran saved correctly
```

---

## ⚠️ IMPORTANT WARNINGS

### 1. Migration #1 (Remove Old Fields) - PENDING
```
🚨 THIS MIGRATION WILL PERMANENTLY DELETE DATA

Before running:
1. ✅ Verify no data in old fields OR
2. ✅ Migrate all data to new fields OR
3. ⚠️ DO NOT RUN if data will be lost

Check first:
SELECT COUNT(*) FROM seragam WHERE tinggi_badan IS NOT NULL;
If > 0: MIGRATE DATA FIRST!
```

### 2. Status Change Impact
```
⚠️ All 'proses' records changed to 'menunggu'

Impact:
- UI labels must be updated
- Status filters in code must be updated
- Any hardcoded 'proses' checks will fail
- Update documentation for users
```

### 3. Rollback Considerations
```
⚠️ Rollback will restore old status values

After rollback:
- 'menunggu' → 'proses'
- Affects ALL records, not just changed ones
- May cause confusion if done after users adapted to new terms
```

---

## 📚 Related Documentation

### Files to Review:
```
✅ app/Models/DaftarUlang.php - Check fillable & casts
✅ app/Models/Seragam.php - Check fillable & casts
✅ app/Livewire/Admin/DaftarUlang.php - Update payment confirmation logic
✅ app/Livewire/Panitia/DaftarUlang.php - Update payment confirmation logic
✅ resources/views/livewire/admin/daftar-ulang.blade.php - Update status labels
✅ resources/views/livewire/panitia/daftar-ulang.blade.php - Update status labels
```

### Documentation Files:
```
📄 PRODUCTION_DEPLOYMENT_3_DAYS_COMPLETE.md - Main deployment guide
📄 DATABASE_MIGRATIONS_30_JAN_2026.md - This file
```

---

## 📞 Support & Troubleshooting

### Common Issues:

#### Issue 1: Migration Fails
```bash
# Error: Column already exists
# Cause: Migration already ran but status not updated
# Fix:
php artisan migrate:rollback --step=1
php artisan migrate

# Or mark as run manually:
INSERT INTO migrations (migration, batch)
VALUES ('2026_01_30_000001_add_nominal_awal_and_waktu_pembayaran_fields',
        (SELECT MAX(batch) FROM migrations));
```

#### Issue 2: Data Lost After Remove Fields Migration
```bash
# If migration #1 ran before data migration:
# 1. Check if backup exists
ls -la backup_before_migration_*.sql

# 2. Rollback migration
php artisan migrate:rollback --step=1

# 3. Restore data from backup if needed
psql -U username database < backup_file.sql

# 4. Migrate data properly
# 5. Re-run migration
```

#### Issue 3: Status Still Shows 'proses'
```sql
-- Check if migration ran
SELECT * FROM daftar_ulang WHERE status = 'proses';

-- If returns rows, migration didn't run:
UPDATE daftar_ulang SET status = 'menunggu' WHERE status = 'proses';
```

---

## ✅ Final Checklist

Before marking as complete:

### Pre-Deployment:
- [ ] Database backup created
- [ ] Migration #1 safety checked (no data loss)
- [ ] All 3 migrations tested on staging/local
- [ ] Models updated (DaftarUlang, Seragam)
- [ ] Livewire components updated
- [ ] Blade views updated (status labels)

### Deployment:
- [ ] Models uploaded to production
- [ ] Migrations run successfully
- [ ] Migration status verified (all RAN)
- [ ] Database schema verified
- [ ] Caches cleared

### Post-Deployment:
- [ ] Data integrity verified
- [ ] UI tested (status labels correct)
- [ ] Payment flow tested
- [ ] No errors in logs
- [ ] User documentation updated

---

**Date**: 30 Januari 2026
**Status**: ⚠️ **REQUIRES ATTENTION**
**Critical**: 1 migration PENDING (check before running)
**Models**: ✅ Updated (DaftarUlang, Seragam)
**Testing**: ⚠️ Required before production

🚨 **IMPORTANT**: Review and test migration #1 before deploying to production!
