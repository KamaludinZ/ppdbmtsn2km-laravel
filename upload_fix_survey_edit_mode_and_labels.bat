@echo off
echo ========================================
echo UPLOAD: Fix Survey Edit Mode and Labels
echo /pendaftar/formulir?activeTab=survey
echo ========================================
echo.
echo Tanggal: 5 Februari 2026
echo Total Files: 2
echo Migration: NO
echo.
echo FIXES:
echo 1. Edit mode returns empty - FIXED
echo 2. Display shows IDs not labels - FIXED
echo 3. Export shows IDs not labels - FIXED
echo.
echo ========================================
echo.

REM Change to project directory
cd /d C:\ppdbmtsn2km-laravel

echo [1/2] Uploading Livewire Controller (Formulir)...
scp app/Livewire/Pendaftar/Formulir.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Pendaftar/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload controller
    pause
    exit /b 1
)
echo SUCCESS: Controller uploaded
echo.

echo [2/2] Uploading Model (SurveyAnswer)...
scp app/Models/SurveyAnswer.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Models/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload model
    pause
    exit /b 1
)
echo SUCCESS: Model uploaded
echo.

echo ========================================
echo [3/3] Clearing Cache...
echo ========================================
echo.

ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan optimize:clear && php artisan view:clear"
if %errorlevel% neq 0 (
    echo WARNING: Cache clear may have failed
)
echo SUCCESS: Cache cleared
echo.

echo ========================================
echo UPLOAD COMPLETE!
echo ========================================
echo.
echo Summary of Changes:
echo.
echo FILE 1: app/Livewire/Pendaftar/Formulir.php
echo   - Created loadSurveyAnswers() method
echo   - Modified mount() to use loadSurveyAnswers()
echo   - Modified toggleEdit() to reload survey answers in edit mode
echo.
echo FILE 2: app/Models/SurveyAnswer.php
echo   - Fixed getJawabanFormatted() to convert option IDs to labels
echo   - Handles text/textarea (return text directly)
echo   - Handles radio/select (return single label)
echo   - Handles checkbox (return comma-separated labels)
echo.
echo IMPACT:
echo   - Edit mode now shows previously selected options ✅
echo   - View mode shows labels instead of IDs ✅
echo   - Excel export shows labels instead of IDs ✅
echo.
echo ========================================
echo TESTING CHECKLIST
echo ========================================
echo.
echo 1. Test Edit Mode (/pendaftar/formulir?activeTab=survey)
echo    a. Go to survey tab
echo    b. Fill survey and save
echo    c. Click "Edit Data"
echo    d. ✅ Previously selected options should show
echo.
echo 2. Test View Mode Display
echo    a. After saving survey
echo    b. ✅ Should show option LABELS not IDs
echo.
echo 3. Test Excel Export
echo    a. Go to /admin/tes-psikologi
echo    b. Click "Export Excel"
echo    c. ✅ Survey columns should show LABELS not IDs
echo.
echo Documentation: FIX_SURVEY_EDIT_MODE_AND_LABELS_COMPLETE.md
echo.
echo ========================================
pause
