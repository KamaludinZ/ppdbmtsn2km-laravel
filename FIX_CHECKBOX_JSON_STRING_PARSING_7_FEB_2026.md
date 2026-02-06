# Fix: Checkbox JSON String Parsing - 7 Februari 2026

**Status**: ✅ FIXED
**File Modified**: 1 file (TesPsikologi.php)
**Issue Found By**: User testing - label tidak muncul karena array `[10,12]` tidak bisa di-parse

---

## 🐛 PROBLEM

User melaporkan: _"kenapa id tersimpan dengan tanda [ ] karena sepertinya itu yang membuat label jadi tidak bisa muncul karena tidak bisa membaca id karenya adanya [ ]"_

**Symptoms**:
1. Checkbox values tersimpan dengan tanda `[]` seperti `[10,12]`
2. Label tidak muncul, hanya tampil ID atau raw value dengan `[]`
3. Display menampilkan `[10,12]` instead of "Label 1, Label 2"

---

## 🔍 ROOT CAUSE

**PostgreSQL JSONB Behavior**:
- PostgreSQL simpan array sebagai JSONB: `[10,12]`
- Saat query data, PostgreSQL bisa return:
  - **Option A**: PHP array `[10, 12]` (jika Laravel auto-decode)
  - **Option B**: JSON string `"[10,12]"` (jika tidak auto-decode)

**Problem**: Code hanya handle Option A (PHP array), tidak handle Option B (JSON string).

**Location**: `app/Livewire/Admin/TesPsikologi.php`

### **Problem 1: Display Logic**

**Lines 1333** (BEFORE):
```php
$jawabanArray = is_array($jawaban) ? $jawaban : [$jawaban];
```

**Issue**:
- Jika `$jawaban = "[10,12]"` (string), maka `$jawabanArray = ["[10,12]"]`
- Loop coba match string `"[10,12]"` sebagai single ID
- Tidak ada option dengan ID `"[10,12]"` → Label not found!

---

### **Problem 2: Edit Modal Load Logic**

**Lines 1093-1102** (BEFORE):
```php
if ($answer) {
    $jawaban = $answer->jawaban;

    if ($this->editingAnswerQuestion->tipe_input === 'checkbox') {
        if (is_array($jawaban)) {
            // Handle array
        } else {
            // Handle single value
        }
    }
}
```

**Issue**:
- Jika `$jawaban = "[10,12]"` (string), masuk ke `else` branch (single value)
- Coba convert string `"[10,12]"` menjadi checkbox value
- Gagal bind ke checkboxes dengan benar

---

## ✅ SOLUTION

### **Fix 1: Display Logic - Parse JSON String**

**Lines 1334-1344** (AFTER):
```php
// CRITICAL FIX: Handle both PHP array and JSON string array
// PostgreSQL JSONB can return as string "[10,12]" instead of PHP array
if (is_string($jawaban) && (str_starts_with($jawaban, '[') || str_starts_with($jawaban, '{'))) {
    // Decode JSON string to PHP array
    $decoded = json_decode($jawaban, true);
    $jawabanArray = (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) ? $decoded : [$jawaban];
} elseif (is_array($jawaban)) {
    $jawabanArray = $jawaban;
} else {
    $jawabanArray = [$jawaban];
}
```

**How it works**:
1. Check if `$jawaban` is string starting with `[` or `{`
2. Try `json_decode()` to convert to PHP array
3. If successful, use decoded array
4. If failed, treat as single value
5. Continue with existing label matching logic

---

### **Fix 2: Edit Modal - Parse JSON String Before Processing**

**Lines 1095-1102** (AFTER):
```php
if ($answer) {
    $jawaban = $answer->jawaban;

    // CRITICAL FIX: Handle JSON string from PostgreSQL JSONB
    // PostgreSQL can return JSONB as string "[10,12]" instead of PHP array
    if (is_string($jawaban) && (str_starts_with($jawaban, '[') || str_starts_with($jawaban, '{'))) {
        $decoded = json_decode($jawaban, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            $jawaban = $decoded;
        }
    }

    // For checkbox, store as array for checkboxes
    if ($this->editingAnswerQuestion->tipe_input === 'checkbox') {
        // Now $jawaban is guaranteed to be PHP array or single value
        // Existing logic works correctly
    }
}
```

**How it works**:
1. Decode JSON string BEFORE type checking
2. Convert `"[10,12]"` → `[10, 12]`
3. Now `is_array($jawaban)` returns true
4. Existing checkbox logic works correctly

---

## 📦 FILE AFFECTED

### **File 1**: `TesPsikologi.php`

**Location**: `app/Livewire/Admin/TesPsikologi.php`

**Changes**:
- **Lines 1334-1344**: Display logic - Added JSON string parsing
- **Lines 1095-1102**: Edit modal load - Added JSON string parsing

**Upload to**: `/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/`

---

## 🔄 DATA FLOW

### **BEFORE (Broken)**:
```
Database: [10,12] (JSONB)
    ↓
Laravel Query: "[10,12]" (string)
    ↓
Display Logic: is_array? No → Treat as single value
    ↓
Match ID "[10,12]" → Not found!
    ↓
Display: "[10,12] ⚠️" (raw value with warning)
```

### **AFTER (Fixed)** ✅:
```
Database: [10,12] (JSONB)
    ↓
Laravel Query: "[10,12]" (string)
    ↓
JSON Parse: [10, 12] (PHP array)
    ↓
Display Logic: Loop through [10, 12]
    ↓
Match ID 10 → "Label Option 1"
Match ID 12 → "Label Option 3"
    ↓
Display: "Label Option 1, Label Option 3" ✅
```

