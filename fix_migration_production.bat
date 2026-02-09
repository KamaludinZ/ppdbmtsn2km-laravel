@echo off
echo ============================================
echo FIX MIGRATION DI PRODUCTION
echo ============================================
echo.
echo LANGKAH:
echo 1. Mark migration lama sebagai completed (skip)
echo 2. Upload migration baru (fixed version)
echo 3. Jalankan migration baru
echo 4. Verify indexes created
echo.
pause

echo.
echo ========================================
echo STEP 1: MARK MIGRATION LAMA SEBAGAI COMPLETED
echo ========================================
echo Marking old migration as completed (akan di-skip)...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && psql -U mtsnkot4 -d mtsnkot4_ppdb -c \"INSERT INTO migrations (migration, batch) VALUES ('2026_02_05_204151_add_indexes_and_constraints_to_survey_tables', (SELECT COALESCE(MAX(batch), 0) + 1 FROM migrations)) ON CONFLICT DO NOTHING;\""
if %errorlevel% neq 0 (
    echo WARNING: Gagal mark migration lama
    echo Mungkin sudah di-mark sebelumnya
) else (
    echo BERHASIL mark migration lama sebagai completed
)

echo.
echo ========================================
echo STEP 2: UPLOAD MIGRATION BARU
echo ========================================
scp database/migrations/2026_02_07_220000_add_indexes_and_constraints_to_survey_tables_fixed.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/database/migrations/
if %errorlevel% neq 0 (
    echo GAGAL upload migration baru
    pause
    exit /b 1
)
echo BERHASIL upload migration baru

echo.
echo ========================================
echo STEP 3: JALANKAN MIGRATION BARU
echo ========================================
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan migrate --path=database/migrations/2026_02_07_220000_add_indexes_and_constraints_to_survey_tables_fixed.php"
if %errorlevel% neq 0 (
    echo WARNING: Migration gagal atau sudah pernah dijalankan
    pause
) else (
    echo BERHASIL run migration baru
)

echo.
echo ========================================
echo STEP 4: VERIFY MIGRATION STATUS
echo ========================================
echo Checking migration status...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan migrate:status | grep survey"

echo.
echo ========================================
echo STEP 5: VERIFY INDEXES CREATED
echo ========================================
echo Checking indexes in database...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && psql -U mtsnkot4 -d mtsnkot4_ppdb -c \"SELECT tablename, indexname FROM pg_indexes WHERE indexname LIKE 'idx_survey%%' ORDER BY tablename, indexname;\""

echo.
echo ========================================
echo STEP 6: CHECK UNIQUE CONSTRAINT
echo ========================================
echo Checking unique constraint...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && psql -U mtsnkot4 -d mtsnkot4_ppdb -c \"SELECT conname, contype FROM pg_constraint WHERE conname = 'unique_pendaftar_question';\""

echo.
echo ============================================
echo SELESAI!
echo ============================================
echo.
echo HASIL EXPECTED:
echo.
echo [MIGRATION STATUS]
echo - 2026_02_05_204151_add_indexes_and_constraints_to_survey_tables: Ran (skipped)
echo - 2026_02_07_220000_add_indexes_and_constraints_to_survey_tables_fixed: Ran
echo.
echo [INDEXES CREATED]
echo - survey_question_options: idx_survey_options_question_urutan
echo - survey_answers: idx_survey_answers_pendaftar_question
echo - survey_questions: idx_survey_questions_context_aktif_urutan
echo.
echo [CONSTRAINT CREATED]
echo - survey_answers: unique_pendaftar_question (unique constraint)
echo.
echo NEXT STEPS:
echo 1. Test upload bukti tes psikologi di /pendaftar/tes-psikologi
echo 2. Test edit catatan di /admin/tes-psikologi?mainTab=data
echo 3. Verify no more migration errors
echo.
pause
