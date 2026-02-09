# Files Pengumuman untuk Pendaftar - Lokasi & Perubahan

## 📋 Jawaban Langsung

**Pertanyaan:** "File mana yang berubah untuk perbaikan RTE di pengumuman yang tampil di pendaftar dan halaman public?"

**Jawaban:**
❌ **Pengumuman TIDAK menggunakan RTE Trix Editor**
✅ **Pengumuman menggunakan TextFormatter** (dari session 2 Feb 2026)

---

## 📂 Files yang Berubah untuk Pengumuman

### Backend Files (3 files):

#### 1. `app/Helpers/TextFormatter.php` ⭐ NEW
**Lokasi:** C:\ppdbmtsn2km-laravel\app\Helpers\TextFormatter.php

**Fungsi:** Custom parser untuk convert markdown-like syntax ke HTML

**Methods:**
```php
// Format pengumuman
TextFormatter::formatPengumuman($text);

// Strip formatting
TextFormatter::stripFormatting($text);

// Convert to plain text
TextFormatter::toPlainText($text);
```

**Apa yang dilakukan:**
- Convert `*text*` → `<strong>text</strong>`
- Convert `_text_` → `<em>text</em>`
- Convert URL → Clickable link dengan `target="_blank"`
- Preserve line breaks
- XSS protection dengan `htmlspecialchars()`

---

#### 2. `app/Helpers/helpers.php` ⭐ NEW
**Lokasi:** C:\ppdbmtsn2km-laravel\app\Helpers\helpers.php

**Fungsi:** Global helper functions

**Functions:**
```php
// Format pengumuman
format_pengumuman($text);

// Strip formatting
strip_formatting($text);
```

**Usage di Blade:**
```blade
{!! format_pengumuman($pengumuman->konten) !!}
```

---

#### 3. `composer.json` 🔧 MODIFIED
**Lokasi:** C:\ppdbmtsn2km-laravel\composer.json

**Perubahan:** Autoload helpers.php

```json
"autoload": {
    "psr-4": {
        "App\\": "app/",
        ...
    },
    "files": [
        "app/Helpers/helpers.php"
    ]
}
```

**PENTING:** Setelah upload, run:
```bash
composer dump-autoload
```

---

### View Files (2 files):

#### 4. `resources/views/livewire/admin/pengumuman.blade.php` 🔧 MODIFIED
**Lokasi:** C:\ppdbmtsn2km-laravel\resources\views\livewire\admin\pengumuman.blade.php

**Perubahan:**
- Textarea diperbesar (rows="10")
- Font monospace untuk editor
- **Panel panduan formatting** dengan icon dan contoh
- Placeholder dengan contoh syntax

**Before:**
```blade
<textarea wire:model="konten" rows="5" class="...">
</textarea>
```

**After:**
```blade
<textarea wire:model="konten" rows="10" class="font-mono ...">
</textarea>

<!-- Panel Panduan Formatting -->
<div class="mt-2 bg-blue-50 border border-blue-200 rounded-lg p-3">
    <h4 class="font-semibold text-blue-900 text-sm mb-2">
        📘 Panduan Formatting:
    </h4>
    <ul class="text-xs text-blue-800 space-y-1">
        <li>• <code class="bg-blue-100 px-1 rounded">*teks*</code> → <strong>teks tebal</strong></li>
        <li>• <code class="bg-blue-100 px-1 rounded">_teks_</code> → <em>teks miring</em></li>
        <li>• Tulis URL lengkap (https://...) untuk membuat link yang bisa diklik</li>
        <li>• Tekan <kbd>Enter 2x</kbd> untuk membuat paragraf baru</li>
        <li>• Tekan <kbd>Enter 1x</kbd> untuk pindah baris</li>
    </ul>
</div>
```

