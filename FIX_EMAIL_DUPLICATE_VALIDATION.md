# Fix Email Duplicate Validation - Real-time Check

**Status:** ✅ COMPLETED
**Date:** 2026-01-20

---

## 🐛 Problem

User mendapatkan error saat mendaftar:

```
Pendaftaran gagal: SQLSTATE[23505]: Unique violation: 7 ERROR:
duplicate key value violates unique constraint "users_email_unique"
DETAIL: Key (email)=(kamaludinzuhri01@gmail.com) already exists.
```

**Root Cause:**
1. Database memiliki constraint `users_email_unique` yang berarti email harus **UNIQUE** (hanya boleh 1 kali)
2. Validasi di kode mengecek apakah email sudah digunakan `>= 5` kali (bukan >= 1)
3. **INKONSISTENSI** antara database schema dan validasi kode
4. Validasi hanya berjalan saat submit, bukan real-time saat user input
5. User tidak diberi feedback bahwa email sudah digunakan sebelum submit

---

## ✅ Solution

Implementasi **Real-time Email & NISN Validation** dengan:

1. ✓ Fix validasi email: check >= 1 (bukan >= 5)
2. ✓ Tambahkan method `validateEmail()` untuk real-time check
3. ✓ Tambahkan method `validateNisn()` untuk real-time check
4. ✓ Trigger validation saat blur (user keluar dari field)
5. ✓ Tampilkan error message instantly
6. ✓ Disable button jika ada validation error
7. ✓ Improved error message dengan saran login jika sudah punya akun

---

## 🔧 Technical Implementation

### 1. Backend - New Properties

**File:** `app/Livewire/Auth/RegisterFixed.php`

**Added properties (lines 72-74):**
```php
// Real-time validation messages
public $emailValidationMessage = '';
public $nisnValidationMessage = '';
```

---

### 2. Backend - Email Validation Method

**Added method (lines 466-490):**
```php
/**
 * Validate email in real-time
 */
public function validateEmail()
{
    $this->emailValidationMessage = '';

    if (empty($this->email)) {
        return;
    }

    // Validate email format
    if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
        $this->emailValidationMessage = 'Format email tidak valid.';
        return;
    }

    // Check if email already exists
    $exists = User::where('email', $this->email)->exists();
    if ($exists) {
        $this->emailValidationMessage = 'Email ini sudah terdaftar. Silakan gunakan email lain atau login jika Anda sudah memiliki akun.';
    } else {
        $this->emailValidationMessage = ''; // Clear error if email is available
    }
}
```

**Features:**
- ✓ Checks email format validity
- ✓ Checks if email exists in database
- ✓ Provides helpful error message with suggestion to login
- ✓ Clears error if email is available

---

### 3. Backend - NISN Validation Method

**Added method (lines 492-516):**
```php
/**
 * Validate NISN in real-time
 */
public function validateNisn()
{
    $this->nisnValidationMessage = '';

    if (empty($this->nisn)) {
        return;
    }

    // Validate NISN format (10 digits)
    if (strlen($this->nisn) !== 10 || !is_numeric($this->nisn)) {
        $this->nisnValidationMessage = 'NISN harus 10 digit angka.';
        return;
    }

    // Check if NISN already used 5 times
    $count = Pendaftar::where('nisn', $this->nisn)->count();
    if ($count >= 5) {
        $this->nisnValidationMessage = 'NISN ini telah digunakan untuk 5 kali pendaftaran.';
    } else {
        $this->nisnValidationMessage = ''; // Clear error if NISN is available
    }
}
```

**Features:**
- ✓ Validates NISN format (10 digits, numeric)
- ✓ Checks if NISN used 5 times (allowed limit)
- ✓ Provides clear error message
- ✓ Clears error if NISN is available

---

### 4. Backend - Fixed Submit Validation

**Changed in `confirmSubmit()` method (lines 489-499):**

**Before:**
```php
'email' => [
    'required',
    'email',
    function ($attribute, $value, $fail) {
        if (!isset($this->emailCountCache[$value])) {
            $this->emailCountCache[$value] = User::where('email', $value)->count();
        }
        if ($this->emailCountCache[$value] >= 5) {  // ❌ WRONG!
            $fail('Email ini sudah digunakan untuk 5 akun pendaftar.');
        }
    },
],
```

**After:**
```php
'email' => [
    'required',
    'email',
    function ($attribute, $value, $fail) {
        // Check if email already exists (unique constraint)
        $exists = User::where('email', $value)->exists();
        if ($exists) {  // ✓ CORRECT! Check if exists at all
            $fail('Email ini sudah terdaftar. Silakan gunakan email lain atau login jika Anda sudah memiliki akun.');
        }
    },
],
```

