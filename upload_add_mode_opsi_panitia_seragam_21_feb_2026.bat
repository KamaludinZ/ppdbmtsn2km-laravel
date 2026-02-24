@echo off
echo ========================================
echo Upload Mode Opsi Panitia Seragam
echo Tanggal: 21 Februari 2026
echo ========================================
echo.
echo PERUBAHAN:
echo 1. Settings tab: dropdown mode opsi (radio/checkbox)
echo 2. Kolom nominal biaya: multi-select display dengan total
echo.

REM Upload file yang diperbaiki
echo [1/3] Uploading Panitia Seragam Settings Tab Partial...
timeout /t 60 /nobreak >nul
scp "C:\ppdbmtsn2km-laravel\resources\views\livewire\panitia-seragam\partials\settings-tab.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/panitia-seragam/partials/

echo.
echo [2/3] Uploading Panitia Seragam Daftar Ulang Main View...
timeout /t 60 /nobreak >nul
scp "C:\ppdbmtsn2km-laravel\resources\views\livewire\panitia-seragam\daftar-ulang.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/panitia-seragam/

echo.
echo [3/3] Uploading Dokumentasi...
timeout /t 30 /nobreak >nul
scp "C:\ppdbmtsn2km-laravel\ADD_MODE_OPSI_PANITIA_SERAGAM_21_FEB_2026.md" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/

echo.
echo Running cache clear di server...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan optimize:clear"

echo.
echo ========================================
echo Upload Selesai!
echo ========================================
echo.
echo File yang diupload:
echo 1. resources/views/livewire/panitia-seragam/partials/settings-tab.blade.php
echo 2. resources/views/livewire/panitia-seragam/daftar-ulang.blade.php
echo 3. ADD_MODE_OPSI_PANITIA_SERAGAM_21_FEB_2026.md
echo.
echo ========================================
echo TESTING CHECKLIST
echo ========================================
echo.
echo Test 1: Settings Tab - Dropdown Mode
echo - Login Panitia Seragam
echo - /panitia-seragam/daftar-ulang?activeTab=settings
echo - Buat/edit paket
echo - Verify: Dropdown "Mode Pemilihan Opsi" tampil
echo - Verify: Ada 2 opsi (Radio/Checkbox)
echo.
echo Test 2: Kolom Nominal Biaya - Multi-select
echo - Pendaftar pilih paket checkbox, centang 3 opsi
echo - Login Panitia Seragam
echo - Tab "Daftar Ulang Seragam"
echo - Verify: Badge "Multi Select (3 opsi)"
echo - Verify: List 3 opsi dengan numbering
echo - Verify: Total sum di bawah
echo.
echo Test 3: Kolom Nominal Biaya - Single Option
echo - Pendaftar pilih paket radio, pilih 1 opsi
echo - Login Panitia Seragam
echo - Verify: Tampil nama dan amount (format lama)
echo - Verify: Tidak ada badge
echo.
echo Test 4: Konsistensi dengan Admin
echo - Bandingkan tampilan di Admin vs Panitia Seragam
echo - Verify: Format tampilan identical
echo.
echo ========================================
echo EXPECTED RESULTS
echo ========================================
echo.
echo 1. Settings Tab:
echo    - Dropdown mode opsi tampil ✅
echo    - Purple theme consistent ✅
echo    - Wire:model binding bekerja ✅
echo.
echo 2. Kolom Nominal - Multi-select:
echo    - Badge indicator ✅
echo    - Itemized list dengan total ✅
echo    - Purple color highlight ✅
echo.
echo 3. Kolom Nominal - Single:
echo    - Original format ✅
echo    - Backward compatible ✅
echo.
echo 4. Feature Parity:
echo    - Same as Admin ✅
echo    - Panitia Seragam independent ✅
echo.
pause
