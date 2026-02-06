@echo off
echo ============================================================
echo ADD RINGKASAN JAWABAN - Survey Tes Psikologi
echo ============================================================
echo.
echo Feature: Tambah bar chart ringkasan jawaban
echo Target: Admin + Panitia Tes Psikologi
echo.

echo [1/2] Uploading Admin file...
scp resources/views/livewire/admin/partials/tes-psikologi-jawaban-survey.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/partials/
if %errorlevel% neq 0 goto manual_upload

echo [2/2] Uploading Panitia file...
scp resources/views/livewire/panitia/partials/tes-psikologi-jawaban-survey.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/panitia/partials/
if %errorlevel% neq 0 goto manual_upload

echo.
echo ✓ Both files uploaded successfully
echo.
echo ============================================================
echo NEXT STEPS:
echo ============================================================
echo.
echo Clear cache via Terminal:
echo   cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
echo   php artisan view:clear
echo   php artisan optimize:clear
echo.
echo Test - Admin:
echo   1. Login sebagai Admin
echo   2. Buka: /admin/tes-psikologi?mainTab=jawaban-survey
echo   3. Scroll ke bawah - Check "Ringkasan Jawaban" muncul
echo.
echo Test - Panitia:
echo   1. Login sebagai Panitia Tes Psikologi
echo   2. Buka: /panitia-psikologi/tes-psikologi?mainTab=jawaban-survey
echo   3. Scroll ke bawah - Check "Ringkasan Jawaban" muncul
echo.
echo See: ADD_RINGKASAN_TES_PSIKOLOGI.md for detailed guide
echo.
pause
exit /b 0

:manual_upload
echo.
echo ❌ SSH not available. Please upload manually:
echo.
echo File 1 (Admin):
echo   Local: C:\ppdbmtsn2km-laravel\resources\views\livewire\admin\partials\tes-psikologi-jawaban-survey.blade.php
echo   Remote: /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/partials/
echo.
echo File 2 (Panitia):
echo   Local: C:\ppdbmtsn2km-laravel\resources\views\livewire\panitia\partials\tes-psikologi-jawaban-survey.blade.php
echo   Remote: /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/panitia/partials/
echo.
echo Via cPanel File Manager:
echo   1. Login cPanel
echo   2. Navigate to each folder
echo   3. Upload files (overwrite existing)
echo.
pause
exit /b 1
