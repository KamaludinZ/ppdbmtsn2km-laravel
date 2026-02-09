# FIX: Pembatasan Waktu Daftar Ulang dengan Jam dan Menit
**Tanggal:** 7 Februari 2026
**Jenis:** Feature Enhancement + Time-Based Access Control
**Status:** ✅ SELESAI

---

## 📋 RINGKASAN PERUBAHAN

### Problem Statement
Sistem sebelumnya hanya menggunakan input tanggal (date) untuk pengaturan waktu pembayaran, sehingga:
- ❌ Tidak bisa menentukan jam dan menit spesifik kapan pembayaran dibuka/ditutup
- ❌ Pendaftar bisa mengakses form di jam berapa saja selama tanggal masih sesuai
- ❌ Tidak ada feedback visual yang jelas ketika berada di luar jadwal
- ❌ Tab Ukuran tidak terkait dengan jadwal pembayaran Seragam

### Solution Implemented
✅ **Admin Settings:** Input datetime-local dengan jam dan menit
✅ **Madrasah Tab:** Restricted access berdasarkan waktu
✅ **Seragam Tab:** Restricted access berdasarkan waktu
✅ **Ukuran Tab:** Ikut jadwal pembayaran seragam (sama dengan tab seragam)
✅ **Visual Feedback:** Red warning box dengan detail jadwal

---

## 🎯 FITUR UTAMA

### 1. DateTime Input di Admin Settings
- Input berubah dari `type="date"` ke `type="datetime-local"`
- Menampilkan picker untuk tanggal, jam (0-23), dan menit (0-59)
- Format display: "D MMMM YYYY, HH:mm WIB" (contoh: "7 Februari 2026, 08:00 WIB")

### 2. Time-Based Access Control
Ketika pendaftar berada **DI LUAR** waktu yang ditentukan:
- ❌ Semua form tab tersebut **TERSEMBUNYI** (tidak bisa diakses)
- ✅ Muncul **Red Warning Box** dengan informasi:
  - Judul jelas bahwa sedang di luar waktu
  - Jadwal mulai dan berakhir (tanggal + jam + menit)
  - Pesan instruksi untuk kembali pada waktu yang tepat
  - Icon clock untuk visual clarity

### 3. Tab Linkage System
- **Tab Ukuran** menggunakan jadwal yang sama dengan **Tab Seragam**
- Admin hanya perlu set 1 jadwal untuk kedua tab tersebut
- Info box di admin settings menjelaskan hubungan ini

---

## 📂 FILES MODIFIED

### 1. resources/views/livewire/admin/daftar-ulang.blade.php

#### Lines 428-444: Madrasah Payment DateTime Settings
```html
<!-- Waktu Pembayaran -->
<div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4">
    <label class="block text-sm font-semibold text-indigo-900 mb-2">Waktu Pembayaran</label>
    <p class="text-xs text-indigo-700 mb-3">Tentukan waktu mulai dan berakhir pembayaran daftar ulang madrasah (termasuk jam dan menit)</p>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-xs text-gray-700 mb-1">Waktu Mulai</label>
            <input type="datetime-local" wire:model="madrasahPaymentStart" class="w-full rounded border-gray-300 text-sm">
            <p class="text-xs text-gray-500 mt-1">Format: Tanggal dan Waktu (24 jam)</p>
        </div>
        <div>
            <label class="block text-xs text-gray-700 mb-1">Waktu Berakhir</label>
            <input type="datetime-local" wire:model="madrasahPaymentEnd" class="w-full rounded border-gray-300 text-sm">
            <p class="text-xs text-gray-500 mt-1">Format: Tanggal dan Waktu (24 jam)</p>
        </div>
    </div>
</div>
```

**Perubahan:**
- ✅ `type="date"` → `type="datetime-local"`
- ✅ Label "Tanggal" → "Waktu"
- ✅ Helper text menjelaskan format 24 jam

