@echo off
echo ============================================
echo UPLOAD FITUR COUNTDOWN DASHBOARD PENDAFTAR
echo ============================================
echo.
echo FITUR BARU:
echo - Countdown timer di dashboard pendaftar
echo - Hitung mundur waktu pendaftaran tersisa
echo - Bisa ON/OFF dari admin settings
echo - Custom label dan deskripsi
echo.
echo FILE YANG AKAN DIUPLOAD (5 files):
echo 1. app/Livewire/Admin/Settings.php (Backend settings)
echo 2. resources/views/livewire/admin/settings.blade.php (Form admin)
echo 3. resources/views/components/countdown-dashboard-pendaftar.blade.php (NEW - Component)
echo 4. resources/views/components/layouts/dashboard.blade.php (Countdown JS function)
echo 5. resources/views/livewire/pendaftar/dashboard.blade.php (Display countdown)
echo.
echo PENGATURAN:
echo - Location: /admin/settings - Pengaturan Umum
echo - Toggle: ON/OFF countdown
echo - Label: Custom text untuk judul countdown
echo - Deskripsi: Custom text untuk pesan countdown
echo.
pause

echo.
echo [1/6] Upload Admin Settings.php...
scp app/Livewire/Admin/Settings.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/
if %errorlevel% neq 0 (
    echo GAGAL upload Admin Settings.php
    pause
    exit /b 1
)
echo BERHASIL upload Admin Settings.php

echo.
echo [2/6] Upload Admin settings view...
scp resources/views/livewire/admin/settings.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/
if %errorlevel% neq 0 (
    echo GAGAL upload admin settings view
    pause
    exit /b 1
)
echo BERHASIL upload admin settings view

echo.
echo [3/6] Upload countdown component (NEW FILE)...
scp resources/views/components/countdown-dashboard-pendaftar.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/components/
if %errorlevel% neq 0 (
    echo GAGAL upload countdown component
    pause
    exit /b 1
)
echo BERHASIL upload countdown component

echo.
echo [4/6] Upload dashboard layout...
scp resources/views/components/layouts/dashboard.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/components/layouts/
if %errorlevel% neq 0 (
    echo GAGAL upload dashboard layout
    pause
    exit /b 1
)
echo BERHASIL upload dashboard layout

echo.
echo [5/6] Upload pendaftar dashboard...
scp resources/views/livewire/pendaftar/dashboard.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/
if %errorlevel% neq 0 (
    echo GAGAL upload pendaftar dashboard
    pause
    exit /b 1
)
echo BERHASIL upload pendaftar dashboard

echo.
echo [6/6] Clear cache production...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan view:clear && php artisan config:clear"
if %errorlevel% neq 0 (
    echo WARNING: Gagal clear cache, silakan manual
) else (
    echo BERHASIL clear cache
)

echo.
echo ============================================
echo UPLOAD SELESAI!
echo ============================================
echo.
echo TESTING YANG HARUS DILAKUKAN:
echo.
echo 1. TEST ADMIN SETTINGS:
echo    - Login sebagai Admin
echo    - Buka /admin/settings
echo    - Scroll ke "Pengaturan Umum"
echo    - Cari section "Countdown Timer Dashboard Pendaftar"
echo    - Toggle OFF - form fields tersembunyi?
echo    - Toggle ON - form fields muncul?
echo    - Isi Label: "Pendaftaran Segera Berakhir!"
echo    - Isi Deskripsi: "Jangan sampai terlewat!"
echo    - Klik SIMPAN
echo    - Notifikasi sukses muncul?
echo.
echo 2. TEST DASHBOARD PENDAFTAR (TOGGLE ON):
echo    - Login sebagai Pendaftar
echo    - Buka /pendaftar/dashboard
echo    - Countdown timer MUNCUL di atas status pendaftaran?
echo    - Label countdown sesuai yang diset?
echo    - Deskripsi countdown sesuai yang diset?
echo    - Countdown menampilkan: [Hari] : [Jam] : [Menit] : [Detik]
echo    - Angka countdown UPDATE setiap 1 detik?
echo    - Tampilan OK di desktop?
echo    - Tampilan OK di mobile?
echo.
echo 3. TEST DASHBOARD PENDAFTAR (TOGGLE OFF):
echo    - Kembali ke Admin Settings
echo    - Toggle countdown OFF
echo    - Simpan
echo    - Refresh dashboard pendaftar
echo    - Countdown timer TIDAK MUNCUL?
echo.
echo 4. TEST EDGE CASES:
echo    - Set Tanggal Selesai = besok
echo    - Countdown shows days ^> 0?
echo    - Tunggu countdown mendekati 0
echo    - Countdown stops at 00:00:00:00 (tidak minus)?
echo.
echo EXPECTED BEHAVIOR:
echo - Toggle ON + ada End Date = Countdown MUNCUL
echo - Toggle OFF = Countdown TERSEMBUNYI
echo - Tidak ada End Date = Countdown TERSEMBUNYI
echo - Countdown real-time update setiap detik
echo - Countdown responsive (mobile + desktop)
echo.
echo DOKUMENTASI:
echo - FITUR_COUNTDOWN_DASHBOARD_PENDAFTAR_7_FEB_2026.md
echo.
pause
