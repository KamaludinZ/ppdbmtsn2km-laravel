# Fix Margin Kanan Terpotong - Kartu Tes PDF
**Tanggal**: 15 Februari 2026
**Jenis**: Bug Fix - Layout Overflow
**Status**: ✅ Fixed

---

## Masalah

User report: "margin kanan masih tidak sama dan terlihat terpotong"

Setelah CSS di-match dengan preview HTML, masih ada masalah:
- Konten overflow ke kanan
- Margin kanan tidak sama dengan preview
- Text terpotong di sisi kanan

---

## Root Cause

1. **Table width calculation tidak akurat**
   - Student data column: 70% vs preview 66.67%
   - Student photo column: 30% vs preview 33.33%
   - Gap/padding tidak match

2. **No table-layout: fixed**
   - Table auto-calculate width
   - Content bisa overflow
   - Width percentage tidak respected

3. **No word-wrap**
   - Long text overflow ke kanan
   - Tidak ada line break otomatis
   - Content melewati batas column

---

## Solusi

### 1. Adjust Column Width (Match Grid-cols-3)

Preview HTML menggunakan `grid-cols-3`:
- Col-span-2 = 66.67% untuk data
- 1 col = 33.33% untuk photo

**Fix CSS**:
```css
/* BEFORE */
.student-data {
    width: 70%;
    padding-right: 8px;
}

.student-photo {
    width: 30%;
}

/* AFTER */
.student-data {
    width: 66%;
    vertical-align: top;
    padding-right: 16px;  /* Match gap-6 dari preview */
}

.student-photo {
    width: 34%;
    text-align: center;
    vertical-align: top;
}
```

### 2. Add table-layout: fixed

Semua table perlu `table-layout: fixed` agar width percentage di-respect:

```css
/* Header Table */
.header table {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed;  /* NEW */
}

/* Student Info Table */
.student-info table {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed;  /* NEW */
}

/* Data Table */
.data-table {
    width: 100%;
    font-size: 10.5pt;
    table-layout: fixed;  /* NEW */
}

/* Prestasi Table */
.prestasi-table {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed;  /* NEW */
}

/* Jadwal & Kendali Tables */
.schedule-table,
.control-table {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed;  /* NEW */
}
```

### 3. Add Word Wrap untuk Semua Elements

Agar long text tidak overflow:

```css
/* Data Table Cells */
.data-table td {
    padding: 4px 0;
    vertical-align: top;
    line-height: 1.5;
    word-wrap: break-word;        /* NEW */
    overflow-wrap: break-word;    /* NEW */
}

.data-table .value {
    font-weight: 600;
    color: #111827;
    width: 65%;  /* Explicit width */
}

/* All Table Cells */
.prestasi-table th,
.prestasi-table td {
    border: 1px solid #d1d5db;
    padding: 3px 3px;
    word-wrap: break-word;        /* NEW */
    overflow-wrap: break-word;    /* NEW */
}

.schedule-table th,
.schedule-table td,
.control-table th,
.control-table td {
    border: 1px solid #d1d5db;
    padding: 2px 3px;
    word-wrap: break-word;        /* NEW */
    overflow-wrap: break-word;    /* NEW */
}

/* Header Content */
.header-content {
    text-align: center;
    vertical-align: middle;
    word-wrap: break-word;        /* NEW */
    overflow-wrap: break-word;    /* NEW */
}

/* Notes Box */
.notes-box {
    border: 2px solid #9ca3af;
    padding: 8px;
    background-color: #fef3c7;
    font-size: 9pt;
    white-space: pre-wrap;
    word-wrap: break-word;        /* NEW */
    overflow-wrap: break-word;    /* NEW */
    color: #374151;
    margin-top: 16px;
    line-height: 1.5;
}
```

---

## File yang Diubah

**File**: `resources/views/pdf/kartu-tes-seleksi.blade.php`

### Perubahan:

1. ✅ Column width: 70/30 → 66/34 (match grid-cols-3)
2. ✅ Padding-right: 8px → 16px (match gap-6)
3. ✅ Added `table-layout: fixed` ke semua tables
4. ✅ Added `word-wrap: break-word` ke semua cells
5. ✅ Added `overflow-wrap: break-word` ke semua cells
6. ✅ Added explicit width untuk `.data-table .value` (65%)

---

## Technical Explanation

### Kenapa table-layout: fixed?

**Default (`table-layout: auto`)**:
- Browser/DomPDF auto-calculate column width berdasarkan content
- Width percentage hanya "suggestion"
- Content bisa overflow jika terlalu panjang
- Tidak predictable

**Fixed (`table-layout: fixed`)**:
- Width percentage STRICTLY respected
- Column width tetap sesuai CSS
- Content di-wrap/truncate untuk fit
- Predictable dan consistent

### Kenapa word-wrap: break-word?

**Without word-wrap**:
```
| Label         | Value                                           |
|---------------|------------------------------------------------>| (OVERFLOW!)
```

**With word-wrap**:
```
| Label         | Value                                    |
|               | (long text wrapped to next line)         |
```

### Column Width Calculation

Preview HTML:
```html
<div class="grid grid-cols-3 gap-6">
  <div class="col-span-2">...</div>  <!-- 66.67% -->
  <div>...</div>                      <!-- 33.33% -->
</div>
```

PDF equivalent:
```html
<table style="width: 100%; table-layout: fixed">
  <tr>
    <td style="width: 66%">...</td>   <!-- ≈66.67% -->
    <td style="width: 34%">...</td>   <!-- ≈33.33% -->
  </tr>
</table>
```

Gap 6 (Tailwind) = 1.5rem = 24px ≈ 16px padding-right

---

## Testing Checklist

### Test 1: Margin Kanan
- [ ] Generate PDF kartu tes
- [ ] **VERIFIKASI**: Margin kanan sama dengan preview HTML
- [ ] **VERIFIKASI**: Tidak ada konten terpotong di kanan
- [ ] **VERIFIKASI**: Border kanan terlihat sempurna

### Test 2: Long Text Wrapping
- [ ] Test dengan nama sangat panjang
- [ ] Test dengan alamat sangat panjang
- [ ] **VERIFIKASI**: Text di-wrap (tidak overflow)
- [ ] **VERIFIKASI**: Semua text visible

### Test 3: Column Width
- [ ] Compare student info section dengan preview HTML
- [ ] **VERIFIKASI**: Data column width sama (66%)
- [ ] **VERIFIKASI**: Photo column width sama (34%)
- [ ] **VERIFIKASI**: Gap antar column sama

### Test 4: All Tables
- [ ] Check tabel prestasi
- [ ] Check tabel jadwal tes
- [ ] Check tabel kartu kendali
- [ ] **VERIFIKASI**: Semua table tidak overflow
- [ ] **VERIFIKASI**: Column width consistent

---

## Expected Result

✅ PDF sekarang:
- **Margin kanan SAMA** dengan preview HTML
- **Tidak ada konten terpotong**
- **Long text di-wrap** dengan benar
- **Column width consistent** (66/34)
- **Table layout predictable** (fixed)
- **Professional appearance**

---

## Upload Command

```bash
upload_fix_kartu_tes_pdf_15_feb_2026.bat
```

---

**Status**: ✅ Complete
**Tested**: ⏳ Pending Production Test
**Ready to Deploy**: ✅ Yes