**Screenshot:**
```
┌─────────────────────────────────────────────┐
│ Konten Pengumuman                           │
│ ┌─────────────────────────────────────────┐ │
│ │ *PENGUMUMAN PENTING*                    │ │
│ │                                         │ │
│ │ Hasil seleksi akan diumumkan pada:     │ │
│ │ _Tanggal: 15 Feb 2026_                 │ │
│ │                                         │ │
│ │ https://ppdb.mtsn2kotamalang.sch.id   │ │
│ └─────────────────────────────────────────┘ │
│                                             │
│ 📘 Panduan Formatting:                      │
│ • *teks* → teks tebal                       │
│ • _teks_ → teks miring                      │
│ • URL lengkap → clickable link              │
│ • Enter 2x → paragraf baru                  │
└─────────────────────────────────────────────┘
```

---

#### 5. `resources/views/livewire/pendaftar/dashboard.blade.php` 🔧 MODIFIED
**Lokasi:** C:\ppdbmtsn2km-laravel\resources\views\livewire\pendaftar\dashboard.blade.php

**Lokasi Perubahan:** Lines 976-978

**Before:**
```blade
<div class="text-sm text-gray-600 leading-relaxed prose prose-sm max-w-none">
    {!! $pengumuman->konten !!}
</div>
```

**After:**
```blade
<div class="text-sm text-gray-600 leading-relaxed">
    {!! format_pengumuman($pengumuman->konten) !!}
</div>
```

**Apa yang Berubah:**
1. ❌ Removed `prose prose-sm max-w-none` classes
2. ✅ Added `format_pengumuman()` helper
3. ✅ Now supports formatting: bold, italic, links

**Result:**
```html
<!-- Input Admin -->
*PENGUMUMAN PENTING*

Hasil seleksi akan diumumkan pada:
_Tanggal: 15 Feb 2026_

Info: https://ppdb.mtsn2kotamalang.sch.id

<!-- Output di Dashboard Pendaftar -->
<strong>PENGUMUMAN PENTING</strong>

<p>Hasil seleksi akan diumumkan pada:<br>
<em>Tanggal: 15 Feb 2026</em></p>

<p>Info: <a href="https://ppdb.mtsn2kotamalang.sch.id" target="_blank" rel="noopener noreferrer">https://ppdb.mtsn2kotamalang.sch.id</a></p>
```

---

### Halaman Public Homepage:

#### 6. `resources/views/public/home.blade.php` ❌ NO CHANGES
**Lokasi:** C:\ppdbmtsn2km-laravel\resources\views\public\home.blade.php

**Status:** ❌ Tidak ada dynamic pengumuman di halaman public

**Yang Ada:**
- Hanya teks statis yang menyebutkan "pengumuman hasil seleksi"
- Tidak ada data pengumuman dari database yang ditampilkan
- Tidak perlu perubahan

**Kesimpulan:**
Halaman public homepage TIDAK menampilkan pengumuman dari database, jadi **tidak ada file yang perlu diubah untuk public**.

---

## 📊 Summary Lokasi Pengumuman

### 1. Dashboard Pendaftar ✅
**File:** `resources/views/livewire/pendaftar/dashboard.blade.php`

**Status:** ✅ Sudah menggunakan `format_pengumuman()`

**Lokasi di Dashboard:**
```
Dashboard Pendaftar
├── Header (Welcome, Nama)
├── Status Progress
├── Pengumuman Section          ← HERE!
│   ├── Icon megaphone
│   ├── "Pengumuman Terbaru"
│   └── Konten (formatted)      ← format_pengumuman()
└── Quick Actions
```

**What Pendaftar Sees:**
- Bold text untuk judul/highlight
- Italic text untuk tanggal/catatan
- Clickable links
- Paragraf terstruktur

---

### 2. Halaman Public ❌
**File:** `resources/views/public/home.blade.php`

**Status:** ❌ Tidak ada dynamic pengumuman

**What's There:**
- Static text tentang "pengumuman hasil seleksi"
- Bukan data dari database
- Tidak perlu formatting

