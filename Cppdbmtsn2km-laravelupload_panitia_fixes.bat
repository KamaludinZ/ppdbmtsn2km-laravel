@echo off
echo =============================================
echo UPLOAD 11 FILE PANITIA - BATCH MODE
echo =============================================
echo.

echo [1/11] Uploading Dashboard.php...
scp app\Livewire\Panitia\Dashboard.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Panitia/Dashboard.php

echo [2/11] Uploading DataAsalSekolah.php...
scp app\Livewire\Panitia\DataAsalSekolah.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Panitia/DataAsalSekolah.php

echo [3/11] Uploading DetailPendaftar.php...
scp app\Livewire\Panitia\DetailPendaftar.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Panitia/DetailPendaftar.php

echo [4/11] Uploading Jadwal.php...
scp app\Livewire\Panitia\Jadwal.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Panitia/Jadwal.php

echo [5/11] Uploading Jalur.php...
scp app\Livewire\Panitia\Jalur.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Panitia/Jalur.php

echo [6/11] Uploading Kelulusan.php...
scp app\Livewire\Panitia\Kelulusan.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Panitia/Kelulusan.php

echo [7/11] Uploading Laporan.php...
scp app\Livewire\Panitia\Laporan.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Panitia/Laporan.php

echo [8/11] Uploading Pendaftar.php...
scp app\Livewire\Panitia\Pendaftar.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Panitia/Pendaftar.php

echo [9/11] Uploading Pengumuman.php...
scp app\Livewire\Panitia\Pengumuman.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Panitia/Pengumuman.php

echo [10/11] Uploading DaftarUlang.php...
scp app\Livewire\Panitia\DaftarUlang.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Panitia/DaftarUlang.php

echo [11/11] Uploading TesPsikologi.php...
scp app\Livewire\Panitia\TesPsikologi.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Panitia/TesPsikologi.php

echo.
echo =============================================
echo SEMUA FILE BERHASIL DIUPLOAD
echo =============================================
echo.
echo Sekarang clear cache di server dengan command:
echo ssh mtsnkot4@103.30.244.77
echo cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
echo php artisan optimize:clear
echo.

pause
