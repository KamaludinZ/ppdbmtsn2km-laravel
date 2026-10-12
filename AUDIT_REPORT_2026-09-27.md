# Laporan Audit Aplikasi PMBM — 27 September 2026

Audit tampilan, fungsi, dan keamanan untuk semua role (admin, 4 jenis panitia, pendaftar) dan halaman publik.
Semua halaman GET untuk semua role dicek otomatis (158 halaman, desktop 1366px + HP 390px).

## 1. Error production yang diperbaiki

| Masalah | Penyebab | Perbaikan |
|---|---|---|
| `array_sum(): Addition is not supported on type string` di Detail Pendaftar, Daftar Ulang, Tes Psikologi | `nominal_biaya` berbentuk `{"name": "...", "amount": "..."}` sehingga teks `name` ikut dijumlahkan; format rupiah (`"Rp 150.000"`) juga tidak bisa dijumlah | Helper `App\Support\Nominal` (`sum`, `toNumber`, `numericValues`) dipakai di semua view, PDF invoice, dan export |
| Batalkan persetujuan daftar ulang gagal diam-diam | Kolom `tanggal_setuju_daftar_ulang` sudah tidak ada | Pakai `status_persetujuan_daftar_ulang = belum_memilih` + `tanggal_pilihan_daftar_ulang` |
| Preview invoice PDF error bila data belum ada | Akses properti pada `null` | Null-safe (`?->`) |
| Seeder gagal | Status `selesai` tidak ada di constraint, kolom seragam salah | Seeder disesuaikan |

## 2. Rekap daftar ulang yang konsisten

Sebelumnya pendaftar yang **belum konfirmasi** daftar ulang tidak masuk rekap/laporan, sehingga jumlahnya tidak cocok.

Sekarang (`App\Support\RekapDaftarUlang`), setiap pendaftar **lulus/diterima** masuk **tepat satu** kategori:

| Kategori | Arti |
|---|---|
| Belum Konfirmasi | Belum memilih setuju/tidak dan belum ada proses daftar ulang |
| Tidak Melanjutkan | Memilih tidak setuju |
| Setuju, Belum Mengisi | Setuju tapi belum mengisi form |
| Menunggu Verifikasi | Sudah mengisi, menunggu panitia |
| Perlu Perbaikan | Diminta memperbaiki |
| Ditolak | Ditolak panitia |
| Selesai Daftar Ulang | Disetujui |

Jumlah semua kategori = **Wajib Daftar Ulang**. Dipakai sama persis di menu Daftar Ulang (admin, panitia daftar ulang,
panitia seragam), menu Laporan, dan export Excel (kolom baru "Status Rekap", filter per kategori). Kartu kategori bisa diklik
untuk memfilter tabel.

## 3. Captcha gambar angka

`App\Support\Captcha`: kode 6 angka digambar sebagai PNG (GD, digit diputar + noise), disimpan **hanya di session**,
sekali pakai, dibandingkan dengan `hash_equals`. Kode tidak pernah dikirim ke HTML. Dipakai di login pendaftar
(beranda + /login) dan login admin/panitia. Ditambah pembatasan 5 percobaan login/menit per email+IP.

## 4. Cek status pendaftaran tanpa login

- Kotak **Cek Status Pendaftaran** di beranda (di atas Timeline Pendaftaran) dan halaman `/cek-pendaftaran` memakai
  komponen yang sama (`App\Livewire\Public\CekPendaftaran`).
- Input dirapikan (spasi & huruf kecil diterima), bisa nomor pendaftaran atau NISN.
- Maksimal 10 pencarian/menit per IP (mencegah tebak NISN massal), NISN ditampilkan tersamar (`88888•••••`).
- Status daftar ulang memakai kategori rekap yang sama dengan laporan.

## 5. Keamanan