---

## 🎯 Yang Pendaftar Lihat

### Dashboard Pendaftar - Section Pengumuman:

**Visual:**
```
┌──────────────────────────────────────────────────┐
│  📢 Pengumuman Terbaru                           │
├──────────────────────────────────────────────────┤
│                                                  │
│  PENGUMUMAN PENTING [Bold]                      │
│                                                  │
│  Kepada seluruh calon peserta didik,            │
│                                                  │
│  Hasil seleksi akan diumumkan pada:             │
│  Tanggal: 15 Februari 2026 [Italic]            │
│  Waktu: 14.00 WIB [Italic]                      │
│                                                  │
│  Silakan cek hasil di: [Link - Clickable]       │
│  https://ppdb.mtsn2kotamalang.sch.id           │
│                                                  │
│  Terima kasih.                                  │
│                                                  │
└──────────────────────────────────────────────────┘
```

**Features:**
- ✅ Bold untuk judul/penting
- ✅ Italic untuk tanggal/detail
- ✅ Links bisa diklik, buka tab baru
- ✅ Paragraf terpisah dengan jelas
- ✅ Line breaks preserved

---

## 🔄 Flow: Admin → Pendaftar

```
┌─────────────────┐
│  1. ADMIN       │
│  Menu Pengumuman│
├─────────────────┤
│ Input:          │
│ *Judul Penting* │
│                 │
│ Detail info...  │
│ _Tanggal: ..._  │
│                 │
│ https://url.com │
└────────┬────────┘
         │
         │ Save ke Database
         │ (raw text dengan syntax)
         ▼
┌─────────────────┐
│  2. DATABASE    │
│  pengumumans    │
├─────────────────┤
│ konten:         │
│ "*Judul*\n\n    │
│  Detail..."     │
└────────┬────────┘
         │
         │ Load dari Database
         ▼
┌─────────────────┐
│ 3. HELPER       │
│ format_pengumuman()│
├─────────────────┤
│ Parse:          │
│ *text* → <strong>│
│ _text_ → <em>   │
│ URL → <a href>  │
└────────┬────────┘
         │
         │ Output HTML
         ▼
┌─────────────────┐
│ 4. PENDAFTAR    │
│ Dashboard       │
├─────────────────┤
│ Display:        │
│ JUDUL [Bold]    │
│ Detail...       │
│ Tanggal [Italic]│
│ [Link clickable]│
└─────────────────┘
```

---

## 📝 Syntax Guide untuk Admin

### Format Bold:
```
*teks yang ingin ditebalkan*
```
**Result:** **teks yang ingin ditebalkan**

### Format Italic:
```
_teks yang ingin dimiringkan_
```
**Result:** _teks yang ingin dimiringkan_

