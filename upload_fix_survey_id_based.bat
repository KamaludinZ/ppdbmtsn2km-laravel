@echo off
echo.
echo ================================================================================
echo   UPLOAD: Survey ID-Based Storage System
echo ================================================================================
echo.
echo Perubahan:
echo   - Form menyimpan OPTION ID (bukan VALUE)
echo   - Admin bisa edit LABEL/VALUE tanpa corrupt data
echo   - Protection system: tidak bisa delete option yang sudah disubmit
echo   - Backward compatible dengan data lama
echo.
echo Files to Upload:
echo   1. resources/views/livewire/pendaftar/tes-psikologi.blade.php
echo   2. app/Livewire/Admin/TesPsikologi.php
echo   3. app/Livewire/Panitia/TesPsikologi.php
echo   4. migrate_survey_answers_value_to_id.php
echo.
echo ================================================================================
echo.

pause

echo.
echo [1/4] Uploading Pendaftar Form...
scp resources/views/livewire/pendaftar/tes-psikologi.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/
if errorlevel 1 (
    echo ERROR: Failed to upload Pendaftar Form
    pause
    exit /b 1
)
echo OK

echo.
echo [2/4] Uploading Admin TesPsikologi...
scp app/Livewire/Admin/TesPsikologi.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/
if errorlevel 1 (
    echo ERROR: Failed to upload Admin TesPsikologi
    pause
    exit /b 1
)
echo OK

echo.
echo [3/4] Uploading Panitia TesPsikologi...
scp app/Livewire/Panitia/TesPsikologi.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Panitia/
if errorlevel 1 (
    echo ERROR: Failed to upload Panitia TesPsikologi
    pause
    exit /b 1
)
echo OK

echo.
echo [4/4] Uploading Migration Script...
scp migrate_survey_answers_value_to_id.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/
if errorlevel 1 (
    echo ERROR: Failed to upload Migration Script
    pause
    exit /b 1
)
echo OK

echo.
echo ================================================================================
echo   Upload Complete!
echo ================================================================================
echo.
echo Next Steps (Manual):
echo.
echo 1. SSH to production:
echo    ssh mtsnkot4@103.30.244.77
echo.
echo 2. Navigate to directory:
echo    cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
echo.
echo 3. Clear cache:
echo    php artisan optimize:clear
echo.
echo 4. (Optional) Run migration untuk convert data lama:
echo    php migrate_survey_answers_value_to_id.php
echo.
echo 5. Test form submission:
echo    - Login sebagai pendaftar
echo    - Isi survey lengkap
echo    - Check database: jawaban harus numeric ID (contoh: [1], [2,5])
echo.
echo 6. Test admin display:
echo    - Login sebagai admin
echo    - Buka /admin/tes-psikologi?mainTab=jawaban-survey
echo    - Semua label harus tampil dengan benar
echo.
echo 7. Test edit label (CRITICAL):
echo    - Edit LABEL salah satu option
echo    - Refresh jawaban survey
echo    - Label harus update otomatis
echo.
echo 8. Test protection:
echo    - Coba hapus option yang sudah disubmit
echo    - Harus muncul error dan tidak bisa dihapus
echo.
echo ================================================================================
echo.
echo IMPORTANT: Dokumentasi lengkap ada di:
echo   SURVEY_ID_BASED_STORAGE_COMPLETE.md
echo.
echo ================================================================================
echo.

pause
