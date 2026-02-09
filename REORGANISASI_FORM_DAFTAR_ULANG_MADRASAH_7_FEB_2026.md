# REORGANISASI FORM DAFTAR ULANG MADRASAH - 7 FEB 2026

## 🎯 Objective

Reorganisasi form daftar ulang madrasah di `/pendaftar/daftarulang?activeTab=madrasah` untuk meningkatkan user experience dengan mengelompokkan field-field yang berkaitan.

---

## 📋 Changes Made

### 1. Pindahkan Surat Pernyataan ke Atas

**Location:** `resources/views/livewire/pendaftar/daftar-ulang.blade.php`

**Lines:** 375-440

**Change:** Surat Pernyataan section dipindahkan ke posisi ATAS, sebelum "Pilih Nominal Biaya Daftar Ulang"

**Before:**
```
1. Pilih Nominal Biaya Daftar Ulang
2. Surat Pernyataan (di bawah)
```

**After:**
```
1. Surat Pernyataan (dipindahkan ke atas ✅)
2. Pilih Nominal Biaya Daftar Ulang
```

**Reason:** User harus download, isi, dan upload surat pernyataan terlebih dahulu sebelum melanjutkan ke pilihan nominal.

---

### 2. Gabungkan Payment Fields dengan Upload Bukti

**Location:** `resources/views/livewire/pendaftar/daftar-ulang.blade.php`

**Lines:** 578-685

**Change:**
- **Moved** "Pilih Opsi Pembayaran" (lines 580-598)
- **Moved** "Daftar Bank/Metode Pembayaran" (lines 600-617)
- **Added** "Nama Pengirim" field (lines 619-632)
- **Grouped** all three into the Upload Bukti Pembayaran container

**Before Structure:**
```
Container 1: Pilih Nominal
├── Pilih Nominal Biaya
├── Konfirmasi Nominal
├── Pilih Opsi Pembayaran
└── Daftar Bank/Metode Pembayaran

Container 2: Upload Bukti (terpisah)
└── Upload Bukti Pembayaran
```

**After Structure:**
```
Container 1: Pilih Nominal
├── Pilih Nominal Biaya
└── Konfirmasi Nominal

Container 2: Upload Bukti (merged ✅)
├── Pilih Opsi Pembayaran
├── Daftar Bank/Metode Pembayaran
├── Nama Pengirim (new ✅)
├── [Divider]
└── Upload Bukti Pembayaran
```

**Reason:** Mengelompokkan semua field yang berkaitan dengan pembayaran dalam satu container visual untuk memudahkan user mengisi data secara berurutan.

---

### 3. Tambahkan Field Nama Pengirim

**Location:** `resources/views/livewire/pendaftar/daftar-ulang.blade.php`

**Lines:** 619-632

**New Field:**
```html
<!-- Nama Pengirim -->
<div class="mb-6">
    <label class="block text-sm font-medium text-gray-700 mb-2">
        Nama Pengirim <span class="text-red-500">*</span>
    </label>
    <input type="text"
           wire:model="namaPengirim"
           placeholder="Masukkan nama pengirim sesuai bukti transfer"
           class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50">
    <p class="mt-1 text-xs text-gray-500">Nama pengirim harus sesuai dengan yang tertera pada bukti transfer</p>
    @error('namaPengirim')
        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
    @enderror
</div>
```

**Features:**
- ✅ Required field (marked with red asterisk)
- ✅ Placeholder text yang jelas
- ✅ Helper text untuk guidance
- ✅ Error validation display
- ✅ Consistent styling dengan field lain

**Wire Model:** `namaPengirim`

**Backend:** Field `nama_pengirim` sudah ada di model `DaftarUlang` (confirmed in fillable array)

**Pattern:** Mengikuti pattern yang sama dengan field Nama Pengirim di form Tes Psikologi

---

## 🏗️ Final Form Structure

### Tab Madrasah - New Layout

