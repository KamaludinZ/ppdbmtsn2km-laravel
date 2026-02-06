@echo off
echo ========================================
echo FIX ERROR 500 - /admin/survey
echo ========================================
echo.
echo FILE YANG AKAN DI-UPLOAD:
echo 1. app/Traits/WithSorting.php (Trait untuk sorting)
echo 2. app/Models/SurveyQuestion.php (Model dengan scope umum)
echo 3. app/Models/SurveyQuestionOption.php (Model opsi)
echo 4. app/Models/SurveyAnswer.php (Model jawaban)
echo 5. app/Livewire/Admin/SurveyManagement.php (Controller)
echo 6. resources/views/livewire/admin/survey-management.blade.php (View)
echo 7. resources/views/livewire/admin/partials/survey-responses.blade.php (View partial - FIX CRITICAL)
echo.
echo TOTAL: 7 files
echo.
pause

echo.
echo ========================================
echo STEP 1: UPLOAD TRAIT (1 file)
echo ========================================
echo.

scp app/Traits/WithSorting.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Traits/

echo.
echo ========================================
echo STEP 2: UPLOAD MODELS (3 files)
echo ========================================
echo.

scp app/Models/SurveyQuestion.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Models/
scp app/Models/SurveyQuestionOption.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Models/
scp app/Models/SurveyAnswer.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Models/

echo.
echo ========================================
echo STEP 3: UPLOAD CONTROLLER (1 file)
echo ========================================
echo.

scp app/Livewire/Admin/SurveyManagement.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/

echo.
echo ========================================
echo STEP 4: UPLOAD VIEWS (2 files)
echo ========================================
echo.

scp resources/views/livewire/admin/survey-management.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/

echo.
echo [CRITICAL FIX: survey-responses.blade.php - fixes "Call to undefined relationship [option]"]
scp resources/views/livewire/admin/partials/survey-responses.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/partials/

echo.
echo ========================================
echo STEP 5: SET PERMISSIONS ^& CLEAR CACHE
echo ========================================
echo.

ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && chmod 644 app/Traits/WithSorting.php && chmod 644 app/Models/SurveyQuestion.php && chmod 644 app/Models/SurveyQuestionOption.php && chmod 644 app/Models/SurveyAnswer.php && chmod 644 app/Livewire/Admin/SurveyManagement.php && chmod 644 resources/views/livewire/admin/survey-management.blade.php && chmod 644 resources/views/livewire/admin/partials/survey-responses.blade.php"

echo.
echo [Clearing cache...]
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan optimize:clear && php artisan view:clear && php artisan config:clear"

echo.
echo ========================================
echo UPLOAD SELESAI!
echo ========================================
echo.
echo TESTING CHECKLIST:
echo.
echo [ ] 1. Buka /admin/survey di browser
echo [ ] 2. Halaman tidak error 500
echo [ ] 3. Stats cards muncul (4 kartu):
echo      - Total Pertanyaan
echo      - Pertanyaan Aktif
echo      - Total Opsi
echo      - Total Responden
echo [ ] 4. Tab "Settings" dan "Responses" muncul
echo [ ] 5. Tabel pertanyaan survey muncul
echo [ ] 6. Bisa tambah pertanyaan baru
echo [ ] 7. Bisa edit pertanyaan
echo [ ] 8. Bisa tambah opsi jawaban
echo.
echo ========================================
echo DETAIL PERBAIKAN:
echo ========================================
echo.
echo ROOT CAUSE:
echo File-file berikut belum ada di production:
echo - Trait WithSorting (untuk sorting data)
echo - Model dengan scope baru (umum, aktif, ordered)
echo - View yang updated
echo.
echo SOLUTION:
echo Upload semua dependency yang diperlukan oleh SurveyManagement
echo.
echo FILES UPLOADED:
echo 1. WithSorting.php - Trait untuk sorting dengan method:
echo    - sortBy(field)
echo    - applySorting(query)
echo    - getSortIcon(field)
echo.
echo 2. SurveyQuestion.php - Model dengan scope:
echo    - scopeAktif() - Filter pertanyaan aktif
echo    - scopeOrdered() - Sort by urutan
echo    - scopeUmum() - Filter context umum
echo    - scopeTesPsikologi() - Filter context tes psikologi
echo.
echo 3. SurveyQuestionOption.php - Model opsi jawaban
echo.
echo 4. SurveyAnswer.php - Model jawaban pendaftar
echo.
echo 5. SurveyManagement.php - Controller dengan:
echo    - Filter by context umum
echo    - Stats calculation
echo    - CRUD operations
echo.
echo 6. survey-management.blade.php - View dengan:
echo    - Stats cards display
echo    - Question management table
echo    - Option management modal
echo    - Response viewing
echo.
echo ========================================
echo CATATAN PENTING:
echo ========================================
echo.
echo 1. Migration sudah jalan sebelumnya (tabel sudah ada):
echo    - survey_questions
echo    - survey_question_options
echo    - survey_answers
echo.
echo 2. Jika masih error 500, cek:
echo    - tail -f storage/logs/laravel.log
echo    - Cek apakah tabel survey_* ada di database
echo    - Cek column 'context' ada di survey_questions
echo.
echo 3. Route sudah ada:
echo    Route::get('/survey', SurveyManagement::class)
echo.
echo ========================================
pause
