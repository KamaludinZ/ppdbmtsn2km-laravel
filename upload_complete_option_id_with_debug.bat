@echo off
echo ============================================================
echo UPLOAD COMPLETE: OPTION ID FIX + DEBUG DISPLAY
echo ============================================================
echo.
echo File yang akan diupload: 6 files
echo.
echo ARCHITECTURAL FIX (Option ID System):
echo   1. resources/views/tab_templates/tab-survey.blade.php
echo   2. resources/views/livewire/pendaftar/tes-psikologi.blade.php
echo   3. app/Livewire/Admin/TesPsikologi.php
echo   4. app/Livewire/Panitia/TesPsikologi.php
echo.
echo DEBUG DISPLAY:
echo   5. resources/views/livewire/admin/partials/tes-psikologi-survey.blade.php
echo   6. resources/views/livewire/admin/partials/tes-psikologi-jawaban-survey.blade.php
echo.
echo DEBUG DISPLAY akan menampilkan:
echo   - Question ID (Q1, Q2, Q5, Q6)
echo   - Option ID (ID:10, ID:47, ID:120)
echo   - RAW data dari database
echo   - Indicator: ✓ ID (data baru) atau ⚠ VALUE/LABEL (data lama)
echo.
echo ============================================================
pause

echo.
echo ============================================================
echo ARCHITECTURAL FIX FILES
echo ============================================================
echo.

echo [1/6] Uploading tab-survey.blade.php...
scp resources/views/tab_templates/tab-survey.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/tab_templates/
if errorlevel 1 (
    echo ERROR: Gagal upload - SSH not available
    goto manual_upload
)
echo SUCCESS: tab-survey.blade.php uploaded

echo.
echo [2/6] Uploading tes-psikologi.blade.php (pendaftar)...
scp resources/views/livewire/pendaftar/tes-psikologi.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/
if errorlevel 1 (
    echo ERROR: Gagal upload - SSH not available
    goto manual_upload
)
echo SUCCESS: tes-psikologi.blade.php uploaded

echo.
echo [3/6] Uploading Admin/TesPsikologi.php...
scp app/Livewire/Admin/TesPsikologi.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/
if errorlevel 1 (
    echo ERROR: Gagal upload - SSH not available
    goto manual_upload
)
echo SUCCESS: Admin/TesPsikologi.php uploaded

echo.
echo [4/6] Uploading Panitia/TesPsikologi.php...
scp app/Livewire/Panitia/TesPsikologi.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Panitia/
if errorlevel 1 (
    echo ERROR: Gagal upload - SSH not available
    goto manual_upload
)
echo SUCCESS: Panitia/TesPsikologi.php uploaded

echo.
echo ============================================================
echo DEBUG DISPLAY FILES
echo ============================================================
echo.

echo [5/6] Uploading tes-psikologi-survey.blade.php (debug view)...
scp resources/views/livewire/admin/partials/tes-psikologi-survey.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/partials/
if errorlevel 1 (
    echo ERROR: Gagal upload - SSH not available
    goto manual_upload
)
echo SUCCESS: tes-psikologi-survey.blade.php uploaded

echo.
echo [6/6] Uploading tes-psikologi-jawaban-survey.blade.php (debug view)...
scp resources/views/livewire/admin/partials/tes-psikologi-jawaban-survey.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/partials/
if errorlevel 1 (
    echo ERROR: Gagal upload - SSH not available
    goto manual_upload
)
echo SUCCESS: tes-psikologi-jawaban-survey.blade.php uploaded

echo.
echo ============================================================
echo UPLOAD SELESAI VIA SSH!
echo ============================================================
goto clear_cache

:manual_upload
echo.
echo ============================================================
echo UPLOAD MANUAL VIA cPanel
echo ============================================================
echo.
echo SSH tidak tersedia. Silakan upload manual 6 files:
echo.
echo ARCHITECTURAL FIX:
echo 1. /resources/views/tab_templates/tab-survey.blade.php
echo 2. /resources/views/livewire/pendaftar/tes-psikologi.blade.php
echo 3. /app/Livewire/Admin/TesPsikologi.php
echo 4. /app/Livewire/Panitia/TesPsikologi.php
echo.
echo DEBUG DISPLAY:
echo 5. /resources/views/livewire/admin/partials/tes-psikologi-survey.blade.php
echo 6. /resources/views/livewire/admin/partials/tes-psikologi-jawaban-survey.blade.php
echo.
echo Setelah upload, tekan tombol apapun untuk lanjut...
pause

:clear_cache
echo.
echo ============================================================
echo CLEAR CACHE PRODUCTION
echo ============================================================
echo.

echo Mencoba clear cache via SSH...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan optimize:clear && php artisan view:clear && php artisan config:clear"
if errorlevel 1 (
    echo.
    echo WARNING: SSH tidak tersedia.
    echo.
    echo Silakan clear cache manual via cPanel Terminal:
    echo.
    echo cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
    echo php artisan optimize:clear
    echo php artisan view:clear
    echo php artisan config:clear
    echo.
    pause
) else (
    echo.
    echo SUCCESS: Cache cleared!
)

