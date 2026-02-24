@echo off
echo ======================================
echo Fix Ukuran Kopyah untuk Perempuan
echo Tanggal: 23 Februari 2026
echo ======================================
echo.
echo Upload file yang diperbaiki ke production...
echo.

REM Upload file Livewire DaftarUlang Pendaftar yang sudah diperbaiki
echo [1/1] Uploading app/Livewire/Pendaftar/DaftarUlang.php...
scp "C:\ppdbmtsn2km-laravel\app\Livewire\Pendaftar\DaftarUlang.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Pendaftar/

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
echo - Validasi ukuran_kopyah hanya required untuk laki-laki
echo - Untuk perempuan, ukuran_kopyah tidak required dan di-set null
echo - Fix error saat simpan data ukuran untuk pendaftar perempuan
echo.
echo TEST:
echo 1. Login sebagai pendaftar perempuan yang lulus
echo 2. Buka halaman /pendaftar/daftarulang?activeTab=ukuran
echo 3. Field ukuran kopyah tidak muncul (sudah benar sebelumnya)
echo 4. Isi semua field ukuran yang tersedia
echo 5. Klik "Simpan Data Ukuran"
echo 6. Data harus berhasil disimpan tanpa error!
echo.
pause