```
┌─────────────────────────────────────────────────────────┐
│ 1. SURAT PERNYATAAN (bg-blue-50)                       │
│    ├── Download Template                               │
│    └── Upload Surat Pernyataan                         │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│ 2. PILIH NOMINAL BIAYA (bg-emerald-50)                 │
│    ├── Pilih Nominal Biaya Daftar Ulang                │
│    └── Konfirmasi Nominal yang Dibayarkan              │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│ 3. UPLOAD BUKTI PEMBAYARAN (bg-emerald-50)             │
│    ├── Pilih Opsi Pembayaran                           │
│    ├── Daftar Bank/Metode Pembayaran                   │
│    ├── Nama Pengirim ⭐ (NEW)                          │
│    ├── ─────────────────────── [divider]               │
│    └── Upload Bukti Pembayaran                         │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│ [Tombol Simpan Data]                                    │
└─────────────────────────────────────────────────────────┘
```

---

## 🎨 Visual Improvements

### 1. Better Grouping
- Payment-related fields sekarang dalam satu container
- Visual hierarchy lebih jelas
- User flow lebih intuitif

### 2. Added Divider
```html
<!-- Divider -->
<div class="border-t border-emerald-300 mb-4"></div>
```

Memisahkan payment information dari upload section untuk clarity.

### 3. Consistent Spacing
- `mb-4` untuk field spacing
- `mb-6` untuk Nama Pengirim (extra space before divider)
- Consistent padding dalam container

---

## 🔧 Technical Details

### Files Changed

```
resources/views/livewire/pendaftar/
└── daftar-ulang.blade.php
    ├── Lines 375-440: Surat Pernyataan (moved to top)
    ├── Lines 442-572: Pilih Nominal (unchanged position)
    └── Lines 578-685: Upload Bukti (merged with payment fields)
```

### Removed Code

**Old Lines 574-611:** Removed Opsi Pembayaran and Bank Selection from old location (previously within Pilih Nominal container)

### Added Code

**Lines 580-632:**
- Opsi Pembayaran (moved from old location)
- Bank Selection (moved from old location)
- Nama Pengirim (new field ⭐)

**Line 635:** Divider between payment info and upload

### Backend Fields

**Livewire Component:** `app/Livewire/Pendaftar/DaftarUlang.php`

**Wire Models Used:**
- `wire:model="opsiPembayaran"` - Payment option (existing)
- `wire:model="bankPilihan"` - Bank selection (existing)
- `wire:model="namaPengirim"` - Sender name (existing property, new field)
- `wire:model="buktiPembayaran"` - Payment proof file (existing)
- `wire:model="suratPernyataan"` - Statement letter file (existing)

**Database Field:**
- Table: `daftar_ulang`
- Column: `nama_pengirim` (already exists in migration and fillable array)

---

## ✅ Benefits

### 1. Improved User Flow
```
Step 1: Download & upload surat pernyataan
   ↓
Step 2: Choose payment nominal
   ↓
Step 3: Fill payment details (opsi, bank, nama pengirim)
   ↓
Step 4: Upload payment proof
   ↓
Step 5: Save
```

### 2. Better Visual Organization
- Related fields grouped together
- Clear visual separation with divider
- Consistent container styling

### 3. Reduced Confusion
- Nama Pengirim now clearly associated with bank selection
- Upload section contains all payment-related information
- Logical progression from choosing payment to uploading proof

### 4. Enhanced Data Collection
- Nama Pengirim field helps verification process
- Admin can cross-check nama pengirim with bukti transfer
- Reduces back-and-forth for clarification

---

## 🧪 Testing Checklist

### Test Scenario 1: Form Load & Display

**Prerequisites:**
- User logged in as Pendaftar
- User status = 'lulus'
- Navigate to `/pendaftar/daftarulang?activeTab=madrasah`

**Expected Display:**

1. **Section 1: Surat Pernyataan** ✅
   - [ ] Appears at the TOP (before Pilih Nominal)
   - [ ] Download template button visible
   - [ ] Upload input visible
   - [ ] Background: blue-50

