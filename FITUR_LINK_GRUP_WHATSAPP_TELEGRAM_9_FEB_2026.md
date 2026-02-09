# Fitur Link Grup WhatsApp/Telegram - 9 Feb 2026

## 🎯 Overview

Fitur ini memungkinkan Admin untuk menambahkan link grup WhatsApp atau Telegram yang akan ditampilkan sebagai tombol **"Bergabung ke Grup"** di:
1. Dashboard Pendaftar (di sebelah tombol "Cetak Kartu Ujian")
2. Halaman Cetak Kartu Tes Seleksi (di sebelah tombol "Cetak Kartu")

Admin dapat mengaktifkan/menonaktifkan tombol ini melalui toggle switch di pengaturan.

---

## 📝 Files Modified: 5 files

### Backend Files (3 files):

1. **app/Livewire/Admin/PengaturanKartu.php**
2. **app/Livewire/Pendaftar/Dashboard.php**
3. **app/Livewire/Pendaftar/KartuTesSeleksi.php**

### Frontend Files (3 files):

1. **resources/views/livewire/admin/pengaturan-kartu.blade.php**
2. **resources/views/livewire/pendaftar/dashboard.blade.php**
3. **resources/views/livewire/pendaftar/kartu-tes-seleksi.blade.php**

---

## 🔧 Technical Implementation

### 1. File: `app/Livewire/Admin/PengaturanKartu.php`

#### Changes Made:

**Location 1: Add Properties (Lines 55-57)**

```php
// Catatan Kartu Ujian
public $catatan_kartu_ujian = '';

// Link Grup properties
public $link_grup_url = '';
public $link_grup_enabled = false;
```

**Why:** Store link grup URL and enabled status as component properties

---

**Location 2: Load Settings in mount() (Lines 78-80)**

```php
public function mount()
{
    // Load catatan kartu ujian from settings
    $this->catatan_kartu_ujian = Setting::get('catatan_kartu_ujian', '');

    // Load link grup settings
    $this->link_grup_url = Setting::get('link_grup_url', '');
    $this->link_grup_enabled = Setting::get('link_grup_enabled', false);
}
```

**Why:** Load saved settings from database when component mounts

---

**Location 3: Add Save Method (Lines 93-106)**

```php
public function saveLinkGrup()
{
    $this->validate([
        'link_grup_url' => 'nullable|url|max:500',
        'link_grup_enabled' => 'boolean',
    ], [
        'link_grup_url.url' => 'Link grup harus berupa URL yang valid (contoh: https://chat.whatsapp.com/...)',
        'link_grup_url.max' => 'Link grup maksimal 500 karakter',
    ]);

    Setting::set('link_grup_url', $this->link_grup_url);
    Setting::set('link_grup_enabled', $this->link_grup_enabled);
    session()->flash('message', 'Link grup berhasil disimpan.');
}
```

**Why:**
- Validate URL format (must be valid URL)
- Validate max length (500 characters)
- Save both URL and enabled status to database
- Show success message

---

### 2. File: `app/Livewire/Pendaftar/Dashboard.php`

#### Changes Made:

**Location 1: Import Setting Model (Line 6)**

```php
use App\Models\Pengumuman as PengumumanModel;
use App\Models\Setting;
use App\Models\TimelinePendaftaran;
```

**Why:** Need Setting model to load link grup settings

---

**Location 2: Add Properties (Lines 22-24)**

```php
public $pengumumanPendaftar;
public $tesPsikologiAktif = false;
public $tesPsikologiData = null;

// Link Grup
public $linkGrupUrl = '';
public $linkGrupEnabled = false;
```

**Why:** Store link grup data for use in blade view

---

**Location 3: Load Settings in mount() (Lines 87-89)**

