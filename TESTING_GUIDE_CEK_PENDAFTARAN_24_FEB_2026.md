# TESTING GUIDE: Cek Pendaftaran Comprehensive Status

**Tanggal:** 24 Februari 2026

## 🧪 Quick Testing Guide

### URL untuk Test

**Local:**
```
http://localhost:8000/cek-pendaftaran
```

**Production:**
```
https://pmbm.mtsn2kotamalang.sch.id/cek-pendaftaran
```

---

## 📋 Test Scenarios

### Scenario 1: Draft - Belum Submit

**Search:** Cari pendaftar dengan `status_pendaftaran = 'draft'`

**Expected Display:**
```
┌─────────────────────────────────────────────────────────────┐
│ 3 Status Cards (Always Visible)                            │
├─────────────────────────────────────────────────────────────┤
│ ┌──────────────────┐ ┌──────────────────┐ ┌─────────────┐ │
│ │ Verif Pendaftaran│ │ Verif Psikologi  │ │ Kelulusan   │ │
│ │ 🟦 Draft         │ │ 🟦 Belum Upload  │ │ 🟦 Belum    │ │
│ │                  │ │                  │ │ Diumumkan   │ │
│ └──────────────────┘ └──────────────────┘ └─────────────┘ │
└─────────────────────────────────────────────────────────────┘
❌ Section "Status Daftar Ulang" TIDAK MUNCUL
```

### Scenario 2: Menunggu Verifikasi

**Search:** Cari pendaftar dengan:
- `status_pendaftaran = 'menunggu'`
- `tes_psikologi_pendaftar.status_verifikasi = 'menunggu'`

**Expected Display:**
```
┌─────────────────────────────────────────────────────────────┐
│ 3 Status Cards                                              │
├─────────────────────────────────────────────────────────────┤
│ ┌──────────────────┐ ┌──────────────────┐ ┌─────────────┐ │
│ │ Verif Pendaftaran│ │ Verif Psikologi  │ │ Kelulusan   │ │
│ │ 🟨 Menunggu      │ │ 🟨 Menunggu      │ │ 🟦 Belum    │ │
│ │    Verifikasi    │ │    Verifikasi    │ │ Diumumkan   │ │
│ └──────────────────┘ └──────────────────┘ └─────────────┘ │
└─────────────────────────────────────────────────────────────┘
❌ Section "Status Daftar Ulang" TIDAK MUNCUL
```

### Scenario 3: LULUS - Belum Daftar Ulang

**Search:** Cari pendaftar dengan:
- `status_pendaftaran = 'lulus'`
- `tes_psikologi_pendaftar.status_verifikasi = 'disetujui'`
- Tidak ada record `daftar_ulang`
- Tidak ada record `seragam`

**Expected Display:**
```
┌─────────────────────────────────────────────────────────────┐
│ 3 Status Cards                                              │
├─────────────────────────────────────────────────────────────┤
│ ┌──────────────────┐ ┌──────────────────┐ ┌─────────────┐ │
│ │ Verif Pendaftaran│ │ Verif Psikologi  │ │ Kelulusan   │ │
│ │ 🟩 Data Disetujui│ │ 🟩 Disetujui     │ │ 🟩 LULUS    │ │
│ └──────────────────┘ └──────────────────┘ └─────────────┘ │
└─────────────────────────────────────────────────────────────┘

✅ Section "Status Daftar Ulang" MUNCUL

┌─────────────────────────────────────────────────────────────┐
│ Status Daftar Ulang (Heading)                               │
├─────────────────────────────────────────────────────────────┤
│ ┌──────────────────┐ ┌──────────────────┐ ┌─────────────┐ │
│ │ DU Madrasah      │ │ Ukuran Seragam   │ │ DU Seragam  │ │
│ │ 🟦 Belum Daftar  │ │ 🟦 Belum         │ │ 🟦 Belum    │ │
│ │    Ulang         │ │                  │ │             │ │
│ └──────────────────┘ └──────────────────┘ └─────────────┘ │
└─────────────────────────────────────────────────────────────┘
```

### Scenario 4: LULUS - Daftar Ulang Lengkap

**Search:** Cari pendaftar dengan:
- `status_pendaftaran = 'lulus'`
- `tes_psikologi_pendaftar.status_verifikasi = 'disetujui'`
- `daftar_ulang.status = 'disetujui'`
- `seragam.status_ukuran = 'disetujui'`
- `seragam.status_seragam = 'disetujui'`

