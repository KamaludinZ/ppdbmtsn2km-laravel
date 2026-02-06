# ✅ STRICT VALIDATION - Survey Tes Psikologi

**Date:** February 5, 2026
**Feature:** Disable tombol jika pertanyaan wajib belum dijawab
**Status:** READY FOR TESTING

---

## 🎯 Problem Yang Diperbaiki

### Before (Masalah):
```
❌ User bisa klik "Selanjutnya" meski pertanyaan wajib belum dijawab
❌ Validasi hanya di backend (flash message timeout 3s, sering terlewat)
❌ Tidak ada feedback visual yang jelas
❌ User bisa melewati pertanyaan wajib dengan cepat klik-klik
```

### After (Solusi):
```
✅ Tombol "Selanjutnya" DISABLED jika pertanyaan wajib belum dijawab
✅ Tombol "Selesai" DISABLED jika ada pertanyaan wajib yang terlewat
✅ Peringatan merah muncul: "Pertanyaan ini wajib diisi!"
✅ Real-time feedback: tombol auto-enabled ketika user jawab
✅ Tidak bisa skip pertanyaan wajib
```

---

## 📊 Visual Flow

### 1. **Pertanyaan Wajib Belum Dijawab**
```
┌─────────────────────────────────────────────┐
│ 1  Apakah Anda setuju? *                    │
│                                             │
│ ○ Sangat Setuju                             │
│ ○ Setuju                                    │
│ ○ Tidak Setuju                              │
│                                             │
│    [Sebelumnya]    [Selanjutnya] ← DISABLED │
│                                             │
│ ⚠️ Pertanyaan ini wajib diisi!              │
│    Silakan jawab pertanyaan ini untuk       │
│    melanjutkan ke pertanyaan berikutnya.    │
└─────────────────────────────────────────────┘
```

**State:**
- Tombol "Selanjutnya" berwarna abu-abu (disabled)
- Cursor: not-allowed
- Peringatan merah muncul di bawah tombol
- User tidak bisa klik tombol

### 2. **Pertanyaan Wajib Sudah Dijawab**
```
┌─────────────────────────────────────────────┐
│ 1  Apakah Anda setuju? *                    │
│                                             │
│ ● Sangat Setuju  ← User pilih ini          │
│ ○ Setuju                                    │
│ ○ Tidak Setuju                              │
│                                             │
│    [Sebelumnya]    [Selanjutnya] ← ENABLED  │
│                                             │
│    ✓ Peringatan hilang                      │
└─────────────────────────────────────────────┘
```

**State:**
- Tombol "Selanjutnya" berwarna indigo (enabled)
- Cursor: pointer
- Peringatan HILANG
- User bisa klik tombol untuk lanjut

---

## 🔧 Technical Implementation

### 1. Backend - Computed Properties

**File:** `app/Livewire/Pendaftar/TesPsikologi.php`

**Added:**
```php
/**
 * Check apakah pertanyaan current sudah dijawab
 */
public function getIsCurrentQuestionAnsweredProperty()
{
    $currentQuestion = $this->surveyQuestions[$this->currentQuestionIndex];

    // Jika tidak wajib, always return true (boleh skip)
    if (!$currentQuestion->wajib_diisi) {
        return true;
    }

    $answer = $this->surveyAnswers[$currentQuestion->id] ?? null;

    // Empty checks
    if (empty($answer)) return false;
    if (is_array($answer) && count($answer) === 0) return false;
    if (is_string($answer) && trim($answer) === '') return false;

    return true;
}

/**
 * Check apakah semua pertanyaan wajib sudah dijawab
 */
public function getAreAllRequiredQuestionsAnsweredProperty()
{
    foreach ($this->surveyQuestions as $question) {
        if ($question->wajib_diisi) {
            $answer = $this->surveyAnswers[$question->id] ?? null;

            if (empty($answer)) return false;
            if (is_array($answer) && count($answer) === 0) return false;
            if (is_string($answer) && trim($answer) === '') return false;
        }
    }
    return true;
}
```

