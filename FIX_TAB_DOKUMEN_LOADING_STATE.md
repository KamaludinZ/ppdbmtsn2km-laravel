# Fix Tab Dokumen - Loading State per Button

**Tanggal:** 3 Februari 2026 - 16:30 WIB
**Status:** ✅ FIXED

---

## 🎯 MASALAH

**User Report:**
> "di /admin/pendaftar/27?activeTab=dokumen pada saat klik simpan status loding state nya di semua tombol dihalaman tersebut tidak sendiri setiap tombol."

**Errors:**
1. ❌ Klik "Simpan Status" di satu dokumen → **SEMUA** tombol di halaman menunjukkan loading state
2. ❌ User tidak bisa membedakan tombol mana yang sedang diproses
3. ❌ UX buruk - tombol lain terlihat disabled padahal tidak sedang diproses

**Root Cause:**
- `wire:target` menggunakan nama method generic: `"updateStatusDokumen"`
- Livewire tidak bisa membedakan tombol mana yang diklik
- Semua tombol dengan target yang sama menunjukkan loading state

---

## ✅ SOLUSI

**Buat wire:target spesifik dengan include parameter document key**

**File:** `resources/views/livewire/admin/detail-pendaftar.blade.php`

---

### Update Button Loading State (Lines 957-977)

**BEFORE:**
```blade
<button
    wire:click="updateStatusDokumen('{{ $dok['key'] }}')"
    wire:loading.attr="disabled"
    wire:target="updateStatusDokumen"  <!-- ❌ Generic - affect ALL buttons -->
    class="px-3 py-1.5 bg-emerald-600 text-white text-xs rounded hover:bg-emerald-700 transition disabled:opacity-50 disabled:cursor-not-allowed"
>
    <span wire:loading.remove wire:target="updateStatusDokumen" class="flex items-center gap-1">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
        Simpan Status
    </span>
    <span wire:loading wire:target="updateStatusDokumen" class="flex items-center gap-1">
        <svg class="animate-spin h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Menyimpan...
    </span>
</button>
```

**AFTER:**
```blade
<button
    wire:click="updateStatusDokumen('{{ $dok['key'] }}')"
    wire:loading.attr="disabled"
    wire:target="updateStatusDokumen('{{ $dok['key'] }}')"  <!-- ✅ Specific per document -->
    class="px-3 py-1.5 bg-emerald-600 text-white text-xs rounded hover:bg-emerald-700 transition disabled:opacity-50 disabled:cursor-not-allowed"
>
    <span wire:loading.remove wire:target="updateStatusDokumen('{{ $dok['key'] }}')" class="flex items-center gap-1">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
        Simpan Status
    </span>
    <span wire:loading wire:target="updateStatusDokumen('{{ $dok['key'] }}')" class="flex items-center gap-1">
        <svg class="animate-spin h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Menyimpan...
    </span>
</button>
```

**Key Changes:**
- ✅ Changed from: `wire:target="updateStatusDokumen"`
- ✅ Changed to: `wire:target="updateStatusDokumen('{{ $dok['key'] }}')"`
- ✅ Include document key parameter in target
- ✅ Now each button has unique target identifier

---

## 📁 FILE YANG DIUBAH

**File:** `resources/views/livewire/admin/detail-pendaftar.blade.php`

**Changes:** 3 locations per button (wire:target, wire:loading.remove, wire:loading)

**Lines Modified:** 957-977 (Tab Dokumen section)

**Sections Modified:**
1. Button `wire:target` attribute
2. "Simpan Status" span `wire:loading.remove`
3. "Menyimpan..." span `wire:loading`

---

## 🚀 CARA UPLOAD KE PRODUCTION

### Quick Upload:

```cmd
cd C:\ppdbmtsn2km-laravel
scp "resources/views/livewire/admin/detail-pendaftar.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/

ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan view:clear && rm -rf storage/framework/views/*.php"
```

**Atau gunakan batch script:**
```cmd
upload_fix_tab_dokumen_loading.bat
```

---

## ✅ TESTING CHECKLIST

### Test di Local:

**URL:** `/admin/pendaftar/27?activeTab=dokumen`
**Login as:** Admin atau Panitia

#### Test 1: Single Button Loading

1. **Setup:**
   - [ ] ✅ Tab Dokumen terbuka
   - [ ] ✅ Ada minimal 3 dokumen terlihat (KK, Akta, Ijazah, dll)

2. **Action:**
   - [ ] ✅ Klik "Simpan Status" di dokumen pertama (misal: KK)

3. **Expected Behavior - Button yang diklik:**
   - [ ] ✅ Text berubah dari "Simpan Status" → "Menyimpan..."
   - [ ] ✅ Icon berubah dari checkmark → spinning loader
   - [ ] ✅ Button menjadi disabled (opacity 50%)

4. **Expected Behavior - Button lain:**
   - [ ] ✅ Button dokumen lain tetap menunjukkan "Simpan Status"
   - [ ] ✅ Button dokumen lain **TIDAK** menunjukkan "Menyimpan..."
   - [ ] ✅ Button dokumen lain tetap interactive (bisa diklik)
   - [ ] ✅ Button dokumen lain **TIDAK** disabled

