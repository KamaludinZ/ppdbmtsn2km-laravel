# CHECKLIST PERBAIKAN TOMBOL DASHBOARD

## 🔴 PRIORITAS 1 - HARUS SELESAI MINGGU INI

### A. Export Functions (Admin & Panitia Laporan)
- [ ] Install Laravel Excel: `composer require maatwebsite/excel`
- [ ] Implementasi `exportDataLengkap()` di Admin/Laporan.php
- [ ] Implementasi `exportDaftarUlang()` di Admin/Laporan.php
- [ ] Implementasi `exportSeragam()` di Admin/Laporan.php
- [ ] Copy implementasi ke Panitia/Laporan.php
- [ ] Test download Excel files

### B. Delete Confirmations (Admin DaftarUlang)
File: `resources/views/livewire/admin/daftar-ulang.blade.php`

- [ ] Line 71: Add `wire:confirm="Hapus nominal madrasah ini?"`
- [ ] Line 84: Add `wire:confirm="Hapus bank madrasah ini?"`
- [ ] Line 118: Add `wire:confirm="Hapus paket seragam ini?"`
- [ ] Line 131: Add `wire:confirm="Hapus nominal seragam ini?"`
- [ ] Line 164: Add `wire:confirm="Hapus ukuran jas ini?"`
- [ ] Line 177: Add `wire:confirm="Hapus ukuran atasan ini?"`
- [ ] Line 190: Add `wire:confirm="Hapus ukuran bawahan ini?"`
- [ ] Line 203: Add `wire:confirm="Hapus ukuran kopyah ini?"`

### C. Fix Confirmation Pattern (5 Files)
Replace `onclick="confirm() || event.stopImmediatePropagation()"` with `wire:confirm`:

- [ ] admin/jalur.blade.php (Line 180)
- [ ] admin/survey-management.blade.php (Lines 145, 163)
- [ ] admin/pengaturan-kartu.blade.php (Lines 132, 155)
- [ ] admin/daftar-ulang.blade.php (Line 389)
- [ ] panitia/jalur.blade.php

---

## 🟠 PRIORITAS 2 - SPRINT INI

### D. Complete EmisInput Downloads
File: `app/Livewire/Panitia/EmisInput.php`

Add missing cases in downloadDocument():
- [ ] case 'skhun': implement download
- [ ] case 'pas_foto': implement download
- [ ] case 'surat_keterangan_lulus': implement download

### E. Add Loading Indicators
Template for all export/download buttons:
```html
<button wire:click="export"
        wire:loading.attr="disabled"
        wire:loading.class="opacity-50 cursor-wait">
    <span wire:loading.remove>Export</span>
    <span wire:loading class="flex items-center">
        <svg class="animate-spin h-5 w-5 mr-2">...</svg>
        Processing...
    </span>
</button>
```

Apply to:
- [ ] All export buttons in Admin/Laporan
- [ ] All export buttons in Panitia/Laporan
- [ ] All download buttons in Panitia/EmisInput
- [ ] Bulk operations in Admin/Kelulusan
- [ ] Save buttons taking > 2 seconds

### F. Protect Bulk Operations
File: `resources/views/livewire/admin/kelulusan.blade.php`

- [ ] Line 85-91: Add `wire:confirm="Set LULUS untuk SEMUA pendaftar?"`
- [ ] Line 92-98: Add `wire:confirm="Set TIDAK LULUS untuk SEMUA pendaftar?"`
- [ ] Add double confirmation for operations affecting > 10 records

---

## 🟡 PRIORITAS 3 - SPRINT BERIKUTNYA

### G. Fix Modal Dismissal
Remove `wire:click="closeModal"` from overlay div:
- [ ] panitia/jadwal.blade.php
- [ ] panitia/jalur.blade.php
- [ ] panitia/pengumuman.blade.php
- [ ] panitia/tes-psikologi.blade.php

### H. Standardize Button Types
- [ ] Submit buttons: `type="submit"`
- [ ] Regular buttons: `type="button"`
- [ ] Reset buttons: `type="reset"`

### I. Add Button States
- [ ] Disabled state during processing
- [ ] Success feedback after operation
- [ ] Error handling with user feedback

---

## 🧪 TESTING CHECKLIST

### After Each Fix:
- [ ] Test button functionality works
- [ ] Test confirmation appears (if applicable)
- [ ] Test loading state shows (if applicable)
- [ ] Test error handling
- [ ] Test on mobile device
- [ ] Test with slow connection

### Final Testing:
- [ ] All export buttons generate files
- [ ] All delete buttons show confirmation
- [ ] No buttons can be double-clicked
- [ ] All modals close properly
- [ ] Loading indicators show for > 1s operations

---

## 📋 QUICK FIXES (< 5 minutes each)

1. **Add to all delete buttons:**
   ```html
   wire:confirm="Yakin ingin menghapus?"
   ```

2. **Add to all export buttons:**
   ```html
   wire:loading.attr="disabled"
   wire:loading.class="opacity-50"
   ```

3. **Fix all form buttons:**
   ```html
   type="submit" <!-- for form submission -->
   type="button" <!-- for regular actions -->
   ```

4. **Prevent double-click:**
   ```html
   wire:click="save"
   wire:loading.attr="disabled"
   onclick="this.disabled=true"
   ```

---

## 🚀 DEPLOYMENT NOTES

Before deploying fixes:
1. Clear all caches: `php artisan optimize:clear`
2. Run tests: `php artisan test`
3. Check in staging first
4. Monitor error logs after deployment
5. Get user feedback on critical functions

---

*Use this checklist to track progress on fixing button issues*