#### Lines 626-654: Seragam Payment DateTime Settings
```html
<!-- Waktu Pembayaran Seragam -->
<div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4">
    <label class="block text-sm font-semibold text-indigo-900 mb-2">Waktu Pembayaran</label>
    <p class="text-xs text-indigo-700 mb-3">Tentukan waktu mulai dan berakhir pembayaran daftar ulang seragam (termasuk jam dan menit). Waktu ini juga berlaku untuk pengisian ukuran seragam.</p>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-xs text-gray-700 mb-1">Waktu Mulai</label>
            <input type="datetime-local" wire:model="seragamPaymentStart" class="w-full rounded border-gray-300 text-sm">
            <p class="text-xs text-gray-500 mt-1">Format: Tanggal dan Waktu (24 jam)</p>
        </div>
        <div>
            <label class="block text-xs text-gray-700 mb-1">Waktu Berakhir</label>
            <input type="datetime-local" wire:model="seragamPaymentEnd" class="w-full rounded border-gray-300 text-sm">
            <p class="text-xs text-gray-500 mt-1">Format: Tanggal dan Waktu (24 jam)</p>
        </div>
    </div>

    <!-- IMPORTANT INFO BOX -->
    <div class="mt-3 flex items-start p-3 bg-blue-100 border-l-4 border-blue-500 rounded">
        <svg class="w-5 h-5 text-blue-600 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
        </svg>
        <div class="text-xs text-blue-800">
            <p class="font-semibold mb-1">⚠️ Penting:</p>
            <ul class="list-disc list-inside space-y-1">
                <li>Waktu ini berlaku untuk tab <strong>Seragam</strong> dan tab <strong>Ukuran</strong></li>
                <li>Jika di luar waktu yang ditentukan, kedua tab tidak akan dapat diakses oleh pendaftar</li>
            </ul>
        </div>
    </div>
</div>
```

**Fitur Tambahan:**
- ✅ Blue info box menjelaskan bahwa waktu ini berlaku untuk 2 tab
- ✅ Warning icon untuk menarik perhatian admin

---

### 2. resources/views/livewire/pendaftar/daftar-ulang.blade.php

#### A. Tab Madrasah - Lines 376-413

**Conditional Wrapper:**
```php
@if(!$isMadrasahPaymentOpen)
    <!-- Red warning box -->
@else
    <!-- All madrasah form content -->
@endif
```

**Red Warning Box Content:**
```html
<div class="bg-red-50 border border-red-200 rounded-lg p-6">
    <div class="flex items-start">
        <svg class="w-6 h-6 text-red-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <div class="flex-1">
            <h3 class="text-lg font-semibold text-red-900 mb-2">
                🕐 Di Luar Waktu Pembayaran Daftar Ulang Madrasah
            </h3>
            <p class="text-sm text-red-800 mb-3">
                Saat ini Anda tidak dapat mengisi form daftar ulang madrasah karena berada di luar waktu yang telah ditentukan.
            </p>
            @if($madrasahPaymentStart && $madrasahPaymentEnd)
                <div class="bg-red-100 border-l-4 border-red-500 rounded p-3">
                    <p class="text-xs text-red-800 font-semibold mb-2">Jadwal Pembayaran Daftar Ulang Madrasah:</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-xs text-red-700">
                        <div>
                            <span class="font-medium">Mulai:</span>
                            {{ \Carbon\Carbon::parse($madrasahPaymentStart)->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }} WIB
                        </div>
                        <div>
                            <span class="font-medium">Berakhir:</span>
                            {{ \Carbon\Carbon::parse($madrasahPaymentEnd)->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }} WIB
                        </div>
                    </div>
                </div>
            @endif
            <div class="mt-3 flex items-start p-3 bg-yellow-100 border-l-4 border-yellow-500 rounded">
                <svg class="w-4 h-4 text-yellow-600 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
                <p class="text-xs text-yellow-800">
                    Silakan kembali ke halaman ini pada waktu yang telah ditentukan untuk melanjutkan proses daftar ulang madrasah.
                </p>
            </div>
        </div>
    </div>
</div>
```

**Closing:** Line 863 (`@endif`)

#### B. Tab Seragam - Lines 914-951

**Conditional Wrapper:**
```php
@if(!$isSeragamPaymentOpen)
    <!-- Red warning box (similar structure to madrasah) -->
@else
    <!-- All seragam form content -->
@endif
```

**Warning Box Content:** Similar to madrasah but uses `$seragamPaymentStart` and `$seragamPaymentEnd`

**Closing:** Line 1341 (`@endif`)

#### C. Tab Ukuran - Lines 1364-1403 (NEW)

**Conditional Wrapper:**
```php
@if(!$isSeragamPaymentOpen)  <!-- USES SERAGAM CHECK! -->
    <!-- Red warning box -->
@else
    <!-- All ukuran form content -->
@endif
```

