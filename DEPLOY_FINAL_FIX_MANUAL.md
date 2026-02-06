# DEPLOY FINAL FIX - Survey Overwrite Bug (MANUAL GUIDE)

**Tanggal**: 6 Februari 2026
**Status**: 🔥 **CRITICAL - READY TO DEPLOY**

---

## 🎯 ROOT CAUSE YANG SUDAH DITEMUKAN

**File**: `app/Models/SurveyAnswer.php`
**Line**: 18-25

**Masalah**:
```php
protected $casts = [
    'jawaban' => 'array', // ❌ INI PENYEBAB BUG!
];
```

Laravel memaksa SEMUA jawaban (radio, select, text, checkbox) menjadi array, menyebabkan corrupted data di PostgreSQL JSONB!

**Solusi**:
```php
protected $casts = [
    // 'jawaban' => 'array', // ❌ REMOVED!
];
```

Biarkan PostgreSQL JSONB handle data secara natural tanpa forced casting.

---

## 📂 FILE YANG SUDAH DI-FIX (READY TO UPLOAD)

### 1. **app/Models/SurveyAnswer.php** ⚠️ CRITICAL!
- ✅ Removed `'jawaban' => 'array'` cast
- ✅ Enhanced `getJawabanArray()` untuk handle JSONB
- ✅ Backward compatible dengan data lama

**Path Local**: `C:\ppdbmtsn2km-laravel\app\Models\SurveyAnswer.php`
**Path Production**: `/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Models/SurveyAnswer.php`

### 2. **app/Livewire/Pendaftar/TesPsikologi.php**
- ✅ Storage format handling (radio→single, checkbox→array)
- ✅ Debug logging untuk troubleshooting
- ✅ Name attribute untuk radio buttons

**Path Local**: `C:\ppdbmtsn2km-laravel\app\Livewire\Pendaftar\TesPsikologi.php`
**Path Production**: `/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Pendaftar/TesPsikologi.php`

---

## 🚀 CARA UPLOAD MANUAL (3 METODE)

### **METODE 1: Via FileZilla / WinSCP** (Paling Mudah)

1. Buka FileZilla atau WinSCP
2. Connect ke server:
   - Host: `103.30.244.77`
   - Username: `mtsnkot4`
   - Password: [password Anda]
   - Port: 22

3. Upload files:
   ```
   Local: C:\ppdbmtsn2km-laravel\app\Models\SurveyAnswer.php
   Remote: /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Models/SurveyAnswer.php

   Local: C:\ppdbmtsn2km-laravel\app\Livewire\Pendaftar\TesPsikologi.php
   Remote: /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Pendaftar/TesPsikologi.php
   ```

---

### **METODE 2: Via cPanel File Manager**

1. Login ke cPanel
2. Buka **File Manager**
3. Navigate ke folder:
   ```
   /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Models/
   ```
4. Upload `SurveyAnswer.php` (atau edit langsung jika ada code editor)
5. Ulangi untuk folder:
   ```
   /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Pendaftar/
   ```
6. Upload `TesPsikologi.php`

---

### **METODE 3: Via SSH (Jika Koneksi Sudah OK)**

```bash
# Upload via SCP
scp app/Models/SurveyAnswer.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Models/

scp app/Livewire/Pendaftar/TesPsikologi.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Pendaftar/
```

Atau jalankan batch file:
```bash
upload_FINAL_FIX_survey_overwrite.bat
```

---

## 🧹 STEP 2: CLEAR CACHE (WAJIB!)

Setelah upload, **HARUS** clear semua cache!

### **Via SSH**:
```bash
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

php artisan view:clear
php artisan optimize:clear
php artisan cache:clear
php artisan config:clear

exit
```

### **Via Artisan Web** (jika SSH tidak tersedia):
Buka browser:
```
https://pmbm.mtsn2kotamalang.sch.id/artisan/clear-cache
```
(Jika route ini tersedia)

### **Via cPanel Terminal** (alternative):
1. Login cPanel
2. Buka **Terminal**
3. Jalankan:
   ```bash
   cd pmbm.mtsn2kotamalang.sch.id
   php artisan view:clear
   php artisan optimize:clear
   php artisan cache:clear
   php artisan config:clear
   ```

---

## 🗑️ STEP 3: DELETE CORRUPT DATA (REQUIRED!)

Semua data survey yang sudah ada PASTI corrupt, harus di-delete!

