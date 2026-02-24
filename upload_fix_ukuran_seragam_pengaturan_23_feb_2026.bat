@echo off
echo ======================================
echo Fix Ukuran Seragam - Pengaturan Wajib/Tidak Wajib
echo Tanggal: 23 Februari 2026
echo ======================================
echo.
echo Upload file yang diperbaiki ke production...
echo.

REM Upload file Livewire yang sudah diperbaiki
echo [1/3] Uploading app/Livewire/Admin/DaftarUlang.php...
scp "C:\ppdbmtsn2km-laravel\app\Livewire\Admin\DaftarUlang.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/

echo [2/3] Uploading app/Livewire/Pendaftar/DaftarUlang.php...
scp "C:\ppdbmtsn2km-laravel\app\Livewire\Pendaftar\DaftarUlang.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Pendaftar/

echo [3/3] Uploading resources/views/livewire/admin/daftar-ulang.blade.php...
scp "C:\ppdbmtsn2km-laravel\resources\views\livewire\admin\daftar-ulang.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/

echo.
echo ======================================
echo Clear cache di production...
echo ======================================
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan optimize:clear"

echo.
echo ======================================
echo Upload selesai!
echo ======================================
echo.
echo PERBAIKAN:
echo 1. Ukuran kopyah hanya untuk laki-laki (perempuan tidak ada field ini)
echo 2. Tambah pengaturan di admin untuk wajib/tidak wajib isi semua field ukuran
echo 3. Jika tidak wajib: minimal 1 field harus diisi, sisanya optional
echo 4. Jika wajib: semua field harus diisi (kecuali kopyah untuk perempuan)
echo.
echo CARA PENGGUNAAN:
echo 1. Login sebagai admin
echo 2. Buka /admin/daftar-ulang?activeTab=settings
echo 3. Scroll ke bagian "Pengaturan Ukuran Seragam"
echo 4. Centang "Wajib Isi Semua Field Ukuran" jika ingin wajib
echo 5. Tidak dicentang = pendaftar bisa isi sebagian (default)
echo 6. Klik "Simpan Pengaturan Ukuran"
echo.
echo TEST SEBAGAI PENDAFTAR:
echo 1. Login sebagai pendaftar perempuan yang lulus
echo 2. Buka /pendaftar/daftarulang?activeTab=ukuran
echo 3. Field kopyah tidak muncul (benar)
echo 4. Jika setting "wajib": harus isi semua field yang ada
echo 5. Jika setting "tidak wajib": bisa isi minimal 1 field saja
echo 6. Klik "Simpan Data Ukuran" - harus berhasil!
echo.
pause