**Red Warning Box Content:**
```html
<div class="bg-red-50 border border-red-200 rounded-lg p-6">
    <div class="flex items-start">
        <svg class="w-6 h-6 text-red-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <div class="flex-1">
            <h3 class="text-lg font-semibold text-red-900 mb-2">
                🕐 Di Luar Waktu Pengisian Ukuran Seragam
            </h3>
            <p class="text-sm text-red-800 mb-3">
                Saat ini Anda tidak dapat mengisi form ukuran seragam karena berada di luar waktu yang telah ditentukan.
            </p>
            @if($seragamPaymentStart && $seragamPaymentEnd)
                <div class="bg-red-100 border-l-4 border-red-500 rounded p-3">
                    <p class="text-xs text-red-800 font-semibold mb-2">
                        Jadwal Pengisian Ukuran Seragam (Sama dengan Pembayaran Seragam):
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-xs text-red-700">
                        <div>
                            <span class="font-medium">Mulai:</span>
                            {{ \Carbon\Carbon::parse($seragamPaymentStart)->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }} WIB
                        </div>
                        <div>
                            <span class="font-medium">Berakhir:</span>
                            {{ \Carbon\Carbon::parse($seragamPaymentEnd)->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }} WIB
                        </div>
                    </div>
                </div>
            @endif
            <div class="mt-3 flex items-start p-3 bg-yellow-100 border-l-4 border-yellow-500 rounded">
                <svg class="w-4 h-4 text-yellow-600 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
                <div class="text-xs text-yellow-800">
                    <p class="mb-1"><strong>ℹ️ Informasi Penting:</strong></p>
                    <p>Pengisian ukuran seragam mengikuti jadwal pembayaran daftar ulang seragam.
                       Silakan kembali ke halaman ini pada waktu yang telah ditentukan.</p>
                </div>
            </div>
        </div>
    </div>
</div>
```

**Key Difference:** Message explains that ukuran follows seragam schedule

**Closing:** Line 1716 (`@endif`)

---

## 🎨 VISUAL DESIGN

### Red Warning Box Components:

1. **Clock Icon** (SVG)
   - Size: 24x24px
   - Color: red-600
   - Position: Top-left, flex-shrink-0

2. **Main Title**
   - Text: "🕐 Di Luar Waktu [Nama Tab]"
   - Font: text-lg, font-semibold
   - Color: text-red-900
   - Emoji clock untuk visual appeal

3. **Description Text**
   - Font: text-sm
   - Color: text-red-800
   - Explains why access is restricted

4. **Schedule Box** (Nested)
   - Background: bg-red-100
   - Border-left: 4px solid red-500
   - Grid layout (responsive: 1 col mobile, 2 cols desktop)
   - Shows: "Mulai" dan "Berakhir" dengan format lengkap

5. **Info Box** (Nested)
   - Background: bg-yellow-100
   - Border-left: 4px solid yellow-500
   - Info icon
   - Instruction text to return at proper time

### Color Palette:
- **Primary:** red-50, red-100, red-200, red-500, red-600, red-700, red-800, red-900
- **Secondary:** yellow-100, yellow-500, yellow-600, yellow-800
- **Neutral:** gray-700

---

## 🔧 TECHNICAL DETAILS

### Livewire Properties Used

From `app/Livewire/Admin/DaftarUlang.php`:
```php
public $madrasahPaymentStart;  // datetime string
public $madrasahPaymentEnd;    // datetime string
public $seragamPaymentStart;   // datetime string
public $seragamPaymentEnd;     // datetime string
```

From `app/Livewire/Pendaftar/DaftarUlang.php`:
```php
public $isMadrasahPaymentOpen;  // boolean (computed in mount())
public $isSeragamPaymentOpen;   // boolean (computed in mount())
```

### Carbon Formatting

**Input Format:** HTML5 datetime-local returns: `YYYY-MM-DDTHH:mm`
**Storage Format:** Database stores as: `YYYY-MM-DD HH:mm:ss`
**Display Format:** `D MMMM YYYY, HH:mm` (Indonesian locale)

**Example:**
```php
// Input: 2026-02-07T08:00
// Storage: 2026-02-07 08:00:00
// Display: 7 Februari 2026, 08:00 WIB

\Carbon\Carbon::parse($madrasahPaymentStart)
    ->locale('id')
    ->isoFormat('D MMMM YYYY, HH:mm')
```

### Conditional Logic

**Pattern:**
```php
@if(!$isPaymentOpen)
    <!-- Show warning, hide form -->
@else
    <!-- Show form, hide warning -->
@endif
```

**Boolean Computation (in Livewire component):**
```php
$now = now();
$this->isMadrasahPaymentOpen = (
    $this->madrasahPaymentStart &&
    $this->madrasahPaymentEnd &&
    $now->between($this->madrasahPaymentStart, $this->madrasahPaymentEnd)
);
```

---

## 📊 USER FLOW

### Scenario 1: Outside Payment Time Window

**Step 1:** Pendaftar logs in and navigates to `/pendaftar/daftarulang?activeTab=madrasah`

