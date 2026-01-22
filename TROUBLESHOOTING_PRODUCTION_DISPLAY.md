# 🔧 TROUBLESHOOTING GUIDE - PRODUCTION DISPLAY ISSUES

## 🚨 QUICK DIAGNOSIS

### Step 1: Run Debug Script
1. Upload `public/debug-production.php` ke production
2. Akses: `https://yourdomain.com/debug-production.php?token=debug2024`
3. Screenshot hasilnya untuk analisis
4. **HAPUS FILE SETELAH SELESAI!**

### Step 2: Test Display
1. Akses: `https://yourdomain.com/test-display.html`
2. Check apakah:
   - ✅ Box berwarna hijau/biru/merah?
   - ✅ Button styled?
   - ✅ Font berbeda?

## 📋 CHECKLIST MASALAH UMUM

### 1️⃣ CSS TIDAK LOAD (Tampilan Polos)

#### Symptoms:
- Halaman tampil tapi tanpa styling
- Console error: "Refused to apply style from '/css/tailwindcss'"
- Console error: "Failed to load resource: /css/app.css"

#### Check:
```bash
# Via browser
https://yourdomain.com/css/app.css

# Harus return CSS, bukan 404 atau HTML
```

#### Solutions:

**A. File app.css berisi sintaks Vite:**
```css
/* SALAH - Ini sintaks Vite */
@import 'tailwindcss';
@source '../**/*.blade.php';
```

**FIX via cPanel:**
1. Edit `/public/css/app.css`
2. Replace ALL content dengan:
```css
/* PPDB MTsN 2 - Production CSS */
@import url("https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css");
@import url("https://fonts.bunny.net/css?family=figtree:300,400,500,600,700&display=swap");

:root {
    --primary: #10b981;
    --secondary: #3b82f6;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Figtree', sans-serif;
    background: #f9fafb;
}
```

**B. File tidak ada:**
```bash
# Create file via cPanel
/public/css/app.css

# Copy content dari app-production.css
```

**C. Permission issue:**
```bash
chmod 644 public/css/app.css
chmod 755 public/css/
```

### 2️⃣ JAVASCRIPT ERRORS

#### Symptoms:
- Console: "Alpine is not defined"
- Console: "countdown is not defined"
- Interactive elements tidak bekerja

#### Solutions:

**A. Create countdown function:**
```javascript
// File: /public/js/countdown-function.js
window.countdown = function(targetDate) {
    return {
        days: '00',
        hours: '00',
        minutes: '00',
        seconds: '00',
        init() {
            // countdown logic
        }
    };
};
```

**B. Load Alpine.js:**
Edit template files, add before closing `</body>`:
```html
<script defer src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.3/cdn.min.js"></script>
```

### 3️⃣ ENVIRONMENT CONFIGURATION

#### Check .env file:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Asset configuration
ASSET_MODE=mixed
USE_VITE_IN_DEV=false
```

#### Clear all caches:
```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Then rebuild
php artisan config:cache
php artisan route:cache
```

### 4️⃣ CDN BLOCKED

#### Symptoms:
- CDN resources blocked by firewall
- Slow loading from CDN

#### Solutions:

**Option 1: Switch to local assets**
```env
# .env
ASSET_MODE=local
```

**Option 2: Download Tailwind locally**
```bash
# Download ke public/css/
wget https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css -O public/css/tailwind.min.css
```

Then update app.css:
```css
@import url("/css/tailwind.min.css");
/* instead of CDN */
```

### 5️⃣ MIXED CONTENT WARNINGS

#### Symptoms:
- Console: "Mixed Content: The page was loaded over HTTPS, but requested an insecure..."

#### Solutions:
```php
// Force HTTPS in AppServiceProvider.php
public function boot()
{
    if (config('app.env') === 'production') {
        \URL::forceScheme('https');
    }
}
```

## 🔍 DIAGNOSTIC COMMANDS

### Via SSH/Terminal:
```bash
# Check file exists
ls -la public/css/app.css

