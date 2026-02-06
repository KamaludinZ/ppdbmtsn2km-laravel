@echo off
REM ============================================================
REM UPLOAD COMPLETE SURVEY FIX - 5 Februari 2026
REM ============================================================
REM
REM Perbaikan sistem survey dengan VALUE-based approach:
REM - Form save VALUE (bukan Option ID)
REM - Display match by VALUE (bukan Option ID)
REM - Admin panel dengan panduan jelas
REM - Migration untuk indexes & constraints
REM
REM ============================================================

echo.
echo ============================================================
echo  UPLOAD COMPLETE SURVEY FIX
echo  Date: 5 Februari 2026
echo ============================================================
echo.

REM Files to upload:
REM 1. resources/views/livewire/pendaftar/tes-psikologi.blade.php
REM 2. resources/views/tab_templates/tab-survey.blade.php
REM 3. resources/views/livewire/admin/survey-management.blade.php (updated: helper text)
REM 4. app/Livewire/Admin/TesPsikologi.php
REM 5. app/Livewire/Panitia/TesPsikologi.php
REM 6. app/Livewire/Pendaftar/TesPsikologi.php (updated: checkbox array init)
REM 7. database/migrations/2026_02_05_204151_add_indexes_and_constraints_to_survey_tables.php

echo [1/7] Uploading pendaftar form...
scp resources\views\livewire\pendaftar\tes-psikologi.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/
if errorlevel 1 (
    echo ERROR: Failed to upload pendaftar form
    pause
    exit /b 1
)
echo SUCCESS: Pendaftar form uploaded
echo.

echo [2/7] Uploading admin tab-survey...
scp resources\views\tab_templates\tab-survey.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/tab_templates/
if errorlevel 1 (
    echo ERROR: Failed to upload tab-survey
    pause
    exit /b 1
)
echo SUCCESS: Tab-survey uploaded
echo.

echo [3/7] Uploading admin survey management...
scp resources\views\livewire\admin\survey-management.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/
if errorlevel 1 (
    echo ERROR: Failed to upload survey-management
    pause
    exit /b 1
)
echo SUCCESS: Survey management uploaded
echo.

echo [4/7] Uploading Admin TesPsikologi.php...
scp app\Livewire\Admin\TesPsikologi.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/
if errorlevel 1 (
    echo ERROR: Failed to upload Admin TesPsikologi
    pause
    exit /b 1
)
echo SUCCESS: Admin TesPsikologi uploaded
echo.

echo [5/7] Uploading Panitia TesPsikologi.php...
scp app\Livewire\Panitia\TesPsikologi.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Panitia/
if errorlevel 1 (
    echo ERROR: Failed to upload Panitia TesPsikologi
    pause
    exit /b 1
)
echo SUCCESS: Panitia TesPsikologi uploaded
echo.

echo [6/7] Uploading Pendaftar TesPsikologi.php (checkbox fix)...
scp app\Livewire\Pendaftar\TesPsikologi.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Pendaftar/
if errorlevel 1 (
    echo ERROR: Failed to upload Pendaftar TesPsikologi
    pause
    exit /b 1
)
echo SUCCESS: Pendaftar TesPsikologi uploaded
echo.

echo [7/7] Uploading migration file...
scp database\migrations\2026_02_05_204151_add_indexes_and_constraints_to_survey_tables.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/database/migrations/
if errorlevel 1 (
    echo ERROR: Failed to upload migration
    pause
    exit /b 1
)
echo SUCCESS: Migration uploaded
echo.

echo ============================================================
echo  ALL FILES UPLOADED SUCCESSFULLY!
echo ============================================================
echo.
echo Next steps:
echo 1. Clear cache: php artisan optimize:clear
echo 2. Run migration: php artisan migrate (optional)
echo 3. Test form submission (pendaftar)
echo 4. Verify display (admin)
echo.
echo See SESSION_SUMMARY_5_FEB_2026_COMPLETE_FIX.md for details
echo ============================================================
echo.

pause
