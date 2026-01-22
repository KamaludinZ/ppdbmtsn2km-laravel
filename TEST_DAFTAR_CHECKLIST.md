# Test Checklist: Halaman /daftar

**Date:** 2026-01-19
**Status:** Ready for Testing ✅

---

## Pre-Testing

- [x] Cache cleared: `php artisan optimize:clear`
- [x] MultipleRootElementsDetectedException fixed
- [x] Component tests passed
- [x] View structure validated
- [x] 5 active jalur pendaftaran available

---

## 🧪 Test Scenario 1: Basic Page Load

**URL:** `http://localhost/daftar`

| # | Test | Expected Result | Status |
|---|------|-----------------|--------|
| 1.1 | Open /daftar | Page loads without errors | ⬜ |
| 1.2 | Check header | "Pendaftaran PPDB" visible | ⬜ |
| 1.3 | Check progress | Step 1 of 3 highlighted | ⬜ |
| 1.4 | Check jalur cards | 5 jalur cards displayed | ⬜ |
| 1.5 | Check browser console (F12) | No red errors | ⬜ |
| 1.6 | Check Livewire loaded | Type `typeof Livewire` → returns "object" | ⬜ |

**If any test fails, check:** `storage/logs/laravel.log`

---

## 🧪 Test Scenario 2: Jalur Selection (Step 1)

| # | Test | Expected Result | Status |
|---|------|-----------------|--------|
| 2.1 | Click any jalur card | Border turns green | ⬜ |
| 2.2 | Check checkmark | Green ✓ appears in top-right | ⬜ |
| 2.3 | Check background | Card has green background | ⬜ |
| 2.4 | Check button | "Lanjutkan" button turns green | ⬜ |
| 2.5 | Check button enabled | Button is clickable | ⬜ |
| 2.6 | Click another jalur | Previous selection deselected | ⬜ |
| 2.7 | Check Network tab | XHR request to /livewire/message | ⬜ |
| 2.8 | Check response | Status 200, no errors | ⬜ |

**Visual Checks:**
- [ ] Kuota information displayed (e.g., "Kuota: 25/50")
- [ ] Nilai minimum shown (e.g., "Min: 80")
- [ ] Hover effect works on cards
- [ ] Loading state shows when clicking

---

## 🧪 Test Scenario 3: Navigate to Step 2

| # | Test | Expected Result | Status |
|---|------|-----------------|--------|
| 3.1 | Click "Lanjutkan" | Loading indicator appears | ⬜ |
| 3.2 | Wait for transition | Smooth transition to Step 2 | ⬜ |
| 3.3 | Check header | "Input Nilai Rapor" visible | ⬜ |
| 3.4 | Check progress | Step 2 highlighted | ⬜ |
| 3.5 | Check form | 9 input fields visible (3×3) | ⬜ |
| 3.6 | Check average display | Shows "0.00" initially | ⬜ |
| 3.7 | Check "Kembali" button | Visible and enabled | ⬜ |

---

## 🧪 Test Scenario 4: Input Nilai (Step 2)

**Test Data:** Enter 85 in all fields

| # | Test | Expected Result | Status |
|---|------|-----------------|--------|
| 4.1 | Enter nilai_b_indo_5_1 = 85 | Field accepts input | ⬜ |
| 4.2 | Check average | Updates to 85.00 | ⬜ |
| 4.3 | Check validation | Green indicator if >= minimum | ⬜ |
| 4.4 | Fill all 9 fields with 85 | All fields accept values | ⬜ |
| 4.5 | Check final average | Shows 85.00 | ⬜ |
| 4.6 | Check validation message | "Nilai Anda memenuhi syarat" | ⬜ |
| 4.7 | Upload rapor PDF | File upload works | ⬜ |
| 4.8 | Check progress bar | Shows during upload | ⬜ |
| 4.9 | Check file preview | Shows filename and size | ⬜ |
| 4.10 | Check "Lanjutkan" button | Becomes enabled and green | ⬜ |

