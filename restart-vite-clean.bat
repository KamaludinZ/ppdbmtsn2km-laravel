@echo off
echo ==========================================
echo   RESTART VITE WITH CLEAN CONSOLE
echo ==========================================
echo.

echo [1] Stopping any running Node processes...
taskkill /F /IM node.exe 2>nul
timeout /t 2 >nul

echo.
echo [2] Clearing caches...
echo    - NPM cache...
call npm cache clean --force 2>nul

echo    - Laravel caches...
call php artisan view:clear
call php artisan config:clear
call php artisan cache:clear

echo    - Vite cache...
if exist "node_modules\.vite" (
    rd /s /q "node_modules\.vite"
    echo    - Vite cache cleared
)

echo.
echo [3] Starting Vite with optimized configuration...
echo.
echo ==========================================
echo   CONSOLE SHOULD BE MUCH CLEANER NOW!
echo ==========================================
echo.
echo Expected output:
echo - CSS parsing errors: FIXED
echo - Vendor prefix warnings: FILTERED
echo - Wire/Program errors: REMOVED
echo.
echo Remaining (normal):
echo - YouTube iframe warnings (external)
echo - Feature Policy (browser security)
echo - Vite connection messages
echo.
echo Press Ctrl+C to stop Vite when needed.
echo ==========================================
echo.

call npm run dev

pause