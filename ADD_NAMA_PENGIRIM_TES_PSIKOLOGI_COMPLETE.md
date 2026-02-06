# ADD NAMA PENGIRIM FIELD - Tes Psikologi Payment

**Tanggal**: 5 Februari 2026
**Status**: ✅ READY TO UPLOAD

---

## 🎯 OBJECTIVE

Menambahkan field "Nama Pengirim" (sender name / account holder name) pada form upload bukti transfer tes psikologi untuk memudahkan verifikasi pembayaran oleh admin/panitia.

---

## 📋 WHAT WAS ADDED

### User Story
**Sebagai** pendaftar yang mengupload bukti transfer tes psikologi
**Saya ingin** mengisi nama pengirim/pemilik rekening
**Agar** panitia dapat memverifikasi dengan mudah bahwa transfer dilakukan oleh orang yang benar

### Feature Details
- **Field Name**: `nama_pengirim`
- **Field Type**: `string(255)`, nullable
- **Required**: Yes (saat upload bukti transfer)
- **Validation**: Required, string, max 255 characters
- **Display Locations**:
  1. Form upload (pendaftar)
  2. Admin/Panitia table view
  3. Invoice PDF
  4. Payment detail display (pendaftar)

---

## 🔧 FILES MODIFIED

### Total: 6 files + 1 migration + 1 export

| # | File | Change |
|---|------|--------|
| 1 | `database/migrations/2026_02_05_100000_add_nama_pengirim_to_tes_psikologi_pendaftar_table.php` | **NEW** - Add column |
| 2 | `app/Models/TesPsikologiPendaftar.php` | Add to fillable array |
| 3 | `app/Livewire/Pendaftar/TesPsikologi.php` | Add property, validation, save/reset logic |
| 4 | `resources/views/livewire/pendaftar/tes-psikologi-form.blade.php` | Add input field (Langkah 2) |
| 5 | `resources/views/livewire/admin/partials/tes-psikologi-data.blade.php` | Add table column |
| 6 | `resources/views/pdf/invoice-psikologi.blade.php` | Add to payment info |
| 7 | `resources/views/livewire/pendaftar/tes-psikologi.blade.php` | Add payment detail display |
| 8 | `app/Exports/TesPsikologiExport.php` | Add column to Excel export |

---

## 📝 DETAILED CHANGES

### 1. Migration File (NEW)

**File**: `database/migrations/2026_02_05_100000_add_nama_pengirim_to_tes_psikologi_pendaftar_table.php`

```php
public function up(): void
{
    Schema::table('tes_psikologi_pendaftar', function (Blueprint $table) {
        $table->string('nama_pengirim')->nullable()->after('waktu_transfer');
    });
}
```

**Positioning**: After `waktu_transfer` column for logical grouping

---

### 2. Model Update

**File**: `app/Models/TesPsikologiPendaftar.php`

**Change**: Added `'nama_pengirim'` to `$fillable` array (line 15)

```php
protected $fillable = [
    'pendaftar_id',
    'opsi_id',
    'waktu_transfer',
    'nama_pengirim',  // ← ADDED
    'bank_id',
    'bukti_transfer',
    // ... rest
];
```

---

### 3. Controller Updates

**File**: `app/Livewire/Pendaftar/TesPsikologi.php`

**Changes Made**:

#### a) Add Property (line 33)
```php
public $namaPengirim;
```

#### b) Load in mount() (line 75)
```php
$this->namaPengirim = $this->tesPsikologiData->nama_pengirim;
```

#### c) Validation Rules (line 249)
```php
'namaPengirim' => 'required|string|max:255',
```

#### d) Validation Messages (lines 252-254)
```php
'namaPengirim.required' => 'Nama pengirim harus diisi',
'namaPengirim.string' => 'Nama pengirim harus berupa teks',
'namaPengirim.max' => 'Nama pengirim maksimal 255 karakter',
```

#### e) Save to Database (line 298)
```php
$this->tesPsikologiData->update([
    'waktu_transfer' => $this->waktuTransfer,
    'nama_pengirim' => $this->namaPengirim,  // ← ADDED
    'bank_id' => $this->selectedBank,
    // ...
]);
```

#### f) Reset on Delete (lines 340 & 355)
```php
$this->tesPsikologiData->update([
    'bukti_transfer' => null,
    'waktu_transfer' => null,
    'nama_pengirim' => null,  // ← ADDED
    'bank_id' => null,
    // ...
]);

// Reset properties
$this->waktuTransfer = null;
$this->namaPengirim = null;  // ← ADDED
$this->selectedBank = null;
```

---

