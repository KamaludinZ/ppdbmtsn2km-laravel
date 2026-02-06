@echo off
echo ============================================================
echo QUICK FIX - Error 500 History Pesan
echo ============================================================
echo.
echo Kemungkinan besar: Migration belum dijalankan
echo.
echo LANGKAH:
echo 1. Backup database (sangat penting!)
echo 2. Jalankan migration
echo 3. Clear caches
echo.
echo Tekan Ctrl+C untuk batal, atau
pause

echo.
echo [1/3] Backup database...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && pg_dump -U mtsnkot4 -d mtsnkot4_ppdb > backup_fix_error_500_$(date +%%Y%%m%%d_%%H%%M%%S).sql"
echo Backup complete!
echo.

echo [2/3] Running migration...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan migrate --force"
echo.

echo [3/3] Clearing all caches...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan optimize:clear && php artisan view:clear"
echo.

echo ============================================================
echo FIX COMPLETE!
echo ============================================================
echo.
echo Sekarang test di browser:
echo 1. Clear browser cache (Ctrl+Shift+Del)
echo 2. Buka /admin/pengaturan-pesan
echo 3. Klik tab "History Email" - harus muncul tanpa error
echo 4. Klik tab "History WhatsApp" - harus muncul tanpa error
echo.
echo Jika masih error, jalankan: debug_production_history_pesan.bat
echo Atau baca: QUICK_FIX_ERROR_500_HISTORY_PESAN.md
echo.
pause
