# NSM & NPSN Numeric Field Fix
Date: 2026-01-18

## Problem
Field NSM (Nomor Statistik Madrasah) dan NPSN (Nomor Pokok Sekolah Nasional) di form pendaftaran dapat menerima input huruf dan karakter lain, seharusnya hanya menerima angka.

## Solution
Menambahkan validasi numeric-only pada frontend dan backend.

## Files Modified

### 1. Frontend - Registration Form
**File:** `resources/views/livewire/auth/register.blade.php`

#### NSM Field (12 digits)
- Added `@input` event to filter non-numeric characters
- Added `pattern="[0-9]{12}"` for HTML5 validation
- Added `inputmode="numeric"` for mobile numeric keyboard
- Added `title` attribute for better UX
- Keep `maxlength="12"`

#### NPSN Field (8 digits)
- Added `@input` event to filter non-numeric characters
- Added `pattern="[0-9]{8}"` for HTML5 validation
- Added `inputmode="numeric"` for mobile numeric keyboard
- Added `title` attribute for better UX
- Keep `maxlength="8"`

### 2. Frontend - Panitia Detail Form
**File:** `resources/views/livewire/panitia/detail-pendaftar.blade.php`
- Updated NSM field with same numeric restrictions
- Updated NPSN field with same numeric restrictions
- Added placeholder text "12 digit angka" and "8 digit angka"

### 3. Backend Validation
**File:** `app/Livewire/Auth/Register.php`
- Changed NSM validation from `'nullable|string|size:12'` to `'nullable|digits:12'`
- Changed NPSN validation from `'nullable|string|size:8'` to `'nullable|digits:8'`

## Validation Details

### NSM (Nomor Statistik Madrasah)
- **Length:** Exactly 12 digits
- **Format:** Numeric only (0-9)
- **Example:** 121135740001

### NPSN (Nomor Pokok Sekolah Nasional)
- **Length:** Exactly 8 digits
- **Format:** Numeric only (0-9)
- **Example:** 20536326

## Features Implemented

1. **Real-time filtering**: Non-numeric characters are automatically removed as user types
2. **Mobile-friendly**: Shows numeric keyboard on mobile devices
3. **HTML5 validation**: Pattern attribute for browser validation
4. **Server-side validation**: Laravel validation rules using `digits` rule
5. **User feedback**: Title attribute shows requirements on hover
6. **Visual limits**: Maxlength prevents entering more than allowed digits

## Testing
1. Try typing letters - they should not appear
2. Try pasting mixed text - only numbers should remain
3. Try submitting with wrong length - should show validation error
4. Mobile devices should show numeric keyboard

## Result
✅ NSM field now only accepts 12 numeric digits
✅ NPSN field now only accepts 8 numeric digits
✅ Both frontend and backend validation in place
✅ Better UX with numeric keyboards on mobile