**Validation Tests:**
- [ ] Enter grade < 0 → Shows error
- [ ] Enter grade > 100 → Shows error
- [ ] Leave field empty → Button stays disabled
- [ ] Enter grade below minimum → Yellow warning

---

## 🧪 Test Scenario 5: Navigate to Step 3

| # | Test | Expected Result | Status |
|---|------|-----------------|--------|
| 5.1 | Click "Lanjutkan" | Loading indicator appears | ⬜ |
| 5.2 | Wait for transition | Smooth transition to Step 3 | ⬜ |
| 5.3 | Check header | "Data Diri" visible | ⬜ |
| 5.4 | Check progress | Step 3 highlighted | ⬜ |
| 5.5 | Check sections | 3 sections visible | ⬜ |
| 5.6 | Section 1 | "Data Pribadi" present | ⬜ |
| 5.7 | Section 2 | "Data Sekolah Asal" present | ⬜ |
| 5.8 | Section 3 | "Data Akun Login" present | ⬜ |

---

## 🧪 Test Scenario 6: Fill Data Pribadi

**Test Data:**
- NISN: 1234567890
- Nama: Ahmad Rizki
- Tempat Lahir: Malang
- Tanggal Lahir: 2010-01-15
- Jenis Kelamin: Laki-laki
- Agama: Islam
- Alamat: Jl. Veteran No. 123
- Telepon: 081234567890

| # | Test | Expected Result | Status |
|---|------|-----------------|--------|
| 6.1 | Enter NISN | Accepts 10 digits | ⬜ |
| 6.2 | Enter Nama Lengkap | Accepts text | ⬜ |
| 6.3 | Enter Tempat Lahir | Accepts text | ⬜ |
| 6.4 | Select Tanggal Lahir | Date picker works | ⬜ |
| 6.5 | Select Jenis Kelamin | Dropdown works | ⬜ |
| 6.6 | Select Agama | Dropdown works | ⬜ |
| 6.7 | Enter Alamat | Textarea accepts text | ⬜ |
| 6.8 | Enter No. Telepon | Accepts phone number | ⬜ |

---

## 🧪 Test Scenario 7: Fill Data Sekolah

**Test Data:**
- Jenis: Madrasah
- NSM: 123456789012
- Nama: MI Negeri 1 Malang

| # | Test | Expected Result | Status |
|---|------|-----------------|--------|
| 7.1 | Select "Madrasah (MI)" | NSM field appears | ⬜ |
| 7.2 | Enter NSM | Accepts 12 digits | ⬜ |
| 7.3 | Enter Nama Sekolah | Accepts text | ⬜ |
| 7.4 | Change to "Sekolah Umum" | NSM hides, NPSN appears | ⬜ |
| 7.5 | Enter NPSN | Accepts 8 digits | ⬜ |

---

## 🧪 Test Scenario 8: Fill Data Akun

**Test Data:**
- Email: test@example.com
- Password: SecurePass123!
- Confirm: SecurePass123!

| # | Test | Expected Result | Status |
|---|------|-----------------|--------|
| 8.1 | Enter email | Accepts valid email | ⬜ |
| 8.2 | Enter password (weak) | Strength bar shows red | ⬜ |
| 8.3 | Enter password (strong) | Strength bar shows green | ⬜ |
| 8.4 | Password min 8 chars | Shows "Sangat Kuat" | ⬜ |
| 8.5 | Confirm password match | No error | ⬜ |
| 8.6 | Confirm password mismatch | Shows error | ⬜ |
| 8.7 | Check button | "Daftar Sekarang" enabled | ⬜ |

---

## 🧪 Test Scenario 9: Submit Registration

| # | Test | Expected Result | Status |
|---|------|-----------------|--------|
| 9.1 | Click "Daftar Sekarang" | Confirmation modal appears | ⬜ |
| 9.2 | Check modal content | Shows warning message | ⬜ |
| 9.3 | Click "Periksa Kembali" | Modal closes | ⬜ |
| 9.4 | Click "Daftar Sekarang" again | Modal reappears | ⬜ |
| 9.5 | Click "Ya, Daftar Sekarang" | Shows processing state | ⬜ |
| 9.6 | Wait for completion | Success or redirect | ⬜ |
| 9.7 | Check database | New pendaftar created | ⬜ |
| 9.8 | Check user table | New user created | ⬜ |

