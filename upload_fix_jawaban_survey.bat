@echo off
echo ============================================================
echo FIX JAWABAN SURVEY - VERSI 3 (CRITICAL FIX)
echo ============================================================
echo.
echo Masalah: Semua jawaban muncul dengan LABEL yang SAMA
echo          (misal: semua "Sangat Tidak Setuju")
echo          padahal setiap pertanyaan punya opsi berbeda
echo.
echo ROOT CAUSE: Options tidak ter-filter per question dengan benar
echo             Eager loading tidak reliable, ambil options dari question lain
echo.
echo CRITICAL FIX V3:
echo - Direct query dengan WHERE clause per question
echo - 100persen dipastikan options hanya untuk question spesifik
echo - Tidak ada lagi tercampur dengan options dari question lain
echo - 3 metode matching tetap dipertahankan
echo - Enhanced debug logging dengan question_id verification
echo.
echo File yang akan diupload:
echo - app/Livewire/Admin/TesPsikologi.php
echo - app/Livewire/Panitia/TesPsikologi.php
echo.
echo ============================================================
pause

echo.
echo [1/2] Uploading Admin/TesPsikologi.php...
scp app/Livewire/Admin/TesPsikologi.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/
if errorlevel 1 (
    echo ERROR: Gagal upload Admin/TesPsikologi.php
    pause
    exit /b 1
)
echo SUCCESS: Admin/TesPsikologi.php uploaded

echo.
echo [2/2] Uploading Panitia/TesPsikologi.php...
scp app/Livewire/Panitia/TesPsikologi.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Panitia/
if errorlevel 1 (
    echo ERROR: Gagal upload Panitia/TesPsikologi.php
    pause
    exit /b 1
)
echo SUCCESS: Panitia/TesPsikologi.php uploaded

echo.
echo ============================================================
echo LANGKAH SELANJUTNYA DI PRODUCTION:
echo ============================================================
echo.
echo 1. Clear cache:
echo    ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan optimize:clear"
echo.
echo 2. Test di Admin - CEK HASIL:
echo    - Buka /admin/tes-psikologi?mainTab=jawaban-survey
echo.
echo    HASIL YANG DIHARAPKAN (SUCCESS):
echo    - Setiap pertanyaan tampil LABEL yang BERBEDA dan SESUAI
echo    - Misal: "Ya", "Guru", "Setuju", "Sangat Setuju"
echo    - BUKAN semua "Sangat Tidak Setuju"
echo.
echo    JIKA TAMPIL [VALUE NOT FOUND: xxx]:
echo    - Artinya options tidak ditemukan untuk pertanyaan tersebut
echo    - Cek log error untuk detail (langkah 5)
echo    - Perlu create/update options di admin panel
echo.
echo 3. Test di Panitia:
echo    - Login sebagai Panitia Tes Psikologi
echo    - Buka /panitia/tes-psikologi?mainTab=jawaban-survey
echo    - Hasil harus sama dengan di Admin
echo.
echo 4. Test Modal "Lihat":
echo    - Klik tombol "Lihat" di kolom Status Survey
echo    - Modal harus menampilkan LABEL yang benar
echo.
echo 5. CEK LOG ERROR (jika masih ada masalah):
echo    - Jalankan: check_survey_logs.bat
echo    - Atau manual: ssh mtsnkot4@103.30.244.77 "tail -100 storage/logs/laravel.log | grep 'Survey option'"
echo    - Log akan menunjukkan:
echo      * VALUE yang dicari dari jawaban
echo      * VALUE yang tersedia di options
echo      * Jika tidak match, berarti opsi belum dibuat atau VALUE berbeda
echo.
echo ============================================================
echo PERBAIKAN TAMBAHAN:
echo ============================================================
echo - Normalize value comparison (trim whitespace + convert to string)
echo - Debug logging untuk tracking masalah
echo - Flexible comparison menggunakan closure function
echo.
echo ============================================================
echo UPLOAD SELESAI!
echo ============================================================
pause