echo.
echo ============================================================
echo TESTING DAN VERIFIKASI
echo ============================================================
echo.
echo STEP 1: CEK TAB SURVEY
echo ----------------------
echo 1. Login sebagai Admin
echo 2. Buka: /admin/tes-psikologi?mainTab=survey
echo 3. Hard Refresh: Ctrl+F5
echo.
echo EXPECTED:
echo - Setiap pertanyaan tampil dengan Question ID (Q5, Q6, Q7, dll)
echo - Setiap option tampil dengan Option ID (ID:120, ID:121, dll)
echo - Format: ID:xxx  Label  (value: x)
echo.
echo CATAT: Question ID dan Option ID untuk referensi
echo.
pause
echo.
echo STEP 2: SUBMIT SURVEY BARU (TEST)
echo ----------------------------------
echo 1. Logout dari Admin
echo 2. Login sebagai Pendaftar (test account)
echo 3. Buka form survey tes psikologi
echo 4. Hard Refresh form: Ctrl+F5  (PENTING!)
echo 5. Isi dan submit survey
echo.
echo Ini untuk generate data BARU dengan sistem Option ID
echo.
pause
echo.
echo STEP 3: CEK TAB JAWABAN SURVEY
echo -------------------------------
echo 1. Login sebagai Admin
echo 2. Buka: /admin/tes-psikologi?mainTab=jawaban-survey
echo 3. Hard Refresh: Ctrl+F5
echo 4. Cari pendaftar test yang baru submit
echo.
echo EXPECTED untuk DATA BARU:
echo   RAW: 122 ✓ ID
echo   Setuju
echo.
echo Artinya:
echo - Data tersimpan sebagai Option ID (122)
echo - Indicator hijau (✓ ID) = sistem baru bekerja!
echo - Display: "Setuju" = lookup by ID berhasil
echo.
echo NOT Expected:
echo   RAW: Sangat Tidak Setuju ⚠ VALUE/LABEL
echo   atau
echo   RAW: 1 ⚠ VALUE/LABEL
echo.
echo Jika masih muncul orange (⚠ VALUE/LABEL):
echo - Form belum terupload atau
echo - Cache belum clear atau
echo - Browser pendaftar perlu hard refresh
echo.
pause
echo.
echo STEP 4: VERIFIKASI CROSS-CHECK
echo -------------------------------
echo.
echo Bandingkan data di Tab Survey dengan Tab Jawaban:
echo.
echo Tab Survey:
echo   Q5: Saya sering menyampaikan masukan...
echo     ID:120  Sangat Tidak Setuju  (value: 1)
echo     ID:121  Tidak Setuju         (value: 2)
echo     ID:122  Setuju               (value: 3)
echo     ID:123  Sangat Setuju        (value: 4)
echo.
echo Tab Jawaban Survey (Pendaftar Test):
echo   Q5: Saya sering...
echo   RAW: 122 ✓ ID
echo   Setuju
echo.
echo CROSS-CHECK:
echo - RAW data = 122 (Option ID)
echo - Tab Survey ID:122 = "Setuju"
echo - Display = "Setuju"
echo - ✓ MATCH! Sistem bekerja dengan benar!
echo.
pause
echo.
echo ============================================================
echo DEBUG INDICATORS
echo ============================================================
echo.
echo ✓ ID (hijau) = Data tersimpan sebagai Option ID (NEW SYSTEM)
echo   - Data baru (setelah upload fix)
echo   - Format: RAW: 122 ✓ ID
echo   - Lookup: Cepat dan akurat by ID
echo.
echo ⚠ VALUE/LABEL (orange) = Data tersimpan sebagai VALUE/LABEL (OLD SYSTEM)
echo   - Data lama (sebelum upload fix)
echo   - Format: RAW: "Sangat Tidak Setuju" ⚠ VALUE/LABEL
echo   - Lookup: Fallback method 2-5
echo.
echo [VALUE NOT FOUND: xxx] = Lookup gagal
echo   - Data tidak match dengan options yang ada
echo   - Perlu cek log error untuk detail
echo.
echo ============================================================
echo TROUBLESHOOTING
echo ============================================================
echo.
echo Jika masih muncul [VALUE NOT FOUND: xxx]:
echo.
echo 1. Verifikasi file terupload:
echo    - Cek production file punya comment: ARCHITECTURAL FIX
echo    - Form: value="{{ $option-^>id }}"
echo    - Logic: $questionOptions-^>firstWhere('id', ...)
echo.
echo 2. Clear cache lagi:
echo    php artisan optimize:clear
echo    php artisan view:clear
echo.
echo 3. Hard refresh browser:
echo    Ctrl+F5 atau Incognito mode
echo.
echo 4. Submit survey BARU (bukan data lama):
echo    - Pendaftar harus hard refresh form dulu
echo    - Data lama mungkin masih pakai VALUE/LABEL
echo.
echo 5. Cek log error:
echo    tail -50 storage/logs/laravel.log ^| grep "Survey option"
echo.
echo ============================================================
echo DOKUMENTASI
echo ============================================================
echo.
echo Baca dokumentasi lengkap:
echo - DEBUG_DISPLAY_GUIDE.md (Cara pakai debug display)
echo - ARCHITECTURAL_FIX_OPTION_ID_SYSTEM.md (Technical detail)
echo - QUICK_GUIDE_OPTION_ID_FIX.md (Quick reference)
echo.
echo ============================================================
echo UPLOAD DAN SETUP SELESAI!
echo ============================================================
echo.
echo Silakan test sesuai instruksi di atas.
echo Dengan debug display, Anda bisa melihat EXACTLY data yang tersimpan!
echo.
echo Good luck! 🔍🎯
echo.
pause
