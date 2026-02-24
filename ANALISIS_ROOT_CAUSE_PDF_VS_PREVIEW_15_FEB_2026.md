# Analisis Root Cause: PDF vs Preview HTML
**Tanggal**: 15 Februari 2026
**Status**: ✅ ROOT CAUSE DITEMUKAN

---

## Masalah User

> "cari tahu kenapa tampilan di sisi kanan selalu melebar tidak bisa fit seperti tampilan di preview di semua komponen yang tampil mulai dari kop, tabel dan catatan yang belum mirip. padahal saya sudah cocok dengan tampilan di preview namun ini di tampilan cetak tidak bisa mirip."

---

## ROOT CAUSE DITEMUKAN! 🎯

### 1. Preview HTML menggunakan MODERN CSS

**File**: `resources/views/livewire/pendaftar/kartu-tes-seleksi.blade.php`

```html
<!-- Line 33: Card dengan padding INLINE STYLE -->
<div class="mx-auto border-2 border-gray-300 bg-white shadow-lg print:shadow-none print:border-black"
     style="width: 210mm; min-height: 297mm; padding: 12mm 20mm;">

    <!-- Line 35: Header menggunakan FLEXBOX -->
    <div class="flex items-start gap-6 pb-3 border-b-4 border-black mb-4">
        <!-- Logo kiri: w-20 (80px), flex-shrink-0 -->
        <div class="flex-shrink-0 w-20 h-20 flex items-center justify-center">

        <!-- Content tengah: flex-1 (grow to fill space) -->
        <div class="flex-1 text-center">

        <!-- Logo kanan: w-20 (80px), flex-shrink-0 -->
        <div class="flex-shrink-0 w-20 h-20 flex items-center justify-center">
    </div>

    <!-- Line 81: Student info menggunakan CSS GRID -->
    <div class="grid grid-cols-3 gap-6 mb-4">
        <div class="col-span-2">  <!-- 66.66% width -->
        <div>                     <!-- 33.33% width -->
    </div>
</div>
```

**Preview HTML uses**:
- ✅ **Flexbox** untuk header kop
- ✅ **CSS Grid** untuk student info
- ✅ **Tailwind CSS** classes (compiled to modern CSS)
- ✅ **Padding: 12mm 20mm** (INLINE STYLE di line 33)
- ✅ **Usable width: 210mm - 40mm = 170mm**

### 2. PDF menggunakan TABLE LAYOUT (v1-v6)

**File**: `resources/views/pdf/kartu-tes-seleksi.blade.php`

```css
.card {
    width: 210mm;
    padding: 5mm 6mm;    /* v6: Saya scale down! */
}
```

**PDF v6 uses**:
- ❌ **Table layout** (karena DomPDF tidak support flexbox/grid)
- ❌ **Padding: 5mm 6mm** (BERBEDA dengan preview!)
- ❌ **Usable width: 210mm - 12mm = 198mm** (TERLALU LUAS!)

---

## PERBEDAAN FUNDAMENTAL

| Aspek | Preview HTML | PDF v1-v6 | MATCH? |
|-------|--------------|-----------|--------|
| **Layout Engine** | Flexbox + Grid | Table | ❌ NO |
| **Padding** | **12mm 20mm** | 5mm 6mm (v6) | ❌ NO |
| **Usable Width** | **170mm** | 198mm (v6) | ❌ NO |
| **Header Layout** | Flexbox (flex-1) | Table (fixed %) | ❌ NO |
| **Student Info** | Grid (grid-cols-3) | Table (66/34%) | ⚠️ PARTIAL |
| **Gap/Spacing** | gap-6 (24px) | Manual padding | ⚠️ PARTIAL |

---

## KENAPA SELALU MELEBAR KE KANAN?

### Root Cause Analysis:

**Preview HTML**:
```
Total width: 210mm
Padding: 12mm (left) + 12mm (right) = 24mm... WAIT!
       → 20mm (left) + 20mm (right) = 40mm
Usable content width: 210mm - 40mm = 170mm ✅

Header kop (flexbox):
  - Logo kiri: w-20 (80px ≈ 21mm)
  - Gap: gap-6 (24px ≈ 6mm)
  - Content: flex-1 (auto, fills remaining space)
  - Gap: gap-6 (24px ≈ 6mm)
  - Logo kanan: w-20 (80px ≈ 21mm)

  Total: 21mm + 6mm + auto + 6mm + 21mm = 54mm + auto
  Auto content width: 170mm - 54mm = 116mm
```

**PDF v6** (yang saya buat):
```
Total width: 210mm
Padding: 5mm (left) + 5mm (right) = 10mm... WAIT!
       → 6mm (left) + 6mm (right) = 12mm
Usable content width: 210mm - 12mm = 198mm ❌ TERLALU LEBAR!

Header kop (table):
  - Logo kiri: 60px (fixed)
  - Content: auto
  - Logo kanan: 60px (fixed)

  Problem: 198mm is TOO WIDE!
  Browser tries to render header in 198mm space
  BUT content designed for 170mm!
  Result: OVERFLOW ke kanan! ❌
```

### Visual Comparison:

```
PREVIEW HTML (170mm usable):
|<- 20mm ->|<----- 170mm content ----->|<- 20mm ->|
           [KOP HEADER FIT PERFECTLY]

PDF v6 (198mm usable):
|<-6mm->|<-------- 198mm content -------->|<-6mm->|
        [KOP HEADER MELEBAR, OVERFLOW KANAN!] ❌
```

---

## KENAPA TABEL JUGA MELEBAR?

