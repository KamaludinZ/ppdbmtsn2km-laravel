@echo off
echo ====================================
echo  PREPARE PRODUCTION UPLOAD
echo  4 Hari Terakhir (23-27 Jan 2026)
echo ====================================
echo.

:: Create upload directory
set UPLOAD_DIR=production_upload_27jan2026
if exist "%UPLOAD_DIR%" (
    echo Cleaning existing upload directory...
    rmdir /s /q "%UPLOAD_DIR%"
)

echo Creating upload directory structure...
mkdir "%UPLOAD_DIR%"
mkdir "%UPLOAD_DIR%\database\migrations"
mkdir "%UPLOAD_DIR%\app\Models"
mkdir "%UPLOAD_DIR%\app\Exports"
mkdir "%UPLOAD_DIR%\app\Http\Controllers"
mkdir "%UPLOAD_DIR%\app\Livewire\Admin"
mkdir "%UPLOAD_DIR%\app\Livewire\Auth"
mkdir "%UPLOAD_DIR%\app\Livewire\Panitia"
mkdir "%UPLOAD_DIR%\app\Livewire\Pendaftar"
mkdir "%UPLOAD_DIR%\resources\views\livewire\admin"
mkdir "%UPLOAD_DIR%\resources\views\livewire\admin\partials"
mkdir "%UPLOAD_DIR%\resources\views\livewire\panitia"
mkdir "%UPLOAD_DIR%\resources\views\livewire\pendaftar"
mkdir "%UPLOAD_DIR%\resources\views\tab_templates"
mkdir "%UPLOAD_DIR%\resources\views\exports"
mkdir "%UPLOAD_DIR%\routes"
mkdir "%UPLOAD_DIR%\scripts"
mkdir "%UPLOAD_DIR%\docs"
echo.

echo ====================================
echo  COPYING FILES...
echo ====================================
echo.

:: 1. Migrations (20 files)
echo [1/16] Copying Migrations...
copy "database\migrations\2026_01_15_051817_create_postal_codes_table.php" "%UPLOAD_DIR%\database\migrations\" >nul
copy "database\migrations\2026_01_15_055334_add_siswa_alamat_to_pendaftar_table.php" "%UPLOAD_DIR%\database\migrations\" >nul
copy "database\migrations\2026_01_16_131212_add_unique_to_users_email_and_performance_indexes.php" "%UPLOAD_DIR%\database\migrations\" >nul
copy "database\migrations\2026_01_16_140214_add_status_fields_to_seragam_table.php" "%UPLOAD_DIR%\database\migrations\" >nul
copy "database\migrations\2026_01_16_180155_add_catatan_to_kartu_kendali_table.php" "%UPLOAD_DIR%\database\migrations\" >nul
copy "database\migrations\2026_01_16_203729_add_graduation_statuses_to_pendaftar_enum.php" "%UPLOAD_DIR%\database\migrations\" >nul
copy "database\migrations\2026_01_17_000001_add_missing_status_to_pendaftar_enum.php" "%UPLOAD_DIR%\database\migrations\" >nul
copy "database\migrations\2026_01_17_000002_update_old_status_values.php" "%UPLOAD_DIR%\database\migrations\" >nul
copy "database\migrations\2026_01_19_optimize_register_performance.php" "%UPLOAD_DIR%\database\migrations\" >nul
copy "database\migrations\2026_01_20_000001_add_payment_and_document_fields_to_daftar_ulang_and_seragam.php" "%UPLOAD_DIR%\database\migrations\" >nul
copy "database\migrations\2026_01_21_212140_add_brosur_settings_to_settings_table.php" "%UPLOAD_DIR%\database\migrations\" >nul
copy "database\migrations\2026_01_22_065858_add_payment_info_text_to_settings_table.php" "%UPLOAD_DIR%\database\migrations\" >nul
copy "database\migrations\2026_01_22_205942_add_nominal_biaya_columns_to_daftar_ulang_and_seragam_tables.php" "%UPLOAD_DIR%\database\migrations\" >nul
copy "database\migrations\2026_01_22_211220_add_detailed_uniform_size_columns_to_seragam_table.php" "%UPLOAD_DIR%\database\migrations\" >nul
copy "database\migrations\2026_01_23_053343_add_tampil_di_daftar_to_jalur_pendaftaran_table.php" "%UPLOAD_DIR%\database\migrations\" >nul
copy "database\migrations\2026_01_23_102510_create_riwayat_pesan_table.php" "%UPLOAD_DIR%\database\migrations\" >nul
copy "database\migrations\2026_01_23_195806_create_statistik_tahun_table.php" "%UPLOAD_DIR%\database\migrations\" >nul
copy "database\migrations\2026_01_25_215507_add_alasan_tidak_setuju_to_pendaftars_table.php" "%UPLOAD_DIR%\database\migrations\" >nul
copy "database\migrations\2026_01_27_124505_update_status_enum_in_daftar_ulang_table.php" "%UPLOAD_DIR%\database\migrations\" >nul
copy "database\migrations\2026_01_27_132623_add_unique_constraint_to_daftar_ulang_pendaftar_id.php" "%UPLOAD_DIR%\database\migrations\" >nul
copy "database\migrations\2026_01_27_150806_add_informasi_admin_panitia_to_tes_psikologi_settings_table.php" "%UPLOAD_DIR%\database\migrations\" >nul
copy "database\migrations\2026_01_27_151550_add_panitia_tes_psikologi_role_to_users_table.php" "%UPLOAD_DIR%\database\migrations\" >nul
echo    ✓ 22 migration files copied

