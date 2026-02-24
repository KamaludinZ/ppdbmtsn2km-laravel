# FIX: Tab Seragam dan Tab Ukuran Terpisah (21 Feb 2026)

## 🐛 Problem

Saat user mengisi **Tab Ukuran** atau **Tab Seragam**, status keduanya langsung menjadi "menunggu" padahal baru 1 tab yang diisi.

**Contoh Issue**:
- User submit Tab Ukuran → status_ukuran="menunggu" ✅ DAN status_seragam="menunggu" ❌
- User submit Tab Seragam → status_seragam="menunggu" ✅ DAN status_ukuran="menunggu" ❌

**Root Cause**:
Kedua status ada di tabel yang sama (`seragam`) dan keduanya NOT NULL tanpa enum value untuk "belum diisi".

---

## ✅ Solution

### 1. **Add Migration** - Tambah Enum Value `'belum_diisi'`

**File**: `database/migrations/2026_02_21_210000_add_belum_diisi_to_seragam_status_enum.php`

```php
public function up(): void
{
    // Add 'belum_diisi' to status_ukuran enum
    DB::statement("
        ALTER TABLE seragam
        DROP CONSTRAINT IF EXISTS seragam_status_ukuran_check
    ");

    DB::statement("
        ALTER TABLE seragam
        ADD CONSTRAINT seragam_status_ukuran_check
        CHECK (status_ukuran::text = ANY (ARRAY[
            'belum_diisi'::character varying,
            'menunggu'::character varying,
            'disetujui'::character varying,
            'ditolak'::character varying,
            'perbaiki'::character varying
        ]::text[]))
    ");

    // Add 'belum_diisi' to status_seragam enum
    DB::statement("
        ALTER TABLE seragam
        DROP CONSTRAINT IF EXISTS seragam_status_seragam_check
    ");

    DB::statement("
        ALTER TABLE seragam
        ADD CONSTRAINT seragam_status_seragam_check
        CHECK (status_seragam::text = ANY (ARRAY[
            'belum_diisi'::character varying,
            'menunggu'::character varying,
            'disetujui'::character varying,
            'ditolak'::character varying,
            'perbaiki'::character varying
        ]::text[]))
    ");
}
```

**Run Migration**:
```bash
php artisan migrate --path=database/migrations/2026_02_21_210000_add_belum_diisi_to_seragam_status_enum.php
```

---

### 2. **Update Code** - Set Status yang Tepat

**File**: `app/Livewire/Pendaftar/DaftarUlang.php`

#### A. Function `saveUkuran()` (Line 694-701)

**Sebelum**:
```php
if (!$this->seragam) {
    $this->seragam = \App\Models\Seragam::create([
        'pendaftar_id' => $this->pendaftar->id,
        'status_ukuran' => 'menunggu',
        'status_seragam' => 'menunggu',  // ❌ WRONG!
        'paket_seragam' => '',
    ]);
}
```

**Sesudah**:
```php
if (!$this->seragam) {
    $this->seragam = \App\Models\Seragam::create([
        'pendaftar_id' => $this->pendaftar->id,
        'status_ukuran' => 'menunggu',  // ✅ Tab Ukuran submitted
        'status_seragam' => 'belum_diisi',  // ✅ Tab Seragam not submitted yet
        'paket_seragam' => '',
    ]);
}
```

---

#### B. Function `saveSeragam()` (Line 603-610)

**Sebelum**:
```php
if (!$this->seragam) {
    $this->seragam = \App\Models\Seragam::create([
        'pendaftar_id' => $this->pendaftar->id,
        'status_seragam' => 'menunggu',
        'status_ukuran' => null,  // ❌ WRONG! Not allowed
    ]);
}
```

**Sesudah**:
```php
if (!$this->seragam) {
    $this->seragam = \App\Models\Seragam::create([
        'pendaftar_id' => $this->pendaftar->id,
        'status_seragam' => 'menunggu',  // ✅ Tab Seragam submitted
        'status_ukuran' => 'belum_diisi',  // ✅ Tab Ukuran not submitted yet
        'paket_seragam' => '',
    ]);
}
```

---

#### C. Function `uploadBuktiSeragam()` (Line 1090-1097)

**Sebelum**:
```php
if (!$this->seragam) {
    $this->seragam = \App\Models\Seragam::create([
        'pendaftar_id' => $this->pendaftar->id,
        'status_seragam' => 'belum',  // ❌ WRONG! Invalid enum value
    ]);
}
```

