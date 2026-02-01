# Fix Export Excel Tes Psikologi - Kolom Kosong

## 🐛 Masalah

Saat export Excel di halaman `/laporan?activeTab=tes_psikologi`, ditemukan 2 kolom yang kosong:
1. **Nomor Pendaftaran** - Kolom kosong
2. **Opsi Tes Psikologi** - Kolom kosong

## 🔍 Root Cause

Terjadi ketidakcocokan nama field antara yang digunakan di export class dengan field yang sebenarnya ada di database model.

### File yang bermasalah:
`app/Exports/TesPsikologiExport.php` - method `map()` line 100 dan 104

### Field yang salah:

| Yang Digunakan (Salah) | Yang Benar | Model |
|------------------------|------------|-------|
| `no_pendaftaran` | `nomor_pendaftaran` | `Pendaftar` |
| `nama_opsi` | `nama` | `TesPsikologiOpsi` |

## ✅ Solusi

### Code BEFORE (SALAH):
```php
public function map($tesPsikologi): array
{
    $baseData = [
        $tesPsikologi->pendaftar->no_pendaftaran ?? '-',        // ❌ SALAH
        $tesPsikologi->pendaftar->nama_lengkap ?? '-',
        $tesPsikologi->pendaftar->nisn ?? '-',
        $tesPsikologi->pendaftar->jalurPendaftaran->nama ?? '-',
        $tesPsikologi->opsi->nama_opsi ?? '-',                  // ❌ SALAH
        // ...
    ];
}
```

### Code AFTER (BENAR):
```php
public function map($tesPsikologi): array
{
    $baseData = [
        $tesPsikologi->pendaftar->nomor_pendaftaran ?? '-',     // ✅ BENAR
        $tesPsikologi->pendaftar->nama_lengkap ?? '-',
        $tesPsikologi->pendaftar->nisn ?? '-',
        $tesPsikologi->pendaftar->jalurPendaftaran->nama ?? '-',
        $tesPsikologi->opsi->nama ?? '-',                       // ✅ BENAR
        // ...
    ];
}
```

## 📋 Detail Perubahan

### 1. Fix Nomor Pendaftaran
**Line 100 - `app/Exports/TesPsikologiExport.php`**

```diff
- $tesPsikologi->pendaftar->no_pendaftaran ?? '-',
+ $tesPsikologi->pendaftar->nomor_pendaftaran ?? '-',
```

**Alasan:**
- Field di model `Pendaftar` adalah `nomor_pendaftaran` (bukan `no_pendaftaran`)
- Lihat: `app/Models/Pendaftar.php` line 20

### 2. Fix Opsi Tes Psikologi
**Line 104 - `app/Exports/TesPsikologiExport.php`**

```diff
- $tesPsikologi->opsi->nama_opsi ?? '-',
+ $tesPsikologi->opsi->nama ?? '-',
```

**Alasan:**
- Field di model `TesPsikologiOpsi` adalah `nama` (bukan `nama_opsi`)
- Lihat: `app/Models/TesPsikologiOpsi.php` line 12

## 🧪 Testing

### Test Case 1: Export dengan Data
1. Login sebagai admin
2. Akses `/admin/laporan?activeTab=tes_psikologi`
3. Pastikan ada data pendaftar tes psikologi
4. Klik tombol "Export Excel"
5. Buka file Excel yang ter-download
6. Verifikasi:
   - ✅ Kolom "No. Pendaftaran" terisi dengan nomor pendaftaran (contoh: P2026-001)
   - ✅ Kolom "Opsi Tes Psikologi" terisi dengan nama opsi (contoh: Opsi A, Opsi B, dst)
   - ✅ Tidak ada kolom yang kosong atau berisi "-" padahal data ada

### Test Case 2: Export dengan Filter Jalur
1. Akses `/admin/laporan?activeTab=tes_psikologi`
2. Pilih filter jalur tertentu
3. Klik "Export Excel"
4. Verifikasi kolom nomor pendaftaran dan opsi terisi

### Test Case 3: Export dengan Filter Status
1. Akses `/admin/laporan?activeTab=tes_psikologi`
2. Pilih filter status (Menunggu, Disetujui, dll)
3. Klik "Export Excel"
4. Verifikasi kolom nomor pendaftaran dan opsi terisi

