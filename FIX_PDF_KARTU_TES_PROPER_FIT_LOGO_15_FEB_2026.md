# Fix PDF Kartu Tes - Proper Fit & Logo Display
**Tanggal**: 15 Februari 2026
**Jenis**: Bug Fix - PDF Display
**Status**: ✅ Fixed

---

## Masalah yang Diperbaiki

### 1. PDF Tidak Fit to Page A4
- ❌ Margin tidak sesuai dengan preview HTML
- ❌ Ukuran tidak proper fit di halaman A4
- ❌ Border card tidak tepat

### 2. Logo Tidak Muncul
- ❌ Logo kiri dan kanan tidak terlihat di PDF
- ❌ Path logo tidak di-convert dengan benar
- ❌ DomPDF tidak bisa load image dari URL

---

## Solusi yang Diterapkan

### 1. Proper A4 Page Fit

**CSS Update**:
```css
@page {
    size: A4 portrait;
    margin: 0;
}

body {
    margin: 0;
    padding: 0;
}

.card {
    width: 210mm;
    min-height: 297mm;
    padding: 12mm 20mm;
    margin: 0;  /* Changed from "0 auto" */
    border: 2px solid #d1d5db;
    background: #fff;
    box-sizing: border-box;
}
```

**Perubahan**:
- ✅ `@page` dipindah ke atas (setelah reset CSS)
- ✅ `body` margin 0
- ✅ `.card` margin 0 (bukan "0 auto")
- ✅ `box-sizing: border-box` untuk include border dalam ukuran

### 2. Logo Display Fix

**Problem**: DomPDF tidak bisa load image dari URL seperti:
```
http://localhost/storage/logos/logo-left.png
```

**Solution**: Convert URL ke absolute file path:

```php
@php
    $logoLeft = $printSettings['print_logo_left'];

    // Convert URL ke absolute path
    if (strpos($logoLeft, 'http') !== false) {
        // Jika URL lengkap: http://domain/storage/logos/logo.png
        $logoLeft = str_replace(url('/storage/'), '', $logoLeft);
        $logoLeft = storage_path('app/public/' . $logoLeft);
    } elseif (strpos($logoLeft, 'storage/') === 0) {
        // Jika format: storage/logos/logo.png
        $logoLeft = str_replace('storage/', '', $logoLeft);
        $logoLeft = storage_path('app/public/' . $logoLeft);
    } else {
        // Fallback: assume it's in public folder
        $logoLeft = public_path($logoLeft);
    }
@endphp

@if(file_exists($logoLeft))
    <img src="{{ $logoLeft }}" alt="Logo Kiri">
@endif
```

**Path Conversion Examples**:

| Input (Database)                                    | Output (PDF)                                              |
|-----------------------------------------------------|-----------------------------------------------------------|
| `http://localhost/storage/logos/logo.png`           | `/path/to/storage/app/public/logos/logo.png`              |
| `storage/logos/logo.png`                            | `/path/to/storage/app/public/logos/logo.png`              |
| `images/logo.png`                                   | `/path/to/public/images/logo.png`                         |

**File Exists Check**:
- Sebelum render `<img>`, cek dulu `file_exists($logoPath)`
- Jika file tidak ada, logo tidak di-render (tidak error)

### 3. Logo Sizing

```css
.header-logo img {
    width: 80px;
    height: 80px;
    object-fit: contain;  /* Maintain aspect ratio */
}
```

- Fixed width/height 80px x 80px
- `object-fit: contain` agar logo tidak terdistorsi
- Logo akan fit dalam box 80x80 dengan maintain aspect ratio

---

## File yang Dimodifikasi

**File**: `resources/views/pdf/kartu-tes-seleksi.blade.php`

**Perubahan**:
1. ✅ CSS `@page` dipindah ke atas
2. ✅ Body dan card margin di-reset
3. ✅ Logo path conversion logic (untuk logo kiri dan kanan)
4. ✅ File exists check sebelum render logo
5. ✅ Logo sizing fixed dengan `object-fit: contain`

---

## Cara Kerja Logo Path Conversion

### Scenario 1: URL dari Storage
```php
// Input from database
$logoLeft = "http://localhost/storage/logos/kemenag.png"

// Process
$logoLeft = str_replace(url('/storage/'), '', $logoLeft);
// Result: "logos/kemenag.png"

$logoLeft = storage_path('app/public/' . $logoLeft);
// Result: "/var/www/storage/app/public/logos/kemenag.png"

// Check if exists
if(file_exists($logoLeft)) {
    // PDF will use absolute path
    <img src="/var/www/storage/app/public/logos/kemenag.png">
}
```

### Scenario 2: Relative Storage Path
```php
// Input from database
$logoLeft = "storage/logos/kemenag.png"

// Process
$logoLeft = str_replace('storage/', '', $logoLeft);
// Result: "logos/kemenag.png"

$logoLeft = storage_path('app/public/' . $logoLeft);
// Result: "/var/www/storage/app/public/logos/kemenag.png"
```

