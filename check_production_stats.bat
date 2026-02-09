@echo off
echo Checking production database stats...
echo.

ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && psql -U mtsnkot4 -d mtsnkot4_ppdb -c \"SELECT status_pendaftaran, COUNT(*) as count FROM pendaftar GROUP BY status_pendaftaran ORDER BY count DESC;\""

echo.
echo.
echo Checking data_disetujui specifically...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && psql -U mtsnkot4 -d mtsnkot4_ppdb -c \"SELECT COUNT(*) as data_disetujui_count FROM pendaftar WHERE status_pendaftaran = 'data_disetujui';\""

echo.
pause
