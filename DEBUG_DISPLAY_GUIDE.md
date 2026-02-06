# DEBUG DISPLAY - VERIFIKASI DATA SURVEY 🔍

## Tanggal: 4 Februari 2026

---

## 📋 APA YANG DITAMBAHKAN?

Saya sudah menambahkan **DEBUG DISPLAY** untuk membantu Anda melihat data actual di database dan memverifikasi apakah sistem Option ID sudah bekerja dengan benar.

---

## 🎯 LOKASI DEBUG DISPLAY

### 1. Tab Survey (`/admin/tes-psikologi?mainTab=survey`)

**Yang Ditampilkan:**
- **Question ID** (badge kuning): `Q5`, `Q6`, dll
- **Option ID** (badge ungu): `ID:10`, `ID:47`, `ID:120`, dll
- **Option Value**: (value: 1), (value: 2), dll
- **Option Label**: Ya, Tidak, Setuju, dll

**Contoh Display:**
```
┌─────────────────────────────────────────────────────────────┐
│ Aktif | Radio | Wajib | Urutan: 5 | Q5 ← Question ID      │
│                                                               │
│ Saya sering menyampaikan masukan...                          │
│                                                               │
│ Opsi Jawaban:                                                │
│ ┌────────────────────────────────────────────────┐          │
│ │ ID:120  Sangat Tidak Setuju  (value: 1)       │          │
│ │ ID:121  Tidak Setuju         (value: 2)       │          │
│ │ ID:122  Setuju               (value: 3)       │          │
│ │ ID:123  Sangat Setuju        (value: 4)       │          │
│ └────────────────────────────────────────────────┘          │
└─────────────────────────────────────────────────────────────┘
```

**Interpretasi:**
- `Q5` = Question ID: 5
- `ID:120` = Option ID: 120 (untuk "Sangat Tidak Setuju")
- `ID:121` = Option ID: 121 (untuk "Tidak Setuju")
- `ID:122` = Option ID: 122 (untuk "Setuju")
- `ID:123` = Option ID: 123 (untuk "Sangat Setuju")
- `(value: 1)` = VALUE yang tersimpan di options (old system)

**Ini akan membantu Anda melihat:**
✅ Setiap pertanyaan punya Question ID unik
✅ Setiap option punya Option ID unik
✅ VALUE bisa sama antar pertanyaan (misal: semua pakai 1, 2, 3, 4)

---

### 2. Tab Jawaban Survey (`/admin/tes-psikologi?mainTab=jawaban-survey`)

**Yang Ditampilkan:**
- **Question ID** di header column: `Q5`, `Q6`, dll
- **RAW DATA** dari database: Data actual yang tersimpan
- **Indicator**: ✓ ID (hijau) atau ⚠ VALUE/LABEL (orange)

**Contoh Display:**
```
┌──────────────────────────────────────────────────────────────────┐
│ Pendaftar     │ Q5: Saya sering...     │ Q6: Saya beberapa...  │
├───────────────┼────────────────────────┼───────────────────────┤
│ Ahmad         │ RAW: 122 ✓ ID          │ RAW: 130 ✓ ID         │
│ (20260001)    │ Setuju                 │ Sangat Setuju         │
├───────────────┼────────────────────────┼───────────────────────┤
│ Siti          │ RAW: Sangat Tidak      │ RAW: Tidak Setuju     │
│ (20260002)    │      Setuju            │      ⚠ VALUE/LABEL    │
│               │ ⚠ VALUE/LABEL          │ Tidak Setuju          │
│               │ Sangat Tidak Setuju    │                       │
└───────────────┴────────────────────────┴───────────────────────┘
```

**Interpretasi:**

#### ✅ DATA BARU (Sistem Option ID):
```
RAW: 122 ✓ ID
Setuju
```
- Database menyimpan: `122` (numeric - Option ID)
- Indicator: `✓ ID` (hijau) → **CORRECT!**
- Display: "Setuju" (hasil lookup by ID 122)

#### ⚠ DATA LAMA (Sistem VALUE/LABEL):
```
RAW: Sangat Tidak Setuju ⚠ VALUE/LABEL
Sangat Tidak Setuju
```
- Database menyimpan: `"Sangat Tidak Setuju"` (string - LABEL)
- Indicator: `⚠ VALUE/LABEL` (orange) → **OLD SYSTEM**
- Display: "Sangat Tidak Setuju" (dari Method 4 - label matching)

---

## 🔍 CARA MENGGUNAKAN DEBUG DISPLAY

### Step 1: Cek Tab Survey

1. **Login sebagai Admin**
2. **Buka**: `/admin/tes-psikologi?mainTab=survey`
3. **Perhatikan**:
   - Apakah setiap pertanyaan punya **Question ID** unik? (Q1, Q2, Q5, Q6, dll)
   - Apakah setiap option punya **Option ID** unik? (ID:10, ID:47, ID:120, dll)
   - Apakah ada VALUE yang sama antar pertanyaan? (misal: semua pakai value: 1, 2, 3, 4)

