@echo off
echo ========================================
echo Upload SEMUA Perbaikan ke Production
echo ========================================
echo.
echo PERBAIKAN YANG AKAN DIUPLOAD:
echo [1] URL Tabs - Setiap tab punya URL sendiri (bisa bookmark dan share)
echo [2] Edit Data berfungsi di semua tab (Admin dan Panitia)
echo [3] Independent Edit Mode per tab (tidak saling conflict)
echo [4] Tab Survey dipindah setelah "Pendidikan Lain"
echo [5] Edit Nilai Rapor berfungsi
echo [6] Fix tombol Lihat Sertifikat Prestasi (muncul untuk semua prestasi)
echo [7] Field KIP, KKS, PKH bisa huruf dan angka (max 25 karakter)
echo.

set SERVER=mtsnkot4@103.30.244.77
set REMOTE_PATH=/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

echo [1/9] Upload Admin Controller...
scp "app/Livewire/Admin/DetailPendaftar.php" %SERVER%:%REMOTE_PATH%/app/Livewire/Admin/
if %errorlevel% neq 0 (
    echo ERROR: Gagal upload Admin controller
    pause
    exit /b 1
)
echo [OK] Admin controller uploaded

echo [2/9] Upload Panitia Controller...
scp "app/Livewire/Panitia/DetailPendaftar.php" %SERVER%:%REMOTE_PATH%/app/Livewire/Panitia/
if %errorlevel% neq 0 (
    echo ERROR: Gagal upload Panitia controller
    pause
    exit /b 1
)
echo [OK] Panitia controller uploaded

echo [3/9] Upload Pendaftar Formulir Controller...
scp "app/Livewire/Pendaftar/Formulir.php" %SERVER%:%REMOTE_PATH%/app/Livewire/Pendaftar/
if %errorlevel% neq 0 (
    echo ERROR: Gagal upload Pendaftar Formulir controller
    pause
    exit /b 1
)
echo [OK] Pendaftar Formulir controller uploaded

echo [4/9] Upload Admin View...
scp "resources/views/livewire/admin/detail-pendaftar.blade.php" %SERVER%:%REMOTE_PATH%/resources/views/livewire/admin/
if %errorlevel% neq 0 (
    echo ERROR: Gagal upload Admin view
    pause
    exit /b 1
)
echo [OK] Admin view uploaded

echo [5/9] Upload Panitia View...
scp "resources/views/livewire/panitia/detail-pendaftar.blade.php" %SERVER%:%REMOTE_PATH%/resources/views/livewire/panitia/
if %errorlevel% neq 0 (
    echo ERROR: Gagal upload Panitia view
    pause
    exit /b 1
)
echo [OK] Panitia view uploaded

echo [6/9] Upload Tab Data Siswa Template...
scp "resources/views/tab_templates/tab-data-siswa.blade.php" %SERVER%:%REMOTE_PATH%/resources/views/tab_templates/
if %errorlevel% neq 0 (
    echo ERROR: Gagal upload Tab Data Siswa template
    pause
    exit /b 1
)
echo [OK] Tab Data Siswa template uploaded

echo [7/9] Upload Tab Data Orangtua Template...
scp "resources/views/tab_templates/tab-data-orangtua.blade.php" %SERVER%:%REMOTE_PATH%/resources/views/tab_templates/
if %errorlevel% neq 0 (
    echo ERROR: Gagal upload Tab Data Orangtua template
    pause
    exit /b 1
)
echo [OK] Tab Data Orangtua template uploaded

echo [8/9] Clear ALL caches...
ssh %SERVER% "cd %REMOTE_PATH% && php artisan cache:clear && php artisan config:clear && php artisan route:clear && php artisan view:clear && php artisan optimize:clear"
if %errorlevel% neq 0 (
    echo ERROR: Gagal clear cache
    pause
    exit /b 1
)
echo [OK] All caches cleared

echo [9/9] Delete compiled views manually...
ssh %SERVER% "cd %REMOTE_PATH% && rm -rf storage/framework/views/*.php"
echo [OK] Compiled views deleted

echo.
echo ========================================
echo UPLOAD BERHASIL!
echo ========================================
echo.
echo YANG SUDAH DIPERBAIKI:
echo ✓ URL Tabs - Setiap tab punya URL sendiri (bisa bookmark)
echo ✓ Independent Edit Mode - Setiap tab edit independen
echo ✓ Edit Data berfungsi di semua tab (Admin dan Panitia)
echo ✓ Edit Nilai Rapor berfungsi (fitur baru dengan modal)
echo ✓ Tab Survey dipindah setelah "Pendidikan Lain"
echo ✓ Tombol Lihat Sertifikat muncul untuk SETIAP prestasi
echo ✓ Field KIP, KKS, PKH bisa huruf+angka maksimal 25 karakter
echo.
echo FILE YANG DIUPLOAD (7 files):
echo - app/Livewire/Admin/DetailPendaftar.php
echo - app/Livewire/Panitia/DetailPendaftar.php
echo - app/Livewire/Pendaftar/Formulir.php
echo - resources/views/livewire/admin/detail-pendaftar.blade.php
echo - resources/views/livewire/panitia/detail-pendaftar.blade.php
echo - resources/views/tab_templates/tab-data-siswa.blade.php (NEW)
echo - resources/views/tab_templates/tab-data-orangtua.blade.php (NEW)
echo.
echo URUTAN TAB BARU:
echo 1. Data Siswa
echo 2. Data Orang Tua
echo 3. Data Alamat
echo 4. Data Prestasi
echo 5. Keahlian
echo 6. Tahfidz
echo 7. Beasiswa dan Bantuan
echo 8. Pendidikan Lain
echo 9. Survey (DIPINDAH KE SINI)
echo 10. Verifikasi Dokumen
echo 11. Daftar Ulang
echo.
echo Silakan test di:
echo.
echo [URL TABS - BARU!]
echo - Admin Tab Siswa: https://pmbm.mtsn2kotamalang.sch.id/admin/pendaftar/13?activeTab=siswa
echo - Admin Tab Orangtua: https://pmbm.mtsn2kotamalang.sch.id/admin/pendaftar/13?activeTab=orangtua
echo - Admin Tab Dokumen: https://pmbm.mtsn2kotamalang.sch.id/admin/pendaftar/13?activeTab=dokumen
echo - Panitia Tab Siswa: https://pmbm.mtsn2kotamalang.sch.id/panitia-pendaftaran/pendaftar/13?activeTab=siswa
echo.
echo [FITUR LAINNYA]
echo - Pendaftar Prestasi: https://pmbm.mtsn2kotamalang.sch.id/pendaftar/formulir?activeTab=prestasi
echo - Pendaftar KIP: https://pmbm.mtsn2kotamalang.sch.id/pendaftar/formulir?activeTab=siswa
echo.
echo Hard refresh browser: Ctrl + Shift + R
echo.
pause
