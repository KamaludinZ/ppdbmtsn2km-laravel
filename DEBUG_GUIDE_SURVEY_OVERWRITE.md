# DEBUG GUIDE - Survey Overwrite Bug Investigation

**Tanggal**: 6 Februari 2026
**Status**: 🔍 **INVESTIGATING**

---

## 🚨 SITUATION

Bug overwrite masih terjadi meskipun sudah upload fix:

**BEFORE SUBMIT** (Correct):
```
Q1  → 133 ✓
Q2  → 129 ✓
Q3  → 7 ✓
...
Q28 → 121 ✓
```

**AFTER SUBMIT** (Corrupted):
```
Q1  → 121 ❌ (overwritten!)
Q2  → 126 ❌
Q3  → 126 ❌
...
Q32 → 126 ❌
```

---

## 🔍 DEBUG APPROACH

Saya sudah tambahkan **EXTENSIVE LOGGING** ke `TesPsikologi.php` untuk capture:

1. **Data di memory** (`$this->surveyAnswers`) sebelum save
2. **Processing setiap question**: tipe, value yang akan disimpan
3. **Database operation**: apa yang sebenarnya di-save

---

## 📋 TESTING STEPS

### **STEP 1: Upload Debug Version**

```bash
# Jalankan batch file
upload_debug_version.bat

# Atau manual
scp app/Livewire/Pendaftar/TesPsikologi.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Pendaftar/
```

---

### **STEP 2: Clear Cache**

```bash
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

php artisan view:clear
php artisan optimize:clear
```

---

### **STEP 3: Test Survey Submission**

**Option A: Test dengan pendaftar existing (JPNA-26020002)**
1. Login sebagai JPNA-26020002
2. Reset survey answers (jika ada tombol Reset)
3. Isi survey dengan jawaban berbeda untuk setiap pertanyaan
4. Klik "Selesai & Simpan"

**Option B: Test dengan pendaftar baru**
1. Daftar akun baru
2. Isi survey
3. Submit

**PENTING**: Catat jawaban yang dipilih untuk 5-10 pertanyaan pertama agar bisa compare dengan hasil di database!

---

### **STEP 4: Collect Logs**

Segera setelah submit, ambil log:

```bash
# SSH ke production
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

# Lihat log terbaru
tail -200 storage/logs/laravel.log | grep -A 50 "SIMPAN SURVEY"
```

**Copy semua output** dan kirim ke saya!

---

### **STEP 5: Check Database**

```bash
# Login PostgreSQL
psql -U mtsnkot4 -d mtsnkot4_ppdb

# Check jawaban untuk pendaftar yang baru submit
SELECT survey_question_id, jawaban
FROM survey_answers
WHERE pendaftar_id = (
    SELECT id FROM pendaftars
    WHERE nomor_pendaftaran = 'JPNA-26020002'  -- atau nomor pendaftar lain
)
ORDER BY survey_question_id;

# Exit
\q
```

**Copy hasil query** dan kirim ke saya!

---

## 🎯 WHAT THE LOGS WILL SHOW

### **Expected Output** (jika fix bekerja):

```
[2026-02-06 17:00:00] local.INFO: === SIMPAN SURVEY START ===
[2026-02-06 17:00:00] local.INFO: Pendaftar ID: 132
[2026-02-06 17:00:00] local.INFO: Total survey answers in memory: 32
[2026-02-06 17:00:00] local.INFO: Survey answers structure: {"answers":{"1":133,"2":129,"3":7,...}}

[2026-02-06 17:00:00] local.INFO: Processing Q1: 133
[2026-02-06 17:00:00] local.INFO: Q1: Type = radio
[2026-02-06 17:00:00] local.INFO: Q1: Single value = 133
[2026-02-06 17:00:00] local.INFO: Q1: Saving to database...
[2026-02-06 17:00:00] local.INFO: Q1: ✓ Saved

[2026-02-06 17:00:00] local.INFO: Processing Q2: 129
[2026-02-06 17:00:00] local.INFO: Q2: Type = radio
[2026-02-06 17:00:00] local.INFO: Q2: Single value = 129
[2026-02-06 17:00:00] local.INFO: Q2: Saving to database...
[2026-02-06 17:00:00] local.INFO: Q2: ✓ Saved

... (untuk semua 32 pertanyaan)

[2026-02-06 17:00:01] local.INFO: === SIMPAN SURVEY END ===
```