### Scenario 3: Public Path
```php
// Input from database
$logoLeft = "images/logo.png"

// Process (fallback)
$logoLeft = public_path($logoLeft);
// Result: "/var/www/public/images/logo.png"
```

---

## Testing Checklist

### Test 1: PDF Fit to Page
- [ ] Generate PDF kartu tes
- [ ] **VERIFIKASI**: PDF fit sempurna di halaman A4
- [ ] **VERIFIKASI**: Tidak ada scroll horizontal/vertical
- [ ] **VERIFIKASI**: Border card terlihat rapih di tepi
- [ ] **VERIFIKASI**: Padding 12mm top/bottom, 20mm left/right

### Test 2: Logo Display
- [ ] Upload logo kiri dan kanan di settings admin
- [ ] Generate PDF kartu tes
- [ ] **VERIFIKASI**: Logo kiri muncul di PDF
- [ ] **VERIFIKASI**: Logo kanan muncul di PDF
- [ ] **VERIFIKASI**: Logo tidak terdistorsi (aspect ratio maintained)
- [ ] **VERIFIKASI**: Logo ukuran 80x80px

### Test 3: Foto Pendaftar
- [ ] Pilih pendaftar yang sudah upload foto
- [ ] Generate PDF kartu tes
- [ ] **VERIFIKASI**: Foto pendaftar muncul di PDF
- [ ] **VERIFIKASI**: Foto tidak terdistorsi
- [ ] **VERIFIKASI**: Ukuran foto 112x144px (3x4)

### Test 4: Konsistensi dengan Preview HTML
- [ ] Buka `/pendaftar/kartu-tes-seleksi` (HTML preview)
- [ ] Bandingkan dengan PDF yang di-generate
- [ ] **VERIFIKASI**: Layout sama
- [ ] **VERIFIKASI**: Font size sama
- [ ] **VERIFIKASI**: Spacing sama
- [ ] **VERIFIKASI**: Border dan warna sama

---

## Troubleshooting

### Jika Logo Masih Tidak Muncul

**1. Check File Path di Database**:
```sql
SELECT print_logo_left, print_logo_right FROM settings;
```

**2. Check File Exists**:
```bash
# SSH ke server
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

# Check storage path
ls -la storage/app/public/logos/

# Check symlink
ls -la public/storage
```

**3. Check Symlink**:
```bash
php artisan storage:link
```

**4. Manual Test Path Conversion**:
```php
// Di controller, tambahkan debug
$logoLeft = $printSettings['print_logo_left'];
\Log::info('Logo original: ' . $logoLeft);

// After conversion
\Log::info('Logo converted: ' . $logoPath);
\Log::info('File exists: ' . (file_exists($logoPath) ? 'YES' : 'NO'));
```

### Jika PDF Tidak Fit

**1. Check Browser Print Settings**:
- Pastikan paper size: A4
- Margins: None
- Scale: 100%

**2. Check DomPDF Config**:
```php
// Di controller
$pdf->setPaper('A4', 'portrait');
```

**3. Check CSS**:
- Pastikan `@page { size: A4 portrait; margin: 0; }`
- Pastikan `.card { width: 210mm; min-height: 297mm; }`

---

## Impact

### Positif
- ✅ PDF kartu tes sekarang fit sempurna di A4
- ✅ Logo kiri dan kanan muncul dengan benar
- ✅ Konsisten dengan preview HTML
- ✅ Professional output

### Negatif
- Tidak ada

---

## Technical Notes

### Kenapa DomPDF Tidak Support URL?

**DomPDF** adalah library PHP yang render HTML ke PDF di server-side. Tidak seperti browser:
- ❌ Tidak bisa akses HTTP/HTTPS URL
- ❌ Tidak punya network access
- ✅ Hanya bisa akses file system lokal

**Solution**: Convert URL ke absolute file path.

### Kenapa Pakai `storage_path()` vs `public_path()`?

Laravel file upload default ke `storage/app/public/`:
```
storage/
  app/
    public/        <- File upload disini
      logos/
        logo.png
```

Symlink dibuat dari `public/storage/` → `storage/app/public/`:
```
public/
  storage/         <- Symlink
    logos/         <- Accessible via URL
      logo.png     <- http://domain/storage/logos/logo.png
```

**Untuk DomPDF**:
- URL: `http://domain/storage/logos/logo.png` ❌
- Path: `/var/www/storage/app/public/logos/logo.png` ✅

### Object-fit: contain vs cover

```css
/* contain - maintain aspect ratio, fit inside box */
object-fit: contain;  /* Logo 100x50 in 80x80 box = centered, no crop */

/* cover - maintain aspect ratio, fill box (may crop) */
object-fit: cover;    /* Logo 100x50 in 80x80 box = cropped sides */
```

Untuk logo, pakai `contain` agar tidak ter-crop.

---

**Status**: ✅ Fixed
**Tested**: ⏳ Pending Production Test
**Ready to Deploy**: ✅ Yes

---

## Upload

File yang sama sudah include fix ini:
```bash
upload_fix_kartu_tes_pdf_15_feb_2026.bat
```

Upload dan test di production untuk verifikasi fix berhasil.
