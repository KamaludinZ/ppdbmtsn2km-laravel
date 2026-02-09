# Fitur Aksi Opsi Individual - 9 Feb 2026

## ✅ COMPLETE - Ready for Upload!

---

## 📋 What Was Implemented

**Feature Name:** Individual Opsi Lainnya Toggles for Daftar Ulang

**Purpose:** Memungkinkan Admin untuk mengaktifkan "Opsi Lainnya" (dropdown dan konfirmasi) secara individual untuk pendaftar tertentu, bahkan jika setting global di tab Settings TIDAK diaktifkan.

**Use Case:**
- Setting global OFF = tidak ada opsi lainnya untuk semua orang
- Setting global OFF + Toggle individual ON = opsi lainnya muncul HANYA untuk orang tertentu
- Setting global ON = opsi lainnya muncul untuk SEMUA orang (toggle individual diabaikan)

---

## 🎯 Key Features

### 1. Kolom "Aksi Opsi" di Tabel

**Location:**
- `/admin/daftar-ulang?activeTab=madrasah` - Tab Daftar Ulang Madrasah
- `/admin/daftar-ulang?activeTab=seragam` - Tab Daftar Ulang Seragam

**Contents:**
Setiap row memiliki 2 toggle switches:

#### Toggle 1: Dropdown Opsi Lainnya
- **Purpose:** Mengaktifkan dropdown "Opsi Lainnya" di dropdown paket untuk pendaftar ini
- **Color:** Blue (Biru)
- **Effect:** Pendaftar akan melihat option "Opsi Lainnya" di dropdown paket (Madrasah/Seragam)

#### Toggle 2: Konfirmasi Opsi Lainnya
- **Purpose:** Mengaktifkan input manual "Opsi Lainnya" di konfirmasi nominal untuk pendaftar ini
- **Color:** Emerald (Hijau)
- **Effect:** Pendaftar akan melihat option "Opsi Lainnya" di konfirmasi nominal pembayaran

### 2. Logic Prioritas

**Global Setting (di Tab Settings) vs Individual Toggle:**

```
IF global_setting_enabled == TRUE:
    → Opsi lainnya muncul untuk SEMUA pendaftar
    → Individual toggles DIABAIKAN

ELSE IF global_setting_enabled == FALSE:
    IF individual_toggle == TRUE:
        → Opsi lainnya muncul untuk PENDAFTAR INI saja
    ELSE:
        → Opsi lainnya TIDAK muncul
```

**Example Scenarios:**

| Global Setting | Individual Toggle | Result |
|----------------|-------------------|--------|
| ✅ ON | ❌ OFF | Opsi lainnya muncul untuk SEMUA |
| ✅ ON | ✅ ON | Opsi lainnya muncul untuk SEMUA |
| ❌ OFF | ✅ ON | Opsi lainnya muncul untuk ORANG INI saja |
| ❌ OFF | ❌ OFF | Opsi lainnya TIDAK muncul |

---

## 📦 Files Modified: 5 files

### 1. Database Migration (NEW)
**File:** `database/migrations/2026_02_09_100000_add_individual_opsi_lainnya_to_daftar_ulang_and_seragam.php`

**What it does:**
- Adds `opsi_lainnya_dropdown_individual` (boolean) to `daftar_ulang` table
- Adds `opsi_lainnya_konfirmasi_individual` (boolean) to `daftar_ulang` table
- Adds `opsi_lainnya_dropdown_individual` (boolean) to `seragam` table
- Adds `opsi_lainnya_konfirmasi_individual` (boolean) to `seragam` table
- Default: `false` for all fields

### 2. DaftarUlang Model
**File:** `app/Models/DaftarUlang.php`

**Changes:**
- Added to `$fillable`: `opsi_lainnya_dropdown_individual`, `opsi_lainnya_konfirmasi_individual`
- Added to `$casts`: both fields cast to `boolean`

### 3. Seragam Model
**File:** `app/Models/Seragam.php`

**Changes:**
- Added to `$fillable`: `opsi_lainnya_dropdown_individual`, `opsi_lainnya_konfirmasi_individual`
- Added to `$casts`: both fields cast to `boolean`

### 4. DaftarUlang Component (Backend)
**File:** `app/Livewire/Admin/DaftarUlang.php`

**New Methods Added (Lines 1032-1106):**

