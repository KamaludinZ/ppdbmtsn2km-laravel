# 📚 Dokumentasi PPDB MTsN 2 Kota Malang - Index

Panduan lengkap untuk setup, deployment, dan maintenance aplikasi PPDB.

---

## 🎯 Dokumentasi Materialized Views & Sorting (Baru)

### [SORTABLE_TABLE_GUIDE.md](SORTABLE_TABLE_GUIDE.md) ⭐ **[BARU]**
**Panduan Implementasi Sortable Table Headers**

📖 **Isi:**
- Cara implementasi sorting di tabel
- Trait dan component yang sudah dibuat
- Step-by-step guide untuk semua halaman
- Contoh implementasi lengkap
- Troubleshooting & tips

🔑 **Untuk siapa:** Developer
⏱️ **Waktu baca:** 10 menit
✅ **Status:** 2 halaman sudah diimplementasi (Admin & Panitia Pendaftar)

---

### [MATERIALIZED_VIEWS_SETUP.md](MATERIALIZED_VIEWS_SETUP.md)
**Panduan Setup Lengkap - WAJIB BACA**

📖 **Isi:**
- Pengenalan materialized views
- Setup development
- Setup cron job (Windows & Linux)
- Panduan deployment production
- Monitoring & troubleshooting
- Maintenance commands

🔑 **Untuk siapa:** Developer, System Admin
⏱️ **Waktu baca:** 15 menit

---

### [DEPLOYMENT_CHECKLIST.md](DEPLOYMENT_CHECKLIST.md)
**Checklist Deploy ke Production**

📖 **Isi:**
- Pre-deployment checklist
- Step-by-step deployment
- Post-deployment verification
- Rollback plan
- Monitoring guidelines
- Emergency contacts template

🔑 **Untuk siapa:** System Admin, Lead Developer
⏱️ **Waktu baca:** 10 menit
✅ **Gunakan saat:** Sebelum & sesudah deployment

---

### [QUICK_REFERENCE.md](QUICK_REFERENCE.md)
**Command Reference - BOOKMARK INI!**

📖 **Isi:**
- Command materialized views
- Cron job commands
- Maintenance commands
- Database backup/restore
- Deployment commands
- Emergency commands
- SQL queries berguna

🔑 **Untuk siapa:** Semua (Developer, Admin)
⏱️ **Waktu baca:** 5 menit
⭐ **Tips:** Bookmark untuk akses cepat!

---

### [scripts/README.md](scripts/README.md)
**Dokumentasi Helper Scripts**

📖 **Isi:**
- Penjelasan semua script di folder `scripts/`
- Cara menggunakan setiap script
- Customization options
- Troubleshooting

🔑 **Untuk siapa:** System Admin
⏱️ **Waktu baca:** 5 menit

---

## 📜 Dokumentasi Aplikasi (Existing)

### [README.md](README.md)
**Dokumentasi Utama Aplikasi**

📖 **Isi:**
- Overview aplikasi
- Features
- Installation
- Configuration
- Usage

---

### [DEMO_ACCOUNTS.md](DEMO_ACCOUNTS.md)
**Akun Demo untuk Testing**

📖 **Isi:**
- Login credentials untuk demo
- Role-based accounts
- Testing scenarios

🔑 **Untuk siapa:** Tester, QA
⏱️ **Waktu baca:** 2 menit

---

### [UI-UX-GUIDE.md](UI-UX-GUIDE.md)
**Panduan UI/UX Design**

📖 **Isi:**
- Design system
- Component guidelines
- Color scheme
- Typography

🔑 **Untuk siapa:** Frontend Developer, Designer
⏱️ **Waktu baca:** 10 menit

---

### [ALUR_PENDAFTARAN_DAN_DAFTAR_ULANG.md](ALUR_PENDAFTARAN_DAN_DAFTAR_ULANG.md)
**Alur Proses Pendaftaran**

📖 **Isi:**
- Flow pendaftaran
- Flow daftar ulang
- Business logic
- Status transitions

🔑 **Untuk siapa:** Developer, Business Analyst
⏱️ **Waktu baca:** 8 menit

