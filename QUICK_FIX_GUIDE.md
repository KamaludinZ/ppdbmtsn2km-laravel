# Quick Fix Guide - Edit Nilai Rapor Production

## 🚨 Masalah
1. **Panitia:** Error 500 - `toggleEdit method not found`
2. **Admin:** Button Simpan tidak berfungsi

## 🔧 Solusi Cepat

### Step 1: Upload File
```cmd
cd C:\ppdbmtsn2km-laravel
upload_fix_edit_nilai_rapor.bat
```

### Step 2: Verifikasi (via SSH)
```bash
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

# Cek jumlah baris
wc -l app/Livewire/Panitia/DetailPendaftar.php    # Harus: 929
wc -l app/Livewire/Admin/DetailPendaftar.php      # Harus: 2577

# Cek method toggleEdit
grep -n "public function toggleEdit" app/Livewire/Panitia/DetailPendaftar.php
# Output harus: 307:    public function toggleEdit($section = null)
```

### Step 3: Clear All Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear
```

### Step 4: Test
- **Panitia:** https://pmbm.mtsn2kotamalang.sch.id/panitia/pendaftar/[id]
  - Tidak boleh error 500 ✓
  - Tombol "Edit Data" berfungsi ✓
  - Tombol "Edit Nilai" muncul & berfungsi ✓
  
- **Admin:** https://pmbm.mtsn2kotamalang.sch.id/admin/pendaftar/[id]
  - Tombol "Edit Nilai" muncul ✓
  - Button "Simpan" berfungsi dengan notifikasi sukses ✓

## 📋 File yang Terupload
- ✅ `app/Livewire/Panitia/DetailPendaftar.php` (929 baris)
- ✅ `app/Livewire/Admin/DetailPendaftar.php` (2577 baris)
- ✅ `resources/views/livewire/panitia/detail-pendaftar.blade.php`
- ✅ `resources/views/livewire/admin/detail-pendaftar.blade.php`

## ⚠️ Jika Masih Error
1. Restart PHP-FPM: `sudo systemctl restart php8.2-fpm`
2. Atau restart Apache: `sudo systemctl restart apache2`
3. Clear browser cache & hard refresh (Ctrl+Shift+R)
4. Cek log: `tail -50 storage/logs/laravel.log | grep toggleEdit`

---
**Dibuat:** 3 Feb 2026 - 05:30 WIB