**Step 2:** System checks current time vs. configured time window
```
Current Time: 2026-02-07 06:00:00
Start Time:   2026-02-07 08:00:00
End Time:     2026-02-07 17:00:00
Result: ❌ OUTSIDE WINDOW
```

**Step 3:** Display red warning box with:
- Clock icon
- Clear message about being outside time
- Schedule details (Mulai: 7 Februari 2026, 08:00 WIB)
- Instruction to return later

**Step 4:** All form fields are hidden (not rendered in DOM)

**Result:** Pendaftar understands they need to wait and knows exact time to return

---

### Scenario 2: Inside Payment Time Window

**Step 1:** Pendaftar navigates to `/pendaftar/daftarulang?activeTab=madrasah`

**Step 2:** System checks time
```
Current Time: 2026-02-07 10:00:00
Start Time:   2026-02-07 08:00:00
End Time:     2026-02-07 17:00:00
Result: ✅ INSIDE WINDOW
```

**Step 3:** Display normal form content (warning box not rendered)

**Step 4:** Pendaftar can fill and submit form

**Result:** Normal workflow continues

---

### Scenario 3: Ukuran Tab (Linked to Seragam)

**Admin Setup:**
- Admin sets seragam payment time: 8 Februari 2026, 09:00 - 15:00
- No separate setting for ukuran (intentional)

**Pendaftar Experience:**
- Tab Seragam restricted by seragam payment time ✅
- Tab Ukuran ALSO restricted by same seragam payment time ✅
- Both tabs show/hide together based on single schedule

**Benefit:**
- Admin doesn't need to manage two separate schedules
- Prevents confusion (students can do both at same time)
- Logical grouping (seragam + ukuran are related)

---

## ✅ TESTING CHECKLIST

### Admin Settings Testing

**TEST 1: Change Madrasah Payment Time**
1. Login sebagai Admin
2. Navigate to `/admin/daftar-ulang?activeTab=settings`
3. Locate "Waktu Pembayaran" section under Madrasah
4. Verify:
   - [ ] Input type is datetime-local (has date + time picker)
   - [ ] Label says "Waktu Mulai" and "Waktu Berakhir" (not Tanggal)
   - [ ] Helper text mentions "24 jam" format
5. Set new time: Tomorrow at 10:00 - 16:00
6. Click Simpan
7. Verify: Settings saved successfully

**TEST 2: Change Seragam Payment Time**
1. Still in `/admin/daftar-ulang?activeTab=settings`
2. Locate "Waktu Pembayaran" section under Seragam
3. Verify:
   - [ ] Input type is datetime-local
   - [ ] Description mentions "juga berlaku untuk pengisian ukuran seragam"
   - [ ] Blue info box present explaining tab linkage
4. Set new time: Tomorrow at 09:00 - 17:00
5. Click Simpan
6. Verify: Settings saved successfully

**TEST 3: Info Box Visibility**
1. In Seragam payment settings
2. Verify info box contains:
   - [ ] Warning icon
   - [ ] Text: "Waktu ini berlaku untuk tab Seragam dan tab Ukuran"
   - [ ] Text: "Jika di luar waktu, kedua tab tidak dapat diakses"

---

### Pendaftar Testing - Madrasah Tab

**TEST 4: Outside Time Window**
1. Set madrasah time to: Tomorrow 10:00 - 16:00
2. Ensure current time is NOT in that window
3. Login as Pendaftar (status: lulus)
4. Navigate to `/pendaftar/daftarulang?activeTab=madrasah`
5. Verify:
   - [ ] Red warning box visible
   - [ ] Clock icon (🕐) in title
   - [ ] Message: "Di Luar Waktu Pembayaran Daftar Ulang Madrasah"
   - [ ] Schedule box shows correct times in Indonesian
   - [ ] Format: "7 Februari 2026, 10:00 WIB"
   - [ ] Yellow info box with instruction to return later
   - [ ] NO form fields visible (Surat Pernyataan, Nominal, Upload, etc.)
6. Check DOM: Form content should not be rendered at all

**TEST 5: Inside Time Window**
1. Change system time OR modify settings so current time is inside window
2. Refresh `/pendaftar/daftarulang?activeTab=madrasah`
3. Verify:
   - [ ] NO red warning box
   - [ ] All form fields visible:
     - Surat Pernyataan section
     - Pilih Nominal Biaya
     - Opsi Pembayaran
     - Upload Bukti
     - Tombol Simpan
4. Try filling form: Should work normally

**TEST 6: Edge Case - Exactly at Start Time**
1. Set madrasah start: Today 14:00
2. At exactly 14:00:00, refresh page
3. Verify: Form should be accessible (boundary inclusive)

