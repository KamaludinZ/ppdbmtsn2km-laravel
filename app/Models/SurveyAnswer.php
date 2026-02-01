<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'pendaftar_id',
        'survey_question_id',
        'jawaban',
    ];

    protected $casts = [
        'jawaban' => 'array', // Cast otomatis JSON ke array untuk checkbox
    ];

    /**
     * Relationship: Jawaban milik satu pendaftar
     */
    public function pendaftar()
    {
        return $this->belongsTo(Pendaftar::class);
    }

    /**
     * Relationship: Jawaban untuk satu pertanyaan
     */
    public function question()
    {
        return $this->belongsTo(SurveyQuestion::class, 'survey_question_id');
    }

    /**
     * Helper: Get jawaban as array (for checkbox with multiple values)
     * Jika jawaban sudah array, return as is
     * Jika string, wrap dalam array
     */
    public function getJawabanArray()
    {
        if (is_array($this->jawaban)) {
            return $this->jawaban;
        }
        return [$this->jawaban];
    }

    /**
     * Helper: Get jawaban as formatted string
     * Untuk checkbox: comma-separated
     * Untuk lainnya: string biasa
     */
    public function getJawabanFormatted()
    {
        $jawaban = $this->getJawabanArray();

        if (count($jawaban) > 1) {
            return implode(', ', $jawaban);
        }

        return $jawaban[0] ?? '';
    }
}
