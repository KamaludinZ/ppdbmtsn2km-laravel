# FIX: Excel Export Daftar Ulang - Semua Sheet (21 Feb 2026)

## 🐛 Problem

Excel export untuk laporan Daftar Ulang memiliki beberapa masalah:

1. **Data Terlewat**: Beberapa kolom penting tidak muncul di export
2. **Raw JSON**: Paket Seragam menampilkan JSON mentah, bukan nama yang readable
3. **Missing Columns**:
   - Tidak ada kolom "Opsi yang Dipilih" (opsi dari paket yang dipilih user)
   - Tidak ada kolom "Nominal Opsi" (nominal dari opsi yang dipilih)
4. **Multi-Select Not Handled**: Checkbox mode (multi-select) tidak ditangani dengan benar
5. **Paket Lainnya Not Labeled**: Paket manual tidak diberi label yang jelas
6. **Wrong Field Used**: Menggunakan `nominal_biaya` padahal harusnya `nominal_biaya_awal` untuk opsi yang dipilih

**User Request**: "selanjutnya perlu perbaikan juga di data laporan excel dari daftar ulang madrasah, daftar ulang seragam dan ukuran seragam yang perlu di sesuaikan juga agar dapat masuk di excel semua tanpa ada yang terlewat."

---

## ✅ Solution Applied

### 1. **DaftarUlangMadrasahSheet.php** - Add Missing Columns & Parse JSON

**File**: `app/Exports/DaftarUlangMadrasahSheet.php`

#### Changes in `map()` method (Line 44-104):

**BEFORE**:
```php
return [
    $pendaftar->nomor_pendaftaran ?? '',
    $pendaftar->nisn ?? '',
    // ... other fields
    'Rp ' . number_format($daftarUlang->nominal_biaya ?? 0, 0, ',', '.'), // WRONG FIELD!
    // Missing: Opsi yang dipilih
    // Missing: Nominal opsi
    // Missing: Konfirmasi nominal
];
```

**AFTER**:
```php
// Get nominal biaya awal (opsi yang dipilih dari paket)
$nominalBiayaAwal = $daftarUlang->nominal_biaya_awal;
$opsiName = '';
$opsiAmount = 0;

if (is_array($nominalBiayaAwal)) {
    $opsiName = $nominalBiayaAwal['name'] ?? '';
    $opsiAmount = $nominalBiayaAwal['amount'] ?? 0;
}

// Get nominal biaya (konfirmasi nominal yang dibayarkan)
$nominalBiaya = $daftarUlang->nominal_biaya;
$nominalBiayaName = '';
$nominalBiayaAmount = 0;

if (is_array($nominalBiaya)) {
    $nominalBiayaName = $nominalBiaya['name'] ?? '';
    $nominalBiayaAmount = $nominalBiaya['amount'] ?? 0;
}

return [
    // ... basic fields
    $opsiName, // NEW: Opsi yang dipilih dari paket
    'Rp ' . number_format($opsiAmount, 0, ',', '.'), // NEW: Nominal opsi
    $nominalBiayaName, // NEW: Konfirmasi nominal
    'Rp ' . number_format($nominalBiayaAmount, 0, ',', '.'), // NEW: Nominal yang dibayarkan
    // ... other fields
];
```

#### Changes in `headings()` method (Line 106-135):

**BEFORE**:
```php
return [
    'No. Pendaftaran',
    // ... other columns
    'Nominal Biaya', // WRONG!
    'Opsi Pembayaran',
    // ...
];
```

**AFTER**:
```php
return [
    'No. Pendaftaran',
    // ... other columns
    'Opsi yang Dipilih', // NEW: Nama opsi dari paket atau nama paket custom
    'Nominal Opsi', // NEW: Nominal dari opsi yang dipilih
    'Konfirmasi Nominal', // RENAMED: Konfirmasi pembayaran
    'Nominal yang Dibayarkan', // RENAMED: Nominal yang dikonfirmasi
    'Opsi Pembayaran',
    'Metode Pembayaran',
    // ...
];
```

