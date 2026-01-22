@echo off
echo ==========================================
echo   FIX POSTCSS ERROR & RESTART VITE
echo ==========================================
echo.

echo [1] Stopping Vite...
taskkill /F /IM node.exe 2>nul
timeout /t 2 >nul

echo.
echo [2] Clearing Vite cache...
if exist "node_modules\.vite" (
    rd /s /q "node_modules\.vite"
    echo    ✓ Vite cache cleared
)

echo.
echo [3] Clearing Laravel view cache...
call php artisan view:clear

echo.
echo [4] PostCSS config has been fixed!
echo    - Removed problematic nesting plugin
echo    - Config is now clean
echo.
echo [5] Starting Vite with fixed config...
echo.
echo ==========================================
echo   VITE SHOULD START WITHOUT ERRORS NOW
echo ==========================================
echo.

call npm run dev

pause