## 📝 Catatan Tambahan

### Field yang Sudah Benar
Field-field lain di export sudah menggunakan nama yang benar:
- ✅ `nama_lengkap` - Pendaftar
- ✅ `nisn` - Pendaftar
- ✅ `nama` - JalurPendaftaran
- ✅ `nama_bank` - TesPsikologiBank
- ✅ `waktu_transfer` - TesPsikologiPendaftar
- ✅ `tanggal_upload` - TesPsikologiPendaftar
- ✅ `status_verifikasi` - TesPsikologiPendaftar
- ✅ `catatan_verifikasi` - TesPsikologiPendaftar
- ✅ `tanggal_verifikasi` - TesPsikologiPendaftar
- ✅ `email` - User (via pendaftar->user)
- ✅ `no_hp` - Pendaftar

### Survey Columns
Export juga mencakup kolom survey yang sudah benar:
- Status Survey
- Kolom dinamis untuk setiap pertanyaan survey
- Handling array answers (untuk checkbox)

## 🚀 Deployment

### File yang Diubah
```
MODIFIED:
- app/Exports/TesPsikologiExport.php (lines 100, 104)
```

### No Database Changes
- ✅ Tidak ada perubahan database
- ✅ Tidak ada migration baru
- ✅ Hanya fix logic di export class

### Clear Cache After Deploy
```bash
php artisan config:clear
php artisan cache:clear
```

### Verify After Deploy
1. Test export Excel untuk tes psikologi
2. Verify kolom "No. Pendaftaran" terisi
3. Verify kolom "Opsi Tes Psikologi" terisi
4. Test dengan berbagai filter (jalur, status)

## 📊 Impact

### Before Fix:
```
| No. Pendaftaran | Nama Lengkap    | NISN       | Jalur      | Opsi Tes Psikologi |
|-----------------|-----------------|------------|------------|-------------------|
| -               | Ahmad Budi      | 0012345678 | Reguler    | -                 |
| -               | Siti Aisyah     | 0012345679 | Prestasi   | -                 |
```
❌ Data nomor pendaftaran dan opsi tes psikologi KOSONG

### After Fix:
```
| No. Pendaftaran | Nama Lengkap    | NISN       | Jalur      | Opsi Tes Psikologi |
|-----------------|-----------------|------------|------------|-------------------|
| P2026-001       | Ahmad Budi      | 0012345678 | Reguler    | Opsi A            |
| P2026-002       | Siti Aisyah     | 0012345679 | Prestasi   | Opsi B            |
```
✅ Semua data terisi dengan lengkap

## 🔍 Lesson Learned

### Best Practices:
1. **Selalu cek nama field di model** sebelum menggunakannya di export/query
2. **Test export dengan data real** untuk memastikan semua kolom terisi
3. **Gunakan IDE autocomplete** untuk menghindari typo pada property name
4. **Review field names** saat copy-paste code dari file lain

### Common Mistakes to Avoid:
- ❌ Menggunakan `no_pendaftaran` padahal field `nomor_pendaftaran`
- ❌ Menggunakan `nama_opsi` padahal field `nama`
- ❌ Asumsi nama field tanpa cek model
- ❌ Tidak test export hasil dengan data lengkap

## 📚 Related Files

### Models Referenced:
- `app/Models/Pendaftar.php` - Main pendaftar data
- `app/Models/TesPsikologiPendaftar.php` - Tes psikologi registration
- `app/Models/TesPsikologiOpsi.php` - Tes psikologi options
- `app/Models/TesPsikologiBank.php` - Bank transfer options
- `app/Models/JalurPendaftaran.php` - Registration paths
- `app/Models/User.php` - User authentication

### Export Files:
- `app/Exports/TesPsikologiExport.php` - Main export class (FIXED)
- `app/Http/Controllers/ExportController.php` - Export controller

## ✅ Status

**Status:** ✅ **FIXED & READY FOR TESTING**

**Fixed On:** 29 Januari 2026
**Fixed By:** Claude Code
**Breaking Changes:** None
**Backward Compatible:** Yes

---

**Dokumentasi:** 29 Januari 2026
**Version:** 1.0.0