**Why the change:**
- Database has `users_email_unique` constraint → email must be unique
- Old validation allowed up to 5 duplicates → inconsistent with DB
- New validation checks if email exists at all (>= 1) → consistent with DB

---

### 5. Backend - Button Validation Update

**Updated `getIsStep3ValidProperty()` (lines 855-858):**

```php
// Check tidak ada validation error
$noValidationErrors = empty($this->emailValidationMessage) && empty($this->nisnValidationMessage);

return $dataPribadiValid && $dataSekolahValid && $nsmNpsnValid && $dataAkunValid && $noValidationErrors;
```

**Features:**
- ✓ Button disabled if email validation error exists
- ✓ Button disabled if NISN validation error exists
- ✓ Prevents submission with invalid data

---

### 6. Frontend - Email Field with Real-time Validation

**File:** `resources/views/livewire/auth/register-fixed.blade.php`

**Updated field (lines 759-789):**
```blade
<div class="md:col-span-2">
    <label class="block text-sm font-medium text-gray-700 mb-2">
        Email <span class="text-red-500">*</span>
    </label>
    <input type="email"
           wire:model.lazy="email"
           wire:blur="validateEmail"  <!-- ✓ Trigger validation on blur -->
           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500
                  @error('email') border-red-500 @enderror
                  @if($emailValidationMessage) border-red-500 @endif"  <!-- ✓ Red border if error -->
           placeholder="email@example.com">

    @if($emailValidationMessage)
        <!-- ✓ Show error message with icon -->
        <p class="mt-1 text-xs text-red-500 flex items-start">
            <svg class="w-4 h-4 text-red-500 mr-1 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>{{ $emailValidationMessage }}</span>
        </p>
    @else
        <!-- ✓ Show helper text if no error -->
        <p class="mt-1 text-xs text-gray-500">
            <svg class="inline w-4 h-4 text-blue-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Gunakan email aktif yang dapat diakses untuk menerima informasi pendaftaran
        </p>
    @endif

    @error('email')
        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
    @enderror
</div>
```

**Features:**
- ✓ `wire:blur="validateEmail"` triggers validation when user leaves field
- ✓ Red border if validation error
- ✓ Error message displayed with warning icon
- ✓ Helper text shown if no error
- ✓ Conditional display prevents layout shift

---

### 7. Frontend - NISN Field with Real-time Validation

**Updated field (lines 526-548):**
```blade
<div>
    <label class="block text-sm font-medium text-gray-700 mb-2">
        NISN <span class="text-red-500">*</span>
    </label>
    <input type="text"
           wire:model.lazy="nisn"
           wire:blur="validateNisn"  <!-- ✓ Trigger validation on blur -->
           maxlength="10"
           pattern="[0-9]{10}"
           onkeypress="return event.charCode >= 48 && event.charCode <= 57"
           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500
                  @error('nisn') border-red-500 @enderror
                  @if($nisnValidationMessage) border-red-500 @endif"  <!-- ✓ Red border if error -->
           placeholder="10 digit NISN (hanya angka)">

    <p class="mt-1 text-xs text-gray-500">Masukkan 10 digit NISN (hanya angka)</p>

    @if($nisnValidationMessage)
        <p class="mt-1 text-xs text-red-500">{{ $nisnValidationMessage }}</p>
    @endif

    @error('nisn')
        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
    @enderror
</div>
```

---

## 📁 Files Modified

### 1. `app/Livewire/Auth/RegisterFixed.php`

**Changes:**
- Lines 72-74: Added validation message properties
- Lines 466-490: Added `validateEmail()` method
- Lines 492-516: Added `validateNisn()` method
- Lines 489-499: Fixed email validation in `confirmSubmit()`
- Lines 855-858: Updated `isStep3Valid` to check validation errors

### 2. `resources/views/livewire/auth/register-fixed.blade.php`

**Changes:**
- Lines 526-548: Updated NISN field with real-time validation
- Lines 759-789: Updated Email field with real-time validation

---

## 🧪 Testing Checklist

### Email Validation:

1. **Test dengan email baru:**
   - [ ] Input email baru (belum terdaftar)
   - [ ] Blur field
   - [ ] Tidak ada error message
   - [ ] Helper text tetap muncul
   - [ ] Button enabled jika semua field valid

2. **Test dengan email yang sudah ada:**
   - [ ] Input email: `kamaludinzuhri01@gmail.com`
   - [ ] Blur field
   - [ ] Muncul error: "Email ini sudah terdaftar. Silakan gunakan email lain atau login jika Anda sudah memiliki akun."
   - [ ] Border berubah merah
   - [ ] Button "Daftar Sekarang" disabled