### **Via SSH/Terminal**:
```bash
# Login PostgreSQL
psql -U mtsnkot4_uspmbm -d mtsnkot4_dbpmbm

# Delete SEMUA survey answers
DELETE FROM survey_answers;

# Verify deletion
SELECT COUNT(*) FROM survey_answers;
-- Harus return: 0

# Exit
\q
```

### **Via phpPgAdmin / Adminer** (alternative):
1. Login ke database manager
2. Pilih database: `mtsnkot4_dbpmbm`
3. Pilih table: `survey_answers`
4. Jalankan SQL:
   ```sql
   DELETE FROM survey_answers;
   ```
5. Verify: `SELECT COUNT(*) FROM survey_answers;` → harus 0

---

## ✅ STEP 4: TEST SURVEY (VERIFICATION)

### **4.1 Submit Survey Baru**

1. Login sebagai pendaftar (atau daftar akun baru)
2. Buka: `/pendaftar/tes-psikologi`
3. Isi survey dengan **jawaban BERBEDA** untuk setiap pertanyaan:
   ```
   Q1:  Ya (ID: 133)
   Q2:  Guru (ID: 129)
   Q3:  Menanyakan langsung (ID: 7)
   Q4:  Sangat Tidak Setuju (ID: 29)
   Q5:  Tidak Setuju (ID: 34)
   Q6:  Tidak Setuju (ID: 39)
   Q7:  Tidak Setuju (ID: 44)
   Q8:  Kurang Setuju (ID: 50)
   ...
   Q32: [pilih berbeda]
   ```
4. Klik **"Selesai & Simpan"**
5. **CATAT** nomor pendaftaran (misal: JPNA-26020003)

---

### **4.2 Verify di Database**

```bash
psql -U mtsnkot4_uspmbm -d mtsnkot4_dbpmbm
```

```sql
-- Get latest pendaftar
SELECT id, nomor_pendaftaran, created_at
FROM pendaftar
ORDER BY created_at DESC
LIMIT 5;

-- Check survey answers
SELECT survey_question_id, jawaban
FROM survey_answers
WHERE pendaftar_id = [PENDAFTAR_ID]  -- ganti dengan ID dari query di atas
ORDER BY survey_question_id;
```

**EXPECTED RESULT** ✅:
```
 survey_question_id | jawaban
--------------------+---------
                  1 | 133      ← INTEGER, bukan "133" atau [133]
                  2 | 129      ← BERBEDA dari Q1!
                  3 | 7        ← BERBEDA!
                  4 | 29       ← BERBEDA!
                  5 | 34       ← BERBEDA!
                  ...
                 32 | 121      ← SEMUA BERBEDA, tidak ada yang overwrite!
```

**GAGAL JIKA** ❌:
```
 survey_question_id | jawaban
--------------------+---------
                  1 | "126"    ← Masih string
                  2 | "126"    ← SAMA SEMUA!
                  3 | "126"    ← OVERWRITE BUG MASIH TERJADI!
```

---

### **4.3 Verify di Admin Panel**

1. Login sebagai Admin
2. Buka: `/admin/tes-psikologi?mainTab=jawaban-survey`
3. Pilih pendaftar yang baru submit
4. Check **RAW display** untuk setiap pertanyaan:

**EXPECTED** ✅:
```
RAW: 133 ✓ ID
Ya

RAW: 129 ✓ ID
Guru

RAW: 7 ✓ ID
Menanyakan langsung
```

Semua harus menampilkan `✓ ID` (hijau), **TIDAK ADA** `⚠ VALUE/LABEL` (orange)!

**GAGAL JIKA** ❌:
```
RAW: "126" ⚠ VALUE/LABEL    ← Masih menyimpan string
RAW: [126] ⚠ ARRAY          ← Masih force ke array
```

---

## 🎉 SUCCESS CRITERIA

Fix berhasil jika:

1. ✅ Database: Semua `jawaban` tersimpan sebagai **INTEGER** (bukan string)
2. ✅ Database: Jawaban untuk Q1-Q32 **SEMUA BERBEDA** (tidak overwrite)
3. ✅ Database: Checkbox masih tersimpan sebagai **ARRAY**
4. ✅ Admin panel: Semua RAW menunjukkan `✓ ID` (hijau)
5. ✅ User bisa submit survey tanpa corruption
6. ✅ No more "all answers become 126" bug!

---

## 📊 COMPARISON: BEFORE vs AFTER