:: 2. Models
echo [2/16] Copying Models...
copy "app\Models\RiwayatPesan.php" "%UPLOAD_DIR%\app\Models\" >nul
copy "app\Models\StatistikJalurTahun.php" "%UPLOAD_DIR%\app\Models\" >nul
copy "app\Models\StatistikTahun.php" "%UPLOAD_DIR%\app\Models\" >nul
copy "app\Models\JalurPendaftaran.php" "%UPLOAD_DIR%\app\Models\" >nul
copy "app\Models\Pendaftar.php" "%UPLOAD_DIR%\app\Models\" >nul
echo    ✓ 5 model files copied

:: 3. Exports
echo [3/16] Copying Export Classes...
copy "app\Exports\DaftarUlangSeragamExport.php" "%UPLOAD_DIR%\app\Exports\" >nul
copy "app\Exports\LaporanPendaftaranExport.php" "%UPLOAD_DIR%\app\Exports\" >nul
copy "app\Exports\DaftarUlangMadrasahSheet.php" "%UPLOAD_DIR%\app\Exports\" >nul
copy "app\Exports\DaftarUlangSeragamSheet.php" "%UPLOAD_DIR%\app\Exports\" >nul
copy "app\Exports\DaftarUlangUkuranSheet.php" "%UPLOAD_DIR%\app\Exports\" >nul
copy "app\Exports\PendaftarExport.php" "%UPLOAD_DIR%\app\Exports\" >nul
copy "app\Exports\SeragamExport.php" "%UPLOAD_DIR%\app\Exports\" >nul
echo    ✓ 7 export class files copied

:: 4. Controllers
echo [4/16] Copying Controllers...
copy "app\Http\Controllers\ExportController.php" "%UPLOAD_DIR%\app\Http\Controllers\" >nul
echo    ✓ 1 controller file copied

:: 5. Livewire Admin
echo [5/16] Copying Livewire Admin Components...
copy "app\Livewire\Admin\DaftarUlang.php" "%UPLOAD_DIR%\app\Livewire\Admin\" >nul
copy "app\Livewire\Admin\Jadwal.php" "%UPLOAD_DIR%\app\Livewire\Admin\" >nul
copy "app\Livewire\Admin\Jalur.php" "%UPLOAD_DIR%\app\Livewire\Admin\" >nul
copy "app\Livewire\Admin\Kelulusan.php" "%UPLOAD_DIR%\app\Livewire\Admin\" >nul
copy "app\Livewire\Admin\Laporan.php" "%UPLOAD_DIR%\app\Livewire\Admin\" >nul
copy "app\Livewire\Admin\Pendaftar.php" "%UPLOAD_DIR%\app\Livewire\Admin\" >nul
copy "app\Livewire\Admin\PengaturanPesan.php" "%UPLOAD_DIR%\app\Livewire\Admin\" >nul
copy "app\Livewire\Admin\TesPsikologi.php" "%UPLOAD_DIR%\app\Livewire\Admin\" >nul
echo    ✓ 8 admin component files copied

:: 6. Livewire Auth
echo [6/16] Copying Livewire Auth Components...
copy "app\Livewire\Auth\Register.php" "%UPLOAD_DIR%\app\Livewire\Auth\" >nul
copy "app\Livewire\Auth\RegisterFixed.php" "%UPLOAD_DIR%\app\Livewire\Auth\" >nul
copy "app\Livewire\Auth\RegisterOptimized.php" "%UPLOAD_DIR%\app\Livewire\Auth\" >nul
echo    ✓ 3 auth component files copied

:: 7. Livewire Panitia
echo [7/16] Copying Livewire Panitia Components...
copy "app\Livewire\Panitia\Dashboard.php" "%UPLOAD_DIR%\app\Livewire\Panitia\" >nul
copy "app\Livewire\Panitia\DaftarUlang.php" "%UPLOAD_DIR%\app\Livewire\Panitia\" >nul
copy "app\Livewire\Panitia\TesPsikologi.php" "%UPLOAD_DIR%\app\Livewire\Panitia\" >nul
echo    ✓ 3 panitia component files copied

