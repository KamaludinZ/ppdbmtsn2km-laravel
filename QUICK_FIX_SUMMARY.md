# QUICK FIX SUMMARY - Survey Overwrite Bug

🔥 **CRITICAL BUG FIXED** - Deploy Immediately!

---

## ROOT CAUSE

**File**: `app/Models/SurveyAnswer.php` line 18-25

```php
// ❌ BEFORE (CAUSING BUG):
protected $casts = [
    'jawaban' => 'array',  // Forces ALL to array → corruption!
];

// ✅ AFTER (FIXED):
protected $casts = [
    // 'jawaban' => 'array',  // REMOVED!
];
```

**Problem**: Laravel forced ALL answers to array, corrupting radio/select values in PostgreSQL JSONB

**Solution**: Remove array cast, let PostgreSQL handle JSONB naturally

---

## FILES TO UPLOAD (2 FILES)

1. **app/Models/SurveyAnswer.php** ← CRITICAL!
2. **app/Livewire/Pendaftar/TesPsikologi.php**

---

## DEPLOYMENT (4 STEPS)

### 1. UPLOAD FILES
Via FileZilla/WinSCP atau cPanel File Manager

### 2. CLEAR CACHE
```bash
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
php artisan view:clear
php artisan optimize:clear
php artisan cache:clear
php artisan config:clear
```

### 3. DELETE CORRUPT DATA
```bash
psql -U mtsnkot4_uspmbm -d mtsnkot4_dbpmbm
DELETE FROM survey_answers;
\q
```

### 4. TEST & VERIFY
- Submit survey baru
- Check database: semua jawaban berbeda (tidak overwrite)
- Check admin panel: semua RAW show `✓ ID`

---

## EXPECTED RESULT

**Database Before** ❌:
```
Q1-Q32: semua "126" (CORRUPT!)
```

**Database After** ✅:
```
Q1: 133, Q2: 129, Q3: 7, Q4: 29... (SEMUA BERBEDA!)
```

---

## FULL GUIDE

Lihat file lengkap:
- `DEPLOY_FINAL_FIX_MANUAL.md` (panduan lengkap 250+ baris)
- `ROOT_CAUSE_FOUND_SURVEY_OVERWRITE.md` (analisa teknis)

---

**Status**: ✅ READY TO DEPLOY
**Estimasi**: 30-45 menit
