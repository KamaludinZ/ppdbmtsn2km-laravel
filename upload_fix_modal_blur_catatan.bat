@echo off
echo ============================================
echo FIX MODAL BLUR CATATAN
echo ============================================
echo.
echo PERBAIKAN:
echo - Modal edit catatan sekarang menggunakan backdrop blur
echo - Z-index konsisten dengan modal lain (z-[9999])
echo - Background overlay: bg-black/30 backdrop-blur-sm
echo - Modal panel: relative z-[10000]
echo.
echo FILES YANG AKAN DIUPLOAD:
echo [1] Admin tes-psikologi.blade.php
echo [2] Panitia tes-psikologi.blade.php
echo.
pause

echo.
echo ========================================
echo UPLOAD ADMIN VIEW
echo ========================================
scp resources/views/livewire/admin/tes-psikologi.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/
if %errorlevel% neq 0 (
    echo GAGAL upload admin view
    pause
    exit /b 1
)
echo BERHASIL upload admin view

echo.
echo ========================================
echo UPLOAD PANITIA VIEW
echo ========================================
scp resources/views/livewire/panitia/tes-psikologi.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/panitia/
if %errorlevel% neq 0 (
    echo GAGAL upload panitia view
    pause
    exit /b 1
)
echo BERHASIL upload panitia view

echo.
echo ========================================
echo CLEAR CACHE PRODUCTION
echo ========================================
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
echo TESTING CHECKLIST:
echo.
echo 1. Login sebagai Admin
echo 2. Buka /admin/tes-psikologi?mainTab=data
echo 3. Klik tombol "Edit Catatan" (warna ungu)
echo 4. PERHATIKAN MODAL:
echo    [x] Background ada blur effect (backdrop-blur-sm)
echo    [x] Background hitam transparan (bg-black/30)
echo    [x] Modal berada di paling depan (tidak tertutup apapun)
echo    [x] Modal center secara vertikal
echo 5. Click di luar modal (area blur) untuk close
echo 6. Modal harus close dengan smooth
echo.
echo 7. Test juga di Panitia:
echo    - Login sebagai Panitia Tes Psikologi
echo    - Buka /panitia-psikologi/tes-psikologi?mainTab=data
echo    - Test sama seperti Admin
echo.
echo VISUAL COMPARISON:
echo - BEFORE: Gray overlay tanpa blur, z-index rendah
echo - AFTER: Black transparent + blur effect, z-index tinggi
echo.
echo NOTE: Jika masih belum blur, coba:
echo - Clear browser cache (Ctrl + Shift + R)
echo - Hard refresh (Ctrl + F5)
echo.
pause