### 4. Upload Form View

**File**: `resources/views/livewire/pendaftar/tes-psikologi-form.blade.php`

**Changes Made**:

#### a) Updated Instructions (line 35)
```blade
<li class="flex items-start">
    <span class="font-bold mr-2">3.</span>
    <span>Isi <strong>nama pengirim</strong> (atas nama rekening yang digunakan untuk transfer)</span>
</li>
```

#### b) Added New Langkah 2 Section (lines 83-106)
```blade
<!-- Langkah 2: Nama Pengirim -->
<div class="relative">
    <div class="flex items-center mb-3">
        <span class="flex items-center justify-center w-8 h-8 rounded-full bg-purple-600 text-white font-bold text-sm mr-3">2</span>
        <label class="block text-base font-semibold text-gray-900">Nama Pengirim (Atas Nama Rekening) *</label>
    </div>
    <div class="ml-11">
        <p class="text-sm text-gray-600 mb-2">Atas nama siapa transfer dilakukan (sesuai pemilik rekening pengirim)</p>
        <input
            type="text"
            wire:model="namaPengirim"
            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 text-base"
            placeholder="Contoh: Budi Santoso">
        <p class="text-xs text-gray-500 mt-1">Isi sesuai nama pemilik rekening yang Anda gunakan untuk transfer</p>
        @error('namaPengirim')
            <p class="text-red-500 text-sm mt-1 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ $message }}
            </p>
        @enderror
    </div>
</div>
```

#### c) Renumbered Subsequent Steps
- Bank/Metode Pembayaran: Langkah 2 → **Langkah 3**
- Upload Bukti Transfer: Langkah 3 → **Langkah 4**

---

### 5. Admin/Panitia Table View

**File**: `resources/views/livewire/admin/partials/tes-psikologi-data.blade.php`

**Note**: This file is used by BOTH admin AND panitia (via `@include`)

**Changes Made**:

#### a) Add Table Header (line 82)
```blade
<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bank Transfer</th>
<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Transfer</th>
<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Pengirim</th>  ← ADDED
<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bukti Transfer</th>
```

#### b) Add Table Data Cell (lines 127-129)
```blade
<td class="px-6 py-4 whitespace-nowrap">
    <div class="text-sm text-gray-900">{{ $data->nama_pengirim ?? '-' }}</div>
</td>
```

---

### 6. Invoice PDF

**File**: `resources/views/pdf/invoice-psikologi.blade.php`

**Change**: Added row in payment information section (lines 221-225)

```blade
<tr>
    <td class="label" style="width: 30%;">Waktu Transfer</td>
    <td class="separator">:</td>
    <td class="value">{{ $tesPsikologiData->waktu_transfer ? \Carbon\Carbon::parse($tesPsikologiData->waktu_transfer)->format('d/m/Y H:i') . ' WIB' : '-' }}</td>
</tr>
<tr>
    <td class="label" style="width: 30%;">Nama Pengirim</td>  ← ADDED
    <td class="separator">:</td>
    <td class="value">{{ $tesPsikologiData->nama_pengirim ?? '-' }}</td>
</tr>
<tr>
    <td class="label" style="width: 30%;">Status</td>
    <td class="separator">:</td>
    <td class="value" style="color: green; text-transform: uppercase;">LUNAS / DISETUJUI</td>
</tr>
```

---

### 7. Pendaftar Payment Detail Display

**File**: `resources/views/livewire/pendaftar/tes-psikologi.blade.php`

**Change**: Added payment detail box when status = "menunggu" (lines 442-470)

```blade
<!-- Detail Pembayaran yang Disubmit -->
<div class="mb-6 bg-gray-50 border border-gray-200 rounded-lg p-4">
    <h4 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
        <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        Detail Pembayaran yang Disubmit
    </h4>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
        <div>
            <span class="text-gray-600">Bank/Metode:</span>
            <span class="ml-2 font-medium text-gray-900">{{ $tesPsikologiData->bank->nama_bank ?? '-' }}</span>
        </div>
        <div>
            <span class="text-gray-600">Waktu Transfer:</span>
            <span class="ml-2 font-medium text-gray-900">
                {{ $tesPsikologiData->waktu_transfer ? $tesPsikologiData->waktu_transfer->format('d M Y, H:i') : '-' }}
            </span>
        </div>
        <div>
            <span class="text-gray-600">Nama Pengirim:</span>  ← ADDED
            <span class="ml-2 font-medium text-gray-900">{{ $tesPsikologiData->nama_pengirim ?? '-' }}</span>
        </div>
        <div>
            <span class="text-gray-600">Tanggal Upload:</span>
            <span class="ml-2 font-medium text-gray-900">{{ $tesPsikologiData->tanggal_upload->format('d M Y, H:i') }}</span>
        </div>
    </div>
</div>
```

