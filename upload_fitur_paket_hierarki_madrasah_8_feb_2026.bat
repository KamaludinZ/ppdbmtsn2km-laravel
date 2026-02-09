@echo off
echo ========================================
echo Fitur: Paket Hierarki Madrasah
echo 8 Februari 2026
echo ========================================
echo.
echo FITUR: Struktur hierarki 3-level sama seperti Seragam
echo - Package dropdown selection
echo - Dependent option radio buttons
echo - Konfirmasi Nominal (Bayar Penuh / Opsi Lainnya)
echo.
echo PERUBAHAN MAYOR:
echo - Madrasah sekarang: Paket → Opsi → Konfirmasi
echo - Progressive disclosure pattern
echo - Consistent dengan Seragam tab
echo.
echo ========================================
echo.

echo [INFO] Uploading file to production server...
echo.

echo [1/2] Uploading daftar-ulang.blade.php...
scp "resources\views\livewire\pendaftar\daftar-ulang.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/

if errorlevel 1 (
    echo [ERROR] Failed to upload daftar-ulang.blade.php
    pause
    exit /b 1
)

echo [SUCCESS] File uploaded successfully!
echo.

echo [2/2] Running artisan optimize:clear...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan optimize:clear"

if errorlevel 1 (
    echo [ERROR] Failed to run optimize:clear
    pause
    exit /b 1
)

echo.
echo ========================================
echo ✅ FITUR DEPLOYED SUCCESSFULLY!
echo ========================================
echo.
echo CHANGES APPLIED:
echo.
echo 1. ✅ Madrasah tab restructured to 3-level hierarchy
echo    - Step 1: Pilih Paket Madrasah (dropdown)
echo    - Step 2: Pilih Opsi dari Paket (radio buttons, dependent)
echo    - Step 3: Konfirmasi Nominal yang Dibayarkan
echo.
echo 2. ✅ Progressive Disclosure Pattern
echo    - Options appear when package selected
echo    - Confirmation appears when option selected
echo    - Clean guided flow
echo.
echo 3. ✅ Consistent dengan Seragam Tab
echo    - Same structure
echo    - Same colors (green for Bayar Penuh, yellow for Opsi Lainnya)
echo    - Same UI/UX patterns
echo.
echo FLOW SEKARANG:
echo.
echo [Dropdown] Pilih Paket Madrasah *
echo   └─ Select: "PAKET A - JALUR PRESTASI"
echo.
echo [Radio Section - appears after package selected]
echo Pilih Opsi dari PAKET A *
echo   ● OPSI A1 - Rp 500.000
echo   ○ OPSI A2 - Rp 600.000
echo.
echo [Confirmation - appears after option selected]
echo Konfirmasi Nominal yang Dibayarkan *
echo   ● Bayar Penuh (GREEN)
echo     OPSI A1
echo     Rp 500.000
echo.
echo   ○ Opsi Lainnya (YELLOW)
echo     Isi nominal secara manual
echo     [Manual input field]
echo.
echo ========================================
echo ⚠️  BACKEND UPDATES REQUIRED
echo ========================================
echo.
echo IMPORTANT: Livewire component perlu update!
echo.
echo File: app/Livewire/Pendaftar/DaftarUlang.php
echo.
echo Add properties:
echo   public $paketMadrasah;
echo   public $opsiMadrasah;
echo.
echo Update validation:
echo   'paketMadrasah' =^> 'required',
echo   'opsiMadrasah' =^> 'required',
echo.
echo Update saveMadrasah() method:
echo   $selectedOption = json_decode($this-^>opsiMadrasah, true);
echo   $amount = $selectedOption['amount'];
echo.
echo ========================================
echo ⚙️  SETTING REQUIRED
echo ========================================
echo.
echo Admin must add this setting:
echo.
echo Key: daftar_ulang_madrasah_packages
echo Type: JSON
echo.
echo Example Value:
echo [
echo   {
echo     "name": "PAKET LENGKAP",
echo     "description": "Biaya penuh",
echo     "options": [
echo       {"name": "OPSI A1", "amount": 500000},
echo       {"name": "OPSI A2", "amount": 600000}
echo     ]
echo   }
echo ]
echo.
echo ========================================
echo 🧪 TESTING REQUIRED
echo ========================================
echo.
echo Please verify at:
echo https://pmbm.mtsn2kotamalang.sch.id/pendaftar/daftarulang?activeTab=madrasah
echo.
echo Test Flow:
echo 1. Hard refresh browser: Ctrl + Shift + R
echo 2. Verify gradient emerald/teal background
echo 3. Verify header: "Step 1: Pilih Paket Madrasah & Nominal"
echo 4. Select paket dari dropdown
echo 5. Verify "Pilih Opsi dari [Paket]" section muncul
echo 6. Verify white background dengan emerald left border
echo 7. Select opsi
echo 8. Verify "Konfirmasi Nominal" section muncul
echo 9. Verify "Bayar Penuh" shows selected option + amount
echo 10. Verify "Opsi Lainnya" option available
echo.
echo Edge Cases:
echo - Package dengan no options → yellow warning muncul
echo - Change package → previous selection cleared
echo - Validation errors → red messages muncul
echo.
echo ========================================
echo 📋 NEXT STEPS
echo ========================================
echo.
echo 1. Update Livewire component (DaftarUlang.php)
echo 2. Add setting: daftar_ulang_madrasah_packages
echo 3. Test full flow on production
echo 4. Verify consistency with Seragam tab
echo.
pause
