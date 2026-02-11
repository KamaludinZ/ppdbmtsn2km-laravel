# FIX: Logika Tombol "Ubah Status" Berdasarkan Status Dokumen

**Tanggal:** 10 Februari 2026
**Status:** ✅ FIXED
**Priority:** HIGH - UX Logic Issue

---

## 🐛 MASALAH SEBELUMNYA

Di halaman `/admin/pendaftar/[id]` dan `/panitia/pendaftar/[id]`, tombol **"Ubah Status"** di header info hanya aktif jika **semua dokumen berstatus "Disetujui"**.

### Logika Lama (SALAH):
```
Tombol AKTIF jika:
- Semua dokumen wajib = "disetujui"
- Semua dokumen optional yang sudah diupload = "disetujui"

Tombol DISABLED jika:
- Ada dokumen dengan status "menunggu_verifikasi" ❌
- Ada dokumen dengan status "perbaiki_data" ❌ (SALAH!)
- Ada dokumen dengan status "data_ditolak" ❌ (SALAH!)
```

❌ **Dampak:**
- Admin/Panitia tidak bisa ubah status pendaftar meskipun sudah verifikasi dokumen (approve/tolak/perbaiki)
- Harus menunggu semua dokumen "disetujui" dulu → workflow terhambat
- Tidak bisa set status "perbaiki_data" atau "data_ditolak" pada pendaftar jika ada dokumen yang ditolak

---

## ✅ SOLUSI

### Logika Baru (BENAR):
```
Tombol AKTIF jika:
- TIDAK ada dokumen dengan status "menunggu_verifikasi"
- Dokumen dengan status "disetujui", "perbaiki_data", atau "data_ditolak" = SUDAH DIVERIFIKASI

Tombol DISABLED jika:
- Ada dokumen dengan status "menunggu_verifikasi" (belum diverifikasi)
```

✅ **Manfaat:**
- Admin/Panitia bisa ubah status pendaftar setelah verifikasi dokumen
- Workflow lebih fleksibel
- Tombol aktif meskipun ada dokumen yang ditolak/diminta perbaikan

---

## 📝 PERUBAHAN KODE

### File yang Diubah:

1. **`app/Livewire/Admin/DetailPendaftar.php`** (baris 1169-1215)
2. **`app/Livewire/Panitia/DetailPendaftar.php`** (baris 1042-1088)

### Method yang Diubah:
`getIsAllDocumentsVerifiedProperty()`

### Before:
```php
// Check all wajib documents must be disetujui
foreach ($wajibDokumen as $dok) {
    $statusField = 'status_' . $dok;
    if ($this->$statusField !== 'disetujui') {
        return false; // ❌ Tombol disabled jika bukan "disetujui"
    }
}
```

### After:
```php
// Check if any wajib documents are still "menunggu_verifikasi"
foreach ($wajibDokumen as $dok) {
    $statusField = 'status_' . $dok;
    if ($this->$statusField === 'menunggu_verifikasi') {
        return false; // ✅ Tombol disabled HANYA jika "menunggu_verifikasi"
    }
}
```

---

## 📊 PERBANDINGAN LOGIKA

| Status Dokumen | Logika Lama | Logika Baru |
|---|---|---|
| `menunggu_verifikasi` | ❌ Tombol Disabled | ❌ Tombol Disabled |
| `disetujui` | ✅ Tombol Aktif | ✅ Tombol Aktif |
| `perbaiki_data` | ❌ Tombol Disabled | ✅ Tombol Aktif ⭐ |
| `data_ditolak` | ❌ Tombol Disabled | ✅ Tombol Aktif ⭐ |

**⭐ Perubahan utama:** Tombol aktif meskipun ada dokumen yang ditolak/diminta perbaikan

---

## 🚀 CARA DEPLOY

### 1. Upload ke Production
```bash
# Jalankan bat file
upload_fix_ubah_status_logic.bat

# Atau manual via SCP
scp app/Livewire/Admin/DetailPendaftar.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/
scp app/Livewire/Panitia/DetailPendaftar.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Panitia/
```

### 2. Clear Cache di Production
```bash
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
php artisan optimize:clear
```

---

## ✅ TESTING CHECKLIST

### Test Case 1: Semua Dokumen "Menunggu Verifikasi"
- [ ] 1. Buka `/admin/pendaftar/[id]`
- [ ] 2. Tab "Verifikasi Dokumen" - pastikan ada dokumen dengan status "Menunggu Verifikasi"
- [ ] 3. Kembali ke header info
- [ ] 4. **Expected:** Tombol "Ubah Status" **DISABLED** (abu-abu)
- [ ] 5. Hover tombol, **Expected:** Tooltip muncul: *"🔒 Terkunci! Verifikasi semua dokumen wajib..."*

### Test Case 2: Semua Dokumen "Disetujui"
- [ ] 1. Verifikasi semua dokumen dengan status "Disetujui"
- [ ] 2. Kembali ke header info
- [ ] 3. **Expected:** Tombol "Ubah Status" **AKTIF** (putih)
- [ ] 4. **Expected:** Bisa pilih status: Menunggu Verifikasi, Lengkapi Data, Perbaiki Data, Data Ditolak, Data Disetujui

### Test Case 3: Ada Dokumen "Perbaiki Data" (NEW TEST!)
- [ ] 1. Verifikasi beberapa dokumen dengan status "Perbaiki Data"
- [ ] 2. Verifikasi dokumen lainnya dengan status "Disetujui"
- [ ] 3. **TIDAK ADA** dokumen dengan status "Menunggu Verifikasi"
- [ ] 4. Kembali ke header info
- [ ] 5. **Expected:** Tombol "Ubah Status" **AKTIF** ✅ (ini yang diperbaiki!)
- [ ] 6. **Expected:** Bisa ubah status pendaftar menjadi "Perbaiki Data"

