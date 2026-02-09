@echo off
echo ============================================
echo FIX TOGGLE COUNTDOWN DASHBOARD PENDAFTAR
echo ============================================
echo.
echo MASALAH:
echo - Toggle di Countdown Timer tidak muncul normal di production
echo - CSS peer-checked tidak bekerja dengan baik
echo.
echo SOLUSI:
echo - Ganti dari Tailwind peer classes ke Alpine.js
echo - Gunakan @entangle untuk binding yang lebih reliable
echo - Gunakan :class binding Alpine.js untuk kondisional styling
echo - Toggle dengan transform translate-x-7 yang lebih kompatibel
echo.
echo PERUBAHAN:
echo - SEBELUM: Toggle dengan peer-checked classes (Tailwind)
echo - SESUDAH: Toggle dengan Alpine.js + :class binding
echo - Form fields: x-show menggunakan Alpine variable, bukan $wire
echo.
echo FILE YANG AKAN DIUPLOAD:
echo - resources/views/livewire/admin/settings.blade.php
echo.
pause

echo.
echo [1/2] Upload admin settings view...
scp resources/views/livewire/admin/settings.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/
if %errorlevel% neq 0 (
    echo GAGAL upload admin settings view
    pause
    exit /b 1
)
echo BERHASIL upload admin settings view

echo.
echo [2/2] Clear cache production...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan view:clear"
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
echo TESTING TOGGLE:
echo.
echo 1. Buka /admin/settings di production
echo 2. Scroll ke "Pengaturan Umum"
echo 3. Cari "Countdown Timer Dashboard Pendaftar"
echo.
echo EXPECTED BEHAVIOR:
echo.
echo [TOGGLE OFF (Default)]
echo - Toggle berwarna ABU-ABU (gray-300)
echo - Bulatan putih di KIRI
echo - Form fields (Label ^& Deskripsi) TERSEMBUNYI
echo.
echo [KLIK TOGGLE - MENJADI ON]
echo - Toggle berubah HIJAU (emerald-600)
echo - Bulatan putih BERGESER KE KANAN (smooth animation)
echo - Form fields (Label ^& Deskripsi) MUNCUL dengan transisi
echo.
echo [KLIK TOGGLE LAGI - MENJADI OFF]
echo - Toggle kembali ABU-ABU
echo - Bulatan putih KEMBALI KE KIRI
echo - Form fields TERSEMBUNYI lagi
echo.
echo [TEST SAVE]
echo - Toggle ON
echo - Isi Label: "Test Countdown"
echo - Isi Deskripsi: "Test description"
echo - Klik SIMPAN
echo - Refresh halaman
echo - Toggle tetap ON
echo - Label ^& Deskripsi tersimpan
echo.
echo TROUBLESHOOTING:
echo - Jika toggle masih tidak muncul: Clear browser cache
echo - Jika form fields tidak show/hide: Cek console JS error
echo - Jika tidak bisa save: Cek network tab untuk error
echo.
pause