**Expected Display:**
```
┌─────────────────────────────────────────────────────────────┐
│ 3 Status Cards                                              │
├─────────────────────────────────────────────────────────────┤
│ ┌──────────────────┐ ┌──────────────────┐ ┌─────────────┐ │
│ │ Verif Pendaftaran│ │ Verif Psikologi  │ │ Kelulusan   │ │
│ │ 🟩 Data Disetujui│ │ 🟩 Disetujui     │ │ 🟩 LULUS    │ │
│ └──────────────────┘ └──────────────────┘ └─────────────┘ │
└─────────────────────────────────────────────────────────────┘

✅ Section "Status Daftar Ulang" MUNCUL

┌─────────────────────────────────────────────────────────────┐
│ Status Daftar Ulang (Heading)                               │
├─────────────────────────────────────────────────────────────┤
│ ┌──────────────────┐ ┌──────────────────┐ ┌─────────────┐ │
│ │ DU Madrasah      │ │ Ukuran Seragam   │ │ DU Seragam  │ │
│ │ 🟩 Disetujui     │ │ 🟩 Disetujui     │ │ 🟩 Disetujui│ │
│ └──────────────────┘ └──────────────────┘ └─────────────┘ │
└─────────────────────────────────────────────────────────────┘
```

### Scenario 5: Tidak Lulus

**Search:** Cari pendaftar dengan:
- `status_pendaftaran = 'tidak_lulus'`
- `tes_psikologi_pendaftar.status_verifikasi = 'disetujui'`

**Expected Display:**
```
┌─────────────────────────────────────────────────────────────┐
│ 3 Status Cards                                              │
├─────────────────────────────────────────────────────────────┤
│ ┌──────────────────┐ ┌──────────────────┐ ┌─────────────┐ │
│ │ Verif Pendaftaran│ │ Verif Psikologi  │ │ Kelulusan   │ │
│ │ 🟥 Data Ditolak  │ │ 🟩 Disetujui     │ │ 🟥 Tidak    │ │
│ │                  │ │                  │ │    Lulus    │ │
│ └──────────────────┘ └──────────────────┘ └─────────────┘ │
└─────────────────────────────────────────────────────────────┘
❌ Section "Status Daftar Ulang" TIDAK MUNCUL
```

### Scenario 6: Cadangan

**Search:** Cari pendaftar dengan:
- `status_pendaftaran = 'cadangan'`
- `tes_psikologi_pendaftar.status_verifikasi = 'disetujui'`

**Expected Display:**
```
┌─────────────────────────────────────────────────────────────┐
│ 3 Status Cards                                              │
├─────────────────────────────────────────────────────────────┤
│ ┌──────────────────┐ ┌──────────────────┐ ┌─────────────┐ │
│ │ Verif Pendaftaran│ │ Verif Psikologi  │ │ Kelulusan   │ │
│ │ 🟩 Data Disetujui│ │ 🟩 Disetujui     │ │ 🟦 Cadangan │ │
│ └──────────────────┘ └──────────────────┘ └─────────────┘ │
└─────────────────────────────────────────────────────────────┘
❌ Section "Status Daftar Ulang" TIDAK MUNCUL
```

### Scenario 7: Status Ditolak/Perbaiki

**Search:** Cari pendaftar dengan:
- `status_pendaftaran = 'lulus'`
- `daftar_ulang.status = 'ditolak'`
- `seragam.status_ukuran = 'perbaiki'`
- `seragam.status_seragam = 'menunggu'`

**Expected Display:**
```
┌─────────────────────────────────────────────────────────────┐
│ 3 Status Cards                                              │
├─────────────────────────────────────────────────────────────┤
│ │ 🟩 Data Disetujui│ │ 🟩 Disetujui     │ │ 🟩 LULUS    │ │
└─────────────────────────────────────────────────────────────┘

✅ Section "Status Daftar Ulang" MUNCUL

┌─────────────────────────────────────────────────────────────┐
│ Status Daftar Ulang (Heading)                               │
├─────────────────────────────────────────────────────────────┤
│ ┌──────────────────┐ ┌──────────────────┐ ┌─────────────┐ │
│ │ DU Madrasah      │ │ Ukuran Seragam   │ │ DU Seragam  │ │
│ │ 🟥 Ditolak       │ │ 🟧 Perlu Perbaik │ │ 🟨 Menunggu │ │
│ └──────────────────┘ └──────────────────┘ └─────────────┘ │
└─────────────────────────────────────────────────────────────┘
```

---

## 🎨 Color Legend

| Color | Status Type | Example |
|-------|------------|---------|
| 🟦 Gray | Belum/Draft | Belum Upload, Belum Diumumkan |
| 🟨 Yellow | Menunggu | Menunggu Verifikasi |
| 🟩 Green | Sukses | Disetujui, Lulus, Diterima |
| 🟥 Red | Gagal | Ditolak, Tidak Lulus |
| 🟧 Orange | Perbaikan | Perlu Perbaikan |
| 🟦 Blue | Info | Sedang Diverifikasi, Cadangan |

