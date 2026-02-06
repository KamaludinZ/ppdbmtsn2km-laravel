# MANUAL DEBUG STEPS - Survey Masih Overwrite

**Status**: Bug MASIH TERJADI setelah upload fix
**Urgent**: Perlu verify apa yang salah

---

## STEP 1: VERIFY FILE UPLOADED CORRECTLY ⚠️

### Via cPanel File Manager atau FileZilla:

**File 1: app/Models/SurveyAnswer.php**
- Navigate ke: `/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Models/SurveyAnswer.php`
- **BUKA FILE** dan cari line 18-25
- **HARUS TERLIHAT INI:**

```php
protected $casts = [
    // 'jawaban' => 'array', // ❌ REMOVED! This was causing the overwrite bug!
];
```

**KALAU MASIH SEPERTI INI, FILE BELUM TER-UPLOAD:**
```php
protected $casts = [
    'jawaban' => 'array',
];
```

---

**File 2: app/Livewire/Pendaftar/TesPsikologi.php**
- Navigate ke: `/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Pendaftar/TesPsikologi.php`
- **BUKA FILE** dan cari sekitar line 310-315
- **HARUS ADA LOG STATEMENT:**

```php
\Log::info('=== SIMPAN SURVEY START ===');
\Log::info('Pendaftar ID: ' . $this->pendaftar->id);
\Log::info('Total survey answers in memory: ' . count($this->surveyAnswers));
```

**KALAU TIDAK ADA LOG INI, FILE BELUM TER-UPLOAD!**

---

## STEP 2: VERIFY CACHE CLEARED ⚠️

### Via cPanel Terminal atau SSH (jika sudah bisa):

```bash
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

# Clear SEMUA cache
php artisan view:clear
php artisan optimize:clear
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Verify cache cleared
ls -la bootstrap/cache/
# Seharusnya file config.php dan routes-v7.php TIDAK ADA atau ter-update
```

### Via cPanel File Manager:

Navigate ke: `/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/bootstrap/cache/`

**DELETE FILES INI** (jika ada):
- `config.php`
- `routes-v7.php`
- Semua file `.php` di folder `bootstrap/cache/`

**JANGAN DELETE** folder `storage/framework/views/` !

---

## STEP 3: CHECK DATABASE RESULT ⚠️

### Via phpPgAdmin atau Terminal:

```sql
-- Get pendaftar terakhir yang submit survey
SELECT id, nomor_pendaftaran, created_at
FROM pendaftar
ORDER BY created_at DESC
LIMIT 5;

-- Check jawaban untuk pendaftar terakhir (ganti ID sesuai hasil query di atas)
SELECT survey_question_id, jawaban, created_at, updated_at
FROM survey_answers
WHERE pendaftar_id = [GANTI_DENGAN_ID]
ORDER BY survey_question_id;
```

**TOLONG COPY HASIL QUERY INI DAN KIRIM KE SAYA!**

Expected result (CORRECT):
```
 survey_question_id | jawaban
--------------------+---------
                  1 | 133
                  2 | 129
                  3 | 7
                  4 | 29
                  ...
```

Actual result (BUG):
```
 survey_question_id | jawaban
--------------------+---------
                  1 | "126"
                  2 | "126"
                  3 | "126"
                  ...
```

---

## STEP 4: CHECK PRODUCTION LOGS ⚠️

### Via cPanel File Manager atau Terminal:

Navigate ke: `/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/storage/logs/laravel.log`

**Cari line yang mengandung** (gunakan search/Ctrl+F):
- `SIMPAN SURVEY START`
- `SIMPAN SURVEY END`

**KALAU TIDAK ADA LOG INI:**
→ Berarti file TesPsikologi.php BELUM TER-UPLOAD atau cache belum clear!

**KALAU ADA LOG INI:**
→ TOLONG COPY semua log dari "SIMPAN SURVEY START" sampai "SIMPAN SURVEY END" dan kirim ke saya!

Format log seharusnya seperti ini:
```
[2026-02-06 19:00:00] production.INFO: === SIMPAN SURVEY START ===
[2026-02-06 19:00:00] production.INFO: Pendaftar ID: 132
[2026-02-06 19:00:00] production.INFO: Total survey answers in memory: 32
[2026-02-06 19:00:00] production.INFO: Survey answers structure: {"answers":{"1":133,"2":129,...}}
[2026-02-06 19:00:00] production.INFO: Processing Q1: 133
...
```