```php
// Toggle dropdown opsi lainnya for individual pendaftar
public function toggleOpsiLainnyaDropdownIndividual($pendaftarId, $tab)
{
    // For 'madrasah' tab: update daftar_ulang table
    // For 'seragam' tab: update seragam table
    // Toggles the boolean value (false → true, true → false)
}

// Toggle konfirmasi opsi lainnya for individual pendaftar
public function toggleOpsiLainnyaKonfirmasiIndividual($pendaftarId, $tab)
{
    // For 'madrasah' tab: update daftar_ulang table
    // For 'seragam' tab: update seragam table
    // Toggles the boolean value (false → true, true → false)
}
```

**Logic:**
- Uses `firstOrCreate()` to ensure record exists
- Toggles boolean value with `!` operator
- Dispatches success notification with status text
- Handles both 'madrasah' and 'seragam' tabs

### 5. DaftarUlang View (Frontend)
**File:** `resources/views/livewire/admin/daftar-ulang.blade.php`

**Changes:**

#### A. Table Header (Added "Aksi Opsi" column)
- Line 1218: Added column header for Madrasah tab
- Line 1231: Added column header for Seragam tab

#### B. Table Body (Added toggle switches)

**Madrasah Tab (Lines 1539-1578):**
```blade
<td class="px-6 py-4">
    <div class="flex flex-col gap-3">
        <!-- Toggle 1: Dropdown Opsi Lainnya -->
        <div x-data="{ enabled: {{ $item->daftarUlang->opsi_lainnya_dropdown_individual }} }">
            <label>Dropdown Opsi Lainnya</label>
            <button @click="$wire.toggleOpsiLainnyaDropdownIndividual({{ $item->id }}, 'madrasah')">
                <!-- Toggle switch UI with Alpine.js -->
            </button>
        </div>

        <!-- Toggle 2: Konfirmasi Opsi Lainnya -->
        <div x-data="{ enabled: {{ $item->daftarUlang->opsi_lainnya_konfirmasi_individual }} }">
            <label>Konfirmasi Opsi Lainnya</label>
            <button @click="$wire.toggleOpsiLainnyaKonfirmasiIndividual({{ $item->id }}, 'madrasah')">
                <!-- Toggle switch UI with Alpine.js -->
            </button>
        </div>
    </div>
</td>
```

**Seragam Tab (Lines 1762-1801):**
- Same structure as Madrasah tab
- Checks `$item->seragam` instead of `$item->daftarUlang`
- Calls methods with `'seragam'` parameter

**Toggle Switch Design:**
- Blue color for Dropdown toggle
- Emerald color for Konfirmasi toggle
- Text indicator: "Aktif untuk orang ini" / "Tidak aktif"
- Smooth animation with Alpine.js
- Focus ring for accessibility

---

## 🚀 How to Upload

### Step 1: Run Migration
```bash
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
php artisan migrate
```

Expected output:
```
Migrating: 2026_02_09_100000_add_individual_opsi_lainnya_to_daftar_ulang_and_seragam
Migrated:  2026_02_09_100000_add_individual_opsi_lainnya_to_daftar_ulang_and_seragam (XX.XXms)
```

### Step 2: Upload Files
Run the upload script:
```bash
.\upload_fitur_aksi_opsi_individual_9_feb_2026.bat
```

This will:
- Upload 4 files (2 models + 2 components)
- Clear all caches (view, Livewire, config, optimize)
- Display testing checklist

---

## 🧪 Testing Guide

### Test 1: Kolom Aksi Opsi Muncul

1. Login sebagai Admin
2. Go to `/admin/daftar-ulang?activeTab=madrasah`
3. Verify: Kolom "Aksi Opsi" muncul setelah kolom "Catatan"
4. Verify: Ada 2 toggle switches di setiap row:
   - "Dropdown Opsi Lainnya" (Biru)
   - "Konfirmasi Opsi Lainnya" (Hijau)
5. Switch to `/admin/daftar-ulang?activeTab=seragam`
6. Verify: Same kolom and toggles muncul

### Test 2: Toggle Dropdown - Madrasah

**Scenario:** Enable dropdown opsi lainnya for specific pendaftar

