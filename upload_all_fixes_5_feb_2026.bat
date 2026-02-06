@echo off
echo ========================================
echo UPLOAD ALL FIXES - 5 FEBRUARI 2026
echo ========================================
echo.
echo FITUR YANG AKAN DI-UPLOAD:
echo 1. History Pesan ^& WhatsApp Queue (3 Feb 2026)
echo 2. Test Email/WA History (4 Feb 2026)
echo 3. File Timestamp Display (5 Feb 2026)
echo 4. Tab Info Akun
echo 5. Tab Survey Movement
echo.
echo TOTAL FILES: 13 files
echo.
pause

echo.
echo ========================================
echo STEP 1: UPLOAD MODELS (1 file)
echo ========================================
echo.
scp app/Models/MessageQueue.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Models/

echo.
echo ========================================
echo STEP 2: UPLOAD SERVICES (1 file)
echo ========================================
echo.
scp app/Services/FileNamingService.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Services/

echo.
echo ========================================
echo STEP 3: UPLOAD CONTROLLERS (5 files)
echo ========================================
echo.
scp app/Livewire/Admin/Pendaftar.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/
scp app/Livewire/Panitia/Pendaftar.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Panitia/
scp app/Livewire/Admin/PengaturanPesan.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/
scp app/Livewire/Admin/Settings.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/
scp app/Livewire/Admin/DetailPendaftar.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/

echo.
scp app/Livewire/Panitia/DetailPendaftar.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Panitia/

echo.
echo ========================================
echo STEP 4: UPLOAD VIEWS (7 files)
echo ========================================
echo.
echo [Admin Views...]
scp resources/views/livewire/admin/pendaftar.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/
scp resources/views/livewire/admin/detail-pendaftar.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/
scp resources/views/livewire/admin/partials/history-email-tab.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/partials/
scp resources/views/livewire/admin/partials/history-whatsapp-tab.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/partials/

echo.
echo [Panitia Views...]
scp resources/views/livewire/panitia/pendaftar.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/panitia/
scp resources/views/livewire/panitia/detail-pendaftar.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/panitia/

echo.
echo [Pendaftar Views...]
scp resources/views/livewire/pendaftar/formulir.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/

echo.
echo ========================================
echo STEP 5: UPLOAD TAB TEMPLATES (1 file)
echo ========================================
echo.
scp resources/views/tab_templates/tab-info-akun.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/tab_templates/

echo.
echo ========================================
echo STEP 6: UPLOAD MIGRATIONS (1 file)
echo ========================================
echo.
scp database/migrations/2026_02_03_210000_create_message_queue_table.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/database/migrations/

echo.
echo ========================================
echo STEP 7: SET PERMISSIONS ^& RUN MIGRATION
echo ========================================
echo.

ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && chmod 644 app/Models/MessageQueue.php && chmod 644 app/Services/FileNamingService.php && chmod 644 app/Livewire/Admin/Pendaftar.php && chmod 644 app/Livewire/Panitia/Pendaftar.php && chmod 644 app/Livewire/Admin/PengaturanPesan.php && chmod 644 app/Livewire/Admin/Settings.php && chmod 644 app/Livewire/Admin/DetailPendaftar.php && chmod 644 app/Livewire/Panitia/DetailPendaftar.php && chmod 644 resources/views/livewire/admin/pendaftar.blade.php && chmod 644 resources/views/livewire/panitia/pendaftar.blade.php && chmod 644 resources/views/livewire/admin/detail-pendaftar.blade.php && chmod 644 resources/views/livewire/panitia/detail-pendaftar.blade.php && chmod 644 resources/views/livewire/admin/partials/history-email-tab.blade.php && chmod 644 resources/views/livewire/admin/partials/history-whatsapp-tab.blade.php && chmod 644 resources/views/livewire/pendaftar/formulir.blade.php && chmod 644 resources/views/tab_templates/tab-info-akun.blade.php && chmod 644 database/migrations/2026_02_03_210000_create_message_queue_table.php"

