# Quick Test Guide - Blade View Fix (9 Feb 2026)

## ✅ What Was Fixed

**Problem:** Individual toggles in Admin worked, but "Opsi Lainnya" wasn't appearing in Pendaftar views.

**Root Cause:** Blade view was using direct database queries instead of component properties.

**Solution:** Changed blade view to use computed properties that check Global OR Individual settings.

---

## 🧪 Quick Test Steps

### Test 1: Madrasah Tab

1. **Admin Side:**
   - Go to: `http://localhost/admin/daftar-ulang?activeTab=madrasah`
   - Find any pendaftar in the table
   - Click the **blue toggle** "Dropdown Opsi Lainnya" → Turn it ON
   - Verify: Toggle turns blue/emerald, success notification appears

2. **Pendaftar Side:**
   - Login as that pendaftar
   - Go to: `http://localhost/pendaftar/daftar-ulang?activeTab=madrasah`
   - Scroll to "Pilih Paket Daftar Ulang Madrasah" dropdown
   - **✅ Check:** "Opsi Lainnya" should NOW appear in the dropdown
   - Select "Opsi Lainnya" and enter a custom amount (e.g., 500000)
   - Verify: Can proceed to next step

### Test 2: Seragam Tab

1. **Admin Side:**
   - Go to: `http://localhost/admin/daftar-ulang?activeTab=seragam`
   - Find the same pendaftar
   - Click the **emerald toggle** "Dropdown Opsi Lainnya" → Turn it ON

2. **Pendaftar Side:**
   - Still logged in as that pendaftar
   - Go to: `http://localhost/pendaftar/daftar-ulang?activeTab=seragam`
   - Scroll to "Pilih Paket Daftar Ulang Seragam" dropdown
   - **✅ Check:** "Opsi Lainnya" should NOW appear
   - Select and test custom amount

### Test 3: Verify Other Pendaftar Don't See It

1. Login as a DIFFERENT pendaftar (one whose toggle is OFF)
2. Go to Madrasah tab
3. **✅ Check:** "Opsi Lainnya" should NOT appear in dropdown
4. This confirms individual toggles are working correctly

---

## ⚠️ Important Notes

### Ukuran Seragam Tab

**You mentioned:** "begitu juga di tab ukuran seragam"

**Clarification:**
- **Ukuran tab does NOT have individual toggles**
- Only Madrasah and Seragam have individual toggles
- Ukuran ONLY has global settings (applies to all pendaftar)

**To enable "Opsi Lainnya" for Ukuran:**
- Must use global settings in Admin panel
- Cannot enable for specific pendaftar
- Will appear for ALL pendaftar if global setting is ON

**Why?**
The database migration only added individual columns to `daftar_ulang` and `seragam` tables, not to ukuran fields. This is by design.

---

## 📊 Feature Coverage

| Feature | Individual Toggle | Global Setting | Status |
|---------|-------------------|----------------|--------|
| Madrasah Dropdown | ✅ Yes | ✅ Yes | ✅ FIXED |
| Madrasah Konfirmasi | ✅ Yes | ✅ Yes | ✅ FIXED |
| Seragam Dropdown | ✅ Yes | ✅ Yes | ✅ FIXED |
| Seragam Konfirmasi | ✅ Yes | ✅ Yes | ✅ FIXED |
| Ukuran Jas | ❌ No | ✅ Yes | ⚠️ Global Only |
| Ukuran Baju Olahraga | ❌ No | ✅ Yes | ⚠️ Global Only |
| Ukuran Celana Olahraga | ❌ No | ✅ Yes | ⚠️ Global Only |
| Ukuran Baju Harian | ❌ No | ✅ Yes | ⚠️ Global Only |
| Ukuran Celana/Rok Harian | ❌ No | ✅ Yes | ⚠️ Global Only |
| Ukuran Kopyah | ❌ No | ✅ Yes | ⚠️ Global Only |

---

## 🔍 What Changed in Code

### File: `resources/views/livewire/pendaftar/daftar-ulang.blade.php`

**4 Locations Changed:**

1. **Line 756** - Madrasah dropdown
2. **Line 859** - Madrasah konfirmasi
3. **Line 1618** - Seragam dropdown
4. **Line 1720** - Seragam konfirmasi

**Pattern:**

❌ **BEFORE (Wrong):**
```php
$madrasahPackagesEnableOpsiLainnya = \App\Models\Setting::get('daftar_ulang_madrasah_packages_enable_opsi_lainnya', false);
```

✅ **AFTER (Correct):**
```php
$madrasahPackagesEnableOpsiLainnya = $this->shouldShowMadrasahOpsiLainnyaDropdown;
```

---

## 🚀 Ready to Upload to Production

Once you confirm the fix works locally, you can upload to production using:

```bash
.\upload_fitur_opsi_individual_COMPLETE_9_feb_2026.bat
```

**Note:** The upload script needs to be updated to include the blade view file. I'll create an updated version.

---

## ✅ Expected Results After Fix

### Scenario 1: Individual Toggle ON (Global OFF)

- **Admin:** Enable individual toggle for Pendaftar A
- **Admin:** Leave global setting OFF
- **Result:**
  - ✅ Pendaftar A sees "Opsi Lainnya"
  - ❌ Other pendaftar do NOT see "Opsi Lainnya"

### Scenario 2: Global ON (Individual Doesn't Matter)

- **Admin:** Enable global setting
- **Result:**
  - ✅ ALL pendaftar see "Opsi Lainnya"
  - Individual toggles are ignored

### Scenario 3: Both OFF

- **Admin:** Global setting OFF, individual toggle OFF
- **Result:**
  - ❌ Pendaftar does NOT see "Opsi Lainnya"

---

## 🐛 Troubleshooting

### Problem: Still not seeing "Opsi Lainnya" after toggle

**Solution 1:** Hard refresh browser
```
Ctrl + Shift + F5 (Windows)
Cmd + Shift + R (Mac)
```

**Solution 2:** Clear browser cache completely

**Solution 3:** Check browser console for errors (F12)

**Solution 4:** Verify toggle saved to database:
```bash
php artisan tinker
$pendaftar = App\Models\Pendaftar::find(1);
$pendaftar->daftarUlang->opsi_lainnya_dropdown_individual; // Should be true
```

---

## 📝 Summary

**What you should see NOW:**
1. Enable individual toggle in Admin → ✅ Works
2. Login as that pendaftar → ✅ Sees "Opsi Lainnya"
3. Login as different pendaftar → ✅ Does NOT see it
4. Enable global setting → ✅ All pendaftar see it

**What was broken BEFORE:**
- Steps 1-4 above didn't work because view was ignoring individual toggles

**What was fixed:**
- Blade view now uses component properties that check Global OR Individual

---

**Test and confirm it works, then we can deploy to production!**

