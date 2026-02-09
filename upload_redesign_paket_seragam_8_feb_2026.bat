@echo off
echo ============================================================
echo UPLOAD: Redesign Paket Seragam - Dependent Dropdown
echo Tanggal: 8 Februari 2026
echo ============================================================
echo.

REM === Database Migration ===
echo [1/6] Uploading migration file...
timeout /t 2 /nobreak >nul
scp database/migrations/2026_02_08_100000_add_opsi_seragam_to_seragam_table.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/database/migrations/

REM === Model ===
echo [2/6] Uploading Seragam model...
timeout /t 1 /nobreak >nul
scp app/Models/Seragam.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Models/

REM === Admin Livewire ===
echo [3/6] Uploading Admin DaftarUlang Livewire...
timeout /t 1 /nobreak >nul
scp app/Livewire/Admin/DaftarUlang.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/

REM === Admin View ===
echo [4/6] Uploading Admin daftar-ulang view...
timeout /t 1 /nobreak >nul
scp "resources/views/livewire/admin/daftar-ulang.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/

REM === Pendaftar Livewire ===
echo [5/6] Uploading Pendaftar DaftarUlang Livewire...
timeout /t 1 /nobreak >nul
scp app/Livewire/Pendaftar/DaftarUlang.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Pendaftar/

REM === Pendaftar View ===
echo [6/6] Uploading Pendaftar daftar-ulang view...
timeout /t 1 /nobreak >nul
scp "resources/views/livewire/pendaftar/daftar-ulang.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/

echo.
echo ============================================================
echo Upload selesai! Selanjutnya jalankan di server:
echo ============================================================
echo ssh mtsnkot4@103.30.244.77
echo cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
echo php artisan migrate
echo php artisan optimize:clear
echo ============================================================
echo.
pause
