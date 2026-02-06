@echo off
echo ============================================================
echo CEK LOG ERROR - SURVEY JAWABAN
echo ============================================================
echo.
echo Script ini akan menampilkan log error terkait survey
echo untuk debugging masalah "jawaban tidak sesuai opsi"
echo.
pause

echo.
echo ============================================================
echo LOG ERROR TERBARU (30 baris terakhir dengan keyword "Survey")
echo ============================================================
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && tail -100 storage/logs/laravel.log | grep -A 5 'Survey option not found'"

echo.
echo ============================================================
echo Jika muncul log "Survey option not found", perhatikan:
echo - question_id: ID pertanyaan
echo - pertanyaan: Teks pertanyaan
echo - looking_for_value: VALUE yang dicari (dari jawaban pendaftar)
echo - available_options: Daftar VALUE yang ada di options
echo.
echo Jika looking_for_value TIDAK ADA di available_options:
echo   Artinya opsi untuk pertanyaan ini belum dibuat atau
echo   VALUE-nya berbeda dengan yang disimpan di jawaban
echo.
echo ============================================================
pause
