# Add: Status "Perbaiki" to Status Display ✅

## 📋 Overview Perubahan

Menambahkan status **"PERLU PERBAIKI"** (orange) ke dalam status display untuk ketiga bagian:
- Status Madrasah
- Status Seragam
- Status Ukuran

**Status**: ✅ COMPLETE

---

## 🎯 Purpose

### User Request:
```
"jangan lupa ada lagi status perbaiki perlu juga di tambahkan"
```

### Use Case:
Status "perbaiki" digunakan ketika panitia meminta pendaftar untuk memperbaiki dokumen/data yang sudah diupload, tanpa menolak secara permanen (seperti status "ditolak").

**Perbedaan**:
- **DITOLAK** (Red) → Data/dokumen ditolak, perlu diupload ulang dari awal
- **PERLU PERBAIKI** (Orange) → Data/dokumen perlu diperbaiki/dilengkapi, masih bisa di-revisi

---

## 🔧 Technical Implementation

### File Modified: `resources/views/livewire/pendaftar/daftar-ulang.blade.php`

### 1. Status Madrasah (Lines 68-91)

**Added**:
```php
@php
    // Determine status based on bukti pembayaran and actual status
    if ($daftarUlang->status === 'disetujui' || $daftarUlang->status === 'selesai') {
        $statusMadrasah = 'disetujui';
    } elseif ($daftarUlang->status === 'ditolak') {
        $statusMadrasah = 'ditolak';
    } elseif ($daftarUlang->status === 'perbaiki') {
        // ✅ NEW: Check for 'perbaiki' status
        $statusMadrasah = 'perbaiki';
    } elseif ($daftarUlang->bukti_pembayaran_madrasah) {
        $statusMadrasah = 'menunggu';
    } else {
        $statusMadrasah = 'belum_upload';
    }

    $statusMadrasahInfo = match($statusMadrasah) {
        'disetujui' => [...],
        'ditolak' => [...],
        'perbaiki' => [
            // ✅ NEW: Orange badge for 'perbaiki'
            'label' => 'PERLU PERBAIKI',
            'color' => 'bg-orange-100 text-orange-800 border-orange-300',
            'icon' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z'
        ],
        'menunggu' => [...],
        default => [...],
    };
@endphp
```

**Changes**:
- Line 74-75: Added check for `$daftarUlang->status === 'perbaiki'`
- Line 87: Added match case for 'perbaiki' with orange styling

---

### 2. Status Seragam (Lines 107-130)

**Added**:
```php
@php
    if ($seragam && ($seragam->status_seragam === 'disetujui' || $seragam->status_seragam === 'selesai')) {
        $statusSeragam = 'disetujui';
    } elseif ($seragam && $seragam->status_seragam === 'ditolak') {
        $statusSeragam = 'ditolak';
    } elseif ($seragam && $seragam->status_seragam === 'perbaiki') {
        // ✅ NEW: Check for 'perbaiki' status
        $statusSeragam = 'perbaiki';
    } elseif ($seragam && $seragam->bukti_pembayaran) {
        $statusSeragam = 'menunggu';
    } else {
        $statusSeragam = 'belum_upload';
    }

    $statusSeragamInfo = match($statusSeragam) {
        'disetujui' => [...],
        'ditolak' => [...],
        'perbaiki' => [
            // ✅ NEW: Orange badge
            'label' => 'PERLU PERBAIKI',
            'color' => 'bg-orange-100 text-orange-800 border-orange-300'
        ],
        'menunggu' => [...],
        default => [...],
    };
@endphp
```

**Changes**:
- Line 113-114: Added check for `status_seragam === 'perbaiki'`
- Line 126: Added match case for 'perbaiki'

---

### 3. Status Ukuran (Lines 146-169)