---

## STEP 5: ALTERNATIVE - RE-UPLOAD FILES ⚠️

Jika Anda TIDAK YAKIN file ter-upload dengan benar, **UPLOAD ULANG** dengan cara ini:

### Via FileZilla/WinSCP:

1. **Download file dari local PC:**
   - `C:\ppdbmtsn2km-laravel\app\Models\SurveyAnswer.php`
   - `C:\ppdbmtsn2km-laravel\app\Livewire\Pendaftar\TesPsikologi.php`

2. **Connect ke server** (103.30.244.77, port 22, user mtsnkot4)

3. **Upload dan OVERWRITE:**
   - Upload `SurveyAnswer.php` ke `/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Models/`
   - Upload `TesPsikologi.php` ke `/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Pendaftar/`

4. **Verify upload success** - buka file di server dan check content-nya

---

### Via cPanel File Manager:

1. Login cPanel
2. File Manager → navigate ke folder
3. **BACKUP existing files first** (download ke local)
4. **DELETE existing files** dari server
5. **UPLOAD NEW files** dari local: `C:\ppdbmtsn2km-laravel\app\Models\SurveyAnswer.php` dan `TesPsikologi.php`
6. **Verify** file size dan timestamp updated

---

## STEP 6: AFTER RE-UPLOAD - CLEAR CACHE AGAIN ⚠️

```bash
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

php artisan view:clear
php artisan optimize:clear
php artisan cache:clear
php artisan config:clear

# Delete corrupt data LAGI
psql -U mtsnkot4_uspmbm -d mtsnkot4_dbpmbm -c "DELETE FROM survey_answers;"
```

---

## STEP 7: TEST AGAIN ⚠️

1. Login sebagai pendaftar
2. Isi survey dengan jawaban **SANGAT BERBEDA**:
   ```
   Q1: Pilih option pertama
   Q2: Pilih option kedua
   Q3: Pilih option ketiga
   Q4: Pilih option keempat
   ...
   JANGAN PILIH YANG SAMA!
   ```
3. Klik "Selesai & Simpan"
4. Check database (lihat STEP 3)
5. Check logs (lihat STEP 4)

---

## TOLONG KIRIM HASIL INI KE SAYA:

1. ✅ **Confirm File Upload**:
   - [ ] SurveyAnswer.php line 24: Ada comment "// REMOVED!"?
   - [ ] TesPsikologi.php line 312: Ada "\\Log::info('=== SIMPAN SURVEY START ===')"?

2. ✅ **Confirm Cache Clear**:
   - [ ] bootstrap/cache/config.php sudah di-delete?
   - [ ] Sudah run php artisan optimize:clear?

3. ✅ **Database Result**:
   ```sql
   -- Copy paste hasil query ini:
   SELECT survey_question_id, jawaban FROM survey_answers
   WHERE pendaftar_id = [ID] ORDER BY survey_question_id;
   ```

4. ✅ **Log Output**:
   ```
   -- Copy paste dari laravel.log (cari "SIMPAN SURVEY"):
   [2026-02-06 ...] production.INFO: === SIMPAN SURVEY START ===
   ...
   ```

---

## KEMUNGKINAN PENYEBAB BUG MASIH TERJADI:

### A. File Belum Ter-Upload dengan Benar
→ **Solution**: Re-upload file (STEP 5)

### B. Cache Belum Clear
→ **Solution**: Clear cache lagi (STEP 6)

### C. Test Menggunakan Data Lama
→ **Solution**: Delete survey_answers, test dengan data baru

### D. Ada Bug Lain yang Lebih Dalam
→ **Need**: Log output untuk analisa lebih lanjut

---

**URGENT**: Tolong lakukan STEP 1-4 dulu dan kirim hasilnya ke saya!

Saya perlu tahu:
1. Apakah file benar-benar ter-upload?
2. Apakah cache sudah clear?
3. Apa yang tersimpan di database?
4. Apa yang tertulis di logs?

Dengan informasi ini, saya bisa identify masalah sebenarnya!