:: 8. Livewire Pendaftar
echo [8/16] Copying Livewire Pendaftar Components...
copy "app\Livewire\Pendaftar\Dashboard.php" "%UPLOAD_DIR%\app\Livewire\Pendaftar\" >nul
echo    ✓ 1 pendaftar component file copied

:: 9. Views - Admin
echo [9/16] Copying Admin Views...
copy "resources\views\livewire\admin\daftar-ulang.blade.php" "%UPLOAD_DIR%\resources\views\livewire\admin\" >nul
copy "resources\views\livewire\admin\jalur.blade.php" "%UPLOAD_DIR%\resources\views\livewire\admin\" >nul
copy "resources\views\livewire\admin\laporan.blade.php" "%UPLOAD_DIR%\resources\views\livewire\admin\" >nul
copy "resources\views\livewire\admin\pendaftar.blade.php" "%UPLOAD_DIR%\resources\views\livewire\admin\" >nul
copy "resources\views\livewire\admin\pengaturan-pesan.blade.php" "%UPLOAD_DIR%\resources\views\livewire\admin\" >nul
copy "resources\views\livewire\admin\tes-psikologi.blade.php" "%UPLOAD_DIR%\resources\views\livewire\admin\" >nul
echo    ✓ 6 admin view files copied

:: 10. Views - Admin Partials
echo [10/16] Copying Admin Partial Views...
copy "resources\views\livewire\admin\partials\bulk-send-message-modal.blade.php" "%UPLOAD_DIR%\resources\views\livewire\admin\partials\" >nul
copy "resources\views\livewire\admin\partials\daftar-ulang-stats.blade.php" "%UPLOAD_DIR%\resources\views\livewire\admin\partials\" >nul
copy "resources\views\livewire\admin\partials\data-lengkap-stats.blade.php" "%UPLOAD_DIR%\resources\views\livewire\admin\partials\" >nul
copy "resources\views\livewire\admin\partials\send-message-modal.blade.php" "%UPLOAD_DIR%\resources\views\livewire\admin\partials\" >nul
copy "resources\views\livewire\admin\partials\tes-psikologi-data.blade.php" "%UPLOAD_DIR%\resources\views\livewire\admin\partials\" >nul
copy "resources\views\livewire\admin\partials\tes-psikologi-settings.blade.php" "%UPLOAD_DIR%\resources\views\livewire\admin\partials\" >nul
echo    ✓ 6 admin partial view files copied

:: 11. Views - Panitia
echo [11/16] Copying Panitia Views...
copy "resources\views\livewire\panitia\dashboard.blade.php" "%UPLOAD_DIR%\resources\views\livewire\panitia\" >nul
copy "resources\views\livewire\panitia\daftar-ulang.blade.php" "%UPLOAD_DIR%\resources\views\livewire\panitia\" >nul
copy "resources\views\livewire\panitia\tes-psikologi.blade.php" "%UPLOAD_DIR%\resources\views\livewire\panitia\" >nul
echo    ✓ 3 panitia view files copied

:: 12. Views - Pendaftar
echo [12/17] Copying Pendaftar Views...
copy "resources\views\livewire\pendaftar\daftar-ulang.blade.php" "%UPLOAD_DIR%\resources\views\livewire\pendaftar\" >nul
copy "resources\views\livewire\pendaftar\dashboard.blade.php" "%UPLOAD_DIR%\resources\views\livewire\pendaftar\" >nul
copy "resources\views\livewire\pendaftar\dokumen.blade.php" "%UPLOAD_DIR%\resources\views\livewire\pendaftar\" >nul
echo    ✓ 3 pendaftar view files copied

:: 13. Components Layouts
echo [13/17] Copying Component Layouts...
mkdir "%UPLOAD_DIR%\resources\views\components\layouts" 2>nul
copy "resources\views\components\layouts\dashboard.blade.php" "%UPLOAD_DIR%\resources\views\components\layouts\" >nul
echo    ✓ 1 layout component file copied

:: 14. Tab Templates
echo [14/17] Copying Tab Templates...
copy "resources\views\tab_templates\tab-data-alamat.blade.php" "%UPLOAD_DIR%\resources\views\tab_templates\" >nul
copy "resources\views\tab_templates\tab-data-orangtua.blade.php" "%UPLOAD_DIR%\resources\views\tab_templates\" >nul
copy "resources\views\tab_templates\tab-data-siswa.blade.php" "%UPLOAD_DIR%\resources\views\tab_templates\" >nul
echo    ✓ 3 tab template files copied

