@echo off
echo ========================================
echo UPLOAD: CRITICAL FIX - Dashboard Progress
echo and Kartu Tes Seleksi Access
echo ========================================
echo.
echo Tanggal: 5 Februari 2026
echo Total Files: 3
echo Migration: NO
echo.
echo PROBLEMS FIXED:
echo 1. Step Verifikasi "Selesai" (should be "Menunggu") when payment pending
echo 2. Menu Kartu Tes Seleksi aktif (should be disabled) when payment pending
echo.
echo ROOT CAUSES:
echo 1. Line 387 Dashboard.php: if ($isVerified) bypass payment check
echo 2. Line 338 Dashboard.php: $tesPsikologiComplete = false for "menunggu" status
echo 3. dashboard.blade.php: Hanya cek upload, tidak cek approval
echo 4. KartuTesSeleksi.php: Hanya cek upload, tidak cek approval
echo.
echo SOLUTIONS:
echo 1. Line 387: Replace if ($isVerified) with if ($isPostTest)
echo 2. Line 338: Set $tesPsikologiComplete = true for "menunggu" status
echo 3. dashboard.blade.php: Add check status_verifikasi === 'disetujui'
echo 4. KartuTesSeleksi.php: Add check status_verifikasi === 'disetujui'
echo.
echo ========================================
echo.

REM Change to project directory
cd /d C:\ppdbmtsn2km-laravel

echo [1/3] Uploading Dashboard Controller...
scp app/Livewire/Pendaftar/Dashboard.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Pendaftar/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload dashboard controller
    pause
    exit /b 1
)
echo SUCCESS: Dashboard controller uploaded
echo.

echo [2/3] Uploading Dashboard Layout...
scp resources/views/components/layouts/dashboard.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/components/layouts/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload dashboard layout
    pause
    exit /b 1
)
echo SUCCESS: Dashboard layout uploaded
echo.

echo [3/3] Uploading Kartu Tes Seleksi Controller...
scp app/Livewire/Pendaftar/KartuTesSeleksi.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Pendaftar/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload kartu tes seleksi controller
    pause
    exit /b 1
)
echo SUCCESS: Kartu tes seleksi controller uploaded
echo.

echo ========================================
echo [4/4] Clearing Cache...
echo ========================================
echo.

ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan optimize:clear"
if %errorlevel% neq 0 (
    echo WARNING: Cache clear may have failed
)
echo SUCCESS: Cache cleared
echo.