**Sesudah**:
```php
if (!$this->seragam) {
    $this->seragam = \App\Models\Seragam::create([
        'pendaftar_id' => $this->pendaftar->id,
        'status_seragam' => 'menunggu',  // ✅ Bukti uploaded, waiting for verification
        'status_ukuran' => 'belum_diisi',  // ✅ Tab Ukuran not submitted yet
        'paket_seragam' => '',
    ]);
}
```

---

### 3. **Cleanup Test Data**

**File**: `cleanup_test_seragam.php`

```php
<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

// Delete seragam records for test pendaftar
$deleted = DB::table('seragam')
    ->where('pendaftar_id', 6)  // Test pendaftar ID
    ->delete();

echo "✅ Deleted {$deleted} seragam record(s)\n";
```

**Run**:
```bash
php cleanup_test_seragam.php
```

---

## 📊 Valid Enum Values

### `status_ukuran`
- `belum_diisi` ⬅️ NEW!
- `menunggu`
- `disetujui`
- `ditolak`
- `perbaiki`

### `status_seragam`
- `belum_diisi` ⬅️ NEW!
- `menunggu`
- `disetujui`
- `ditolak`
- `perbaiki`

---

## 🧪 Testing Scenarios

### Scenario 1: User Submit Tab Ukuran Only

**Action**: User isi dan submit Tab Ukuran

**Expected Result**:
- ✅ status_ukuran = 'menunggu'
- ✅ status_seragam = 'belum_diisi'
- ✅ Tab Seragam masih kosong (tidak ada status "menunggu")

---

### Scenario 2: User Submit Tab Seragam Only

**Action**: User isi dan submit Tab Seragam

**Expected Result**:
- ✅ status_seragam = 'menunggu'
- ✅ status_ukuran = 'belum_diisi'
- ✅ Tab Ukuran masih kosong (tidak ada status "menunggu")

---

### Scenario 3: User Submit Both Tabs

**Action**: User isi dan submit Tab Ukuran, lalu Tab Seragam

**Expected Result**:
- ✅ status_ukuran = 'menunggu'
- ✅ status_seragam = 'menunggu'
- ✅ Kedua tab menunjukkan status "menunggu"

---

## 📁 Files Changed

1. **database/migrations/2026_02_21_210000_add_belum_diisi_to_seragam_status_enum.php** (NEW)
   - Add enum value 'belum_diisi' to both status columns

2. **app/Livewire/Pendaftar/DaftarUlang.php** (MODIFIED)
   - Line 697-698: saveUkuran() - set correct statuses
   - Line 606-607: saveSeragam() - set correct statuses
   - Line 1093-1094: uploadBuktiSeragam() - set correct statuses

3. **cleanup_test_seragam.php** (NEW)
   - Script to cleanup test data for fresh testing

---

## 🚀 Deployment

### Local Testing
```bash
# 1. Run migration
php artisan migrate --path=database/migrations/2026_02_21_210000_add_belum_diisi_to_seragam_status_enum.php

# 2. Cleanup test data
php cleanup_test_seragam.php

# 3. Test di browser
# - Login sebagai pendaftar@test.com
# - Isi Tab Ukuran → Submit
# - Verifikasi status_seragam masih "belum_diisi"
```

### Production Upload
```bash
# Upload files:
scp database/migrations/2026_02_21_210000_add_belum_diisi_to_seragam_status_enum.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/database/migrations/
scp app/Livewire/Pendaftar/DaftarUlang.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Pendaftar/

# Run migration on production
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan migrate --force"
```

---

## ✅ Success Criteria

- [x] Migration berhasil dijalankan tanpa error
- [x] Enum value 'belum_diisi' tersedia di kedua kolom
- [x] Submit Tab Ukuran HANYA mengubah status_ukuran
- [x] Submit Tab Seragam HANYA mengubah status_seragam
- [x] Tidak ada error database NOT NULL violation
- [x] Tidak ada error invalid enum value

---

## 📝 Notes

- Enum value 'belum_diisi' digunakan sebagai default untuk tab yang **belum diisi**
- Status 'menunggu' hanya muncul setelah user **submit form** di tab tersebut
- Kedua tab sekarang **benar-benar terpisah** dalam tracking statusnya
- Fix ini **kompatibel** dengan fitur mode checkbox/radio yang sudah ada

---

**Author**: Claude Code
**Date**: 21 Februari 2026
**Related Issues**: Tab separation, Status tracking, Database constraints
