@echo off
echo ============================================================
echo DEBUG SURVEY DATA - CEK PERTANYAAN DAN JAWABAN
echo ============================================================
echo.
echo Script ini akan menampilkan:
echo 1. Daftar pertanyaan survey tes psikologi
echo 2. Opsi untuk setiap pertanyaan
echo 3. Sample jawaban dari pendaftar
echo.
pause

echo.
echo ============================================================
echo 1. PERTANYAAN SURVEY TES PSIKOLOGI
echo ============================================================
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && psql -U mtsnkot4 -d mtsnkot4_ppdb -c \"SELECT id, pertanyaan, tipe_input, urutan FROM survey_questions WHERE context = 'tes_psikologi' AND aktif = true ORDER BY urutan;\""

echo.
echo ============================================================
echo 2. OPSI UNTUK SETIAP PERTANYAAN
echo ============================================================
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && psql -U mtsnkot4 -d mtsnkot4_ppdb -c \"SELECT sq.id as question_id, sq.pertanyaan, sqo.label, sqo.value, sqo.urutan FROM survey_questions sq LEFT JOIN survey_question_options sqo ON sq.id = sqo.survey_question_id WHERE sq.context = 'tes_psikologi' AND sq.aktif = true ORDER BY sq.urutan, sqo.urutan;\""

echo.
echo ============================================================
echo 3. SAMPLE JAWABAN (5 pendaftar terakhir)
echo ============================================================
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && psql -U mtsnkot4 -d mtsnkot4_ppdb -c \"SELECT sa.pendaftar_id, p.nama_lengkap, sa.survey_question_id, sq.pertanyaan, sa.jawaban FROM survey_answers sa JOIN pendaftar p ON sa.pendaftar_id = p.id JOIN survey_questions sq ON sa.survey_question_id = sq.id WHERE sq.context = 'tes_psikologi' ORDER BY sa.created_at DESC LIMIT 20;\""

echo.
echo ============================================================
echo SELESAI!
echo ============================================================
pause