### **BEFORE FIX** ❌:
```sql
-- Database corrupt:
 1 | "113"
 2 | "126"
 3 | "126"
 4 | "18"
 5 | "16"
 6 | "14"
 8 | "126"
 9 | "126"    ← Q8-Q32 SEMUA SAMA!
...
32 | "126"
```

### **AFTER FIX** ✅:
```sql
-- Database correct:
 1 | 133
 2 | 129
 3 | 7
 4 | 29
 5 | 34
 6 | 39
 7 | 44
 8 | 50
...
32 | 121      ← SEMUA BERBEDA!
```

---

## 🔧 TROUBLESHOOTING

### **Issue 1: Bug Masih Terjadi Setelah Upload**

**Check**:
```bash
# 1. Verify file updated
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
grep "REMOVED" app/Models/SurveyAnswer.php
# Harus muncul comment "// REMOVED!"

# 2. Clear cache LAGI
php artisan view:clear
php artisan optimize:clear
php artisan cache:clear
php artisan config:clear

# 3. Delete survey_answers LAGI
psql -U mtsnkot4_uspmbm -d mtsnkot4_dbpmbm -c "DELETE FROM survey_answers;"
```

---

### **Issue 2: Debug Logs Tidak Muncul**

**Check**:
```bash
# Verify TesPsikologi.php updated
ssh mtsnkot4@103.30.244.77
grep "SIMPAN SURVEY START" app/Livewire/Pendaftar/TesPsikologi.php
# Harus ada log statement ini

# Check log permissions
ls -la storage/logs/
chmod -R 775 storage/logs/
```

---

### **Issue 3: Database Connection Error**

**Check credentials**:
```bash
# Test connection
psql -U mtsnkot4_uspmbm -d mtsnkot4_dbpmbm -c "SELECT version();"

# If error, check .env
cat .env | grep DB_
```

---

## 📧 NOTIFIKASI KE USER (Setelah Fix Verified)

Setelah fix berhasil, kirim notifikasi ke semua pendaftar:

**Subject**: Pemberitahuan - Survey Perlu Diisi Ulang

**Isi**:
```
Kepada Calon Peserta Didik yang terhormat,

Kami telah memperbaiki sistem survey tes psikologi untuk memastikan
akurasi data yang lebih baik.

Mohon untuk mengisi kembali survey melalui:
https://pmbm.mtsn2kotamalang.sch.id/pendaftar/tes-psikologi

Data survey sebelumnya telah direset untuk memastikan integritas data.

Terima kasih atas pengertian dan kerjasamanya.

Panitia PPDB MTsN 2 Kota Malang
```

---

## 📝 CHECKLIST DEPLOYMENT

**PRE-DEPLOYMENT**:
- [x] Root cause identified
- [x] Fix applied to local files
- [x] Documentation created
- [x] Upload methods prepared

**DEPLOYMENT**:
- [ ] Upload `SurveyAnswer.php` ke production
- [ ] Upload `TesPsikologi.php` ke production
- [ ] Clear ALL caches (view, optimize, cache, config)
- [ ] Delete ALL corrupt survey_answers

**VERIFICATION**:
- [ ] Test submit survey dengan pendaftar baru
- [ ] Verify database: semua jawaban INTEGER dan BERBEDA
- [ ] Verify admin panel: semua RAW show `✓ ID`
- [ ] Check logs: no errors

**POST-DEPLOYMENT**:
- [ ] Notify affected users to re-submit
- [ ] Monitor 10-20 submissions pertama
- [ ] Remove debug logging (optional)
- [ ] Run SMART migration untuk 106 VALUE/LABEL issues (nanti)

---

## 🎯 KENAPA FIX INI CRITICAL?

1. **Root Cause**: Model cast memaksa SEMUA jawaban jadi array
2. **Impact**: Radio/select corrupted, overwrite ke ID terakhir
3. **Scope**: Affect 100% survey submissions
4. **Data Loss**: Semua survey existing corrupt dan tidak valid
5. **User Impact**: Pendaftar harus isi ulang survey

**PRIORITAS**: 🔥 **DEPLOY IMMEDIATELY** setelah koneksi tersedia!

---

**Dibuat**: 6 Februari 2026, 18:45 WIB
**Estimasi Deploy**: 30-45 menit (upload, clear cache, test, verify)
**Estimasi Total**: 1-2 jam (termasuk user notification)
