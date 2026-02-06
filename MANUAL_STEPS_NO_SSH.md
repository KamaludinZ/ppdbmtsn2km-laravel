# MANUAL STEPS - Bug Survey Masih Overwrite (No SSH Access)

**Status**: SSH connection refused - perlu manual steps
**Urgent**: Perlu verify dan fix secara manual

---

## 🎯 PRIORITY: VERIFY FILE UPLOADED CORRECTLY

### STEP 1: Check via cPanel File Manager

1. **Login cPanel**
2. **File Manager** → Navigate ke: `/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/`

### STEP 2: Check File #1 - SurveyAnswer.php

Navigate ke: `app/Models/SurveyAnswer.php`

**KLIK KANAN → Edit** (atau Code Editor)

**CARI LINE 18-25** (gunakan Ctrl+F):

**HARUS TERLIHAT INI** ✅:
```php
protected $casts = [
    // 'jawaban' => 'array', // ❌ REMOVED! This was causing the overwrite bug!
];
```

**JIKA MASIH SEPERTI INI** ❌:
```php
protected $casts = [
    'jawaban' => 'array',
];
```

→ **FILE BELUM TER-UPLOAD!** Upload ulang dari: `C:\ppdbmtsn2km-laravel\app\Models\SurveyAnswer.php`

---

### STEP 3: Check File #2 - TesPsikologi.php

Navigate ke: `app/Livewire/Pendaftar/TesPsikologi.php`

**KLIK KANAN → Edit**

**CARI "SIMPAN SURVEY START"** (Ctrl+F):

**HARUS ADA INI** ✅:
```php
\Log::info('=== SIMPAN SURVEY START ===');
\Log::info('Pendaftar ID: ' . $this->pendaftar->id);
```

Sekitar line 310-315.

**JIKA TIDAK ADA** ❌:
→ **FILE BELUM TER-UPLOAD!** Upload ulang dari: `C:\ppdbmtsn2km-laravel\app\Livewire\Pendaftar\TesPsikologi.php`

---

## 🧹 STEP 4: CLEAR CACHE (CRITICAL!)

### Via cPanel Terminal:

1. Login cPanel
2. **Terminal** (atau Advanced → Terminal)
3. Jalankan commands ini:

```bash
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

php artisan view:clear
php artisan optimize:clear
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

**ATAU Via File Manager:**

Navigate ke: `bootstrap/cache/`

**DELETE files ini** (jika ada):
- `config.php`
- `routes-v7.php`
- `packages.php`
- `services.php`

**Tapi JANGAN delete** folder `storage/`!

---

## 🗑️ STEP 5: DELETE CORRUPT DATA

### Via phpPgAdmin / Database Manager:

1. Login cPanel → **phpPgAdmin** atau **Adminer**
2. Pilih database: `mtsnkot4_dbpmbm`
3. **SQL Query**:

```sql
DELETE FROM survey_answers;
```

4. Verify: `SELECT COUNT(*) FROM survey_answers;` → harus return **0**

---

## ✅ STEP 6: TEST SURVEY ULANG

1. Login sebagai **pendaftar** (atau daftar akun baru)
2. Buka: `https://pmbm.mtsn2kotamalang.sch.id/pendaftar/tes-psikologi`
3. Isi survey dengan jawaban **SANGAT BERBEDA**:

```
Q1:  Pilih option PERTAMA
Q2:  Pilih option KEDUA
Q3:  Pilih option KETIGA
Q4:  Pilih option KEEMPAT
Q5:  Pilih option KELIMA
...
Q32: Pilih option berbeda lagi

JANGAN PILIH YANG SAMA!
```

4. Klik **"Selesai & Simpan"**
5. **CATAT nomor pendaftaran** (misal: JPNA-26020003)

---

## 📊 STEP 7: CHECK DATABASE

Via phpPgAdmin:

```sql
-- Get latest pendaftar
SELECT id, nomor_pendaftaran, created_at
FROM pendaftar
ORDER BY created_at DESC
LIMIT 5;
```

Copy ID dari pendaftar yang baru test, lalu:

```sql
-- Check jawaban (GANTI [ID] dengan ID dari query di atas)
SELECT survey_question_id, jawaban, created_at
FROM survey_answers
WHERE pendaftar_id = [ID]
ORDER BY survey_question_id;
```

### **EXPECTED RESULT** ✅ (FIX BERHASIL):
```
 survey_question_id | jawaban
--------------------+---------
                  1 | 133      ← INTEGER (bukan "133")
                  2 | 129      ← BERBEDA dari Q1!
                  3 | 7        ← BERBEDA!
                  4 | 29       ← BERBEDA!
                  5 | 34       ← BERBEDA!
                  ...
                 32 | 121      ← SEMUA BERBEDA!
```

### **IF BUG MASIH TERJADI** ❌:
```
 survey_question_id | jawaban
--------------------+---------
                  1 | "126"    ← Masih string
                  2 | "126"    ← OVERWRITE!
                  3 | "126"    ← SEMUA SAMA!
```

---

