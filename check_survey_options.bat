@echo off
echo ============================================================
echo CEK STRUKTUR OPTIONS - APAKAH VALUE DUPLIKAT?
echo ============================================================
echo.
echo Script ini akan menampilkan semua pertanyaan dan options
echo untuk mengecek apakah VALUE duplikat antar pertanyaan
echo.
pause

echo.
echo ============================================================
echo PERTANYAAN DAN OPTIONS (Grouped by Question)
echo ============================================================
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && psql -U mtsnkot4 -d mtsnkot4_ppdb -c \"SELECT sq.id as q_id, sq.urutan, SUBSTRING(sq.pertanyaan, 1, 50) as pertanyaan, sqo.id as opt_id, sqo.label, sqo.value, sqo.urutan as opt_urutan FROM survey_questions sq LEFT JOIN survey_question_options sqo ON sq.id = sqo.survey_question_id WHERE sq.context = 'tes_psikologi' AND sq.aktif = true ORDER BY sq.urutan, sqo.urutan;\""

echo.
echo ============================================================
echo CEK VALUE DUPLIKAT
echo ============================================================
echo Perhatikan apakah VALUE yang sama digunakan di banyak pertanyaan:
echo Contoh MASALAH:
echo   Q2: value = "1", "2", "3"
echo   Q3: value = "1", "2", "3", "4"  ^<-- VALUE SAMA!
echo   Q4: value = "1", "2", "3", "4"  ^<-- VALUE SAMA!
echo.
echo Jika VALUE sama, code bisa salah mengambil option dari Q2
echo ketika seharusnya ambil dari Q3 atau Q4
echo.
pause
