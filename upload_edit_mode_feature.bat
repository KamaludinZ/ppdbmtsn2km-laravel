@echo off
echo ============================================================
echo UPLOAD EDIT MODE FEATURE - Toggle + Persistent State + Show IDs
echo ============================================================
echo.
echo Features:
echo   - Toggle Edit Mode button dengan persistent state (URL)
echo   - Edit icons hanya muncul saat mode ON
echo   - Tampilkan ID Pertanyaan dan ID Opsi di modal
echo   - State survives page refresh
echo.

echo [1/2] Uploading TesPsikologi.php (Backend Component)...
scp app/Livewire/Admin/TesPsikologi.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/
if %errorlevel% neq 0 goto error_upload

echo.
echo [2/2] Uploading tes-psikologi-jawaban-survey.blade.php (Frontend View)...
scp resources/views/livewire/admin/partials/tes-psikologi-jawaban-survey.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/partials/
if %errorlevel% neq 0 goto error_upload

echo.
echo ============================================================
echo ✓ ALL FILES UPLOADED SUCCESSFULLY
echo ============================================================
echo.
echo NEXT STEPS - Clear Cache via SSH:
echo.
echo   cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
echo   php artisan optimize:clear
echo   php artisan view:clear
echo   php artisan config:clear
echo.
echo ============================================================
echo TESTING CHECKLIST:
echo ============================================================
echo.
echo Test 1: Toggle Edit Mode
echo   1. Login sebagai Admin
echo   2. Buka: /admin/tes-psikologi?mainTab=jawaban-survey
echo   3. Click "Mode Edit: OFF" button
echo   4. Button berubah jadi "Mode Edit: ON" (yellow)
echo   5. Edit icons (pencil) muncul di setiap cell
echo   6. Click button lagi - Mode Edit OFF
echo   7. Edit icons hilang
echo.
echo Test 2: Persistent State
echo   1. Enable Mode Edit (click toggle)
echo   2. Check URL: harus ada "editMode=1"
echo   3. Press F5 (refresh page)
echo   4. Expected: Mode Edit tetap ON, icons tetap visible
echo   5. Disable Mode Edit
echo   6. Press F5 (refresh page)
echo   7. Expected: Mode Edit tetap OFF, icons tetap hidden
echo.
echo Test 3: ID Display
echo   1. Enable Mode Edit
echo   2. Click pencil icon pada jawaban
echo   3. Modal opens
echo   4. Check: "ID Pertanyaan: XX" tampil (gray badge)
echo   5. Check: Setiap opsi tampil "Label (ID: XX)"
echo   6. IDs match dengan database
echo.
echo Test 4: Audit Logs
echo   1. Check: storage/logs/laravel.log
echo   2. Find: "Admin toggled Edit Mode"
echo   3. Verify: admin_id, admin_name, edit_mode, timestamp
echo.
echo See: COMPLETE_EDIT_MODE_FEATURE_6_FEB_2026.md for full guide
echo.
pause
exit /b 0

:error_upload
echo.
echo ❌ UPLOAD FAILED - Use Manual Upload
echo ============================================================
echo.
echo File 1:
echo   Local:  C:\ppdbmtsn2km-laravel\app\Livewire\Admin\TesPsikologi.php
echo   Remote: /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/
echo.
echo File 2:
echo   Local:  C:\ppdbmtsn2km-laravel\resources\views\livewire\admin\partials\tes-psikologi-jawaban-survey.blade.php
echo   Remote: /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/partials/
echo.
echo Via cPanel File Manager:
echo   1. Login to cPanel
echo   2. Navigate to app/Livewire/Admin/
echo   3. Upload TesPsikologi.php (overwrite)
echo   4. Navigate to resources/views/livewire/admin/partials/
echo   5. Upload tes-psikologi-jawaban-survey.blade.php (overwrite)
echo.
echo IMPORTANT: Clear cache after manual upload!
echo   ssh mtsnkot4@103.30.244.77
echo   cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
echo   php artisan optimize:clear
echo   php artisan view:clear
echo.
pause
exit /b 1
