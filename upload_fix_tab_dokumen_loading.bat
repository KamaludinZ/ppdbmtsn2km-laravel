@echo off
echo ========================================
echo Upload Fix Tab Dokumen - Loading State per Button
echo ========================================
echo.
echo MASALAH YANG DIPERBAIKI:
echo.
echo [1] Klik "Simpan Status" di satu dokumen = SEMUA tombol show loading
echo [2] User tidak bisa membedakan tombol mana yang sedang diproses
echo [3] UX buruk - tombol lain disabled padahal tidak sedang diproses
echo.

set SERVER=mtsnkot4@103.30.244.77
set REMOTE_PATH=/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

echo [1/3] Upload Admin Detail Pendaftar View...
scp "resources/views/livewire/admin/detail-pendaftar.blade.php" %SERVER%:%REMOTE_PATH%/resources/views/livewire/admin/
if %errorlevel% neq 0 (
    echo ERROR: Gagal upload view
    pause
    exit /b 1
)
echo [OK] Admin detail pendaftar view uploaded

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
echo 1. WIRE:TARGET SPESIFIK PER DOKUMEN:
echo    ✓ Before: wire:target="updateStatusDokumen"
echo    ✓ After:  wire:target="updateStatusDokumen('kk')" (example)
echo    ✓ Include document key in target
echo.
echo 2. LOADING STATE INDEPENDENT:
echo    ✓ Klik "Simpan Status" Dokumen KK → Hanya button KK show loading
echo    ✓ Button dokumen lain tetap normal
echo    ✓ Button dokumen lain tetap interactive
echo.
echo 3. UX IMPROVEMENTS:
echo    ✓ Clear feedback per button action
echo    ✓ User bisa membedakan proses mana yang running
echo    ✓ Other buttons remain clickable
echo.
echo ========================================
echo TESTING CHECKLIST
echo ========================================
echo.
echo URL: https://pmbm.mtsn2kotamalang.sch.id/admin/pendaftar/27?activeTab=dokumen
echo.
echo TEST 1 - SINGLE BUTTON LOADING:
echo [ ] Tab Dokumen terbuka
echo [ ] Ada minimal 3 dokumen visible
echo [ ] Klik "Simpan Status" di dokumen pertama (KK)
echo.
echo EXPECTED - Button yang diklik:
echo [ ] Text berubah: "Simpan Status" → "Menyimpan..."
echo [ ] Icon berubah: checkmark → spinning loader
echo [ ] Button disabled (opacity 50%%)
echo.
echo EXPECTED - Button lain:
echo [ ] Tetap menunjukkan "Simpan Status"
echo [ ] TIDAK menunjukkan "Menyimpan..."
echo [ ] Tetap interactive (bisa diklik)
echo [ ] TIDAK disabled
echo.
echo TEST 2 - MULTIPLE DOCUMENTS:
echo [ ] Tunggu request pertama selesai
echo [ ] Klik "Simpan Status" di dokumen kedua (Akta)
echo [ ] Hanya button dokumen kedua yang show loading
echo [ ] Button lain (termasuk pertama) tetap normal
echo.
echo TEST 3 - RAPID CLICKS:
echo [ ] Coba klik beberapa tombol cepat
echo [ ] Setiap button show loading masing-masing
echo [ ] Tidak ada konflik antar button
echo [ ] Loading clear setelah masing-masing selesai
echo.
echo BEFORE vs AFTER:
echo.
echo BEFORE:
echo   User: Klik "Simpan Status" dokumen KK
echo   App:  ALL buttons → "Menyimpan..." ❌
echo   UX:   Confusing, cannot tell which processing
echo.
echo AFTER:
echo   User: Klik "Simpan Status" dokumen KK
echo   App:  ONLY KK button → "Menyimpan..." ✓
echo   UX:   Clear feedback, other buttons clickable
echo.
echo Hard refresh browser: Ctrl + Shift + R
echo.
pause