2. **Section 2: Pilih Nominal** ✅
   - [ ] Appears AFTER Surat Pernyataan
   - [ ] Radio buttons for nominal selection visible
   - [ ] Konfirmasi Nominal section visible
   - [ ] Background: emerald-50

3. **Section 3: Upload Bukti Pembayaran** ✅
   - [ ] Contains Pilih Opsi Pembayaran dropdown
   - [ ] Contains Daftar Bank dropdown
   - [ ] Contains Nama Pengirim input field ⭐
   - [ ] Divider visible between payment info and upload
   - [ ] Upload Bukti section visible below
   - [ ] Background: emerald-50

### Test Scenario 2: Field Interactions

**Test Nama Pengirim Field:**

1. **Field Attributes:**
   - [ ] Label: "Nama Pengirim" with red asterisk
   - [ ] Placeholder: "Masukkan nama pengirim sesuai bukti transfer"
   - [ ] Helper text visible below input
   - [ ] Input is editable

2. **Validation:**
   - [ ] Try to submit without filling Nama Pengirim
   - [ ] Error message should appear: red text below input
   - [ ] Error styling should be consistent

3. **Data Binding:**
   - [ ] Type in Nama Pengirim field
   - [ ] Value should be bound to Livewire component
   - [ ] Check browser DevTools network tab for wire:model updates

### Test Scenario 3: Form Submission

**Prerequisites:**
- Fill all required fields:
  1. Upload Surat Pernyataan
  2. Select Nominal
  3. Confirm Nominal
  4. Select Opsi Pembayaran
  5. Select Bank
  6. Enter Nama Pengirim
  7. Upload Bukti Pembayaran

**Steps:**
1. Click "Simpan Data" button
2. Observe loading indicator
3. Wait for success message

**Expected:**
- [ ] Form submits successfully
- [ ] Success message appears
- [ ] Data saved to database
- [ ] Nama Pengirim saved correctly in `daftar_ulang.nama_pengirim`

**Verify in Database:**
```sql
SELECT
    pendaftar_id,
    opsi_pembayaran,
    metode_pembayaran,
    nama_pengirim,
    bukti_pembayaran_madrasah,
    surat_pernyataan_path
FROM daftar_ulang
WHERE pendaftar_id = [test_pendaftar_id];
```

### Test Scenario 4: Visual Consistency

**Check:**
- [ ] All containers have consistent emerald/blue theme
- [ ] Spacing between sections is uniform
- [ ] Divider is visible and styled correctly
- [ ] Field labels are consistent (font, size, color)
- [ ] Required asterisks are red and visible
- [ ] Helper texts are gray and smaller font

### Test Scenario 5: Responsive Layout

**Test on different screen sizes:**

**Desktop (>1024px):**
- [ ] All fields display in full width
- [ ] Labels and inputs are aligned
- [ ] Divider spans full width

**Tablet (768px - 1024px):**
- [ ] Layout remains single column
- [ ] Fields stack properly
- [ ] No horizontal overflow

**Mobile (<768px):**
- [ ] All sections responsive
- [ ] Dropdowns are mobile-friendly
- [ ] Upload buttons accessible
- [ ] Text is readable

---

## 📦 Deployment

### Files to Upload

```
resources/views/livewire/pendaftar/
└── daftar-ulang.blade.php
```

### Upload Command

```bash
# Upload view file
scp resources/views/livewire/pendaftar/daftar-ulang.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/

# Clear view cache
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan view:clear"
```

### Batch Script

```bash
./upload_reorganisasi_form_daftar_ulang.bat
```

---

## 🔍 Verification in Production

### Quick Visual Test

1. **Navigate to form:**
   ```
   https://pmbm.mtsn2kotamalang.sch.id/pendaftar/daftarulang?activeTab=madrasah
   ```

2. **Verify order:**
   - ✅ Surat Pernyataan di paling atas
   - ✅ Pilih Nominal di tengah
   - ✅ Upload Bukti (dengan payment fields) di bawah

3. **Verify Nama Pengirim field:**
   - ✅ Field visible setelah Daftar Bank
   - ✅ Required asterisk visible
   - ✅ Placeholder text correct
   - ✅ Helper text visible

