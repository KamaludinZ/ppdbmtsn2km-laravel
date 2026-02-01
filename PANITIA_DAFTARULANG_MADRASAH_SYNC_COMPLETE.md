# Sync Admin Daftar Ulang Madrasah ke Panitia Daftar Ulang - Complete

**Tanggal:** 30 Januari 2026
**Status:** ✅ Backend Selesai | 🔄 View In Progress

## Overview

Menyinkronkan **semua fitur dari tab Daftar Ulang Madrasah** (`/admin/daftar-ulang?activeTab=madrasah`) ke halaman Panitia Daftar Ulang (`/panitia-daftarulang/daftar-ulang`).

**PENTING:**
- **TIDAK menggunakan tab** (single page)
- **Fokus HANYA ke Madrasah** (tidak ada seragam, ukuran)
- **Role:** Panitia Daftar Ulang saja

---

## Backend Changes (✅ COMPLETED)

### File: `app/Livewire/Panitia/DaftarUlang.php`

### 1. Traits Added
```php
use WithFileUploads;  // Added for file uploads
```

### 2. Properties Added

#### Madrasah Settings
```php
public $madrasahContent = '';
public $nominalsMadrasah = [];
public $nominalBayarMadrasah = '';
public $banksMadrasah = [];
public $bankMadrasah = '';
public $templateSuratMadrasah = null;
public $templateEmailMadrasah = '';
public $templateWaMadrasah = '';
public $imageMadrasah = null;
public $currentImageMadrasah = '';
public $paymentInfoText = '';
public $showPernyataan = true;
public $showPembayaranMadrasah = true;
```

#### Bulk Message Properties
```php
public $showBulkMessageModal = false;
public $bulkMessageType = 'email';
public $bulkMessageContent = '';
public $bulkMessageSubject = '';
public $bulkRecipientFilter = 'all';
```

### 3. Methods Added/Updated

#### mount() Method
- Load all Madrasah settings from database
- Load nominals, banks, templates (email & WA)
- Load images and payment info text

#### Template Methods
```php
public function loadTemplateEmail()    // Load email template
public function loadTemplateWA()       // Load WhatsApp template
public function resetTemplate($type)   // Reset template (email/wa)
```

#### Settings Management Methods
```php
public function addNominalMadrasah()      // Add nominal to list
public function removeNominalMadrasah()   // Remove nominal
public function addBankMadrasah()         // Add bank to list
public function removeBankMadrasah()      // Remove bank
```

#### saveSettings() Method
- Save all Madrasah settings
- Handle file uploads (image, template surat)
- Save to database with JSON encoding

#### Status Management Methods
```php
public function resetPersetujuan($pendaftarId)              // Reset approval status
public function changeStatusPersetujuan($pendaftarId, $status)  // Change approval status
```

#### Existing Methods (Already in Panitia)
- `sendEmail($id)` - Send email to pendaftar
- `openMessageModal($id)` - Open message modal
- `sendMessage()` - Send internal message
- `verifyDocument()` - Verify documents
- `verifyDaftarUlang()` - Verify daftar ulang
- `changeStatusMadrasah()` - Change madrasah status
- `saveCatatan()` - Save notes (updated to Madrasah only)

---

## View Changes (🔄 IN PROGRESS)

### File: `resources/views/livewire/panitia/daftar-ulang.blade.php`

### Structure from Admin Madrasah Tab

The Admin view has these sections that need to be copied:

#### 1. Settings Panel (Lines 275-600+ in Admin)
- **Header:** "Pengaturan Daftar Ulang Madrasah"
- **Content Editor:** CKEditor for madrasah content
- **Nominal Pembayaran:** Dynamic add/remove list
- **Bank Options:** Dynamic add/remove list
- **Template Surat:** File upload
- **Template Email:** Textarea with placeholder reference
- **Template WhatsApp:** Textarea with placeholder reference
- **Image Upload:** For madrasah info image
- **Payment Info Text:** Textarea
- **Toggle Switches:** Show Pernyataan, Show Pembayaran
- **Save Button:** Save all settings

#### 2. Data Table (Lines 1000+ in Admin)
Current Panitia view already has table, needs enhancement:

**Columns to ensure exist:**
- Nomor Pendaftaran
- Pendaftar (Nama + NISN)
- Jenis Kelamin
- Jalur
- Persetujuan (with timestamp)
- Alasan (if tidak setuju)
- Status
- Bukti Pernyataan
- Bukti Pembayaran
- Catatan (with edit button)
- Aksi (Verifikasi buttons)
- Aksi Pesan (Email + WhatsApp buttons)
- Riwayat Pesan (Last message info)

**Action Buttons:**
- Reset Persetujuan button
- Change Status Persetujuan dropdown
- Change Status Madrasah dropdown
- Verifikasi buttons
- Send Email button
- Send Message button
- Edit Catatan button

