<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SurveyQuestion;
use App\Models\SurveyOption;

class SurveyQuestionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pertanyaan 1: Sumber Informasi Pendaftaran
        $question1 = SurveyQuestion::create([
            'question_text' => 'Darimana anda mengetahui informasi pendaftaran ini?',
            'question_type' => 'dropdown',
            'is_required' => true,
            'order' => 1,
            'is_active' => true,
            'description' => 'Sumber informasi tentang pendaftaran'
        ]);

        $informationSources = [
            'Media Sosial (Facebook, Instagram, WhatsApp, dll)',
            'Website Sekolah',
            'Brosur/Spanduk',
            'Teman/Keluarga',
            'Alumni',
            'Guru/Ustadz',
            'Lainnya'
        ];

        foreach ($informationSources as $index => $source) {
            SurveyOption::create([
                'survey_question_id' => $question1->id,
                'option_text' => $source,
                'order' => $index + 1
            ]);
        }

        // Pertanyaan 2: Minat Kelas Unggulan
        $question2 = SurveyQuestion::create([
            'question_text' => 'Berminat Jurusan apa di kelas unggulan?',
            'question_type' => 'dropdown',
            'is_required' => true,
            'order' => 2,
            'is_active' => true,
            'description' => 'Pilihan kelas unggulan'
        ]);

        $kelasUnggulan = [
            'Kelas SKS/CBI',
            'Kelas Tahfidz',
            'Kelas Olimpiade',
            'Kelas Bilingual'
        ];

        foreach ($kelasUnggulan as $index => $kelas) {
            SurveyOption::create([
                'survey_question_id' => $question2->id,
                'option_text' => $kelas,
                'order' => $index + 1
            ]);
        }

        // Pertanyaan 3: Minat Ma'had (Asrama)
        $question3 = SurveyQuestion::create([
            'question_text' => 'Apakah Berminat Masuk Ke Ma\'had? (Asrama Sekolah)',
            'question_type' => 'dropdown',
            'is_required' => true,
            'order' => 3,
            'is_active' => true,
            'description' => 'Data awal untuk panitia PSB Mahad'
        ]);

        $mahad = ['Ya', 'Tidak', 'Masih Mempertimbangkan'];

        foreach ($mahad as $index => $option) {
            SurveyOption::create([
                'survey_question_id' => $question3->id,
                'option_text' => $option,
                'order' => $index + 1
            ]);
        }
    }
}