:: 15. Export Templates
echo [15/17] Copying Export Templates...
copy "resources\views\exports\laporan-pendaftaran-pdf.blade.php" "%UPLOAD_DIR%\resources\views\exports\" >nul
echo    ✓ 1 export template file copied

:: 16. Routes
echo [16/17] Copying Routes...
copy "routes\web.php" "%UPLOAD_DIR%\routes\" >nul
echo    ✓ 1 route file copied

:: 17. Utility Scripts
echo [17/17] Copying Utility Scripts...
copy "cleanup_duplicates.php" "%UPLOAD_DIR%\scripts\" >nul
copy "check_catatan.php" "%UPLOAD_DIR%\scripts\" >nul
copy "check_data_completeness.php" "%UPLOAD_DIR%\scripts\" >nul
copy "check_jalur_status.php" "%UPLOAD_DIR%\scripts\" >nul
echo    ✓ 4 utility scripts copied

:: Copy Documentation
echo.
echo Copying Documentation...
copy "PRODUCTION_UPLOAD_CHECKLIST_4_HARI.md" "%UPLOAD_DIR%\docs\" >nul
copy "FITUR_RIWAYAT_PESAN_COMPLETE.md" "%UPLOAD_DIR%\docs\" >nul 2>nul
copy "CONTEXTUAL_TEMPLATE_COMPLETE_IMPLEMENTATION.md" "%UPLOAD_DIR%\docs\" >nul 2>nul
copy "FITUR_STATUS_DAFTAR_JALUR.md" "%UPLOAD_DIR%\docs\" >nul 2>nul
copy "EXCEL_EXPORT_EMIS_RESTRUCTURE_COMPLETE.md" "%UPLOAD_DIR%\docs\" >nul 2>nul
copy "DAFTAR_ULANG_IMPROVEMENTS_COMPLETE.md" "%UPLOAD_DIR%\docs\" >nul 2>nul
copy "FIX_REGION_CODES_TO_NAMES_COMPLETE.md" "%UPLOAD_DIR%\docs\" >nul 2>nul
copy "FIX_CATATAN_MADRASAH_COMPLETE.md" "%UPLOAD_DIR%\docs\" >nul 2>nul
copy "ALL_TABS_STATS_COMPLETE.md" "%UPLOAD_DIR%\docs\" >nul 2>nul
copy "FITUR_ROLE_PANITIA_TES_PSIKOLOGI_COMPLETE.md" "%UPLOAD_DIR%\docs\" >nul 2>nul
copy "FITUR_BOX_INFORMASI_TES_PSIKOLOGI_UPDATE.md" "%UPLOAD_DIR%\docs\" >nul 2>nul
echo    ✓ Documentation copied

:: Create README for upload directory
echo Creating README in upload directory...
(
echo # PRODUCTION UPLOAD - 27 Januari 2026
echo.
echo ## File Summary
echo - Total Files: 77
echo - Migrations: 22 (termasuk role panitia_tes_psikologi dan box informasi)
echo - Models: 5
echo - Exports: 7
echo - Controllers: 1
echo - Livewire Components: 18 (Admin: 8, Auth: 3, Panitia: 3, Pendaftar: 1)
echo - Views: 22 (Admin: 6 + 6 partials, Panitia: 3, Pendaftar: 3, Layouts: 1, Tab Templates: 3)
echo - Routes: 1
echo - Utilities: 4
echo.
echo ## Upload Instructions
echo.
echo 1. Backup production database terlebih dahulu
echo 2. Upload semua file sesuai struktur direktori
echo 3. Jalankan: php artisan migrate
echo 4. Jalankan: php scripts/cleanup_duplicates.php
echo 5. Clear cache: php artisan optimize:clear
echo 6. Test semua fitur baru
echo.
echo ## Detailed Documentation
echo Lihat file PRODUCTION_UPLOAD_CHECKLIST_4_HARI.md di folder docs/
echo.
) > "%UPLOAD_DIR%\README.md"

echo.
echo ====================================
echo  SUMMARY
echo ====================================
echo  ✓ Total files prepared: 77
echo  ✓ Migrations: 22
echo  ✓ Application files: 54
echo  ✓ Documentation: 11+
echo  ✓ Output directory: %UPLOAD_DIR%
echo ====================================
echo.
echo  📦 Files are ready for production upload!
echo  📁 Location: %CD%\%UPLOAD_DIR%
echo.
echo  Next steps:
echo  1. Review files in %UPLOAD_DIR%
echo  2. Zip the folder for upload
echo  3. Follow instructions in docs/PRODUCTION_UPLOAD_CHECKLIST_4_HARI.md
echo.
pause
