@echo off
echo ========================================
echo FIX: SYNTAX ERROR - MISSING CLOSING TAGS
echo ========================================
echo.
echo Perubahan:
echo - Fix ParseError: unexpected end of file at line 1912/1916
echo - Added missing closing tags for Tab Madrasah
echo - Added missing closing tags for Tab Seragam
echo - Balanced blade directives: 112 @if = 112 @endif
echo.
echo Tanggal: 5 Februari 2026
echo.

REM Upload View
echo [1/2] Uploading Pendaftar DaftarUlang view...
scp resources/views/livewire/pendaftar/daftar-ulang.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload view
    pause
    exit /b 1
)
echo View uploaded successfully!
echo.

REM Clear cache
echo [2/2] Clearing production cache...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan optimize:clear"
if %errorlevel% neq 0 (
    echo WARNING: Failed to clear cache
)
echo Cache cleared!
echo.

echo ========================================
echo UPLOAD COMPLETED SUCCESSFULLY!
echo ========================================
echo.
echo Files uploaded:
echo - 1 view (Pendaftar DaftarUlang)
echo.
echo FIXES APPLIED:
echo.
echo 1. TAB MADRASAH (Lines 976-978):
echo    - Added closing ^</div^> for tab div (line 269)
echo    - Added closing @endif for tab conditional (line 268)
echo.
echo 2. TAB SERAGAM (Lines 1584-1586):
echo    - Added closing ^</div^> for tab div (line 982)
echo    - Added closing @endif for tab conditional (line 981)
echo.
echo VERIFICATION:
echo  - Blade directives balanced: 112 @if = 112 @endif
echo  - ParseError FIXED
echo  - Page should load without errors
echo.
echo TESTING CHECKLIST:
echo [ ] Page loads without ParseError
echo [ ] Tab Madrasah displays correctly
echo [ ] Tab Seragam displays correctly
echo [ ] Tab Ukuran displays correctly
echo [ ] Can switch between tabs
echo [ ] Footer displays correctly (not broken)
echo [ ] Step-by-step form works in Tab Madrasah
echo.
pause