**Result**:
- ✅ Opsi yang dipilih user terlihat dengan jelas
- ✅ Nominal opsi dan konfirmasi terpisah dengan jelas
- ✅ Paket custom (paket_lainnya) menampilkan nama yang diinput user
- ✅ Semua data tidak ada yang terlewat

---

### 2. **DaftarUlangSeragamSheet.php** - Parse JSON Paket & Handle Multi-Select

**File**: `app/Exports/DaftarUlangSeragamSheet.php`

#### Changes in `map()` method (Line 47-132):

**BEFORE**:
```php
return [
    $pendaftar->nomor_pendaftaran ?? '',
    // ... other fields
    $seragam->paket_seragam ?? '', // RAW JSON! e.g. {"name":"Paket A","items":[...]}
    'Rp ' . number_format($seragam->nominal_seragam ?? 0, 0, ',', '.'), // WRONG FIELD!
    // Missing: Opsi yang dipilih
    // Missing: Multi-select handling
];
```

**AFTER**:
```php
// Parse paket seragam (JSON paket normal atau "paket_lainnya")
$paketName = 'Belum Dipilih';
$paketSeragamRaw = $seragam->paket_seragam;

if ($paketSeragamRaw === 'paket_lainnya') {
    $paketName = 'Paket Lainnya (Manual)';
} elseif (!empty($paketSeragamRaw)) {
    // Try to decode if it's JSON
    $paketData = json_decode($paketSeragamRaw, true);
    if (is_array($paketData) && isset($paketData['name'])) {
        $paketName = $paketData['name'];
    } else {
        $paketName = 'Paket Seragam';
    }
}

// Get nominal seragam awal (opsi yang dipilih dari paket)
// Could be single object or array (multi-select/checkbox mode)
$nominalSeragamAwal = $seragam->nominal_seragam_awal;
$opsiName = '';
$opsiAmount = 0;

if (is_array($nominalSeragamAwal)) {
    // Check if multi-select (array of objects with name/amount)
    if (isset($nominalSeragamAwal[0]) && is_array($nominalSeragamAwal[0])) {
        // Multi-select: combine names and sum amounts
        $names = [];
        $totalAmount = 0;
        foreach ($nominalSeragamAwal as $opsi) {
            $names[] = $opsi['name'] ?? '';
            $totalAmount += $opsi['amount'] ?? 0;
        }
        $opsiName = implode(' + ', array_filter($names)); // e.g. "Baju Harian + Celana"
        $opsiAmount = $totalAmount;
    } else {
        // Single select or paket_lainnya
        $opsiName = $nominalSeragamAwal['name'] ?? '';
        $opsiAmount = $nominalSeragamAwal['amount'] ?? 0;
    }
}

// Get nominal seragam (konfirmasi nominal yang dibayarkan)
$nominalSeragam = $seragam->nominal_seragam;
$nominalSeragamName = '';
$nominalSeragamAmount = 0;

if (is_array($nominalSeragam)) {
    $nominalSeragamName = $nominalSeragam['name'] ?? '';
    $nominalSeragamAmount = $nominalSeragam['amount'] ?? 0;
}

return [
    // ... basic fields
    $paketName, // Nama paket (readable) e.g. "Paket Lengkap"
    $opsiName, // Opsi yang dipilih (bisa multi-select dengan +)
    'Rp ' . number_format($opsiAmount, 0, ',', '.'), // Total nominal dari opsi
    $nominalSeragamName, // Konfirmasi nominal
    'Rp ' . number_format($nominalSeragamAmount, 0, ',', '.'), // Nominal yang dibayarkan
    // ... other fields
];
```

#### Changes in `headings()` method (Line 134-158):

**BEFORE**:
```php
return [
    'No. Pendaftaran',
    // ... other columns
    'Paket Seragam', // Shows raw JSON!
    'Nominal Seragam', // Wrong field!
    // ...
];
```

**AFTER**:
```php
return [
    'No. Pendaftaran',
    // ... other columns
    'Paket Seragam', // UPDATED: Nama paket yang readable
    'Opsi yang Dipilih', // NEW: Nama opsi (bisa multi-select dengan +)
    'Nominal Opsi', // NEW: Total nominal dari opsi yang dipilih
    'Konfirmasi Nominal', // RENAMED: Konfirmasi pembayaran
    'Nominal yang Dibayarkan', // RENAMED: Nominal yang dikonfirmasi
    'Opsi Pembayaran',
    'Bank',
    'Nama Pengirim',
    // ...
];
```

