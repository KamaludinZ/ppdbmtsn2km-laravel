# Penjelasan: Pengumuman - RTE vs TextFormatter

## 🔍 Pertanyaan User
**"Apakah bisa ditunjukan file mana yang berubah untuk perbaikan di pendaftar tentang perbaikan RTE di pengumuman yang tampil di pendaftar dan juga di halaman pengumuman public?"**

---

## ⚠️ PENTING: Klarifikasi

### Pengumuman TIDAK Menggunakan RTE Trix Editor!

**Session 8 Feb 2026 (baru selesai):**
- ❌ TIDAK menambahkan RTE di pengumuman
- ✅ Menambahkan RTE di 10 lokasi informasi fields
- ✅ RTE hanya untuk: Daftar Ulang (madrasah/seragam/ukuran) & Tes Psikologi

**Session 2 Feb 2026 (sebelumnya):**
- ✅ Menambahkan **TextFormatter** untuk pengumuman
- ✅ Menggunakan markdown-like syntax (`*bold*`, `_italic_`)
- ✅ Bukan Trix Editor, tapi custom parser

---

## 🎯 Dua Sistem Berbeda

### 1. **RTE (Trix Editor)** - Session 8 Feb 2026
**Lokasi:** 10 fields informasi

**Technology:** Trix Editor (WYSIWYG)
- Toolbar visual (Bold, Italic, Link, List, Heading buttons)
- Real-time preview
- HTML output
- Rich editing experience

**Tidak Termasuk:**
- ❌ Pengumuman Admin → Pendaftar
- ❌ Pengumuman Public

**Files:**
- `resources/views/livewire/admin/daftar-ulang.blade.php`
- `resources/views/livewire/panitia/partials/settings-madrasah-tab.blade.php`
- `resources/views/livewire/panitia-seragam/partials/settings-tab.blade.php`
- `resources/views/livewire/admin/partials/tes-psikologi-settings.blade.php`
- `resources/views/livewire/admin/partials/tes-psikologi-survey.blade.php`
- `resources/views/livewire/panitia/partials/tes-psikologi-survey.blade.php`

---

### 2. **TextFormatter** - Session 2 Feb 2026
**Lokasi:** Pengumuman (Admin → Pendaftar & Public)

**Technology:** Custom PHP Parser
- Markdown-like syntax
- No toolbar, ketik manual
- Simple formatting
- Lightweight

**Syntax:**
```
*teks* → teks tebal
_teks_ → teks miring
https://... → clickable link
Enter 2x → paragraf baru
```

**Files yang Berubah (Session 2 Feb):**

#### A. Backend Files:
1. **`app/Helpers/TextFormatter.php`** (NEW)
   - Custom parser untuk convert markdown-like syntax ke HTML
   - Methods: `formatPengumuman()`, `stripFormatting()`, `toPlainText()`

2. **`app/Helpers/helpers.php`** (NEW)
   - Global helper functions
   - `format_pengumuman()`, `strip_formatting()`

3. **`composer.json`** (MODIFIED)
   - Autoload helpers.php
   ```json
   "autoload": {
       "files": [
           "app/Helpers/helpers.php"
       ]
   }
   ```

#### B. View Files:
4. **`resources/views/livewire/admin/pengumuman.blade.php`** (MODIFIED)
   - Form admin untuk buat/edit pengumuman
   - Textarea dengan panduan formatting
   - Placeholder dengan contoh syntax
   - Font monospace untuk editor

5. **`resources/views/livewire/pendaftar/dashboard.blade.php`** (MODIFIED)
   - Display pengumuman di dashboard pendaftar
   - Line ~976-978
   ```blade
   <!-- BEFORE -->
   {!! $pengumuman->konten !!}

   <!-- AFTER -->
   {!! format_pengumuman($pengumuman->konten) !!}
   ```

6. **`resources/views/public/home.blade.php`** (POSSIBLY MODIFIED)
   - Perlu cek apakah halaman public juga menampilkan pengumuman
   - Jika ya, perlu gunakan `format_pengumuman()` juga