---

### [PERBAIKAN_KELULUSAN.md](PERBAIKAN_KELULUSAN.md)
**Dokumentasi Perbaikan Fitur Kelulusan**

📖 **Isi:**
- Changelog kelulusan feature
- Bug fixes
- Improvements

🔑 **Untuk siapa:** Developer
⏱️ **Waktu baca:** 5 menit

---

## 🚀 Quick Start Guides

### Untuk Developer Baru

1. **Baca dulu:**
   - [ ] [README.md](README.md) - Overview aplikasi
   - [ ] [DEMO_ACCOUNTS.md](DEMO_ACCOUNTS.md) - Akun testing
   - [ ] [ALUR_PENDAFTARAN_DAN_DAFTAR_ULANG.md](ALUR_PENDAFTARAN_DAN_DAFTAR_ULANG.md) - Business logic

2. **Setup Development:**
   - [ ] Install dependencies
   - [ ] Setup database
   - [ ] Run migrations (termasuk materialized views)
   - [ ] Seed database
   - [ ] Test aplikasi

3. **Materialized Views:**
   - [ ] Baca [MATERIALIZED_VIEWS_SETUP.md](MATERIALIZED_VIEWS_SETUP.md) bagian "Setup Development"
   - [ ] Test refresh command: `php artisan dashboard:refresh-views`
   - [ ] Bookmark [QUICK_REFERENCE.md](QUICK_REFERENCE.md)

---

### Untuk System Admin (Deployment)

1. **Pre-Deployment:**
   - [ ] Baca [DEPLOYMENT_CHECKLIST.md](DEPLOYMENT_CHECKLIST.md)
   - [ ] Baca [MATERIALIZED_VIEWS_SETUP.md](MATERIALIZED_VIEWS_SETUP.md) bagian "Deploy ke Production"
   - [ ] Setup backup strategy
   - [ ] Setup monitoring

2. **Deployment:**
   - [ ] Follow [DEPLOYMENT_CHECKLIST.md](DEPLOYMENT_CHECKLIST.md) step-by-step
   - [ ] Atau gunakan automated script: `./deploy.sh production`
   - [ ] Setup cron job: `sudo ./scripts/setup-cron.sh`

3. **Post-Deployment:**
   - [ ] Run health check: `./scripts/check-health.sh`
   - [ ] Monitor views: `./scripts/monitor-views.sh`
   - [ ] Keep [QUICK_REFERENCE.md](QUICK_REFERENCE.md) handy

---

### Untuk Maintenance

**Daily:**
- [ ] Check logs: `tail -f storage/logs/laravel.log`
- [ ] Monitor views: `./scripts/monitor-views.sh`

**Weekly:**
- [ ] Run health check: `./scripts/check-health.sh`
- [ ] Database backup
- [ ] Review performance

**Monthly:**
- [ ] Full system review
- [ ] Update dependencies
- [ ] Security audit

👉 **Reference:** [QUICK_REFERENCE.md](QUICK_REFERENCE.md) untuk command-command

---

## 🗂️ File Structure

```
ppdbmtsn2km-laravel/
│
├── 📄 README.md                                 # Overview aplikasi
├── 📄 SORTABLE_TABLE_GUIDE.md                   # ⭐ Sortable table guide (BARU!)
├── 📄 MATERIALIZED_VIEWS_SETUP.md               # ⭐ Setup materialized views
├── 📄 DEPLOYMENT_CHECKLIST.md                   # ⭐ Deployment guide
├── 📄 QUICK_REFERENCE.md                        # ⭐ Command reference
├── 📄 DOCUMENTATION_INDEX.md                    # ⭐ Index ini
│
├── 📄 UI-UX-GUIDE.md                            # Design guidelines
├── 📄 ALUR_PENDAFTARAN_DAN_DAFTAR_ULANG.md     # Business flow
├── 📄 PERBAIKAN_KELULUSAN.md                   # Feature changelog
├── 📄 DEMO_ACCOUNTS.md                         # Testing accounts
│
├── 🔧 deploy.sh                                 # ⭐ Automated deployment
│
├── app/Traits/
│   └── WithSorting.php                          # ⭐ Trait untuk sorting
│
├── resources/views/components/
│   └── sortable-header.blade.php                # ⭐ Component sortable header
│
└── scripts/
    ├── 📄 README.md                             # Scripts documentation
    ├── 🔧 setup-cron.sh                         # Setup cron job
    ├── 🔧 monitor-views.sh                      # Monitor materialized views
    ├── 🔧 check-health.sh                       # Health check
    ├── 🔧 scheduler.bat                         # Windows scheduler
    └── 🔧 refresh-views.bat                     # Windows refresh

⭐ = Fitur baru (materialized views & sortable tables)
```