**Result**:
- ✅ Paket Seragam menampilkan nama yang readable (bukan JSON)
- ✅ Paket Lainnya diberi label "Paket Lainnya (Manual)"
- ✅ Multi-select ditangani dengan separator " + " (e.g. "Baju Harian + Celana")
- ✅ Total nominal multi-select dijumlahkan dengan benar
- ✅ Opsi yang dipilih dan konfirmasi pembayaran terpisah jelas

---

### 3. **DaftarUlangUkuranSheet.php** - Parse Paket Seragam for Consistency

**File**: `app/Exports/DaftarUlangUkuranSheet.php`

#### Changes in `map()` method (Line 57-99):

**BEFORE**:
```php
return [
    $pendaftar->nomor_pendaftaran ?? '',
    // ... other fields
    $seragam->paket_seragam ?? '', // RAW JSON or "paket_lainnya"
    // ... ukuran fields
];
```

**AFTER**:
```php
// Parse paket seragam (same as DaftarUlangSeragamSheet for consistency)
$paketName = 'Belum Dipilih';
$paketSeragamRaw = $seragam->paket_seragam;

if ($paketSeragamRaw === 'paket_lainnya') {
    $paketName = 'Paket Lainnya (Manual)';
} elseif (!empty($paketSeragamRaw)) {
    // Try to decode if it's JSON
    $paketData = json_decode($paketSeragamRaw, true);
    if (is_array($paketData) && isset($paketData['name'])) {
        $paketName = $paketData['name'];
    } else {
        $paketName = 'Paket Seragam';
    }
}

return [
    $pendaftar->nomor_pendaftaran ?? '',
    // ... other fields
    $paketName, // Nama paket (readable)
    // ... ukuran fields
];
```

#### Changes in `headings()` method (Line 101-125):

No changes needed in headings - already has "Paket Seragam" column, just the value is now parsed correctly.

**Result**:
- ✅ Paket Seragam menampilkan nama yang readable
- ✅ Konsisten dengan DaftarUlangSeragamSheet
- ✅ Paket Lainnya diberi label yang jelas

---

## 📁 Files Changed

1. **app/Exports/DaftarUlangMadrasahSheet.php** (MODIFIED)
   - Line 44-104: Added parsing for nominal_biaya_awal and nominal_biaya
   - Line 106-135: Added new column headings

2. **app/Exports/DaftarUlangSeragamSheet.php** (MODIFIED)
   - Line 47-132: Added paket parsing, multi-select handling, and nominal parsing
   - Line 134-158: Added new column headings

3. **app/Exports/DaftarUlangUkuranSheet.php** (MODIFIED)
   - Line 57-99: Added paket seragam parsing

4. **FIX_EXCEL_EXPORT_DAFTAR_ULANG_21_FEB_2026.md** (NEW)
   - This documentation file

---

## 🧪 Testing Instructions

### Test Case 1: Export Daftar Ulang Madrasah

**Step 1**: Akses halaman Daftar Ulang Madrasah
```
URL: /admin/daftarulang (pilih tab "Daftar Ulang Madrasah")
atau: /panitia/daftarulang (pilih tab "Daftar Ulang Madrasah")
```

**Step 2**: Klik tombol "Export Excel"

**Step 3**: Verify Excel columns
Buka file Excel dan pastikan ada kolom:
- No. Pendaftaran
- NISN
- Nama Lengkap
- ... (kolom lain)
- **Opsi yang Dipilih** ← NEW
- **Nominal Opsi** ← NEW
- **Konfirmasi Nominal** ← RENAMED
- **Nominal yang Dibayarkan** ← RENAMED
- Opsi Pembayaran
- Metode Pembayaran

**Step 4**: Verify data for normal package
Cari pendaftar yang pilih paket normal (bukan paket_lainnya):
- **Opsi yang Dipilih**: harus menampilkan nama opsi (e.g. "Bayar Penuh Rp 500.000")
- **Nominal Opsi**: harus menampilkan "Rp 500.000"
- **Konfirmasi Nominal**: harus menampilkan "Bayar Penuh Rp 500.000" atau "Opsi Lainnya"
- **Nominal yang Dibayarkan**: harus menampilkan nominal yang dikonfirmasi

