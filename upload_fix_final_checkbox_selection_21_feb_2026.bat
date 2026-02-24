@echo off
echo ========================================
echo Upload COMPLETE - Mode Opsi Seragam ^& Fixes
echo Tanggal: 21 Februari 2026
echo ========================================
echo.
echo PERBAIKAN LENGKAP:
echo.
echo A. PENDAFTAR (3 fixes):
echo 1. Fix checkbox: hanya tercentang satu per satu (ID-based comparison)
echo 2. Fix konfirmasi nominal: total sum dari checkbox terpilih
echo 3. Fix rangkuman opsi paket di status menunggu (multi-select display)
echo.
echo B. PANITIA SERAGAM (2 additions):
echo 4. Settings: dropdown mode opsi (radio/checkbox)
echo 5. Kolom nominal biaya: multi-select display dengan total
echo.
echo C. DATABASE FIX (1 critical):
echo 6. Fix paket_seragam VARCHAR(255) ^-^> TEXT (mengatasi error 500)
echo.

REM Upload file yang diperbaiki
echo [1/6] Uploading Pendaftar Daftar Ulang Blade View (Alpine.js ^+ rangkuman)...
timeout /t 60 /nobreak >nul
scp "C:\ppdbmtsn2km-laravel\resources\views\livewire\pendaftar\daftar-ulang.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/

echo.
echo [2/6] Uploading Panitia Seragam Settings Tab Partial...
timeout /t 60 /nobreak >nul
scp "C:\ppdbmtsn2km-laravel\resources\views\livewire\panitia-seragam\partials\settings-tab.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/panitia-seragam/partials/

echo.
echo [3/6] Uploading Panitia Seragam Daftar Ulang Main View...
timeout /t 60 /nobreak >nul
scp "C:\ppdbmtsn2km-laravel\resources\views\livewire\panitia-seragam\daftar-ulang.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/panitia-seragam/

echo.
echo [4/6] Uploading Migration File (DATABASE FIX - CRITICAL)...
timeout /t 30 /nobreak >nul
scp "C:\ppdbmtsn2km-laravel\database\migrations\2026_02_21_100000_change_paket_seragam_to_text.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/database/migrations/

echo.
echo [5/6] Uploading Dokumentasi (6 docs)...
timeout /t 30 /nobreak >nul
scp "C:\ppdbmtsn2km-laravel\FIX_FINAL_CHECKBOX_SELECTION_21_FEB_2026.md" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/
scp "C:\ppdbmtsn2km-laravel\FIX_RANGKUMAN_OPSI_PAKET_SERAGAM_21_FEB_2026.md" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/
scp "C:\ppdbmtsn2km-laravel\RINGKASAN_FIX_FINAL_21_FEB_2026.md" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/
scp "C:\ppdbmtsn2km-laravel\ADD_MODE_OPSI_PANITIA_SERAGAM_21_FEB_2026.md" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/
scp "C:\ppdbmtsn2km-laravel\FIX_DATABASE_PAKET_SERAGAM_VARCHAR_21_FEB_2026.md" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/

echo.
echo [6/6] Uploading Summary Complete...
timeout /t 30 /nobreak >nul
scp "C:\ppdbmtsn2km-laravel\SUMMARY_COMPLETE_MODE_OPSI_21_FEB_2026.md" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/

echo.
echo ========================================
echo CRITICAL: Running Migration di Server
echo ========================================
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan migrate --force"

echo.
echo Running cache clear di server...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan optimize:clear"

