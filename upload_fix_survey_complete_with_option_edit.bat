@echo off
echo.
echo ================================================================================
echo   UPLOAD: Complete Survey System with Individual Option Edit
echo ================================================================================
echo.
echo Complete Implementation:
echo   1. Survey Tes Psikologi - Form simpan ID
echo   2. Survey Umum (Pendaftaran) - Form simpan ID
echo   3. Admin/Panitia Display - Query by ID dengan fallback VALUE
echo   4. SurveyAnswer Model - Backward compatible display
echo   5. Admin Survey Management - Individual EDIT/DELETE option buttons
echo   6. Protection System - Prevent delete option yang disubmit
echo   7. Migration Script - Convert data lama VALUE ke ID
echo.
echo Benefits:
echo   - Admin bisa edit LABEL/VALUE tanpa corrupt data
echo   - Individual option edit - tidak perlu edit seluruh pertanyaan
echo   - Protection system: tidak bisa delete option yang sudah disubmit
echo   - Backward compatible dengan data lama
echo   - Tidak ada lagi [VALUE NOT FOUND] untuk data baru
echo.
echo Files to Upload (8 files):
echo   1. resources/views/livewire/pendaftar/tes-psikologi.blade.php
echo   2. resources/views/tab_templates/tab-survey.blade.php
echo   3. app/Livewire/Pendaftar/Formulir.php (checkbox fix)
echo   4. app/Livewire/Admin/TesPsikologi.php (with option edit/delete)
echo   5. resources/views/livewire/admin/partials/tes-psikologi-survey.blade.php
echo   6. app/Livewire/Panitia/TesPsikologi.php
echo   7. app/Models/SurveyAnswer.php
echo   8. migrate_survey_answers_value_to_id.php
echo.
echo ================================================================================
echo.

pause

echo.
echo [1/8] Uploading Pendaftar Tes Psikologi Form...
scp resources/views/livewire/pendaftar/tes-psikologi.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/
if errorlevel 1 (
    echo ERROR: Failed to upload Pendaftar Tes Psikologi Form
    pause
    exit /b 1
)
echo OK

echo.
echo [2/8] Uploading Survey Umum Tab Template...
scp resources/views/tab_templates/tab-survey.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/tab_templates/
if errorlevel 1 (
    echo ERROR: Failed to upload Survey Umum Tab
    pause
    exit /b 1
)
echo OK

echo.
echo [3/8] Uploading Pendaftar Formulir (checkbox fix)...
scp app/Livewire/Pendaftar/Formulir.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Pendaftar/
if errorlevel 1 (
    echo ERROR: Failed to upload Formulir
    pause
    exit /b 1
)
echo OK

echo.
echo [4/8] Uploading Admin TesPsikologi (with option edit/delete)...
scp app/Livewire/Admin/TesPsikologi.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/
if errorlevel 1 (
    echo ERROR: Failed to upload Admin TesPsikologi
    pause
    exit /b 1
)
echo OK

echo.
echo [5/8] Uploading Admin Survey Management Blade...
scp resources/views/livewire/admin/partials/tes-psikologi-survey.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/partials/
if errorlevel 1 (
    echo ERROR: Failed to upload Admin Survey Blade
    pause
    exit /b 1
)
echo OK

echo.
echo [6/8] Uploading Panitia TesPsikologi...
scp app/Livewire/Panitia/TesPsikologi.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Panitia/
if errorlevel 1 (
    echo ERROR: Failed to upload Panitia TesPsikologi
    pause
    exit /b 1
)
echo OK

echo.
echo [7/8] Uploading SurveyAnswer Model...
scp app/Models/SurveyAnswer.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Models/
if errorlevel 1 (
    echo ERROR: Failed to upload SurveyAnswer Model
    pause
    exit /b 1
)
echo OK

echo.
echo [8/8] Uploading Migration Script...
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
echo ================================================================================
echo   TESTING CHECKLIST
echo ================================================================================
echo.
echo TEST SURVEY TES PSIKOLOGI:
echo   [ ] Form simpan numeric ID: [1], [2,5]
echo   [ ] Display tampil LABEL dengan benar
echo   [ ] Admin hover option - tampil EDIT/DELETE button
echo   [ ] Click EDIT option - modal terbuka dengan data option
echo   [ ] Edit LABEL option - display update otomatis
echo   [ ] Tidak bisa delete option yang sudah disubmit
echo.
echo TEST SURVEY UMUM (Pendaftaran):
echo   [ ] Tab Survey - Form simpan numeric ID
echo   [ ] Admin lihat jawaban - tampil LABEL dengan benar
echo   [ ] Edit LABEL di /admin/survey-management
echo   [ ] Tab Survey pendaftar - display update otomatis
echo   [ ] Tidak bisa delete option yang sudah disubmit
echo.
echo ================================================================================
echo.
echo IMPORTANT:
echo   - Dokumentasi lengkap: SURVEY_ID_BOTH_SYSTEMS_COMPLETE.md
echo   - Test lokal terlebih dahulu sebelum production!
echo   - Backup database sebelum run migration script
echo.
echo ================================================================================
echo.

pause
