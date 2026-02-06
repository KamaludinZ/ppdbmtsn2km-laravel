@echo off
echo ============================================================
echo UPLOAD FIX - TEST EMAIL DAN WHATSAPP KE HISTORY
echo ============================================================
echo.
echo File yang akan diupload:
echo - app/Livewire/Admin/Settings.php (UPDATED)
echo.
echo Perubahan yang dibuat:
echo 1. Fungsi sendTestEmail() sekarang menyimpan ke riwayat_pesan
echo 2. Fungsi sendTestWhatsApp() sekarang menyimpan ke riwayat_pesan
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
echo 2. Test Email:
echo    - Login sebagai Admin
echo    - Buka /admin/settings
echo    - Scroll ke bagian Email Settings
echo    - Klik tombol "Kirim Email Percobaan"
echo    - Cek history muncul di /admin/pengaturan-pesan?activeTab=history-email
echo.
echo 3. Test WhatsApp:
echo    - Di /admin/settings, scroll ke bagian WhatsApp Settings
echo    - Klik tombol "Kirim Pesan Percobaan"
echo    - Cek history muncul di /admin/pengaturan-pesan?activeTab=history-whatsapp
echo.
echo ============================================================
echo UPLOAD SELESAI!
echo ============================================================
pause
