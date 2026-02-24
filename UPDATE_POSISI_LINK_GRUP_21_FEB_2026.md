# UPDATE POSISI LINK GRUP DAFTAR ULANG - 21 FEBRUARI 2026

## 🔄 PERUBAHAN YANG DILAKUKAN

### Sebelumnya (Original):
- **Posisi**: Di bawah status "Data Daftar Ulang Madrasah Sudah Disetujui"
- **Kriteria Muncul**: Status daftar ulang madrasah = **'disetujui'** (sudah approved panitia)
- **Timing**: Setelah panitia approve daftar ulang

### Sekarang (Updated):
- **Posisi**: **DI ATAS bagian "Surat Pernyataan"** ⭐
- **Kriteria Muncul**: User bisa akses halaman daftar ulang (status_pendaftaran = **'data_disetujui'**)
- **Timing**: **SEGERA** setelah pendaftar setuju daftar ulang (TIDAK perlu tunggu approval panitia)

---

## 🎯 ALASAN PERUBAHAN

1. **User Experience**: Pendaftar bisa segera bergabung grup setelah setuju daftar ulang
2. **Koordinasi Lebih Awal**: Panitia bisa langsung komunikasi dengan pendaftar sejak awal proses
3. **Posisi Lebih Prominent**: Di atas Surat Pernyataan = lebih terlihat
4. **Call-to-Action Jelas**: Tambahan text "Segera Bergabung" dengan informasi lengkap

---

## 📝 DETAIL PERUBAHAN KODE

### File Modified: `resources/views/livewire/pendaftar/daftar-ulang.blade.php`

#### Perubahan 1: Hapus Tombol dari Posisi Lama

**Dihapus** (line 371-401 - posisi lama):
```blade
@if($isMadrasahApproved)
    <!-- ... status approved box ... -->

    @php
        $linkGrupDaftarUlangEnabled = \App\Models\Setting::get('link_grup_daftar_ulang_enabled', false);
        $linkGrupDaftarUlangUrl = \App\Models\Setting::get('link_grup_daftar_ulang_url', '');
    @endphp

    @if($linkGrupDaftarUlangEnabled && !empty($linkGrupDaftarUlangUrl))
        <!-- Tombol Bergabung Grup (POSISI LAMA) -->
    @endif
@endif
```

#### Perubahan 2: Tambah Tombol di Posisi Baru

**Ditambahkan** (line 578-613 - posisi baru):
```blade
@else
{{-- Form untuk mengisi data (status belum menunggu/disetujui) --}}

{{-- Link Grup Daftar Ulang - Muncul saat sudah setuju daftar ulang --}}
@php
    $linkGrupDaftarUlangEnabled = \App\Models\Setting::get('link_grup_daftar_ulang_enabled', false);
    $linkGrupDaftarUlangUrl = \App\Models\Setting::get('link_grup_daftar_ulang_url', '');
@endphp

@if($linkGrupDaftarUlangEnabled && !empty($linkGrupDaftarUlangUrl))
    <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-lg shadow-lg p-6 mb-6">
        <div class="flex flex-col items-center justify-center text-center">
            <svg>...</svg>
            <h4 class="text-xl font-bold text-white mb-2">Segera Bergabung ke Grup Daftar Ulang!</h4>
            <p class="text-sm text-emerald-50 mb-4 max-w-lg leading-relaxed">
                Bergabunglah ke grup WhatsApp khusus untuk calon siswa yang melakukan daftar ulang.
                Dapatkan informasi terbaru dan koordinasi langsung dengan panitia.
            </p>
            <a href="{{ $linkGrupDaftarUlangUrl }}" target="_blank" ...>
                Bergabung ke Grup WhatsApp
            </a>
            <p class="text-xs text-emerald-100 mt-3 italic">
                * Silakan bergabung sebelum melanjutkan proses daftar ulang
            </p>
        </div>
    </div>
@endif

{{-- DI ATAS bagian Surat Pernyataan --}}
@if($showPernyataan)
    <!-- Surat Pernyataan -->
    ...
@endif
```

---

## 📊 COMPARISON TABLE

| Aspek | Sebelum | Sesudah |
|-------|---------|---------|
| **Posisi Tombol** | Di bawah status approval | **DI ATAS Surat Pernyataan** |
| **Kriteria** | `$isMadrasahApproved == true` | User bisa akses halaman (sudah setuju daftar ulang) |
| **Blok Kode** | Dalam `@if($isMadrasahApproved)` | Dalam `@else` (form section) |
| **Line Number** | 371-401 | **578-613** |
| **Heading** | "Bergabung dengan Grup Daftar Ulang" | "**Segera** Bergabung ke Grup Daftar Ulang!" |
| **Deskripsi** | "Silakan bergabung ke grup WhatsApp..." (1 kalimat) | **2 kalimat** + call-to-action |
| **Extra Text** | - | "* Silakan bergabung **sebelum** melanjutkan..." |
| **Timing Muncul** | Setelah panitia approve | **Segera** setelah setuju daftar ulang |

---

## ✅ TESTING CHECKLIST

### Test 1: Tombol Muncul di Posisi Baru