### Format Link:
```
https://ppdb.mtsn2kotamalang.sch.id
atau
https://example.com
```
**Result:** [https://ppdb.mtsn2kotamalang.sch.id](https://ppdb.mtsn2kotamalang.sch.id) (clickable)

### Line Break vs Paragraph:
```
Baris 1
Baris 2          ← Enter 1x = line break (<br>)

Paragraf 2       ← Enter 2x = new paragraph (<p>)
```

### Contoh Lengkap:
```
*PENGUMUMAN HASIL SELEKSI*

Kepada Yth. Calon Peserta Didik Baru MTsN 2 Kota Malang,

Dengan ini kami informasikan bahwa *hasil seleksi tahap 1* telah diumumkan.

Jadwal Pengumuman:
_Tanggal: 15 Februari 2026_
_Waktu: 14.00 WIB_
_Tempat: Website PPDB_

Silakan cek hasil Anda di:
https://ppdb.mtsn2kotamalang.sch.id/hasil-seleksi

Bagi yang _dinyatakan lulus_, mohon segera melakukan daftar ulang paling lambat 3 hari setelah pengumuman.

Terima kasih atas perhatiannya.

*Panitia PPDB MTsN 2 Kota Malang*
```

---

## 🔧 Technical Details

### XSS Protection:
```php
// All user input is escaped
htmlspecialchars($text, ENT_QUOTES, 'UTF-8')

// URLs are validated
preg_match(URL_PATTERN, $url)

// Links use security attributes
rel="noopener noreferrer"
```

### Performance:
- ✅ Lightweight parser
- ✅ No database queries
- ✅ Cached by PHP OpCache
- ✅ Minimal regex operations

### Compatibility:
- ✅ PHP 8.2+
- ✅ Laravel 12
- ✅ All modern browsers
- ✅ Mobile responsive

---

## 📦 Upload Checklist

**Files to Upload (5 files):**
- [ ] `app/Helpers/TextFormatter.php`
- [ ] `app/Helpers/helpers.php`
- [ ] `composer.json`
- [ ] `resources/views/livewire/admin/pengumuman.blade.php`
- [ ] `resources/views/livewire/pendaftar/dashboard.blade.php`

**After Upload:**
```bash
# SSH ke server
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

# Regenerate autoload (PENTING!)
composer dump-autoload

# Clear cache
php artisan view:clear
php artisan cache:clear

# Test helper
php artisan tinker
> format_pengumuman('*test* _italic_');
```

---

## ✅ Testing Checklist

### Test as Admin:
- [ ] Login sebagai Admin
- [ ] Menu Pengumuman → Tambah Pengumuman
- [ ] Lihat panduan formatting di bawah textarea
- [ ] Input dengan format: `*bold*`, `_italic_`, URL
- [ ] Save pengumuman

### Test as Pendaftar:
- [ ] Logout dari Admin
- [ ] Login sebagai Pendaftar
- [ ] Buka Dashboard
- [ ] Scroll ke section "Pengumuman Terbaru"
- [ ] Verifikasi:
  - ✅ Text bold tampil tebal
  - ✅ Text italic tampil miring
  - ✅ Link bisa diklik
  - ✅ Link buka tab baru
  - ✅ Paragraf terpisah dengan benar

---

## 🆚 Perbandingan: TextFormatter vs RTE

| Feature | TextFormatter<br>(Pengumuman) | RTE Trix<br>(Informasi Fields) |
|---------|-------------------------------|--------------------------------|
| **Input Method** | Ketik syntax manual | Visual toolbar |
| **Bold** | `*text*` | Click button |
| **Italic** | `_text_` | Click button |
| **Link** | Type full URL | Select text, add link |
| **Lists** | ❌ No | ✅ Yes (bulleted/numbered) |
| **Headings** | ❌ No | ✅ Yes (H1-H6) |
| **Learning Curve** | Low (markdown-like) | Lower (visual WYSIWYG) |
| **File Size** | Minimal | ~47KB (Trix) |
| **Use Case** | Simple announcements | Complex information |

---

## 📄 Related Documentation

**Pengumuman (TextFormatter):**
- ✅ `FIX_PENGUMUMAN_FORMATTING_DAN_DEBUG_WHATSAPP.md` (Session 2 Feb)
- ✅ `PENJELASAN_PENGUMUMAN_RTE_VS_TEXTFORMATTER.md` (This is comparison)
- ✅ `FILES_PENGUMUMAN_UNTUK_PENDAFTAR.md` (This file)

**RTE Implementation:**
- ✅ `COMPLETE_FIXES_8_FEB_2026.md` (Session 8 Feb)
- ✅ `IMPACT_UNTUK_PENDAFTAR_8_FEB_2026.md` (Session 8 Feb)

---

**Status:** ✅ DOCUMENTED
**Session:** 2 Feb 2026 (TextFormatter), NOT 8 Feb 2026 (RTE)
**Halaman Public:** ❌ Tidak ada dynamic pengumuman
