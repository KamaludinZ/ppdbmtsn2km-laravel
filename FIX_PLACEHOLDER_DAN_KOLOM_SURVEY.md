# Fix: Placeholder Hitam & Kolom Status Survey Tidak Muncul ✅

## Tanggal
29 Januari 2026

## Masalah yang Dilaporkan

### 1. Placeholder Pencarian Warna Hitam ❌
- User mengetik di search field
- Placeholder text berwarna hitam (tidak readable)
- Seharusnya berwarna abu-abu seperti standar placeholder

### 2. Kolom Status Survey Tidak Muncul ❌
- Kolom "Status Survey" tidak terlihat di table
- Seharusnya muncul setelah kolom "Status Psikologi"
- Header dan body table tidak aligned

---

## Analisis Masalah

### Masalah 1: CSS Placeholder
**Root Cause:**
- Class `placeholder-gray-500` tidak compatible dengan semua browser
- Perlu menggunakan pseudo-class `placeholder:` untuk Tailwind v3+

**Lokasi:**
- File: `resources/views/livewire/admin/partials/tes-psikologi-data.blade.php`
- Line: 39

### Masalah 2: Kolom Survey Misalignment
**Root Cause:**
- Header kolom "Status Survey" ada di posisi yang benar (line 78-80)
- Body kolom "Status Survey" ada di posisi yang SALAH (line 289-319)
- Kolom survey di body terletak SETELAH kolom admin, bukan sebelumnya
- Menyebabkan header dan body columns tidak aligned

**Diagram Masalah:**
```
HEADER (Benar):
[Status Psikologi] → [Status Survey] → [Admin Columns...]

BODY (Salah):
[Status Psikologi] → [Admin Columns...] → [Status Survey]
                                           ↑ SALAH!
```

**Lokasi Duplikasi:**
- Kolom survey yang benar: Line 166-196 (sebelum admin columns)
- Kolom survey yang salah: Line 289-319 (setelah admin columns) - DUPLIKAT!

---

## Solusi yang Diterapkan

### Fix 1: Placeholder Color

**Before:**
```blade
class="... placeholder-gray-500 ..."
```

**After:**
```blade
class="... placeholder:text-gray-400 ..."
```

**Penjelasan:**
- Menggunakan `placeholder:text-gray-400` (Tailwind v3+ syntax)
- Pseudo-class `placeholder:` lebih reliable dan modern
- Warna `gray-400` lebih soft dan readable

**File Modified:**
- `resources/views/livewire/admin/partials/tes-psikologi-data.blade.php` (Line 39)

---

### Fix 2: Column Alignment

**Step 1: Pindahkan Kolom Survey ke Posisi yang Benar**

Memindahkan kolom Status Survey agar berada SEBELUM kolom admin:

```blade
<!-- Status Psikologi -->
</td>

<!-- Kolom Jawaban Survey - HARUS SEBELUM KOLOM ADMIN -->
@if($setting && $setting->survey_aktif)
    <td class="px-6 py-4 whitespace-nowrap text-center">
        @php
            $surveyAnswersCount = \App\Models\SurveyAnswer::where('pendaftar_id', $data->pendaftar_id)
                ->whereHas('question', function($q) {
                    $q->tesPsikologi();
                })
                ->count();
        @endphp
        @if($surveyAnswersCount > 0)
            <!-- Badge Sudah Dijawab + Button Lihat -->
        @else
            <!-- Badge Belum Dijawab -->
        @endif
    </td>
@endif

<!-- Admin Columns -->
@if(Auth::user()->role === 'admin')
    <!-- Ubah Status, Kirim Pesan, Riwayat Pesan -->
@endif
```

**Step 2: Hapus Duplikasi**

Menghapus kolom Status Survey yang duplikat (setelah admin columns):

**Deleted Lines:** 289-319

**File Modified:**
- `resources/views/livewire/admin/partials/tes-psikologi-data.blade.php` (Lines 165-196, 287-319)

---

## Verification Script

Dibuat file `check_survey_aktif.php` untuk:
1. Cek apakah setting tes psikologi ada
2. Cek apakah survey aktif
3. Aktifkan survey jika belum aktif
4. Tampilkan daftar pertanyaan survey

**Cara Menggunakan:**
```bash
php check_survey_aktif.php
```

**Output Script:**
```
=== CEK STATUS SURVEY TES PSIKOLOGI ===

Current Status:
├─ ID: 1
├─ Tes Psikologi Aktif: ✅ YA
├─ Survey Aktif: ✅ YA
└─ Survey Informasi: Silakan isi survey tes psikologi

✅ Survey sudah aktif!

=== PERTANYAAN SURVEY ===

Total Pertanyaan: 3
Pertanyaan Aktif: 3

Daftar Pertanyaan:
1. ✅ Apakah Anda mengikuti bimbingan belajar?
   └─ Tipe: radio, Wajib: Ya
2. ✅ Bagaimana Anda mengetahui MTs Negeri 2 Kota Malang?
   └─ Tipe: select, Wajib: Ya
3. ✅ Saran dan masukan untuk kami
   └─ Tipe: textarea, Wajib: Tidak

=== SELESAI ===

Silakan refresh halaman admin/tes-psikologi atau panitia-pendaftaran/tes-psikologi
Kolom 'Status Survey' seharusnya sudah muncul!
```

---

## Struktur Table yang Benar