echo.
echo [Running Migration...]
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan migrate --force"

echo.
echo ========================================
echo STEP 8: CLEAR CACHE
echo ========================================
echo.

ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan optimize:clear && php artisan view:clear && php artisan config:clear && php artisan route:clear && php artisan cache:clear"

echo.
echo ========================================
echo UPLOAD SELESAI!
echo ========================================
echo.
echo TESTING CHECKLIST:
echo.
echo [ ] 1. HISTORY PESAN ^& WHATSAPP QUEUE
echo     - Tab History WhatsApp: Kolom "Isi Pesan" dengan modal
echo     - Tab History WhatsApp: Kolom "Waktu Tersisa" dengan badge
echo     - Tab History Email: 5 kartu statistik
echo     - Filter EMIS di Admin/Panitia Pendaftar
echo.
echo [ ] 2. TEST EMAIL/WA HISTORY
echo     - Test Email dari /admin/settings -^> cek history
echo     - Test WhatsApp dari /admin/settings -^> cek history
echo.
echo [ ] 3. FILE TIMESTAMP DISPLAY
echo     - Upload dokumen baru -^> nama file ada timestamp
echo     - Replace dokumen -^> timestamp berubah
echo     - Format: {jenis}-{nomor}-{nama}-{timestamp}.{ext}
echo.
echo [ ] 4. TAB INFO AKUN
echo     - Tab "Info Akun" muncul di Admin detail pendaftar
echo     - Tab "Info Akun" muncul di Panitia detail pendaftar
echo     - Field WhatsApp orang tua ada
echo.
echo [ ] 5. TAB SURVEY MOVEMENT
echo     - Tab Survey ada di sebelah kanan tab Tahfidz
echo     - Info box menampilkan instruksi lengkap
echo.
echo ========================================
echo DETAIL FITUR YANG TELAH DI-UPLOAD:
echo ========================================
echo.
echo 1. HISTORY PESAN ^& WHATSAPP QUEUE (3 Feb 2026)
echo    - WhatsApp Queue dengan "Isi Pesan" dan "Waktu Tersisa"
echo    - Statistics Overview untuk Email ^& WhatsApp
echo    - Filter EMIS Completeness
echo    - Bug fix pagination error
echo.
echo 2. TEST EMAIL/WA HISTORY (4 Feb 2026)
echo    - Test email/WA sekarang menyimpan history
echo    - Status: pending -^> terkirim/gagal
echo.
echo 3. FILE TIMESTAMP DISPLAY (5 Feb 2026)
echo    - Timestamp pada nama file di tab Dokumen
echo    - Format sistematis dengan FileNamingService
echo    - Contoh: rapor-PPDB2024001-Ahmad_Fulan-20260205_143052.pdf
echo.
echo 4. TAB INFO AKUN
echo    - Tab baru di detail pendaftar
echo    - Data dari registration Step 3
echo    - Editable oleh admin/panitia
echo    - Field WhatsApp orang tua
echo.
echo 5. TAB SURVEY MOVEMENT
echo    - Tab Survey pindah ke kanan tab Tahfidz
echo    - Enhanced info box dengan instruksi lengkap
echo.
echo ========================================
echo CATATAN PENTING:
echo ========================================
echo.
echo 1. Backup database SUDAH/BELUM dilakukan? ___________
echo 2. Migration SUDAH dijalankan: database/migrations/2026_02_03_210000_create_message_queue_table.php
echo 3. Cache SUDAH di-clear
echo 4. Silakan lakukan testing manual sesuai checklist di atas
echo.
echo JIKA ADA ERROR:
echo - Cek log: tail -f storage/logs/laravel.log
echo - Cek migration status: php artisan migrate:status
echo - Rollback jika perlu: php artisan migrate:rollback --step=1
echo.
echo Lihat dokumentasi lengkap di:
echo RANGKUMAN_UPLOAD_LENGKAP_5_FEB_2026.md
echo.
pause