---

## ✅ Testing Checklist

### Visual Testing
- [ ] 3 status cards utama tampil dengan benar
- [ ] Warna background sesuai dengan status (gray, yellow, green, red, orange, blue)
- [ ] Icon muncul di setiap status card
- [ ] Text label jelas dan terbaca
- [ ] Border dan spacing proper

### Responsive Testing
- [ ] Desktop (>768px): 3 kolom grid
- [ ] Mobile (<768px): 1 kolom (stacked)
- [ ] Tablet: grid menyesuaikan dengan baik

### Conditional Logic Testing
- [ ] Section "Status Daftar Ulang" HANYA muncul jika status = lulus/diterima
- [ ] Section "Status Daftar Ulang" TIDAK muncul jika status = draft, menunggu, cadangan, tidak_lulus
- [ ] Status "Belum Upload" muncul jika tidak ada record tes_psikologi_pendaftar
- [ ] Status "Belum Daftar Ulang" muncul jika tidak ada record daftar_ulang
- [ ] Status "Belum" muncul jika tidak ada record seragam

### Data Accuracy Testing
- [ ] Status Verifikasi Pendaftaran sesuai dengan `pendaftar.status_pendaftaran`
- [ ] Status Verif Psikologi sesuai dengan `tes_psikologi_pendaftar.status_verifikasi`
- [ ] Status Kelulusan logic benar (lulus/diterima = LULUS, cadangan = Cadangan, dll)
- [ ] Status DU Madrasah sesuai dengan `daftar_ulang.status`
- [ ] Status Ukuran Seragam sesuai dengan `seragam.status_ukuran`
- [ ] Status DU Seragam sesuai dengan `seragam.status_seragam`

### Error Handling
- [ ] Error "Data tidak ditemukan" muncul jika search tidak match
- [ ] Validation error muncul jika search query < 3 karakter
- [ ] No error jika relationship kosong (null coalescing operator bekerja)

### Performance
- [ ] No N+1 query (menggunakan eager loading)
- [ ] Page load cepat (<2 detik)
- [ ] Tidak ada console error di browser

---

## 🐛 Common Issues & Solutions

### Issue 1: Cache Lama
**Problem:** Tampilan masih menampilkan design lama
**Solution:**
```bash
# Local
php artisan view:clear
php artisan cache:clear

# Production
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan view:clear && php artisan cache:clear"
```

Browser:
- Hard refresh: `Ctrl + Shift + R`
- Clear browser cache
- Test di incognito mode

### Issue 2: Error "Undefined property: stdClass::$tesPsikologiPendaftar"
**Problem:** Eager loading tidak bekerja
**Solution:** Pastikan di `CekPendaftaran.php` ada:
```php
$pendaftar = Pendaftar::with([
    'jalurPendaftaran',
    'tesPsikologiPendaftar',
    'daftarUlang',
    'seragam'
])->where(...)->first();
```

### Issue 3: Section Daftar Ulang Tidak Muncul untuk Yang Lulus
**Problem:** Conditional logic salah
**Solution:** Cek di blade file ada:
```php
$isLulus = in_array($result->status_pendaftaran, ['lulus', 'diterima']);
```
Dan di view ada:
```blade
@if($isLulus)
    <!-- daftar ulang section -->
@endif
```

---

## 📊 Test Data Generator (SQL)

Jika perlu membuat test data untuk testing:

```sql
-- Update pendaftar ke status lulus
UPDATE pendaftar SET status_pendaftaran = 'lulus' WHERE id = 1;

-- Insert tes psikologi dengan status disetujui
INSERT INTO tes_psikologi_pendaftar (pendaftar_id, status_verifikasi)
VALUES (1, 'disetujui')
ON CONFLICT (pendaftar_id) DO UPDATE SET status_verifikasi = 'disetujui';

-- Insert daftar ulang dengan status menunggu
INSERT INTO daftar_ulang (pendaftar_id, status)
VALUES (1, 'menunggu')
ON CONFLICT (pendaftar_id) DO UPDATE SET status = 'menunggu';

-- Insert seragam dengan status ukuran dan seragam
INSERT INTO seragam (pendaftar_id, status_ukuran, status_seragam)
VALUES (1, 'menunggu', 'menunggu')
ON CONFLICT (pendaftar_id) DO UPDATE SET status_ukuran = 'menunggu', status_seragam = 'menunggu';
```

---

**Status:** Ready for Testing ✅
**Date:** 24 Februari 2026
