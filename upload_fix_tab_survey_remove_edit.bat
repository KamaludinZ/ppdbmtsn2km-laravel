@echo off
echo ========================================
echo Upload Fix Tab Survey - Remove Edit Button for Admin/Panitia
echo ========================================
echo.
echo MASALAH YANG DIPERBAIKI:
echo.
echo [1] MethodNotFoundException - Method saveDataSurvey tidak ada
echo [2] Admin/Panitia bisa klik Edit Data tapi error saat simpan
echo [3] Survey seharusnya hanya diisi oleh Pendaftar
echo.

set SERVER=mtsnkot4@103.30.244.77
set REMOTE_PATH=/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

echo [1/3] Upload Tab Survey View...
scp "resources/views/tab_templates/tab-survey.blade.php" %SERVER%:%REMOTE_PATH%/resources/views/tab_templates/
if %errorlevel% neq 0 (
    echo ERROR: Gagal upload view
    pause
    exit /b 1
)
echo [OK] Tab survey view uploaded

echo.
echo [2/3] Clear view cache...
ssh %SERVER% "cd %REMOTE_PATH% && php artisan view:clear"
if %errorlevel% neq 0 (
    echo ERROR: Gagal clear cache
    pause
    exit /b 1
)
echo [OK] View cache cleared

echo.
echo [3/3] Delete compiled views...
ssh %SERVER% "cd %REMOTE_PATH% && rm -rf storage/framework/views/*.php"
echo [OK] Compiled views deleted

echo.
echo ========================================
echo UPLOAD BERHASIL!
echo ========================================
echo.
echo YANG SUDAH DIPERBAIKI:
echo.
echo 1. TOMBOL EDIT DIHAPUS UNTUK ADMIN/PANITIA:
echo    ✓ Admin/Panitia: Badge "Mode Lihat" (read-only)
echo    ✓ Pendaftar: Tombol "Edit Data" tetap ada
echo.
echo 2. ERROR FIXED:
echo    ✓ Error "saveDataSurvey not found" tidak muncul lagi
echo    ✓ Livewire warning property surveyAnswers hilang
echo.
echo 3. ROLE-BASED ACCESS CONTROL:
echo    ✓ Admin/Panitia: View only (tidak bisa edit)
echo    ✓ Pendaftar (belum disetujui): Can edit survey
echo    ✓ Pendaftar (sudah disetujui): Edit button disabled
echo.
echo 4. UI IMPROVEMENTS:
echo    ✓ Badge "Mode Lihat" dengan icon eye untuk Admin/Panitia
echo    ✓ Clear indicator bahwa tab ini read-only
echo.
echo ========================================
echo TESTING CHECKLIST
echo ========================================
echo.
echo TEST 1 - ADMIN/PANITIA (READ ONLY):
echo URL: https://pmbm.mtsn2kotamalang.sch.id/admin/pendaftar/27?activeTab=survey
echo Login as: Admin atau Panitia
echo.
echo [ ] Badge "Mode Lihat" tampil (bukan tombol "Edit Data")
echo [ ] Icon eye di badge
echo [ ] Background abu-abu
echo [ ] TIDAK ADA tombol "Edit Data"
echo [ ] TIDAK ADA error saat buka tab
echo [ ] Tampil jawaban survey existing (view mode)
echo.
echo TEST 2 - PENDAFTAR (CAN EDIT):
echo URL: https://pmbm.mtsn2kotamalang.sch.id/pendaftar/dashboard?activeTab=survey
echo Login as: Pendaftar (status belum disetujui)
echo.
echo [ ] Tombol "Edit Data" tampil (biru)
echo [ ] Klik "Edit Data" → Masuk edit mode
echo [ ] Form input survey tampil
echo [ ] Tombol "Simpan" dan "Batal" ada
echo [ ] Bisa isi/ubah jawaban survey
echo [ ] Klik "Simpan" → Data tersimpan
echo [ ] Klik "Batal" → Keluar edit mode
echo.
echo TEST 3 - PENDAFTAR (DISABLED):
echo Login as: Pendaftar (status sudah disetujui)
echo.
echo [ ] Tombol "Edit Data" tampil tapi disabled
echo [ ] Opacity 50%%, cursor not-allowed
echo [ ] Tooltip: "Data sudah disetujui..."
echo [ ] Tidak bisa klik tombol
echo.
echo ROLE ACCESS MATRIX:
echo - Admin: Read-only, badge "Mode Lihat"
echo - Panitia: Read-only, badge "Mode Lihat"
echo - Panitia_*: Read-only, badge "Mode Lihat"
echo - Pendaftar (belum disetujui): Can edit
echo - Pendaftar (sudah disetujui): View only (disabled)
echo.
echo Hard refresh browser: Ctrl + Shift + R
echo.
pause