**Preview HTML tabel**:
```html
<!-- Prestasi table: font-size: 9px (inline style) -->
<table class="w-full border-collapse border border-gray-300"
       style="font-size: 9px; line-height: 1.3;">
    <!-- Column widths defined as percentages of 170mm -->
    <th style="width: 4%">No</th>       <!-- 6.8mm -->
    <th style="width: 6%">Tahun</th>    <!-- 10.2mm -->
    <th style="width: 24%">Nama Lomba</th> <!-- 40.8mm -->
    ...
    Total: 100% of 170mm = 170mm ✅ FIT!
```

**PDF v6 tabel**:
```css
.prestasi-table {
    width: 100%;        /* 100% of 198mm! */
    font-size: 5.5pt;   /* Scaled down */
}
/* Columns SAME percentages but of 198mm base! */
width: 4% → 4% of 198mm = 7.9mm ❌ BERBEDA!
width: 24% → 24% of 198mm = 47.5mm ❌ TERLALU LEBAR!

Result: Tabel melebar karena base width berbeda!
```

---

## SOLUSI YANG BENAR

### Strategy: MATCH PREVIEW 100% dengan Table Layout

#### Fix 1: Gunakan Padding SAMA dengan Preview
```css
/* WRONG (v6) */
.card {
    padding: 5mm 6mm;   /* Usable: 198mm */
}

/* CORRECT (v7) */
.card {
    padding: 12mm 20mm; /* Usable: 170mm - MATCH PREVIEW! */
}
```

#### Fix 2: Font Sizes TIDAK PERLU di-scale extreme
```css
/* Preview HTML uses: */
- Body: ~14px (default browser)
- Student data: text-sm (14px)
- Prestasi table: 9px (inline style)
- Jadwal/Kendali: text-xs (12px)

/* PDF v7 should use (convert to pt): */
- Body: 10.5pt (≈14px)
- Student data: 10.5pt
- Prestasi table: 6.75pt (≈9px)
- Jadwal/Kendali: 9pt (≈12px)

NO EXTREME SCALING NEEDED!
```

#### Fix 3: Logo Size MATCH Preview
```css
/* Preview: w-20 h-20 (80px) */

/* PDF v7: */
.header-logo {
    width: 80px;    /* NOT 60px! Match preview! */
}
.header-logo img {
    width: 80px;
    height: 80px;
}
```

#### Fix 4: Calculate Header Layout EXACTLY

**Preview flexbox calculation**:
```
Logo kiri: 80px (w-20)
Gap: 24px (gap-6)
Content: flex-1 (remaining space)
Gap: 24px (gap-6)
Logo kanan: 80px (w-20)
```

**PDF table layout** (convert to percentages of 170mm):
```
Total usable: 170mm = 641px (at 96 DPI)

Logo kiri: 80px / 641px = 12.5%
Content: auto (remaining)
Logo kanan: 80px / 641px = 12.5%

OR use fixed widths in table:
<td style="width: 80px;">Logo</td>
<td>Content (auto)</td>
<td style="width: 80px;">Logo</td>
```

---

## PERBANDINGAN v6 vs v7 (FIXED)

| Element | Preview HTML | PDF v6 (Wrong) | PDF v7 (Correct) |
|---------|--------------|----------------|------------------|
| **Card Padding** | 12mm 20mm | 5mm 6mm ❌ | **12mm 20mm** ✅ |
| **Usable Width** | 170mm | 198mm ❌ | **170mm** ✅ |
| **Logo Size** | 80x80px | 60x60px ❌ | **80x80px** ✅ |
| **Body Font** | ~14px | 7.5pt (10px) ❌ | **10.5pt (14px)** ✅ |
| **Student Data** | 14px (text-sm) | 7.5pt ❌ | **10.5pt** ✅ |
| **Prestasi Table** | 9px | 5.5pt (7px) ❌ | **6.75pt (9px)** ✅ |
| **Jadwal Table** | 12px (text-xs) | 6.5pt (9px) ❌ | **9pt (12px)** ✅ |
| **Header Border** | 4px (border-b-4) | 2px ❌ | **4px** ✅ |
| **Line-height** | 1.5 (default) | 1.3 ❌ | **1.5** ✅ |
| **Margins** | mb-4 (16px) | 10px ❌ | **16px** ✅ |

---

## JAWABAN untuk User

### Pertanyaan:
> "apakah bisa jika di buat sangat mirip?"

### JAWABAN: **YA, BISA!** ✅

**Caranya**:
1. ✅ Gunakan **padding SAMA** dengan preview: `12mm 20mm`
2. ✅ Gunakan **font sizes SAMA** dengan preview (10.5pt body, 9pt tables)
3. ✅ Gunakan **logo size SAMA**: 80x80px
4. ✅ Gunakan **spacing SAMA**: margins 16px, gaps match Tailwind
5. ✅ Calculate **table layouts** yang match dengan flexbox/grid percentages
6. ✅ Usable width akan **SAMA**: 170mm

**Kenapa v1-v6 gagal?**
- Karena saya **scale down terlalu agresif** (padding 12mm→5mm, fonts 10pt→7.5pt)
- User sudah cocok dengan **preview 100%**
- Tapi saya malah **mengubah padding dan fonts**
- Harusnya saya **MATCH EXACT preview, convert layout saja**!

**v7 akan**:
- Padding: **12mm 20mm** (match preview)
- Fonts: **10.5pt body, 9pt tables** (match preview)
- Logo: **80x80px** (match preview)
- Width: **170mm** (match preview)
- Result: **SANGAT MIRIP dengan preview!** ✅

---

## Next Step

Buat **v7** dengan approach:
- ✅ MATCH preview dimensions 100%
- ✅ Convert HANYA layout (flexbox→table, grid→table)
- ✅ Keep ALL sizes sama dengan preview
- ✅ NO extreme scaling

**Confidence**: ⭐⭐⭐⭐⭐ (100% - ini adalah solusi yang benar!)