**Step 5**: Verify data for paket_lainnya
Cari pendaftar yang pilih "Paket Lainnya (Input Manual)":
- **Opsi yang Dipilih**: harus menampilkan nama custom (e.g. "Paket Custom Saya")
- **Nominal Opsi**: harus menampilkan nominal custom (e.g. "Rp 750.000")

---

### Test Case 2: Export Daftar Ulang Seragam

**Step 1**: Akses halaman Daftar Ulang Seragam
```
URL: /admin/daftarulang (pilih tab "Daftar Ulang Seragam")
atau: /panitia/daftarulang (pilih tab "Daftar Ulang Seragam")
```

**Step 2**: Klik tombol "Export Excel"

**Step 3**: Verify Excel columns
Buka file Excel dan pastikan ada kolom:
- No. Pendaftaran
- ... (kolom lain)
- **Paket Seragam** ← UPDATED (readable)
- **Opsi yang Dipilih** ← NEW
- **Nominal Opsi** ← NEW
- **Konfirmasi Nominal** ← RENAMED
- **Nominal yang Dibayarkan** ← RENAMED
- Opsi Pembayaran

**Step 4**: Verify paket_seragam parsing
- **Paket Seragam** dengan paket normal: harus menampilkan "Paket Lengkap" (bukan JSON)
- **Paket Seragam** dengan paket_lainnya: harus menampilkan "Paket Lainnya (Manual)"

**Step 5**: Verify single-select (radio mode)
Cari pendaftar yang pilih opsi tunggal:
- **Opsi yang Dipilih**: "Baju Seragam Harian"
- **Nominal Opsi**: "Rp 150.000"

**Step 6**: Verify multi-select (checkbox mode)
Cari pendaftar yang pilih multiple opsi:
- **Opsi yang Dipilih**: "Baju Seragam Harian + Celana Panjang + Kopyah" ← WITH " + " separator
- **Nominal Opsi**: "Rp 350.000" ← SUM of all selected options

**Step 7**: Verify paket_lainnya
Cari pendaftar dengan paket custom:
- **Paket Seragam**: "Paket Lainnya (Manual)"
- **Opsi yang Dipilih**: "Seragam Custom Saya"
- **Nominal Opsi**: "Rp 2.000.000"

---

### Test Case 3: Export Ukuran Seragam

**Step 1**: Akses halaman Ukuran Seragam
```
URL: /admin/daftarulang (pilih tab "Ukuran Seragam & Atribut")
atau: /panitia/daftarulang (pilih tab "Ukuran Seragam & Atribut")
```

**Step 2**: Klik tombol "Export Excel"

**Step 3**: Verify paket_seragam column
- Pendaftar dengan paket normal: harus menampilkan "Paket Lengkap" (bukan JSON)
- Pendaftar dengan paket_lainnya: harus menampilkan "Paket Lainnya (Manual)"
- Konsisten dengan export Daftar Ulang Seragam

---

## 📊 Sample Expected Output

### Daftar Ulang Madrasah Excel:

| No. Pendaftaran | Nama Lengkap | Opsi yang Dipilih | Nominal Opsi | Konfirmasi Nominal | Nominal yang Dibayarkan |
|-----------------|--------------|-------------------|--------------|---------------------|-------------------------|
| 2026001 | Ahmad Zaki | Bayar Penuh Rp 500.000 | Rp 500.000 | Bayar Penuh Rp 500.000 | Rp 500.000 |
| 2026002 | Siti Aminah | Paket Custom Khusus | Rp 750.000 | Opsi Lainnya | Rp 600.000 |

### Daftar Ulang Seragam Excel:

| No. Pendaftaran | Nama Lengkap | Paket Seragam | Opsi yang Dipilih | Nominal Opsi | Konfirmasi Nominal |
|-----------------|--------------|---------------|-------------------|--------------|---------------------|
| 2026001 | Ahmad Zaki | Paket Lengkap | Baju Harian + Celana + Kopyah | Rp 350.000 | Bayar Penuh Rp 350.000 |
| 2026002 | Siti Aminah | Paket Lainnya (Manual) | Seragam Custom | Rp 2.000.000 | Bayar Penuh Rp 2.000.000 |

