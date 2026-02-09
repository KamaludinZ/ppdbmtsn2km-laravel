@echo off
echo ============================================
echo FIX TAMPILAN TIDAK SETUJU DAFTAR ULANG
echo ============================================
echo.
echo PERBAIKAN:
echo [1] FIX KONDISI: status_persetujuan_daftar_ulang (bukan setuju_daftar_ulang)
echo [2] Ucapan: "Terima Kasih atas Partisipasi Anda"
echo [3] Warna: Blue gradient (lebih friendly)
echo [4] Icon: Thumbs up (lebih positif)
echo [5] Menampilkan alasan yang dipilih user
echo [6] Layout responsive dan modern
echo.
echo LOKASI PERUBAHAN:
echo - Halaman: /pendaftar/dashboard
echo - Status: status_persetujuan_daftar_ulang = 'tidak_setuju'
echo - Tampil setelah user submit modal alasan
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
echo TEST SCENARIO 1: Modal Submit dan Tampilan Baru
echo ================================================
echo 1. Login sebagai Pendaftar dengan status "lulus"
echo 2. Buka /pendaftar/dashboard
echo 3. Klik tombol "Tidak Melanjutkan Daftar Ulang"
echo 4. Pilih salah satu alasan (misal: "Diterima di Sekolah Lain")
echo 5. Klik "Konfirmasi Tidak Melanjutkan"
echo.
echo 6. PERHATIKAN TAMPILAN BARU:
echo    [x] Judul: "Terima Kasih atas Partisipasi Anda"
echo    [x] Background: Biru gradient (blue-500 to blue-600)
echo    [x] Icon: Thumbs up (bukan X)
echo    [x] Ucapan terima kasih yang hangat
echo    [x] Waktu pilihan ditampilkan
echo.
echo 7. PERHATIKAN BOX ALASAN:
echo    [x] Ada box putih dengan border biru
echo    [x] Header: "Alasan yang Anda Berikan:"
echo    [x] Isi alasan: "Diterima di Sekolah Lain" (sesuai pilihan)
echo    [x] Note di bawah: "Informasi ini akan membantu kami..."
echo.
echo TEST SCENARIO 2: Alasan "Lainnya" dengan Custom Text
echo ======================================================
echo 1. Login dengan pendaftar lain dengan status "lulus"
echo 2. Klik "Tidak Melanjutkan Daftar Ulang"
echo 3. Pilih "Lainnya (Isi sendiri)"
echo 4. Isi textarea dengan: "Saya ingin fokus ke prestasi non-akademik"
echo 5. Submit modal
echo.
echo 6. PERHATIKAN ALASAN:
echo    [x] Box alasan menampilkan: "Saya ingin fokus ke prestasi non-akademik"
echo    [x] Bukan menampilkan "lainnya"
echo.
echo VISUAL COMPARISON:
echo =================
echo BEFORE:
echo - Warna: Gray (negatif)
echo - Icon: X (negatif)
echo - Judul: "Pilihan Tercatat: Tidak Melanjutkan Daftar Ulang"
echo - Tidak ada tampilan alasan
echo.
echo AFTER:
echo - Warna: Blue (friendly)
echo - Icon: Thumbs up (positif)
echo - Judul: "Terima Kasih atas Partisipasi Anda"
echo - Ada box alasan dengan styling yang bagus
echo.
echo NOTE:
echo - Tampilan ini hanya muncul jika status_persetujuan_daftar_ulang = 'tidak_setuju'
echo - Alasan diambil dari field: pendaftar.alasan_tidak_setuju
echo - Jika alasan kosong, box alasan tidak ditampilkan
echo.
pause