```php
// Load tes psikologi data if active
if ($this->tesPsikologiAktif) {
    $this->tesPsikologiData = TesPsikologiPendaftar::firstOrCreate(
        ['pendaftar_id' => $this->pendaftar->id],
        ['status_verifikasi' => 'belum_upload']
    );
}

// Load link grup settings
$this->linkGrupUrl = Setting::get('link_grup_url', '');
$this->linkGrupEnabled = Setting::get('link_grup_enabled', false);

$this->calculateProgress();
```

**Why:** Load link grup settings fresh from database every time dashboard loads

---

### 3. File: `app/Livewire/Pendaftar/KartuTesSeleksi.php`

#### Changes Made:

**Location 1: Add Properties (Lines 21-23)**

```php
public $pendaftar;
public $appAcronym;
public $alamatLengkap;

// Link Grup
public $linkGrupUrl = '';
public $linkGrupEnabled = false;
```

**Why:** Store link grup data for kartu tes seleksi page

---

**Location 2: Load Settings in mount() (Lines 56-58)**

```php
// Load app acronym
$appName = Setting::get('app_name', 'PPDB');
// ... acronym logic ...

// Load link grup settings
$this->linkGrupUrl = Setting::get('link_grup_url', '');
$this->linkGrupEnabled = Setting::get('link_grup_enabled', false);

$this->generateAlamatLengkap();
```

**Why:** Load link grup settings for kartu tes page

---

### 4. File: `resources/views/livewire/admin/pengaturan-kartu.blade.php`

#### Changes Made:

**Location 1: Add Tab Button (Lines 31-35)**

```blade
<button
    wire:click="$set('activeTab', 'catatan-kartu')"
    class="py-2 px-3 md:px-4 border-b-2 font-medium text-xs md:text-sm {{ $activeTab === 'catatan-kartu' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
    Catatan Kartu
</button>
<button
    wire:click="$set('activeTab', 'link-grup')"
    class="py-2 px-3 md:px-4 border-b-2 font-medium text-xs md:text-sm {{ $activeTab === 'link-grup' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
    Link Grup
</button>
<button
    wire:click="$set('activeTab', 'preview-kartu')"
    class="py-2 px-3 md:px-4 border-b-2 font-medium text-xs md:text-sm {{ $activeTab === 'preview-kartu' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
    Preview Kartu
</button>
```

**Why:** Add new "Link Grup" tab button after "Catatan Kartu" tab

---

**Location 2: Add Tab Content (Lines 323-433)**

Full tab content with:

1. **Info Box:** Explains where the button will appear
2. **Toggle Switch:** Enable/disable link grup visibility
3. **URL Input:** Field for WhatsApp/Telegram group link
4. **Preview Link:** Shows clickable preview of the URL
5. **Save Button:** Submit form to save settings
6. **Button Preview:** Shows how the buttons will look on dashboard

**Key Features:**

- **Toggle Switch:** Modern sliding toggle (Tailwind CSS peer classes)
- **URL Validation:** Client-side URL format validation
- **Preview:** Real-time preview of entered link
- **Button Preview:** Shows exact visual appearance on dashboard
- **Conditional Display:** Warning message if link grup not enabled