---

## 🧪 Test Scenario 10: Navigation Tests

| # | Test | Expected Result | Status |
|---|------|-----------------|--------|
| 10.1 | Step 2 → "Kembali" | Returns to Step 1 | ⬜ |
| 10.2 | Check jalur selection | Still selected | ⬜ |
| 10.3 | Step 3 → "Kembali" | Returns to Step 2 | ⬜ |
| 10.4 | Check nilai fields | Values preserved | ⬜ |
| 10.5 | Forward again to Step 3 | Data preserved | ⬜ |

---

## 🧪 Test Scenario 11: Validation Tests

| # | Test | Expected Result | Status |
|---|------|-----------------|--------|
| 11.1 | Step 1: No jalur selected | Button disabled | ⬜ |
| 11.2 | Step 2: Empty nilai | Button disabled | ⬜ |
| 11.3 | Step 2: Nilai < minimum | Warning shown | ⬜ |
| 11.4 | Step 3: Empty NISN | Shows error on submit | ⬜ |
| 11.5 | Step 3: Invalid email | Shows error | ⬜ |
| 11.6 | Step 3: Password mismatch | Shows error | ⬜ |
| 11.7 | Step 3: NISN exists | Shows error | ⬜ |

---

## 🧪 Test Scenario 12: Performance Tests

| # | Test | Expected Result | Status |
|---|------|-----------------|--------|
| 12.1 | Initial page load | < 500ms | ⬜ |
| 12.2 | Jalur selection | < 50ms response | ⬜ |
| 12.3 | Step navigation | < 100ms | ⬜ |
| 12.4 | Nilai calculation | Real-time (< 50ms) | ⬜ |
| 12.5 | Network requests | Max 2 DB queries | ⬜ |

**Check Network Tab:**
- [ ] Initial load: 1-2 requests
- [ ] Jalur click: 1 XHR request
- [ ] Step change: 1 XHR request
- [ ] No repeated/duplicate requests

---

## 🧪 Test Scenario 13: Browser Compatibility

Test on multiple browsers:

| Browser | Version | Status | Notes |
|---------|---------|--------|-------|
| Chrome | Latest | ⬜ | |
| Firefox | Latest | ⬜ | |
| Edge | Latest | ⬜ | |
| Safari | Latest | ⬜ | |

---

## 🧪 Test Scenario 14: Mobile Responsive

| # | Test | Expected Result | Status |
|---|------|-----------------|--------|
| 14.1 | Open on mobile viewport | Layout adapts | ⬜ |
| 14.2 | Check jalur cards | Stack vertically | ⬜ |
| 14.3 | Check form fields | Full width | ⬜ |
| 14.4 | Touch interactions | Work properly | ⬜ |

---

## 📊 Test Results Summary

**Total Tests:** 100+

**Passed:** _____ / 100+
**Failed:** _____
**Skipped:** _____

---

## 🐛 Issues Found

| Issue # | Description | Severity | Status |
|---------|-------------|----------|--------|
| | | | |
| | | | |
| | | | |

---

## ✅ Final Checklist

Before marking as production ready:

- [ ] All scenarios passed
- [ ] No console errors
- [ ] No server errors
- [ ] Database working correctly
- [ ] Performance benchmarks met
- [ ] Mobile responsive works
- [ ] All browsers tested
- [ ] Documentation updated
- [ ] Team notified

---

## 📝 Notes

**Tester:** _______________
**Date Tested:** _______________
**Environment:** Development / Staging / Production

**Additional Comments:**
```
[Write any additional observations here]
```

---

## Quick Commands

```bash
# Clear caches before testing
php artisan optimize:clear

# Check logs for errors
powershell -Command "Get-Content storage\logs\laravel.log -Tail 50"

# Run automated tests
php test_daftar_fixed.php

# Test Livewire functionality
# Visit: http://localhost/test-livewire
```

---

**Status:** Ready for Testing ✅
**Last Updated:** 2026-01-19
