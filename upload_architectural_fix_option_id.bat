@echo off
echo ============================================================
echo ARCHITECTURAL FIX - OPTION ID SYSTEM
echo ============================================================
echo.
echo Masalah: Semua jawaban muncul sama / [VALUE NOT FOUND: xxx]
echo Root Cause: VALUE sama antar pertanyaan -^> ambiguity
echo.
echo Solusi: Simpan OPTION ID (unique) bukan VALUE
echo   - Form: value="{{ $option-^>id }}" (bukan value)
echo   - Display: Lookup by ID first (fallback ke VALUE/LABEL)
echo   - Backward compatible dengan data lama
echo.
echo File yang akan diupload: 4 files
echo   1. resources/views/tab_templates/tab-survey.blade.php
echo   2. resources/views/livewire/pendaftar/tes-psikologi.blade.php
echo   3. app/Livewire/Admin/TesPsikologi.php
echo   4. app/Livewire/Panitia/TesPsikologi.php
echo.
echo ============================================================
pause

echo.
echo [1/4] Uploading tab-survey.blade.php...
scp resources/views/tab_templates/tab-survey.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/tab_templates/
if errorlevel 1 (
    echo ERROR: Gagal upload tab-survey.blade.php
    echo.
    echo NOTE: Jika SSH tidak tersedia, upload manual via cPanel:
    echo 1. Login cPanel -^> File Manager
    echo 2. Navigate: /resources/views/tab_templates/
    echo 3. Upload file: tab-survey.blade.php
    echo.
    pause
    goto manual_upload
)
echo SUCCESS: tab-survey.blade.php uploaded

echo.
echo [2/4] Uploading tes-psikologi.blade.php (pendaftar form)...
scp resources/views/livewire/pendaftar/tes-psikologi.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/
if errorlevel 1 (
    echo ERROR: Gagal upload tes-psikologi.blade.php
    echo Upload manual via cPanel
    pause
    goto manual_upload
)
echo SUCCESS: tes-psikologi.blade.php uploaded

echo.
echo [3/4] Uploading Admin/TesPsikologi.php...
scp app/Livewire/Admin/TesPsikologi.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/
if errorlevel 1 (
    echo ERROR: Gagal upload Admin/TesPsikologi.php
    echo Upload manual via cPanel
    pause
    goto manual_upload
)
echo SUCCESS: Admin/TesPsikologi.php uploaded

echo.
echo [4/4] Uploading Panitia/TesPsikologi.php...
scp app/Livewire/Panitia/TesPsikologi.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Panitia/
if errorlevel 1 (
    echo ERROR: Gagal upload Panitia/TesPsikologi.php
    echo Upload manual via cPanel
    pause
    goto manual_upload
)
echo SUCCESS: Panitia/TesPsikologi.php uploaded

echo.
echo ============================================================
echo UPLOAD SELESAI VIA SSH!
echo ============================================================
echo.
goto clear_cache

:manual_upload
echo.
echo ============================================================
echo UPLOAD MANUAL VIA cPanel
echo ============================================================
echo.
echo SSH tidak tersedia. Silakan upload manual:
echo.
echo 1. Login cPanel -^> File Manager
echo 2. Upload 4 files ke:
echo    - /resources/views/tab_templates/tab-survey.blade.php
echo    - /resources/views/livewire/pendaftar/tes-psikologi.blade.php
echo    - /app/Livewire/Admin/TesPsikologi.php
echo    - /app/Livewire/Panitia/TesPsikologi.php
echo.
echo Setelah upload selesai, tekan tombol apapun untuk lanjut clear cache...
pause
goto clear_cache

:clear_cache
echo.
echo ============================================================
echo CLEAR CACHE PRODUCTION
echo ============================================================
echo.
echo Jalankan commands berikut di cPanel Terminal:
echo.
echo cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
echo php artisan optimize:clear
echo php artisan view:clear
echo php artisan config:clear
echo php artisan cache:clear
echo.

echo Mencoba clear cache via SSH...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan optimize:clear && php artisan view:clear && php artisan config:clear"
if errorlevel 1 (
    echo.
    echo WARNING: SSH tidak tersedia.
    echo Silakan clear cache manual via cPanel Terminal dengan commands di atas.
    echo.
    pause
) else (
    echo.
    echo SUCCESS: Cache cleared!
)

echo.
echo ============================================================
echo TESTING
echo ============================================================
echo.
echo 1. TEST DATA BARU:
echo    - Logout dari Admin/Panitia
echo    - Login sebagai Pendaftar
echo    - Submit survey baru
echo    - Verifikasi di Admin/Panitia
echo.
echo 2. EXPECTED RESULT:
echo    Setiap pertanyaan tampil LABEL yang BERBEDA:
echo    | Pertanyaan 2 ^| Pertanyaan 3     ^| Pertanyaan 5        ^|
echo    ^|-------------^|-----------------^|--------------------^|
echo    ^| Ya          ^| Guru             ^| Setuju              ^|
echo    ^| Ya          ^| Sosial Media     ^| Sangat Tidak Setuju ^|
echo    ^| Ya          ^| Kepala Sekolah   ^| Sangat Setuju       ^|
echo.
echo    BUKAN semua "Sangat Tidak Setuju"!
echo.
echo 3. VERIFY DATA TERSIMPAN SEBAGAI OPTION ID:
echo    cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
echo    php artisan tinker
echo    $answer = App\Models\SurveyAnswer::latest()-^>first();
echo    dd($answer-^>jawaban);  // Harus numeric (Option ID)
echo.
echo 4. TEST BACKWARD COMPATIBILITY:
echo    - Data lama harus masih bisa dibaca dengan benar
echo    - Tidak ada [VALUE NOT FOUND: xxx]
echo.
echo ============================================================
echo TROUBLESHOOTING
echo ============================================================
echo.
echo Jika masih muncul [VALUE NOT FOUND: xxx]:
echo.
echo 1. Verifikasi file terupload:
echo    - Buka file production via cPanel
echo    - Cari comment: ARCHITECTURAL FIX
echo    - Cari code: value="{{ $option-^>id }}"
echo.
echo 2. Clear cache lagi:
echo    php artisan optimize:clear
echo    php artisan view:clear
echo.
echo 3. Hard refresh browser:
echo    Ctrl+F5 atau Cmd+Shift+R
echo    Atau gunakan Incognito mode
echo.
echo 4. Check log error:
echo    tail -50 storage/logs/laravel.log ^| grep Survey
echo.
echo ============================================================
echo DOKUMENTASI LENGKAP
echo ============================================================
echo.
echo Lihat file:
echo - ARCHITECTURAL_FIX_OPTION_ID_SYSTEM.md (comprehensive)
echo - QUICK_GUIDE_OPTION_ID_FIX.md (quick reference)
echo.
echo ============================================================
echo UPLOAD DAN SETUP SELESAI!
echo ============================================================
echo.
echo Silakan test sesuai instruksi di atas.
echo Good luck! 🎯
echo.
pause
