# RTE di Admin Daftar Ulang Settings - 8 Feb 2026

## 📋 Pertanyaan User
**"Apa saja yang berubah untuk perbaikan RTE di `/admin/daftar-ulang?activeTab=settings` pada informasi atau pengumuman?"**

---

## ✅ Jawaban Langsung

**File yang Berubah:** 1 file
- `resources/views/livewire/admin/daftar-ulang.blade.php`

**Jumlah RTE yang Ditambahkan:** 3 fields
1. **Informasi Madrasah** (Tab Settings Madrasah)
2. **Informasi Seragam** (Tab Settings Seragam)
3. **Informasi Ukuran** (Tab Settings Ukuran)

**Technology:** Trix Rich Text Editor (https://trix-editor.org/)

---

## 🎯 Lokasi di Admin

### URL Pattern:
```
/admin/daftar-ulang?activeTab=madrasah     → RTE #1
/admin/daftar-ulang?activeTab=seragam      → RTE #2
/admin/daftar-ulang?activeTab=ukuran       → RTE #3
```

### Menu Path:
```
Admin Dashboard
└── Daftar Ulang
    ├── Tab: Madrasah      → RTE Field: "Informasi untuk Pendaftar"
    ├── Tab: Seragam       → RTE Field: "Informasi untuk Pendaftar"
    └── Tab: Ukuran        → RTE Field: "Informasi untuk Pendaftar"
```

---

## 📂 File yang Berubah

### File: `resources/views/livewire/admin/daftar-ulang.blade.php`

**Total Perubahan:** 3 RTE implementations

---

## 🔧 Detail Perubahan per Tab

### 1. RTE untuk Informasi Madrasah ✅

**Lokasi:** Lines 299-329
**Tab:** Madrasah Settings
**Field Name:** `madrasahInfo`
**Border Color:** Indigo

#### BEFORE (Textarea Biasa):
```blade
<textarea
    wire:model="madrasahInfo"
    rows="5"
    class="w-full border border-gray-300 rounded-lg p-2"
    placeholder="Informasi untuk pendaftar...">
</textarea>
```

#### AFTER (Trix RTE):
```blade
<!-- Header -->
<div class="flex items-start">
    <svg class="w-5 h-5 mr-2 text-indigo-600 mt-1">...</svg>
    <div class="flex-1">
        <label class="block text-sm font-semibold text-indigo-900">
            Informasi/Pengumuman untuk Pendaftar
        </label>
        <p class="text-xs text-indigo-700 mt-1">
            Informasi ini akan ditampilkan di halaman daftar ulang madrasah pendaftar
        </p>
    </div>
</div>

<!-- Trix Rich Text Editor -->
<div wire:ignore x-data="{ trixId: 'madrasah-info-{{ uniqid() }}' }" x-init="
    $nextTick(() => {
        const hiddenInput = document.getElementById(trixId);
        const editor = document.querySelector(`trix-editor[input='${trixId}']`);

        if (editor) {
            // Set initial content
            editor.editor.loadHTML('{{ addslashes($madrasahInfo ?? '') }}');

            // Listen for changes
            editor.addEventListener('trix-change', function(e) {
                @this.set('madrasahInfo', editor.value);
            });
        }
    });
">
    <input :id="trixId" type="hidden" name="madrasahInfo" value="{{ $madrasahInfo ?? '' }}">
    <trix-editor
        :input="trixId"
        class="trix-content rounded-lg border border-indigo-300 shadow-sm focus-within:border-indigo-500 focus-within:ring focus-within:ring-indigo-200 focus-within:ring-opacity-50"
        placeholder="Tulis informasi, pengumuman, atau petunjuk untuk pendaftar terkait daftar ulang madrasah..."
    ></trix-editor>
</div>

<!-- Helper Text -->
<div class="mt-2 flex items-center text-xs text-indigo-600">
    <svg class="w-4 h-4 mr-1" fill="currentColor">...</svg>
    <span>Gunakan toolbar untuk format (Bold, Italic, Link, List, dll)</span>
</div>
```

**Visual:**
```
┌────────────────────────────────────────────────────┐
│ 📝 Informasi/Pengumuman untuk Pendaftar            │
│    Informasi ini akan ditampilkan di halaman...   │
├────────────────────────────────────────────────────┤
│ [B] [I] [U] [Link] [•] [1.] [<>]  ← Toolbar      │
├────────────────────────────────────────────────────┤
│ Tulis informasi, pengumuman, atau petunjuk...     │
│                                                    │
│                                                    │
│                                                    │
├────────────────────────────────────────────────────┤
│ ⓘ Gunakan toolbar untuk format (Bold, Italic...)  │
└────────────────────────────────────────────────────┘
```

**Styling:**
- Border: `border-indigo-300`
- Focus: `focus-within:border-indigo-500`
- Ring: `ring-indigo-200`

---

### 2. RTE untuk Informasi Seragam ✅

**Lokasi:** Lines 561-591
**Tab:** Seragam Settings
**Field Name:** `seragamInfo`
**Border Color:** Cyan

#### Implementation:
```blade
<!-- Header -->
<div class="flex items-start">
    <svg class="w-5 h-5 mr-2 text-cyan-600 mt-1">...</svg>
    <div class="flex-1">
        <label class="block text-sm font-semibold text-cyan-900">
            Informasi/Pengumuman untuk Pendaftar
        </label>
        <p class="text-xs text-cyan-700 mt-1">
            Informasi ini akan ditampilkan di halaman daftar ulang seragam pendaftar
        </p>
    </div>
</div>

<!-- Trix Rich Text Editor -->
<div wire:ignore x-data="{ trixId: 'seragam-info-{{ uniqid() }}' }" x-init="
    $nextTick(() => {
        const hiddenInput = document.getElementById(trixId);
        const editor = document.querySelector(`trix-editor[input='${trixId}']`);

        if (editor) {
            editor.editor.loadHTML('{{ addslashes($seragamInfo ?? '') }}');
            editor.addEventListener('trix-change', function(e) {
                @this.set('seragamInfo', editor.value);
            });
        }
    });
">
    <input :id="trixId" type="hidden" name="seragamInfo" value="{{ $seragamInfo ?? '' }}">
    <trix-editor
        :input="trixId"
        class="trix-content rounded-lg border border-cyan-300 shadow-sm focus-within:border-cyan-500 focus-within:ring focus-within:ring-cyan-200 focus-within:ring-opacity-50"
        placeholder="Tulis informasi, pengumuman, atau petunjuk untuk pendaftar terkait pembelian seragam..."
    ></trix-editor>
</div>

<!-- Helper Text -->
<div class="mt-2 flex items-center text-xs text-cyan-600">
    <svg class="w-4 h-4 mr-1" fill="currentColor">...</svg>
    <span>Gunakan toolbar untuk format (Bold, Italic, Link, List, dll)</span>
</div>
```

**Styling:**
- Border: `border-cyan-300`
- Focus: `focus-within:border-cyan-500`
- Ring: `ring-cyan-200`

**Placeholder:**
```
"Tulis informasi, pengumuman, atau petunjuk untuk pendaftar terkait pembelian seragam..."
```

---

### 3. RTE untuk Informasi Ukuran ✅

**Lokasi:** Lines 820-850
**Tab:** Ukuran Settings
**Field Name:** `ukuranInfo`
**Border Color:** Purple

#### Implementation:
```blade
<!-- Header -->
<div class="flex items-start">
    <svg class="w-5 h-5 mr-2 text-purple-600 mt-1">...</svg>
    <div class="flex-1">
        <label class="block text-sm font-semibold text-purple-900">
            Informasi/Pengumuman untuk Pendaftar
        </label>
        <p class="text-xs text-purple-700 mt-1">
            Informasi ini akan ditampilkan di halaman pemilihan ukuran seragam pendaftar
        </p>
    </div>
</div>

<!-- Trix Rich Text Editor -->
<div wire:ignore x-data="{ trixId: 'ukuran-info-{{ uniqid() }}' }" x-init="
    $nextTick(() => {
        const hiddenInput = document.getElementById(trixId);
        const editor = document.querySelector(`trix-editor[input='${trixId}']`);

        if (editor) {
            editor.editor.loadHTML('{{ addslashes($ukuranInfo ?? '') }}');
            editor.addEventListener('trix-change', function(e) {
                @this.set('ukuranInfo', editor.value);
            });
        }
    });
">
    <input :id="trixId" type="hidden" name="ukuranInfo" value="{{ $ukuranInfo ?? '' }}">
    <trix-editor
        :input="trixId"
        class="trix-content rounded-lg border border-purple-300 shadow-sm focus-within:border-purple-500 focus-within:ring focus-within:ring-purple-200 focus-within:ring-opacity-50"
        placeholder="Tulis informasi, pengumuman, atau petunjuk untuk pendaftar terkait pemilihan ukuran seragam..."
    ></trix-editor>
</div>

<!-- Helper Text -->
<div class="mt-2 flex items-center text-xs text-purple-600">
    <svg class="w-4 h-4 mr-1" fill="currentColor">...</svg>
    <span>Gunakan toolbar untuk format (Bold, Italic, Link, List, dll)</span>
</div>
```

**Styling:**
- Border: `border-purple-300`
- Focus: `focus-within:border-purple-500`
- Ring: `ring-purple-200`

**Placeholder:**
```
"Tulis informasi, pengumuman, atau petunjuk untuk pendaftar terkait pemilihan ukuran seragam..."
```

---

## 🎨 Design Pattern yang Digunakan

### Standard RTE Implementation Pattern:

```blade
<!-- Step 1: Header dengan icon & description -->
<div class="flex items-start">
    <svg class="w-5 h-5 mr-2 text-{color}-600 mt-1">
        <!-- Info icon -->
    </svg>
    <div class="flex-1">
        <label class="block text-sm font-semibold text-{color}-900">
            Informasi/Pengumuman untuk Pendaftar
        </label>
        <p class="text-xs text-{color}-700 mt-1">
            {description}
        </p>
    </div>
</div>

<!-- Step 2: Trix Editor dengan Alpine.js initialization -->
<div wire:ignore x-data="{ trixId: '{unique-id}' }" x-init="
    $nextTick(() => {
        const editor = document.querySelector(`trix-editor[input='${trixId}']`);
        if (editor) {
            // Load initial content
            editor.editor.loadHTML('{content}');

            // Sync changes to Livewire
            editor.addEventListener('trix-change', function(e) {
                @this.set('{fieldName}', editor.value);
            });
        }
    });
">
    <!-- Hidden input for Trix -->
    <input :id="trixId" type="hidden" name="{fieldName}" value="{value}">

    <!-- Trix Editor -->
    <trix-editor
        :input="trixId"
        class="trix-content rounded-lg border border-{color}-300 shadow-sm focus-within:border-{color}-500 focus-within:ring focus-within:ring-{color}-200 focus-within:ring-opacity-50"
        placeholder="{placeholder}"
    ></trix-editor>
</div>

<!-- Step 3: Helper text -->
<div class="mt-2 flex items-center text-xs text-{color}-600">
    <svg class="w-4 h-4 mr-1" fill="currentColor">...</svg>
    <span>Gunakan toolbar untuk format (Bold, Italic, Link, List, dll)</span>
</div>
```

---

## 📊 Comparison Table

| Feature | Tab Madrasah | Tab Seragam | Tab Ukuran |
|---------|-------------|-------------|------------|
| **Line Numbers** | 299-329 | 561-591 | 820-850 |
| **Field Name** | `madrasahInfo` | `seragamInfo` | `ukuranInfo` |
| **Trix ID** | `madrasah-info-{uid}` | `seragam-info-{uid}` | `ukuran-info-{uid}` |
| **Color Theme** | Indigo | Cyan | Purple |
| **Border** | `border-indigo-300` | `border-cyan-300` | `border-purple-300` |
| **Focus** | `indigo-500` | `cyan-500` | `purple-500` |
| **Placeholder** | "...daftar ulang madrasah..." | "...pembelian seragam..." | "...ukuran seragam..." |
| **Display Location** | Pendaftar: Tab Madrasah | Pendaftar: Tab Seragam | Pendaftar: Tab Ukuran |

---

## 🔍 Technical Details

### Alpine.js Integration:
```javascript
x-data="{ trixId: 'unique-id-{{ uniqid() }}' }"
```
- Generates unique ID untuk setiap editor
- Prevents conflicts dengan multiple Trix editors di halaman yang sama

### Livewire Integration:
```javascript
editor.addEventListener('trix-change', function(e) {
    @this.set('fieldName', editor.value);
});
```
- Real-time sync ke Livewire property
- Two-way data binding

### wire:ignore:
```blade
<div wire:ignore>
```
- Prevent Livewire dari re-rendering Trix editor
- Maintain editor state saat Livewire update

### Initial Content Loading:
```javascript
editor.editor.loadHTML('{{ addslashes($fieldName ?? '') }}');
```
- Load existing content dari database
- `addslashes()` untuk escape quotes

---

## 📝 Contoh Penggunaan Admin

### Input di Admin:
```
Tab: Madrasah Settings

[B] [I] [U] [Link] [•] [1.]  ← Toolbar Trix

📋 Petunjuk Daftar Ulang Madrasah

Silakan lengkapi berkas berikut:
• Fotokopi KK (1 lembar)
• Fotokopi Akta (1 lembar)
• Surat Pernyataan (download template)

⚠️ PENTING:
Berkas harus diunggah paling lambat 3 hari setelah dinyatakan lulus.

Info lebih lanjut: https://ppdb.mtsn2kotamalang.sch.id
```

---

## 🎯 Output untuk Pendaftar

### Pendaftar Melihat di:
**URL:** `/pendaftar/daftar-ulang?activeTab=madrasah`

**Display:**
```html
<div class="info-box">
    <h3>📋 Petunjuk Daftar Ulang Madrasah</h3>

    <p>Silakan lengkapi berkas berikut:</p>
    <ul>
        <li>Fotokopi KK (1 lembar)</li>
        <li>Fotokopi Akta (1 lembar)</li>
        <li>Surat Pernyataan (download template)</li>
    </ul>

    <p><strong>⚠️ PENTING:</strong><br>
    Berkas harus diunggah paling lambat 3 hari setelah dinyatakan lulus.</p>

    <p>Info lebih lanjut: <a href="https://ppdb.mtsn2kotamalang.sch.id" target="_blank">https://ppdb.mtsn2kotamalang.sch.id</a></p>
</div>
```

**Visual untuk Pendaftar:**
```
┌──────────────────────────────────────────────────┐
│ 📋 Petunjuk Daftar Ulang Madrasah [Bold]        │
│                                                  │
│ Silakan lengkapi berkas berikut:                │
│ • Fotokopi KK (1 lembar)                        │
│ • Fotokopi Akta (1 lembar)                      │
│ • Surat Pernyataan (download template)          │
│                                                  │
│ ⚠️ PENTING: [Bold]                              │
│ Berkas harus diunggah paling lambat 3 hari     │
│ setelah dinyatakan lulus.                       │
│                                                  │
│ Info lebih lanjut: [Link Clickable]             │
│ https://ppdb.mtsn2kotamalang.sch.id            │
└──────────────────────────────────────────────────┘
```

---

## ✨ Features yang Tersedia

### Trix Toolbar:
1. **Bold** - Teks tebal
2. **Italic** - Teks miring
3. **Underline** - Garis bawah (if enabled)
4. **Strike** - Coret (if enabled)
5. **Link** - Hyperlink
6. **Bullet List** - List dengan bullet
7. **Numbered List** - List dengan nomor
8. **Heading** - H1 (if enabled)
9. **Quote** - Block quote (if enabled)
10. **Code** - Code block (if enabled)

### Default Enabled:
- ✅ Bold
- ✅ Italic
- ✅ Link
- ✅ Bullet List
- ✅ Numbered List
- ✅ Heading (H1)

---

## 🆚 Before & After Comparison

### BEFORE (Textarea):
```
┌────────────────────────────────┐
│ Informasi untuk Pendaftar      │
├────────────────────────────────┤
│ Silakan lengkapi berkas...     │
│                                │
│                                │
│                                │
│                                │
└────────────────────────────────┘
```

**Masalah:**
- ❌ Tidak ada formatting
- ❌ Tidak ada struktur
- ❌ Sulit highlight informasi penting
- ❌ Tidak ada bullet points
- ❌ Links tidak clickable

---

### AFTER (Trix RTE):
```
┌────────────────────────────────────────┐
│ 📝 Informasi/Pengumuman untuk Pendaftar│
│    Informasi ini akan ditampilkan...   │
├────────────────────────────────────────┤
│ [B] [I] [Link] [•] [1.] [H1]  ← Toolbar│
├────────────────────────────────────────┤
│ 📋 Petunjuk Daftar Ulang [Bold+Emoji] │
│                                        │
│ Silakan lengkapi:                      │
│ • Fotokopi KK [Bullet]                 │
│ • Fotokopi Akta [Bullet]               │
│                                        │
│ ⚠️ PENTING: [Bold]                     │
│ Deadline 3 hari                        │
│                                        │
│ Link: [Clickable]                      │
├────────────────────────────────────────┤
│ ⓘ Gunakan toolbar untuk format...     │
└────────────────────────────────────────┘
```

**Keuntungan:**
- ✅ Professional formatting
- ✅ Clear structure dengan heading
- ✅ Highlight dengan bold/italic
- ✅ Organized lists (bullet/numbered)
- ✅ Clickable links
- ✅ Visual toolbar WYSIWYG
- ✅ Real-time preview

---

## 🎯 Use Cases

### 1. Informasi Madrasah:
```
📋 Dokumen yang Diperlukan

Wajib Upload:
• Fotokopi Kartu Keluarga
• Fotokopi Akta Kelahiran
• Surat Pernyataan (download template)

Catatan:
Upload paling lambat 3 hari setelah pengumuman

Bantuan: 0812-xxxx-xxxx
```

### 2. Informasi Seragam:
```
🎽 Panduan Pembelian Seragam

Opsi 1: Beli Paket Seragam
• Pilih paket yang tersedia
• Upload bukti transfer
• Seragam dikirim ke alamat

Opsi 2: Beli Sendiri
• Lihat spesifikasi di tab Ukuran
• Beli sesuai standar madrasah
• Bawa bukti pembelian saat daftar ulang

Info: https://seragam.mtsn2kotamalang.sch.id
```

### 3. Informasi Ukuran:
```
📏 Cara Mengisi Ukuran Seragam

Langkah-langkah:
1. Ukur tinggi badan (cm)
2. Ukur lingkar dada (cm)
3. Lihat tabel ukuran di bawah
4. Pilih ukuran yang sesuai

⚠️ Tips Penting:
• Jika ragu, pilih ukuran lebih besar
• Ukuran tidak bisa diubah setelah konfirmasi
• Pastikan ukuran akurat

Video panduan: https://youtube.com/watch?v=...
```

---

## 📦 Backend (Livewire Component)

### Properties yang Perlu Ada:
```php
// app/Livewire/Admin/DaftarUlang.php

public $madrasahInfo = '';
public $seragamInfo = '';
public $ukuranInfo = '';

protected $rules = [
    'madrasahInfo' => 'nullable|string',
    'seragamInfo' => 'nullable|string',
    'ukuranInfo' => 'nullable|string',
];

public function mount()
{
    $settings = DaftarUlangSetting::first();
    $this->madrasahInfo = $settings->madrasah_info ?? '';
    $this->seragamInfo = $settings->seragam_info ?? '';
    $this->ukuranInfo = $settings->ukuran_info ?? '';
}

public function save()
{
    $this->validate();

    DaftarUlangSetting::updateOrCreate([], [
        'madrasah_info' => $this->madrasahInfo,
        'seragam_info' => $this->seragamInfo,
        'ukuran_info' => $this->ukuranInfo,
    ]);

    session()->flash('success', 'Settings berhasil disimpan');
}
```

---

## ✅ Testing Checklist

### Test as Admin:

**Tab Madrasah:**
- [ ] Login sebagai Admin
- [ ] Buka `/admin/daftar-ulang?activeTab=madrasah`
- [ ] Scroll ke field "Informasi/Pengumuman untuk Pendaftar"
- [ ] Verify Trix toolbar visible (Bold, Italic, Link, List)
- [ ] Type text, format dengan bold
- [ ] Add bullet list
- [ ] Add link: `https://example.com`
- [ ] Click Save
- [ ] Refresh page
- [ ] Verify content preserved dengan formatting

**Tab Seragam:**
- [ ] Switch to tab Seragam
- [ ] Scroll ke field "Informasi/Pengumuman untuk Pendaftar"
- [ ] Verify Trix toolbar visible (cyan theme)
- [ ] Add formatted content
- [ ] Save & verify

**Tab Ukuran:**
- [ ] Switch to tab Ukuran
- [ ] Scroll ke field "Informasi/Pengumuman untuk Pendaftar"
- [ ] Verify Trix toolbar visible (purple theme)
- [ ] Add formatted content with numbered list
- [ ] Save & verify

### Test as Pendaftar:

- [ ] Logout dari Admin
- [ ] Login sebagai Pendaftar
- [ ] Navigate to `/pendaftar/daftar-ulang?activeTab=madrasah`
- [ ] Verify informasi displayed dengan formatting
- [ ] Check: Bold text appears bold
- [ ] Check: Lists appear as bullet/numbered
- [ ] Check: Links are clickable
- [ ] Switch to tab Seragam, verify info
- [ ] Switch to tab Ukuran, verify info

---

## 📊 Summary

### What Changed:
- ✅ **1 file modified:** `resources/views/livewire/admin/daftar-ulang.blade.php`
- ✅ **3 RTE fields added:** madrasahInfo, seragamInfo, ukuranInfo
- ✅ **3 locations:** Tab Madrasah, Tab Seragam, Tab Ukuran
- ✅ **Technology:** Trix Editor with Alpine.js & Livewire

### Impact:
- ✅ Admin dapat format informasi dengan professional
- ✅ Pendaftar mendapat informasi yang lebih jelas
- ✅ Consistent pattern across all 3 tabs
- ✅ Color-coded untuk visual distinction

### Benefits:
- 📈 Better communication Admin → Pendaftar
- 🎨 Professional appearance
- ✅ Structured information dengan lists
- 🔗 Clickable links
- 📝 Easy to highlight important points

---

## 🔗 Related Documentation

**Session 8 Feb 2026:**
- `COMPLETE_FIXES_8_FEB_2026.md` - Full technical documentation
- `IMPACT_UNTUK_PENDAFTAR_8_FEB_2026.md` - User impact analysis
- `RTE_ADMIN_DAFTAR_ULANG_SETTINGS_8_FEB_2026.md` - This file

**Upload:**
- `upload_fixes_8_feb_2026_complete.bat` - Includes this file

---

**Status:** ✅ IMPLEMENTED
**Date:** 8 Feb 2026
**File Modified:** 1 file
**RTE Fields Added:** 3 fields
**Ready for Production:** Yes
