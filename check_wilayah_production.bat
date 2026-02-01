@echo off
echo ========================================
echo CHECK WILAYAH DATA DI PRODUCTION
echo ========================================
echo.

echo [1/3] Upload check script...
scp "C:\ppdbmtsn2km-laravel\check_wilayah_data.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/
if %errorlevel% neq 0 (
    echo ERROR: Upload gagal!
    pause
    exit /b 1
)
echo ✓ Upload berhasil
echo.

echo [2/3] Jalankan check script di production...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php check_wilayah_data.php"
if %errorlevel% neq 0 (
    echo WARNING: Script execution warning
)
echo.

echo [3/3] Hapus check script dari server...
ssh mtsnkot4@103.30.244.77 "rm /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/check_wilayah_data.php"
echo ✓ Cleanup selesai
echo.

echo ========================================
echo CHECK SELESAI
echo ========================================
pause
