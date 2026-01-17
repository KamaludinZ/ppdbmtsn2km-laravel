<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\JalurPendaftaran;
use App\Models\Pendaftar;
use App\Models\Prestasi;
use App\Models\DaftarUlang;
use App\Models\Pembayaran;
use Carbon\Carbon;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data lama jika ada
        \DB::statement('SET CONSTRAINTS ALL DEFERRED');

        // Clear existing demo data
        DaftarUlang::whereHas('pendaftar', function($q) {
            $q->where('email', 'like', 'demo%@test.com');
        })->delete();

        Prestasi::whereHas('pendaftar', function($q) {
            $q->where('email', 'like', 'demo%@test.com');
        })->delete();

        Pembayaran::whereHas('pendaftar', function($q) {
            $q->where('email', 'like', 'demo%@test.com');
        })->delete();

        Pendaftar::where('email', 'like', 'demo%@test.com')->delete();
        User::where('email', 'like', 'demo%@test.com')->delete();

        $this->command->info('🗑️  Data demo lama berhasil dihapus...');

        // Create demo files directory
        Storage::makeDirectory('public/demo-files');
        $this->createDummyFiles();

        // ========================================
        // 1. CREATE DEMO USERS
        // ========================================
        $this->command->info('👤 Membuat akun demo...');

        $adminUser = User::create([
            'name' => 'Admin Demo',
            'email' => 'demo.admin@test.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $this->command->info("✅ Admin: demo.admin@test.com / password");

        // ========================================
        // 2. CREATE JALUR PENDAFTARAN (6 JALUR)
        // ========================================
        $this->command->info('📋 Membuat jalur pendaftaran...');

        $jalurData = [
            // 2 JALUR SUDAH SELESAI (ada pengumuman)
            [
                'nama' => 'Jalur Prestasi',
                'kode' => 'PREST',
                'kuota' => 50,
                'deskripsi' => 'Jalur pendaftaran untuk siswa berprestasi',
                'persyaratan' => json_encode([
                    'Sertifikat prestasi minimal tingkat kabupaten',
                    'Fotokopi raport kelas 5 dan 6',
                    'Surat rekomendasi dari sekolah asal'
                ]),
                'warna' => 'blue',
                'aktif' => true,
                'nilai_minimum' => 75.00,
                'urutan' => 1,
                'paling_populer' => true,
                'icon' => 'trophy',
                'prefix_nomor' => 'PREST',
            ],
            [
                'nama' => 'Jalur Tahfidz',
                'kode' => 'TAHFZ',
                'kuota' => 30,
                'deskripsi' => 'Jalur pendaftaran untuk calon siswa penghafal Al-Quran',
                'persyaratan' => json_encode([
                    'Hafalan minimal 3 juz',
                    'Surat keterangan dari guru tahfidz',
                    'Lulus tes tahfidz'
                ]),
                'warna' => 'green',
                'aktif' => true,
                'nilai_minimum' => 70.00,
                'urutan' => 2,
                'paling_populer' => true,
                'icon' => 'book',
                'prefix_nomor' => 'TAHFZ',
            ],

            // 4 JALUR MASIH BERJALAN
            [
                'nama' => 'Jalur Umum',
                'kode' => 'UMUM',
                'kuota' => 200,
                'deskripsi' => 'Jalur pendaftaran umum untuk semua calon siswa',
                'persyaratan' => json_encode([
                    'Fotokopi Kartu Keluarga',
                    'Fotokopi Akta Kelahiran',
                    'Fotokopi raport kelas 5 dan 6'
                ]),
                'warna' => 'purple',
                'aktif' => true,
                'nilai_minimum' => 65.00,
                'urutan' => 3,
                'paling_populer' => false,
                'icon' => 'users',
                'prefix_nomor' => 'UMUM',
            ],
            [
                'nama' => 'Jalur Afirmasi',
                'kode' => 'AFIR',
                'kuota' => 40,
                'deskripsi' => 'Jalur pendaftaran untuk siswa kurang mampu',
                'persyaratan' => json_encode([
                    'Surat keterangan tidak mampu dari kelurahan',
                    'Fotokopi Kartu Keluarga Sejahtera (KKS)',
                    'Bukti penerima PKH/KIP'
                ]),
                'warna' => 'orange',
                'aktif' => true,
                'nilai_minimum' => 60.00,
                'urutan' => 4,
                'paling_populer' => false,
                'icon' => 'heart',
                'prefix_nomor' => 'AFIR',
            ],
            [
                'nama' => 'Jalur Perpindahan Tugas Orang Tua',
                'kode' => 'PTOT',
                'kuota' => 20,
                'deskripsi' => 'Jalur untuk anak yang ikut orang tua pindah tugas',
                'persyaratan' => json_encode([
                    'Surat perpindahan tugas orang tua',
                    'Fotokopi SK perpindahan',
                    'Surat keterangan dari sekolah asal'
                ]),
                'warna' => 'red',
                'aktif' => true,
                'nilai_minimum' => 65.00,
                'urutan' => 5,
                'paling_populer' => false,
                'icon' => 'truck',
                'prefix_nomor' => 'PTOT',
            ],
            [
                'nama' => 'Jalur Anak Guru/Karyawan',
                'kode' => 'KARY',
                'kuota' => 15,
                'deskripsi' => 'Jalur untuk anak guru atau karyawan MTsN 2 Kota Malang',
                'persyaratan' => json_encode([
                    'Surat keterangan sebagai guru/karyawan MTsN 2 Kota Malang',
                    'Fotokopi SK pengangkatan',
                    'Surat pernyataan kesanggupan'
                ]),
                'warna' => 'teal',
                'aktif' => true,
                'nilai_minimum' => 60.00,
                'urutan' => 6,
                'paling_populer' => false,
                'icon' => 'briefcase',
                'prefix_nomor' => 'KARY',
            ],
        ];

        $jalurList = [];
        foreach ($jalurData as $data) {
            $jalur = JalurPendaftaran::create($data);
            $jalurList[$data['kode']] = $jalur;
            $this->command->info("  ✓ {$data['nama']} (Kuota: {$data['kuota']})");
        }

        // ========================================
        // 3. CREATE PENDAFTAR DENGAN BERBAGAI KONDISI
        // ========================================
        $this->command->info('👥 Membuat data pendaftar...');

        $pendaftarData = [
            // ===== JALUR PRESTASI (SELESAI) =====
            // Pendaftar DITERIMA + DAFTAR ULANG LENGKAP
            [
                'jalur' => 'PREST',
                'nama_lengkap' => 'Ahmad Rizki Maulana',
                'email' => 'demo.prestasi1@test.com',
                'nisn' => '0051234501',
                'status' => 'lulus_daftar_ulang',
                'daftar_ulang' => true,
                'daftar_ulang_complete' => true,
                'prestasi' => [
                    ['nama' => 'Juara 1 Olimpiade Matematika Tingkat Kota', 'tingkat' => 'Kota', 'tahun' => 2024],
                    ['nama' => 'Juara 2 Lomba Karya Ilmiah Remaja Tingkat Provinsi', 'tingkat' => 'Provinsi', 'tahun' => 2023],
                ],
            ],
            [
                'jalur' => 'PREST',
                'nama_lengkap' => 'Siti Nurhaliza',
                'email' => 'demo.prestasi2@test.com',
                'nisn' => '0051234502',
                'status' => 'lulus_daftar_ulang',
                'daftar_ulang' => true,
                'daftar_ulang_complete' => true,
                'prestasi' => [
                    ['nama' => 'Juara 1 MTQ Tingkat Kabupaten', 'tingkat' => 'Kabupaten', 'tahun' => 2024],
                ],
            ],
            // Pendaftar DITERIMA tapi BELUM DAFTAR ULANG
            [
                'jalur' => 'PREST',
                'nama_lengkap' => 'Muhammad Fajar Sidiq',
                'email' => 'demo.prestasi3@test.com',
                'nisn' => '0051234503',
                'status' => 'diterima',
                'daftar_ulang' => false,
                'prestasi' => [
                    ['nama' => 'Juara 3 Lomba Cerdas Cermat Tingkat Kota', 'tingkat' => 'Kota', 'tahun' => 2024],
                ],
            ],
            // Pendaftar TIDAK DITERIMA
            [
                'jalur' => 'PREST',
                'nama_lengkap' => 'Rina Pratiwi',
                'email' => 'demo.prestasi4@test.com',
                'nisn' => '0051234504',
                'status' => 'tidak_diterima',
                'daftar_ulang' => false,
                'prestasi' => [
                    ['nama' => 'Juara Harapan Lomba Pidato Tingkat Sekolah', 'tingkat' => 'Sekolah', 'tahun' => 2024],
                ],
            ],

            // ===== JALUR TAHFIDZ (SELESAI) =====
            [
                'jalur' => 'TAHFZ',
                'nama_lengkap' => 'Abdullah Al-Hafidz',
                'email' => 'demo.tahfidz1@test.com',
                'nisn' => '0051234505',
                'status' => 'lulus_daftar_ulang',
                'daftar_ulang' => true,
                'daftar_ulang_complete' => true,
            ],
            [
                'jalur' => 'TAHFZ',
                'nama_lengkap' => 'Fatimah Az-Zahra',
                'email' => 'demo.tahfidz2@test.com',
                'nisn' => '0051234506',
                'status' => 'lulus_daftar_ulang',
                'daftar_ulang' => true,
                'daftar_ulang_complete' => true,
            ],
            [
                'jalur' => 'TAHFZ',
                'nama_lengkap' => 'Umar bin Khattab',
                'email' => 'demo.tahfidz3@test.com',
                'nisn' => '0051234507',
                'status' => 'diterima',
                'daftar_ulang' => true,
                'daftar_ulang_complete' => false, // Sedang proses daftar ulang
            ],

            // ===== JALUR UMUM (BERJALAN) =====
            [
                'jalur' => 'UMUM',
                'nama_lengkap' => 'Budi Santoso',
                'email' => 'demo.umum1@test.com',
                'nisn' => '0051234508',
                'status' => 'verifikasi',
                'daftar_ulang' => false,
            ],
            [
                'jalur' => 'UMUM',
                'nama_lengkap' => 'Dewi Lestari',
                'email' => 'demo.umum2@test.com',
                'nisn' => '0051234509',
                'status' => 'pending',
                'daftar_ulang' => false,
            ],
            [
                'jalur' => 'UMUM',
                'nama_lengkap' => 'Rudi Hartono',
                'email' => 'demo.umum3@test.com',
                'nisn' => '0051234510',
                'status' => 'ditolak',
                'daftar_ulang' => false,
            ],
            [
                'jalur' => 'UMUM',
                'nama_lengkap' => 'Ani Wijayanti',
                'email' => 'demo.umum4@test.com',
                'nisn' => '0051234511',
                'status' => 'pending',
                'daftar_ulang' => false,
            ],

            // ===== JALUR AFIRMASI (BERJALAN) =====
            [
                'jalur' => 'AFIR',
                'nama_lengkap' => 'Slamet Riyadi',
                'email' => 'demo.afirmasi1@test.com',
                'nisn' => '0051234512',
                'status' => 'verifikasi',
                'daftar_ulang' => false,
            ],
            [
                'jalur' => 'AFIR',
                'nama_lengkap' => 'Nur Aini',
                'email' => 'demo.afirmasi2@test.com',
                'nisn' => '0051234513',
                'status' => 'pending',
                'daftar_ulang' => false,
            ],
        ];

        foreach ($pendaftarData as $index => $data) {
            $user = User::create([
                'name' => $data['nama_lengkap'],
                'email' => $data['email'],
                'password' => Hash::make('password'),
                'role' => 'pendaftar',
                'email_verified_at' => now(),
            ]);

            $pendaftar = Pendaftar::create([
                'user_id' => $user->id,
                'jalur_pendaftaran_id' => $jalurList[$data['jalur']]->id,
                'nama_lengkap' => $data['nama_lengkap'],
                'nisn' => $data['nisn'],
                'nik' => '3573' . str_pad($index + 1, 12, '0', STR_PAD_LEFT),
                'tempat_lahir' => 'Malang',
                'tanggal_lahir' => Carbon::now()->subYears(13)->format('Y-m-d'),
                'jenis_kelamin' => $index % 2 == 0 ? 'L' : 'P',
                'agama' => 'Islam',
                'alamat' => 'Jl. Contoh No. ' . ($index + 1) . ' Malang',
                'kecamatan' => 'Lowokwaru',
                'kelurahan' => 'Dinoyo',
                'kota' => 'Malang',
                'provinsi' => 'Jawa Timur',
                'kode_pos' => '65144',
                'telepon' => '0812345678' . str_pad($index + 1, 2, '0', STR_PAD_LEFT),
                'email' => $data['email'],

                // Data Orang Tua
                'nama_ayah' => 'Bapak ' . explode(' ', $data['nama_lengkap'])[0],
                'pekerjaan_ayah' => ['PNS', 'Wiraswasta', 'Petani', 'Guru'][rand(0, 3)],
                'pendidikan_ayah' => ['SMA', 'D3', 'S1', 'S2'][rand(0, 3)],
                'penghasilan_ayah' => ['< 2 juta', '2-5 juta', '5-10 juta', '> 10 juta'][rand(0, 3)],
                'telepon_ayah' => '0812345600' . str_pad($index + 1, 2, '0', STR_PAD_LEFT),

                'nama_ibu' => 'Ibu ' . explode(' ', $data['nama_lengkap'])[0],
                'pekerjaan_ibu' => ['Ibu Rumah Tangga', 'PNS', 'Wiraswasta', 'Guru'][rand(0, 3)],
                'pendidikan_ibu' => ['SMA', 'D3', 'S1', 'S2'][rand(0, 3)],
                'penghasilan_ibu' => ['< 2 juta', '2-5 juta', '5-10 juta', '> 10 juta'][rand(0, 3)],
                'telepon_ibu' => '0812345700' . str_pad($index + 1, 2, '0', STR_PAD_LEFT),

                // Data Sekolah Asal
                'asal_sekolah' => 'SDN ' . rand(1, 10) . ' Malang',
                'npsn_asal_sekolah' => '20536' . rand(100, 999),
                'alamat_sekolah' => 'Jl. Pendidikan No. ' . rand(1, 100) . ' Malang',

                'status_pendaftaran' => $data['status'],

                // Upload files (dummy)
                'file_kartu_keluarga' => 'demo-files/kk_' . $data['nisn'] . '.pdf',
                'file_akta_kelahiran' => 'demo-files/akta_' . $data['nisn'] . '.pdf',
                'file_pas_foto' => 'demo-files/foto_' . $data['nisn'] . '.jpg',
            ]);

            // Tambahkan dokumen tambahan untuk yang sudah verifikasi/diterima
            if (in_array($data['status'], ['verifikasi', 'diterima', 'lulus_daftar_ulang'])) {
                $pendaftar->update([
                    'file_ijazah' => 'demo-files/ijazah_' . $data['nisn'] . '.pdf',
                    'file_skhun' => 'demo-files/skhun_' . $data['nisn'] . '.pdf',
                    'file_surat_keterangan_lulus' => 'demo-files/skl_' . $data['nisn'] . '.pdf',
                ]);
            }

            // Tambahkan prestasi jika ada
            if (isset($data['prestasi'])) {
                foreach ($data['prestasi'] as $prestasi) {
                    Prestasi::create([
                        'pendaftar_id' => $pendaftar->id,
                        'nama_prestasi' => $prestasi['nama'],
                        'tingkat' => $prestasi['tingkat'],
                        'tahun' => $prestasi['tahun'],
                        'penyelenggara' => 'Dinas Pendidikan',
                        'file_sertifikat' => 'demo-files/sertifikat_' . $data['nisn'] . '_' . $prestasi['tahun'] . '.pdf',
                    ]);
                }
            }

            // Buat data daftar ulang jika diperlukan
            if ($data['daftar_ulang']) {
                $daftarUlang = DaftarUlang::create([
                    'pendaftar_id' => $pendaftar->id,
                    'status' => $data['daftar_ulang_complete'] ? 'selesai' : 'proses',
                    'tanggal_daftar_ulang' => Carbon::now()->subDays(rand(1, 10)),

                    // Status Pembayaran
                    'status_pembayaran' => $data['daftar_ulang_complete'] ? 'lunas' : 'belum_lunas',
                    'nominal_pembayaran' => 3500000,
                    'tanggal_pembayaran' => $data['daftar_ulang_complete'] ? Carbon::now()->subDays(rand(1, 5)) : null,
                    'bukti_pembayaran' => $data['daftar_ulang_complete'] ? 'demo-files/bukti_bayar_' . $data['nisn'] . '.pdf' : null,

                    // Status Seragam
                    'status_seragam' => $data['daftar_ulang_complete'] ? 'sudah' : 'belum',
                    'ukuran_seragam' => $data['daftar_ulang_complete'] ? ['S', 'M', 'L', 'XL'][rand(0, 3)] : null,
                    'tanggal_pengambilan_seragam' => $data['daftar_ulang_complete'] ? Carbon::now()->subDays(rand(1, 3)) : null,

                    // Status Berkas
                    'status_berkas' => $data['daftar_ulang_complete'] ? 'lengkap' : 'belum_lengkap',
                    'catatan_berkas' => $data['daftar_ulang_complete'] ? 'Semua berkas sudah lengkap dan terverifikasi' : 'Menunggu verifikasi berkas',
                ]);

                // Buat data pembayaran
                if ($data['daftar_ulang_complete']) {
                    Pembayaran::create([
                        'pendaftar_id' => $pendaftar->id,
                        'jenis_pembayaran' => 'Biaya Pendaftaran Ulang',
                        'nominal' => 3500000,
                        'status' => 'lunas',
                        'tanggal_pembayaran' => Carbon::now()->subDays(rand(5, 10)),
                        'metode_pembayaran' => ['Transfer Bank', 'Cash', 'Virtual Account'][rand(0, 2)],
                        'bukti_pembayaran' => 'demo-files/bukti_bayar_' . $data['nisn'] . '.pdf',
                        'keterangan' => 'Pembayaran daftar ulang tahun ajaran ' . date('Y') . '/' . (date('Y') + 1),
                    ]);
                }
            }

            $this->command->info("  ✓ {$data['nama_lengkap']} ({$data['jalur']}) - {$data['status']}");
        }

        // ========================================
        // 4. DISPLAY DEMO ACCOUNTS
        // ========================================
        $this->command->info('');
        $this->command->line('╔════════════════════════════════════════════════════════════════╗');
        $this->command->line('║             🎉 DEMO DATA BERHASIL DIBUAT! 🎉                  ║');
        $this->command->line('╠════════════════════════════════════════════════════════════════╣');
        $this->command->line('║                    AKUN DEMO LOGIN                             ║');
        $this->command->line('╠════════════════════════════════════════════════════════════════╣');
        $this->command->line('║                                                                ║');
        $this->command->line('║  👤 ADMIN                                                      ║');
        $this->command->line('║     Email    : demo.admin@test.com                             ║');
        $this->command->line('║     Password : password                                        ║');
        $this->command->line('║                                                                ║');
        $this->command->line('╠════════════════════════════════════════════════════════════════╣');
        $this->command->line('║  🎓 PENDAFTAR - JALUR PRESTASI (Diterima + Daftar Ulang)      ║');
        $this->command->line('║     Email    : demo.prestasi1@test.com                         ║');
        $this->command->line('║     Password : password                                        ║');
        $this->command->line('║                                                                ║');
        $this->command->line('║  🎓 PENDAFTAR - JALUR TAHFIDZ (Diterima + Daftar Ulang)       ║');
        $this->command->line('║     Email    : demo.tahfidz1@test.com                          ║');
        $this->command->line('║     Password : password                                        ║');
        $this->command->line('║                                                                ║');
        $this->command->line('║  🎓 PENDAFTAR - JALUR PRESTASI (Diterima, Belum Daftar Ulang) ║');
        $this->command->line('║     Email    : demo.prestasi3@test.com                         ║');
        $this->command->line('║     Password : password                                        ║');
        $this->command->line('║                                                                ║');
        $this->command->line('║  🎓 PENDAFTAR - JALUR UMUM (Dalam Verifikasi)                 ║');
        $this->command->line('║     Email    : demo.umum1@test.com                             ║');
        $this->command->line('║     Password : password                                        ║');
        $this->command->line('║                                                                ║');
        $this->command->line('║  🎓 PENDAFTAR - JALUR UMUM (Pending)                           ║');
        $this->command->line('║     Email    : demo.umum2@test.com                             ║');
        $this->command->line('║     Password : password                                        ║');
        $this->command->line('║                                                                ║');
        $this->command->line('║  🎓 PENDAFTAR - JALUR UMUM (Ditolak)                           ║');
        $this->command->line('║     Email    : demo.umum3@test.com                             ║');
        $this->command->line('║     Password : password                                        ║');
        $this->command->line('║                                                                ║');
        $this->command->line('╠════════════════════════════════════════════════════════════════╣');
        $this->command->line('║                     RINGKASAN DATA                             ║');
        $this->command->line('╠════════════════════════════════════════════════════════════════╣');
        $this->command->line('║  📋 Total Jalur Pendaftaran: 6                                 ║');
        $this->command->line('║     - Selesai           : 2 (Prestasi, Tahfidz)               ║');
        $this->command->line('║     - Berjalan          : 2 (Umum, Afirmasi)                  ║');
        $this->command->line('║     - Belum Mulai       : 2 (PTOT, Karyawan)                  ║');
        $this->command->line('║                                                                ║');
        $this->command->line('║  👥 Total Pendaftar: 14                                        ║');
        $this->command->line('║     - Lulus + Daftar Ulang Lengkap : 4                         ║');
        $this->command->line('║     - Diterima (Belum Daftar Ulang): 2                         ║');
        $this->command->line('║     - Dalam Verifikasi             : 2                         ║');
        $this->command->line('║     - Pending                      : 4                         ║');
        $this->command->line('║     - Ditolak/Tidak Diterima       : 2                         ║');
        $this->command->line('║                                                                ║');
        $this->command->line('║  🏆 Pendaftar dengan Prestasi: 4                               ║');
        $this->command->line('║  📄 Dokumen Upload: Lengkap untuk yang diterima                ║');
        $this->command->line('║                                                                ║');
        $this->command->line('╚════════════════════════════════════════════════════════════════╝');
        $this->command->info('');
        $this->command->line('📝 Catatan:');
        $this->command->line('   - Semua password adalah: password');
        $this->command->line('   - File dokumen dummy sudah dibuat di storage/app/public/demo-files');
        $this->command->line('   - Data ini untuk keperluan demo dan testing');
        $this->command->info('');
    }

    /**
     * Create dummy PDF and image files
     */
    private function createDummyFiles()
    {
        $this->command->info('📁 Membuat file dummy untuk dokumen...');

        $files = [
            'kk_', 'akta_', 'foto_', 'ijazah_', 'skhun_', 'skl_',
            'sertifikat_', 'bukti_bayar_'
        ];

        // Create simple PDF content
        $pdfContent = "%PDF-1.4
1 0 obj
<<
/Type /Catalog
/Pages 2 0 R
>>
endobj
2 0 obj
<<
/Type /Pages
/Kids [3 0 R]
/Count 1
>>
endobj
3 0 obj
<<
/Type /Page
/Parent 2 0 R
/Resources <<
/Font <<
/F1 4 0 R
>>
>>
/MediaBox [0 0 612 792]
/Contents 5 0 R
>>
endobj
4 0 obj
<<
/Type /Font
/Subtype /Type1
/BaseFont /Helvetica
>>
endobj
5 0 obj
<<
/Length 44
>>
stream
BT
/F1 12 Tf
100 700 Td
(Dokumen Demo) Tj
ET
endstream
endobj
xref
0 6
0000000000 65535 f
0000000009 00000 n
0000000058 00000 n
0000000115 00000 n
0000000262 00000 n
0000000341 00000 n
trailer
<<
/Size 6
/Root 1 0 R
>>
startxref
433
%%EOF";

        // Create simple JPG content (1x1 pixel red image)
        $jpgContent = base64_decode('/9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAAgGBgcGBQgHBwcJCQgKDBQNDAsLDBkSEw8UHRofHh0aHBwgJC4nICIsIxwcKDcpLDAxNDQ0Hyc5PTgyPC4zNDL/2wBDAQkJCQwLDBgNDRgyIRwhMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjL/wAARCAABAAEDASIAAhEBAxEB/8QAFQABAQAAAAAAAAAAAAAAAAAAAAv/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/8QAFQEBAQAAAAAAAAAAAAAAAAAAAAX/xAAUEQEAAAAAAAAAAAAAAAAAAAAA/9oADAMBAAIRAxEAPwCwAA//2Q==');

        for ($i = 1; $i <= 15; $i++) {
            $nisn = '005123450' . $i;

            foreach ($files as $prefix) {
                if (strpos($prefix, 'foto') !== false) {
                    Storage::put("public/demo-files/{$prefix}{$nisn}.jpg", $jpgContent);
                } else {
                    Storage::put("public/demo-files/{$prefix}{$nisn}.pdf", $pdfContent);
                    if ($prefix === 'sertifikat_') {
                        Storage::put("public/demo-files/{$prefix}{$nisn}_2023.pdf", $pdfContent);
                        Storage::put("public/demo-files/{$prefix}{$nisn}_2024.pdf", $pdfContent);
                    }
                }
            }
        }

        $this->command->info('  ✓ File dummy berhasil dibuat');
    }
}