#### Test 2: Multiple Documents Sequentially

1. **Action:**
   - [ ] ✅ Tunggu request pertama selesai
   - [ ] ✅ Klik "Simpan Status" di dokumen kedua (misal: Akta)

2. **Expected Behavior:**
   - [ ] ✅ Hanya button dokumen kedua yang menunjukkan loading
   - [ ] ✅ Button dokumen lain (termasuk yang pertama) tetap normal

#### Test 3: Rapid Clicks (Edge Case)

1. **Action:**
   - [ ] ✅ Coba klik beberapa tombol berbeda secara cepat

2. **Expected Behavior:**
   - [ ] ✅ Setiap button menunjukkan loading state masing-masing
   - [ ] ✅ Tidak ada konflik antar button
   - [ ] ✅ Loading state clear setelah masing-masing request selesai

### Test di Production:

**URL:** `https://pmbm.mtsn2kotamalang.sch.id/admin/pendaftar/27?activeTab=dokumen`

**Quick Test:**
1. Login as Admin → Tab Dokumen
2. Klik "Simpan Status" di satu dokumen
3. Observe: **Hanya button itu** yang menunjukkan loading ✅
4. Observe: Button dokumen lain tetap normal ✅

**Hard refresh browser:** Ctrl + Shift + R

---

## 📝 CATATAN TEKNIS

### Livewire wire:target Behavior:

**Generic Target (BEFORE):**
```blade
wire:target="updateStatusDokumen"
```
- ❌ Livewire mencocokkan semua call ke method `updateStatusDokumen`
- ❌ Semua tombol dengan target yang sama akan show loading
- ❌ Tidak ada cara membedakan instance mana yang diklik

**Specific Target (AFTER):**
```blade
wire:target="updateStatusDokumen('{{ $dok['key'] }}')"
```
- ✅ Livewire mencocokkan method call **DAN** parameter
- ✅ Hanya tombol dengan parameter yang sama yang show loading
- ✅ Setiap dokumen punya unique key → unique target

### Document Keys in Loop:

```php
@foreach($dokumenVerifikasi as $dok)
    <!-- Each $dok['key'] is unique: 'kk', 'akta_kelahiran', 'ijazah', etc. -->
    wire:target="updateStatusDokumen('{{ $dok['key'] }}')"
@endforeach
```

**Example:**
- Dokumen KK: `wire:target="updateStatusDokumen('kk')"`
- Dokumen Akta: `wire:target="updateStatusDokumen('akta_kelahiran')"`
- Dokumen Ijazah: `wire:target="updateStatusDokumen('ijazah')"`

Each has **unique** target identifier → independent loading states.

### Why Three Locations?

```blade
wire:target="..."                      <!-- 1. Disable button during loading -->
wire:loading.remove wire:target="..."  <!-- 2. Hide normal state during loading -->
wire:loading wire:target="..."         <!-- 3. Show loading state -->
```

All three must use **same specific target** for consistency.

---

## 📊 SUMMARY

| Issue | Status | Impact |
|-------|--------|--------|
| All buttons show loading | ✅ FIXED | Each button independent now |
| Cannot identify which processing | ✅ FIXED | Clear visual feedback per button |
| Poor UX | ✅ IMPROVED | Professional loading state per action |
| Button specificity | ✅ IMPLEMENTED | wire:target includes parameters |

**Lines Changed:** ~20 lines (3 locations per button template)

**User Satisfaction Expected:** ⭐⭐⭐⭐⭐
- Clear feedback per button
- Other buttons remain interactive
- Professional UX
- Matches standard app behavior

---

## 🎯 BEST PRACTICE

### Livewire Loading States:

**DO:**
- ✅ Include method parameters in `wire:target` for specificity
- ✅ Use unique identifiers (like document keys) in loops
- ✅ Apply same target to all related directives
- ✅ Test with multiple items in list

**DON'T:**
- ❌ Use generic method name only in loops
- ❌ Mix specific and generic targets
- ❌ Forget to update all three loading directives
- ❌ Assume Livewire auto-detects which button clicked

### Pattern for Lists:

```blade
@foreach($items as $item)
    <button
        wire:click="action('{{ $item->id }}')"
        wire:target="action('{{ $item->id }}')"  <!-- Include parameter -->
    >
        <span wire:loading.remove wire:target="action('{{ $item->id }}')">
            Normal State
        </span>
        <span wire:loading wire:target="action('{{ $item->id }}')">
            Loading State
        </span>
    </button>
@endforeach
```

---

## 🔄 RELATED FIXES

**Similar Pattern Used In:**
- Tab Prestasi: Edit/Save buttons (already specific per tab)
- Tab Orangtua: Edit/Save buttons (already specific per tab)
- Tab Siswa: Edit/Save buttons (already specific per tab)

**This Fix Completes:**
The pattern of specific loading states across all tabs in Detail Pendaftar view.

---

**Prepared by:** Claude Code Assistant
**Date:** 3 Februari 2026 - 16:30 WIB
**Status:** ✅ READY TO UPLOAD TO PRODUCTION
