# Fix Summary: Step 2 - Rata-Rata Display & Button Logic

**Date:** 2026-01-19
**Status:** ✅ COMPLETED
**Issue:** Step 2 perlu tampilan rata-rata per mapel dan logic button yang tepat

---

## 🎯 Requirements

### 1. Kuota Jalur Terupdate Otomatis
- Ketika ada pendaftar baru yang berhasil register dengan jalur tertentu
- Badge kuota di Step 1 harus berkurang secara real-time
- Hanya untuk jalur dengan kuota terbatas (bukan unlimited)

### 2. Tampilan Rata-Rata di Step 2
- Setelah field input nilai, tampilkan:
  1. **Rata-rata per mapel** (Bahasa Indonesia, Matematika, IPA)
  2. **Rata-rata total** (dari semua nilai)
  3. **Status validasi** (memenuhi syarat atau belum)

### 3. Logic Tombol "Lanjutkan" di Step 2
Tombol aktif **HANYA JIKA:**
- ✅ Semua kolom nilai terisi (9 field)
- ✅ File rapor sudah diupload
- ✅ Nilai rata-rata memenuhi syarat minimum jalur

---

## ✅ Solutions Implemented

### Solution 1: Kuota Terupdate Otomatis

**Mechanism:**

1. **Computed Property (Always Fresh Data):**
   ```php
   // app/Livewire/Auth/RegisterFixed.php (lines 111-118)
   public function getJalurPendaftaranProperty()
   {
       // Load fresh data from database with counts
       return JalurPendaftaran::aktif()
           ->ordered()
           ->withCount('pendaftar')  // ← Menghitung ulang setiap query
           ->get();
   }
   ```

2. **Cache Clear After Registration:**
   ```php
   // app/Livewire/Auth/RegisterFixed.php (line 570)
   DB::commit();

   // Clear cache
   Cache::forget('jalur_pendaftaran_with_counts');
   ```

3. **Auto-Update Flow:**
   ```
   User A registers → Pendaftar record created
                   → DB commit
                   → Cache cleared

   User B visits /daftar → getJalurPendaftaranProperty() called
                         → Fresh query: withCount('pendaftar')
                         → Badge shows updated quota
   ```

**Result:**
- ✅ Kuota berkurang otomatis setelah ada pendaftar baru
- ✅ No manual refresh needed
- ✅ Real-time data from database

---

### Solution 2: Display Rata-Rata Per Mapel & Total

**File:** `resources/views/livewire/auth/register-fixed.blade.php` (lines 401-472)

#### A. Section Header
```blade
<div class="bg-gradient-to-br from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-xl p-6">
    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
        <svg class="w-6 h-6 text-blue-600 mr-2">...</svg>
        Perhitungan Nilai Rata-Rata
    </h3>
```

#### B. Rata-Rata Per Mapel (3 Cards)
```blade
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
    <!-- Bahasa Indonesia -->
    <div class="bg-white rounded-lg p-4 shadow-sm">
        <p class="text-xs text-gray-600 mb-1">Rata-rata Bahasa Indonesia</p>
        <p class="text-2xl font-bold text-blue-600">
            {{ number_format($this->getAverageForSubject('b_indo'), 2) }}
        </p>
        <p class="text-xs text-gray-500 mt-1">Dari 3 semester</p>
    </div>

    <!-- Matematika -->
    <div class="bg-white rounded-lg p-4 shadow-sm">
        <p class="text-xs text-gray-600 mb-1">Rata-rata Matematika</p>
        <p class="text-2xl font-bold text-blue-600">
            {{ number_format($this->getAverageForSubject('matematika'), 2) }}
        </p>
        <p class="text-xs text-gray-500 mt-1">Dari 3 semester</p>
    </div>

    <!-- IPA -->
    <div class="bg-white rounded-lg p-4 shadow-sm">
        <p class="text-xs text-gray-600 mb-1">Rata-rata IPA</p>
        <p class="text-2xl font-bold text-blue-600">
            {{ number_format($this->getAverageForSubject('ipa'), 2) }}
        </p>
        <p class="text-xs text-gray-500 mt-1">Dari 3 semester</p>
    </div>
</div>
```