**Benefits:**
- Real-time validation tanpa AJAX request
- Livewire computed properties auto-update ketika `surveyAnswers` berubah
- Efficient - hanya check pertanyaan yang relevan

### 2. Frontend - Disabled Buttons

**File:** `resources/views/livewire/pendaftar/tes-psikologi.blade.php`

**Tombol "Selanjutnya":**
```blade
<button type="button"
        wire:click="nextQuestion"
        @if(!$this->isCurrentQuestionAnswered) disabled @endif
        class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white
               font-medium rounded-lg hover:bg-indigo-700 transition
               disabled:opacity-50 disabled:cursor-not-allowed disabled:bg-gray-400">
    Selanjutnya
</button>
```

**Tombol "Selesai":**
```blade
<button type="button"
        wire:click="simpanSurvey"
        @if(!$this->areAllRequiredQuestionsAnswered) disabled @endif
        class="inline-flex items-center px-6 py-3 bg-green-600 text-white
               font-medium rounded-lg hover:bg-green-700 transition
               disabled:opacity-50 disabled:cursor-not-allowed disabled:bg-gray-400">
    Selesai & Simpan
</button>
```

**Peringatan Visual:**
```blade
@if($question->wajib_diisi && !$this->isCurrentQuestionAnswered)
    <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
        <svg class="w-5 h-5 text-red-600">...</svg>
        <p class="text-sm font-bold text-red-900">Pertanyaan ini wajib diisi!</p>
        <p class="text-xs text-red-800">Silakan jawab pertanyaan ini untuk melanjutkan...</p>
    </div>
@endif
```

---

## 🧪 Testing Scenarios

### Test 1: Radio Button (Single Choice)

**Steps:**
```
1. Buka /pendaftar/tes-psikologi
2. Pertanyaan wajib dengan tipe radio
3. Jangan pilih apapun
```

**Expected:**
- ✅ Tombol "Selanjutnya" DISABLED (abu-abu)
- ✅ Peringatan merah muncul
- ✅ Click tombol tidak ada efek

**Steps Lanjutan:**
```
4. Pilih salah satu opsi radio
```

**Expected:**
- ✅ Tombol "Selanjutnya" jadi ENABLED (indigo)
- ✅ Peringatan hilang
- ✅ Click tombol → pindah ke pertanyaan berikutnya

### Test 2: Checkbox (Multiple Choice)

**Steps:**
```
1. Pertanyaan wajib dengan tipe checkbox
2. Jangan centang apapun
```

**Expected:**
- ✅ Tombol DISABLED
- ✅ Peringatan muncul

**Steps Lanjutan:**
```
3. Centang minimal 1 checkbox
```

**Expected:**
- ✅ Tombol jadi ENABLED
- ✅ Peringatan hilang

### Test 3: Text Input

**Steps:**
```
1. Pertanyaan wajib dengan tipe text
2. Field kosong
```

**Expected:**
- ✅ Tombol DISABLED

**Steps Lanjutan:**
```
3. Ketik jawaban (minimal 1 karakter)
```

**Expected:**
- ✅ Tombol jadi ENABLED
- ✅ Real-time: tombol enabled langsung saat user ketik

### Test 4: Select Dropdown

**Steps:**
```
1. Pertanyaan wajib dengan tipe select
2. Pilihan default: "-- Pilih Jawaban --"
```

**Expected:**
- ✅ Tombol DISABLED

**Steps Lanjutan:**
```
3. Pilih salah satu option (bukan default)
```

**Expected:**
- ✅ Tombol jadi ENABLED

### Test 5: Pertanyaan Tidak Wajib

**Steps:**
```
1. Pertanyaan TIDAK wajib (tanpa tanda *)
2. Jangan jawab apapun
```

**Expected:**
- ✅ Tombol "Selanjutnya" tetap ENABLED
- ✅ Tidak ada peringatan
- ✅ User boleh skip pertanyaan ini