```blade
<!-- Link Grup Tab -->
@if($activeTab === 'link-grup')
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Link Grup WhatsApp/Telegram</h3>

        <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <p class="text-sm text-blue-800">
                <strong>Info:</strong> Link grup akan ditampilkan sebagai tombol "Bergabung ke Grup"
                di dashboard pendaftar (di sebelah tombol Cetak Kartu) dan di halaman cetak kartu tes seleksi.
                Tombol hanya muncul jika link grup diaktifkan.
            </p>
        </div>

        <form wire:submit.prevent="saveLinkGrup">
            <!-- Toggle Enable/Disable -->
            <div class="mb-6">
                <label class="flex items-center cursor-pointer">
                    <div class="relative">
                        <input type="checkbox" wire:model="link_grup_enabled" class="sr-only peer">
                        <div class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4
                                    peer-focus:ring-emerald-300 rounded-full peer
                                    peer-checked:after:translate-x-full peer-checked:after:border-white
                                    after:content-[''] after:absolute after:top-0.5 after:left-[4px]
                                    after:bg-white after:border-gray-300 after:border after:rounded-full
                                    after:h-6 after:w-6 after:transition-all peer-checked:bg-emerald-600">
                        </div>
                    </div>
                    <span class="ml-3 text-sm font-medium text-gray-700">
                        Aktifkan Link Grup (Tombol "Bergabung ke Grup" akan muncul di dashboard dan kartu tes)
                    </span>
                </label>
            </div>

            <!-- URL Input -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    URL Link Grup
                    @if($link_grup_enabled)
                        <span class="text-red-500">*</span>
                    @endif
                </label>
                <input
                    type="url"
                    wire:model="link_grup_url"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg
                           focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                    placeholder="https://chat.whatsapp.com/... atau https://t.me/...">
                @error('link_grup_url') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Preview & Save Button -->
            <!-- ... (see full code for preview and button preview sections) -->

        </form>
    </div>
@endif
```

**Why:**
- Provide complete UI for admin to manage link grup
- Show real-time preview of link and buttons
- Give clear instructions on where buttons appear
- Validate input before saving

---

### 5. File: `resources/views/livewire/pendaftar/dashboard.blade.php`

#### Changes Made:

**Location: Add "Bergabung ke Grup" Button (Lines 224-240)**

```blade
<div class="flex items-center gap-4">
    <a href="/pendaftar/kartu-tes-seleksi" class="bg-white text-blue-600 px-8 py-3 rounded-lg
       font-bold text-lg hover:bg-blue-50 transition transform hover:scale-105 shadow-lg flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
        </svg>
        Cetak Kartu Ujian
    </a>
    @if($linkGrupEnabled && $linkGrupUrl)
        <a href="{{ $linkGrupUrl }}" target="_blank" rel="noopener noreferrer"
           class="bg-green-500 hover:bg-green-600 text-white px-8 py-3 rounded-lg
                  font-bold text-lg transition transform hover:scale-105 shadow-lg flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"></path>
                <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"></path>
            </svg>
            Bergabung ke Grup
        </a>
    @endif
</div>
```

**Why:**
- Display "Bergabung ke Grup" button next to "Cetak Kartu Ujian" button
- Only show if link grup is enabled AND URL is not empty
- Open link in new tab (`target="_blank"`)
- Green color to distinguish from blue "Cetak Kartu" button
- Chat icon to indicate communication/group

---

### 6. File: `resources/views/livewire/pendaftar/kartu-tes-seleksi.blade.php`

#### Changes Made:

**Location: Add "Bergabung ke Grup" Button (Lines 5-24)**

```blade
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-gray-800">Kartu Tanda Peserta Tes Seleksi</h2>
        <div class="flex items-center gap-3 print:hidden">
            <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded
                                                     hover:bg-blue-700 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                Cetak Kartu
            </button>
            @if($linkGrupEnabled && $linkGrupUrl)
                <a href="{{ $linkGrupUrl }}" target="_blank" rel="noopener noreferrer"
                   class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"></path>
                        <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"></path>
                    </svg>
                    Bergabung ke Grup
                </a>
            @endif
        </div>
    </div>
```

**Why:**
- Wrap buttons in flex container for horizontal alignment
- Add `print:hidden` to button container so buttons don't show when printing
- Same conditional logic as dashboard
- Same styling as dashboard for consistency

---

## 📊 Database Storage

**Settings Table:** Link grup data is stored using Setting model

```php
Setting::set('link_grup_url', 'https://chat.whatsapp.com/ABC123');
Setting::set('link_grup_enabled', true);
```

**Keys:**
- `link_grup_url` (string) - The WhatsApp/Telegram group link URL
- `link_grup_enabled` (boolean) - Whether to show the button or not

---

## 🧪 Testing Checklist

### Test 1: Admin Can Configure Link Grup

