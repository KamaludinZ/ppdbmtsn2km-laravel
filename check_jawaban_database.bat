@echo off
echo ============================================================
echo CEK JAWABAN DI DATABASE - APA YANG TERSIMPAN?
echo ============================================================
echo.
echo Script ini akan menampilkan:
echo 1. Sample jawaban yang tersimpan di database
echo 2. Options yang tersedia untuk pertanyaan terkait
echo 3. Membandingkan VALUE di jawaban vs VALUE di options
echo.
pause

echo.
echo ============================================================
echo 1. SAMPLE JAWABAN DARI DATABASE (10 terakhir)
echo ============================================================
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && psql -U mtsnkot4 -d mtsnkot4_ppdb -c \"SELECT sa.id, sa.pendaftar_id, sa.survey_question_id, SUBSTRING(sq.pertanyaan, 1, 40) as pertanyaan, sa.jawaban, sa.created_at FROM survey_answers sa JOIN survey_questions sq ON sa.survey_question_id = sq.id WHERE sq.context = 'tes_psikologi' ORDER BY sa.created_at DESC LIMIT 10;\""

echo.
echo ============================================================
echo 2. OPTIONS UNTUK PERTANYAAN TERSEBUT
echo ============================================================
echo Kita akan cek pertanyaan ID 5 dan 6 sebagai contoh:
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && psql -U mtsnkot4 -d mtsnkot4_ppdb -c \"SELECT sqo.survey_question_id, sqo.label, sqo.value FROM survey_question_options sqo WHERE sqo.survey_question_id IN (SELECT id FROM survey_questions WHERE context = 'tes_psikologi' AND urutan IN (5, 6)) ORDER BY sqo.survey_question_id, sqo.urutan;\""

echo.
echo ============================================================
echo ANALISIS:
echo ============================================================
echo.
echo Perhatikan kolom 'jawaban' di hasil query pertama:
echo.
echo Jika jawaban berisi:
echo   - ANGKA (1, 2, 3, 4) atau KODE (sangat_setuju, guru, dll)
echo     =^> INI ADALAH VALUE (BENAR)
echo.
echo   - TEXT LENGKAP ("Sangat Tidak Setuju", "Sangat Setuju", dll)
echo     =^> INI ADALAH LABEL (SALAH - data lama)
echo.
echo Bandingkan dengan kolom 'value' di hasil query kedua:
echo   - Jika VALUE di options = angka (1, 2, 3, 4)
echo     dan jawaban = "Sangat Tidak Setuju"
echo     =^> MISMATCH! Perlu fix data atau options
echo.
echo   - Jika VALUE di options = "Sangat Tidak Setuju"
echo     dan jawaban = "Sangat Tidak Setuju"
echo     =^> MATCH! Method 3/4 seharusnya bisa handle
echo.
echo ============================================================
pause