---

## 📂 File Lokasi Detail

### Files dari Session 2 Feb (Pengumuman TextFormatter):

**Backend:**
```
app/
├── Helpers/
│   ├── TextFormatter.php          ← NEW - Custom parser
│   └── helpers.php                ← NEW - Global helpers
composer.json                       ← MODIFIED - Autoload helpers
```

**Views:**
```
resources/views/
├── livewire/
│   ├── admin/
│   │   └── pengumuman.blade.php   ← MODIFIED - Form admin dengan panduan
│   └── pendaftar/
│       └── dashboard.blade.php    ← MODIFIED - Display formatted
└── public/
    └── home.blade.php             ← TO CHECK - Halaman public
```

---

## 🎨 Visual Comparison

### TextFormatter (Pengumuman):
**Admin Input:**
```
*PENGUMUMAN PENTING*

Hasil seleksi akan diumumkan pada:
_Tanggal: 15 Feb 2026_

Info: https://ppdb.mtsn2kotamalang.sch.id
```

**Pendaftar Output:**
```html
<strong>PENGUMUMAN PENTING</strong>

<p>Hasil seleksi akan diumumkan pada:<br>
<em>Tanggal: 15 Feb 2026</em></p>

<p>Info: <a href="https://ppdb.mtsn2kotamalang.sch.id" target="_blank">https://ppdb.mtsn2kotamalang.sch.id</a></p>
```

---

### RTE Trix Editor (Informasi Fields):
**Admin Input:**
[Toolbar visual: Bold, Italic, Link, List buttons]
Admin klik toolbar → langsung format

**Pendaftar Output:**
```html
<strong>PENGUMUMAN PENTING</strong>
<p>Hasil seleksi akan diumumkan pada:</p>
<ul>
  <li>Tanggal: 15 Feb 2026</li>
  <li>Waktu: 14.00 WIB</li>
</ul>
<p>Info: <a href="https://ppdb.mtsn2kotamalang.sch.id">Link Pendaftaran</a></p>
```

---

## 🤔 Kenapa Pengumuman Tidak Pakai RTE?

### Alasan:
1. **Already Implemented** - TextFormatter sudah ada dan bekerja baik
2. **Different Use Case** - Pengumuman lebih simple, tidak perlu toolbar kompleks
3. **Consistency** - TextFormatter sudah established di session 2 Feb
4. **Lightweight** - TextFormatter lebih ringan untuk use case pengumuman

### RTE Lebih Cocok Untuk:
- ✅ Informasi panjang dengan struktur kompleks
- ✅ Multiple sections dengan heading
- ✅ Lists (bulleted/numbered)
- ✅ Admin butuh visual feedback saat edit

### TextFormatter Lebih Cocok Untuk:
- ✅ Pengumuman singkat
- ✅ Simple formatting (bold, italic, link)
- ✅ Quick input tanpa toolbar
- ✅ Markdown-like familiar syntax

---

## 📋 Summary untuk Pendaftar

### Yang Pendaftar Lihat di Dashboard:

**1. Pengumuman (TextFormatter)**
- ✅ Text formatting: Bold, Italic
- ✅ Clickable links
- ✅ Paragraf terstruktur
- 📍 **File:** `resources/views/livewire/pendaftar/dashboard.blade.php`

**2. Informasi Daftar Ulang (RTE Trix)**
- ✅ Heading, Bold, Italic
- ✅ Bulleted/Numbered lists
- ✅ Links dengan custom text
- 📍 **Location:** Tab Madrasah/Seragam/Ukuran di halaman daftar ulang

**3. Informasi Tes Psikologi (RTE Trix)**
- ✅ Full formatting dengan toolbar
- ✅ Complex structure
- 📍 **Location:** Halaman tes psikologi

---

## 🔧 Jika Ingin Ganti Pengumuman ke RTE