1. Login sebagai Admin
2. Buka `/admin/pengaturan-kartu?activeTab=link-grup`
3. Verify tab "Link Grup" tampil di navigation
4. Verify toggle switch dan URL input tampil
5. Toggle switch ON
6. Input URL: `https://chat.whatsapp.com/test123`
7. Klik "Simpan Link Grup"
8. Verify success message: "Link grup berhasil disimpan"
9. Refresh page
10. Verify:
    - Toggle masih ON
    - URL masih ada: `https://chat.whatsapp.com/test123`
    - Preview link tampil dan clickable
    - Button preview tampil

### Test 2: URL Validation

1. Toggle ON
2. Input invalid URL: `not-a-url`
3. Klik "Simpan Link Grup"
4. Verify error: "Link grup harus berupa URL yang valid"
5. Input valid URL: `https://t.me/testgroup`
6. Klik "Simpan Link Grup"
7. Verify success

### Test 3: Button Appears in Dashboard (When Enabled)

1. Login sebagai Pendaftar (dengan status `data_disetujui`)
2. Buka `/pendaftar/dashboard`
3. Scroll to "Petunjuk Cetak Kartu Ujian" section
4. Verify 2 buttons tampil:
    - "Cetak Kartu Ujian" (blue)
    - "Bergabung ke Grup" (green)
5. Click "Bergabung ke Grup"
6. Verify:
    - Opens in new tab
    - URL matches configured link

### Test 4: Button Does NOT Appear When Disabled

1. Login sebagai Admin
2. Buka `/admin/pengaturan-kartu?activeTab=link-grup`
3. Toggle OFF
4. Klik "Simpan Link Grup"
5. Logout
6. Login sebagai Pendaftar
7. Buka `/pendaftar/dashboard`
8. Verify: ONLY "Cetak Kartu Ujian" button tampil
9. Verify: NO "Bergabung ke Grup" button

### Test 5: Button in Kartu Tes Seleksi Page

1. Ensure link grup enabled and URL configured
2. Login sebagai Pendaftar
3. Buka `/pendaftar/kartu-tes-seleksi`
4. Verify 2 buttons di top-right corner:
    - "Cetak Kartu" (blue)
    - "Bergabung ke Grup" (green)
5. Click "Bergabung ke Grup"
6. Verify opens in new tab with correct URL

### Test 6: Print Behavior

1. Buka `/pendaftar/kartu-tes-seleksi`
2. Click "Cetak Kartu" button
3. Print preview opens
4. Verify: Both buttons (Cetak Kartu & Bergabung ke Grup) are HIDDEN in print view
5. Only kartu content visible

### Test 7: Empty URL Behavior

1. Admin: Enable toggle but leave URL empty
2. Save
3. Pendaftar: Check dashboard
4. Verify: NO "Bergabung ke Grup" button appears (because URL is empty)

### Test 8: WhatsApp Link

1. Admin: Input `https://chat.whatsapp.com/ABC123DEF456`
2. Save
3. Pendaftar: Click "Bergabung ke Grup"
4. Verify: Opens WhatsApp group join page

### Test 9: Telegram Link

1. Admin: Input `https://t.me/testgroup`
2. Save
3. Pendaftar: Click "Bergabung ke Grup"
4. Verify: Opens Telegram group join page

### Test 10: Long URL Handling

1. Admin: Input very long URL (500 characters)
2. Save
3. Verify: Success (max 500 chars allowed)
4. Try 501 characters
5. Verify: Validation error

---

## 🎨 UI/UX Design

### Color Scheme:
- **"Cetak Kartu" button:** Blue (`bg-blue-600`)
- **"Bergabung ke Grup" button:** Green (`bg-green-500`)

### Icons:
- **Cetak Kartu:** Document/file icon
- **Bergabung ke Grup:** Chat/message icon (double chat bubbles)

