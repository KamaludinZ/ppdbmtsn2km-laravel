# Checklist Production Testing - Status Display Seragam
**Date:** 8 Februari 2026
**Feature:** Status Display Cards Seragam Tab

---

## 📋 Pre-Deployment Checklist

- [ ] Backup production database
- [ ] Backup `resources/views/livewire/pendaftar/daftar-ulang.blade.php` file
- [ ] Review all changes in `FITUR_STATUS_DISPLAY_SERAGAM_8_FEB_2026.md`
- [ ] Ensure upload script is ready: `upload_status_display_seragam_8_feb_2026.bat`

---

## 🚀 Deployment Steps

### Step 1: Upload File
```batch
upload_status_display_seragam_8_feb_2026.bat
```

- [ ] File uploaded successfully
- [ ] No upload errors
- [ ] SSH connection stable

### Step 2: Clear Cache
```bash
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
php artisan optimize:clear
```

- [ ] Cache cleared successfully
- [ ] No PHP errors
- [ ] Views compiled correctly

---

## ✅ Testing Checklist

### Test 1: Status NULL (First Time User)
**URL:** `/pendaftar/daftarulang?activeTab=seragam`

- [ ] Form is visible
- [ ] All fields enabled
- [ ] Upload field shows empty state (drag & drop area)
- [ ] "Simpan Data Seragam" button visible
- [ ] Can fill form and submit

---

### Test 2: Status MENUNGGU
**Setup:** Submit seragam form → status changes to "menunggu"

#### Visual Display
- [ ] Form is HIDDEN (not visible)
- [ ] Yellow status card is shown
- [ ] Card has yellow-50 background
- [ ] Card has yellow-400 border (2px)
- [ ] Clock icon displayed

#### Status Card Content
- [ ] Title: "⏳ Status: Menunggu Persetujuan"
- [ ] Description about verification process
- [ ] Information box with 4 bullet points:
  - [ ] "Bukti pembayaran seragam Anda sedang diverifikasi"
  - [ ] "Proses verifikasi biasanya memakan waktu 1-3 hari kerja"
  - [ ] "Anda akan mendapat notifikasi jika data disetujui atau perlu perbaikan"
  - [ ] "Pantau terus status di halaman ini"

#### Action Buttons
- [ ] "Lihat Bukti Pembayaran" button visible
- [ ] Button has blue-600 background
- [ ] Button has eye icon
- [ ] Button opens file in new tab when clicked
- [ ] "Download Invoice" button NOT visible (should be hidden)

---

### Test 3: Status DISETUJUI
**Setup:** Admin approves seragam → status changes to "disetujui"

#### Visual Display
- [ ] Form is HIDDEN (not visible)
- [ ] Green status card is shown
- [ ] Card has green-50 background
- [ ] Card has green-400 border (2px)
- [ ] Checkmark icon displayed

#### Status Card Content
- [ ] Title: "✅ Status: Disetujui"
- [ ] Congratulations message
- [ ] Verification box with 4 checkmarks:
  - [ ] "Bukti pembayaran seragam telah diverifikasi"
  - [ ] "Data nominal pembayaran sesuai"
  - [ ] "Proses daftar ulang seragam selesai"
  - [ ] "Anda dapat mengunduh invoice pembayaran"

#### Action Buttons
- [ ] "Lihat Bukti Pembayaran" button visible
- [ ] Button opens file correctly
- [ ] "Download Invoice Seragam" button visible (SHOULD SHOW)
- [ ] Button has purple-600 background
- [ ] Button has download icon
- [ ] Click button → PDF downloads
- [ ] PDF filename format: `invoice-du-seragam-{nomor}-{nama}.pdf`
- [ ] PDF opens correctly
- [ ] PDF contains correct data

---

### Test 4: Status DITOLAK
**Setup:** Admin rejects seragam → status changes to "ditolak"

#### Form Display
- [ ] Form is VISIBLE (shown again)
- [ ] All fields are enabled
- [ ] Previously filled data still exists (paket, nominal, bank, etc.)

#### Upload Field Behavior
- [ ] Upload field shows EMPTY state (not file selected state)
- [ ] No old file info displayed
- [ ] Drag & drop area visible
- [ ] Instructions text visible: "Klik untuk upload bukti pembayaran"
- [ ] File type info visible: "PDF, JPG, PNG (Max. 5MB)"

#### Re-upload Test
- [ ] Can select new file
- [ ] File selected state displays correctly (green border)
- [ ] File info shows: name, type, size
- [ ] "Ganti File" button works
- [ ] "Simpan Data Seragam" button enabled
- [ ] Click Simpan → form submits
- [ ] Status changes to "menunggu"
- [ ] Form hides again
- [ ] Yellow status card shows

---

### Test 5: Status PERBAIKI
**Setup:** Admin sets status to "perbaiki"

#### Same as Test 4 (Ditolak)
- [ ] Form visible
- [ ] Upload field empty
- [ ] Can re-upload
- [ ] Can re-submit
- [ ] Status changes to menunggu after submit

---

### Test 6: File Naming Verification

#### Upload New File
- [ ] Select and upload file
- [ ] Submit form
- [ ] Check database: `seragam.bukti_pembayaran` column

#### Verify Filename Format
Expected format: `bukti_bayar_seragam_{nomor_pendaftaran}_{timestamp}_{nama_slug}.{ext}`

