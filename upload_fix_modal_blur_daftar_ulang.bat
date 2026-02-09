@echo off
echo ============================================
echo FIX MODAL BLUR DAFTAR ULANG
echo ============================================
echo.
echo PERBAIKAN:
echo - Modal "Alasan Tidak Setuju Daftar Ulang" sekarang menggunakan backdrop blur
echo - Z-index konsisten dengan modal lain (z-[9999])
echo - Background overlay: bg-black/30 backdrop-blur-sm
echo - Modal panel: relative z-[10000]
echo.
echo LOKASI MODAL:
echo - Modal muncul saat klik tombol "Tidak Melanjutkan Daftar Ulang"
echo - Di halaman /pendaftar/dashboard
echo - Pada bagian "SELAMAT! Anda LULUS Seleksi"
echo.
pause

echo.
echo ========================================
echo UPLOAD DASHBOARD VIEW
echo ========================================
scp resources/views/livewire/pendaftar/dashboard.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/
if %errorlevel% neq 0 (
    echo GAGAL upload dashboard view
    pause
    exit /b 1
)
echo BERHASIL upload dashboard view

echo.
echo ========================================
echo CLEAR VIEW CACHE
echo ========================================
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan view:clear"
if %errorlevel% neq 0 (
    echo WARNING: Gagal clear cache
) else (
    echo BERHASIL clear cache
)

echo.
echo ============================================
echo UPLOAD SELESAI!
echo ============================================
echo.
echo TESTING CHECKLIST:
echo.
echo 1. Login sebagai Pendaftar dengan status "lulus"
echo 2. Buka /pendaftar/dashboard
echo 3. Scroll ke bagian "SELAMAT! Anda LULUS Seleksi" (warna orange)
echo 4. Klik tombol "Tidak Melanjutkan Daftar Ulang"
echo.
echo 5. PERHATIKAN MODAL:
echo    [x] Background ada blur effect (backdrop-blur-sm)
echo    [x] Background hitam transparan (bg-black/30)
echo    [x] Modal berada di paling depan (tidak tertutup apapun)
echo    [x] Modal center secara vertikal
echo.
echo 6. Pilih salah satu alasan (radio button)
echo 7. Click di luar modal (area blur) untuk close
echo 8. Modal harus close dengan smooth
echo.
echo 9. Test juga tombol:
echo    - "Konfirmasi Tidak Melanjutkan" harus kirim data
echo    - "Batal" harus close modal tanpa kirim data
echo.
echo VISUAL COMPARISON:
echo - BEFORE: Dark gray overlay tanpa blur (bg-gray-900 bg-opacity-75)
echo - AFTER: Black transparent + blur effect (bg-black/30 backdrop-blur-sm)
echo.
echo NOTE:
echo - Tombol "Saya Setuju Daftar Ulang" menggunakan confirm() biasa (bukan modal)
echo - Tombol "Tidak Melanjutkan Daftar Ulang" menggunakan modal Livewire (sekarang sudah blur)
echo.
pause