**Added**:
```php
@php
    if ($seragam && ($seragam->status_ukuran === 'disetujui' || $seragam->status_ukuran === 'selesai')) {
        $statusUkuran = 'disetujui';
    } elseif ($seragam && $seragam->status_ukuran === 'ditolak') {
        $statusUkuran = 'ditolak';
    } elseif ($seragam && $seragam->status_ukuran === 'perbaiki') {
        // ✅ NEW: Check for 'perbaiki' status
        $statusUkuran = 'perbaiki';
    } elseif ($seragam && (...)) {
        $statusUkuran = 'menunggu';
    } else {
        $statusUkuran = 'belum_isi';
    }

    $statusUkuranInfo = match($statusUkuran) {
        'disetujui' => [...],
        'ditolak' => [...],
        'perbaiki' => [
            // ✅ NEW: Orange badge
            'label' => 'PERLU PERBAIKI',
            'color' => 'bg-orange-100 text-orange-800 border-orange-300'
        ],
        'menunggu' => [...],
        default => [...],
    };
@endphp
```

**Changes**:
- Line 152-153: Added check for `status_ukuran === 'perbaiki'`
- Line 165: Added match case for 'perbaiki'

---

### 4. Overall Status Description (Lines 178-223)

**Added**:
```php
@php
    $allApproved = (...);
    $anyRejected = (...);
    $anyPerbaiki = ($statusMadrasah === 'perbaiki' || $statusSeragam === 'perbaiki' || $statusUkuran === 'perbaiki');
    // ✅ NEW: Check if any status is 'perbaiki'
    $allWaiting = (...);
@endphp

@if($allApproved)
    <!-- Green success message -->
@elseif($anyRejected)
    <!-- Red rejected message -->
@elseif($anyPerbaiki)
    <!-- ✅ NEW: Orange perbaiki message -->
    <div class="flex items-center text-orange-700">
        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
        </svg>
        <span class="font-medium">Ada data yang perlu diperbaiki. Silakan periksa catatan dari panitia dan lakukan perbaikan.</span>
    </div>
@elseif($allWaiting)
    <!-- Yellow waiting message -->
@else
    <!-- Gray incomplete message -->
@endif
```

**Changes**:
- Line 183: Added `$anyPerbaiki` variable
- Lines 200-206: Added orange message when any status is 'perbaiki'

**Priority Order**:
1. All Approved → Green
2. Any Rejected → Red
3. **Any Perbaiki → Orange** ✅ NEW
4. All Waiting → Yellow
5. Else → Gray

---

## 🎨 Visual Design

### Status Badge Colors

| Status | Badge Color | Text Color | Border | Use Case |
|--------|-------------|------------|--------|----------|
| DISETUJUI | `bg-green-100` | `text-green-800` | `border-green-300` | Data approved |
| DITOLAK | `bg-red-100` | `text-red-800` | `border-red-300` | Data rejected |
| **PERLU PERBAIKI** | **`bg-orange-100`** | **`text-orange-800`** | **`border-orange-300`** | **Needs revision** ✅ NEW |
| MENUNGGU | `bg-yellow-100` | `text-yellow-800` | `border-yellow-300` | Under review |
| BELUM UPLOAD | `bg-gray-100` | `text-gray-800` | `border-gray-300` | Not uploaded |

### Icon for "Perbaiki"

```svg
<!-- Edit/Pencil icon -->
<path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
```

**Meaning**: Pencil/edit icon representing "needs editing/fixing"

---

## 📊 Status Display Examples

### Example 1: Madrasah Status = 'perbaiki'

**Database**:
```
daftar_ulang.status = 'perbaiki'
daftar_ulang.bukti_pembayaran_madrasah = 'path/to/file.pdf'
```

**Display**:
```
┌─────────────────────┐
│ 🏫 Madrasah         │
│                     │
│  PERLU PERBAIKI     │
│    (orange)         │
└─────────────────────┘
```

**Overall Message**:
```
🖊️ Ada data yang perlu diperbaiki. Silakan periksa catatan dari panitia
   dan lakukan perbaikan.
```