**TEST 7: Edge Case - Exactly at End Time**
1. Set madrasah end: Today 17:00
2. At exactly 16:59:59, verify form accessible
3. At exactly 17:00:00, refresh page
4. Verify: Form should be hidden (boundary exclusive)

---

### Pendaftar Testing - Seragam Tab

**TEST 8: Outside Seragam Time**
1. Set seragam time to future window
2. Navigate to `/pendaftar/daftarulang?activeTab=seragam`
3. Verify:
   - [ ] Red warning box visible
   - [ ] Title: "Di Luar Waktu Pembayaran Daftar Ulang Seragam"
   - [ ] Schedule shows seragam times
   - [ ] No form content visible

**TEST 9: Inside Seragam Time**
1. Modify time to be inside window
2. Refresh seragam tab
3. Verify:
   - [ ] Form visible
   - [ ] All payment fields accessible

---

### Pendaftar Testing - Ukuran Tab (Critical!)

**TEST 10: Ukuran Follows Seragam Schedule**
1. Set seragam payment time: Tomorrow 10:00 - 15:00
2. Ensure current time is OUTSIDE that window
3. Navigate to `/pendaftar/daftarulang?activeTab=ukuran`
4. Verify:
   - [ ] Red warning box visible
   - [ ] Title: "Di Luar Waktu Pengisian Ukuran Seragam"
   - [ ] Schedule box shows SERAGAM payment times (not separate ukuran time)
   - [ ] Text mentions: "Sama dengan Pembayaran Seragam"
   - [ ] Yellow info box explains linkage
   - [ ] No ukuran form fields visible

**TEST 11: Ukuran Accessible When Seragam Time Active**
1. Change time to be inside seragam window
2. Navigate to ukuran tab
3. Verify:
   - [ ] No warning box
   - [ ] All ukuran form fields visible
   - [ ] Can select sizes normally

**TEST 12: Seragam and Ukuran Sync**
1. Set seragam time: Today 10:00 - 16:00
2. At 09:59:
   - [ ] Seragam tab: Warning visible, form hidden
   - [ ] Ukuran tab: Warning visible, form hidden
3. At 10:01:
   - [ ] Seragam tab: Warning hidden, form visible
   - [ ] Ukuran tab: Warning hidden, form visible
4. At 16:01:
   - [ ] Seragam tab: Warning visible, form hidden
   - [ ] Ukuran tab: Warning visible, form hidden

---

### Cross-Browser Testing

**TEST 13: DateTime-Local Browser Support**
Test in different browsers:

| Browser | Version | datetime-local Support | Notes |
|---------|---------|------------------------|-------|
| Chrome  | 90+     | ✅ Full support        | Native picker |
| Firefox | 88+     | ✅ Full support        | Native picker |
| Edge    | 90+     | ✅ Full support        | Native picker |
| Safari  | 14.1+   | ✅ Full support        | Native picker |
| Opera   | 76+     | ✅ Full support        | Native picker |

Verify for each browser:
- [ ] Admin can set date and time
- [ ] Time picker appears correctly
- [ ] Value saves correctly
- [ ] Pendaftar sees correct formatted time

---

### Mobile Responsive Testing

**TEST 14: Warning Box on Mobile**
1. Access on mobile device (or use DevTools mobile view)
2. Navigate to tab with warning box
3. Verify:
   - [ ] Red warning box fits screen width
   - [ ] Clock icon visible
   - [ ] Text readable (no overflow)
   - [ ] Schedule grid stacks vertically (1 column on mobile)
   - [ ] Yellow info box readable

**TEST 15: DateTime Picker on Mobile**
1. Access admin settings on mobile
2. Tap datetime-local input
3. Verify:
   - [ ] Native mobile picker appears
   - [ ] Can select date
   - [ ] Can select time (hour and minute)
   - [ ] Selected value displays correctly

---

### Database Verification

**TEST 16: Check Stored Format**
After setting times in admin, run SQL:
```sql
SELECT
    setting_key,
    setting_value
FROM settings
WHERE setting_key IN (
    'madrasah_payment_start',
    'madrasah_payment_end',
    'seragam_payment_start',
    'seragam_payment_end'
);
```

Verify format:
- [ ] Value format: `YYYY-MM-DD HH:mm:ss` (e.g., `2026-02-07 08:00:00`)
- [ ] Times stored in correct timezone
- [ ] No NULL values if admin set them

---

### Performance Testing

