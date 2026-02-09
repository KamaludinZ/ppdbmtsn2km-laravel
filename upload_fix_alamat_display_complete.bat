@echo off
echo ============================================
echo UPLOAD FIX ALAMAT DISPLAY LOGIC - COMPLETE
echo ============================================
echo.
echo MASALAH YANG SUDAH DIPERBAIKI:
echo - Field alamat yang tersembunyi saat status bukan "masih_hidup"
echo - Field sekarang TETAP MUNCUL tapi OPTIONAL
echo - Info box pindah ke ATAS
echo - Required mark (*) jadi conditional
echo.
echo SECTION YANG SUDAH DIPERBAIKI:
echo [AYAH]
echo   - Luar Negeri: Fixed (2 fields)
echo   - Dalam Negeri: Fixed (9 fields)
echo.
echo [IBU]
echo   - Luar Negeri: Fixed (2 fields)
echo   - Dalam Negeri: Fixed (9 fields)
echo.
echo [WALI]
echo   - Tidak perlu fix (tidak ada konsep status Meninggal)
echo.
echo TOTAL: 22 fields diperbaiki
echo.
echo FILE YANG AKAN DIUPLOAD:
echo - resources/views/tab_templates/tab-data-alamat.blade.php
echo.
echo USER TESTING:
echo - Section AYAH sudah di-test: "Biak sudah saya tes dan berhasil"
echo - Section IBU menggunakan pattern yang sama
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
echo TESTING YANG HARUS DILAKUKAN:
echo.
echo 1. TEST DI PENDAFTAR:
echo    a. Login sebagai Pendaftar
echo    b. /pendaftar/formulir?activeTab=orangtua
echo    c. Ubah Status Ayah ke "Meninggal"
echo    d. Save, pindah ke tab Alamat
echo    e. Cek:
echo       - Info box muncul?
echo       - Field alamat tetap muncul?
echo       - Tanda (*) hilang?
echo       - Bisa simpan tanpa isi alamat?
echo.
echo 2. TEST UNTUK IBU:
echo    a. Ubah Status Ibu ke "Tidak Diketahui"
echo    b. Save, pindah ke tab Alamat
echo    c. Cek section Ibu - behavior sama seperti Ayah
echo.
echo 3. TEST DI ADMIN:
echo    a. Login sebagai Admin
echo    b. Detail pendaftar dengan status ayah Meninggal
echo    c. Tab Alamat - Edit
echo    d. Cek: Field tetap muncul + info box muncul
echo    e. Simpan - harus berhasil
echo.
echo 4. TEST DI PANITIA:
echo    a. Login sebagai Panitia
echo    b. Detail pendaftar
echo    c. Tab Alamat - Edit
echo    d. Test sama seperti Admin
echo.
echo 5. TEST KOMBINASI (IBU):
echo    a. Status Ibu: Masih Hidup
echo    b. Checkbox "Sama dengan Ayah": Checked
echo    c. Cek: Field disabled + tidak ada (*)
echo    d. Uncheck
echo    e. Cek: Field enabled + ada (*)
echo    f. Ubah Status Ibu ke "Meninggal"
echo    g. Cek: Field muncul + tidak ada (*)
echo.
echo EXPECTED BEHAVIOR:
echo.
echo | Status        | Info Box | Field | Tanda (*) | Wajib? |
echo |---------------|----------|-------|-----------|--------|
echo | Masih Hidup   | Tidak    | Ya    | Ya        | Ya     |
echo | Meninggal     | Ya       | Ya    | Tidak     | Tidak  |
echo | Tidak Diketah | Ya       | Ya    | Tidak     | Tidak  |
echo.
echo DOKUMENTASI:
echo - FIX_ALAMAT_DISPLAY_COMPLETE_7_FEB_2026.md
echo.
pause
