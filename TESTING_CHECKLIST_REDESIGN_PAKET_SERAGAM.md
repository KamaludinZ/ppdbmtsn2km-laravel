# Testing Checklist: Redesign Paket Seragam

**Tanggal:** 8 Februari 2026
**Fitur:** Dependent Dropdown Paket Seragam

---

## 📋 Pre-Deployment Testing (Local)

### Admin - Settings Configuration

**Path:** `/admin/daftar-ulang?activeTab=settings` → Tab "Daftar Ulang Seragam"

- [ ] **Create First Package**
  - [ ] Click "+ Tambah Paket"
  - [ ] Enter package name: "Paket Lengkap"
  - [ ] Enter description: "Seragam lengkap untuk tahun ajaran baru"
  - [ ] Click "+ Tambah Opsi" (inside package)
  - [ ] Add Option 1: Name: "Putra", Amount: 1500000
  - [ ] Add Option 2: Name: "Putri", Amount: 1450000
  - [ ] Click "Simpan Pengaturan"
  - [ ] ✅ Check: Success message appears
  - [ ] ✅ Check: Page reload shows saved data

- [ ] **Create Second Package**
  - [ ] Click "+ Tambah Paket" again
  - [ ] Enter package name: "Paket Ekonomis"
  - [ ] Enter description: "Paket hemat"
  - [ ] Add Option 1: Name: "Standar", Amount: 1000000
  - [ ] Save settings
  - [ ] ✅ Check: Both packages visible

- [ ] **Edit Package**
  - [ ] Change first package description
  - [ ] Add one more option to first package
  - [ ] Save settings
  - [ ] ✅ Check: Changes persist

- [ ] **Delete Operations**
  - [ ] Delete one option from a package
  - [ ] ✅ Check: Confirmation modal appears
  - [ ] Confirm deletion
  - [ ] ✅ Check: Option removed
  - [ ] Delete entire second package
  - [ ] ✅ Check: Package removed

- [ ] **Opsi Lainnya Setting**
  - [ ] Check "Aktifkan Opsi Lainnya untuk Konfirmasi Nominal"
  - [ ] Save settings
  - [ ] ✅ Check: Setting saved

---

### Pendaftar - Form Submission

**Path:** `/pendaftar/daftarulang?activeTab=seragam`

#### Section 1: Pilih Paket & Nominal

- [ ] **Visual Check**
  - [ ] ✅ Blue gradient background for Section 1
  - [ ] ✅ "Step 1" header with icon visible
  - [ ] ✅ Section break (dashed line) visible

- [ ] **Paket Dropdown**
  - [ ] ✅ Dropdown shows all packages created in admin
  - [ ] Select "Paket Lengkap"
  - [ ] ✅ Dependent dropdown appears below

- [ ] **Opsi Dropdown (Dependent)**
  - [ ] ✅ Shows options: "Putra (Rp 1.500.000)" and "Putri (Rp 1.450.000)"
  - [ ] Select "Putra"
  - [ ] ✅ Konfirmasi Nominal section appears below

- [ ] **Konfirmasi Nominal - Auto Populate**
  - [ ] ✅ "Bayar Penuh" option selected by default
  - [ ] ✅ Amount matches selected option (Rp 1.500.000)
  - [ ] ✅ Shows option name in subtext

- [ ] **Konfirmasi Nominal - Opsi Lainnya**
  - [ ] ✅ "Opsi Lainnya" radio button visible (if enabled in admin)
  - [ ] Click "Opsi Lainnya"
  - [ ] ✅ Manual input field appears
  - [ ] Enter custom amount: 750000
  - [ ] ✅ No validation errors

#### Section 2: Pembayaran & Upload

- [ ] **Visual Check**
  - [ ] ✅ Green gradient background for Section 2
  - [ ] ✅ "Step 2" header with icon visible

