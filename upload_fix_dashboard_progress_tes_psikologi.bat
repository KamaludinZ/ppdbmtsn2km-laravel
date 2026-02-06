@echo off
echo ========================================
echo UPLOAD: Fix Dashboard Progress
echo Tes Psikologi Status Consistency
echo ========================================
echo.
echo Tanggal: 5 Februari 2026
echo Total Files: 1
echo Migration: NO
echo.
echo PROBLEM FIXED:
echo - Progress "Selesai" meski tes psikologi masih "Menunggu Verifikasi"
echo - Root Cause: if ($isVerified) bypass ALL payment checks
echo.
echo SOLUTION:
echo - Replace if ($isVerified) with if ($isPostTest)
echo - Only auto-complete if status: lulus/tidak_lulus/cadangan
echo - For status "data_disetujui", check actual payment status
echo.
echo ========================================
echo.

REM Change to project directory
cd /d C:\ppdbmtsn2km-laravel

echo [1/1] Uploading Dashboard Controller...
scp app/Livewire/Pendaftar/Dashboard.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Pendaftar/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload dashboard controller
    pause
    exit /b 1
)
echo SUCCESS: Dashboard controller uploaded
echo.

echo ========================================
echo [2/2] Clearing Cache...
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
echo FILE: app/Livewire/Pendaftar/Dashboard.php
echo   REAL FIX - Line 310-320:
echo   - Added: $isPostTest = in_array($status, ['lulus', 'tidak_lulus', 'cadangan'])
echo   - Changed: if ($isVerified) → if ($isPostTest)
echo   - Impact: Tes psikologi only auto-complete for post-test statuses
echo   - For status "data_disetujui", now checks actual payment status
echo.
echo   ADDITIONAL FIX - Lines 337, 345, 353, 362:
echo   - tesPsikologiComplete = false when status = "menunggu"
echo   - tesPsikologiComplete = false when status = "ditolak"
echo   - tesPsikologiComplete = false when status = "belum_upload"
echo   - tesPsikologiComplete = true ONLY when status = "disetujui"
echo.
echo ROOT CAUSE:
echo   The if ($isVerified) condition checked for:
echo   ['data_disetujui', 'lulus', 'tidak_lulus', 'cadangan']
echo   This caused status "data_disetujui" to bypass ALL payment checks!
echo.
echo IMPACT:
echo   - Status "data_disetujui" + payment "menunggu" → Progress "Menunggu" (CORRECT!)
echo   - Status "data_disetujui" + payment "disetujui" → Progress "Selesai" (CORRECT!)
echo   - Status "lulus/tidak_lulus/cadangan" → Progress "Selesai" (CORRECT!)
echo.
echo ========================================
echo TESTING CHECKLIST
echo ========================================
echo.
echo Test 1: Status Pendaftaran = "data_disetujui" + Payment "Menunggu" (CRITICAL!)
echo    a. Login sebagai pendaftar
echo    b. Status pendaftaran: "data_disetujui"
echo    c. Upload bukti transfer tes psikologi
echo    d. Status payment: "Menunggu Verifikasi"
echo    e. Check /pendaftar/dashboard
echo    f. ✅ Progress "Tes Psikologi": "Menunggu" (yellow) - NOT "Selesai"!
echo    g. ✅ Progress "Verifikasi": "Terkunci" or "Menunggu" (NOT "Selesai")
echo    h. Cross-check /pendaftar/status: "Menunggu Verifikasi" ✅ CONSISTENT!
echo.
echo Test 2: Status Pendaftaran = "data_disetujui" + Payment "Disetujui"
echo    a. Admin approve bukti transfer
echo    b. Status payment: "Disetujui"
echo    c. Check /pendaftar/dashboard
echo    d. ✅ Progress "Tes Psikologi": "Selesai" (green)
echo    e. ✅ Can proceed to next steps
echo.
echo Test 3: Status Pendaftaran = "lulus/tidak_lulus/cadangan"
echo    a. Any payment status (irrelevant after test)
echo    b. Check /pendaftar/dashboard
echo    c. ✅ Progress "Tes Psikologi": "Selesai" (green)
echo    d. ✅ Test phase completed, payment not blocking
echo.
echo Test 4: Status Payment = "Ditolak"
echo    a. Admin reject bukti transfer
echo    b. Status payment: "Ditolak"
echo    c. Check /pendaftar/dashboard
echo    d. ✅ Progress "Tes Psikologi": "Perbaiki" (red)
echo    e. ✅ Progress "Verifikasi": "Terkunci"
echo.
echo Documentation: FIX_DASHBOARD_PROGRESS_TES_PSIKOLOGI_COMPLETE_V2.md
echo.
echo ========================================
pause