### Perlu Perubahan:

**1. Admin Pengumuman Form**
```blade
<!-- File: resources/views/livewire/admin/pengumuman.blade.php -->

<!-- BEFORE (TextFormatter) -->
<textarea wire:model="konten" rows="10" class="font-mono">
    Panduan: *bold* _italic_
</textarea>

<!-- AFTER (RTE) -->
<div wire:ignore x-data="{ trixId: 'pengumuman-{{ uniqid() }}' }">
    <input :id="trixId" type="hidden" wire:model="konten">
    <trix-editor :input="trixId" class="trix-content"></trix-editor>
</div>
```

**2. Pendaftar Dashboard Display**
```blade
<!-- File: resources/views/livewire/pendaftar/dashboard.blade.php -->

<!-- BEFORE (TextFormatter) -->
{!! format_pengumuman($pengumuman->konten) !!}

<!-- AFTER (RTE) -->
{!! $pengumuman->konten !!}
<!-- Karena RTE sudah output HTML -->
```

**3. Public Homepage Display**
```blade
<!-- File: resources/views/public/home.blade.php -->

<!-- Check if using TextFormatter -->
{!! format_pengumuman($pengumuman->konten) !!}

<!-- If switch to RTE -->
{!! $pengumuman->konten !!}
```

### Trade-offs:

**Ganti ke RTE:**
- ✅ PRO: Visual toolbar, easier for non-technical admin
- ✅ PRO: More formatting options (lists, headings)
- ❌ CON: Existing pengumuman perlu migrasi/re-format
- ❌ CON: Heavier (Trix Editor assets)

**Keep TextFormatter:**
- ✅ PRO: Sudah ada, backward compatible
- ✅ PRO: Lightweight, fast
- ✅ PRO: Simple syntax, familiar (markdown-like)
- ❌ CON: No visual toolbar
- ❌ CON: Limited formatting options

---

## 📊 Current Status

### Session 2 Feb 2026 (Implemented):
✅ **TextFormatter untuk Pengumuman**
- `app/Helpers/TextFormatter.php`
- `app/Helpers/helpers.php`
- `composer.json`
- `resources/views/livewire/admin/pengumuman.blade.php`
- `resources/views/livewire/pendaftar/dashboard.blade.php`

### Session 8 Feb 2026 (Just Completed):
✅ **RTE Trix untuk Informasi Fields (10 lokasi)**
- Admin DaftarUlang: 3 fields
- Panitia Settings: 3 fields
- Admin Tes Psikologi: 3 fields
- Panitia Tes Psikologi: 1 field

❌ **Pengumuman TIDAK di-upgrade ke RTE** (masih TextFormatter)

---

## 🎯 Rekomendasi

### Keep Both Systems:
**TextFormatter untuk Pengumuman:**
- Sudah implemented dan working
- Simple use case, tidak perlu RTE
- Lightweight

**RTE untuk Informasi Fields:**
- Complex content dengan struktur
- Butuh formatting advanced
- Better UX untuk long-form content

### Ini adalah Best Practice:
- ✅ Gunakan tool yang sesuai use case
- ✅ Tidak semua field perlu RTE
- ✅ Balance antara features vs complexity

---

## 📄 Related Documentation

**Pengumuman (TextFormatter):**
- `FIX_PENGUMUMAN_FORMATTING_DAN_DEBUG_WHATSAPP.md` (Session 2 Feb)

**RTE Implementation:**
- `COMPLETE_FIXES_8_FEB_2026.md` (Session 8 Feb)
- `IMPACT_UNTUK_PENDAFTAR_8_FEB_2026.md` (Session 8 Feb)

---

**Kesimpulan:**
Pengumuman menggunakan **TextFormatter** (session 2 Feb), bukan **RTE Trix** (session 8 Feb). Kedua sistem berbeda dan digunakan untuk use case yang berbeda. Keduanya sama-sama memberikan formatting yang baik untuk pendaftar.