**Calculation Logic (Backend):**
```php
// app/Livewire/Auth/RegisterFixed.php (lines 655-686)
public function getAverageForSubject($subject)
{
    $nilai = [];

    switch ($subject) {
        case 'b_indo':
            $nilai = [
                $this->nilai_b_indo_5_1,
                $this->nilai_b_indo_5_2,
                $this->nilai_b_indo_6_1,
            ];
            break;
        case 'matematika':
            $nilai = [
                $this->nilai_matematika_5_1,
                $this->nilai_matematika_5_2,
                $this->nilai_matematika_6_1,
            ];
            break;
        case 'ipa':
            $nilai = [
                $this->nilai_ipa_5_1,
                $this->nilai_ipa_5_2,
                $this->nilai_ipa_6_1,
            ];
            break;
    }

    $nilaiValid = array_filter($nilai, fn($n) => $n !== null && $n !== '');
    return empty($nilaiValid) ? 0 : round(array_sum($nilaiValid) / count($nilaiValid), 2);
}
```

#### C. Rata-Rata Total & Validation Status
```blade
<div class="bg-white rounded-lg p-5 shadow-md border-2 {{ $isValid ? 'border-green-400' : 'border-yellow-400' }}">
    <div class="flex items-center justify-between">
        <!-- Rata-Rata Total -->
        <div>
            <p class="text-sm text-gray-600 mb-1">Rata-Rata Total</p>
            <p class="text-3xl font-bold {{ $isValid ? 'text-green-600' : 'text-yellow-600' }}">
                {{ number_format($rata_rata_nilai, 2) }}
            </p>
        </div>

        <!-- Nilai Minimum Required -->
        <div class="text-right">
            <p class="text-sm text-gray-600 mb-1">Nilai Minimum</p>
            <p class="text-2xl font-bold text-gray-700">
                {{ $selectedJalur ? number_format($selectedJalur->nilai_minimum, 2) : '-' }}
            </p>
        </div>
    </div>

    <!-- Validation Message -->
    @if($validationMessage)
        <div class="mt-4 flex items-start p-3 rounded-lg {{ $isValid ? 'bg-green-50' : 'bg-yellow-50' }}">
            <svg class="w-5 h-5 {{ $isValid ? 'text-green-500' : 'text-yellow-500' }}">
                <!-- Icon checkmark or warning -->
            </svg>
            <p class="text-sm {{ $isValid ? 'text-green-700' : 'text-yellow-700' }}">
                {{ $validationMessage }}
            </p>
        </div>
    @endif
</div>
```

**Visual Design:**
- 🟦 Blue cards for per-subject averages
- 🟢 Green border/text if valid
- 🟡 Yellow border/text if not valid
- ✅ Icon checkmark if memenuhi
- ⚠️ Icon warning if belum memenuhi

---

### Solution 3: Button Logic - Computed Property

**File:** `app/Livewire/Auth/RegisterFixed.php` (lines 696-720)

```php
/**
 * Check if Step 2 is complete and valid
 * Computed property untuk button enable/disable di Step 2
 */
public function getIsStep2ValidProperty()
{
    // Check semua nilai terisi
    $allNilaiFilled = !empty($this->nilai_b_indo_5_1) &&
                     !empty($this->nilai_matematika_5_1) &&
                     !empty($this->nilai_ipa_5_1) &&
                     !empty($this->nilai_b_indo_5_2) &&
                     !empty($this->nilai_matematika_5_2) &&
                     !empty($this->nilai_ipa_5_2) &&
                     !empty($this->nilai_b_indo_6_1) &&
                     !empty($this->nilai_matematika_6_1) &&
                     !empty($this->nilai_ipa_6_1);

    // Check file rapor uploaded
    $fileRaporUploaded = $this->file_rapor !== null;

    // Check nilai memenuhi minimum requirement
    $nilaiMemenuhi = $this->isValid;

    return $allNilaiFilled && $fileRaporUploaded && $nilaiMemenuhi;
}
```

**Logic Breakdown:**
1. **All fields filled:** Check 9 nilai fields tidak empty
2. **File uploaded:** Check `$file_rapor` not null
3. **Valid average:** Check `$isValid` (nilai rata-rata >= minimum)
4. **Return:** TRUE only if ALL three conditions met

**Button Implementation:**
```blade
<!-- resources/views/livewire/auth/register-fixed.blade.php (lines 870-878) -->
<button type="button"
        wire:click="nextStep"
        wire:loading.attr="disabled"
        wire:target="nextStep"
        @if($currentStep == 2 && !$isStep2Valid) disabled @endif
        class="... {{ $isStep2Valid ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-gray-400' }}">
    Lanjutkan
</button>
```

**Button States:**

