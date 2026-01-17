<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faqs = [
            [
                'pertanyaan' => 'Apa saja persyaratan pendaftaran?',
                'jawaban' => '<p>Persyaratan pendaftaran meliputi:</p><ul class="list-disc pl-5 mt-2"><li>Foto copy rapor semester 1-5 (rangkap 2)</li><li>Foto copy akta kelahiran (rangkap 2)</li><li>Foto copy kartu keluarga (rangkap 2)</li><li>Pas foto 3x4 (4 lembar)</li><li>Surat keterangan sehat dari dokter</li></ul>',
                'urutan' => 1,
                'aktif' => true,
            ],
            [
                'pertanyaan' => 'Bagaimana cara mendaftar secara online?',
                'jawaban' => '<p>Untuk mendaftar secara online, silakan ikuti langkah-langkah berikut:</p><ol class="list-decimal pl-5 mt-2"><li>Kunjungi halaman pendaftaran di website kami</li><li>Isi formulir pendaftaran dengan data yang benar</li><li>Upload dokumen yang diperlukan</li><li>Lakukan pembayaran biaya pendaftaran</li><li>Tunggu konfirmasi verifikasi dari panitia</li></ol>',
                'urutan' => 2,
                'aktif' => true,
            ],
            [
                'pertanyaan' => 'Berapa biaya pendaftaran?',
                'jawaban' => '<p>Biaya pendaftaran tahun pelajaran 2025/2026 adalah Rp. 250.000,- (dua ratus lima puluh ribu rupiah).</p><p>Pembayaran dapat dilakukan melalui transfer ke rekening sekolah atau langsung ke sekolah.</p>',
                'urutan' => 3,
                'aktif' => true,
            ],
            [
                'pertanyaan' => 'Kapan pengumuman hasil seleksi?',
                'jawaban' => '<p>Pengumuman hasil seleksi akan diumumkan pada tanggal 15 Juni 2025 melalui website sekolah dan pengumuman di halaman depan sekolah.</p><p>Hasil seleksi juga akan dikirimkan melalui email dan SMS ke pendaftar yang terdaftar.</p>',
                'urutan' => 4,
                'aktif' => true,
            ],
            [
                'pertanyaan' => 'Apakah bisa mendaftar lebih dari satu jalur?',
                'jawaban' => '<p>Tidak, setiap calon peserta didik hanya dapat mendaftar pada satu jalur pendaftaran saja.</p><p>Jika ditemukan pendaftar yang mendaftar di lebih dari satu jalur, maka pendaftaran yang pertama kali masuk yang akan diproses.</p>',
                'urutan' => 5,
                'aktif' => true,
            ],
            [
                'pertanyaan' => 'Bagaimana jika lupa password akun?',
                'jawaban' => '<p>Jika Anda lupa password akun pendaftaran, silakan hubungi panitia melalui kontak yang tersedia di halaman kontak.</p><p>Untuk keamanan, panitia akan meminta beberapa informasi untuk verifikasi identitas sebelum mereset password.</p>',
                'urutan' => 6,
                'aktif' => true,
            ],
            [
                'pertanyaan' => 'Apakah ada kuota penerimaan?',
                'jawaban' => '<p>Ya, kuota penerimaan untuk tahun pelajaran 2025/2026 adalah 120 siswa.</p><p>Kuota ini dibagi ke dalam beberapa jalur pendaftaran sesuai dengan ketentuan yang berlaku.</p>',
                'urutan' => 7,
                'aktif' => true,
            ],
            [
                'pertanyaan' => 'Apakah bisa mengganti jalur pendaftaran?',
                'jawaban' => '<p>Pendaftar dapat mengganti jalur pendaftaran sebelum masa pendaftaran ditutup.</p><p>Untuk mengganti jalur, silakan login ke akun pendaftaran dan ubah pilihan jalur pada formulir pendaftaran.</p>',
                'urutan' => 8,
                'aktif' => true,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::create($faq);
        }

        $this->command->info('FAQs seeded successfully!');
    }
}
