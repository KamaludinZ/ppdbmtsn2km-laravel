@echo off
echo ========================================
echo UPLOAD POSTAL CODES DATA KE PRODUCTION
echo ========================================
echo.
echo File yang akan di-upload:
echo - database/seeders/PostalCodeSeeder.php (seeder class)
echo - database/seeders/sql/postal_codes.sql (data SQL - 4.71 MB)
echo.
echo Total data: 81,248 kode pos seluruh Indonesia
echo.
pause
echo.

echo [1/4] Upload PostalCodeSeeder.php...
scp "C:\ppdbmtsn2km-laravel\database\seeders\PostalCodeSeeder.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/database/seeders/
if %errorlevel% neq 0 (
    echo ERROR: Upload seeder gagal!
    pause
    exit /b 1
)
echo ✓ Seeder uploaded
echo.

echo [2/4] Buat folder sql di server...
ssh mtsnkot4@103.30.244.77 "mkdir -p /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/database/seeders/sql"
echo ✓ Folder created
echo.

echo [3/4] Upload postal_codes.sql (4.71 MB - mungkin butuh waktu)...
scp "C:\ppdbmtsn2km-laravel\database\seeders\sql\postal_codes.sql" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/database/seeders/sql/
if %errorlevel% neq 0 (
    echo ERROR: Upload SQL file gagal!
    pause
    exit /b 1
)
echo ✓ SQL file uploaded
echo.

echo [4/4] Jalankan seeder di production...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan db:seed --class=PostalCodeSeeder"
if %errorlevel% neq 0 (
    echo WARNING: Seeder mungkin gagal, cek error di atas
) else (
    echo ✓ Seeder executed successfully
)
echo.

echo ========================================
echo SELESAI
echo ========================================
echo.
echo Kode pos sekarang akan terisi otomatis saat memilih kelurahan!
echo Test di: /pendaftar/formulir?activeTab=alamat
echo.
pause
