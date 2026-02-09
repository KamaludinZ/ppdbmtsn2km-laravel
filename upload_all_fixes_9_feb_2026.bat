@echo off
REM ============================================================
REM UPLOAD SCRIPT - ALL FIXES 9 FEB 2026
REM ============================================================
REM This script uploads all changes made on 9 Feb 2026:
REM - Fix Paket Seragam JSON display
REM - Fix Kelulusan stats (menunggu -> data disetujui)
REM - Fix Daftar Ulang stats (add Total Pendaftar & Lulus)
REM - Add RTE to Pengumuman (Admin, Panitia, Public)
REM - Sync Panitia-Seragam Settings (backend & frontend)
REM - Sync Panitia Madrasah Settings (backend & frontend)
REM ============================================================

echo ============================================================
echo UPLOAD ALL FIXES - 9 FEBRUARI 2026
echo ============================================================
echo.

REM ========== BACKEND FILES ==========
echo [1/17] Uploading Admin DaftarUlang backend (stats fix)...
scp "C:\ppdbmtsn2km-laravel\app\Livewire\Admin\DaftarUlang.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/DaftarUlang.php

echo [2/17] Uploading Admin Kelulusan backend (stats fix)...
scp "C:\ppdbmtsn2km-laravel\app\Livewire\Admin\Kelulusan.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/Kelulusan.php

echo [3/17] Uploading Panitia Kelulusan backend (stats fix)...
scp "C:\ppdbmtsn2km-laravel\app\Livewire\Panitia\Kelulusan.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Panitia/Kelulusan.php

echo [4/17] Uploading PanitiaSeragam DaftarUlang backend (settings)...
scp "C:\ppdbmtsn2km-laravel\app\Livewire\PanitiaSeragam\DaftarUlang.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/PanitiaSeragam/DaftarUlang.php

echo [5/17] Uploading Panitia DaftarUlang backend (Madrasah settings)...
scp "C:\ppdbmtsn2km-laravel\app\Livewire\Panitia\DaftarUlang.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Panitia/DaftarUlang.php

echo [6/17] Uploading TextFormatter helper (RTE support)...
scp "C:\ppdbmtsn2km-laravel\app\Helpers\TextFormatter.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Helpers/TextFormatter.php

REM ========== FRONTEND FILES ==========
echo [7/17] Uploading Admin DaftarUlang view (JSON fix)...
scp "C:\ppdbmtsn2km-laravel\resources\views\livewire\admin\daftar-ulang.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/daftar-ulang.blade.php

echo [8/17] Uploading Admin Kelulusan view (stats display)...
scp "C:\ppdbmtsn2km-laravel\resources\views\livewire\admin\kelulusan.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/kelulusan.blade.php

echo [9/17] Uploading Daftar Ulang stats partial...
scp "C:\ppdbmtsn2km-laravel\resources\views\livewire\admin\partials\daftar-ulang-stats.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/partials/daftar-ulang-stats.blade.php

echo [10/17] Uploading Admin Pengumuman view (RTE)...
scp "C:\ppdbmtsn2km-laravel\resources\views\livewire\admin\pengumuman.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/pengumuman.blade.php

echo [11/17] Uploading Panitia Kelulusan view (stats display)...
scp "C:\ppdbmtsn2km-laravel\resources\views\livewire\panitia\kelulusan.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/panitia/kelulusan.blade.php

echo [12/17] Uploading Panitia Pengumuman view (RTE)...
scp "C:\ppdbmtsn2km-laravel\resources\views\livewire\panitia\pengumuman.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/panitia/pengumuman.blade.php

echo [13/17] Uploading PanitiaSeragam DaftarUlang view (Settings tab)...
scp "C:\ppdbmtsn2km-laravel\resources\views\livewire\panitia-seragam\daftar-ulang.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/panitia-seragam/daftar-ulang.blade.php

echo [14/17] Uploading Panitia DaftarUlang view (Settings tab)...
scp "C:\ppdbmtsn2km-laravel\resources\views\livewire\panitia\daftar-ulang.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/panitia/daftar-ulang.blade.php

echo [15/17] Uploading Public Pengumuman view (HTML render)...
scp "C:\ppdbmtsn2km-laravel\resources\views\public\pengumuman.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/public/pengumuman.blade.php

REM ========== NEW PARTIALS ==========
echo [16/17] Uploading PanitiaSeragam Settings partial (NEW)...
scp "C:\ppdbmtsn2km-laravel\resources\views\livewire\panitia-seragam\partials\settings-tab.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/panitia-seragam/partials/settings-tab.blade.php

echo [17/17] Uploading Panitia Madrasah Settings partial (NEW)...
scp "C:\ppdbmtsn2km-laravel\resources\views\livewire\panitia\partials\settings-madrasah-tab.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/panitia/partials/settings-madrasah-tab.blade.php

echo.
echo ============================================================
echo CLEARING CACHE ON PRODUCTION...
echo ============================================================
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan optimize:clear && php artisan view:clear && php artisan config:clear && php artisan cache:clear"

echo.
echo ============================================================
echo UPLOAD COMPLETED SUCCESSFULLY!
echo ============================================================
echo.
echo Summary:
echo - 6 Backend files uploaded
echo - 9 Frontend files uploaded
echo - 2 New partials uploaded
echo - Cache cleared
echo.
echo IMPORTANT REMINDER:
echo - Test Paket Seragam display in both Admin and Panitia-Seragam
echo - Test Kelulusan stats display (Data Disetujui, not Menunggu)
echo - Test Daftar Ulang stats (Total Pendaftar and Lulus as separate cards)
echo - Test RTE in Pengumuman (Admin, Panitia, and Public display)
echo - Test Settings tabs in Panitia-Seragam and Panitia
echo.
pause
