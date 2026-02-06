@echo off
echo ============================================================
echo UPLOAD DIAGNOSTIC SCRIPT - Survey Issues Analyzer
echo ============================================================
echo.

echo [1/3] Uploading diagnose_survey_issues.php...
scp diagnose_survey_issues.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload diagnose_survey_issues.php
    pause
    exit /b 1
)
echo OK - diagnose_survey_issues.php uploaded

echo.
echo [2/3] Uploading migrate_survey_answers_SMART.php...
scp migrate_survey_answers_SMART.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload migrate_survey_answers_SMART.php
    pause
    exit /b 1
)
echo OK - migrate_survey_answers_SMART.php uploaded

echo.
echo [3/3] Uploading fix_survey_answer_format.php...
scp fix_survey_answer_format.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload fix_survey_answer_format.php
    pause
    exit /b 1
)
echo OK - fix_survey_answer_format.php uploaded

echo.
echo ============================================================
echo UPLOAD SELESAI!
echo ============================================================
echo.
echo LANGKAH SELANJUTNYA:
echo 1. Jalankan diagnostic dulu untuk analisa masalah:
echo    php diagnose_survey_issues.php
echo.
echo 2. Lihat hasil diagnostic, lalu jalankan SMART migration:
echo    php migrate_survey_answers_SMART.php
echo.
echo 3. Verifikasi hasil di admin panel
echo.
pause