- [ ] **Opsi Pembayaran**
  - [ ] ✅ Dropdown shows payment options (Lunas/Angsuran/Potongan)
  - [ ] Select "Lunas"

- [ ] **Bank/Metode Pembayaran**
  - [ ] ✅ Dropdown shows configured banks
  - [ ] Select a bank

- [ ] **Nama Pengirim (NEW)**
  - [ ] ✅ Input field visible
  - [ ] ✅ Helper text: "Nama orang yang mengirimkan pembayaran (sesuai bukti transfer)"
  - [ ] ✅ Placeholder text visible
  - [ ] Enter name: "Ahmad Zulkifli"

- [ ] **Upload Bukti**
  - [ ] ✅ File upload area visible
  - [ ] Upload a PDF/JPG file
  - [ ] ✅ File name appears after selection

#### Form Submission

- [ ] **Save Data**
  - [ ] Click "Simpan Data Seragam"
  - [ ] ✅ Loading state appears
  - [ ] ✅ Success message appears
  - [ ] Reload page
  - [ ] ✅ All data persists:
    - [ ] Paket selection
    - [ ] Opsi selection
    - [ ] Konfirmasi nominal
    - [ ] Payment info
    - [ ] Nama pengirim
    - [ ] Bukti file

---

## 🗄️ Database Verification

```sql
-- Check seragam table has new column
SELECT
    paket_seragam,
    opsi_seragam,        -- NEW COLUMN
    nominal_seragam_awal,
    nominal_seragam,
    nama_pengirim_seragam,
    opsi_pembayaran_seragam,
    bank_seragam
FROM seragam
WHERE pendaftar_id = [TEST_USER_ID];
```

**Expected Result:**
- `opsi_seragam`: Contains JSON of selected option (e.g., `{"id":"opt_xyz","name":"Putra","amount":1500000}`)
- `paket_seragam`: Contains full package JSON
- `nominal_seragam`: Contains konfirmasi nominal (auto or manual)
- `nama_pengirim_seragam`: Contains sender name

---

## 🚀 Production Deployment Checklist

### Upload Files

```batch
# Run the upload script
upload_redesign_paket_seragam_8_feb_2026.bat
```

**Files uploaded:**
1. ✅ Migration file
2. ✅ Seragam model
3. ✅ Admin Livewire
4. ✅ Admin view
5. ✅ Pendaftar Livewire
6. ✅ Pendaftar view

### Run on Production Server

```bash
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

# Run migration
php artisan migrate

# Clear all cache
php artisan optimize:clear

# Verify migration
php artisan migrate:status | grep opsi_seragam
```

### Post-Deployment Testing

**Repeat all tests above on production environment:**

1. [ ] Admin can create hierarchical packages
2. [ ] Pendaftar sees dependent dropdowns
3. [ ] Konfirmasi nominal auto-populates
4. [ ] Nama Pengirim field works
5. [ ] Data saves correctly
6. [ ] Database has correct structure

---

## 🔧 Rollback Plan (If Issues Found)

If critical issues are found in production:

```bash
# Rollback migration
php artisan migrate:rollback --step=1

# Restore old files from backup
# (Ensure you have backups before deployment)
```

---

## 📝 Notes

- **Visual Sections:** New design has 2 clear visual sections (Blue for selection, Green for payment)
- **Auto-populate:** Konfirmasi nominal automatically fills from selected option
- **Manual Override:** "Opsi Lainnya" allows custom nominal input
- **Nama Pengirim:** New required field for payment sender identification
- **Backward Compatibility:** Old data in database will still work (nullable column)

---

## ✅ Sign-off

- [ ] Local testing completed successfully
- [ ] Database migration verified
- [ ] Production deployment completed
- [ ] Production testing verified
- [ ] No critical issues found

**Tested by:** _______________
**Date:** _______________
**Production URL:** https://pmbm.mtsn2kotamalang.sch.id/pendaftar/daftarulang?activeTab=seragam
