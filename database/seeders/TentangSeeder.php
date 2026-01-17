<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class TentangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Konten Profil Madrasah
        Setting::updateOrCreate(
            ['key' => 'page_tentang_content'],
            [
                'value' => '<h2>Profil Madrasah Tsanawiyah Negeri 2 Kota Malang</h2>
                <p>Madrasah Tsanawiyah Negeri 2 Kota Malang berdiri dengan tujuan untuk mencetak generasi muda yang beriman, bertaqwa, berilmu, dan berakhlak mulia. Berlokasi di Jl. Raya Cemorokandang 77 Kota Malang, MTSN 2 Malang telah menjadi salah satu lembaga pendidikan yang terpercaya dalam mendidik putra-putri bangsa.</p>
                <p>Sejak berdiri, madrasah ini telah berkomitmen untuk memberikan pendidikan yang seimbang antara ilmu agama dan ilmu umum. Dengan tenaga pendidik yang profesional dan berpengalaman, MTSN 2 Malang terus berupaya meningkatkan kualitas pendidikan dan menghasilkan lulusan yang siap menghadapi perkembangan zaman.</p>
                <p>Madrasah ini dilengkapi dengan sarana dan prasarana yang memadai untuk mendukung proses pembelajaran, termasuk laboratorium komputer, perpustakaan, masjid, dan fasilitas olahraga. Kurikulum yang diterapkan mengacu pada standar nasional pendidikan dengan penekanan pada pengembangan karakter dan keterampilan abad 21.</p>',
                'type' => 'text',
                'description' => 'Konten Profil Madrasah'
            ]
        );

        // Visi Madrasah
        Setting::updateOrCreate(
            ['key' => 'page_tentang_visi'],
            [
                'value' => '<p>Menjadi madrasah unggul yang berlandaskan nilai-nilai Islam dalam membentuk insan yang beriman, bertaqwa, berilmu, berakhlak mulia, dan berprestasi akademik serta non-akademik.</p>',
                'type' => 'text',
                'description' => 'Visi Madrasah'
            ]
        );

        // Misi Madrasah
        Setting::updateOrCreate(
            ['key' => 'page_tentang_misi'],
            [
                'value' => '<ol>
                    <li>Menyelenggarakan pendidikan yang bermutu berdasarkan nilai-nilai Islam.</li>
                    <li>Mengembangkan potensi peserta didik secara optimal sesuai dengan bakat dan minatnya.</li>
                    <li>Membentuk peserta didik yang berakhlak mulia dan berkepribadian mandiri.</li>
                    <li>Meningkatkan kualitas pendidik dan tenaga kependidikan secara berkelanjutan.</li>
                    <li>Menerapkan manajemen pendidikan yang efektif dan efisien.</li>
                    <li>Meningkatkan kerjasama dengan orang tua, masyarakat, dan instansi terkait.</li>
                </ol>',
                'type' => 'text',
                'description' => 'Misi Madrasah'
            ]
        );

        // Tujuan Madrasah
        Setting::updateOrCreate(
            ['key' => 'page_tentang_tujuan'],
            [
                'value' => '<ul>
                    <li>Menghasilkan lulusan yang unggul dalam bidang akademik dan non-akademik.</li>
                    <li>Menghasilkan lulusan yang memiliki keimanan dan ketaqwaan yang tinggi terhadap Tuhan Yang Maha Esa.</li>
                    <li>Menghasilkan lulusan yang memiliki akhlak mulia dan berkepribadian mandiri.</li>
                    <li>Menghasilkan lulusan yang mampu bersaing secara global dengan tetap memegang nilai-nilai agama dan budaya bangsa.</li>
                    <li>Menghasilkan lulusan yang memiliki keterampilan dan kreativitas dalam menghadapi perkembangan ilmu pengetahuan dan teknologi.</li>
                    <li>Menghasilkan lulusan yang mampu menjadi pemimpin yang amanah dan berkontribusi positif bagi masyarakat.</li>
                </ul>',
                'type' => 'text',
                'description' => 'Tujuan Madrasah'
            ]
        );

        $this->command->info('Data tentang madrasah berhasil diisi!');
    }
}