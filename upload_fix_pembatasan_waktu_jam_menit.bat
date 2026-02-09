@echo off
echo ============================================
echo FIX PEMBATASAN WAKTU DENGAN JAM DAN MENIT
echo ============================================
echo.
echo PERUBAHAN YANG AKAN DI-UPLOAD:
echo [1] Admin Settings: datetime-local inputs (tanggal + jam + menit)
echo [2] Madrasah Tab: Red warning box jika di luar waktu
echo [3] Seragam Tab: Red warning box jika di luar waktu
echo [4] Ukuran Tab: Ikut jadwal seragam (red warning box)
echo [5] Info box di admin: Seragam dan Ukuran share jadwal
echo.
echo FILES YANG AKAN DI-UPLOAD:
echo - resources/views/livewire/admin/daftar-ulang.blade.php
echo - resources/views/livewire/pendaftar/daftar-ulang.blade.php
echo.
pause

echo.
echo ========================================
echo UPLOAD ADMIN DAFTAR ULANG VIEW
echo ========================================
scp resources/views/livewire/admin/daftar-ulang.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/
if %errorlevel% neq 0 (
    echo GAGAL upload admin daftar-ulang view
    pause
    exit /b 1
)
echo BERHASIL upload admin daftar-ulang view

echo.
echo ========================================
echo UPLOAD PENDAFTAR DAFTAR ULANG VIEW
echo ========================================
scp resources/views/livewire/pendaftar/daftar-ulang.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/
if %errorlevel% neq 0 (
    echo GAGAL upload pendaftar daftar-ulang view
    pause
    exit /b 1
)
echo BERHASIL upload pendaftar daftar-ulang view