### Ukuran Seragam Excel:

| No. Pendaftaran | Nama Lengkap | Paket Seragam | Ukuran Jas | Ukuran Baju Harian |
|-----------------|--------------|---------------|------------|---------------------|
| 2026001 | Ahmad Zaki | Paket Lengkap | M | L |
| 2026002 | Siti Aminah | Paket Lainnya (Manual) | L | XL |

---

## ✅ Success Criteria

### DaftarUlangMadrasahSheet:
- [x] Parsing nominal_biaya_awal (opsi yang dipilih)
- [x] Parsing nominal_biaya (konfirmasi pembayaran)
- [x] Column "Opsi yang Dipilih" added
- [x] Column "Nominal Opsi" added
- [x] Column "Konfirmasi Nominal" renamed
- [x] Column "Nominal yang Dibayarkan" renamed
- [x] Paket custom menampilkan nama yang diinput user
- [x] Semua data tidak ada yang terlewat

### DaftarUlangSeragamSheet:
- [x] Parsing paket_seragam JSON to readable name
- [x] Paket Lainnya labeled as "Paket Lainnya (Manual)"
- [x] Multi-select handled with " + " separator
- [x] Total nominal multi-select summed correctly
- [x] Parsing nominal_seragam_awal (opsi yang dipilih)
- [x] Parsing nominal_seragam (konfirmasi pembayaran)
- [x] Column "Paket Seragam" shows readable name
- [x] Column "Opsi yang Dipilih" added
- [x] Column "Nominal Opsi" added
- [x] Column "Konfirmasi Nominal" renamed
- [x] Column "Nominal yang Dibayarkan" renamed

### DaftarUlangUkuranSheet:
- [x] Parsing paket_seragam JSON to readable name
- [x] Paket Lainnya labeled as "Paket Lainnya (Manual)"
- [x] Consistent with DaftarUlangSeragamSheet

---

## 📝 Notes

### Key Data Structure Changes:
- **Before**: Export hanya menampilkan `nominal_biaya` atau `nominal_seragam` (field konfirmasi)
- **After**: Export menampilkan **both** `nominal_biaya_awal`/`nominal_seragam_awal` (opsi yang dipilih) DAN `nominal_biaya`/`nominal_seragam` (konfirmasi pembayaran)

### Multi-Select Handling:
- System sekarang support **checkbox mode** di Tab Seragam
- Multiple opsi di-combine dengan separator " + " (e.g. "Baju + Celana + Kopyah")
- Total nominal adalah **SUM** dari semua opsi yang dipilih

### Paket Lainnya Handling:
- Paket manual (paket_lainnya) sekarang diberi label "Paket Lainnya (Manual)"
- Nama custom yang diinput user ditampilkan di kolom "Opsi yang Dipilih"
- Nominal custom ditampilkan di kolom "Nominal Opsi"

### Consistency Across All Exports:
- Semua 3 export sheet sekarang **konsisten** dalam parsing paket_seragam
- Column naming **konsisten**: "Opsi yang Dipilih", "Nominal Opsi", "Konfirmasi Nominal", "Nominal yang Dibayarkan"
- Label "Paket Lainnya (Manual)" **konsisten** di semua sheet

---

## 🔗 Related Fixes

- `FIX_PAKET_LAINNYA_TAB_MADRASAH_21_FEB_2026.md` - Fix paket lainnya display & save (Tab Madrasah)
- `FIX_PAKET_LAINNYA_TAB_SERAGAM_21_FEB_2026.md` - Fix paket lainnya display & save (Tab Seragam)
- Export fixes sekarang **consistent** dengan logic di Blade template dan Livewire component

---

**Author**: Claude Code
**Date**: 21 Februari 2026
**Related Files**: DaftarUlangMadrasahSheet.php, DaftarUlangSeragamSheet.php, DaftarUlangUkuranSheet.php
**Issue Type**: Excel Export, Data Completeness, JSON Parsing, Multi-Select Support