---

### **Possible Issues We're Looking For**:

#### **Issue A: Data di memory sudah corrupt**
```
[2026-02-06 17:00:00] local.INFO: Survey answers structure:
  {"answers":{"1":126,"2":126,"3":126,...}}  ❌ SEMUA SAMA!
```
→ Artinya bug terjadi SEBELUM `simpanSurvey()` dipanggil (di Livewire binding)

---

#### **Issue B: Processing logic salah**
```
[2026-02-06 17:00:00] local.INFO: Processing Q1: 133
[2026-02-06 17:00:00] local.INFO: Q1: Single value = 126  ❌ BERUBAH!
```
→ Artinya ada logic error di dalam foreach loop

---

#### **Issue C: Database update issue**
```
[2026-02-06 17:00:00] local.INFO: Q1: Saving to database...
[2026-02-06 17:00:00] local.INFO: Q1: ✓ Saved
# Tapi di database jadi 126 bukan 133
```
→ Artinya ada issue di `updateOrCreate()` atau model accessor

---

## 📊 WHAT TO SEND ME

Kirim 3 hal ini:

1. **Log Output**:
   ```
   tail -200 storage/logs/laravel.log | grep -A 50 "SIMPAN SURVEY"
   ```

2. **Database Result**:
   ```sql
   SELECT survey_question_id, jawaban
   FROM survey_answers
   WHERE pendaftar_id = X
   ORDER BY survey_question_id;
   ```

3. **What you selected** (jawaban yang dipilih):
   ```
   Q1: Saya pilih "Ya" (should be ID 133)
   Q2: Saya pilih "Guru" (should be ID 129)
   Q3: Saya pilih "Menanyakan langsung" (should be ID 7)
   ...
   ```

---

## 🔧 ALTERNATIVE: Check Without Re-Submitting

Jika tidak ingin test dengan pendaftar baru, bisa check log existing:

```bash
# Lihat log untuk submission terakhir
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

# Check log hari ini
tail -500 storage/logs/laravel.log | grep "SIMPAN SURVEY" -A 100
```

Jika tidak ada log (karena belum upload versi debug), maka **HARUS test dengan versi debug baru**.

---

## 🎯 NEXT ACTIONS BASED ON FINDINGS

### **If Issue A** (data di memory corrupt):
→ Bug ada di Livewire wire:model binding
→ Perlu fix di Blade template atau Livewire property

### **If Issue B** (processing logic salah):
→ Bug ada di foreach loop `simpanSurvey()`
→ Perlu review logic `$answerValue` assignment

### **If Issue C** (database update issue):
→ Bug ada di Model atau database layer
→ Perlu check SurveyAnswer model, casts, accessors

---

## ⚡ QUICK COMMANDS REFERENCE

```bash
# Upload debug version
upload_debug_version.bat

# SSH dan clear cache
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
php artisan view:clear && php artisan optimize:clear

# View logs
tail -200 storage/logs/laravel.log | grep "SIMPAN SURVEY" -A 100

# Check database
psql -U mtsnkot4 -d mtsnkot4_ppdb -c "SELECT survey_question_id, jawaban FROM survey_answers WHERE pendaftar_id = 132 ORDER BY survey_question_id;"
```

---

**Dibuat**: 6 Februari 2026, 17:30 WIB
**Priority**: 🔥 CRITICAL - Need log output ASAP
**Estimasi**: 15-30 menit untuk test & collect logs