---

### 8. Excel Export

**File**: `app/Exports/TesPsikologiExport.php`

**Changes Made**:

#### a) Add Column Header (line 75)
```php
$baseHeadings = [
    'No. Pendaftaran',
    'Nama Lengkap',
    'NISN',
    'Jalur Pendaftaran',
    'Opsi Tes Psikologi',
    'Bank Transfer',
    'Waktu Transfer',
    'Nama Pengirim',  // ← ADDED
    'Tanggal Upload Bukti',
    // ...
];
```

#### b) Add Data Column (line 108)
```php
$baseData = [
    $tesPsikologi->pendaftar->nomor_pendaftaran ?? '-',
    $tesPsikologi->pendaftar->nama_lengkap ?? '-',
    $tesPsikologi->pendaftar->nisn ?? '-',
    $tesPsikologi->pendaftar->jalurPendaftaran->nama ?? '-',
    $tesPsikologi->opsi->nama ?? '-',
    $tesPsikologi->bank->nama_bank ?? '-',
    $tesPsikologi->waktu_transfer ? Carbon::parse($tesPsikologi->waktu_transfer)->format('d/m/Y H:i') : '-',
    $tesPsikologi->nama_pengirim ?? '-',  // ← ADDED
    $tesPsikologi->tanggal_upload ? Carbon::parse($tesPsikologi->tanggal_upload)->format('d/m/Y H:i') : '-',
    // ...
];
```

**Column Position**: Setelah "Waktu Transfer", sebelum "Tanggal Upload Bukti"

**Export Locations**:
- `/admin/tes-psikologi` → "Export Excel" button
- `/panitia/tes-psikologi` → "Export Excel" button (if panitia tes psikologi)

---

## 🚀 DEPLOYMENT STEPS

### 1. Upload Files to Production

Run the batch script:
```bash
upload_add_nama_pengirim.bat
```

**Files to Upload**:
```
1. database/migrations/2026_02_05_100000_add_nama_pengirim_to_tes_psikologi_pendaftar_table.php
2. app/Models/TesPsikologiPendaftar.php
3. app/Livewire/Pendaftar/TesPsikologi.php
4. resources/views/livewire/pendaftar/tes-psikologi-form.blade.php
5. resources/views/livewire/admin/partials/tes-psikologi-data.blade.php
6. resources/views/pdf/invoice-psikologi.blade.php
7. resources/views/livewire/pendaftar/tes-psikologi.blade.php
8. app/Exports/TesPsikologiExport.php
```

### 2. Run Migration

```bash
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
php artisan migrate
```

**Expected Output**:
```
Migrating: 2026_02_05_100000_add_nama_pengirim_to_tes_psikologi_pendaftar_table
Migrated:  2026_02_05_100000_add_nama_pengirim_to_tes_psikologi_pendaftar_table (XX.XXms)
```

### 3. Clear Cache

```bash
php artisan optimize:clear
php artisan view:clear
```

---

## 🧪 TESTING CHECKLIST

### Test 1: Pendaftar Upload Form
```
URL: /pendaftar/tes-psikologi

✅ Field "Nama Pengirim" muncul sebagai Langkah 2
✅ Label: "Nama Pengirim (Atas Nama Rekening) *"
✅ Placeholder: "Contoh: Budi Santoso"
✅ Help text muncul: "Isi sesuai nama pemilik rekening..."
✅ Field required validation bekerja
✅ Error message muncul jika kosong
✅ Langkah 3 = Bank, Langkah 4 = Upload
```

### Test 2: Data Submission
```
1. Login sebagai pendaftar
2. Isi form upload bukti transfer:
   - Tanggal/waktu transfer
   - Nama pengirim: "John Doe"
   - Bank: "BCA"
   - Upload bukti
3. Submit form

✅ Data tersimpan ke database
✅ Field nama_pengirim terisi: "John Doe"
✅ Redirect ke halaman yang sama
✅ Status berubah: "Menunggu Verifikasi"
```

### Test 3: Pendaftar Payment Detail Display
```
URL: /pendaftar/tes-psikologi (status = menunggu)

✅ Box "Detail Pembayaran yang Disubmit" muncul
✅ Bank/Metode ditampilkan
✅ Waktu Transfer ditampilkan
✅ Nama Pengirim ditampilkan: "John Doe"
✅ Tanggal Upload ditampilkan
✅ Bukti transfer image muncul di bawahnya
```