**ENABLED (Green):**
```
✓ All 9 nilai fields filled
✓ File rapor uploaded
✓ Rata-rata >= nilai minimum
→ Button: bg-emerald-600 (clickable)
```

**DISABLED (Gray):**
```
✗ Missing any nilai field
  OR
✗ File rapor not uploaded
  OR
✗ Rata-rata < nilai minimum
→ Button: bg-gray-400 (not clickable)
```

---

## 📊 Expected Behavior

### Scenario 1: Initial Load (Fields Empty)
```
Step 2 opened:
- Rata-rata per mapel: 0.00, 0.00, 0.00
- Rata-rata total: 0.00
- Validation message: (belum muncul)
- Button "Lanjutkan": DISABLED (gray)
```

### Scenario 2: Partial Input
```
User fills 5 of 9 fields:
- Rata-rata per mapel: (partially calculated)
- Button "Lanjutkan": DISABLED (not all filled)
```

### Scenario 3: All Fields Filled, No File
```
All 9 nilai fields filled:
- Rata-rata calculated
- Validation message shown
- File rapor: NOT uploaded
- Button "Lanjutkan": DISABLED (file missing)
```

### Scenario 4: All Fields + File, But Low Score
```
All 9 fields filled: ✓
File rapor uploaded: ✓
Rata-rata: 75 (minimum: 80)
- Validation: ⚠️ "Nilai rata-rata Anda (75) belum memenuhi syarat minimum (80)"
- Button "Lanjutkan": DISABLED (score too low)
```

### Scenario 5: All Valid ✅
```
All 9 fields filled: ✓
File rapor uploaded: ✓
Rata-rata: 85 (minimum: 80): ✓
- Validation: ✅ "Nilai rata-rata Anda (85) memenuhi syarat minimum (80)"
- Button "Lanjutkan": ENABLED (green, clickable)
```

---

## 🎨 Visual Example

### Display Layout:

```
┌─────────────────────────────────────────────────────┐
│  📚 Perhitungan Nilai Rata-Rata                     │
├─────────────────────────────────────────────────────┤
│  ┌─────────┐  ┌─────────┐  ┌─────────┐            │
│  │ B.Indo  │  │  Matema │  │   IPA   │            │
│  │  85.67  │  │  88.33  │  │  87.00  │            │
│  └─────────┘  └─────────┘  └─────────┘            │
│                                                     │
│  ┌───────────────────────────────────────────────┐ │
│  │  Rata-Rata Total    Nilai Minimum             │ │
│  │     87.00               80.00                 │ │
│  │                                               │ │
│  │  ✅ Nilai rata-rata Anda (87.00) memenuhi    │ │
│  │     syarat minimum (80.00)                   │ │
│  └───────────────────────────────────────────────┘ │
└─────────────────────────────────────────────────────┘

Upload Rapor: ✅ rapor_123456.pdf

Button: [ Lanjutkan → ] (ENABLED - Green)
```

---

## 📝 Files Modified

### 1. `app/Livewire/Auth/RegisterFixed.php`

**Added Method (lines 696-720):**
- `getIsStep2ValidProperty()` - Computed property for button logic

**Key Features:**
- Check all 9 nilai fields filled
- Check file rapor uploaded
- Check rata-rata memenuhi minimum
- Returns boolean for button enable/disable

### 2. `resources/views/livewire/auth/register-fixed.blade.php`

**Added Section (lines 401-472):**
- Rata-rata calculation display
- 3 cards for per-subject averages
- Total average with validation status
- Visual feedback (colors, icons)

**Modified Button Logic (lines 876-878):**
- Changed from `!$isValid` to `!$isStep2Valid`
- Now checks all 3 conditions (fields, file, average)

---

## 🧪 Testing Steps

### Test 1: Kuota Update After Registration

**Steps:**
1. Note current quota (e.g., "49/50")
2. Complete a new registration successfully
3. Return to `/daftar` (or have another user visit)
4. **Verify:** Badge shows reduced quota ("48/50")

**Expected:** ✅ Kuota berkurang 1

---

### Test 2: Rata-Rata Per Mapel Display

**Steps:**
1. Go to Step 2
2. Fill nilai for Bahasa Indonesia:
   - Kelas 5.1: 80
   - Kelas 5.2: 85
   - Kelas 6.1: 90
3. **Verify:** Rata-rata B.Indo shows **85.00**
4. Repeat for Matematika and IPA

**Expected:** Each subject shows correct average

---

