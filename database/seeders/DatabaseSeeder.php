<?php

namespace Database\Seeders;

use App\Models\DaftarUlang;
use App\Models\Dokumen;
use App\Models\JalurPendaftaran;
use App\Models\Panitia;
use App\Models\Pembayaran;
use App\Models\Pendaftar;
use App\Models\Seragam;
use App\Models\User;
use App\Models\VerifikasiBerkas;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Static & Config Data
        $this->call([
            SettingSeeder::class,
            TentangSeeder::class,
            JalurPendaftaranSeeder::class,
            TimelinePendaftaranSeeder::class,
            AsalSekolahSeeder::class,
            KontenInformasiSeeder::class,
            LinkTerkaitSeeder::class,
            TestimoniSeeder::class,
            PersyaratanUmumSeeder::class,
            VideoPanduanSeeder::class,
            InfografisSeeder::class,
            FaqSeeder::class,
            KontakPanitiaSeeder::class,
            FasilitasSeeder::class,
        ]);

        // 2. Users & Roles
        $this->call(UserSeeder::class);
        $this->call(PanitiaSeeder::class);

        // 3. Demo Pendaftar Data (Detailed Happy Paths)
        $this->createDemoPendaftar();

        // 4. Random Bulk Data (Commented out to avoid errors)
        // Create 20 random pendaftar with various states
        // Pendaftar::factory()->count(20)->create()->each(function ($pendaftar) {
        //     // Add some documents
        //     Dokumen::factory()->count(rand(1, 3))->create(['pendaftar_id' => $pendaftar->id]);
        // });

        $this->command->info('All database seeds completed successfully!');
        $this->command->info('');
        $this->command->info('Login Credentials:');
        $this->command->info('Admin: admin@mtsn2kotamalang.sch.id / password');
        $this->command->info('Panitia: panitia1@mtsn2kotamalang.sch.id / password');
        $this->command->info('Pendaftar: ahmad.maulana@example.com / password');
    }

    private function createDemoPendaftar()
    {
        // Get necessary references
        $jalurPrestasi = JalurPendaftaran::where('kode', 'prestasi_akademik')->first();
        $jalurReguler = JalurPendaftaran::where('kode', 'reguler')->first();
        $jalurTahfidz = JalurPendaftaran::where('kode', 'tahfidz')->first();
        
        $panitiaPendaftaran = Panitia::where('role', 'panitia_pendaftaran')->first();
        $panitiaDaftarUlang = Panitia::where('role', 'panitia_daftarulang')->first();

        $pendaftarData = [
            [
                'email' => 'ahmad.maulana@example.com',
                'jalur_id' => $jalurPrestasi->id,
                'nomor' => 'SPMBM-2025-001234',
                'nisn' => '0012345678',
                'nama' => 'Ahmad Maulana Yusuf',
                'data' => [
                    'tempat_lahir' => 'Malang',
                    'tanggal_lahir' => '2012-01-15',
                    'jenis_kelamin' => 'L',
                    'agama' => 'Islam',
                    'alamat' => 'Jl. Soekarno Hatta No. 123, Malang',
                    'telepon' => '081234567890',
                    'nama_ayah' => 'Budi Santoso',
                    'nama_ibu' => 'Siti Aminah',
                    'pekerjaan_ayah' => 'Wiraswasta',
                    'pekerjaan_ibu' => 'Ibu Rumah Tangga',
                    'penghasilan_ortu' => '5-10 juta',
                    'telepon_ortu' => '081234567899',
                    'asal_sekolah' => 'SDN 1 Malang',
                    'nilai_rata_rata' => 88.50,
                    'prestasi' => 'Juara 1 Olimpiade Matematika Tingkat Kota',
                    'status_pendaftaran' => 'menunggu',
                    'tanggal_daftar' => now(),
                ]
            ],
            [
                'email' => 'siti.aisyah@example.com',
                'jalur_id' => $jalurReguler->id,
                'nomor' => 'SPMBM-2025-000567',
                'nisn' => '0012345679',
                'nama' => 'Siti Aisyah',
                'data' => [
                    'tempat_lahir' => 'Malang',
                    'tanggal_lahir' => '2012-03-20',
                    'jenis_kelamin' => 'P',
                    'agama' => 'Islam',
                    'alamat' => 'Jl. Ijen No. 45, Malang',
                    'telepon' => '081234567891',
                    'nama_ayah' => 'Ahmad Fauzi',
                    'nama_ibu' => 'Fatimah',
                    'pekerjaan_ayah' => 'PNS',
                    'pekerjaan_ibu' => 'Guru',
                    'penghasilan_ortu' => '3-5 juta',
                    'telepon_ortu' => '081234567898',
                    'asal_sekolah' => 'SDN 5 Malang',
                    'nilai_rata_rata' => 85.00,
                    'status_pendaftaran' => 'diterima',
                    'tanggal_daftar' => now()->subDays(10),
                    'tanggal_verifikasi' => now()->subDays(5),
                ]
            ],
            [
                'email' => 'muhammad.fauzi@example.com',
                'jalur_id' => $jalurTahfidz->id,
                'nomor' => 'SPMBM-2025-001235',
                'nisn' => '0012345680',
                'nama' => 'Muhammad Fauzi',
                'data' => [
                    'tempat_lahir' => 'Malang',
                    'tanggal_lahir' => '2012-05-10',
                    'jenis_kelamin' => 'L',
                    'agama' => 'Islam',
                    'alamat' => 'Jl. Veteran No. 78, Malang',
                    'telepon' => '081234567892',
                    'nama_ayah' => 'Hasan Basri',
                    'nama_ibu' => 'Khadijah',
                    'pekerjaan_ayah' => 'Ustadz',
                    'pekerjaan_ibu' => 'Ibu Rumah Tangga',
                    'penghasilan_ortu' => '2-3 juta',
                    'telepon_ortu' => '081234567897',
                    'asal_sekolah' => 'MI Al-Hidayah Malang',
                    'nilai_rata_rata' => 82.00,
                    'prestasi' => 'Hafal 10 Juz Al-Quran',
                    'status_pendaftaran' => 'menunggu',
                    'tanggal_daftar' => now()->subDays(2),
                ]
            ]
        ];

        foreach ($pendaftarData as $item) {
            $user = User::where('email', $item['email'])->first();
            if (!$user) continue;

            // Use updateOrCreate to avoid duplicate entries
            $pendaftar = Pendaftar::updateOrCreate(
                ['nomor_pendaftaran' => $item['nomor']],
                array_merge([
                    'user_id' => $user->id,
                    'jalur_pendaftaran_id' => $item['jalur_id'],
                    'nisn' => $item['nisn'],
                    'nama_lengkap' => $item['nama'],
                    'email' => $item['email'],
                ], $item['data'])
            );

            // Add dummy documents
            Dokumen::updateOrCreate(
                [
                    'pendaftar_id' => $pendaftar->id,
                    'jenis_dokumen' => 'kk',
                ],
                [
                    'nama_file' => 'kartu_keluarga.pdf',
                    'path_file' => 'dummy/kk.pdf',
                    'mime_type' => 'application/pdf',
                    'ukuran_file' => 500000,
                    'status_verifikasi' => 'menunggu',
                    'tanggal_upload' => now(),
                ]
            );

            // Handle "Diterima" status specifically
            if ($item['data']['status_pendaftaran'] === 'diterima' && $panitiaPendaftaran) {
                VerifikasiBerkas::updateOrCreate(
                    ['pendaftar_id' => $pendaftar->id],
                    [
                        'panitia_id' => $panitiaPendaftaran->id,
                        'status' => 'diterima',
                        'catatan' => 'Semua dokumen lengkap dan valid',
                        'tanggal_verifikasi' => now()->subDays(5),
                    ]
                );

                if ($panitiaDaftarUlang) {
                    DaftarUlang::updateOrCreate(
                        ['pendaftar_id' => $pendaftar->id],
                        [
                            'status' => 'selesai',
                            'pembayaran_lunas' => true,
                            'seragam_diukur' => true,
                            'berkas_lengkap' => true,
                            'tanggal_daftar_ulang' => now()->subDays(3),
                            'batas_daftar_ulang' => now()->addMonths(2),
                            'panitia_id' => $panitiaDaftarUlang->id,
                        ]
                    );

                    Pembayaran::updateOrCreate(
                        [
                            'pendaftar_id' => $pendaftar->id,
                            'jenis_pembayaran' => 'daftar_ulang',
                        ],
                        [
                            'jumlah' => 5000000,
                            'metode_pembayaran' => 'transfer',
                            'nomor_referensi' => 'TRF-2025-' . $pendaftar->id,
                            'status' => 'lunas',
                            'tanggal_bayar' => now()->subDays(4),
                            'tanggal_verifikasi' => now()->subDays(3),
                            'verifikator_id' => $panitiaDaftarUlang->id,
                        ]
                    );

                    Seragam::updateOrCreate(
                        ['pendaftar_id' => $pendaftar->id],
                        [
                            'tinggi_badan' => 150,
                            'berat_badan' => 45,
                            'ukuran_baju' => 'M',
                            'ukuran_celana' => 'M',
                            'lingkar_dada' => 80,
                            'lingkar_pinggang' => 70,
                            'panjang_lengan' => 50,
                            'ukuran_sepatu' => '38',
                            'ukuran_topi' => 'M',
                            'sudah_diukur' => true,
                            'sudah_dipesan' => true,
                            'sudah_diterima' => false,
                            'tanggal_ukur' => now()->subDays(3),
                            'tanggal_pesan' => now()->subDays(2),
                            'petugas_id' => $panitiaDaftarUlang->id,
                        ]
                    );
                }
            }
        }
    }
}