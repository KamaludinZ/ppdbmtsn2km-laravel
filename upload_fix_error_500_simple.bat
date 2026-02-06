@echo off
echo ============================================================
echo FIX ERROR 500 - TEST EMAIL DAN WHATSAPP (SIMPLE SOLUTION)
echo ============================================================
echo.
echo Masalah: Field pendaftar_id tidak nullable
echo Solusi: Gunakan ID pendaftar pertama untuk testing
echo.
echo Keuntungan:
echo - Tidak perlu alter table
echo - Tidak perlu migration
echo - Langsung bisa dipakai
echo.
echo ============================================================
pause

echo.
echo [1/1] Uploading Settings.php...
scp app/Livewire/Admin/Settings.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/
if errorlevel 1 (
    echo ERROR: Gagal upload Settings.php
    pause
    exit /b 1
)
echo SUCCESS: Settings.php uploaded

echo.
echo ============================================================
echo LANGKAH SELANJUTNYA DI PRODUCTION:
echo ============================================================
echo.
echo 1. Clear cache:
echo    ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan optimize:clear"
echo.
echo 2. Test Email di /admin/settings
echo    - Kirim Email Percobaan
echo    - Harus berhasil tanpa error 500
echo    - Cek history di /admin/pengaturan-pesan?activeTab=history-email
echo.
echo 3. Test WhatsApp di /admin/settings
echo    - Kirim Pesan Percobaan
echo    - Harus berhasil tanpa error 500
echo    - Cek history di /admin/pengaturan-pesan?activeTab=history-whatsapp
echo.
echo ============================================================
echo CATATAN:
echo History test akan menggunakan nama pendaftar pertama
echo di kolom Pendaftar, tapi penerima tetap sesuai yang diinput
echo ============================================================
pause