#### 3. Stats Section
Already exists in Panitia, keep as-is.

---

## Admin Madrasah Tab Features Copied

### ✅ Settings Management
- [x] Content editor (madrasah content)
- [x] Dynamic nominal list
- [x] Dynamic bank list
- [x] Template surat upload
- [x] Email template with placeholders
- [x] WhatsApp template with placeholders
- [x] Image upload
- [x] Payment info text
- [x] Toggle show/hide forms

### ✅ Status Management
- [x] Reset persetujuan
- [x] Change status persetujuan
- [x] Change status madrasah
- [x] Catatan management

### ✅ Verification
- [x] Document verification
- [x] Payment verification
- [x] Daftar ulang verification

### ✅ Messaging
- [x] Send email (existing)
- [x] Send internal message (existing)
- [x] Riwayat pesan display

### ⏳ To Be Added (View Only)
- [ ] Settings panel UI
- [ ] Template loading buttons
- [ ] Image preview
- [ ] Enhanced table with all action buttons
- [ ] Reset persetujuan button UI
- [ ] Change status persetujuan UI
- [ ] WhatsApp button (if needed)

---

## Differences from Admin

### Removed Features (Not needed for Panitia)
- ❌ **NO TABS** - Single page, Madrasah only
- ❌ **No Seragam** - Not in scope
- ❌ **No Ukuran** - Not in scope
- ❌ **No Bulk Message** - Not in scope (can be added later if needed)

### Kept Features (Same as Admin)
- ✅ All Madrasah settings
- ✅ All status management
- ✅ All verification features
- ✅ Messaging (email + internal)
- ✅ Catatan management
- ✅ Riwayat pesan

---

## Template Placeholders (27 Available)

For Email/WhatsApp templates, these placeholders can be used:

```
{nama}                  - Nama lengkap pendaftar
{nomor_pendaftaran}     - Nomor pendaftaran
{nisn}                  - NISN
{tempat_lahir}          - Tempat lahir
{tanggal_lahir}         - Tanggal lahir (formatted)
{jenis_kelamin}         - Jenis kelamin (Laki-laki/Perempuan)
{alamat}                - Alamat lengkap
{email}                 - Email
{telepon}               - Nomor telepon
{no_hp}                 - Nomor HP
{asal_sekolah}          - Asal sekolah
{jalur}                 - Jalur pendaftaran
{status}                - Status pendaftaran
{catatan}               - Catatan dari panitia
{url_dashboard}         - URL dashboard
{password}              - [Encrypted message]
{agama}                 - Agama
{nama_ayah}             - Nama ayah
{nama_ibu}              - Nama ibu
{pekerjaan_ayah}        - Pekerjaan ayah
{pekerjaan_ibu}         - Pekerjaan ibu
{provinsi}              - Provinsi
{kabupaten}             - Kabupaten
{kecamatan}             - Kecamatan
{desa}                  - Desa/Kelurahan
{kode_pos}              - Kode pos
{nilai_rata_rata}       - Nilai rata-rata
```

---

## Next Steps (View Implementation)

### Step 1: Add Settings Panel
Copy settings panel from Admin Madrasah tab (lines 275-600) to Panitia view, place it BEFORE the table section.

### Step 2: Enhance Table
Update table to include:
- Reset Persetujuan button
- Change Status Persetujuan dropdown
- Enhanced action buttons

### Step 3: Test Features
- [ ] Settings save/load correctly
- [ ] File uploads work
- [ ] Templates load correctly
- [ ] Status changes work
- [ ] Verification works
- [ ] Messaging works
- [ ] Catatan save/load works

---

## Files Modified

### Backend (✅ Complete)
- `app/Livewire/Panitia/DaftarUlang.php` (line count: 628 lines)

### View (🔄 To be updated)
- `resources/views/livewire/panitia/daftar-ulang.blade.php` (current: 535 lines)

---

## Benefits for Panitia Daftar Ulang

1. **Complete Control** - Same settings as Admin for Madrasah
2. **Template Management** - Can manage email/WA templates
3. **Status Management** - Can reset and change persetujuan
4. **Efficient Workflow** - All features in one page
5. **Consistent Experience** - Same features as Admin

---

## Important Notes

- **No Tabs:** Single page design, cleaner for Panitia role
- **Madrasah Focus:** Only Madrasah features, no seragam/ukuran
- **Same Features:** All Admin Madrasah features available
- **Role Specific:** Only for panitia_daftarulang and panitia roles

---

**Implementation Status:**
- Backend: ✅ 100% Complete
- View: 🔄 Awaiting implementation
- Testing: ⏳ Pending

**Next Action:** Implement view changes to add settings panel and enhance table with all action buttons.
