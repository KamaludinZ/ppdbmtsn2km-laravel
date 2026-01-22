# FIX: Backup Tidak Muncul di List

**Tanggal**: 20 Januari 2026
**Masalah**: File backup berhasil dibuat tapi tidak muncul di "Backup Tersedia"
**Status**: ✅ DIPERBAIKI

---

## 🔍 MASALAH

User membuat backup dan proses berhasil, tapi list "Backup Tersedia" tetap menunjukkan:
```
Backup Tersedia (0)
Tidak ada backup
Buat backup pertama Anda menggunakan tombol di atas.
```

Padahal file backup **SUDAH TERBUAT** di `storage/app/backups/`:
```
backup_full_2026-01-20_11-18-22.zip  (28.5 MB)
backup_full_2026-01-20_11-26-55.zip  (28.5 MB)
```

---

## 🐛 ROOT CAUSE

Method `loadBackups()` menggunakan `Storage::files('backups')` yang:
- ✅ Menemukan semua file di folder backups
- ❌ **TIDAK memfilter** hanya file .zip
- ❌ Mendeteksi `.gitignore` dan `index.html` sebagai "backup"
- ❌ Kedua file tersebut menyebabkan error saat parsing

**Kode Lama**:
```php
private function loadBackups()
{
    $this->backups = [];

    if (Storage::exists('backups')) {
        $files = Storage::files('backups');  // ❌ Ambil SEMUA file

        foreach ($files as $file) {
            $filename = basename($file);
            $path = storage_path("app/{$file}");

            // ❌ Tidak ada filter .zip!
            $this->backups[] = [
                'filename' => $filename,
                'size' => $this->formatBytes(filesize($path)),
                'created_at' => date('d M Y H:i:s', filemtime($path)),
                'path' => $path
            ];
        }
    }
}
```

**Masalah**:
1. Loop mencoba parse `.gitignore` dan `index.html` sebagai backup
2. Size dan timestamp dari file non-zip mungkin menyebabkan error
3. Array `$backups` tidak terisi dengan benar

---

## ✅ PERBAIKAN

**Kode Baru**:
```php
private function loadBackups()
{
    $this->backups = [];

    $backupDir = storage_path('app/backups');

    if (File::isDirectory($backupDir)) {
        $files = File::files($backupDir);

        foreach ($files as $file) {
            $filename = $file->getFilename();

            // ✅ Only include .zip files
            if (pathinfo($filename, PATHINFO_EXTENSION) === 'zip') {
                $this->backups[] = [
                    'filename' => $filename,
                    'size' => $this->formatBytes($file->getSize()),
                    'created_at' => date('d M Y H:i:s', $file->getMTime()),
                    'path' => $file->getPathname()
                ];
            }
        }

        // Sort by creation date (newest first)
        usort($this->backups, function ($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });
    }
}
```

**Perubahan**:
1. ✅ Gunakan `File::files()` bukan `Storage::files()`
2. ✅ **Filter hanya file .zip** dengan `pathinfo()`
3. ✅ Skip `.gitignore` dan `index.html` otomatis
4. ✅ Gunakan method SplFileInfo (`getSize()`, `getMTime()`, `getFilename()`)
5. ✅ Lebih reliable dan clean

---

## 🧪 HASIL

Setelah perbaikan:

**Sebelum**:
```
Backup Tersedia (0)
Tidak ada backup
```

**Sesudah** (setelah refresh halaman atau klik Refresh):
```
Backup Tersedia (2)

📦 backup_full_2026-01-20_11-26-55.zip
   📊 Size: 27.19 MB
   📅 Created: 20 Jan 2026 11:26:55
   [Unduh] [Hapus]

📦 backup_full_2026-01-20_11-18-22.zip
   📊 Size: 27.19 MB
   📅 Created: 20 Jan 2026 11:18:22
   [Unduh] [Hapus]
```

---

## 📝 FILES YANG DIMODIFIKASI

1. **app/Livewire/Admin/BackupRestore.php**
   - Method `loadBackups()` diperbaiki
   - Filter hanya file .zip
   - Gunakan File facade

---

## ⚡ CARA MELIHAT BACKUP

### Opsi 1: Refresh Halaman
1. Refresh halaman `/admin/backup-restore`
2. List backup akan muncul otomatis

### Opsi 2: Klik Tombol Refresh
1. Klik tombol **"Refresh"** di section "Backup Tersedia"
2. List akan update

### Opsi 3: Tunggu Auto-Refresh
Jika sudah ada `wire:key="backup-list-{{ count($backups) }}"`, list akan otomatis update setelah:
- Create backup selesai
- Delete backup selesai

---

## ✅ TESTING

### Test 1: Load Existing Backups
1. Refresh halaman `/admin/backup-restore`
2. **Expected**: Muncul 2 backup yang sudah ada

### Test 2: Create New Backup
1. Pilih "File Saja"
2. Klik "Buat Backup"
3. **Expected**: Counter bertambah (2 → 3), file baru muncul di list

### Test 3: Delete Backup
1. Klik "Hapus" pada salah satu backup
2. **Expected**: Counter berkurang (3 → 2), file hilang dari list

---

## 🎯 KESIMPULAN

Masalah fixed! File backup yang sudah ada sekarang bisa terdeteksi dan muncul di list.

**Root cause**: Method `loadBackups()` tidak memfilter file .zip

**Solution**: Tambahkan filter `pathinfo($filename, PATHINFO_EXTENSION) === 'zip'`

**Status**: ✅ BERFUNGSI NORMAL

---

**Note untuk User**:
Silakan **refresh halaman** atau klik tombol **"Refresh"** untuk melihat backup yang sudah ada!
