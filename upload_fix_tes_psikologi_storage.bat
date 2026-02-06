@echo off
echo ============================================================
echo FIX TES PSIKOLOGI STORAGE - Apply Survey Umum Pattern
echo ============================================================
echo.
echo Issue: Soal terakhir tidak tersimpan saat klik "Selesai & Simpan"
echo Solution: Save ALL questions like Survey Umum (proven working)
echo.

echo [1/1] Uploading TesPsikologi.php...
scp app/Livewire/Pendaftar/TesPsikologi.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Pendaftar/
if %errorlevel% neq 0 goto manual_upload

echo.
echo ✓ File uploaded successfully
echo.
echo ============================================================
echo NEXT STEPS:
echo ============================================================
echo.
echo Clear cache via Terminal:
echo   cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
echo   php artisan optimize:clear
echo   php artisan view:clear
echo.
echo Test - Pendaftar:
echo   1. Login sebagai Pendaftar
echo   2. Buka halaman Tes Psikologi
echo   3. Isi survey sampai soal terakhir
echo   4. JANGAN klik "Next" - langsung klik "Selesai & Simpan"
echo   5. Check semua jawaban tersimpan (termasuk soal terakhir)
echo.
echo Test - Admin:
echo   1. Login sebagai Admin
echo   2. Buka: /admin/tes-psikologi?mainTab=jawaban-survey
echo   3. Check semua jawaban pendaftar tampil dengan benar
echo.
echo See: FIX_TES_PSIKOLOGI_STORAGE_FINAL.md for detailed guide
echo.
pause
exit /b 0

:manual_upload
echo.
echo ❌ SSH not available. Please upload manually:
echo.
echo File:
echo   Local: C:\ppdbmtsn2km-laravel\app\Livewire\Pendaftar\TesPsikologi.php
echo   Remote: /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Pendaftar/
echo.
echo Via cPanel File Manager:
echo   1. Login cPanel
echo   2. Navigate to: app/Livewire/Pendaftar/
echo   3. Upload TesPsikologi.php (overwrite existing)
echo.
echo IMPORTANT: Clear cache after upload!
echo   php artisan optimize:clear
echo   php artisan view:clear
echo.
pause
exit /b 1