4. **Catat Option ID** dari pertanyaan tertentu, misal:
   ```
   Q5: Saya sering menyampaikan masukan...
     - ID:120 = "Sangat Tidak Setuju" (value: 1)
     - ID:121 = "Tidak Setuju" (value: 2)
     - ID:122 = "Setuju" (value: 3)
     - ID:123 = "Sangat Setuju" (value: 4)
   ```

---

### Step 2: Cek Tab Jawaban Survey

1. **Buka**: `/admin/tes-psikologi?mainTab=jawaban-survey`
2. **Hard Refresh**: `Ctrl+F5`
3. **Perhatikan setiap baris pendaftar**

#### A. CEK DATA BARU (Setelah Fix Option ID)

**Cari pendaftar yang baru submit survey** (hari ini setelah upload fix)

**Yang Diharapkan:**
```
RAW: 122 ✓ ID
Setuju
```

**Artinya:**
- ✅ Data tersimpan sebagai **Option ID 122** (numeric)
- ✅ Indicator hijau `✓ ID` → Sistem baru bekerja!
- ✅ Display "Setuju" → Lookup by ID berhasil!

**Cross-check dengan Tab Survey:**
- ID:122 di Q5 = "Setuju" ✅ **MATCH!**

---

#### B. CEK DATA LAMA (Sebelum Fix)

**Cari pendaftar yang submit survey kemarin/sebelumnya**

**Yang Mungkin Muncul:**
```
RAW: Sangat Tidak Setuju ⚠ VALUE/LABEL
Sangat Tidak Setuju
```
atau
```
RAW: 1 ⚠ VALUE/LABEL
[VALUE NOT FOUND: 1]
```

**Artinya:**
- ⚠ Data tersimpan sebagai **VALUE/LABEL** (string)
- ⚠ Indicator orange → Sistem lama (backward compatibility)
- ⚠ Jika tampil `[VALUE NOT FOUND: xxx]` → Method 1-5 gagal match

---

### Step 3: Diagnose Masalah

#### Scenario 1: Data Baru Masih VALUE/LABEL (⚠ orange)

**Contoh:**
```
RAW: Sangat Tidak Setuju ⚠ VALUE/LABEL
```

**Kemungkinan Penyebab:**
1. **Form belum terupload** dengan fix Option ID
2. **Cache belum di-clear**
3. **Browser cache** pendaftar yang masih pakai form lama

**Solusi:**
- Verifikasi file form terupload (cek `value="{{ $option->id }}"`)
- Clear cache: `php artisan optimize:clear && php artisan view:clear`
- Pendaftar harus hard refresh form (`Ctrl+F5`)
- Test dengan **submit survey baru**

---

#### Scenario 2: Data Baru Adalah ID tapi Display Salah

**Contoh:**
```
RAW: 120 ✓ ID
[VALUE NOT FOUND: 120]
```

**Artinya:**
- ✅ Data tersimpan sebagai Option ID (correct!)
- ❌ Tapi display logic gagal lookup

**Kemungkinan Penyebab:**
1. **Display logic belum terupload** dengan fix lookup by ID
2. **Option ID 120 tidak ada** dalam collection (data integrity issue)

**Solusi:**
- Verifikasi file display logic terupload
  - `app/Livewire/Admin/TesPsikologi.php` (line ~1025)
  - Cari: `$option = $questionOptions->firstWhere('id', (int)$normalizedValue);`
- Clear cache
- Cek log error untuk detail

---

#### Scenario 3: Data Lama Masih `[VALUE NOT FOUND: xxx]`

**Contoh:**
```
RAW: Sangat Tidak Setuju ⚠ VALUE/LABEL
[VALUE NOT FOUND: Sangat Tidak Setuju]
```

**Artinya:**
- Data lama menggunakan LABEL
- Method 4 & 5 (label matching) gagal

**Kemungkinan Penyebab:**
1. **Options VALUE berbeda** dengan jawaban
2. **Typo atau spacing** di data/options
3. **Case sensitivity issue**

**Solusi:**
- Cek options di tab Survey:
  - Apakah ada option dengan label "Sangat Tidak Setuju"?
  - Apakah ada typo (misal: "Sangat  Tidak Setuju" dengan 2 spasi)?
- Update options VALUE = LABEL jika perlu:
  ```sql
  UPDATE survey_question_options
  SET value = label
  WHERE survey_question_id = 5;
  ```

---

## 📊 EXPECTED RESULT SETELAH FIX