### Button Styling:
- Rounded corners (`rounded-lg`)
- Shadow (`shadow-lg`)
- Hover effects (`hover:scale-105`, `hover:bg-*-600`)
- Flexbox with icon and text

### Toggle Switch:
- Modern sliding toggle using Tailwind's `peer` classes
- Green when ON (`peer-checked:bg-emerald-600`)
- Gray when OFF (`bg-gray-200`)
- Smooth animation (`transition-all`)

---

## 🔐 Security

### URL Validation:
- Backend validation: `'link_grup_url' => 'nullable|url|max:500'`
- Only valid URLs accepted
- Max 500 characters to prevent abuse

### XSS Protection:
- Blade escaping: `{{ $linkGrupUrl }}` automatically escapes HTML
- `rel="noopener noreferrer"` on external links (prevents window.opener access)

### Access Control:
- Only Admin can configure link grup
- Pendaftar can only view/click button (read-only access)
- Link opens in new tab (`target="_blank"`)

---

## 💡 Use Cases

### 1. WhatsApp Group for Test Takers
Admin creates WhatsApp group for all test participants to share updates, answer questions, etc.

**Example URL:** `https://chat.whatsapp.com/ABC123DEF456`

### 2. Telegram Channel for Announcements
Admin uses Telegram channel for official announcements.

**Example URL:** `https://t.me/ppdb2026official`

### 3. Temporary Groups
Admin can:
- Enable link during test period
- Disable after tests complete
- Change URL if group link changes

### 4. Different Groups per Batch
Admin can update link for each new batch of students.

---

## 🚨 Edge Cases Handled

### 1. Link Enabled but URL Empty
**Behavior:** Button does NOT appear
**Reason:** Both conditions must be true: `$linkGrupEnabled && $linkGrupUrl`

### 2. Link Disabled but URL Exists
**Behavior:** Button does NOT appear
**Reason:** Toggle is OFF, so button hidden

### 3. Invalid URL Format
**Behavior:** Validation error when saving
**Error Message:** "Link grup harus berupa URL yang valid"

### 4. URL Too Long (>500 chars)
**Behavior:** Validation error when saving
**Error Message:** "Link grup maksimal 500 karakter"

### 5. Pendaftar Without Access to Kartu
**Behavior:** Pendaftar doesn't reach kartu page (access denied by component)
**Fallback:** Redirect to dashboard with error message

### 6. Print Mode
**Behavior:** Buttons hidden with `print:hidden` class
**Result:** Clean print output without action buttons

---

## 📝 Summary

**Files Modified:** 5 files
- **Backend:** 3 Livewire components
- **Frontend:** 3 Blade views

**New Settings Keys:** 2 database settings
- `link_grup_url` (string)
- `link_grup_enabled` (boolean)

**New UI Elements:**
- 1 new tab in Admin Pengaturan Kartu
- 1 toggle switch
- 1 URL input field
- 2 buttons in Pendaftar views (dashboard + kartu)

**Features:**
- Enable/disable toggle
- URL validation (format + max length)
- Real-time preview
- Button preview
- Conditional display
- External link safety (`target="_blank"`, `rel="noopener noreferrer"`)
- Print-friendly (buttons hidden when printing)

---

## 🔗 Related Features

**Previous Work:**
- RTE implementation for informasi fields (8 Feb 2026)
- Cache fix untuk Pendaftar display (8 Feb 2026)
- Escaping fix untuk Admin RTE (8 Feb 2026)

**This Feature:**
- Link Grup WhatsApp/Telegram (9 Feb 2026)
- Integrates with existing Kartu Tes Seleksi system
- Follows same UI/UX patterns as existing buttons

---

**Status:** ✅ COMPLETE
**Date:** 9 Feb 2026
**Feature:** Link Grup WhatsApp/Telegram dengan Enable/Disable Toggle
**Impact:** Admin dapat mengelola link grup untuk pendaftar, pendaftar dapat bergabung ke grup dengan 1 klik
**Testing:** All 10 test cases documented and ready for verification