Example: `bukti_bayar_seragam_20260001_1707379200_ahmad_rizki.pdf`

- [ ] Filename has correct prefix: `bukti_bayar_seragam_`
- [ ] Filename contains nomor pendaftaran
- [ ] Filename contains timestamp
- [ ] Filename contains nama lengkap (slugified)
- [ ] Filename has correct extension (.pdf, .jpg, .jpeg, .png)
- [ ] File stored in correct path: `storage/app/private/bukti_bayar/{pendaftar_id}/`

---

### Test 7: Button Hover Effects

#### Lihat Bukti Pembayaran Button
- [ ] Hover → background changes to blue-700
- [ ] Hover → slight scale transform (hover:scale-105)
- [ ] Smooth transition

#### Download Invoice Button
- [ ] Hover → background changes to purple-700
- [ ] Hover → slight scale transform
- [ ] Smooth transition

---

### Test 8: Responsive Layout

#### Desktop (md breakpoint and above)
- [ ] Action buttons in 2-column grid
- [ ] Buttons side by side
- [ ] Adequate spacing between buttons

#### Mobile (below md breakpoint)
- [ ] Action buttons in 1-column grid
- [ ] Buttons stacked vertically
- [ ] Full width buttons
- [ ] Adequate spacing between buttons

---

### Test 9: Edge Cases

#### Case 1: No bukti_pembayaran in database
- [ ] "Lihat Bukti Pembayaran" button should NOT show
- [ ] "Download Invoice" button should still show (if disetujui)

#### Case 2: Invalid file path
- [ ] Click "Lihat Bukti" → should handle gracefully (404 or error message)

#### Case 3: Multiple status changes
1. Submit → menunggu
2. Admin approve → disetujui
3. Admin revert to perbaiki
4. Pendaftar re-upload
5. Submit → menunggu
- [ ] Each status displays correctly
- [ ] No UI glitches

#### Case 4: Long file names
- [ ] File name truncates properly
- [ ] No layout breaking

---

### Test 10: Integration with Existing Features

#### Warning Perbedaan Ukuran
- [ ] Warning still shows correctly (if ukuran baju ≠ ukuran celana)
- [ ] Warning position above form sections
- [ ] Warning NOT shown when status = menunggu/disetujui

#### Hierarchical Dropdown (Paket → Opsi)
- [ ] Dropdown works when form is visible
- [ ] Selected paket/opsi saves correctly
- [ ] Auto-populate nominal works

#### Payment Time Settings
- [ ] If payment time expired, appropriate message shows
- [ ] Upload disabled if payment closed

---

## 🐛 Bug Verification

Check for these common issues:

- [ ] No "Undefined variable" errors
- [ ] No "Call to a member function on null" errors
- [ ] No blank white screen (check error logs)
- [ ] No layout breaking or overlapping elements
- [ ] No missing translations or text
- [ ] No console errors (check browser DevTools)
- [ ] No 500 errors when clicking buttons
- [ ] No 404 errors when viewing files

---

## 📊 Performance Check

- [ ] Page loads within 2 seconds
- [ ] No slow database queries
- [ ] File upload works smoothly
- [ ] PDF generation is fast (< 3 seconds)
- [ ] No memory issues

---

## 🔒 Security Verification

- [ ] Can only view own bukti pembayaran (not other users')
- [ ] Can only download own invoice
- [ ] Cannot access bukti files directly via URL without auth
- [ ] File upload validation works (size, type)
- [ ] No XSS vulnerabilities in file names
- [ ] No SQL injection in form fields

---

## 📝 Final Verification

### Functionality
- [ ] All status displays work correctly
- [ ] All buttons function as expected
- [ ] Form show/hide logic correct
- [ ] Upload field resets properly
- [ ] File naming service working

### Visual Design
- [ ] Colors match design (yellow-50/400, green-50/400, blue-600, purple-600)
- [ ] Icons display correctly
- [ ] Typography is consistent
- [ ] Spacing and padding look good
- [ ] Responsive layout works

### User Experience
- [ ] Clear status messages
- [ ] Helpful instructions
- [ ] Smooth transitions
- [ ] Intuitive button placement
- [ ] Good feedback on actions

---

## 📸 Screenshots (Optional)

Take screenshots of:
- [ ] Status Menunggu (yellow card)
- [ ] Status Disetujui (green card)
- [ ] Form for Ditolak/Perbaiki (with empty upload)
- [ ] Action buttons layout (desktop & mobile)

---

## ✅ Sign-off

**Tested by:** ___________________
**Date:** ___________________
**Time:** ___________________

**All tests passed:** [ ] Yes [ ] No

**Issues found:**
```
(List any issues discovered during testing)
```

**Notes:**
```
(Additional observations or comments)
```

---

## 🔄 Rollback Plan (If Needed)

If critical issues are found:

1. Restore backup file:
```bash
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
cp resources/views/livewire/pendaftar/daftar-ulang.blade.php.backup resources/views/livewire/pendaftar/daftar-ulang.blade.php
php artisan optimize:clear
```

2. Verify rollback successful
3. Investigate and fix issues
4. Re-deploy when ready

---

**Feature:** Status Display Seragam
**Documentation:** FITUR_STATUS_DISPLAY_SERAGAM_8_FEB_2026.md
**Upload Script:** upload_status_display_seragam_8_feb_2026.bat

🚀 **Ready for Production Testing!**