| Temuan | Risiko | Perbaikan |
|---|---|---|
| Pengumuman HTML (bisa dibuat panitia) ditampilkan apa adanya — "Trix output aman" padahal isi dikirim dari browser | **Tinggi** — stored XSS ke dashboard pendaftar, admin, halaman publik | `App\Support\HtmlSanitizer` (allowlist tag/atribut, buang `script`, `on*`, `javascript:`); dipakai di pengumuman, profil, visi, jalur, informasi, FAQ, info daftar ulang |
| Aksi Livewire tidak mengecek role ulang | **Sedang** — snapshot komponen admin bisa dipakai akun lain (terbukti: sebelum 200, sesudah 403) | `CheckRole` didaftarkan sebagai persistent middleware Livewire |
| Akun yang dinonaktifkan tetap bisa memakai sesi lama | Sedang | `CheckRole` langsung logout akun nonaktif |
| IDOR di formulir pendaftar (prestasi, beasiswa, pendidikan lain, keahlian, tahfidz) | **Tinggi** — bisa mengubah/menghapus data pendaftar lain lewat ID | Query lewat relasi milik pendaftar sendiri |
| `/file/view` membaca atribut apa saja, daftar role salah (`panitia` tidak ada, panitia daftar ulang tidak termasuk) | Sedang | Whitelist kolom file per model, role pakai `isAdmin()/isPanitia()`, file selain PDF/gambar diunduh (bukan inline) |
| Upload KK/Akta/Ijazah/SKHUN oleh admin/panitia disimpan di disk publik | Sedang — data pribadi bisa diakses lewat URL | Disimpan di disk `private` |
| Log Monitoring: nama file log bisa `../` | Rendah (admin saja) | Nama & tanggal divalidasi, file besar dibaca bagian akhirnya saja |
| Backup download/restore memakai nama file mentah | Sedang (admin) | `basename()` |
| Route demo `/welcome`, `/test-livewire` aktif di production | Rendah | Hanya di environment `local` |
| Pesan 404 membocorkan path file server | Rendah | Pesan umum |

Sudah baik: upload divalidasi `mimes` (PDF/JPG/PNG), header keamanan (`nosniff`, `SAMEORIGIN`) sudah terpasang,
semua grup route memakai middleware role.

## 6. Tampilan (UI)

- **Tailwind v4 tidak mengenal `bg-opacity-*`** → 248 kelas di 52 file diubah ke sintaks `/N` (overlay modal yang tadinya hitam pekat/transparan kini benar).
- **CSS production** (`public/css/app-production-full.css`) tertinggal ±240 kelas → skrip `npm run build:css` membangun ulang otomatis.
- Input/select/textarea tanpa style (plugin forms v3 tidak terbaca di v4) → aturan dasar di `resources/css/app.css`.
- Kartu statistik: angka tidak lagi menabrak ikon (`min-w-0`, grid 2 kolom di HP).
- Header publik: menu lengkap hanya di layar ≥1536px; di laptop 1280–1440px sebelumnya menu menimpa logo dan tombol terpotong.
- Tabel lebar diberi scroll horizontal, tab bisa di-scroll di HP, toggle "Aksi Opsi" dibuat ringkas.
- Log Monitoring memakai kelas Bootstrap (tidak tampil) → ditulis ulang dengan Tailwind.

Sisa yang disengaja: ikon mata di kolom password, halaman demo `/test-livewire`, preview kartu A4.

## 6b. Menu Laporan admin (`/admin/laporan`)

Diuji dengan 612 pendaftar (termasuk 101 pendaftar di jalur yang sudah dinonaktifkan).

| Masalah | Penyebab | Perbaikan |
|---|---|---|
| Pindah tab ±8 detik | Setiap klik menghitung statistik **semua** tab; tab Data Lengkap memanggil `getEmisCompletenessDetails()` 11× per pendaftar (±33 query per pendaftar) | Hanya tab aktif yang dihitung, query GROUP BY, `withExists` → pindah tab ±0,1–0,3 detik |
| Tab bar melebar, isi halaman tergeser ke kiri saat membuka tab Tes Psikologi | Tab tidak bisa di-scroll | Tab bar bisa digeser, tab aktif otomatis terlihat |
| Jalur nonaktif hilang dari tabel per jalur, filter, Excel & PDF | Hanya `JalurPendaftaran::aktif()` | `App\Support\RekapPendaftaran`: jalur nonaktif yang masih punya pendaftar tetap tampil (label "nonaktif"), total per jalur = total pendaftar |
| Export Daftar Ulang Seragam & Ukuran hanya 184 dari 246 wajib | Berbasis tabel `seragam` | Berbasis semua pendaftar wajib daftar ulang + kolom "Status Rekap" (trait `PendaftarWajibDaftarUlang`) |
| Export mengabaikan filter (status kelulusan, jalur, status seragam) | Parameter tidak diteruskan | Link unduh `admin.laporan.export/{jenis}` meneruskan semua filter; jumlah baris ditampilkan sebelum unduh |
| Dropdown "Status Pembayaran" di tab Daftar Ulang Seragam error | Properti `filterStatus` tidak ada | Diganti filter Status Rekap |
| Error JS `showProgressDU is not defined` dan progress bar palsu 30 detik | Modal progres Alpine | Dihapus, diganti tombol unduh biasa dengan status "Menyiapkan file…" |
| Kelengkapan data semua 0 tanpa konteks | Kartu hanya angka | Tampil "x / y (z%)" per bagian; bagian yang tidak berlaku tidak dihitung |