echo.
echo ========================================
echo Upload FIX FINAL Selesai!
echo ========================================
echo.
echo File yang diupload:
echo.
echo === PENDAFTAR ===
echo 1. resources/views/livewire/pendaftar/daftar-ulang.blade.php
echo.
echo === PANITIA SERAGAM ===
echo 2. resources/views/livewire/panitia-seragam/partials/settings-tab.blade.php
echo 3. resources/views/livewire/panitia-seragam/daftar-ulang.blade.php
echo.
echo === DATABASE (CRITICAL) ===
echo 4. database/migrations/2026_02_21_100000_change_paket_seragam_to_text.php
echo.
echo === DOKUMENTASI ===
echo 5. FIX_FINAL_CHECKBOX_SELECTION_21_FEB_2026.md
echo 6. FIX_RANGKUMAN_OPSI_PAKET_SERAGAM_21_FEB_2026.md
echo 7. RINGKASAN_FIX_FINAL_21_FEB_2026.md
echo 8. ADD_MODE_OPSI_PANITIA_SERAGAM_21_FEB_2026.md
echo 9. FIX_DATABASE_PAKET_SERAGAM_VARCHAR_21_FEB_2026.md
echo 10. SUMMARY_COMPLETE_MODE_OPSI_21_FEB_2026.md
echo.
echo PERUBAHAN UTAMA:
echo.
echo 1. Alpine.js dengan ID-based Comparison
echo    - toggleOption: findIndex by item.id === option.id
echo    - isSelected: some by item.id === optionId
echo    - Fast dan reliable
echo.
echo 2. Proper Initialization
echo    - Added init() method
echo    - Handles edge cases (null, undefined, empty)
echo.
echo 3. Single Click Handler
echo    - Changed from @click.stop to @change
echo    - No duplicate triggers
echo.
echo 4. Immediate Sync
echo    - @this.set('opsiSeragam', this.selectedOptions)
echo    - Alpine handles reactivity
echo.
echo ========================================
echo TESTING CHECKLIST
echo ========================================
echo.
echo Test 1: Single Checkbox
echo - Login pendaftar ^> /pendaftar/daftarulang?activeTab=seragam
echo - Pilih paket checkbox mode
echo - Klik 1 checkbox
echo - Verify: HANYA yang diklik tercentang
echo.
echo Test 2: Multiple Checkbox
echo - Centang 2-3 checkbox lainnya
echo - Verify: Konfirmasi nominal = TOTAL SUM
echo - Verify: Rincian tampil breakdown per item
echo.
echo Test 3: Uncheck
echo - Klik checkbox yang sudah tercentang
echo - Verify: Ter-uncheck, total berkurang
echo.
echo Test 4: Ganti Paket
echo - Ganti dari paket checkbox ke paket radio
echo - Verify: Opsi di-reset, konfirmasi hilang
echo.
echo Test 5: Radio Mode
echo - Pilih paket radio mode
echo - Verify: Hanya 1 opsi terpilih (radio behavior)
echo.
echo Test 6: Save ^& Invoice
echo - Pilih checkbox mode, centang 3 opsi
echo - Bayar penuh, upload bukti, simpan
echo - Download invoice
echo - Verify: Invoice tampil itemized breakdown
echo.
echo Test 7: Rangkuman Data Terkirim
echo - Setelah submit, scroll ke "Data yang Telah Dikirim"
echo - Lihat section "Opsi Paket"
echo - Verify: Tampil semua opsi yang dipilih dengan numbering
echo - Verify: Ada total sum di bawah
echo.
echo Test 8: Panitia Seragam - Settings
echo - Login Panitia Seragam
echo - /panitia-seragam/daftar-ulang?activeTab=settings
echo - Verify: Dropdown "Mode Pemilihan Opsi" tampil
echo - Verify: Ada 2 opsi (Radio/Checkbox)
echo.
echo Test 9: Panitia Seragam - Kolom Nominal
echo - Tab "Daftar Ulang Seragam"
echo - Verify: Multi-select tampil dengan badge + list + total
echo - Verify: Single option tampil format lama
echo.
echo ========================================
echo EXPECTED RESULTS
echo ========================================
echo.
echo 1. Checkbox Selection:
echo    - Before: Click 1 = all selected ❌
echo    - After: Click 1 = only 1 selected ✅
echo.
echo 2. Konfirmasi Nominal:
echo    - Before: No sum calculation ❌
echo    - After: Shows total sum + rincian ✅
echo.
echo 3. Rangkuman Opsi Paket:
echo    - Before: Only show 1st item ❌
echo    - After: Shows all selected items + total ✅
echo.
echo 4. Panitia Seragam - Settings:
echo    - Before: No mode opsi dropdown ❌
echo    - After: Dropdown radio/checkbox available ✅
echo.
echo 5. Panitia Seragam - Kolom Nominal:
echo    - Before: Only show 1st item for multi ❌
echo    - After: Shows all items + total ✅
echo.
echo 6. Performance:
echo    - Before: Slow (JSON stringify) ❌
echo    - After: Fast (ID comparison) ✅
echo.
echo 7. Code Quality:
echo    - Before: Complex, hard to debug ❌
echo    - After: Simple, clean, maintainable ✅
echo.
pause