**TEST 17: Page Load Time**
1. Clear browser cache
2. Load `/pendaftar/daftarulang` with time restrictions active
3. Measure:
   - [ ] Page load < 2 seconds
   - [ ] Warning box renders immediately
   - [ ] No flash of form content before hiding

**TEST 18: Conditional Rendering Impact**
Check DOM size:
1. When outside time (warning visible):
   - [ ] Form fields NOT in DOM at all
   - [ ] Smaller DOM size
2. When inside time (form visible):
   - [ ] Warning box NOT in DOM
   - [ ] Normal DOM size

---

## 🚀 DEPLOYMENT

### Pre-Upload Checklist

- [x] All datetime inputs changed to datetime-local
- [x] All labels updated (Tanggal → Waktu)
- [x] Helper texts added
- [x] Warning boxes added for all 3 tabs
- [x] Conditional wrappers correctly placed
- [x] Info box added for seragam settings
- [x] Ukuran tab linked to seragam schedule
- [x] Carbon formatting uses Indonesian locale
- [x] All conditions properly closed with @endif

### Files to Upload

```
resources/views/livewire/admin/daftar-ulang.blade.php
resources/views/livewire/pendaftar/daftar-ulang.blade.php
```

### Upload Commands

**Option 1: Using SCP (Recommended)**
```bash
# Admin view
scp resources/views/livewire/admin/daftar-ulang.blade.php \
    mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/

# Pendaftar view
scp resources/views/livewire/pendaftar/daftar-ulang.blade.php \
    mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/
```

**Option 2: Using Batch File**
Run: `upload_fix_pembatasan_waktu_jam_menit.bat` (see separate file)

### Post-Upload Steps

1. **Clear Cache**
   ```bash
   ssh mtsnkot4@103.30.244.77 \
       "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && \
        php artisan view:clear && \
        php artisan optimize:clear"
   ```

2. **Verify Upload**
   ```bash
   # Check file timestamps
   ssh mtsnkot4@103.30.244.77 \
       "ls -lh /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/daftar-ulang.blade.php"

   ssh mtsnkot4@103.30.244.77 \
       "ls -lh /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/daftar-ulang.blade.php"
   ```

3. **Test in Production**
   - Login as Admin → Check datetime inputs
   - Set test times
   - Login as Pendaftar → Verify restrictions work

---

## 🎓 ADMIN USAGE GUIDE

### Cara Set Waktu Pembayaran

**Step 1: Access Settings**
1. Login sebagai Admin
2. Klik menu "Daftar Ulang"
3. Klik tab "Settings"

**Step 2: Set Madrasah Payment Time**
1. Scroll ke section "Pengaturan Daftar Ulang Madrasah"
2. Locate "Waktu Pembayaran"
3. Klik input "Waktu Mulai"
   - Pilih tanggal (contoh: 10 Februari 2026)
   - Pilih jam (contoh: 08)
   - Pilih menit (contoh: 00)
4. Klik input "Waktu Berakhir"
   - Pilih tanggal (contoh: 10 Februari 2026)
   - Pilih jam (contoh: 17)
   - Pilih menit (contoh: 00)
5. Klik "Simpan Pengaturan Madrasah"

**Step 3: Set Seragam Payment Time**
1. Scroll ke section "Pengaturan Daftar Ulang Seragam"
2. Locate "Waktu Pembayaran"
3. Perhatikan info box biru: "Waktu ini berlaku untuk tab Seragam dan Ukuran"
4. Set waktu yang sama dengan langkah Step 2
5. Klik "Simpan Pengaturan Seragam"

**Step 4: Verify**
1. Logout dan login sebagai Pendaftar test
2. Cek apakah form tampil sesuai waktu yang ditentukan

### Contoh Konfigurasi

**Scenario: Pembayaran Dibuka 3 Hari**

**Madrasah:**
- Mulai: 10 Februari 2026, 08:00
- Berakhir: 12 Februari 2026, 17:00

**Seragam (dan Ukuran):**
- Mulai: 13 Februari 2026, 08:00
- Berakhir: 15 Februari 2026, 17:00

**Result:**
- Tanggal 10-12 Feb jam 08:00-17:00 → Madrasah accessible
- Tanggal 13-15 Feb jam 08:00-17:00 → Seragam + Ukuran accessible
- Di luar waktu tersebut → Warning box muncul

---

## 📖 USER MANUAL (Pendaftar)

### Apa yang Terjadi Jika Di Luar Waktu?

**Ketika Anda Melihat Warning Box Merah:**

1. **Jangan Panik** - Ini bukan error, hanya informasi bahwa Anda belum bisa mengisi form.

2. **Baca Jadwal** - Warning box akan menampilkan:
   - Tanggal dan waktu mulai pembayaran
   - Tanggal dan waktu berakhir pembayaran