### Test Case 4: Ada Dokumen "Data Ditolak" (NEW TEST!)
- [ ] 1. Verifikasi beberapa dokumen dengan status "Data Ditolak"
- [ ] 2. Verifikasi dokumen lainnya dengan status "Disetujui"
- [ ] 3. **TIDAK ADA** dokumen dengan status "Menunggu Verifikasi"
- [ ] 4. Kembali ke header info
- [ ] 5. **Expected:** Tombol "Ubah Status" **AKTIF** ✅ (ini yang diperbaiki!)
- [ ] 6. **Expected:** Bisa ubah status pendaftar menjadi "Data Ditolak"

### Test Case 5: Campuran (Disetujui + Perbaiki Data + Ditolak)
- [ ] 1. Rapor = "Disetujui"
- [ ] 2. Akta = "Perbaiki Data"
- [ ] 3. KK = "Data Ditolak"
- [ ] 4. NISN = "Disetujui"
- [ ] 5. Ijazah = "Disetujui"
- [ ] 6. **TIDAK ADA** yang "Menunggu Verifikasi"
- [ ] 7. **Expected:** Tombol "Ubah Status" **AKTIF** ✅
- [ ] 8. **Expected:** Bisa ubah status pendaftar sesuai kebutuhan

### Test Case 6: Ada 1 Dokumen "Menunggu Verifikasi"
- [ ] 1. Rapor = "Disetujui"
- [ ] 2. Akta = "Disetujui"
- [ ] 3. KK = "Menunggu Verifikasi" ⚠️
- [ ] 4. NISN = "Disetujui"
- [ ] 5. Ijazah = "Disetujui"
- [ ] 6. **Expected:** Tombol "Ubah Status" **DISABLED** ❌
- [ ] 7. Verifikasi KK (approve/tolak/perbaiki)
- [ ] 8. **Expected:** Tombol "Ubah Status" langsung **AKTIF** ✅

---

## 📌 WORKFLOW BARU

### Sebelumnya (SALAH):
```
1. Pendaftar upload dokumen
2. Admin verifikasi dokumen:
   - Rapor = Disetujui ✅
   - Akta = Perbaiki Data ⚠️
   - KK = Disetujui ✅
3. Tombol "Ubah Status" = DISABLED ❌
4. Admin TIDAK BISA ubah status pendaftar ❌
5. Admin harus tunggu semua dokumen "Disetujui" ❌
```

### Sekarang (BENAR):
```
1. Pendaftar upload dokumen
2. Admin verifikasi dokumen:
   - Rapor = Disetujui ✅
   - Akta = Perbaiki Data ⚠️
   - KK = Disetujui ✅
3. Semua dokumen SUDAH diverifikasi (tidak ada yang "Menunggu")
4. Tombol "Ubah Status" = AKTIF ✅
5. Admin bisa ubah status pendaftar:
   - "Perbaiki Data" (karena ada dokumen yang perlu diperbaiki)
   - "Data Disetujui" (jika mau lanjut)
   - "Data Ditolak" (jika mau tolak)
6. Workflow lebih fleksibel! ✅
```

---

## 🎯 KRITERIA AKTIF/DISABLED

### ✅ Tombol AKTIF jika:
- [x] Semua dokumen wajib (rapor, akte, kk, nisn, ijazah) = BUKAN "menunggu_verifikasi"
- [x] Semua dokumen optional yang sudah diupload = BUKAN "menunggu_verifikasi"
- [x] Status bisa "disetujui", "perbaiki_data", atau "data_ditolak"

### ❌ Tombol DISABLED jika:
- [x] Ada dokumen wajib dengan status "menunggu_verifikasi"
- [x] Ada dokumen optional yang sudah diupload dengan status "menunggu_verifikasi"

---

## 💡 NOTES

1. **Dokumen Wajib:**
   - Rapor
   - Akta Kelahiran
   - Kartu Keluarga
   - NISN
   - Ijazah

2. **Dokumen Optional:**
   - Sertifikat (jika ada)
   - KIP (jika ada nomor_kip)
   - PKH (jika ada nomor_pkh)
   - KKS (jika ada nomor_kks)

3. **Status yang Dianggap "Sudah Diverifikasi":**
   - `disetujui` ✅
   - `perbaiki_data` ⚠️ (perlu perbaikan tapi sudah diverifikasi)
   - `data_ditolak` ❌ (ditolak tapi sudah diverifikasi)

4. **Status yang Membuat Tombol Disabled:**
   - `menunggu_verifikasi` ⏳ (belum diverifikasi)

---

## 📍 LOKASI TOMBOL

**Admin:**
- URL: `/admin/pendaftar/[id]`
- Lokasi: Header info di atas, bagian kanan atas
- Label: "Ubah Status"

**Panitia:**
- URL: `/panitia/pendaftar/[id]`
- Lokasi: Header info di atas, bagian kanan atas
- Label: "Ubah Status"

---

## 🔗 RELATED FILES

### Livewire Components:
- `app/Livewire/Admin/DetailPendaftar.php` (method: `getIsAllDocumentsVerifiedProperty`)
- `app/Livewire/Panitia/DetailPendaftar.php` (method: `getIsAllDocumentsVerifiedProperty`)

### Blade Views:
- `resources/views/livewire/admin/detail-pendaftar.blade.php` (baris 164-199)
- `resources/views/livewire/panitia/detail-pendaftar.blade.php` (baris 164-199)

---

**Author:** Claude Code
**Last Updated:** 10 Februari 2026
