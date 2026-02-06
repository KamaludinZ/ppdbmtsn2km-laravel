@echo off
echo ========================================
echo   Upload Fix: ENUM Constraint Violation
echo   Pendidikan Fields (ayah/ibu/wali)
echo ========================================
echo.
echo Tanggal: %date% %time%
echo.
echo Masalah: Database reject "SMA/Sederajat" (slash)
echo Solusi: Ganti ke "SMA Sederajat" (spasi)
echo.

echo [1/2] Uploading tab-data-orangtua.blade.php (Fix dropdown values)...
scp resources/views/tab_templates/tab-data-orangtua.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/tab_templates/
if %errorlevel% neq 0 (
    echo ERROR: Gagal upload view file
    pause
    exit /b 1
)
echo OK - View file uploaded
echo.

echo [2/2] Uploading migration file (Fix existing data)...
scp database/migrations/2026_02_02_073000_fix_pendidikan_values_slash_to_space.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/database/migrations/
if %errorlevel% neq 0 (
    echo ERROR: Gagal upload migration file
    pause
    exit /b 1
)
echo OK - Migration file uploaded
echo.

echo ========================================
echo   Upload Selesai!
echo ========================================
echo.
echo PENTING: Jalankan perintah berikut di server:
echo.
echo ssh mtsnkot4@103.30.244.77
echo cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
echo.
echo # Run migration (fix existing data)
echo php artisan migrate
echo.
echo # Clear cache
echo php artisan view:clear
echo php artisan cache:clear
echo php artisan config:clear
echo.
echo ========================================
echo   Verifikasi:
echo ========================================
echo.
echo 1. Cek migration sukses:
echo    php artisan migrate:status ^| grep fix_pendidikan
echo.
echo 2. Cek data sudah ter-fix:
echo    psql -U mtsnkot4 -d mtsnkot4_ppdb -c "SELECT ayah_pendidikan, COUNT(*) FROM pendaftar WHERE ayah_pendidikan LIKE '%%/%%' GROUP BY ayah_pendidikan;"
echo    ^(Harus 0 rows^)
echo.
echo 3. Test di browser:
echo    - Login sebagai Pendaftar
echo    - Buka Formulir -^> Tab Data Orang Tua
echo    - Pilih dropdown Pendidikan Ayah
echo    - Harus muncul: "SMA Sederajat" (SPASI)
echo    - TIDAK ada: "SMA/Sederajat" (SLASH)
echo.
echo 4. Monitor log untuk ensure no more constraint errors:
echo    tail -f storage/logs/laravel.log ^| grep -i "constraint\|pendidikan_check"
echo.
echo ========================================
echo   Known Fixed User:
echo ========================================
echo.
echo Pendaftar JPAK-26020002 (Askana Sakhi) yang sebelumnya
echo error constraint violation sekarang harus bisa save data
echo orang tua tanpa masalah.
echo.

pause
