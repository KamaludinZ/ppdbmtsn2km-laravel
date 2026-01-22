@echo off
echo ========================================
echo QUICK TEST - Halaman Daftar
echo ========================================
echo.

echo [1/5] Clearing caches...
php artisan optimize:clear >nul 2>&1
echo      Done!

echo.
echo [2/5] Testing registration flow...
php test_registration_flow.php

echo.
echo [3/5] Testing component...
php artisan tinker --execute="echo 'Component Test: '; $c = new \App\Livewire\Auth\RegisterFixed(); echo 'RegisterFixed loaded: OK'; exit();"

echo.
echo [4/5] Checking routes...
php artisan route:list --path=daftar

echo.
echo [5/5] Checking Livewire components...
php artisan livewire:list | findstr "RegisterFixed"

echo.
echo ========================================
echo NEXT STEPS:
echo ========================================
echo 1. Open: http://localhost/test-livewire
echo 2. Test basic Livewire functionality
echo 3. Open: http://localhost/daftar
echo 4. Test registration flow
echo.
echo If issues persist:
echo - Check storage/logs/laravel.log
echo - Check browser console (F12)
echo - See SOLUSI_LENGKAP_HALAMAN_DAFTAR.md
echo ========================================
pause