---

### Example 2: Mixed Status (Approved + Perbaiki)

**Database**:
```
daftar_ulang.status = 'disetujui'
seragam.status_seragam = 'perbaiki'
seragam.status_ukuran = 'menunggu'
```

**Display**:
```
┌──────────────┐  ┌──────────────┐  ┌──────────────┐
│ 🏫 Madrasah  │  │ 👕 Seragam   │  │ 📏 Ukuran    │
│  DISETUJUI   │  │PERLU PERBAIKI│  │  MENUNGGU    │
│   (green)    │  │   (orange)   │  │   (yellow)   │
└──────────────┘  └──────────────┘  └──────────────┘

🖊️ Ada data yang perlu diperbaiki. Silakan periksa catatan...
```

**Priority**: Orange message shown because `$anyPerbaiki = true`

---

### Example 3: All Status 'perbaiki'

**Database**:
```
daftar_ulang.status = 'perbaiki'
seragam.status_seragam = 'perbaiki'
seragam.status_ukuran = 'perbaiki'
```

**Display**:
```
┌──────────────┐  ┌──────────────┐  ┌──────────────┐
│ 🏫 Madrasah  │  │ 👕 Seragam   │  │ 📏 Ukuran    │
│PERLU PERBAIKI│  │PERLU PERBAIKI│  │PERLU PERBAIKI│
│   (orange)   │  │   (orange)   │  │   (orange)   │
└──────────────┘  └──────────────┘  └──────────────┘

🖊️ Ada data yang perlu diperbaiki. Silakan periksa catatan...
```

---

## 🧪 Testing Checklist

### Test 1: Madrasah Status 'perbaiki'

- [ ] Set database:
  ```sql
  UPDATE daftar_ulang SET status = 'perbaiki' WHERE pendaftar_id = X;
  ```
- [ ] Login as pendaftar
- [ ] Buka `/pendaftar/daftar-ulang`
- [ ] **Expected**: Status Madrasah badge = "PERLU PERBAIKI" (orange) ✅
- [ ] **Expected**: Overall message = "Ada data yang perlu diperbaiki..." (orange) ✅

### Test 2: Seragam Status 'perbaiki'

- [ ] Set database:
  ```sql
  UPDATE seragam SET status_seragam = 'perbaiki' WHERE pendaftar_id = X;
  ```
- [ ] Refresh page
- [ ] **Expected**: Status Seragam badge = "PERLU PERBAIKI" (orange) ✅

### Test 3: Ukuran Status 'perbaiki'

- [ ] Set database:
  ```sql
  UPDATE seragam SET status_ukuran = 'perbaiki' WHERE pendaftar_id = X;
  ```
- [ ] Refresh page
- [ ] **Expected**: Status Ukuran badge = "PERLU PERBAIKI" (orange) ✅

### Test 4: Mixed Status (Approved + Perbaiki)

- [ ] Set database:
  ```sql
  UPDATE daftar_ulang SET status = 'disetujui' WHERE pendaftar_id = X;
  UPDATE seragam SET status_seragam = 'perbaiki', status_ukuran = 'menunggu' WHERE pendaftar_id = X;
  ```
- [ ] Refresh page
- [ ] **Expected**:
  - Madrasah = "DISETUJUI" (green) ✅
  - Seragam = "PERLU PERBAIKI" (orange) ✅
  - Ukuran = "MENUNGGU" (yellow) ✅
  - Overall = Orange "Ada data yang perlu diperbaiki..." ✅

### Test 5: Priority Order

#### Test 5a: Rejected > Perbaiki
- [ ] Set: `status = 'ditolak'`, `status_seragam = 'perbaiki'`
- [ ] **Expected**: Overall message = RED "Ada data yang ditolak..." ✅

#### Test 5b: Perbaiki > Waiting
- [ ] Set: `status = 'perbaiki'`, `status_seragam = 'menunggu'`
- [ ] **Expected**: Overall message = ORANGE "Ada data yang perlu diperbaiki..." ✅

