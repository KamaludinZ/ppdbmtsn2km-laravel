@echo off
echo ============================================================
echo FINAL FIX - Survey Overwrite Bug (ROOT CAUSE FOUND!)
echo ============================================================
echo.
echo ROOT CAUSE: Model cast forcing all jawaban to array
echo FIX: Remove auto-cast, let PostgreSQL JSONB handle it
echo.

echo [1/2] Uploading SurveyAnswer.php (CRITICAL MODEL FIX)...
scp app/Models/SurveyAnswer.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Models/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload SurveyAnswer.php
    pause
    exit /b 1
)
echo OK - SurveyAnswer.php uploaded

echo.
echo [2/2] Uploading TesPsikologi.php (with debug logging)...
scp app/Livewire/Pendaftar/TesPsikologi.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Pendaftar/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload TesPsikologi.php
    pause
    exit /b 1
)
echo OK - TesPsikologi.php uploaded

echo.
echo ============================================================
echo UPLOAD SELESAI!
echo ============================================================
echo.
echo CRITICAL NEXT STEPS:
echo.
echo 1. SSH dan clear cache:
echo    ssh mtsnkot4@103.30.244.77
echo    cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
echo    php artisan view:clear
echo    php artisan optimize:clear
echo    php artisan cache:clear
echo.
echo 2. DELETE semua survey answers yang corrupt:
echo    psql -U mtsnkot4_uspmbm -d mtsnkot4_dbpmbm
echo    DELETE FROM survey_answers;
echo    \q
echo.
echo 3. Test submit survey dengan pendaftar baru:
echo    - Jawab semua pertanyaan dengan BERBEDA
echo    - Klik "Selesai & Simpan"
echo.
echo 4. Verify di database:
echo    psql -U mtsnkot4_uspmbm -d mtsnkot4_dbpmbm
echo    SELECT survey_question_id, jawaban FROM survey_answers
echo    WHERE pendaftar_id = [NEW_ID] ORDER BY survey_question_id;
echo.
echo 5. Expected result: Semua jawaban BERBEDA, tidak ada yang overwrite!
echo.
pause
