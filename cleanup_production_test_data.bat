@echo off
echo ========================================
echo CLEANUP TEST DATA DI PRODUCTION
echo ========================================
echo.

echo [1/3] Upload cleanup script...
scp "cleanup_test_users.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/
if %errorlevel% neq 0 (
    echo ERROR: Upload gagal!
    pause
    exit /b 1
)
echo ✓ Upload berhasil
echo.

echo [2/3] Jalankan cleanup script...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php cleanup_test_users.php"
if %errorlevel% neq 0 (
    echo ERROR: Cleanup gagal!
    pause
    exit /b 1
)
echo ✓ Cleanup selesai
echo.

echo [3/3] Hapus cleanup script dari server...
ssh mtsnkot4@103.30.244.77 "rm /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/cleanup_test_users.php"
echo ✓ Cleanup script dihapus
echo.

echo ========================================
echo CLEANUP SELESAI!
echo ========================================
echo.
echo Silakan coba pendaftaran lagi dengan:
echo - Email baru yang belum pernah dipakai
echo - NISN baru yang belum pernah dipakai
echo.
pause
