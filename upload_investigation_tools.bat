@echo off
echo ============================================================
echo UPLOAD INVESTIGATION TOOLS - Survey Structure Analysis
echo ============================================================
echo.

echo [1/2] Uploading diagnose_survey_issues.php (FIXED)...
scp diagnose_survey_issues.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload diagnose_survey_issues.php
    pause
    exit /b 1
)
echo OK - diagnose_survey_issues.php uploaded

echo.
echo [2/2] Uploading investigate_survey_structure.php (NEW)...
scp investigate_survey_structure.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload investigate_survey_structure.php
    pause
    exit /b 1
)
echo OK - investigate_survey_structure.php uploaded

echo.
echo ============================================================
echo UPLOAD SELESAI!
echo ============================================================
echo.
echo LANGKAH SELANJUTNYA:
echo.
echo 1. SSH ke production:
echo    ssh mtsnkot4@103.30.244.77
echo.
echo 2. Masuk ke direktori:
echo    cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
echo.
echo 3. Jalankan investigation script:
echo    php investigate_survey_structure.php ^> investigation_report.txt
echo.
echo 4. Lihat hasil:
echo    cat investigation_report.txt
echo.
echo 5. Jalankan diagnostic lagi (sudah diperbaiki):
echo    php diagnose_survey_issues.php
echo.
pause
