@echo off
REM ========================================
REM Manual Refresh Materialized Views (Windows)
REM Usage: refresh-views.bat [concurrent]
REM ========================================

SET PHP_PATH=C:\xampp\php\php.exe
SET PROJECT_PATH=%~dp0..

echo ========================================
echo  Refresh Materialized Views
echo ========================================
echo.

cd /d %PROJECT_PATH%

if "%1"=="concurrent" (
    echo Running with concurrent mode...
    %PHP_PATH% artisan dashboard:refresh-views --concurrent
) else (
    echo Running normal refresh...
    %PHP_PATH% artisan dashboard:refresh-views
)

echo.
echo Done!
pause