### Test 4: Admin/Panitia Table View
```
URL: /admin/tes-psikologi?mainTab=data

✅ Kolom "Nama Pengirim" muncul di table header
✅ Data nama pengirim ditampilkan: "John Doe"
✅ Jika null, tampil: "-"
✅ Kolom terurut: Bank → Waktu → Nama Pengirim → Bukti
```

### Test 5: Panitia Table View
```
URL: /panitia/tes-psikologi?mainTab=data

✅ Kolom "Nama Pengirim" muncul (sama dengan admin)
✅ Data ditampilkan dengan benar
✅ Menggunakan file yang sama dengan admin
```

### Test 6: Invoice PDF
```
1. Akses /pendaftar/tes-psikologi
2. Klik "Download Invoice" (status disetujui)

✅ PDF ter-generate
✅ Section "Informasi Pembayaran" ada
✅ Row "Bank Dari" ada
✅ Row "Waktu Transfer" ada
✅ Row "Nama Pengirim" ada dengan value "John Doe"
✅ Row "Status" ada
✅ Format A5 landscape
```

### Test 7: Edit/Delete Functionality
```
1. Upload bukti dengan nama pengirim "John Doe"
2. Klik "Hapus & Upload Ulang"
3. Konfirmasi delete

✅ Field nama_pengirim di database → NULL
✅ Property $namaPengirim di component → NULL
✅ Form kembali ke state awal
✅ Field nama pengirim kosong
```

### Test 8: Validation
```
Test Case 1: Empty Field
- Submit form tanpa isi nama pengirim
✅ Error: "Nama pengirim harus diisi"

Test Case 2: Too Long
- Isi dengan 300 karakter
✅ Error: "Nama pengirim maksimal 255 karakter"

Test Case 3: Valid Input
- Isi "Budi Santoso"
✅ Accepted, tersimpan dengan benar
```

### Test 9: Excel Export
```
1. Login sebagai Admin/Panitia Tes Psikologi
2. Akses /admin/tes-psikologi (atau /panitia/tes-psikologi)
3. Klik tombol "Export Excel"
4. Buka file Excel yang ter-download

✅ File Excel ter-download
✅ Column header ada: "Nama Pengirim"
✅ Column position: setelah "Waktu Transfer"
✅ Data nama pengirim ditampilkan
✅ Jika null, tampil: "-"
✅ Format konsisten dengan kolom lain
```

---

## 📊 BEFORE vs AFTER

### BEFORE ❌

**Upload Form**:
```
Langkah 1: Tanggal dan Waktu Transfer
Langkah 2: Pilih Bank/Metode  ← LANGSUNG KE BANK
Langkah 3: Upload Bukti Transfer
```

**Admin Table**:
```
| Bank | Waktu Transfer | Bukti Transfer |  ← TIDAK ADA NAMA PENGIRIM
```

**Invoice PDF**:
```
Bank Dari        : BCA
Waktu Transfer   : 05/02/2026 14:30 WIB
Status           : LUNAS  ← TIDAK ADA NAMA PENGIRIM
```

**Excel Export**:
```
| Bank | Waktu Transfer | Tanggal Upload | ... |  ← TIDAK ADA NAMA PENGIRIM
| BCA  | 05/02/26 14:30 | 05/02/26 14:35 | ... |
```

**Problem**:
- Admin/panitia tidak tahu siapa yang transfer
- Sulit verifikasi jika transfer dari rekening orang lain (ortu, dll)
- Harus cek manual di bukti transfer
- Export Excel tidak lengkap

---

### AFTER ✅

**Upload Form**:
```
Langkah 1: Tanggal dan Waktu Transfer
Langkah 2: Nama Pengirim (Atas Nama Rekening) *  ← ADDED!
Langkah 3: Pilih Bank/Metode
Langkah 4: Upload Bukti Transfer
```

**Admin Table**:
```
| Bank | Waktu Transfer | Nama Pengirim | Bukti Transfer |  ← ADDED!
| BCA  | 05/02/26 14:30 | John Doe      | [Lihat]        |
```

**Invoice PDF**:
```
Bank Dari        : BCA
Waktu Transfer   : 05/02/2026 14:30 WIB
Nama Pengirim    : John Doe  ← ADDED!
Status           : LUNAS
```

**Pendaftar Detail**:
```
Detail Pembayaran yang Disubmit:
- Bank/Metode: BCA
- Waktu Transfer: 05 Feb 2026, 14:30
- Nama Pengirim: John Doe  ← ADDED!
- Tanggal Upload: 05 Feb 2026, 14:35
```

