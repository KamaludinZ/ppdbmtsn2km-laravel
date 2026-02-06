@echo off
echo ============================================================
echo UPLOAD FIX - Survey Answer Overwrite Bug
echo ============================================================
echo.
echo BUG FOUND: Semua jawaban overwrite jadi ID yang sama
echo FIX: Radio name attribute + storage format handling
echo.

echo [1/2] Uploading TesPsikologi.php (Fixed save logic)...
scp app/Livewire/Pendaftar/TesPsikologi.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Pendaftar/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload TesPsikologi.php
    pause
    exit /b 1
)
echo OK - TesPsikologi.php uploaded

echo.
echo [2/2] Uploading tes-psikologi.blade.php (Added radio name)...
scp resources/views/livewire/pendaftar/tes-psikologi.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload tes-psikologi.blade.php
    pause
    exit /b 1
)
echo OK - tes-psikologi.blade.php uploaded

echo.
echo ============================================================
echo UPLOAD SELESAI!
echo ============================================================
echo.
echo TESTING STEPS:
echo 1. Clear production cache:
echo    ssh mtsnkot4@103.30.244.77
echo    cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
echo    php artisan view:clear
echo    php artisan optimize:clear
echo.
echo 2. Test submit survey sebagai pendaftar baru
echo    - Jawab semua pertanyaan dengan berbeda
echo    - Klik "Selesai & Simpan"
echo    - Cek di admin panel - setiap pertanyaan harus punya jawaban berbeda
echo    - RAW harus menunjukkan ID yang benar (tidak semua ID sama)
echo.
echo 3. Verify di database:
echo    psql -U mtsnkot4 -d mtsnkot4_ppdb
echo    SELECT pendaftar_id, survey_question_id, jawaban
echo    FROM survey_answers
echo    WHERE pendaftar_id = [NEW_PENDAFTAR_ID]
echo    ORDER BY survey_question_id;
echo.
pause