echo ========================================
echo UPLOAD COMPLETE!
echo ========================================
echo.
echo Summary of Changes:
echo.
echo FILE 1: app/Livewire/Pendaftar/Dashboard.php
echo   Line 310-320: Added $isPostTest for tes psikologi step
echo   Line 338: CRITICAL FIX - Set $tesPsikologiComplete = TRUE for "menunggu"
echo   Line 387: CRITICAL FIX - Changed if ($isVerified) to if ($isPostTest)
echo   Impact:
echo   - Step tes psikologi: "Menunggu" (correct label)
echo   - Step verifikasi: "Menunggu" (NOT "Terkunci"!)
echo.
echo FILE 2: resources/views/components/layouts/dashboard.blade.php
echo   Line 262: Added check status_verifikasi === 'disetujui'
echo   Line 311: Updated tooltip message
echo   Impact: Menu Kartu Tes Seleksi hanya aktif setelah payment APPROVED
echo.
echo FILE 3: app/Livewire/Pendaftar/KartuTesSeleksi.php
echo   Line 33-37: Changed to check paymentApproved (with status_verifikasi check)
echo   Line 38: Updated error message
echo   Impact: Controller blocks access until payment APPROVED
echo.
echo CRITICAL UNDERSTANDING:
echo   Variable $tesPsikologiComplete artinya:
echo   "Apakah step sudah AT LEAST MENUNGGU (bukan lengkapi)?"
echo.
echo   - Status 'disetujui' → TRUE (complete) → Label "Selesai"
echo   - Status 'menunggu' → TRUE (at least waiting) → Label "Menunggu"
echo   - Status 'ditolak' → FALSE (needs fix) → Label "Perbaiki"
echo   - Status 'belum_upload' → FALSE (needs action) → Label "Lengkapi"
echo.
echo   Step Verifikasi Logic:
echo   - if ($isPostTest ^|^| $allStepsCompleted) → "Selesai"
echo   - elseif ($allStepsAtLeastWaiting) → "Menunggu" ← FIX HERE!
echo   - elseif ($needsFix) → "Perbaiki"
echo   - else → "Terkunci" (ada step yang masih "Lengkapi")
echo.
echo IMPACT:
echo   SCENARIO: status_pendaftaran = "data_disetujui" + payment = "menunggu"
echo.
echo   Result:
echo   - Step Tes Psikologi: "Menunggu" (yellow) ✅
echo   - Step Verifikasi: "Menunggu" (yellow) ✅ (NOT "Terkunci"!)
echo   - Menu Kartu: Disabled (gray) ✅
echo.
echo   Verifikasi akan "Terkunci" HANYA jika ada step yang masih "Lengkapi"
echo   (misalnya: formulir belum lengkap, berkas belum upload, dll)
echo.
echo ========================================
echo TESTING CHECKLIST
echo ========================================
echo.
echo Test 1: Status "data_disetujui" + Payment "Menunggu" (CRITICAL!)
echo    Setup:
echo    a. Status pendaftaran: "data_disetujui"
echo    b. Upload bukti transfer tes psikologi
echo    c. Status payment: "Menunggu Verifikasi"
echo    d. Semua step lain sudah at least "Menunggu"
echo.
echo    Expected at /pendaftar/dashboard:
echo    e. ✅ Step "Tes Psikologi": "Menunggu" (yellow)
echo    f. ✅ Step "Verifikasi": "MENUNGGU" (yellow) - NOT "Terkunci"!
echo    g. ✅ Menu "Kartu Tes Seleksi": DISABLED (gray, cannot click)
echo    h. ✅ Tooltip: "...pembayaran tes psikologi disetujui"
echo.
echo    Cross-check:
echo    i. /pendaftar/status → "Menunggu Verifikasi" ✅
echo    j. /pendaftar/tes-psikologi → "Menunggu Verifikasi" ✅
echo    k. /pendaftar/dashboard → Step "Menunggu" ✅
echo    l. ALL CONSISTENT! 🎯
echo.
echo Test 2: Step Verifikasi "Terkunci" - When Step Lain Masih "Lengkapi"
echo    a. Status pendaftaran: any
echo    b. Ada step yang masih "Lengkapi" (misalnya berkas belum upload)
echo    c. Check /pendaftar/dashboard
echo    d. ✅ Step "Verifikasi": "Terkunci" (gray)
echo.
echo Test 3: Status "data_disetujui" + Payment "Disetujui"
echo    a. Admin approve bukti transfer
echo    b. Status payment berubah: "Disetujui"
echo    c. Check /pendaftar/dashboard
echo    d. ✅ Step "Tes Psikologi": "Selesai" (green)
echo    e. ✅ Step "Verifikasi": "Menunggu" or "Selesai"
echo    f. ✅ Menu "Kartu Tes Seleksi": ENABLED (can click!)
echo.
echo Test 4: Direct URL Access - /pendaftar/kartu-tes-seleksi
echo    a. Status payment: "Menunggu Verifikasi"
echo    b. Try access URL directly: /pendaftar/kartu-tes-seleksi
echo    c. ✅ Redirected to dashboard with error message
echo    d. ✅ Error: "...pembayaran tes psikologi disetujui"
echo.
echo Documentation: FIX_DASHBOARD_AND_KARTU_CRITICAL_V2.md
echo.
echo ========================================
pause
