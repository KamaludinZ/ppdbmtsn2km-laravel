@echo off
echo ============================================================
echo UPLOAD FIX - Survey Responses Display
echo ============================================================
echo.
echo Problem: Ringkasan jawaban tidak muncul
echo Fix: Update field names (question_text -^> pertanyaan)
echo.

echo Uploading fixed file...
scp resources/views/livewire/admin/partials/survey-responses.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/partials/
if %errorlevel% neq 0 (
    echo.
    echo ❌ SSH not available. Please upload manually:
    echo.
    echo File: C:\ppdbmtsn2km-laravel\resources\views\livewire\admin\partials\survey-responses.blade.php
    echo To: /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/partials/
    echo.
    echo Via cPanel File Manager:
    echo 1. Login cPanel
    echo 2. File Manager - Navigate to resources/views/livewire/admin/partials/
    echo 3. Upload survey-responses.blade.php (overwrite existing)
    echo.
    pause
    exit /b 1
)

echo ✓ File uploaded
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
echo Test fix:
echo   1. Login sebagai Admin
echo   2. Buka: /admin/survey-management?mainTab=responses
echo   3. Check: Ringkasan Jawaban muncul di bawah tabel
echo.
echo See: FIX_SURVEY_RESPONSES_DISPLAY.md for detailed guide
echo.
pause
