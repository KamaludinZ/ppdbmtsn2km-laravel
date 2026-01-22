# Quick Fix Guide - Dropdown & Edit Profil

## 🚀 Quick Deploy

### 1. Upload File Berikut:
```
resources/views/components/layouts/dashboard.blade.php
resources/views/livewire/edit-profil.blade.php
app/Livewire/EditProfil.php
```

### 2. Clear Cache di Server:
```bash
php artisan view:clear
php artisan cache:clear
```

### 3. Test Langsung:
Buka: `test-dropdown-edit-profil.html` di browser untuk guided testing

---

## 🔍 Quick Troubleshooting

### Dropdown Tidak Muncul

**Console Test:**
```javascript
// Buka browser console (F12), ketik:
window.Alpine
// Expected: Object { ... }
```

**Fix:**
- Clear browser cache: `Ctrl + Shift + R`
- Periksa error di console
- Test di incognito mode

### Modal Tidak Muncul

**Console Test:**
```javascript
// Test Livewire:
window.Livewire
// Expected: Object { ... }

// Test manual trigger:
window.dispatchLivewireEvent('openModal')
// Expected: Modal muncul
```

**Fix:**
- Periksa Livewire version: `window.Livewire.version`
- Clear Laravel cache: `php artisan cache:clear`
- Reload halaman

### Form Submit Tidak Bekerja

**Fix:**
```bash
# Clear session:
php artisan session:flush

# Clear view cache:
php artisan view:clear

# Regenerate config:
php artisan config:clear
```

---

## 🧪 Quick Test (1 Menit)

### Test Dropdown:
1. ✅ Klik avatar → Dropdown muncul
2. ✅ Klik di luar → Dropdown tertutup

### Test Modal:
1. ✅ Klik "Edit Profil" → Modal muncul
2. ✅ Tekan ESC → Modal tertutup
3. ✅ Ubah nama → Submit → Sukses

### Test Console (F12):
```javascript
window.Livewire  // ✅ Should be Object
window.Alpine    // ✅ Should be Object
```

---

## 📱 Browser Compatibility

| Browser | Status |
|---------|--------|
| Chrome | ✅ Supported |
| Firefox | ✅ Supported |
| Safari | ✅ Supported |
| Edge | ✅ Supported |
| Mobile | ✅ Supported |

---

## 🔧 Advanced Debugging

### Check Z-Index Hierarchy:
```javascript
// Find overlapping elements:
document.querySelectorAll('[style*="z-index"]').forEach(el => {
    console.log(el.tagName, window.getComputedStyle(el).zIndex);
});
```

### Force Open Modal:
```javascript
// If button doesn't work, force open:
Livewire.dispatch('openModal')
// or for v2:
Livewire.emit('openModal')
```

### Check Alpine Data:
```javascript
// Check dropdown state:
Alpine.$data(document.querySelector('[x-data*="profilOpen"]'))
// Expected: { profilOpen: false/true }
```

---

## 📞 Quick Support Commands

### Check Livewire Status:
```bash
php artisan about
```

### Clear All Caches:
```bash
php artisan optimize:clear
```

### Restart Services (if needed):
```bash
# PHP-FPM:
sudo systemctl restart php8.2-fpm

# Nginx:
sudo systemctl restart nginx
```

---

## ✅ Success Indicators

- ✅ Dropdown muncul saat klik avatar
- ✅ Modal muncul saat klik "Edit Profil"
- ✅ Loading spinner muncul saat submit
- ✅ Pesan sukses muncul setelah submit
- ✅ Modal tertutup dengan ESC / klik luar
- ✅ No console errors
- ✅ Bekerja di semua role (Admin, Panitia, Pendaftar)

---

## 📝 Quick Notes

**Z-Index Levels:**
- Modal: `z-[100000]` ← Highest
- Overlay: `z-[99999]`
- Dropdown: `z-[9999]`

**Event Methods:**
- Livewire v3: `Livewire.dispatch()`
- Livewire v2: `Livewire.emit()`
- Fallback: Custom events

**Loading States:**
- Button disabled during submit
- Spinner animation shows
- Text changes to "Menyimpan..."

---

**Status:** ✅ Production Ready
**Date:** 21 Jan 2026
**Support:** Check `FIX_DROPDOWN_EDIT_PROFIL_SUMMARY.md` for details