# Check content
head -20 public/css/app.css

# Check for Vite syntax
grep "tailwindcss" public/css/app.css

# Check permissions
stat public/css/app.css

# Test PHP version
php -v

# Check Laravel logs
tail -100 storage/logs/laravel.log
```

### Via Browser Console:
```javascript
// Check loaded stylesheets
Array.from(document.styleSheets).forEach(sheet => {
    console.log(sheet.href || 'Inline');
});

// Check for Alpine
console.log('Alpine loaded:', typeof Alpine !== 'undefined');

// Check for Livewire
console.log('Livewire loaded:', typeof Livewire !== 'undefined');

// Check for errors
console.log('Errors:', window.console.error.toString());
```

## 🛠️ STEP-BY-STEP FIX GUIDE

### Scenario 1: Fresh Upload, Display Broken

1. **Check CSS file content:**
   ```bash
   https://yourdomain.com/css/app.css
   ```

2. **If contains @import 'tailwindcss':**
   - Go to cPanel → File Manager
   - Edit `/public/css/app.css`
   - Replace with content from `app-production.css`

3. **Clear Laravel cache:**
   ```bash
   php artisan view:clear
   php artisan cache:clear
   ```

4. **Clear browser cache:**
   - Ctrl + F5
   - Or open in Incognito

### Scenario 2: Was Working, Now Broken

1. **Check what changed:**
   - Recent uploads?
   - Config changes?
   - PHP version update?

2. **Restore from backup:**
   ```bash
   cp public/css/app.css.backup public/css/app.css
   ```

3. **Check error logs:**
   ```bash
   tail -50 storage/logs/laravel.log
   ```

### Scenario 3: Partial Styling

1. **Some styles work, some don't:**
   - CDN might be partially blocked
   - Switch to ASSET_MODE=local

2. **Mobile broken, desktop works:**
   - Check responsive meta tag
   - Clear mobile browser cache

## 📊 TESTING MATRIX

| Test | URL | Expected Result |
|------|-----|-----------------|
| CSS Load | `/css/app.css` | CSS content, not 404 |
| JS Load | `/js/app.js` | JS content |
| Test Page | `/test-display.html` | Colored boxes, styled buttons |
| Debug | `/debug-production.php?token=debug2024` | Full diagnostic report |
| Homepage | `/` | Fully styled page |

## 🚑 EMERGENCY FIXES

### Quick Fix #1: Use CDN Only
```html
<!-- Edit template files -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
```

### Quick Fix #2: Inline Critical CSS
```html
<!-- In template <head> -->
<style>
body { font-family: sans-serif; margin: 0; padding: 0; }
.container { max-width: 1200px; margin: 0 auto; padding: 1rem; }
/* Add critical styles */
</style>
```

### Quick Fix #3: Bypass Asset System
```php
// In .env
ASSET_MODE=cdn
APP_DEBUG=false
```

## 📞 FINAL RESORT

Jika semua gagal:

1. **Backup current state**
2. **Re-upload fresh copy**
3. **Use production-ready CSS only**
4. **Contact hosting support** - check if mod_rewrite enabled

## ⚠️ COMMON MISTAKES

1. ❌ **Upload file CSS development ke production**
   - File dengan @import 'tailwindcss' tidak bekerja

2. ❌ **Lupa clear cache setelah update**
   - Selalu clear view & config cache

3. ❌ **Wrong file permissions**
   - CSS files harus 644, folders 755

4. ❌ **Mixed HTTP/HTTPS**
   - Semua resources harus HTTPS di production

5. ❌ **Typo in .env**
   - Double check APP_URL dan ASSET_MODE

## 🎯 VALIDATION

Setelah fix, validasi dengan:

1. ✅ Akses `/test-display.html` - semua test passed
2. ✅ Console browser - no errors
3. ✅ Network tab - all resources 200 OK
4. ✅ Different browsers - consistent display
5. ✅ Mobile device - responsive working

---

**Remember:** Selalu backup sebelum changes dan test di staging first jika memungkinkan!