### Test 6: Tombol "Selesai" - Final Question

**Steps:**
```
1. Isi survey sampai pertanyaan terakhir
2. Ada 3 pertanyaan wajib total
3. Baru jawab 2 pertanyaan wajib
4. Pertanyaan terakhir (wajib) belum dijawab
```

**Expected:**
- ✅ Tombol "Selesai" DISABLED
- ✅ Peringatan muncul

**Steps Lanjutan:**
```
5. Jawab pertanyaan terakhir
```

**Expected:**
- ✅ Tombol "Selesai" jadi ENABLED
- ✅ Click → Survey tersimpan → Redirect ke upload bukti

### Test 7: Mixed Required & Optional

**Steps:**
```
1. Survey dengan 5 pertanyaan:
   - Q1: Wajib (radio)
   - Q2: Tidak wajib (text)
   - Q3: Wajib (checkbox)
   - Q4: Tidak wajib (select)
   - Q5: Wajib (textarea)
```

**Expected Behavior:**
- Q1: Harus dijawab untuk lanjut
- Q2: Boleh skip
- Q3: Harus dijawab untuk lanjut
- Q4: Boleh skip
- Q5: Harus dijawab untuk click "Selesai"

---

## ✅ Success Criteria

### 1. UI/UX
- [ ] Tombol disabled punya visual yang jelas (abu-abu, opacity 50%)
- [ ] Cursor berubah jadi `not-allowed` saat hover disabled button
- [ ] Peringatan merah muncul dengan icon warning yang jelas
- [ ] Tanda `*` merah muncul di label pertanyaan wajib

### 2. Functionality
- [ ] Tombol disabled tidak bisa diklik (no backend call)
- [ ] Tombol auto-enabled ketika user jawab (real-time)
- [ ] Validasi backend masih jalan (double protection)
- [ ] Pertanyaan tidak wajib boleh di-skip

### 3. Performance
- [ ] Computed properties tidak menyebabkan lag
- [ ] Livewire wire:model update smooth
- [ ] No full page reload

---

## 📦 Deployment

### Files Modified (2 files):
1. `app/Livewire/Pendaftar/TesPsikologi.php` - Added computed properties
2. `resources/views/livewire/pendaftar/tes-psikologi.blade.php` - Disabled buttons + warning

### Upload Script:
```bash
upload_fix_survey_FINAL_with_strict_validation.bat
```

### After Upload:
```bash
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
php artisan view:clear
php artisan optimize:clear
```

---

## 🎯 Benefits Summary

### For Users (Pendaftar):
✅ **Clear Guidance** - Tahu mana yang wajib diisi
✅ **No Confusion** - Tombol disabled = belum boleh lanjut
✅ **Instant Feedback** - Langsung enabled saat dijawab
✅ **Better UX** - Tidak perlu trial-error klik tombol

### For System:
✅ **Data Quality** - Semua pertanyaan wajib pasti terisi
✅ **Less Errors** - Validasi di frontend lebih ketat
✅ **Better Flow** - User tidak bisa skip pertanyaan penting
✅ **Double Protection** - Frontend + Backend validation

---

## 📞 Troubleshooting

### Issue: Tombol tetap disabled meski sudah dijawab

**Possible Causes:**
1. Livewire `wire:model` tidak ter-bind dengan benar
2. Cache issue di browser
3. Computed property tidak update

**Debug:**
```php
// Di Livewire component
dd($this->surveyAnswers);
dd($this->isCurrentQuestionAnswered);
```

**Fix:**
```bash
php artisan view:clear
Hard refresh browser (Ctrl+Shift+R)
```

### Issue: Peringatan tidak muncul

**Check:**
```blade
@if($question->wajib_diisi && !$this->isCurrentQuestionAnswered)
    <!-- This should render -->
@endif
```

Pastikan `$question` variable ada di context.

---

**STATUS: ✅ READY FOR LOCAL TESTING**

Test fitur strict validation ini secara menyeluruh di lokal sebelum upload ke production!
