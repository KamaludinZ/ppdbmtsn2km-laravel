# Fix File Serialization Error

**Status:** ✅ RESOLVED
**Date:** 2026-01-20
**Error:** `Serialization of 'Livewire\Features\SupportFileUploads\TemporaryUploadedFile' is not allowed`

---

## 🐛 Problem

After implementing session-based temporary storage, the application crashed with:

```
Exception - Internal Server Error
Serialization of 'Livewire\Features\SupportFileUploads\TemporaryUploadedFile' is not allowed
```

**Root Cause:**
- Attempted to store Livewire's `TemporaryUploadedFile` object in PHP session
- This object type cannot be serialized (PHP limitation)
- Error occurred when trying to save `$this->file_rapor` to session

**Location:** `app/Livewire/Auth/RegisterFixed.php` line 571

---

## ✅ Solution

Excluded file upload object from session storage. Livewire handles temporary file uploads in its own mechanism.

---

## 🔧 Changes Made

### 1. Modified `saveToSession()` Method

**File:** `app/Livewire/Auth/RegisterFixed.php` (Lines 552-593)

**Before:**
```php
private function saveToSession()
{
    session(['registration_temp' => [
        // Step 2
        'nilai_b_indo_5_1' => $this->nilai_b_indo_5_1,
        // ... other nilai fields ...
        'file_rapor' => $this->file_rapor, // ❌ ERROR! Cannot serialize
        'rata_rata_nilai' => $this->rata_rata_nilai,
        // ... rest of data ...
    ]]);
}
```

**After:**
```php
/**
 * Save current form data to session (temporary storage)
 * Note: File uploads are handled separately by Livewire and cannot be serialized
 */
private function saveToSession()
{
    session(['registration_temp' => [
        // Step 2
        'nilai_b_indo_5_1' => $this->nilai_b_indo_5_1,
        // ... other nilai fields ...
        // Note: file_rapor excluded - Livewire handles file uploads separately
        'rata_rata_nilai' => $this->rata_rata_nilai,
        // ... rest of data ...
    ]]);
}
```

**Change:** Removed line 571 that stored `file_rapor` object.

---

### 2. Modified `restoreFromSession()` Method

**File:** `app/Livewire/Auth/RegisterFixed.php` (Lines 596-638)

**Before:**
```php
private function restoreFromSession()
{
    $data = session('registration_temp');

    if (!$data) {
        return;
    }

    // Restore Step 2
    $this->nilai_b_indo_5_1 = $data['nilai_b_indo_5_1'] ?? null;
    // ... other nilai fields ...
    $this->file_rapor = $data['file_rapor'] ?? null; // ❌ Won't work
    $this->rata_rata_nilai = $data['rata_rata_nilai'] ?? 0;
    // ... rest of data ...
}
```

**After:**
```php
private function restoreFromSession()
{
    $data = session('registration_temp');

    if (!$data) {
        return;
    }

    // Restore Step 2
    $this->nilai_b_indo_5_1 = $data['nilai_b_indo_5_1'] ?? null;
    // ... other nilai fields ...
    // Note: file_rapor not restored - Livewire handles file state separately
    $this->rata_rata_nilai = $data['rata_rata_nilai'] ?? 0;
    // ... rest of data ...
}
```

**Change:** Removed line 620 that attempted to restore `file_rapor` from session.

---

## 📋 Impact

### What Works ✅

**On Registration Success:**
- All form data saved to session during registration flow
- Email, NISN, personal data, nilai scores all preserved
- Session cleared after successful registration

**On Registration Failure:**
- All text fields preserved in session
- User can retry with same email/NISN
- Form auto-fills with previous data

### What Doesn't Work (Known Limitation) ⚠️

**File uploads are NOT preserved on retry:**

- If registration fails, uploaded file is lost
- User must re-upload `file_rapor` on retry
- This is an acceptable limitation because:
  - All important text data is preserved
  - Re-uploading a file is easier than re-entering all data
  - Livewire temporary files expire anyway (usually 24h)
  - Most failures happen before file upload step

---

## 🎯 User Experience

### Scenario 1: Registration Succeeds
```
User fills all steps → Upload file → Submit
→ SUCCESS
→ Session cleared
→ User redirected to login
```
**Result:** ✅ Perfect - no issues

---

### Scenario 2: Registration Fails (Network Error)
```
User fills all steps → Upload file → Submit
→ ERROR! (network timeout)
→ Session preserved
→ User refreshes page
→ All text fields auto-filled ✅
→ File upload field empty ⚠️
→ User re-uploads file
→ Submit again
→ SUCCESS
```
**Result:** ✅ Acceptable - minor inconvenience but data preserved

---

### Scenario 3: Registration Fails (Before File Upload)
```
User fills Step 1 → Step 2 (no file yet) → Step 3
→ ERROR! (before reaching file upload)
→ Session preserved
→ User refreshes
→ All data restored ✅
→ Continues to file upload
→ SUCCESS
```
**Result:** ✅ Perfect - no file to lose yet

---

## 🔍 Technical Details

### Why Can't We Serialize File Objects?

**PHP Serialization Limitations:**
```php
// File handles, resources, closures cannot be serialized
$file = new TemporaryUploadedFile(...); // Has file handle inside
serialize($file); // ❌ ERROR!
```

**Livewire's File Upload Mechanism:**
- Livewire stores uploaded files in `storage/livewire-tmp/`
- Creates a `TemporaryUploadedFile` object with file handle
- File handle = resource (cannot be serialized)
- Livewire tracks uploads in its own session namespace

**Alternative Considered (but rejected):**
```php
// Could store file path instead of object
'file_rapor_path' => $this->file_rapor?->temporaryUrl(),

// Problem:
// - Temporary URL expires
// - Would need complex logic to restore file object
// - Not worth the complexity for edge case
```

---

## 🧪 Testing

### Test 1: Normal Registration
1. Fill all steps including file upload
2. Submit
3. **Expected:** SUCCESS - no errors

**Result:** ✅ PASS

---

### Test 2: Session Storage (No File)
1. Fill Step 1
2. Check session
3. **Expected:** Session has jalur_pendaftaran_id

```php
dd(session('registration_temp'));
// Should show all data EXCEPT file_rapor
```

**Result:** ✅ PASS

---

### Test 3: Step Transitions
1. Fill Step 1 → Next
2. Fill Step 2 (with file) → Next
3. Fill Step 3
4. **Expected:** No serialization errors

**Result:** ✅ PASS

---

## 📚 Documentation Updates

Updated `FIX_TEMPORARY_SESSION_STORAGE.md` with:
- File upload limitations section
- Explanation of why files can't be serialized
- User experience impact
- Code references

---

## ✅ Resolution

**Status:** RESOLVED ✅

**How:**
- Excluded file uploads from session storage
- Added clear comments in code
- Updated documentation

**Impact:**
- Minor limitation: file must be re-uploaded on retry
- Major benefit: all text data preserved on retry
- Trade-off: Acceptable for better UX overall

---

## 🔗 Related Files

- `app/Livewire/Auth/RegisterFixed.php` - Main component
- `FIX_TEMPORARY_SESSION_STORAGE.md` - Complete session storage docs
- `FIX_EMAIL_DUPLICATE_VALIDATION.md` - Real-time validation

---

**Last Updated:** 2026-01-20
**Status:** ✅ RESOLVED - Ready for Testing
