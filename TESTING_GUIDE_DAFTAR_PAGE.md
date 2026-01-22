# Testing Guide: Halaman /daftar

**Date:** 2026-01-19
**Purpose:** Manual testing checklist for all fixes on registration page

---

## 🚀 Quick Start

### 1. Clear Cache
```bash
php artisan optimize:clear
```

### 2. Run Automated Test (Optional)
```bash
php test_kuota_jalur.php
```
Expected output: All 5 jalur available, no failures

### 3. Open Browser
Navigate to: `http://localhost/daftar`

---

## ✅ Test Checklist

### Test 1: Initial Page Load

**Steps:**
1. Open `/daftar`
2. Wait for page to fully load

**Expected Results:**
- [ ] Page loads without errors
- [ ] All jalur cards displayed
- [ ] Badges show correct values
- [ ] No JavaScript errors in console (F12)

---

### Test 2: Badge Display Accuracy

**Steps:**
1. Note the badge value for each jalur (e.g., "199/200")
2. Take a screenshot or write down the values

**Expected Badge Displays:**

**Jalur Prestasi Akademik (kuota=0):**
- [ ] Badge: 🟢 "Kuota Tidak Terbatas" (green background)

**Jalur Reguler (kuota=200, pendaftar=1):**
- [ ] Badge: 🔵 "Kuota: 199/200" (blue background)

**Jalur Tahfidz (kuota=50, pendaftar=1):**
- [ ] Badge: 🔵 "Kuota: 49/50" (blue background)

**Jalur Prestasi Non-Akademik (kuota=40, pendaftar=0):**
- [ ] Badge: 🔵 "Kuota: 40/40" (blue background)

**Jalur Afirmasi (kuota=30, pendaftar=0):**
- [ ] Badge: 🔵 "Kuota: 30/30" (blue background)

---

### Test 3: Selecting Unlimited Quota Jalur

**Steps:**
1. Click on "Jalur Prestasi Akademik" (unlimited quota)

**Expected Results:**
- [ ] No error message appears
- [ ] Card border turns **green**
- [ ] Green checkmark appears in top-right corner
- [ ] Badge stays "Kuota Tidak Terbatas"
- [ ] "Lanjutkan" button becomes **enabled** (bright green)

**CRITICAL CHECK:**
- [ ] **All other jalur badges REMAIN UNCHANGED** (no "0/40" corruption!)

---

### Test 4: Selecting Limited Quota Jalur

**Steps:**
1. Refresh page (`Ctrl+R` or `F5`)
2. Click on "Jalur Reguler" (limited quota)

**Expected Results:**
- [ ] No error message appears
- [ ] Card border turns green
- [ ] Green checkmark appears
- [ ] Badge stays "Kuota: 199/200" (NOT "0/200")
- [ ] "Lanjutkan" button enabled

**CRITICAL CHECK:**
- [ ] **All other jalur badges REMAIN UNCHANGED**
- [ ] "Jalur Tahfidz" still shows "49/50" (not "0/50")
- [ ] "Jalur Prestasi Akademik" still shows "Unlimited" (not "0")

---

### Test 5: Button Loading State - "Lanjutkan"

**Steps:**
1. Select any jalur
2. Click "Lanjutkan" button
3. **Observe the button during loading**

**Expected Results:**
- [ ] Button text changes to: **"⟳ Loading..."** (spinner + text)
- [ ] **NO text overlap** (should NOT show "Lanjutkan > Loading...")
- [ ] Button is disabled during loading
- [ ] Layout stays centered and clean
- [ ] After loading: Advances to Step 2

**What you should SEE:**
```
Before click: [ Lanjutkan > ]
During load:  [ ⟳ Loading... ]  ← Only this, NOT both!
After load:   Step 2 displayed
```

**What you should NOT see:**
```
❌ [ Lanjutkan > ⟳ Loading... ]  ← Wrong! Both texts visible
```

---

### Test 6: Complete Step 2 (Input Nilai)

**Steps:**
1. At Step 2, enter nilai rapor
2. Example: Matematika=85, IPA=90, Bahasa Indonesia=88
3. Click "Lanjutkan" again

**Expected Results:**
- [ ] Button shows clean "Loading..." state (no overlap)
- [ ] Advances to Step 3
- [ ] All previous jalur badges still correct if you go back to Step 1

---

### Test 7: Complete Step 3 (Data Diri)

**Steps:**
1. Fill all required fields in Step 3
2. Click "Daftar Sekarang" button

**Expected Results:**
- [ ] Button shows: "⟳ Loading..." (clean, no overlap)
- [ ] Modal appears: "Konfirmasi Pendaftaran"

---

### Test 8: Modal Button Loading State

**Steps:**
1. In the modal, click "Ya, Daftar Sekarang"
2. **Observe the button during loading**

**Expected Results:**
- [ ] Button text changes to: "⟳ Loading..." (clean)
- [ ] NO text overlap
- [ ] Button disabled during loading
- [ ] After success: Redirect or success message

---

### Test 9: Badge Stability (CRITICAL!)

**This is the MOST IMPORTANT test!**

**Steps:**
1. Refresh page to start fresh
2. **BEFORE selecting:** Write down all badge values
3. Select "Jalur Reguler"
4. **Check ALL badges again:**

**Expected Results:**

**Selected Jalur (Reguler):**
- [x] Badge: "Kuota: 199/200" ✅ (correct)

**Non-Selected Jalur (Others):**
- [ ] Prestasi Akademik: "Kuota Tidak Terbatas" ✅ (unchanged)
- [ ] Tahfidz: "Kuota: 49/50" ✅ (unchanged)
- [ ] Prestasi Non-Akademik: "Kuota: 40/40" ✅ (unchanged)
- [ ] Afirmasi: "Kuota: 30/30" ✅ (unchanged)

