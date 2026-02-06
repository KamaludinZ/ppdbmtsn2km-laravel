@echo off
echo ========================================
echo UPLOAD FIX FILE TIMESTAMP DISPLAY
echo ========================================
echo.
echo File yang akan di-upload:
echo 1. app/Livewire/Admin/DetailPendaftar.php
echo 2. app/Livewire/Panitia/DetailPendaftar.php
echo 3. app/Services/FileNamingService.php
echo 4. resources/views/livewire/admin/detail-pendaftar.blade.php
echo 5. resources/views/livewire/panitia/detail-pendaftar.blade.php
echo.
echo Fitur: Timestamp akan muncul pada nama file di tab Dokumen
echo Format: rapor-PPDB2024001-Nama_Pendaftar-20260205_143052.pdf
echo.
pause

echo.
echo Uploading files...
echo.

scp app/Livewire/Admin/DetailPendaftar.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/
scp app/Livewire/Panitia/DetailPendaftar.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Panitia/
scp app/Services/FileNamingService.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Services/
scp resources/views/livewire/admin/detail-pendaftar.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/
scp resources/views/livewire/panitia/detail-pendaftar.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/panitia/

echo.
echo Clearing cache di production...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan optimize:clear"

echo.
echo ========================================
echo UPLOAD SELESAI!
echo ========================================
echo.
echo CARA TEST:
echo 1. Login sebagai Admin/Panitia
echo 2. Buka detail pendaftar: /admin/pendaftar/[ID]?activeTab=dokumen
echo 3. Upload/Replace salah satu dokumen
echo 4. Setelah upload, nama file akan menampilkan timestamp
echo    Contoh: rapor-PPDB2024001-Ahmad_Fulan-20260205_143052.pdf
echo.
echo CATATAN PENTING:
echo - File yang sudah ada sebelumnya akan tetap menggunakan nama lama
echo - Timestamp hanya muncul pada file yang baru di-upload setelah update ini
echo - Untuk file lama, silakan re-upload agar mendapat timestamp
echo.
pause