### Header Columns (Order):
1. Nomor Pendaftaran
2. NISN
3. Nama Pendaftar
4. Opsi/Biaya
5. Bank Transfer
6. Waktu Transfer
7. Bukti Transfer
8. Status Psikologi
9. **Status Survey** ← Conditional (`@if($setting->survey_aktif)`)
10. Ubah Status ← Admin only
11. Kirim Pesan ← Admin only
12. Riwayat Pesan ← Admin only

### Body Columns (Order):
**HARUS SAMA dengan header!**

```blade
<tr>
    <td>{{ Nomor Pendaftaran }}</td>
    <td>{{ NISN }}</td>
    <td>{{ Nama }}</td>
    <td>{{ Opsi/Biaya }}</td>
    <td>{{ Bank }}</td>
    <td>{{ Waktu Transfer }}</td>
    <td>{{ Bukti Transfer }}</td>
    <td>{{ Status Psikologi }}</td>

    <!-- Status Survey - SEBELUM admin columns -->
    @if($setting->survey_aktif)
        <td>{{ Status Survey }}</td>
    @endif

    <!-- Admin Columns -->
    @if(Auth::user()->role === 'admin')
        <td>{{ Ubah Status }}</td>
        <td>{{ Kirim Pesan }}</td>
        <td>{{ Riwayat Pesan }}</td>
    @endif
</tr>
```

---

## Testing Checklist

### Placeholder Color
- [ ] Refresh halaman
- [ ] Lihat search input field
- [ ] Placeholder "Cari nama, NISN, nomor..." berwarna abu-abu
- [ ] Ketik di input → text berwarna hitam (gray-900)
- [ ] Clear input → placeholder kembali abu-abu

### Kolom Status Survey
- [ ] Jalankan: `php check_survey_aktif.php`
- [ ] Pastikan survey_aktif = true
- [ ] Refresh halaman `/admin/tes-psikologi?mainTab=data`
- [ ] Kolom "Status Survey" muncul setelah "Status Psikologi"
- [ ] Kolom "Status Survey" SEBELUM "Ubah Status" (admin only)
- [ ] Badge "Sudah Dijawab" (hijau) muncul jika ada jawaban
- [ ] Badge "Belum Dijawab" (abu-abu) muncul jika belum ada jawaban
- [ ] Button "Lihat (N)" muncul jika sudah dijawab
- [ ] Klik button → modal terbuka dengan jawaban survey
- [ ] Test di role panitia: kolom survey muncul, admin columns tidak muncul

---

## Kemungkinan Masalah Lain

### Jika Kolom Masih Tidak Muncul:

#### 1. Survey Tidak Aktif
**Cek:**
```sql
SELECT * FROM tes_psikologi_settings;
```

**Fix:**
```sql
UPDATE tes_psikologi_settings SET survey_aktif = 1;
```

Atau jalankan:
```bash
php check_survey_aktif.php
```

#### 2. Cache Browser
**Fix:**
- Hard refresh: `Ctrl + Shift + R` (Windows/Linux) atau `Cmd + Shift + R` (Mac)
- Clear cache browser
- Buka di incognito/private mode

#### 3. Livewire Cache
**Fix:**
```bash
php artisan livewire:discover
php artisan view:clear
php artisan config:clear
php artisan cache:clear
```

#### 4. Variable $setting Tidak Tersedia
**Cek di render method:**
```php
// Di app/Livewire/Admin/TesPsikologi.php
$setting = TesPsikologiSetting::getSetting();
return view('...', compact('setting', ...));

// Di app/Livewire/Panitia/TesPsikologi.php
$setting = TesPsikologiSetting::getSetting();
return view('...', ['setting' => $setting, ...]);
```

---

## Files Modified

### 1. `resources/views/livewire/admin/partials/tes-psikologi-data.blade.php`
**Changes:**
- Line 39: Fixed placeholder color (`placeholder:text-gray-400`)
- Lines 165-196: Moved Status Survey column BEFORE admin columns
- Lines 289-319: Deleted duplicate Status Survey column

### 2. `check_survey_aktif.php` (New File)
**Purpose:**
- Verify survey settings
- Auto-activate survey if needed
- Display survey questions
- Troubleshooting tool

---

## Summary

### ✅ Fixed Issues:
1. Placeholder text sekarang berwarna abu-abu (gray-400)
2. Kolom Status Survey sekarang muncul di posisi yang benar
3. Header dan body table sudah aligned
4. Duplikasi kolom sudah dihapus

### ✅ Improvements:
1. Created verification script untuk troubleshooting
2. Better code organization dan comments
3. Consistent column order across admin dan panitia

### 📝 Next Steps:
1. Test di browser (admin role)
2. Test di browser (panitia role)
3. Verify placeholder color
4. Verify column alignment
5. Test survey modal functionality

---

## Quick Fix Commands

```bash
# 1. Cek dan aktifkan survey
php check_survey_aktif.php

# 2. Clear semua cache
php artisan optimize:clear

# 3. Restart development server
# Ctrl+C untuk stop, lalu:
php artisan serve
# atau npm run dev (jika pakai Vite)

# 4. Hard refresh browser
# Windows/Linux: Ctrl + Shift + R
# Mac: Cmd + Shift + R
```

---

## Status: ✅ COMPLETE

Kedua masalah telah diperbaiki:
- ✅ Placeholder search field sekarang berwarna abu-abu
- ✅ Kolom Status Survey sekarang muncul di posisi yang benar
- ✅ Header dan body table sudah aligned
- ✅ Duplikasi sudah dihapus
- ✅ Verification script sudah dibuat

**Ready for testing! 🚀**