**FAIL if you see:**
- ❌ Any badge showing "0/40" or "0/50"
- ❌ Any badge changing when NOT selected

---

### Test 10: Browser Console (No Errors)

**Steps:**
1. Open Developer Tools (`F12`)
2. Go to Console tab
3. Perform all above tests
4. Check Network tab

**Expected Results:**
- [ ] No JavaScript errors in Console
- [ ] No Livewire errors
- [ ] Network XHR requests: All status 200 (success)
- [ ] No 500 errors
- [ ] No SQL errors

---

### Test 11: Multiple Selections

**Steps:**
1. Select "Jalur Reguler"
2. Check all badges (should be correct)
3. Select "Jalur Tahfidz" (change selection)
4. Check all badges again

**Expected Results:**
- [ ] Previous selection border turns gray
- [ ] New selection border turns green
- [ ] **ALL badges remain accurate** (no corruption)

---

### Test 12: Complete Registration Flow

**Full end-to-end test:**

**Step 1:**
1. [ ] Select jalur → Badge stays correct
2. [ ] Other badges stay correct
3. [ ] Click "Lanjutkan" → Clean loading state
4. [ ] Advances to Step 2

**Step 2:**
1. [ ] Enter nilai rapor
2. [ ] Click "Lanjutkan" → Clean loading state
3. [ ] Advances to Step 3

**Step 3:**
1. [ ] Fill all data diri fields
2. [ ] Click "Daftar Sekarang" → Clean loading state
3. [ ] Modal appears
4. [ ] Click "Ya, Daftar Sekarang" → Clean loading state
5. [ ] Registration succeeds

**After Registration:**
1. [ ] Check database: New pendaftar created
2. [ ] Check jalur: `pendaftar_count` increased by 1
3. [ ] Reload `/daftar`: Badge shows reduced quota (e.g., "198/200")

---

## 🐛 Common Issues to Watch For

### Issue: Badge Shows "0/40"
**Symptom:** Non-selected jalur badges display "0/40" or "0/50"
**Expected:** All badges should remain stable
**If this happens:** Fix NOT applied correctly, check blade view lines 108-118

### Issue: Button Shows Both Texts
**Symptom:** "Lanjutkan > Loading..." visible together
**Expected:** Only one text at a time
**If this happens:** Fix NOT applied correctly, check button classes

### Issue: Unlimited Quota Shows Error
**Symptom:** "Kuota Penuh" error on jalur with kuota=0
**Expected:** Should always be selectable
**If this happens:** Check RegisterFixed.php lines 178-257

### Issue: Available Quota Shows Error
**Symptom:** Error despite badge showing "199/200"
**Expected:** Should be selectable
**If this happens:** Check real-time query in selectJalur()

---

## ✅ Success Criteria

All fixes are working correctly if:

1. ✅ All badges display accurate quota at all times
2. ✅ Selected jalur badge stays correct
3. ✅ **Non-selected jalur badges stay correct** (NO "0/40")
4. ✅ Unlimited quota (kuota=0) always selectable
5. ✅ Available quota jalur can be selected
6. ✅ Full quota jalur shows proper error
7. ✅ All button loading states are clean (no text overlap)
8. ✅ "Loading..." text appears (not "Processing...")
9. ✅ No JavaScript errors in console
10. ✅ Complete 3-step registration works

---

## 📊 Test Results Template

Copy this template and fill in your test results:

```
=== TESTING RESULTS ===
Date: _______________
Tester: _______________

Test 1: Initial Page Load           [ PASS / FAIL ]
Test 2: Badge Display Accuracy      [ PASS / FAIL ]
Test 3: Unlimited Quota Jalur       [ PASS / FAIL ]
Test 4: Limited Quota Jalur         [ PASS / FAIL ]
Test 5: Button "Lanjutkan" Loading  [ PASS / FAIL ]
Test 6: Complete Step 2             [ PASS / FAIL ]
Test 7: Complete Step 3             [ PASS / FAIL ]
Test 8: Modal Button Loading        [ PASS / FAIL ]
Test 9: Badge Stability (CRITICAL)  [ PASS / FAIL ]
Test 10: Browser Console            [ PASS / FAIL ]
Test 11: Multiple Selections        [ PASS / FAIL ]
Test 12: Complete Registration      [ PASS / FAIL ]

Overall Status: [ PASS / FAIL ]

Notes:
____________________________________
____________________________________
____________________________________
```

---

## 🔧 Troubleshooting

### If tests fail:

1. **Clear cache again:**
   ```bash
   php artisan optimize:clear
   ```

2. **Check file modifications:**
   - `app/Livewire/Auth/RegisterFixed.php` (lines 178-257)
   - `resources/views/livewire/auth/register-fixed.blade.php` (lines 108-118, 792-881)

3. **Check browser cache:**
   - Hard refresh: `Ctrl+Shift+R` (Windows) or `Cmd+Shift+R` (Mac)
   - Or clear browser cache

4. **Check logs:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

5. **Run automated test:**
   ```bash
   php test_kuota_jalur.php
   ```

---

## 📚 Related Documentation

- `FIX_COMPLETE_DAFTAR_PAGE_SUMMARY.md` - Complete fix summary
- `FIX_KUOTA_JALUR_SUMMARY.md` - Quota validation fix details
- `FIX_BADGE_DAN_BUTTON_SUMMARY.md` - UI fixes details

---

**Last Updated:** 2026-01-19
**Status:** Ready for Testing