## 📋 STEP 8: CHECK LOGS

Via cPanel File Manager:

1. Navigate ke: `storage/logs/laravel.log`
2. **Download** file ke local (size mungkin besar)
3. Buka dengan Notepad++ atau text editor
4. **Search** (Ctrl+F): `SIMPAN SURVEY START`

### **IF LOG ADA** ✅:
**Copy semua log dari "SIMPAN SURVEY START" sampai "SIMPAN SURVEY END"**

Kirim ke saya log ini! Format:
```
[2026-02-06 19:30:00] production.INFO: === SIMPAN SURVEY START ===
[2026-02-06 19:30:00] production.INFO: Pendaftar ID: 132
[2026-02-06 19:30:00] production.INFO: Total survey answers in memory: 32
[2026-02-06 19:30:00] production.INFO: Survey answers structure: {"answers":{"1":133,"2":129,...}}
[2026-02-06 19:30:00] production.INFO: Processing Q1: 133
[2026-02-06 19:30:00] production.INFO: Q1: Type = radio
[2026-02-06 19:30:00] production.INFO: Q1: Single value = 133
...
```

### **IF LOG TIDAK ADA** ❌:
→ File `TesPsikologi.php` **BELUM TER-UPLOAD** atau cache belum clear!

---

## 🔄 IF BUG MASIH TERJADI - RE-UPLOAD FILES

### Via FileZilla/WinSCP:

1. **Connect** ke server (103.30.244.77, port 21 untuk FTP, atau port 22 untuk SFTP jika ada)
2. **Navigate** ke: `/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/`

3. **Upload File #1**:
   - Local: `C:\ppdbmtsn2km-laravel\app\Models\SurveyAnswer.php`
   - Remote: `app/Models/SurveyAnswer.php`
   - **OVERWRITE existing file**

4. **Upload File #2**:
   - Local: `C:\ppdbmtsn2km-laravel\app\Livewire\Pendaftar\TesPsikologi.php`
   - Remote: `app/Livewire/Pendaftar/TesPsikologi.php`
   - **OVERWRITE existing file**

5. **Verify file size dan timestamp** di server (harus berubah!)

6. **Ulangi STEP 4-8** (clear cache, delete data, test, check)

---

### Via cPanel File Manager:

1. **Navigate** ke folder yang sesuai
2. **Upload** file dari local (C:\ppdbmtsn2km-laravel\...)
3. Pilih **"Overwrite existing files"**
4. **Verify** upload sukses (check file size)

---

## 📤 TOLONG KIRIM HASIL INI:

### 1️⃣ Confirm Files Uploaded:
- [ ] `app/Models/SurveyAnswer.php` line 24 ada comment "// REMOVED!"?
- [ ] `app/Livewire/Pendaftar/TesPsikologi.php` line 312 ada log statement?

### 2️⃣ Confirm Cache Cleared:
- [ ] Sudah run `php artisan optimize:clear`?
- [ ] Atau sudah delete file di `bootstrap/cache/`?

### 3️⃣ Database Result:
**Copy paste hasil query ini:**
```sql
SELECT survey_question_id, jawaban
FROM survey_answers
WHERE pendaftar_id = [ID]
ORDER BY survey_question_id;
```

Paste di sini:
```
[Paste hasil query database]
```

### 4️⃣ Log Output (if ada):
**Cari "SIMPAN SURVEY" di laravel.log, paste di sini:**
```
[Paste log dari SIMPAN SURVEY START sampai END]
```

---

## 🚨 KEMUNGKINAN PENYEBAB BUG MASIH TERJADI:

### A. File Belum Ter-Upload ❌
**Cek**: Buka file di cPanel, search "REMOVED" di SurveyAnswer.php
**Fix**: Upload ulang file

### B. Cache Belum Clear ❌
**Cek**: File di `bootstrap/cache/config.php` masih ada?
**Fix**: Delete file atau run `php artisan optimize:clear`

### C. Test Pakai Data Lama ❌
**Cek**: Sudah run `DELETE FROM survey_answers;`?
**Fix**: Delete data lama, test dengan pendaftar baru

### D. Ada Bug Lain Lebih Dalam ❌
**Need**: Log output untuk analisa

---

## ⚡ QUICK CHECKLIST:

```
[ ] 1. Verify SurveyAnswer.php uploaded (line 24: "// REMOVED!")
[ ] 2. Verify TesPsikologi.php uploaded (line 312: log statement)
[ ] 3. Clear cache (artisan atau delete files)
[ ] 4. Delete survey_answers (DELETE FROM survey_answers)
[ ] 5. Test dengan pendaftar baru (isi berbeda!)
[ ] 6. Check database (semua jawaban berbeda?)
[ ] 7. Check logs (ada "SIMPAN SURVEY"?)
[ ] 8. Kirim hasil ke saya!
```

---

**Tolong lakukan step 1-8 dan kirim hasil:**
1. ✅ Confirm file uploaded
2. ✅ Database query result
3. ✅ Log output (jika ada)

Dengan info ini, saya bisa identify masalah sebenarnya! 🔍
