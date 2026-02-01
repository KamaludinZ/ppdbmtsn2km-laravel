@echo off
echo =============================================
echo VERIFIKASI FILE DI PRODUCTION
echo =============================================
echo.

echo Cek file Dashboard.php di production...
ssh mtsnkot4@103.30.244.77 "grep -n 'panitia_seragam' /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Panitia/Dashboard.php | head -5"

echo.
echo Jika muncul baris dengan "panitia_seragam" berarti file sudah terupload.
echo Jika tidak muncul apa-apa, berarti file belum terupload dengan benar.
echo.

pause
