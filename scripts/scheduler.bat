@echo off
REM ========================================
REM Laravel Scheduler for Windows
REM Usage: scheduler.bat
REM ========================================

SET PHP_PATH=C:\xampp\php\php.exe
SET PROJECT_PATH=%~dp0..

echo ========================================
echo  Laravel Scheduler - PPDB MTsN 2
echo ========================================
echo.
echo Starting scheduler...
echo Project: %PROJECT_PATH%
echo.

:loop
cd /d %PROJECT_PATH%
%PHP_PATH% artisan schedule:run

REM Wait 60 seconds
timeout /t 60 /nobreak >nul

goto loop
