@echo off
echo ============================================
echo UPLOAD CATATAN PSIKOLOGI COMPLETE
echo ============================================
echo.
echo FITUR BARU:
echo - Admin/Panitia: Kolom catatan psikologi + modal edit
echo - Pendaftar: Display catatan di atas info tes psikologi
echo - Conditional hiding untuk status disetujui
echo.
echo FILES YANG AKAN DIUPLOAD:
echo [1] Migration
echo [2] Model
echo [3] Admin Backend
echo [4] Admin Views (2 files)
echo [5] Panitia Backend
echo [6] Panitia View
echo [7] Pendaftar View
echo.
pause

echo.
echo ========================================
echo STEP 1: UPLOAD MIGRATION
echo ========================================
scp database/migrations/2026_02_07_100000_add_catatan_psikologi_to_tes_psikologi_pendaftar_table.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/database/migrations/
if %errorlevel% neq 0 (
    echo GAGAL upload migration
    pause
    exit /b 1
)
echo BERHASIL upload migration

echo.
echo ========================================
echo STEP 2: UPLOAD MODEL
echo ========================================
scp app/Models/TesPsikologiPendaftar.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Models/
if %errorlevel% neq 0 (
    echo GAGAL upload model
    pause
    exit /b 1
)
echo BERHASIL upload model

echo.
echo ========================================
echo STEP 3: UPLOAD ADMIN BACKEND
echo ========================================
scp app/Livewire/Admin/TesPsikologi.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/
if %errorlevel% neq 0 (
    echo GAGAL upload Admin TesPsikologi.php
    pause
    exit /b 1
)
echo BERHASIL upload Admin TesPsikologi.php

echo.
echo ========================================
echo STEP 4: UPLOAD ADMIN VIEWS
echo ========================================
echo [4a] Uploading admin tes-psikologi.blade.php...
scp resources/views/livewire/admin/tes-psikologi.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/
if %errorlevel% neq 0 (
    echo GAGAL upload admin tes-psikologi.blade.php
    pause
    exit /b 1
)
echo BERHASIL upload admin tes-psikologi.blade.php

echo [4b] Uploading partials/tes-psikologi-data.blade.php...
scp resources/views/livewire/admin/partials/tes-psikologi-data.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/partials/
if %errorlevel% neq 0 (
    echo GAGAL upload partials/tes-psikologi-data.blade.php
    pause
    exit /b 1
)
echo BERHASIL upload partials/tes-psikologi-data.blade.php

echo.
echo ========================================
echo STEP 5: UPLOAD PANITIA BACKEND
echo ========================================
scp app/Livewire/Panitia/TesPsikologi.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Panitia/
if %errorlevel% neq 0 (
    echo GAGAL upload Panitia TesPsikologi.php
    pause
    exit /b 1
)
echo BERHASIL upload Panitia TesPsikologi.php

echo.
echo ========================================
echo STEP 6: UPLOAD PANITIA VIEW
echo ========================================
scp resources/views/livewire/panitia/tes-psikologi.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/panitia/
if %errorlevel% neq 0 (
    echo GAGAL upload panitia tes-psikologi.blade.php
    pause
    exit /b 1
)
echo BERHASIL upload panitia tes-psikologi.blade.php

echo.
echo ========================================
echo STEP 7: UPLOAD PENDAFTAR VIEW
echo ========================================
scp resources/views/livewire/pendaftar/tes-psikologi.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/
if %errorlevel% neq 0 (
    echo GAGAL upload pendaftar tes-psikologi.blade.php
    pause
    exit /b 1
)
echo BERHASIL upload pendaftar tes-psikologi.blade.php

echo.
echo ========================================
echo STEP 8: RUN MIGRATION IN PRODUCTION
echo ========================================
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan migrate --path=database/migrations/2026_02_07_100000_add_catatan_psikologi_to_tes_psikologi_pendaftar_table.php"
if %errorlevel% neq 0 (
    echo WARNING: Migration gagal atau sudah pernah dijalankan
) else (
    echo BERHASIL run migration
)

echo.
echo ========================================
echo STEP 9: CLEAR CACHE PRODUCTION
echo ========================================
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan view:clear && php artisan cache:clear && php artisan config:clear"
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
echo [ADMIN - /admin/tes-psikologi?mainTab=data]
echo 1. Login sebagai Admin
echo 2. Lihat kolom "Catatan Psikologi" di tabel
echo 3. Klik tombol "Edit Catatan" (warna ungu)
echo 4. Modal muncul dengan textarea
echo 5. Isi catatan: "Hasil tes baik, silakan lanjut"
echo 6. Klik "Simpan Catatan"
echo 7. Success message muncul
echo 8. Catatan muncul di tabel (ter-truncate 50 karakter)
echo.
echo [PANITIA - /panitia-psikologi/tes-psikologi?mainTab=data]
echo 1. Login sebagai Panitia Tes Psikologi
echo 2. Test sama seperti Admin
echo.
echo [PENDAFTAR - /pendaftar/tes-psikologi]
echo.
echo A. Test Catatan Display:
echo    1. Admin isi catatan untuk pendaftar
echo    2. Login sebagai pendaftar tersebut
echo    3. Buka /pendaftar/tes-psikologi
echo    4. Box ungu "Catatan Psikologi" muncul di atas
echo    5. Catatan tampil dengan benar
echo.
echo B. Test Conditional Display (Status: disetujui):
echo    1. Admin set status = "disetujui"
echo    2. Refresh halaman pendaftar
echo    3. Yang TERSEMBUNYI:
echo       - Informasi Tes Psikologi
echo       - Informasi Survey
echo       - Informasi Rekening Tujuan Transfer
echo       - Biaya Tes Psikologi
echo    4. Yang MUNCUL:
echo       - Catatan Psikologi (jika ada)
echo       - Status Alert "Disetujui"
echo       - Green box "Bukti Transfer Disetujui"
echo       - Tombol "Lihat Bukti"
echo       - Tombol "Download Invoice"
echo.
echo C. Test Conditional Display (Status: belum_upload):
echo    1. Admin set status = "belum_upload"
echo    2. Refresh halaman pendaftar
echo    3. Semua section MUNCUL kembali:
echo       - Informasi Tes Psikologi
echo       - Informasi Survey
echo       - Informasi Rekening
echo       - Biaya Tes Psikologi
echo       - Form Upload Bukti
echo.
echo TROUBLESHOOTING:
echo - Jika modal tidak muncul: Clear browser cache (Ctrl+Shift+R)
echo - Jika catatan tidak save: Cek network tab untuk error
echo - Jika conditional tidak kerja: Cek status verifikasi di database
echo.
pause