---

## 🧪 TESTING

### **Test 1: Display Checkbox Labels**

1. Buka `/admin/tes-psikologi?mainTab=jawaban-survey`
2. Cari pendaftar dengan checkbox answers
3. ✅ **Verify**: Labels muncul (e.g., "Label 1, Label 2")
4. ✅ **Verify**: TIDAK ada `[10,12]` atau brackets di display
5. ✅ **Verify**: TIDAK ada `⚠️` warning symbol

---

### **Test 2: Edit Checkbox Values**

1. Enable Mode Edit
2. Click edit pada checkbox answer
3. ✅ **Verify**: Modal opens dengan checkboxes ter-check sesuai saved values
4. ✅ **Verify**: Checkboxes yang dipilih sebelumnya highlighted
5. Change selections
6. Click "Simpan"
7. ✅ **Verify**: New values saved correctly
8. Refresh page
9. ✅ **Verify**: Labels muncul dengan benar (bukan raw IDs)

---

### **Test 3: New Checkbox Answer**

1. Enable Mode Edit
2. Open checkbox question dengan no existing answer
3. Select multiple options
4. Click "Simpan"
5. ✅ **Verify**: Labels muncul di table (bukan IDs atau brackets)

---

## 📊 BEFORE vs AFTER

| Aspect | BEFORE | AFTER |
|--------|--------|-------|
| **Display Format** | `[10,12]` or `[10,12] ⚠️` | `Label 1, Label 2` ✅ |
| **JSON String Handling** | ❌ Not handled | ✅ Decoded to array ✅ |
| **Edit Modal Load** | ❌ String treated as single value | ✅ Decoded then processed ✅ |
| **Label Matching** | ❌ Failed (matches whole string) | ✅ Works (matches each ID) ✅ |
| **User Experience** | ❌ Confusing (shows IDs/brackets) | ✅ Clear (shows labels) ✅ |

---

## 🔧 WHY THIS HAPPENED

### **PostgreSQL JSONB Behavior**:

PostgreSQL JSONB storage is flexible:
- Simpan array: `[10, 12]`
- Simpan object: `{"key": "value"}`
- Simpan scalar: `"value"` or `123`

**Laravel + PostgreSQL Interaction**:
- Sometimes auto-casts JSONB to PHP types
- Sometimes returns as JSON string
- Depends on query method, connection config, driver version

**Model SurveyAnswer** (lines 18-25):
```php
protected $casts = [
    // 'jawaban' => 'array', // ❌ REMOVED! This was causing the overwrite bug!
];
```

**Why removed?**: Cast to 'array' caused other bugs (overwrite issues)

**Side effect**: No auto-decode, so JSONB returns as string

**Solution**: Manual JSON decode in display/load logic ✅

---

## 💡 DEFENSIVE PROGRAMMING

The fix handles ALL possible data formats:

1. **PHP Array**: `[10, 12]` → Use directly
2. **JSON String Array**: `"[10,12]"` → Decode to array
3. **JSON String Object**: `"{\"key\":\"val\"}"` → Decode (though not used for checkbox)
4. **Single Value**: `10` → Wrap in array `[10]`
5. **String Value**: `"value"` → Wrap in array `["value"]`

**Robust Against**:
- Driver changes
- Laravel version updates
- PostgreSQL version updates
- Query method changes

---

## 🎯 RELATED FIXES

This is part of **Checkbox System Improvements**:

1. ✅ UI improvement (text input → checkboxes)
2. ✅ Visual feedback (indigo highlight)
3. ✅ Zero value filtering
4. ✅ JSON string parsing (THIS FIX) ← New!

---

## 🚀 UPLOAD INSTRUCTIONS

**File Modified**: `TesPsikologi.php` (same file, additional fixes)

### **Step 1: Upload File (OVERWRITE)**

**Local Path**:
```
C:\ppdbmtsn2km-laravel\app\Livewire\Admin\TesPsikologi.php
```

**Remote Path**:
```
/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/
```

**Action**: OVERWRITE existing file (~57 KB)

### **Step 2: Clear Cache**

```bash
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
php artisan optimize:clear
```

### **Step 3: Test Display**

1. Open `/admin/tes-psikologi?mainTab=jawaban-survey`
2. Check checkbox answers
3. Verify labels muncul (NOT raw IDs or brackets)

---

## ✅ SUMMARY

### **What's Fixed**:
✅ JSON string parsing di display logic
✅ JSON string parsing di edit modal load
✅ Checkbox labels now display correctly
✅ No more `[10,12]` raw format shown to user
✅ Edit modal loads values correctly

### **Files Updated**:
- `TesPsikologi.php` (same file, lines 1095-1102 and 1334-1344)

### **Testing Time**:
- 5 minutes (test checkbox display and edit)

---

**Fixed**: 7 Februari 2026, 00:15 WIB
**Status**: ✅ READY
**Impact**: High - Fixes major display issue
**Risk**: Low - Defensive parsing, handles all formats

---

## 📚 UPDATED FILE STATUS

**File 1** (`TesPsikologi.php`) now includes:
1. ✅ Toggle Edit Mode system
2. ✅ ID Display system
3. ✅ Jalur field fix (nama_jalur → nama)
4. ✅ Checkbox array handling
5. ✅ Zero value filtering
6. ✅ JSON string parsing ← NEW!

**Total Changes in This File**: 6 major improvements

---

✅ **SIAP UPLOAD!** - Same file, now with JSON string parsing fix.
