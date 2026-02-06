@echo off
echo ============================================================
echo INCREMENTAL SAVE FIX - Survey Overwrite Bug (FINAL SOLUTION!)
echo ============================================================
echo.
echo ROOT CAUSE IDENTIFIED BY USER:
echo - Tombol "Selanjutnya" save incremental = OK (no overwrite)
echo - Tombol "Selesai & Simpan" loop all answers = BUG (overwrite!)
echo.
echo SOLUTION:
echo - simpanSurvey() now saves ONLY last question (incremental)
echo - Previous answers already saved by nextQuestion()
echo - No more loop = No more overwrite!
echo.
echo ============================================================
echo.

echo [1/2] Uploading TesPsikologi.php (CRITICAL FIX - Incremental Save)...
scp app/Livewire/Pendaftar/TesPsikologi.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Pendaftar/
if %errorlevel% neq 0 (
    echo.
    echo ❌ ERROR: Failed to upload TesPsikologi.php
    echo.
    echo SSH not available. Please upload manually:
    echo.
    echo Via FileZilla/WinSCP:
    echo   Local:  C:\ppdbmtsn2km-laravel\app\Livewire\Pendaftar\TesPsikologi.php
    echo   Remote: /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Pendaftar/
    echo.
    echo Via cPanel File Manager:
    echo   1. Login cPanel
    echo   2. File Manager - Navigate to app/Livewire/Pendaftar/
    echo   3. Upload TesPsikologi.php (overwrite existing)
    echo.
    pause
    exit /b 1
)
echo ✓ TesPsikologi.php uploaded
echo.

echo [2/2] Uploading SurveyAnswer.php (Model Fix - Remove array cast)...
scp app/Models/SurveyAnswer.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Models/
if %errorlevel% neq 0 (
    echo.
    echo ❌ ERROR: Failed to upload SurveyAnswer.php
    echo.
    echo Please upload manually via cPanel File Manager:
    echo   Local:  C:\ppdbmtsn2km-laravel\app\Models\SurveyAnswer.php
    echo   Remote: /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Models/
    echo.
    pause
    exit /b 1
)
echo ✓ SurveyAnswer.php uploaded
echo.

echo ============================================================
echo ✅ UPLOAD COMPLETE!
echo ============================================================
echo.
echo CRITICAL NEXT STEPS:
echo.
echo 1. CLEAR CACHE (WAJIB!):
echo    Via cPanel Terminal:
echo      cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
echo      php artisan view:clear
echo      php artisan optimize:clear
echo      php artisan cache:clear
echo.
echo    Or via File Manager:
echo      Delete all files in: bootstrap/cache/
echo.
echo 2. DELETE CORRUPT DATA (REQUIRED!):
echo    Via phpPgAdmin:
echo      DELETE FROM survey_answers;
echo.
echo 3. TEST SURVEY:
echo    - Login sebagai pendaftar
echo    - Isi survey dengan jawaban BERBEDA tiap pertanyaan
echo    - Klik tombol "Selanjutnya" untuk setiap pertanyaan
echo    - Klik "Selesai & Simpan" di pertanyaan terakhir
echo.
echo 4. VERIFY DATABASE:
echo    SELECT survey_question_id, jawaban
echo    FROM survey_answers
echo    WHERE pendaftar_id = [ID]
echo    ORDER BY survey_question_id;
echo.
echo    Expected: SEMUA jawaban BERBEDA (tidak ada yang sama!)
echo.
echo ============================================================
echo.
echo See MANUAL_UPLOAD_INCREMENTAL_FIX.md for detailed guide.
echo.
pause