### Test 6: All Status Types

- [ ] Test each status value for all 3 sections:
  - 'disetujui' → Green badge ✅
  - 'ditolak' → Red badge ✅
  - 'perbaiki' → Orange badge ✅
  - 'menunggu' → Yellow badge ✅
  - NULL/other → Gray badge ✅

---

## 📁 Files Modified

### 1. `resources/views/livewire/pendaftar/daftar-ulang.blade.php`

**Lines Modified**:

- **Lines 74-75**: Added 'perbaiki' check for Madrasah
- **Line 87**: Added 'perbaiki' match case for Madrasah
- **Lines 113-114**: Added 'perbaiki' check for Seragam
- **Line 126**: Added 'perbaiki' match case for Seragam
- **Lines 152-153**: Added 'perbaiki' check for Ukuran
- **Line 165**: Added 'perbaiki' match case for Ukuran
- **Line 183**: Added `$anyPerbaiki` variable
- **Lines 200-206**: Added orange overall message for 'perbaiki'

**Total Changes**: ~15 lines modified/added

---

## 🚀 Deployment Instructions

### Files to Upload

```
📁 Upload ke Production:

1. Frontend (resources/views/):
   - resources/views/livewire/pendaftar/daftar-ulang.blade.php

2. Documentation:
   - ADD_STATUS_PERBAIKI.md
```

### Deployment Steps

```bash
# Upload file
scp resources/views/livewire/pendaftar/daftar-ulang.blade.php user@server:/path/to/resources/views/livewire/pendaftar/

# Clear cache
ssh user@production-server
cd /path/to/project
php artisan view:clear
```

### No Database Changes Required

**IMPORTANT**:
- ✅ No database migration needed
- ✅ Uses existing status column
- ✅ 'perbaiki' is just a new enum value
- ✅ Database already supports any string value

---

## ✅ Benefits Summary

### For Pendaftar:

1. **Clear Status Differentiation**
   - ✅ Know difference between "ditolak" (rejected) vs "perbaiki" (needs revision)
   - ✅ Orange color indicates action needed but not as severe as red rejection
   - ✅ Clear instruction: "lakukan perbaikan"

2. **Better UX**
   - ✅ Less intimidating than red "DITOLAK"
   - ✅ Implies data can be fixed/revised
   - ✅ Encourages user to make corrections

### For Admin/Panitia:

1. **Flexible Status Options**
   - ✅ Can request revisions without fully rejecting
   - ✅ Differentiate between "needs minor fixes" vs "completely wrong"
   - ✅ Better communication with pendaftar

2. **Workflow Improvement**
   - ✅ 'perbaiki' → pendaftar makes changes → back to 'menunggu'
   - ✅ 'ditolak' → complete rejection, start over
   - ✅ Clear distinction in workflow

---

## 📝 Summary

### Added:
New status "PERLU PERBAIKI" (orange badge) for all 3 status sections

### Color Scheme:
- Green = Approved ✅
- Red = Rejected ❌
- **Orange = Needs Revision** 🖊️ NEW
- Yellow = Under Review ⏳
- Gray = Not Started ⚪

### Impact:
- ✅ Better status communication
- ✅ More flexible workflow
- ✅ Clearer user guidance
- ✅ No database changes needed

---

**Created**: 30 Januari 2026
**Status**: ✅ COMPLETE
**Version**: 1.0
**Author**: Development Team
**Priority**: MEDIUM (Feature Enhancement)
**Category**: Enhancement - Status Display

**User Request**: "jangan lupa ada lagi status perbaiki perlu juga di tambahkan"

**Resolution**: Added 'perbaiki' status (orange badge "PERLU PERBAIKI") to all 3 status displays (Madrasah, Seragam, Ukuran) with corresponding overall status message indicating revision is needed.
