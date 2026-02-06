@echo off
echo.
echo ================================================================================
echo   UPLOAD: Complete Survey System - FINAL with Strict Validation
echo ================================================================================
echo.
echo Complete Implementation:
echo   1. Survey Tes Psikologi - Form simpan ID
echo   2. Survey Umum (Pendaftaran) - Form simpan ID
echo   3. Admin/Panitia Display - Query by ID dengan fallback VALUE
echo   4. SurveyAnswer Model - Backward compatible display
echo   5. Admin Survey Management - Individual EDIT/DELETE option buttons
echo   6. Protection System - Prevent delete option yang disubmit
echo   7. STRICT VALIDATION - Disable tombol jika pertanyaan wajib belum dijawab
echo   8. Migration Script - Convert data lama VALUE ke ID
echo.
echo Benefits:
echo   - Admin bisa edit LABEL/VALUE tanpa corrupt data
echo   - Individual option edit - tidak perlu edit seluruh pertanyaan
echo   - Protection system: tidak bisa delete option yang sudah disubmit
echo   - STRICT: Tombol disabled sampai pertanyaan wajib dijawab
echo   - Visual feedback yang jelas untuk pertanyaan wajib
echo   - Backward compatible dengan data lama
echo.
echo Files to Upload (10 files):
echo   1. resources/views/livewire/pendaftar/tes-psikologi.blade.php (STRICT VALIDATION)
echo   2. app/Livewire/Pendaftar/TesPsikologi.php (COMPUTED PROPERTIES)
echo   3. resources/views/tab_templates/tab-survey.blade.php
echo   4. app/Livewire/Pendaftar/Formulir.php (checkbox fix)
echo   5. app/Livewire/Admin/TesPsikologi.php (with option edit/delete)
echo   6. resources/views/livewire/admin/partials/tes-psikologi-survey.blade.php
echo   7. app/Livewire/Panitia/TesPsikologi.php
echo   8. app/Models/SurveyAnswer.php
echo   9. migrate_survey_answers_value_to_id.php
echo.
echo ================================================================================
echo.

pause

echo.
echo [1/9] Uploading Pendaftar Tes Psikologi Form (with strict validation)...
scp resources/views/livewire/pendaftar/tes-psikologi.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/
if errorlevel 1 (
    echo ERROR: Failed to upload Pendaftar Tes Psikologi Form
    pause
    exit /b 1
)
echo OK

echo.
echo [2/9] Uploading Pendaftar TesPsikologi Component (with computed properties)...
scp app/Livewire/Pendaftar/TesPsikologi.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Pendaftar/
if errorlevel 1 (
    echo ERROR: Failed to upload Pendaftar TesPsikologi Component
    pause
    exit /b 1
)
echo OK

echo.
echo [3/9] Uploading Survey Umum Tab Template...
scp resources/views/tab_templates/tab-survey.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/tab_templates/
if errorlevel 1 (
    echo ERROR: Failed to upload Survey Umum Tab
    pause
    exit /b 1
)
echo OK

echo.
echo [4/9] Uploading Pendaftar Formulir (checkbox fix)...
scp app/Livewire/Pendaftar/Formulir.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Pendaftar/
if errorlevel 1 (
    echo ERROR: Failed to upload Formulir
    pause
    exit /b 1
)
echo OK

echo.
echo [5/9] Uploading Admin TesPsikologi (with option edit/delete)...
scp app/Livewire/Admin/TesPsikologi.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/
if errorlevel 1 (
    echo ERROR: Failed to upload Admin TesPsikologi
    pause
    exit /b 1
)
echo OK

echo.
echo [6/9] Uploading Admin Survey Management Blade...
scp resources/views/livewire/admin/partials/tes-psikologi-survey.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/partials/
if errorlevel 1 (
    echo ERROR: Failed to upload Admin Survey Blade
    pause
    exit /b 1
)
echo OK

echo.
echo [7/9] Uploading Panitia TesPsikologi...
scp app/Livewire/Panitia/TesPsikologi.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Panitia/
if errorlevel 1 (
    echo ERROR: Failed to upload Panitia TesPsikologi
    pause
    exit /b 1
)
echo OK

echo.
echo [8/9] Uploading SurveyAnswer Model...
scp app/Models/SurveyAnswer.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Models/
if errorlevel 1 (
    echo ERROR: Failed to upload SurveyAnswer Model
    pause
    exit /b 1
)
echo OK

echo.
echo [9/9] Uploading Migration Script...
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
echo    php artisan view:clear
echo.
echo 4. (Optional) Run migration untuk convert data lama:
echo    php migrate_survey_answers_value_to_id.php
echo.
echo ================================================================================
echo   TESTING CHECKLIST
echo ================================================================================
echo.
echo TEST STRICT VALIDATION TES PSIKOLOGI:
echo   [ ] Buka /pendaftar/tes-psikologi sebagai pendaftar
echo   [ ] Lihat pertanyaan wajib (ada tanda * merah)
echo   [ ] Tombol "Selanjutnya" DISABLED jika belum dijawab
echo   [ ] Peringatan merah muncul: "Pertanyaan ini wajib diisi!"
echo   [ ] Jawab pertanyaan - tombol jadi ENABLED
echo   [ ] Klik "Selanjutnya" - berhasil ke pertanyaan berikutnya
echo   [ ] Di pertanyaan terakhir - tombol "Selesai" DISABLED jika ada wajib yang belum
echo   [ ] Lengkapi semua wajib - tombol "Selesai" jadi ENABLED
echo.
echo TEST ADMIN SURVEY MANAGEMENT:
echo   [ ] Hover option - tampil EDIT/DELETE button
echo   [ ] Click EDIT option - modal terbuka dengan data option
echo   [ ] Edit LABEL option - display update otomatis
echo   [ ] Click "Tambah Opsi" - modal terbuka dengan form kosong
echo   [ ] Tidak bisa delete option yang sudah disubmit
echo   [ ] Click tombol Edit pertanyaan - form edit pertanyaan tampil (tidak hilang)
echo.
echo TEST SURVEY UMUM (Pendaftaran):
echo   [ ] Tab Survey - Form simpan numeric ID
echo   [ ] Admin lihat jawaban - tampil LABEL dengan benar
echo   [ ] Edit LABEL di /admin/survey-management
echo   [ ] Tab Survey pendaftar - display update otomatis
echo.
echo ================================================================================
echo.
echo IMPORTANT:
echo   - Test STRICT VALIDATION lokal terlebih dahulu!
echo   - Pastikan tombol disabled bekerja dengan benar
echo   - Backup database sebelum run migration script
echo.
echo ================================================================================
echo.

pause
