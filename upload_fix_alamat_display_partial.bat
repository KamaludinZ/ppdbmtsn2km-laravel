@echo off
echo ============================================
echo UPLOAD FIX ALAMAT DISPLAY LOGIC - PARTIAL
echo ============================================
echo.
echo MASALAH:
echo - Field alamat tersembunyi saat status orangtua bukan "masih_hidup"
echo - Seharusnya field tetap muncul tapi OPTIONAL (tidak wajib diisi)
echo.
echo FIX YANG SUDAH DITERAPKAN:
echo - Section AYAH KANDUNG: Fixed (Luar Negeri + Dalam Negeri)
echo   * Field tetap muncul
echo   * Info box muncul jika status bukan masih_hidup
echo   * Tanda wajib (*) conditional
echo   * Required attribute dihapus
echo.
echo BELUM DIPERBAIKI:
echo - Section IBU KANDUNG: Perlu fix yang sama
echo - Section WALI: Perlu fix yang sama
echo.
echo FILE YANG DIUPLOAD:
echo - resources/views/tab_templates/tab-data-alamat.blade.php
echo.
echo NOTE:
echo Test dulu section AYAH, jika berhasil baru fix Ibu dan Wali
echo dengan pola yang SAMA PERSIS.
echo.
pause

echo.
echo [1/2] Upload tab-data-alamat.blade.php...
scp resources/views/tab_templates/tab-data-alamat.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/tab_templates/
if %errorlevel% neq 0 (
    echo GAGAL upload tab-data-alamat.blade.php
    pause
    exit /b 1
)
echo BERHASIL upload tab-data-alamat.blade.php

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
echo TESTING SECTION AYAH:
echo.
echo 1. TEST DI PENDAFTAR:
echo    - Buka /pendaftar/formulir?activeTab=orangtua
echo    - Ubah Status Ayah ke "Meninggal"
echo    - Save, lalu pindah ke tab Alamat
echo    - Cek: Info box muncul?
echo    - Cek: Field alamat ayah tetap muncul?
echo    - Cek: Tanda (*) hilang?
echo    - Isi atau kosongkan field alamat
echo    - Simpan - harus berhasil
echo.
echo 2. TEST DI ADMIN:
echo    - Detail pendaftar yang status ayah Meninggal
echo    - Tab Alamat - Edit
echo    - Cek: Field tetap muncul + info box muncul
echo    - Simpan - harus berhasil
echo.
echo 3. TEST DI PANITIA:
echo    - Sama seperti Admin
echo.
echo JIKA BERHASIL:
echo - Konfirmasi ke developer untuk fix Ibu dan Wali
echo - Pola fix sama persis dengan Ayah
echo.
pause