Hasil export diverifikasi: jumlah baris dan kolom "Status Rekap" sama persis dengan angka di layar untuk semua kombinasi filter.

## 6c. Persetujuan melanjutkan daftar ulang (12 Oktober 2026)

| Masalah | Akibat | Perbaikan |
|---|---|---|
| Pilihan "Tidak Melanjutkan" membuat record `daftar_ulang` berstatus `selesai` yang ditolak database | Pilihan & alasan tersimpan setengah, pendaftar melihat pesan error | Keputusan dicatat di data pendaftar saja, dalam satu transaksi |
| Kotak pilihan di dashboard hanya untuk status `lulus` | Pendaftar `diterima` tidak bisa memilih → selalu "belum konfirmasi" | Berlaku untuk lulus **dan** diterima |
| Halaman daftar ulang hanya untuk `lulus` | Pendaftar `diterima` yang setuju tidak bisa mengisi daftar ulang | Sama: lulus & diterima |
| Pilihan "Lainnya" tidak memunculkan kolom isian | Pendaftar tidak bisa menulis alasan sendiri | Pilihan alasan langsung diperbarui (`wire:model.live`) |
| Klik ganda / setuju setelah reset | Record daftar ulang ganda atau error unik | Transaksi + kunci baris, `firstOrCreate` |
| Ubah/reset oleh admin & panitia tanpa validasi, alasan lama tertinggal, reset menghapus bukti bayar | Data tidak konsisten / hilang | `App\Support\PersetujuanDaftarUlang` dipakai semua role; reset tidak menghapus record yang sudah berisi unggahan |
| Data lama hanya punya `setuju_daftar_ulang = true` | Terhitung "belum konfirmasi" | Migrasi normalisasi + logika status ternormalisasi |
| Pendaftar yang sudah memilih lalu statusnya diubah (bukan lulus/diterima) | Hilang dari rekap tanpa diketahui | Peringatan "tidak masuk rekap" + daftar namanya di Laporan |

Laporan → tab Daftar Ulang Madrasah kini menampilkan jumlah Setuju / Tidak Melanjutkan / Belum Memilih (dicek = total wajib),
ringkasan alasan, dan daftar pendaftar yang tidak melanjutkan. Export menambah kolom "Tanggal Pilihan".
Diuji dengan 32 skenario otomatis (semua lulus) dan uji browser.

## 7. Langkah deploy ke production

1. `git pull` branch ini (atau merge PR).
2. `php artisan migrate --force` (normalisasi data persetujuan), lalu `php artisan view:clear && php artisan route:clear && php artisan config:clear`
3. Pastikan ekstensi PHP **GD** aktif (untuk gambar captcha): `php -m | grep -i gd`
4. `APP_ENV=production` di `.env` (agar route demo tidak aktif).
5. Setiap menambah kelas Tailwind baru di view: jalankan `npm run build:css` lalu commit `public/css/app-production-full.css`.

## 8. Saran berikutnya

- Salin data production ke lokal (anonimkan NIK/NISN) untuk audit data lama yang formatnya beragam.
- Bersihkan file `*.backup*` di `app/` dan `resources/views/` agar tidak membingungkan.
- Pertimbangkan membatasi upload SVG pada menu Video & Infografis (saat ini admin boleh upload SVG).