### Tab Survey
```
Q5: Saya sering menyampaikan masukan...
  ID:120  Sangat Tidak Setuju  (value: 1)
  ID:121  Tidak Setuju         (value: 2)
  ID:122  Setuju               (value: 3)
  ID:123  Sangat Setuju        (value: 4)

Q6: Saya beberapa kali menyampaikan pendapat...
  ID:130  Sangat Tidak Setuju  (value: 1)
  ID:131  Tidak Setuju         (value: 2)
  ID:132  Setuju               (value: 3)
  ID:133  Sangat Setuju        (value: 4)
```

**Perhatikan:**
- Question ID berbeda: Q5 ≠ Q6
- Option ID berbeda: ID:120-123 vs ID:130-133
- VALUE **SAMA**: semua pakai (1, 2, 3, 4) → **INI YANG BIKIN MASALAH DI SISTEM LAMA!**

---

### Tab Jawaban Survey (Data Baru)
```
| Pendaftar | Q5: Saya sering...         | Q6: Saya beberapa...       |
|-----------|----------------------------|----------------------------|
| Ahmad     | RAW: 122 ✓ ID              | RAW: 133 ✓ ID              |
|           | Setuju                     | Sangat Setuju              |
|-----------|----------------------------|----------------------------|
| Siti      | RAW: 120 ✓ ID              | RAW: 131 ✓ ID              |
|           | Sangat Tidak Setuju        | Tidak Setuju               |
```

**✅ CORRECT!**
- Semua data tersimpan sebagai **Option ID** (numeric)
- Indicator hijau `✓ ID`
- Display **sesuai** dengan label option yang benar
- Q5 dan Q6 tampil **BERBEDA** (bukan semua sama)

---

## 🚀 UPLOAD FILE DENGAN DEBUG DISPLAY

### File yang Perlu Diupload (6 files)

#### 1-4. Architectural Fix (Option ID System)
```
1. resources/views/tab_templates/tab-survey.blade.php
2. resources/views/livewire/pendaftar/tes-psikologi.blade.php
3. app/Livewire/Admin/TesPsikologi.php
4. app/Livewire/Panitia/TesPsikologi.php
```

#### 5-6. Debug Display
```
5. resources/views/livewire/admin/partials/tes-psikologi-survey.blade.php
6. resources/views/livewire/admin/partials/tes-psikologi-jawaban-survey.blade.php
```

---

### Upload via cPanel

1. **Login cPanel** → **File Manager**
2. **Upload 6 files** ke folder tujuan (overwrite existing)
3. **Clear cache** via Terminal:
   ```bash
   cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
   php artisan optimize:clear
   php artisan view:clear
   php artisan config:clear
   ```
4. **Hard Refresh browser**: `Ctrl+F5`

---

## 🔧 CHECKLIST VERIFIKASI

### ✅ Step 1: Tab Survey
- [ ] Setiap pertanyaan tampil dengan **Question ID** (Q1, Q2, Q5, Q6)
- [ ] Setiap option tampil dengan **Option ID** (ID:10, ID:47, ID:120)
- [ ] VALUE bisa sama antar pertanyaan (value: 1, 2, 3, 4)
- [ ] Option ID **UNIK** (tidak ada duplikat)

### ✅ Step 2: Submit Survey Baru
- [ ] Logout dari Admin
- [ ] Login sebagai **Pendaftar**
- [ ] Submit **survey baru** (test account)
- [ ] **Hard refresh** form sebelum submit: `Ctrl+F5`

### ✅ Step 3: Tab Jawaban Survey
- [ ] Login sebagai **Admin**
- [ ] Buka **Tab Jawaban Survey**
- [ ] **Hard Refresh**: `Ctrl+F5`
- [ ] Cari pendaftar yang baru submit (test account)

**Expected:**
```
RAW: 122 ✓ ID
Setuju
```

**NOT Expected:**
```
RAW: Sangat Tidak Setuju ⚠ VALUE/LABEL
atau
RAW: 1 ⚠ VALUE/LABEL
```

### ✅ Step 4: Verifikasi Semua Kolom
- [ ] Setiap kolom pertanyaan tampil **LABEL yang BERBEDA**
- [ ] Tidak ada `[VALUE NOT FOUND: xxx]`
- [ ] RAW data menunjukkan **Option ID** (numeric dengan ✓ ID hijau)

---

## 📞 JIKA MASIH ADA MASALAH

Laporkan dengan screenshot dan info:

1. **Screenshot Tab Survey**
   - Show Question ID dan Option ID
   - Catat: Pertanyaan ID berapa? Option ID berapa?

2. **Screenshot Tab Jawaban Survey**
   - Show RAW data (dengan indicator ✓ ID atau ⚠ VALUE/LABEL)
   - Untuk pendaftar yang baru submit hari ini

3. **Info Tambahan:**
   - Kapan pendaftar submit survey? (sebelum atau sesudah upload fix?)
   - Apakah pendaftar hard refresh form sebelum submit?
   - Apakah cache production sudah di-clear?

---

**Good luck with debugging! Dengan display ini, Anda bisa melihat EXACTLY apa yang tersimpan di database! 🔍🎯**