3. **Test format email invalid:**
   - [ ] Input: `invalid@email`
   - [ ] Blur field
   - [ ] Muncul error: "Format email tidak valid."
   - [ ] Border merah
   - [ ] Button disabled

4. **Test clear error:**
   - [ ] Input email sudah terdaftar → error muncul
   - [ ] Ganti ke email baru
   - [ ] Blur field
   - [ ] Error hilang
   - [ ] Helper text kembali
   - [ ] Button enabled (jika semua valid)

### NISN Validation:

1. **Test NISN valid:**
   - [ ] Input 10 digit NISN baru
   - [ ] Blur field
   - [ ] Tidak ada error
   - [ ] Button enabled

2. **Test NISN sudah digunakan 5x:**
   - [ ] Input NISN yang sudah 5x dipakai
   - [ ] Blur field
   - [ ] Error: "NISN ini telah digunakan untuk 5 kali pendaftaran."
   - [ ] Border merah
   - [ ] Button disabled

3. **Test NISN format invalid:**
   - [ ] Input kurang dari 10 digit
   - [ ] Blur field
   - [ ] Error: "NISN harus 10 digit angka."
   - [ ] Border merah
   - [ ] Button disabled

### Integration Test:

1. **Test full registration flow:**
   - [ ] Complete Step 1
   - [ ] Complete Step 2
   - [ ] Go to Step 3
   - [ ] Fill semua field dengan data valid
   - [ ] Email baru (belum terdaftar)
   - [ ] NISN valid
   - [ ] Button enabled
   - [ ] Click "Daftar Sekarang"
   - [ ] Registration SUCCESS

2. **Test dengan email duplicate:**
   - [ ] Fill semua field
   - [ ] Use email yang sudah terdaftar
   - [ ] Error message muncul saat blur
   - [ ] Button tetap disabled
   - [ ] Cannot submit

---

## 🎯 Expected Behavior

### Scenario 1: Email Baru (Valid)
```
User input: newemail@example.com
On blur: ✓ No error
Display: Helper text "Gunakan email aktif..."
Border: Normal (gray)
Button: Enabled (if all fields valid)
Submit: SUCCESS
```

### Scenario 2: Email Sudah Ada
```
User input: kamaludinzuhri01@gmail.com
On blur: ✗ Error appears
Display: "Email ini sudah terdaftar. Silakan gunakan email lain atau login jika Anda sudah memiliki akun."
Border: Red
Button: Disabled
Submit: Cannot submit
```

### Scenario 3: Email Format Invalid
```
User input: notanemail
On blur: ✗ Error appears
Display: "Format email tidak valid."
Border: Red
Button: Disabled
Submit: Cannot submit
```

### Scenario 4: NISN Sudah 5x Digunakan
```
User input: 1234567890 (sudah 5x)
On blur: ✗ Error appears
Display: "NISN ini telah digunakan untuk 5 kali pendaftaran."
Border: Red
Button: Disabled
Submit: Cannot submit
```

---

## 🚀 Benefits

1. **Better UX:**
   - User langsung tahu jika email sudah digunakan
   - Tidak perlu menunggu submit untuk tahu error
   - Error message helpful dengan saran login

2. **Prevent Errors:**
   - Tidak ada lagi unique constraint violation error
   - Validasi konsisten dengan database schema
   - Real-time feedback

3. **Data Integrity:**
   - Email benar-benar unique di database
   - NISN limit enforcement (max 5x)
   - Proper validation before insert

4. **Improved Error Messages:**
   - Clear, actionable messages
   - Suggestion to login if already have account
   - Visual feedback (red border + icon)

---

## 📝 Notes

- Email validation triggers on `blur` (when user leaves field)
- NISN validation also triggers on `blur`
- Button validation checks both `emailValidationMessage` and `nisnValidationMessage`
- Error messages are cleared automatically when user fixes the issue
- Helper text shown only when no error (prevents clutter)

---

## 🔍 Troubleshooting

### Issue: Email validation tidak jalan

**Check:**
```bash
# Pastikan wire:blur ada di blade
grep "wire:blur=\"validateEmail\"" resources/views/livewire/auth/register-fixed.blade.php

# Pastikan method exists di component
grep "public function validateEmail" app/Livewire/Auth/RegisterFixed.php
```

### Issue: Button masih enabled meskipun ada error

**Check:**
```bash
# Pastikan isStep3Valid mengecek validation errors
grep "noValidationErrors" app/Livewire/Auth/RegisterFixed.php
```

### Issue: Error tidak muncul

**Solution:**
```bash
# Clear cache
php artisan optimize:clear

# Hard refresh browser
Ctrl + Shift + R (Windows)
Cmd + Shift + R (Mac)
```

---

**Last Updated:** 2026-01-20
**Status:** ✅ READY FOR TESTING