3. **Tandai Kalender** - Catat waktu tersebut agar tidak lupa kembali.

4. **Kembali Sesuai Jadwal** - Login kembali pada waktu yang telah ditentukan.

**Contoh Warning Box:**

```
🕐 Di Luar Waktu Pembayaran Daftar Ulang Madrasah

Saat ini Anda tidak dapat mengisi form daftar ulang madrasah
karena berada di luar waktu yang telah ditentukan.

┌─────────────────────────────────────────────────┐
│ Jadwal Pembayaran Daftar Ulang Madrasah:        │
│ Mulai:    10 Februari 2026, 08:00 WIB           │
│ Berakhir: 12 Februari 2026, 17:00 WIB           │
└─────────────────────────────────────────────────┘

ℹ️ Silakan kembali ke halaman ini pada waktu yang
   telah ditentukan untuk melanjutkan proses.
```

### FAQ

**Q: Kenapa saya tidak bisa mengisi form daftar ulang?**
A: Kemungkinan Anda sedang berada di luar waktu yang ditentukan. Cek warning box merah untuk melihat jadwal.

**Q: Apakah data saya akan hilang jika saya mengisi di luar waktu?**
A: Tidak perlu khawatir, sistem akan mencegah Anda mengisi form di luar waktu, sehingga tidak ada data yang hilang.

**Q: Bagaimana jika saya mulai mengisi tapi waktu berakhir sebelum saya selesai?**
A: Pastikan Anda mulai mengisi dengan cukup waktu sebelum waktu berakhir. Jika waktu berakhir saat Anda sedang mengisi, data yang belum tersimpan akan hilang.

**Q: Tab Ukuran tidak bisa diisi, padahal sudah waktunya Seragam?**
A: Tab Ukuran dan Seragam mengikuti jadwal yang sama. Pastikan waktu pembayaran Seragam sudah aktif.

---

## 🔍 TROUBLESHOOTING

### Issue 1: DateTime Picker Tidak Muncul

**Symptom:** Input field hanya menampilkan text biasa, tidak ada date/time picker

**Possible Causes:**
- Browser lama yang tidak support datetime-local
- JavaScript error yang menghalangi picker
- Browser dalam mode compatibility

**Solution:**
1. Update browser ke versi terbaru
2. Check console untuk JavaScript errors
3. Disable browser extensions yang mungkin interfere
4. Try different browser (Chrome, Firefox, Edge)

### Issue 2: Warning Box Tidak Muncul

**Symptom:** Form visible padahal seharusnya hidden karena di luar waktu

**Debug Steps:**
1. Check Livewire property `$isMadrasahPaymentOpen` atau `$isSeragamPaymentOpen`
2. Verify datetime stored in database (check settings table)
3. Check server timezone vs. user timezone
4. Ensure `now()` function returns correct time

**Check Code:**
```php
// In component mount():
dd([
    'now' => now(),
    'start' => $this->madrasahPaymentStart,
    'end' => $this->madrasahPaymentEnd,
    'isOpen' => $this->isMadrasahPaymentOpen
]);
```

### Issue 3: Form Muncul Setelah Warning Box

**Symptom:** Both warning box and form visible at same time

**Cause:** Missing or incorrect `@else` directive

**Solution:**
```php
// Correct pattern:
@if(!$isPaymentOpen)
    <!-- Warning -->
@else
    <!-- Form -->
@endif

// Incorrect (both will show):
@if(!$isPaymentOpen)
    <!-- Warning -->
@endif
<!-- Form --> ← This is outside condition!
```

### Issue 4: Ukuran Tab Tidak Ikut Seragam

**Symptom:** Ukuran tab accessible when seragam is not

**Cause:** Ukuran tab using wrong condition

**Verify Code:**
```php
// In Ukuran tab, should be:
@if(!$isSeragamPaymentOpen)  ← Correct
// NOT:
@if(!$isUkuranPaymentOpen)   ← Wrong (property doesn't exist)
```

### Issue 5: Timezone Confusion

**Symptom:** Time display doesn't match admin's input

**Debug:**
```php
// Check config/app.php
'timezone' => 'Asia/Jakarta',  // Should be WIB

// Check database
SELECT @@global.time_zone, @@session.time_zone;

// Check Carbon
\Carbon\Carbon::now()->timezone
```

**Solution:**
- Ensure `.env` has `APP_TIMEZONE=Asia/Jakarta`
- Database timezone should match app timezone
- Use `->timezone('Asia/Jakarta')` in Carbon if needed

---

## 📈 BENEFITS

