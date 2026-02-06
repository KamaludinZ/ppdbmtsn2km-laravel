# FIX FILE TIMESTAMP DISPLAY DI TAB DOKUMEN

**Tanggal**: 5 Februari 2026
**Status**: ✅ READY TO UPLOAD

## 🔍 MASALAH

Di production, pada halaman `/admin/pendaftar/[ID]?activeTab=dokumen` dan halaman panitia yang sama, nama file yang ditampilkan **tidak menampilkan timestamp** ketika file di-replace/upload ulang.

Contoh yang diharapkan:
```
rapor-PPDB2024001-Ahmad_Fulan-20260205_143052.pdf
```

Tapi yang muncul di production:
```
rapor.pdf
```

## 🎯 PENYEBAB

Di lokal development sudah menggunakan **FileNamingService** untuk generate nama file dengan timestamp, tapi file-file ini belum di-upload ke production.

### Detail Teknis:

**FileNamingService** (app/Services/FileNamingService.php):
- Generate nama file dengan format: `{jenis}-{nomor_pendaftaran}-{nama}-{timestamp}.{ext}`
- Timestamp format: `Ymd_His` (contoh: `20260205_143052`)
- Contoh hasil: `rapor-PPDB2024001-Ahmad_Fulan-20260205_143052.pdf`

**Upload Logic** (app/Livewire/Admin/DetailPendaftar.php baris 1081-1086):
```php
$filename = FileNamingService::generateFileName(
    $jenis,
    $this->pendaftar->nomor_pendaftaran,
    $file->getClientOriginalExtension(),
    $this->pendaftar->nama_lengkap
);
```

**Display Logic** (resources/views/livewire/admin/detail-pendaftar.blade.php baris 924):
```blade
{{ $uploadedDokumen ? $uploadedDokumen->nama_file : basename($fileRapor) }}
```

## ✅ SOLUSI

Upload file-file berikut ke production:

### File yang Harus Di-upload:

1. **app/Livewire/Admin/DetailPendaftar.php**
   - Mengintegrasikan FileNamingService untuk upload dokumen
   - Baris 1081-1086: Generate filename dengan timestamp

2. **app/Livewire/Panitia/DetailPendaftar.php**
   - Same as Admin (baris 1132-1137)

3. **app/Services/FileNamingService.php**
   - Service utama untuk generate nama file dengan timestamp

4. **resources/views/livewire/admin/detail-pendaftar.blade.php**
   - Display logic sudah benar (baris 924)

5. **resources/views/livewire/panitia/detail-pendaftar.blade.php**
   - Display logic sudah benar (baris 924)

## 📋 CARA UPLOAD

### Opsi 1: Menggunakan Batch Script (RECOMMENDED)

```bash
upload_fix_file_timestamp.bat
```

Script akan:
1. Upload 5 file yang diperlukan
2. Clear cache di production
3. Menampilkan instruksi testing

### Opsi 2: Manual Upload via SCP

```bash
# Upload Livewire Components
scp app/Livewire/Admin/DetailPendaftar.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/
scp app/Livewire/Panitia/DetailPendaftar.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Panitia/

# Upload Service
scp app/Services/FileNamingService.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Services/

# Upload Blade Templates
scp resources/views/livewire/admin/detail-pendaftar.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/
scp resources/views/livewire/panitia/detail-pendaftar.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/panitia/

# Clear cache
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan optimize:clear"
```

## 🧪 CARA TEST

1. **Login** sebagai Admin atau Panitia
2. **Buka detail pendaftar**: `/admin/pendaftar/29?activeTab=dokumen`
3. **Upload/Replace** salah satu dokumen (misalnya rapor)
4. **Lihat hasil**: Nama file akan menampilkan timestamp

### Expected Result:

**Before** (tanpa timestamp):
```
rapor.pdf
Ukuran: 245.67 KB
Upload: 05/02/2026 14:30
```

**After** (dengan timestamp):
```
rapor-PPDB2024029-Ahmad_Fulan-20260205_143052.pdf
Ukuran: 245.67 KB
Upload: 05/02/2026 14:30
```

## ⚠️ CATATAN PENTING

### 1. File Lama vs File Baru

- **File yang sudah ada sebelumnya**: Tetap menggunakan nama lama (tanpa timestamp)
- **File baru yang di-upload setelah update**: Akan mendapat timestamp
- **Untuk update file lama**: Silakan re-upload file agar mendapat timestamp

### 2. Backward Compatibility

Kode sudah handle backward compatibility:
```blade
@if($uploadedDokumen || $fileRapor)
    {{ $uploadedDokumen ? $uploadedDokumen->nama_file : basename($fileRapor) }}
@endif
```

- Jika ada dokumen baru (dengan timestamp): tampilkan `nama_file` dari database
- Jika dokumen lama (legacy): tampilkan `basename($fileRapor)`

### 3. Database Schema

Field `nama_file` di tabel `dokumen`:
- Tipe: VARCHAR(255)
- Nullable: NO
- Isi: Nama file lengkap dengan timestamp

### 4. Storage

File disimpan di:
- **Disk**: private
- **Path**: `dokumen/{pendaftar_id}/{filename}`
- **Contoh**: `dokumen/29/rapor-PPDB2024029-Ahmad_Fulan-20260205_143052.pdf`

## 📊 BENEFIT

1. **Traceability**: Mudah melacak kapan file di-upload
2. **Unique Filename**: Mencegah file overwrite yang tidak disengaja
3. **Searchable**: Mudah dicari berdasarkan nama pendaftar atau nomor pendaftaran
4. **Professional**: Tampilan lebih profesional dan informatif

## 🔄 ROLLBACK (Jika Diperlukan)

Jika terjadi masalah, restore file dari backup:

```bash
# Restore dari backup
scp backup/app/Livewire/Admin/DetailPendaftar.php.backup-before-timestamp mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/DetailPendaftar.php

# Clear cache
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan optimize:clear"
```

## 📝 SUMMARY

| Item | Sebelum | Sesudah |
|------|---------|---------|
| Nama File | rapor.pdf | rapor-PPDB2024029-Ahmad_Fulan-20260205_143052.pdf |
| Timestamp | ❌ Tidak ada | ✅ Ada (format: Ymd_His) |
| Searchable | ⚠️ Susah | ✅ Mudah |
| Unique | ⚠️ Bisa konflik | ✅ Unik per upload |

---

**Dibuat oleh**: Claude Code AI Assistant
**Referensi**: Session 5 Februari 2026