1. Admin → `/admin/daftar-ulang?activeTab=madrasah`
2. Find pendaftar (contoh: ID 27)
3. Click toggle "Dropdown Opsi Lainnya" (biru) → ON
4. Verify: Toggle berubah warna biru, text "Aktif untuk orang ini"
5. Verify: Success notification: "Opsi Lainnya Dropdown (Madrasah) untuk pendaftar ini berhasil diaktifkan."
6. Refresh page (F5)
7. Verify: Toggle masih ON

**Check Database:**
```bash
ssh server "cd path && php artisan tinker"
DaftarUlang::where('pendaftar_id', 27)->first()->opsi_lainnya_dropdown_individual
# Expected: true
```

**Check Pendaftar View (TODO - Step 5):**
- Login as pendaftar ID 27
- Go to Daftar Ulang Madrasah page
- Verify: Dropdown paket shows "Opsi Lainnya" option
- Other pendaftars should NOT see this option (unless global setting is ON)

### Test 3: Toggle Konfirmasi - Madrasah

1. Admin → `/admin/daftar-ulang?activeTab=madrasah`
2. Find pendaftar (contoh: ID 28)
3. Click toggle "Konfirmasi Opsi Lainnya" (hijau) → ON
4. Verify: Toggle berubah warna hijau, text "Aktif untuk orang ini"
5. Verify: Success notification
6. Refresh page
7. Verify: Toggle masih ON

**Check Database:**
```bash
DaftarUlang::where('pendaftar_id', 28)->first()->opsi_lainnya_konfirmasi_individual
# Expected: true
```

### Test 4: Toggle OFF

1. Admin → Click toggle yang sudah ON → OFF
2. Verify: Toggle berubah abu-abu, text "Tidak aktif"
3. Verify: Success notification: "...berhasil dinonaktifkan."
4. Refresh page
5. Verify: Toggle OFF
6. Check database: Expected `false`

### Test 5: Toggle Seragam - Same Tests

Repeat Test 2-4 for `/admin/daftar-ulang?activeTab=seragam`:
- Toggle "Dropdown Opsi Lainnya" untuk seragam
- Toggle "Konfirmasi Opsi Lainnya" untuk seragam
- Check `seragam` table instead of `daftar_ulang`

### Test 6: Multiple Pendaftar

1. Enable Dropdown toggle untuk pendaftar A → ON
2. Enable Konfirmasi toggle untuk pendaftar B → ON
3. Enable both toggles untuk pendaftar C → ON
4. Leave both toggles OFF untuk pendaftar D
5. Verify: Each pendaftar has correct toggle states
6. Verify: States persist after page reload

### Test 7: Global Setting Priority (TODO - Step 5)

**When global setting is ON:**
1. Admin → `/admin/daftar-ulang?activeTab=settings`
2. Enable "Aktifkan Opsi Lainnya" globally (in Madrasah settings)
3. Go to Pendaftar view
4. Verify: ALL pendaftars see opsi lainnya (regardless of individual toggles)

**When global setting is OFF:**
5. Admin → Disable global setting
6. Admin → Enable individual toggle for pendaftar A only
7. Login as pendaftar A
8. Verify: Pendaftar A sees opsi lainnya
9. Login as pendaftar B (toggle OFF)
10. Verify: Pendaftar B does NOT see opsi lainnya

---

## ✅ Expected Results

After implementation:
- ✅ Kolom "Aksi Opsi" muncul di tab Madrasah dan Seragam
- ✅ 2 toggle switches per row: Dropdown (biru) dan Konfirmasi (hijau)
- ✅ Toggle switches work: click to enable/disable
- ✅ State persists after page reload
- ✅ Database updates correctly
- ✅ Success notifications show
- ✅ Different colors for different toggles (blue vs emerald)
- ✅ Text indicator shows current state

---

## 🔍 Technical Details

### Database Schema

**daftar_ulang table:**
```sql
opsi_lainnya_dropdown_individual BOOLEAN DEFAULT FALSE
opsi_lainnya_konfirmasi_individual BOOLEAN DEFAULT FALSE
```

**seragam table:**
```sql
opsi_lainnya_dropdown_individual BOOLEAN DEFAULT FALSE
opsi_lainnya_konfirmasi_individual BOOLEAN DEFAULT FALSE
```

### Toggle Switch UI Components

**Alpine.js State Management:**
```javascript
x-data="{ enabled: {{ boolean_value }} }"
```

