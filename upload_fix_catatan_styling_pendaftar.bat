@echo off
echo ========================================
echo UPLOAD FIX CATATAN STYLING PENDAFTAR
echo ========================================
echo.

REM Upload file yang sudah diperbaiki styling
echo [1/2] Uploading dashboard.blade.php (Pendaftar View)...
scp "C:\ppdbmtsn2km-laravel\resources\views\livewire\pendaftar\dashboard.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload dashboard.blade.php
    pause
    exit /b 1
)

echo [2/2] Uploading status.blade.php (Pendaftar View)...
scp "C:\ppdbmtsn2km-laravel\resources\views\livewire\pendaftar\status.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload status.blade.php
    pause
    exit /b 1
)

echo.
echo ========================================
echo CLEARING CACHE
echo ========================================
echo.

REM Clear view cache
echo Clearing view cache...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan view:clear"

echo.
echo ========================================
echo UPLOAD COMPLETED!
echo ========================================
echo.
echo Files uploaded:
echo - resources/views/livewire/pendaftar/dashboard.blade.php
echo - resources/views/livewire/pendaftar/status.blade.php
echo.
echo ========================================
echo STYLING CHANGES SUMMARY
echo ========================================
echo.
echo [SEBELUM] - Text Putih Tidak Terlihat:
echo ----------------------------------------
echo Outer Box:
echo - Background: Gradient yellow-orange ✓ (OK)
echo - Border: 4px yellow ✓ (OK)
echo - Title: White text ✓ (OK)
echo.
echo Inner Catatan Box:
echo - Background: bg-white bg-opacity-20 (semi-transparent)
echo - Text: text-white (PUTIH!) ❌ TIDAK TERLIHAT!
echo - Border: white semi-transparent
echo.
echo [SESUDAH] - Text Hitam Jelas Terlihat:
echo ----------------------------------------
echo Outer Box:
echo - Background: Gradient yellow-orange ✓
echo - Border: 4px yellow ✓
echo - Title: White text ✓
echo.
echo Inner Catatan Box:
echo - Background: bg-yellow-50 (SOLID KUNING MUDA) ✓
echo - Text: text-gray-800 (HITAM GELAP) ✓ TERLIHAT JELAS!
echo - Border: 2px yellow-400 solid ✓
echo - Font: font-semibold text-sm ✓
echo.
echo ========================================
echo PERUBAHAN DETAIL
echo ========================================
echo.
echo File 1: dashboard.blade.php (Line 358-360)
echo ------------------------------------------
echo OLD:
echo   ^<div class="bg-white bg-opacity-20..."^>
echo       ^<p class="text-white..."^>
echo.
echo NEW:
echo   ^<div class="bg-yellow-50 border-2 border-yellow-400..."^>
echo       ^<p class="text-gray-800 font-semibold text-sm..."^>
echo.
echo File 2: status.blade.php (Line 82-84)
echo ------------------------------------------
echo OLD:
echo   ^<div class="bg-white bg-opacity-20..."^>
echo       ^<p class="text-white..."^>
echo.
echo NEW:
echo   ^<div class="bg-yellow-50 border-2 border-yellow-400..."^>
echo       ^<p class="text-gray-800 font-semibold text-sm..."^>
echo.
echo ========================================
echo VISUAL COMPARISON
echo ========================================
echo.
echo BEFORE (Invisible):
echo ┌─────────────────────────────────────┐
echo │ ⚠️ 📝 Catatan dari Panitia (white)  │
echo ├─────────────────────────────────────┤
echo │ ╭─────────────────────────────────╮ │
echo │ │ [WHITE TEXT ON WHITE BG]        │ │  ← TIDAK TERLIHAT!
echo │ │ Mohon lengkapi...               │ │
echo │ ╰─────────────────────────────────╯ │
echo └─────────────────────────────────────┘
echo.
echo AFTER (High Contrast):
echo ┌─────────────────────────────────────┐
echo │ ⚠️ 📝 Catatan dari Panitia (white)  │
echo ├─────────────────────────────────────┤
echo │ ╭─────────────────────────────────╮ │
echo │ │ [DARK GRAY ON YELLOW BG]        │ │  ← JELAS TERLIHAT!
echo │ │ Mohon lengkapi...               │ │
echo │ ╰─────────────────────────────────╯ │
echo └─────────────────────────────────────┘
echo.
echo ========================================
echo TESTING CHECKLIST
echo ========================================
echo.
echo Test 1: Dashboard - Catatan Visibility
echo ---------------------------------------
echo 1. Set status_pendaftaran = 'perbaiki_data'
echo 2. Fill catatan_verifikasi with multi-line text
echo 3. Access /pendaftar/dashboard
echo 4. Verify outer box: Yellow-orange gradient ✓
echo 5. Verify inner box: Light yellow background ✓
echo 6. Verify text: Dark gray, clearly readable ✓
echo 7. Verify border: 2px solid yellow ✓
echo.
echo Test 2: Status Page - Catatan Visibility
echo ------------------------------------------
echo 1. Same pendaftar as Test 1
echo 2. Access /pendaftar/status
echo 3. Verify same styling as dashboard ✓
echo 4. Verify text is clearly readable ✓
echo.
echo Test 3: Multi-line Text Preservation
echo -------------------------------------
echo 1. Set catatan with line breaks:
echo    "Mohon lengkapi:
echo     - Data A
echo     - Data B
echo     - Data C"
echo 2. Verify line breaks preserved (whitespace-pre-line) ✓
echo.
echo Test 4: Long Text Wrapping
echo --------------------------
echo 1. Set very long catatan text
echo 2. Verify text wraps properly ✓
echo 3. Verify no overflow issues ✓
echo.
echo ========================================
echo COLOR PALETTE USED
echo ========================================
echo.
echo Outer Container:
echo - Background: gradient from-yellow-500 to-orange-600
echo - Border: border-4 border-yellow-400
echo - Title: text-white
echo - Helper: text-yellow-100
echo.
echo Inner Catatan Box:
echo - Background: bg-yellow-50 (rgb(254, 252, 232))
echo - Text: text-gray-800 (rgb(31, 41, 55))
echo - Border: border-2 border-yellow-400 (rgb(250, 204, 21))
echo - Contrast Ratio: ~12:1 (AAA Accessibility) ✓
echo.
pause
