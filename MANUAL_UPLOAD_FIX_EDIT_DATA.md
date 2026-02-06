# Manual Upload - Fix Edit Data & Tab Survey

## 📋 File yang Perlu Diupload (4 files)

### 1. Controller Files (2 files)

```
app/Livewire/Admin/DetailPendaftar.php
app/Livewire/Panitia/DetailPendaftar.php
```

**Perubahan:**
- ✅ Tambah section-specific editMode properties (editModeSiswa, editModeOrangtua, dll)
- ✅ Perbaiki method toggleEdit($section) untuk handle setiap section

### 2. View Files (2 files)

```
resources/views/livewire/admin/detail-pendaftar.blade.php
resources/views/livewire/panitia/detail-pendaftar.blade.php
```

**Perubahan:**
- ✅ Tab Survey dipindah ke sebelah kanan "Pendidikan Lain" (sebelum Verifikasi Dokumen)

---

## 🚀 Cara Upload Manual

### Via SCP (Recommended)

```bash
# 1. Upload Admin Controller
scp "app/Livewire/Admin/DetailPendaftar.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/

# 2. Upload Panitia Controller
scp "app/Livewire/Panitia/DetailPendaftar.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Panitia/

# 3. Upload Admin View
scp "resources/views/livewire/admin/detail-pendaftar.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/

# 4. Upload Panitia View
scp "resources/views/livewire/panitia/detail-pendaftar.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/panitia/

# 5. Clear ALL Cache
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan cache:clear && php artisan config:clear && php artisan route:clear && php artisan view:clear && php artisan optimize:clear"
```

### Via Batch Script (Windows)

Jalankan script yang sudah disediakan:
```cmd
cd C:\ppdbmtsn2km-laravel
upload_fix_edit_data_complete.bat
```

---

## ✅ Hasil Setelah Upload

### Tab Survey - Urutan Baru:
1. Data Siswa
2. Data Orang Tua
3. Data Alamat
4. Data Prestasi
5. Keahlian
6. Tahfidz
7. Beasiswa & Bantuan
8. Pendidikan Lain
9. **Survey** ← Dipindah ke sini
10. Verifikasi Dokumen
11. Daftar Ulang

### Edit Data Berfungsi di Semua Tab:
- ✅ Admin - Tab Siswa, Orangtua, Alamat, dll → Edit Data berfungsi
- ✅ Panitia - Tab Siswa, Orangtua, Alamat, dll → Edit Data berfungsi
- ✅ Edit Nilai Rapor tetap berfungsi

---

## 🔍 Verifikasi

Setelah upload, test di production:

### 1. Test di Admin
```
https://pmbm.mtsn2kotamalang.sch.id/admin/pendaftar/13
```
- Klik tab "Data Siswa" → Klik "Edit Data" → Harus berfungsi ✓
- Klik tab "Data Orang Tua" → Klik "Edit Data" → Harus berfungsi ✓
- Klik tab "Data Alamat" → Klik "Edit Data" → Harus berfungsi ✓
- Tab "Survey" harus ada di sebelah kanan "Pendidikan Lain" ✓

### 2. Test di Panitia
```
https://pmbm.mtsn2kotamalang.sch.id/panitia-pendaftaran/pendaftar/13
```
- Klik tab "Data Siswa" → Klik "Edit Data" → Harus berfungsi ✓
- Klik tab "Data Orang Tua" → Klik "Edit Data" → Harus berfungsi ✓
- Tab "Survey" harus ada di sebelah kanan "Pendidikan Lain" ✓

---

## 📝 Penjelasan Teknis

### Masalah Sebelumnya:
- Admin/Panitia hanya punya 1 property: `public $editMode = false;`
- Pendaftar/Formulir punya multiple properties: `editModeSiswa`, `editModeOrangtua`, dll
- Method `toggleEdit()` di Admin/Panitia salah, hanya memanggil `toggleEditMode()`

### Solusi:
```php
// SEBELUM (SALAH)
public $editMode = false;

public function toggleEdit($section = null) {
    $this->toggleEditMode();  // ← Salah, tidak handle section
}

// SESUDAH (BENAR)
public $editMode = false;
public $editModeSiswa = false;
public $editModeOrangtua = false;
public $editModeAlamat = false;
// ... dst untuk semua section

public function toggleEdit($section) {
    $property = 'editMode' . ucfirst($section);  // editMode + Siswa = editModeSiswa
    if (property_exists($this, $property)) {
        $this->$property = !$this->$property;    // Toggle property spesifik
        if ($this->$property) {
            $this->loadDataToProperties();        // Reload data saat enable
        }
    }
}
```

---

## ⚠️ Catatan Penting

1. **Clear cache WAJIB** setelah upload
2. **Hard refresh browser** (Ctrl+Shift+R)
3. Jika masih error, restart PHP-FPM:
   ```bash
   sudo systemctl restart php8.2-fpm
   ```

---

Tanggal: 3 Februari 2026 - 06:00 WIB
