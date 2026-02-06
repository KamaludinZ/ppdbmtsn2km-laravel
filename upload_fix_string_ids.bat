@echo off
echo ============================================================
echo UPLOAD FIX - Survey String IDs to Integer
echo ============================================================
echo.
echo Problem: Answers stored as "21" instead of 21
echo Fix: Convert all numeric strings to integers
echo.

echo Uploading PHP fix script...
scp fix_survey_string_to_integer.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/
if %errorlevel% neq 0 (
    echo.
    echo ❌ SSH not available. Please upload manually:
    echo.
    echo File: C:\ppdbmtsn2km-laravel\fix_survey_string_to_integer.php
    echo To: /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/
    echo.
    echo Via cPanel File Manager:
    echo 1. Login cPanel
    echo 2. File Manager - Root directory
    echo 3. Upload fix_survey_string_to_integer.php
    echo.
    pause
    exit /b 1
)

echo ✓ Script uploaded
echo.
echo ============================================================
echo NEXT STEPS:
echo ============================================================
echo.
echo Run script via Terminal:
echo   cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
echo   php fix_survey_string_to_integer.php
echo.
echo The script will:
echo   1. Analyze all survey answers
echo   2. Show what needs to be fixed
echo   3. Ask confirmation (yes/no)
echo   4. Fix the data
echo   5. Show summary report
echo.
echo See: FIX_SURVEY_STRING_IDS.md for detailed guide
echo.
pause