4. **Test submission:**
   - ✅ Fill form completely
   - ✅ Submit and verify success
   - ✅ Check database for nama_pengirim value

### Browser DevTools Check

**Inspect Nama Pengirim field:**
```html
<input type="text"
       wire:model="namaPengirim"
       placeholder="Masukkan nama pengirim sesuai bukti transfer"
       class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50">
```

**Check Network Tab:**
- Type in Nama Pengirim field
- Should see Livewire request with `namaPengirim` property

---

## 📊 Impact Analysis

### User Experience
- 🟢 **Improved:** More intuitive form flow
- 🟢 **Improved:** Better visual grouping
- 🟢 **Improved:** Clearer data collection

### Performance
- 🟢 **No Impact:** Same number of fields
- 🟢 **No Impact:** Same Livewire bindings
- 🟢 **No Impact:** No additional queries

### Maintenance
- 🟢 **Improved:** Better code organization
- 🟢 **Improved:** Clearer section boundaries
- 🟢 **Improved:** Easier to locate fields

### Data Quality
- 🟢 **Improved:** Nama Pengirim helps verification
- 🟢 **Improved:** Reduces admin clarification requests
- 🟢 **Improved:** Better payment tracking

---

## ⚠️ Risk Assessment

### Technical Risk
- 🟢 **Very Low:** View-only changes
- 🟢 **Very Low:** No database changes
- 🟢 **Very Low:** No breaking changes
- 🟢 **Very Low:** Existing wire models reused

### User Impact
- 🟢 **Positive:** Better UX
- 🟢 **Positive:** Clearer form flow
- 🟢 **Minimal:** Users may need to scroll slightly more

### Rollback Plan
If issues occur:
1. Restore previous version of `daftar-ulang.blade.php`
2. Clear view cache
3. No database rollback needed

---

## 📝 Notes

### Design Decisions

1. **Why move Surat Pernyataan to top?**
   - Logical first step in the process
   - User downloads, fills, and uploads before payment

2. **Why group payment fields with upload?**
   - All payment information in one place
   - Reduces cognitive load
   - Clearer progression: choose → fill details → upload proof

3. **Why add divider?**
   - Visual separation between information entry and file upload
   - Helps user understand form structure

4. **Why Nama Pengirim required?**
   - Helps admin verify payment
   - Reduces back-and-forth communication
   - Standard practice in payment forms

### Related Forms

**Tes Psikologi Form:** Already has Nama Pengirim field
- Pattern followed for consistency
- Same styling and validation

**Seragam Tab:** May benefit from similar field (future enhancement)

---

## ✅ Status

**Status:** ✅ **COMPLETED - READY FOR PRODUCTION**

**Completed Tasks:**
1. ✅ Moved Surat Pernyataan to top position
2. ✅ Removed duplicate Surat Pernyataan section
3. ✅ Moved payment fields into Upload Bukti container
4. ✅ Added Nama Pengirim field
5. ✅ Added visual divider
6. ✅ Verified wire models and backend support

**Changes Summary:**
- ✅ Surat Pernyataan: Repositioned (lines 375-440)
- ✅ Payment fields: Merged into Upload Bukti (lines 580-632)
- ✅ Nama Pengirim: Added (lines 619-632)
- ✅ Divider: Added (line 635)
- ✅ Form structure: Reorganized for better UX

**Impact:**
- 🟢 Better user experience
- 🟢 Improved data collection
- 🟢 Enhanced visual organization
- 🟢 No breaking changes
- 🟢 No database migrations needed

**Risk:**
- 🟢 Very Low - View-only changes
- 🟢 Backward compatible
- 🟢 Safe to deploy

---

**Generated:** 7 Feb 2026, 23:55
**Developer:** Claude Code
**Session:** REORGANISASI_FORM_DAFTAR_ULANG_MADRASAH
**File Changed:** `resources/views/livewire/pendaftar/daftar-ulang.blade.php`