### Test 3: Button Logic - Missing Fields

**Steps:**
1. Go to Step 2
2. Fill only 5 of 9 nilai fields
3. Upload file rapor
4. **Check:** Button "Lanjutkan"

**Expected:** ❌ Button DISABLED (gray)

---

### Test 4: Button Logic - Missing File

**Steps:**
1. Fill all 9 nilai fields (rata-rata >= minimum)
2. Do NOT upload file rapor
3. **Check:** Button "Lanjutkan"

**Expected:** ❌ Button DISABLED (gray)

---

### Test 5: Button Logic - Low Score

**Steps:**
1. Fill all 9 fields with low scores (average = 70)
2. Upload file rapor
3. Jalur minimum = 80
4. **Check:** Validation message and button

**Expected:**
- ⚠️ Message: "belum memenuhi syarat minimum"
- ❌ Button DISABLED (gray)

---

### Test 6: Button Logic - All Valid

**Steps:**
1. Fill all 9 fields with good scores (average = 85)
2. Upload file rapor
3. Jalur minimum = 80
4. **Check:** Validation message and button

**Expected:**
- ✅ Message: "memenuhi syarat minimum"
- ✅ Button ENABLED (green)

---

### Test 7: Complete Flow

**Steps:**
1. Select jalur (Step 1) → Next
2. Fill all nilai, upload file (Step 2) → Next
3. Fill data diri (Step 3) → Register
4. Return to `/daftar`
5. **Verify:** Kuota reduced by 1

**Expected:** Full registration success + kuota update

---

## 🔍 Technical Details

### Why Computed Property for Button?

**Livewire Computed Properties:**
- Auto-recalculated when dependencies change
- No need to manually trigger validation
- Clean, reactive code

**Example:**
```php
// When user types nilai → Livewire auto-updates
$this->nilai_b_indo_5_1 = 85;

// Computed property auto-recalculates
$isStep2Valid = $this->isStep2Valid;  // → calls getIsStep2ValidProperty()

// Button auto-updates (reactive)
```

### Why withCount() for Quota?

**Laravel withCount():**
```php
JalurPendaftaran::withCount('pendaftar')->get()
```

**Adds:** `pendaftar_count` attribute to each model

**Benefits:**
- Single query (efficient)
- Auto-updated when pendaftar added
- No manual counting needed

**SQL Generated:**
```sql
SELECT jalur_pendaftaran.*,
       COUNT(pendaftar.id) as pendaftar_count
FROM jalur_pendaftaran
LEFT JOIN pendaftar ON pendaftar.jalur_pendaftaran_id = jalur_pendaftaran.id
GROUP BY jalur_pendaftaran.id
```

---

## ✅ Verification Checklist

After implementation:

- [x] Cache cleared
- [ ] **Kuota Update:**
  - [ ] Complete a registration
  - [ ] Verify kuota reduced
  - [ ] Check badge displays correct count
- [ ] **Rata-Rata Display:**
  - [ ] Per-subject averages show correctly
  - [ ] Total average matches calculation
  - [ ] Visual design looks good
- [ ] **Button Logic:**
  - [ ] Disabled when fields empty
  - [ ] Disabled when file missing
  - [ ] Disabled when score low
  - [ ] Enabled when all valid
- [ ] **Complete Flow:**
  - [ ] Step 1 → Step 2 → Step 3 → Success
  - [ ] No errors in console
  - [ ] Kuota updates after registration

---

## 🎯 Success Criteria

All features working if:

1. ✅ Kuota berkurang setelah ada pendaftar baru
2. ✅ Rata-rata per mapel ditampilkan dengan benar
3. ✅ Rata-rata total dihitung dengan tepat
4. ✅ Button "Lanjutkan" disabled saat:
   - Nilai belum lengkap
   - File rapor belum upload
   - Rata-rata belum memenuhi
5. ✅ Button "Lanjutkan" enabled saat semua syarat terpenuhi
6. ✅ Visual feedback jelas (warna hijau/kuning)

---

## 📚 Related Documentation

- `FIX_BADGE_SERIALIZATION_ISSUE.md` - Computed property solution
- `FIX_COMPLETE_DAFTAR_PAGE_SUMMARY.md` - Complete page fixes
- `TESTING_GUIDE_DAFTAR_PAGE.md` - Testing guide

---

**Last Updated:** 2026-01-19
**Version:** 7.0 - Step 2 Improvements
**Author:** Claude Code
**Status:** ✅ READY FOR TESTING
