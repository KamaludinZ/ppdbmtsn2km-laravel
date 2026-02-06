@echo off
echo ============================================================
echo TEST SSH CONNECTION - Production Server
echo ============================================================
echo.

echo [1/5] Testing basic SSH connection...
echo.
ssh ppdb-production "echo 'SSH Connection: OK'"
if %errorlevel% neq 0 (
    echo.
    echo ❌ FAILED: Cannot connect to server
    echo.
    echo Possible issues:
    echo - SSH key not set up correctly
    echo - Public key not copied to server
    echo - Firewall blocking connection
    echo - SSH service not running
    echo.
    echo Please check PANDUAN_SETUP_SSH_SHARED_HOSTING.md
    pause
    exit /b 1
)
echo ✓ Basic connection OK
echo.

echo [2/5] Testing PHP availability...
echo.
ssh ppdb-production "php -v"
if %errorlevel% neq 0 (
    echo ❌ PHP not found on server
    pause
    exit /b 1
)
echo ✓ PHP OK
echo.

echo [3/5] Testing Laravel directory...
echo.
ssh ppdb-production "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && pwd"
if %errorlevel% neq 0 (
    echo ❌ Cannot access Laravel directory
    pause
    exit /b 1
)
echo ✓ Laravel directory OK
echo.

echo [4/5] Testing Laravel command...
echo.
ssh ppdb-production "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan --version"
if %errorlevel% neq 0 (
    echo ❌ Cannot run Laravel command
    pause
    exit /b 1
)
echo ✓ Laravel command OK
echo.

echo [5/5] Testing file listing...
echo.
ssh ppdb-production "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && ls -la app/Models/ | head -10"
if %errorlevel% neq 0 (
    echo ❌ Cannot list files
    pause
    exit /b 1
)
echo ✓ File listing OK
echo.

echo ============================================================
echo ✅ ALL TESTS PASSED!
echo ============================================================
echo.
echo SSH connection is working correctly!
echo Claude Code can now execute commands on production server.
echo.
echo You can now inform Claude Code that SSH is ready.
echo.
pause
