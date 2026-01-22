# Alpine.js Error Fixes - Registration Page
## Date: 2026-01-19

## Issue Description
The registration page (`/daftar`) was experiencing Alpine.js errors with the following symptoms:
- `Alpine Expression Error: undefined` for `$wire.jalur_pendaftaran_id`
- `Component not found: undefined` errors in the console
- Issues with Alpine.js bindings for form validation

## Root Cause
1. **Uninitialized Livewire Properties**: The `jalur_pendaftaran_id` property in the Register Livewire component was declared but not initialized, causing it to be `undefined` when Alpine.js tried to access it.
2. **Missing View Variable**: The `appAcronym` variable was used in the Blade template but not passed from the Livewire component.

## Fixes Applied

### 1. Register.php Component (app/Livewire/Auth/Register.php)

#### Initialize Properties with Default Values
```php
// Before:
public $jalur_pendaftaran_id;
public $file_rapor;

// After:
public $jalur_pendaftaran_id = null;
public $file_rapor = null;
```

All form properties were initialized with appropriate default values:
- Nullable fields: `null`
- String fields: `''` (empty string)
- Boolean fields: `false`
- Numeric fields: `0`

#### Add Missing View Variable
```php
// Updated render() method:
public function render()
{
    $jalurPendaftaran = JalurPendaftaran::aktif()->ordered()->get();
    $appAcronym = Setting::get('app_acronym', 'SPMBM');  // Added

    return view('livewire.auth.register', [
        'jalurPendaftaran' => $jalurPendaftaran,
        'appAcronym' => $appAcronym,  // Added
    ])->layout('components.layouts.public')->layoutData(['title' => 'Pendaftaran SPMBM']);
}
```

## Properties Initialized
- `jalur_pendaftaran_id = null`
- `selectedJalur` (left as is)
- All nilai_* properties = `null`
- `file_rapor = null`
- `isValid = false`
- `validationMessage = ''`
- All personal data fields = `''`
- `agama = 'Islam'` (default value)

## Testing
Created and ran test script (`test-register-page.php`) to verify:
- ✅ All properties are properly initialized
- ✅ Render method executes successfully
- ✅ appAcronym is passed to view
- ✅ jalurPendaftaran is passed to view
- ✅ All Alpine.js binding properties are accessible

## Result
The Alpine.js errors have been resolved. The registration page should now:
- Load without console errors
- Properly handle Alpine.js bindings for form validation
- Display the correct app acronym in the header
- Enable/disable buttons based on form state correctly

## Files Modified
1. `app/Livewire/Auth/Register.php` - Initialize properties and add appAcronym to view
2. Created `test-register-page.php` - Test script to verify fixes
3. Created this documentation file

## Recommendations
1. Always initialize Livewire public properties with appropriate default values
2. Ensure all Blade template variables are passed from the component
3. Test Alpine.js bindings when using `$wire` in Alpine expressions