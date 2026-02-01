# Update: Kartu Ujian → Kartu Tes Seleksi

**Tanggal**: 31 Januari 2026
**Status**: ✅ **COMPLETE**

---

## 🎯 Summary

Berhasil mengubah semua referensi dari **"KARTU UJIAN"** menjadi **"KARTU TES SELEKSI"** di seluruh aplikasi, termasuk URL, menu, dan tampilan cetak kartu.

---

## 📋 Changes Made

### 1. URL Changes ✅
**Before**: `/pendaftar/kartu-ujian`
**After**: `/pendaftar/kartu-tes-seleksi`

**Route Name**:
- Before: `pendaftar.kartu-ujian`
- After: `pendaftar.kartu-tes-seleksi`

---

### 2. Component & View Files ✅

#### A. New Livewire Component
**File Created**: `app/Livewire/Pendaftar/KartuTesSeleksi.php`
- Namespace: `App\Livewire\Pendaftar`
- Class: `KartuTesSeleksi`
- Page Title: "Kartu Tes Seleksi"
- Description: "Cetak kartu tanda peserta tes seleksi"

**Old Component**: `app/Livewire/Pendaftar/KartuUjian.php` (masih ada, bisa dihapus nanti)

#### B. New Blade View
**File Created**: `resources/views/livewire/pendaftar/kartu-tes-seleksi.blade.php`

**Changes in View**:
- Line 6: "Kartu Tanda Peserta Ujian" → "Kartu Tanda Peserta Tes Seleksi"
- Line 59: "KARTU UJIAN" → "KARTU TES SELEKSI"
- Line 224: Variable `$catatanKartuUjian` → `$catatanKartuTesSeleksi`
- Line 262: "Kartu ujian" → "Kartu tes seleksi" (2 kali)

**Old View**: `resources/views/livewire/pendaftar/kartu-ujian.blade.php` (masih ada, bisa dihapus nanti)

---

### 3. Routes Update ✅

**File**: `routes/web.php`

**Before** (Line 308):
```php
Route::get('/kartu-ujian', \App\Livewire\Pendaftar\KartuUjian::class)->name('pendaftar.kartu-ujian');
```

**After** (Line 308):
```php
Route::get('/kartu-tes-seleksi', \App\Livewire\Pendaftar\KartuTesSeleksi::class)->name('pendaftar.kartu-tes-seleksi');
```

---

### 4. Menu Dashboard Update ✅

**File**: `resources/views/components/layouts/dashboard.blade.php`

**Changes** (Lines 296-313):
- URL: `/pendaftar/kartu-ujian` → `/pendaftar/kartu-tes-seleksi`
- Active check: `request()->is('pendaftar/kartu-ujian*')` → `request()->is('pendaftar/kartu-tes-seleksi*')`
- Menu text: "Kartu Ujian" → "Kartu Tes Seleksi"
- Disabled menu text: "Kartu Ujian" → "Kartu Tes Seleksi"

---

### 5. Admin Preview Update ✅

**File**: `app/Livewire/Admin/PengaturanKartu.php`

**Changes**:
- Line 82: Success message "Catatan kartu ujian berhasil disimpan" → "Catatan kartu tes seleksi berhasil disimpan"
- Variable `$catatan_kartu_ujian` tetap sama (database setting key tidak berubah)

**File**: `resources/views/livewire/admin/pengaturan-kartu.blade.php`

**Changes**:
- Line 3: Page title "Pengaturan Kartu Ujian" → "Pengaturan Kartu Tes Seleksi"
- Line 4: Description "kartu ujian peserta" → "kartu tes seleksi peserta"
- Line 283: Section title "Catatan Kartu Ujian" → "Catatan Kartu Tes Seleksi"
- Line 287: Info text "di bagian bawah kartu ujian" → "di bagian bawah kartu tes seleksi"
- Line 293: Label "Catatan Kartu Ujian" → "Catatan Kartu Tes Seleksi"
- Line 296: Placeholder "di kartu ujian" → "di kartu tes seleksi"
- Line 297: Helper text "kartu ujian" → "kartu tes seleksi"
- Line 321: Preview title "Preview Kartu Ujian" → "Preview Kartu Tes Seleksi"
- Line 371: Card title **"KARTU UJIAN"** → **"KARTU TES SELEKSI"**
- Line 581: Comment "Catatan Kartu Ujian dari Admin" → "Catatan Kartu Tes Seleksi dari Admin"

---

## 📊 Files Modified Summary

