@echo off
echo ========================================
echo VERIFIKASI FILE PRODUCTION
echo ========================================
echo.
echo Mengecek jumlah baris file di production...
echo.

set SERVER=mtsnkot4@103.30.244.77
set REMOTE_PATH=/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

echo [1] Cek file Panitia DetailPendaftar.php:
ssh %SERVER% "cd %REMOTE_PATH% && wc -l app/Livewire/Panitia/DetailPendaftar.php"
echo    ^ Harus: 929 baris (jika sudah benar)
echo.

echo [2] Cek file Admin DetailPendaftar.php:
ssh %SERVER% "cd %REMOTE_PATH% && wc -l app/Livewire/Admin/DetailPendaftar.php"
echo    ^ Harus: 2577 baris (jika sudah benar)
echo.

echo [3] Cek method toggleEdit di Panitia:
ssh %SERVER% "cd %REMOTE_PATH% && grep -n 'public function toggleEdit' app/Livewire/Panitia/DetailPendaftar.php"
echo    ^ Harus ada di line 307 (jika sudah benar)
echo.

echo [4] Cek method saveNilaiRapor di Panitia:
ssh %SERVER% "cd %REMOTE_PATH% && grep -n 'public function saveNilaiRapor' app/Livewire/Panitia/DetailPendaftar.php"
echo    ^ Harus ada di line 875 (jika sudah benar)
echo.

echo [5] Cek method saveNilaiRapor di Admin:
ssh %SERVER% "cd %REMOTE_PATH% && grep -n 'public function saveNilaiRapor' app/Livewire/Admin/DetailPendaftar.php"
echo    ^ Harus ada di line 2520 (jika sudah benar)
echo.

echo ========================================
echo Selesai
echo ========================================
pause