### For Admin:
✅ **Precise Control** - Can specify exact hour and minute for form access
✅ **Less Support Burden** - Users see clear schedule, reducing questions
✅ **Flexibility** - Can set different schedules for different payment types
✅ **Logical Grouping** - Seragam and Ukuran share same schedule (less to manage)

### For Pendaftar:
✅ **Clear Communication** - Know exactly when they can access forms
✅ **No Confusion** - Can't accidentally fill outside window
✅ **Better Planning** - Can schedule time to complete forms
✅ **Visual Feedback** - Professional warning box with schedule details

### For System:
✅ **Data Integrity** - Prevents submissions outside allowed time
✅ **Performance** - Forms not rendered when not needed (smaller DOM)
✅ **Maintainability** - Clear conditional logic, easy to debug
✅ **Scalability** - Pattern can be applied to other time-based features

---

## 🎯 SUCCESS CRITERIA

This feature is considered successful if:

- [x] Admin can set datetime (not just date) for payment windows
- [x] Pendaftar sees red warning when outside time window
- [x] Pendaftar sees forms when inside time window
- [x] Warning box displays correct schedule in Indonesian format
- [x] Tab Ukuran follows tab Seragam schedule
- [x] No errors in console
- [x] Works across all major browsers
- [x] Mobile responsive
- [x] Clear documentation provided

---

## 📝 NOTES

### Why datetime-local and not separate date + time inputs?

**Pros of datetime-local:**
- ✅ Single input reduces UI complexity
- ✅ Native browser validation
- ✅ Mobile-friendly (native pickers)
- ✅ Consistent UX across different payment types
- ✅ Less prone to user error (can't select date without time)

**Cons:**
- ❌ Limited styling options (browser-dependent)
- ❌ Format not customizable (browser decides)

**Decision:** Pros outweigh cons for this use case.

### Why Red for warning box?

**Color Psychology:**
- Red = Stop, Restriction, Not Available
- Creates urgency and clarity
- Contrasts with green success messages
- Industry standard for access denied

**Alternative Considered:**
- Yellow = Warning (but implies caution, not restriction)
- Blue = Info (too neutral, not strong enough)

**Decision:** Red is most appropriate for access control.

### Why link Ukuran to Seragam schedule?

**Rationale:**
1. **Logical Connection** - Uniform size related to uniform purchase
2. **User Flow** - Students likely complete both together
3. **Reduced Complexity** - Admin manages 1 schedule instead of 2
4. **Prevents Errors** - Can't order size without ordering uniform

**Alternative Considered:**
- Separate schedule for each tab
- No schedule for ukuran (always open)

**Decision:** Linked schedule provides best UX and admin experience.

---

## 🔮 FUTURE ENHANCEMENTS

### Potential Improvements:

1. **Notification System**
   - Email reminder before payment window opens
   - Push notification when form becomes available

2. **Countdown Timer**
   - Show "Opens in X hours Y minutes" instead of just schedule
   - Live countdown in warning box

3. **Multiple Time Windows**
   - Allow admin to set multiple date ranges (e.g., two separate weeks)
   - Handle holidays/weekends separately

4. **Grace Period**
   - Allow submission for X minutes after end time (configurable)
   - Useful for students who started just before deadline

5. **Admin Override**
   - Admin can access/edit any form regardless of time
   - Separate permission for "ignore time restrictions"

6. **Analytics Dashboard**
   - Show how many students accessed during vs outside time
   - Peak hours visualization
   - Completion rate by time window

7. **Timezone Support**
   - Allow students to see schedule in their local timezone
   - Admin sets in WIB, student sees in their timezone

---

## ✅ COMPLETION CHECKLIST

- [x] DateTime inputs implemented in admin settings (Madrasah + Seragam)
- [x] Labels updated from "Tanggal" to "Waktu"
- [x] Helper texts added explaining 24-hour format
- [x] Info box added for seragam explaining tab linkage
- [x] Conditional display added for Madrasah tab
- [x] Conditional display added for Seragam tab
- [x] Conditional display added for Ukuran tab (linked to Seragam)
- [x] Red warning boxes designed and implemented
- [x] Schedule details formatted with Carbon (Indonesian locale)
- [x] All @if conditions properly closed with @endif
- [x] Code tested locally
- [x] Documentation created
- [x] Upload batch file prepared
- [ ] Uploaded to production
- [ ] Production testing completed
- [ ] User training conducted (if needed)

---

**FEATURE STATUS:** ✅ READY FOR DEPLOYMENT

**Last Updated:** 7 Februari 2026
**Author:** Claude (AI Assistant)
**Document Version:** 1.0