echo.
echo ========================================
echo CLEAR CACHE
echo ========================================
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan view:clear && php artisan optimize:clear"
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
echo FITUR BARU:
echo ===========
echo.
echo 1. ADMIN SETTINGS (/admin/daftar-ulang?activeTab=settings)
echo    ========================================================
echo    [x] Input Waktu Mulai: datetime-local (tanggal + jam + menit)
echo    [x] Input Waktu Berakhir: datetime-local (tanggal + jam + menit)
echo    [x] Format: 24 jam (00:00 - 23:59)
echo    [x] Berlaku untuk:
echo        - Madrasah payment time (hanya untuk tab Madrasah)
echo        - Seragam payment time (untuk tab Seragam DAN Ukuran)
echo    [x] Info box biru di Seragam menjelaskan linkage ke Ukuran
echo.
echo 2. PENDAFTAR - TAB MADRASAH
echo    =========================
echo    Jika DI LUAR waktu pembayaran:
echo    [x] Red warning box muncul dengan:
echo        - Icon clock (jam)
echo        - Title: "Di Luar Waktu Pembayaran Daftar Ulang Madrasah"
echo        - Jadwal lengkap (tanggal, jam, menit) dalam bahasa Indonesia
echo        - Instruksi untuk kembali pada waktu yang tepat
echo    [x] Semua form TERSEMBUNYI (tidak bisa diakses):
echo        - Surat Pernyataan
echo        - Pilih Nominal
echo        - Upload Bukti
echo        - Tombol Simpan
echo.
echo    Jika DI DALAM waktu pembayaran:
echo    [x] Warning box HILANG
echo    [x] Semua form TAMPIL dan bisa diisi
echo.
echo 3. PENDAFTAR - TAB SERAGAM
echo    ========================
echo    Jika DI LUAR waktu pembayaran:
echo    [x] Red warning box muncul (struktur sama dengan Madrasah)
echo    [x] Title: "Di Luar Waktu Pembayaran Daftar Ulang Seragam"
echo    [x] Menampilkan jadwal pembayaran seragam
echo    [x] Semua form seragam TERSEMBUNYI
echo.
echo    Jika DI DALAM waktu pembayaran:
echo    [x] Warning box HILANG
echo    [x] Form seragam TAMPIL
echo.
echo 4. PENDAFTAR - TAB UKURAN
echo    =======================
echo    PENTING: Tab Ukuran mengikuti jadwal Tab Seragam!
echo.
echo    Jika DI LUAR waktu pembayaran seragam:
echo    [x] Red warning box muncul
echo    [x] Title: "Di Luar Waktu Pengisian Ukuran Seragam"
echo    [x] Jadwal yang ditampilkan: Jadwal Seragam
echo    [x] Text: "Sama dengan Pembayaran Seragam"
echo    [x] Yellow info box menjelaskan linkage
echo    [x] Semua form ukuran TERSEMBUNYI
echo.
echo    Jika DI DALAM waktu pembayaran seragam:
echo    [x] Warning box HILANG
echo    [x] Form ukuran TAMPIL
echo.
echo ============================================
echo TESTING CHECKLIST
echo ============================================
echo.
echo TEST 1: Admin Settings - Madrasah
echo ==================================
echo 1. Login sebagai Admin
echo 2. Buka /admin/daftar-ulang?activeTab=settings
echo 3. Scroll ke "Pengaturan Daftar Ulang Madrasah"
echo 4. VERIFY:
echo    [x] Input "Waktu Mulai" type=datetime-local
echo    [x] Input "Waktu Berakhir" type=datetime-local
echo    [x] Label: "Waktu" (bukan "Tanggal")
echo    [x] Helper text: "Format: Tanggal dan Waktu (24 jam)"
echo 5. Set waktu: Besok 10:00 - 16:00
echo 6. Klik Simpan
echo 7. VERIFY: Success message
echo.
echo TEST 2: Admin Settings - Seragam
echo =================================
echo 1. Masih di /admin/daftar-ulang?activeTab=settings
echo 2. Scroll ke "Pengaturan Daftar Ulang Seragam"
echo 3. VERIFY:
echo    [x] Input "Waktu Mulai" type=datetime-local
echo    [x] Input "Waktu Berakhir" type=datetime-local
echo    [x] Description mentions "juga berlaku untuk pengisian ukuran seragam"
echo    [x] Blue info box visible dengan:
echo        - Warning icon
echo        - Text: "Waktu ini berlaku untuk tab Seragam dan tab Ukuran"
echo        - Text: "Jika di luar waktu, kedua tab tidak dapat diakses"
echo 4. Set waktu: Besok 09:00 - 17:00
echo 5. Klik Simpan
echo 6. VERIFY: Success message
echo.
echo TEST 3: Pendaftar Madrasah - Outside Time
echo ==========================================
echo 1. Pastikan waktu saat ini DI LUAR jadwal madrasah
echo 2. Login sebagai Pendaftar (status: lulus)
echo 3. Buka /pendaftar/daftarulang?activeTab=madrasah
echo 4. VERIFY:
echo    [x] Red warning box visible
echo    [x] Icon clock (jam)
echo    [x] Title: "🕐 Di Luar Waktu Pembayaran Daftar Ulang Madrasah"
echo    [x] Red schedule box dengan:
echo        - "Mulai: [Tanggal], [Jam]:[Menit] WIB"
echo        - "Berakhir: [Tanggal], [Jam]:[Menit] WIB"
echo        - Format Indonesia (contoh: "10 Februari 2026, 10:00 WIB")
echo    [x] Yellow info box: "Silakan kembali pada waktu yang telah ditentukan"
echo    [ ] NO form visible (Surat Pernyataan, Nominal, Upload, Simpan)
echo 5. Inspect DOM:
echo    [x] Form elements NOT rendered
echo.
echo TEST 4: Pendaftar Madrasah - Inside Time
echo =========================================
echo 1. Ubah waktu sistem atau setting agar saat ini DI DALAM jadwal
echo 2. Refresh /pendaftar/daftarulang?activeTab=madrasah
echo 3. VERIFY:
echo    [ ] NO red warning box
echo    [x] Surat Pernyataan section visible
echo    [x] Pilih Nominal form visible
echo    [x] Upload Bukti form visible
echo    [x] Tombol Simpan visible
echo 4. Try fill form:
echo    [x] Should work normally
echo.
echo TEST 5: Pendaftar Seragam - Outside Time
echo =========================================
echo 1. Pastikan waktu saat ini DI LUAR jadwal seragam
echo 2. Buka /pendaftar/daftarulang?activeTab=seragam
echo 3. VERIFY:
echo    [x] Red warning box visible
echo    [x] Title: "🕐 Di Luar Waktu Pembayaran Daftar Ulang Seragam"
echo    [x] Schedule shows seragam payment times
echo    [ ] NO seragam form visible
echo.
echo TEST 6: Pendaftar Seragam - Inside Time
echo ========================================
echo 1. Ubah waktu agar DI DALAM jadwal seragam
echo 2. Refresh tab seragam
echo 3. VERIFY:
echo    [ ] NO warning box
echo    [x] Seragam form visible
echo.
echo TEST 7: Pendaftar Ukuran - Outside Seragam Time (CRITICAL!)
echo ============================================================
echo 1. Pastikan waktu DI LUAR jadwal seragam
echo 2. Buka /pendaftar/daftarulang?activeTab=ukuran
echo 3. VERIFY:
echo    [x] Red warning box visible
echo    [x] Title: "🕐 Di Luar Waktu Pengisian Ukuran Seragam"
echo    [x] Schedule box shows SERAGAM payment times (bukan ukuran time)
echo    [x] Text mentions: "Sama dengan Pembayaran Seragam"
echo    [x] Yellow info box: "Pengisian ukuran seragam mengikuti jadwal pembayaran daftar ulang seragam"
echo    [ ] NO ukuran form visible
echo.
echo TEST 8: Pendaftar Ukuran - Inside Seragam Time
echo ===============================================
echo 1. Ubah waktu agar DI DALAM jadwal seragam
echo 2. Buka tab ukuran
echo 3. VERIFY:
echo    [ ] NO warning box
echo    [x] Ukuran form visible
echo    [x] Can select sizes normally
echo.
echo TEST 9: Seragam and Ukuran Sync (CRITICAL!)
echo ============================================
echo 1. Set seragam time: Hari ini 10:00 - 16:00
echo 2. Test at different times:
echo.
echo    At 09:59 (before start):
echo    [x] Seragam tab: Warning visible, form hidden
echo    [x] Ukuran tab: Warning visible, form hidden
echo.
echo    At 10:01 (after start):
echo    [x] Seragam tab: Warning hidden, form visible
echo    [x] Ukuran tab: Warning hidden, form visible
echo.
echo    At 16:01 (after end):
echo    [x] Seragam tab: Warning visible, form hidden
echo    [x] Ukuran tab: Warning visible, form hidden
echo.
echo TEST 10: Edge Cases
echo ===================
echo 1. Exactly at start time (10:00:00):
echo    [x] Form should be accessible (boundary inclusive)
echo.
echo 2. Exactly at end time (16:00:00):
echo    [x] Form should be hidden (boundary exclusive)
echo.
echo 3. One second before end (15:59:59):
echo    [x] Form should still be accessible
echo.
echo TEST 11: Mobile Responsive
echo ==========================
echo 1. Access on mobile device
echo 2. VERIFY:
echo    [x] Red warning box fits screen
echo    [x] Clock icon visible
echo    [x] Text readable
echo    [x] Schedule grid stacks vertically (1 column)
echo    [x] Yellow info box readable
echo.
echo TEST 12: Cross-Browser
echo ======================
echo Test in:
echo [ ] Chrome - datetime picker works, times display correctly
echo [ ] Firefox - datetime picker works, times display correctly
echo [ ] Edge - datetime picker works, times display correctly
echo [ ] Safari - datetime picker works, times display correctly
echo.
echo ============================================
echo DATABASE CHECK (Optional)
echo ============================================
echo.
echo Run this query to verify stored times:
echo.
echo SELECT setting_key, setting_value
echo FROM settings
echo WHERE setting_key IN (
echo     'madrasah_payment_start',
echo     'madrasah_payment_end',
echo     'seragam_payment_start',
echo     'seragam_payment_end'
echo );
echo.
echo Expected format: YYYY-MM-DD HH:mm:ss
echo Example: 2026-02-10 08:00:00
echo.
echo ============================================
echo PRODUCTION USAGE GUIDE
echo ============================================
echo.
echo CARA SET WAKTU PEMBAYARAN:
echo ==========================
echo.
echo 1. Login sebagai Admin
echo 2. Menu "Daftar Ulang" -^> Tab "Settings"
echo.
echo 3. Set Madrasah Payment Time:
echo    - Klik "Waktu Mulai"
echo    - Pilih tanggal (contoh: 10 Feb 2026)
echo    - Pilih jam (contoh: 08)
echo    - Pilih menit (contoh: 00)
echo    - Klik "Waktu Berakhir"
echo    - Pilih tanggal (contoh: 12 Feb 2026)
echo    - Pilih jam (contoh: 17)
echo    - Pilih menit (contoh: 00)
echo    - Klik "Simpan Pengaturan Madrasah"
echo.
echo 4. Set Seragam Payment Time:
echo    - Perhatikan info box: "Berlaku untuk Seragam dan Ukuran"
echo    - Set waktu yang sama dengan langkah di atas
echo    - Klik "Simpan Pengaturan Seragam"
echo.
echo 5. Verify:
echo    - Logout, login sebagai Pendaftar test
echo    - Cek apakah form tampil sesuai waktu
echo.
echo CONTOH KONFIGURASI:
echo ===================
echo.
echo Madrasah:
echo - Mulai: 10 Februari 2026, 08:00
echo - Berakhir: 12 Februari 2026, 17:00
echo.
echo Seragam (dan Ukuran):
echo - Mulai: 13 Februari 2026, 08:00
echo - Berakhir: 15 Februari 2026, 17:00
echo.
echo Result:
echo - Tgl 10-12 Feb (08:00-17:00): Madrasah accessible
echo - Tgl 13-15 Feb (08:00-17:00): Seragam + Ukuran accessible
echo - Di luar waktu: Warning box muncul
echo.
pause