**Excel Export**:
```
| Bank | Waktu Transfer | Nama Pengirim | Tanggal Upload | ... |  ← ADDED!
| BCA  | 05/02/26 14:30 | John Doe      | 05/02/26 14:35 | ... |
```

**Benefits**:
- ✅ Admin/panitia langsung tahu siapa pengirim
- ✅ Verifikasi lebih cepat
- ✅ Data lebih lengkap untuk audit
- ✅ Invoice lebih informatif
- ✅ Export Excel lengkap dengan nama pengirim

---

## ⚠️ IMPORTANT NOTES

### 1. Nullable Column
Column `nama_pengirim` dibuat **nullable** karena:
- Data lama yang sudah ada tidak memiliki field ini
- Migration tidak akan fail pada existing data
- Display menggunakan `{{ $data->nama_pengirim ?? '-' }}` untuk fallback

### 2. Required Validation
Meskipun column nullable, validation di form **required** karena:
- Upload baru HARUS menyertakan nama pengirim
- Memastikan data baru lengkap untuk verifikasi

### 3. Display Logic
```php
// Model: nullable
$table->string('nama_pengirim')->nullable();

// Validation: required (untuk upload baru)
'namaPengirim' => 'required|string|max:255',

// Display: fallback to '-'
{{ $data->nama_pengirim ?? '-' }}
```

### 4. Panitia View Sharing
File `tes-psikologi-data.blade.php` digunakan oleh:
- `/admin/tes-psikologi` (admin)
- `/panitia/tes-psikologi` (panitia + panitia tes psikologi)

Satu perubahan otomatis apply ke semua role!

---

## 🔍 TROUBLESHOOTING

### Issue 1: Column Not Found Error
```
SQLSTATE[42S22]: Column not found: nama_pengirim
```

**Solution**:
```bash
# Check migration status
php artisan migrate:status

# If not migrated, run:
php artisan migrate

# If already migrated but still error, check column exists:
php artisan tinker
>>> Schema::hasColumn('tes_psikologi_pendaftar', 'nama_pengirim')
```

### Issue 2: Field Not Showing in Form
**Check**:
1. Cache cleared? `php artisan view:clear`
2. File uploaded correctly? Check timestamp
3. Browser cache? Hard refresh (Ctrl+F5)

### Issue 3: Validation Error Not Showing
**Check**:
1. Property declared? `public $namaPengirim;`
2. Validation rule added? Check `uploadBukti()` method
3. Error directive in view? `@error('namaPengirim')`

### Issue 4: Data Not Saving
**Check**:
1. Model fillable? `'nama_pengirim'` in array
2. Update statement? `'nama_pengirim' => $this->namaPengirim`
3. Database column exists? Check migration

---

## ✅ SUCCESS CRITERIA

Feature dianggap berhasil jika:

1. ✅ Migration berhasil tanpa error
2. ✅ Column `nama_pengirim` ada di table `tes_psikologi_pendaftar`
3. ✅ Form upload menampilkan field nama pengirim
4. ✅ Validation bekerja (required, max 255)
5. ✅ Data tersimpan ke database saat submit
6. ✅ Admin/panitia table menampilkan kolom nama pengirim
7. ✅ Invoice PDF menampilkan nama pengirim
8. ✅ Pendaftar bisa melihat detail pembayaran mereka
9. ✅ Reset/delete berfungsi dengan benar
10. ✅ No error di production

---

## 📚 RELATED DOCUMENTATION

- `QUICK_SUMMARY_SURVEY_FIXES.md` - Survey error fixes (uploaded bersamaan)
- `FIX_SURVEY_ERROR_500_COMPLETE_ANALYSIS.md` - Detail survey fixes
- Model: `app/Models/TesPsikologiPendaftar.php`
- Controller: `app/Livewire/Pendaftar/TesPsikologi.php`

---

## 🎯 SUMMARY

| Item | Value |
|------|-------|
| **Feature** | Add Nama Pengirim field for Tes Psikologi payment |
| **Total Files** | 8 files (6 modified + 1 new migration + 1 export) |
| **Database Changes** | 1 column added (nullable string) |
| **Migration Required** | ✅ YES |
| **Cache Clear** | ✅ YES |
| **Risk Level** | 🟢 LOW (additive change, backward compatible) |
| **Testing Time** | ~10-15 minutes |
| **User Impact** | Positive (better verification + complete exports) |
| **Ready to Deploy** | ✅ YES |

---

**IMPLEMENTATION COMPLETE** ✅

Next step: Run `upload_add_nama_pengirim.bat` to deploy to production.