- [ ] Login admin
- [ ] Enable toggle + isi URL di `/admin/pengaturan-kartu?activeTab=link-grup`
- [ ] Login pendaftar
- [ ] Setuju daftar ulang (status_pendaftaran = 'data_disetujui')
- [ ] Akses `/pendaftar/daftarulang?activeTab=madrasah`
- [ ] **VERIFY**: Tombol "Segera Bergabung ke Grup Daftar Ulang!" muncul
- [ ] **VERIFY**: Tombol berada **DI ATAS** bagian "Surat Pernyataan"
- [ ] **VERIFY**: Text "*Silakan bergabung sebelum melanjutkan..." terlihat
- [ ] Scroll ke bawah
- [ ] **VERIFY**: Bagian "Surat Pernyataan" ada di BAWAH tombol

### Test 2: Tombol TIDAK Muncul Jika Toggle Disabled

- [ ] Admin: Disable toggle link grup daftar ulang
- [ ] Pendaftar: Refresh `/pendaftar/daftarulang?activeTab=madrasah`
- [ ] **VERIFY**: Tombol TIDAK muncul (tidak ada kotak emerald)

### Test 3: Tombol Langsung Muncul (Tidak Tunggu Approval)

- [ ] Admin: Enable toggle + isi URL
- [ ] Login pendaftar baru
- [ ] Setuju daftar ulang
- [ ] Akses `/pendaftar/daftarulang?activeTab=madrasah`
- [ ] **VERIFY**: Tombol SUDAH MUNCUL (meskipun belum submit data apapun)
- [ ] **VERIFY**: Tidak perlu tunggu panitia approve daftar ulang madrasah

### Test 4: Tombol TIDAK Muncul di Tab Lain

- [ ] Akses `/pendaftar/daftarulang?activeTab=seragam`
- [ ] **VERIFY**: Tombol TIDAK muncul
- [ ] Akses `/pendaftar/daftarulang?activeTab=ukuran`
- [ ] **VERIFY**: Tombol TIDAK muncul
- [ ] Akses `/pendaftar/daftarulang?activeTab=madrasah`
- [ ] **VERIFY**: Tombol MUNCUL

### Test 5: Link Berfungsi dengan Benar

- [ ] Klik tombol "Bergabung ke Grup WhatsApp"
- [ ] **VERIFY**: Link buka di tab baru
- [ ] **VERIFY**: URL sesuai dengan yang diset admin

---

## 🚨 POTENTIAL ISSUES & SOLUTIONS

### Issue 1: Tombol Masih Muncul di Posisi Lama

**Penyebab**: File belum ter-upload dengan benar atau cache belum dibersihkan

**Solution**:
```bash
# Re-upload file
scp "resources/views/livewire/pendaftar/daftar-ulang.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/

# Clear cache
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan view:clear && php artisan cache:clear"
```

### Issue 2: Tombol Tidak Muncul Sama Sekali

**Check**:
1. Toggle enabled? → `/admin/pengaturan-kartu?activeTab=link-grup`
2. URL diisi? → Same page
3. User bisa akses halaman? → Cek `status_pendaftaran` di database

### Issue 3: Posisi Tidak Sesuai (Tidak Di Atas Surat Pernyataan)

**Check**:
- File: `resources/views/livewire/pendaftar/daftar-ulang.blade.php`
- Line: 578-613 (tombol) harus SEBELUM line 615+ (surat pernyataan)
- Urutan harus benar

---

## 📤 DEPLOYMENT

### File yang Harus Di-Upload: 1 file

```bash
# Via SCP
scp "resources/views/livewire/pendaftar/daftar-ulang.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/
```

### Post-Upload Commands

```bash
# SSH to server
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

# Clear cache
php artisan view:clear
php artisan cache:clear

# Verify file timestamp
ls -la resources/views/livewire/pendaftar/daftar-ulang.blade.php

# Exit
exit
```

### ⚠️ NO DATABASE MIGRATION NEEDED

Perubahan ini **HANYA** mengubah posisi dan kriteria tampil tombol. Tidak ada perubahan database.

---

## 📚 UPDATED DOCUMENTATION

File dokumentasi yang sudah diupdate:
1. **ADD_LINK_GRUP_DAFTAR_ULANG_21_FEB_2026.md** ✅ (Updated)
2. **UPDATE_POSISI_LINK_GRUP_21_FEB_2026.md** ⭐ (This file - NEW)

File yang masih menggunakan kriteria lama (perlu update manual jika diperlukan):
- FINAL_SUMMARY_COMPLETE_21_FEB_2026.md
- QUICK_REFERENCE_COMPLETE_21_FEB_2026.txt

---

## 📝 SUMMARY

**What Changed**:
- Tombol dipindah dari posisi lama (di dalam `@if($isMadrasahApproved)`) ke posisi baru (di atas Surat Pernyataan)
- Kriteria berubah dari "status approved" menjadi "user bisa akses halaman"
- Timing berubah dari "setelah panitia approve" menjadi "**segera** setelah setuju daftar ulang"
- Text diperbarui dengan call-to-action lebih jelas

**Impact**:
- User Experience: **Better** (tombol muncul lebih awal, posisi lebih prominent)
- Breaking Changes: **None** (backward compatible)
- Database: **No changes**

**Files Modified**: 1
**Documentation Updated**: 2

**Status**: ✅ READY FOR DEPLOYMENT

---

**Date**: 21 Februari 2026
**Change Type**: UI/UX Improvement
**Risk Level**: LOW

---

END OF UPDATE DOCUMENT