---

## 📋 Cheat Sheet

### Materialized Views Commands

```bash
# Refresh views
php artisan dashboard:refresh-views
php artisan dashboard:refresh-views --concurrent

# Monitor
./scripts/monitor-views.sh

# Setup cron (once)
sudo ./scripts/setup-cron.sh
```

### Deployment Commands

```bash
# Automated
./deploy.sh production

# Manual
php artisan down
git pull
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan dashboard:refresh-views
php artisan optimize
sudo systemctl restart php8.1-fpm nginx
php artisan up
```

### Health Check

```bash
./scripts/check-health.sh
./scripts/monitor-views.sh
tail -f storage/logs/laravel.log
```

👉 **More commands:** [QUICK_REFERENCE.md](QUICK_REFERENCE.md)

---

## 🆘 Troubleshooting Quick Links

**Issue** | **Documentation** | **Section**
---|---|---
Views tidak refresh | [MATERIALIZED_VIEWS_SETUP.md](MATERIALIZED_VIEWS_SETUP.md) | Monitoring & Troubleshooting
Deployment gagal | [DEPLOYMENT_CHECKLIST.md](DEPLOYMENT_CHECKLIST.md) | Rollback Plan
Cron tidak jalan | [MATERIALIZED_VIEWS_SETUP.md](MATERIALIZED_VIEWS_SETUP.md) | Setup Cron Job
Performance lambat | [QUICK_REFERENCE.md](QUICK_REFERENCE.md) | Performance Monitoring
Database error | [QUICK_REFERENCE.md](QUICK_REFERENCE.md) | Database Backup & Restore
Emergency | [QUICK_REFERENCE.md](QUICK_REFERENCE.md) | Emergency Commands

---

## 📊 Database Materialized Views

Views yang dibuat untuk optimasi dashboard:

| View Name | Purpose | Refresh Interval |
|-----------|---------|------------------|
| `mv_dashboard_stats` | Statistik utama pendaftar | 5-15 menit |
| `mv_jalur_stats` | Statistik per jalur | 5-15 menit |
| `mv_recent_pendaftar` | 100 pendaftar terbaru | 5-15 menit |
| `mv_panitia_stats` | Statistik panitia aktif | 5-15 menit |

**Performance:** Dashboard load time reduced **5-10x** faster!

---

## 🔗 External Resources

- **Laravel Documentation:** https://laravel.com/docs
- **PostgreSQL Materialized Views:** https://www.postgresql.org/docs/current/sql-creatematerializedview.html
- **Laravel Scheduling:** https://laravel.com/docs/scheduling
- **Livewire:** https://laravel-livewire.com/docs

---

## 📝 Contributing

Jika menambahkan dokumentasi baru:

1. Tambahkan file di root atau folder yang sesuai
2. Update `DOCUMENTATION_INDEX.md` ini
3. Link dari dokumentasi terkait
4. Update tanggal di footer

---

## 📞 Support

**Technical Issues:**
- Check relevant documentation above
- Review logs: `storage/logs/`
- Run health check: `./scripts/check-health.sh`

**Documentation Issues:**
- Review [QUICK_REFERENCE.md](QUICK_REFERENCE.md)
- Check [MATERIALIZED_VIEWS_SETUP.md](MATERIALIZED_VIEWS_SETUP.md)

---

**Dokumentasi PPDB MTsN 2 Kota Malang**
**Last Updated:** 2026-01-11
**Version:** 2.0 (with Materialized Views)
