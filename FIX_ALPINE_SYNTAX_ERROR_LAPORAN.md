# FIX ALPINE.JS SYNTAX ERROR - LAPORAN PANITIA

## Error di Console

```
Uncaught SyntaxError: Unexpected token 'if'
    at new AsyncFunction (<anonymous>)
    at module.esm.js:503:19
    at Xf (module.esm.js:515:3)
    at Yf (module.esm.js:521:14)
    ...
```

**Location:** https://pmbm.mtsn2kotamalang.sch.id/panitia-psikologi/laporan

## Root Cause

### ❌ Code Lama (SALAH):

```blade
<div x-data x-init="
    // Clean URL on component init
    if (window.location.search.includes('activeTab=')) {
        const cleanUrl = window.location.origin + window.location.pathname;
        window.history.replaceState({}, document.title, cleanUrl);
    }
    // More code...
" style="display: none;"></div>
```

**Masalah:**
- Alpine.js **TIDAK** bisa menggunakan `if` statement dengan curly braces `{}` langsung di dalam `x-init`
- Alpine.js mengharapkan expression, bukan statement
- Ini menyebabkan syntax error saat Alpine.js mencoba parse directive

### ✅ Code Baru (BENAR):

```blade
<div x-data x-init="
    // Clean URL on component init - wrap in IIFE to avoid syntax error
    (() => {
        if (window.location.search.includes('activeTab=')) {
            const cleanUrl = window.location.origin + window.location.pathname;
            window.history.replaceState({}, document.title, cleanUrl);
        }
    })();

    // Also clean URL after any Livewire update
    Livewire.hook('commit', ({ component, commit, respond, succeed, fail }) => {
        succeed(({ snapshot, effect }) => {
            if (window.location.search.includes('activeTab=')) {
                const cleanUrl = window.location.origin + window.location.pathname;
                window.history.replaceState({}, document.title, cleanUrl);
            }
        });
    });
" style="display: none;"></div>
```

**Perbaikan:**
- ✅ Membungkus `if` statement dalam **IIFE** (Immediately Invoked Function Expression)
- ✅ IIFE: `(() => { ... })()` langsung dijalankan dan valid di Alpine.js
- ✅ Tidak mengubah fungsi, hanya memperbaiki syntax

## Mengapa Ini Berisiko?

### 🔴 HIGH RISK - Harus Diperbaiki

**1. User Experience:**
- Error di console membingungkan user yang membuka DevTools
- Menunjukkan aplikasi tidak profesional
- Bisa membuat user khawatir ada masalah keamanan

**2. Functionality:**
- Meskipun error, code mungkin tetap berjalan karena JavaScript engine mencoba recover
- TAPI tidak ada jaminan code berjalan dengan benar
- Bisa menyebabkan behavior yang tidak konsisten

**3. Performance:**
- Alpine.js harus retry parse berkali-kali
- Menggunakan extra memory dan CPU
- Memperlambat rendering halaman

**4. Debugging:**
- Error ini bisa menutupi error lain yang lebih penting
- Developer sulit debug karena console penuh dengan error
- Stack trace error bisa misleading

**5. Future Compatibility:**
- Alpine.js versi baru mungkin lebih strict
- Code yang error bisa completely break di update berikutnya

## File yang Diperbaiki

**File:** `resources/views/livewire/panitia/laporan.blade.php`

**Lines:** 4-22

**Changes:**
```diff
  @if($isPanitiaDaftarUlang || $isPanitiaPsikologi)
  <!-- Clean URL for role-specific pages (remove activeTab parameter) -->
  <div x-data x-init="
-     // Clean URL on component init
-     if (window.location.search.includes('activeTab=')) {
-         const cleanUrl = window.location.origin + window.location.pathname;
-         window.history.replaceState({}, document.title, cleanUrl);
-     }
+     // Clean URL on component init - wrap in IIFE to avoid syntax error
+     (() => {
+         if (window.location.search.includes('activeTab=')) {
+             const cleanUrl = window.location.origin + window.location.pathname;
+             window.history.replaceState({}, document.title, cleanUrl);
+         }
+     })();
+
      // Also clean URL after any Livewire update
      Livewire.hook('commit', ({ component, commit, respond, succeed, fail }) => {
          succeed(({ snapshot, effect }) => {
              if (window.location.search.includes('activeTab=')) {
                  const cleanUrl = window.location.origin + window.location.pathname;
                  window.history.replaceState({}, document.title, cleanUrl);
              }
          });
      });
  " style="display: none;"></div>
  @endif
```

