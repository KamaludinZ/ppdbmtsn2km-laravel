@echo off
echo ========================================
echo   Check Constraint Violations
echo ========================================
echo.
echo Checking recent constraint violations in production log...
echo.

ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && tail -100 storage/logs/laravel.log | grep -B 5 'Check violation' | grep -E 'constraint|Failing row'"

echo.
echo ========================================
echo   Check Pendidikan Values in Database
echo ========================================
echo.

ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && psql -U mtsnkot4 -d mtsnkot4_ppdb -c \"SELECT DISTINCT ayah_pendidikan FROM pendaftar WHERE ayah_pendidikan IS NOT NULL;\""

echo.
echo.

ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && psql -U mtsnkot4 -d mtsnkot4_ppdb -c \"SELECT DISTINCT ibu_pendidikan FROM pendaftar WHERE ibu_pendidikan IS NOT NULL;\""

echo.
echo ========================================
echo   Check All Constraints on Pendaftar
echo ========================================
echo.

ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && psql -U mtsnkot4 -d mtsnkot4_ppdb -c \"SELECT conname FROM pg_constraint WHERE conrelid = 'pendaftar'::regclass AND contype = 'c' ORDER BY conname;\""

pause
