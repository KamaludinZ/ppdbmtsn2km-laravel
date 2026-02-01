@echo off
echo ========================================
echo UPLOAD FIX SURVEY KE PRODUCTION
echo ========================================
echo.
echo Fix yang akan di-upload:
echo - Mengganti active() menjadi aktif()
echo - Mengganti question_type menjadi tipe_input
echo - Mengganti is_required menjadi wajib_diisi
echo - Mengganti question_text menjadi pertanyaan
echo - Mengganti survey_option_id dan answer_text menjadi jawaban
echo.

echo [1/2] Upload Formulir.php...
scp "C:\ppdbmtsn2km-laravel\app\Livewire\Pendaftar\Formulir.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Pendaftar/
if %errorlevel% neq 0 (
    echo ERROR: Upload gagal!
    pause
    exit /b 1
)
echo ✓ Upload berhasil
echo.

echo [2/2] Clear cache di production...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan view:clear && php artisan cache:clear && php artisan config:clear && echo '✅ Cache cleared'"
if %errorlevel% neq 0 (
    echo WARNING: Clear cache gagal, silakan clear manual
) else (
    echo ✓ Cache cleared
)
echo.

echo ========================================
echo UPLOAD SELESAI
echo ========================================
echo.
echo Silakan coba lagi survey di:
echo https://pmbm.mtsn2kotamalang.sch.id/pendaftar/formulir?activeTab=survey
echo.
pause