## Cara Upload ke Production

### Opsi 1: Menggunakan Script (RECOMMENDED)

```batch
cd C:\ppdbmtsn2km-laravel
upload_fix_alpine_laporan.bat
```

### Opsi 2: Manual Upload

**1. Upload file:**
```
File lokal:
C:\ppdbmtsn2km-laravel\resources\views\livewire\panitia\laporan.blade.php

Upload ke:
/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/panitia/laporan.blade.php
```

**2. Clear cache:**
```bash
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
php artisan view:clear
php artisan cache:clear
```

## Testing Setelah Fix

### 1. Check Console Errors

1. Login sebagai panitia tes psikologi
2. Buka: https://pmbm.mtsn2kotamalang.sch.id/panitia-psikologi/laporan
3. Buka DevTools (F12) → Tab Console
4. **Error "Unexpected token 'if'" seharusnya HILANG**
5. Hanya muncul: "Countdown and init functions loaded successfully"

### 2. Check Functionality

1. URL activeTab cleanup seharusnya tetap berfungsi
2. Jika ada query parameter `?activeTab=xxx`, akan otomatis dihapus
3. URL menjadi clean: `/panitia-psikologi/laporan`

## Alpine.js Best Practices

### ❌ JANGAN:

```javascript
// SALAH - Statement di x-init
x-init="if (condition) { doSomething(); }"

// SALAH - Multiple statements tanpa wrapper
x-init="statement1; statement2; if (x) { y(); }"

// SALAH - Try-catch tanpa wrapper
x-init="try { something(); } catch (e) { error(); }"
```

### ✅ LAKUKAN:

```javascript
// BENAR - IIFE untuk statements
x-init="(() => { if (condition) { doSomething(); } })()"

// BENAR - Arrow function
x-init="$nextTick(() => { if (condition) { doSomething(); } })"

// BENAR - Ternary untuk simple conditions
x-init="condition ? doSomething() : doOtherThing()"

// BENAR - Function call
x-init="initComponent()"
```

## Alpine.js Syntax Rules

**x-init accepts:**
- ✅ Function calls: `myFunction()`
- ✅ Expressions: `count = 5`
- ✅ IIFE: `(() => { code })()`
- ✅ Arrow functions: `() => { code }`
- ✅ Ternary: `condition ? a : b`
- ❌ If statements: `if (x) { y }`
- ❌ For loops: `for (let i...) { }`
- ❌ Try-catch: `try { } catch { }`

## Summary

| Aspect | Before | After |
|--------|--------|-------|
| Console Error | ❌ Yes | ✅ No |
| Syntax Valid | ❌ No | ✅ Yes |
| Performance | ❌ Degraded | ✅ Normal |
| Maintainability | ❌ Confusing | ✅ Clear |
| Future-proof | ❌ May break | ✅ Stable |

## Related Files

```
resources/views/livewire/panitia/laporan.blade.php  ← FIXED
resources/views/livewire/admin/laporan.blade.php    ← Check if same issue
resources/views/livewire/panitia-seragam/laporan.blade.php ← Check if same issue
```

## Checklist

- [x] Identify error location
- [x] Analyze root cause
- [x] Apply IIFE wrapper fix
- [x] Test locally
- [x] Create upload script
- [ ] Upload to production
- [ ] Clear cache
- [ ] Verify error is gone
- [ ] Test functionality still works
- [ ] Check other similar files

## Prevention

**Code Review Checklist untuk Alpine.js:**
1. ✅ Tidak ada `if/for/try` langsung di `x-init`
2. ✅ Gunakan IIFE atau arrow function untuk complex logic
3. ✅ Test di browser console sebelum deploy
4. ✅ Check Alpine.js version compatibility
5. ✅ Use Alpine DevTools untuk debugging

## References

- Alpine.js Documentation: https://alpinejs.dev/directives/init
- IIFE Pattern: https://developer.mozilla.org/en-US/docs/Glossary/IIFE
- Alpine.js v3 Migration: https://alpinejs.dev/upgrade-guide