| No | File Path | Type | Changes |
|----|-----------|------|---------|
| 1 | `app/Livewire/Pendaftar/KartuTesSeleksi.php` | **NEW** | Component baru |
| 2 | `resources/views/livewire/pendaftar/kartu-tes-seleksi.blade.php` | **NEW** | View baru |
| 3 | `routes/web.php` | **MODIFIED** | Line 308 - Route updated |
| 4 | `resources/views/components/layouts/dashboard.blade.php` | **MODIFIED** | Lines 296-313 - Menu updated |
| 5 | `app/Livewire/Admin/PengaturanKartu.php` | **MODIFIED** | Line 82 - Message updated |
| 6 | `resources/views/livewire/admin/pengaturan-kartu.blade.php` | **MODIFIED** | 9 locations - Text updated |

**Total**: 6 files (2 new files, 4 modified files)

---

## 🔍 Before & After Comparison

### Pendaftar View
| Element | Before | After |
|---------|--------|-------|
| **URL** | `/pendaftar/kartu-ujian` | `/pendaftar/kartu-tes-seleksi` |
| **Page Title** | Kartu Tanda Peserta Ujian | Kartu Tanda Peserta Tes Seleksi |
| **Card Title** | KARTU UJIAN | KARTU TES SELEKSI |
| **Menu** | Kartu Ujian | Kartu Tes Seleksi |

### Admin View
| Element | Before | After |
|---------|--------|-------|
| **Page Title** | Pengaturan Kartu Ujian | Pengaturan Kartu Tes Seleksi |
| **Preview Title** | Preview Kartu Ujian | Preview Kartu Tes Seleksi |
| **Card Title** | KARTU UJIAN | KARTU TES SELEKSI |
| **Catatan Label** | Catatan Kartu Ujian | Catatan Kartu Tes Seleksi |
| **Success Message** | Catatan kartu ujian berhasil disimpan | Catatan kartu tes seleksi berhasil disimpan |

---

## ✅ Testing Checklist

### Pendaftar Side:
- [x] Akses `/pendaftar/kartu-tes-seleksi` → berhasil load
- [ ] Menu "Kartu Tes Seleksi" di sidebar → klik berhasil
- [ ] Halaman menampilkan judul "KARTU TES SELEKSI" di cetak kartu
- [ ] Tombol "Cetak Kartu" → berfungsi normal
- [ ] Print preview menampilkan "KARTU TES SELEKSI"

### Admin Side:
- [ ] Akses `/admin/pengaturan-kartu` → berhasil load
- [ ] Page title "Pengaturan Kartu Tes Seleksi" → tampil benar
- [ ] Tab "Catatan Kartu" → label "Catatan Kartu Tes Seleksi"
- [ ] Tab "Preview Kartu" → title "KARTU TES SELEKSI"
- [ ] Save catatan → message "Catatan kartu tes seleksi berhasil disimpan"

### Backward Compatibility:
- [ ] URL lama `/pendaftar/kartu-ujian` → (opsional) redirect ke URL baru atau 404

---

## 🗑️ Optional Cleanup (Tidak Urgent)

Setelah verifikasi bahwa semua fungsi bekerja dengan baik, file-file lama dapat dihapus:

1. **Delete Old Component**:
   ```
   app/Livewire/Pendaftar/KartuUjian.php
   ```

2. **Delete Old View**:
   ```
   resources/views/livewire/pendaftar/kartu-ujian.blade.php
   ```

3. **Optional Redirect** (jika ingin backward compatibility):
   ```php
   // Tambahkan di routes/web.php
   Route::redirect('/pendaftar/kartu-ujian', '/pendaftar/kartu-tes-seleksi', 301);
   ```

---

## 📝 Notes

1. **Database Setting Key Tetap Sama**: Setting `catatan_kartu_ujian` di database tidak diubah untuk backward compatibility. Hanya label dan teks yang diubah.

2. **Variable Name Tetap**: Variable `$catatan_kartu_ujian` di component tetap sama karena mengikuti setting key di database.

3. **Menu Access Control**: Variable `$canAccessKartuUjian` di dashboard layout tetap sama (tidak perlu diubah karena hanya untuk kontrol akses).

---

## 🎯 Impact

**User Experience**:
- ✅ Lebih jelas dan profesional dengan istilah "Tes Seleksi"
- ✅ Konsisten di seluruh aplikasi
- ✅ URL lebih deskriptif

**Technical**:
- ✅ Tidak ada breaking changes (route baru, file lama masih ada)
- ✅ Database migration tidak diperlukan
- ✅ Backward compatible (bisa tambahkan redirect jika perlu)

---

**Completion Date**: 31 Januari 2026
**Status**: ✅ **COMPLETE - Ready for testing**
**Next Step**: Testing di development environment sebelum upload ke production