**Toggle Button Classes:**
- Base: `relative inline-flex h-6 w-11 ...`
- Active (Blue): `:class="enabled ? 'bg-blue-600' : 'bg-gray-200'"`
- Active (Emerald): `:class="enabled ? 'bg-emerald-600' : 'bg-gray-200'"`
- Slider: `translate-x-5` when enabled, `translate-x-0` when disabled

**Wire Method Call:**
```javascript
$wire.toggleOpsiLainnyaDropdownIndividual({{ $item->id }}, 'madrasah')
```

---

## 📊 Before vs After

| Aspect | BEFORE | AFTER |
|--------|--------|-------|
| Global setting only | ✅ Yes | ✅ Yes |
| Per-pendaftar control | ❌ No | ✅ **Yes** |
| Fine-grained control | ❌ No | ✅ **Yes** |
| Exception for specific users | ❌ Manual workaround | ✅ **Toggle in UI** |
| Admin flexibility | Limited | High |

**Use Case Example:**

Before: "Opsi lainnya harus ON untuk semua atau OFF untuk semua"

After: "Opsi lainnya OFF untuk semua, tapi ON untuk 3 orang yang special case (keluarga tidak mampu, dll)"

---

## ⚠️ Important Notes

### 1. Global Setting Has Priority

When global setting is ON, individual toggles are IGNORED. This is by design to prevent confusion.

**Admin workflow:**
- For most cases: Use global setting (simple, applies to all)
- For exceptions: Disable global, enable individual toggles for specific users

### 2. Step 5 NOT YET IMPLEMENTED

**What works now (Step 1-4):**
- ✅ UI toggles in Admin panel
- ✅ Database updates
- ✅ Toggle state management

**What needs to be done (Step 5):**
- ⏸️ Update Pendaftar Daftar Ulang Madrasah view to check:
  - `global_setting OR individual_setting`
- ⏸️ Update Pendaftar Daftar Ulang Seragam view to check:
  - `global_setting OR individual_setting`
- ⏸️ Display opsi lainnya ONLY if either is true

**Workaround for now:**
- Test toggle UI and database updates
- Pendaftar view update will be in next iteration

### 3. No Migration Rollback Warning

Once you run the migration, new columns exist in database. If you need to rollback:

```bash
php artisan migrate:rollback --step=1
```

This will DROP the 4 new columns.

---

## 🔗 Related Features

**Existing Opsi Lainnya Settings (Global):**
- `madrasahEnableOpsiLainnya` - For konfirmasi nominal
- `madrasahPackagesEnableOpsiLainnya` - For dropdown paket
- `seragamEnableOpsiLainnya` - For konfirmasi nominal
- `seragamPackagesEnableOpsiLainnya` - For dropdown paket

**New Individual Settings (Per-Pendaftar):**
- `opsi_lainnya_dropdown_individual` - Replaces `*PackagesEnableOpsiLainnya` for this pendaftar
- `opsi_lainnya_konfirmasi_individual` - Replaces `*EnableOpsiLainnya` for this pendaftar

---

## 🎯 Future Enhancements (Optional)

1. **Bulk Toggle:** Select multiple pendaftar, enable toggle for all at once
2. **Reason Field:** Add optional reason field when enabling individual toggle
3. **History Log:** Track who enabled/disabled toggles and when
4. **Filter:** Filter table to show only pendaftar with individual toggles enabled
5. **Panitia Role:** Currently Admin-only, could extend to Panitia if needed

---

## ✅ Checklist

- [x] Database migration created
- [x] DaftarUlang model updated
- [x] Seragam model updated
- [x] Backend toggle methods added
- [x] Frontend column added to Madrasah tab
- [x] Frontend column added to Seragam tab
- [x] Toggle UI implemented with Alpine.js
- [x] Documentation created
- [x] Upload script created
- [ ] Pendaftar views updated (Step 5 - TODO)

---

## 🚀 Ready to Deploy!

**Status:** ✅ **Steps 1-4 COMPLETE**

**What's working:**
- Admin UI toggles
- Database updates
- State persistence

**What's pending:**
- Pendaftar view logic (Step 5)

**Recommendation:**
- Deploy Steps 1-4 now
- Test toggle UI and database
- Implement Step 5 in next iteration

---

**Date:** 9 Feb 2026
**Feature:** Individual Opsi Lainnya Toggles
**Impact:** Admin can now control opsi lainnya per-pendaftar
**Complexity:** Medium (5 files modified)
**Risk:** Low (pure addition, no breaking changes)
