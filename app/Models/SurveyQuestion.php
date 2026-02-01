<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'pertanyaan',
        'deskripsi',
        'tipe_input',
        'wajib_diisi',
        'urutan',
        'aktif',
        'context',
    ];

    protected $casts = [
        'wajib_diisi' => 'boolean',
        'aktif' => 'boolean',
        'urutan' => 'integer',
    ];

    /**
     * Relationship: Pertanyaan memiliki banyak opsi jawaban
     */
    public function options()
    {
        return $this->hasMany(SurveyQuestionOption::class)->orderBy('urutan');
    }

    /**
     * Relationship: Pertanyaan memiliki banyak jawaban dari pendaftar
     */
    public function answers()
    {
        return $this->hasMany(SurveyAnswer::class);
    }

    /**
     * Scope: Hanya pertanyaan aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }

    /**
     * Scope: Urut berdasarkan urutan
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan');
    }

    /**
     * Scope: Filter by context (umum atau tes_psikologi)
     */
    public function scopeContext($query, $context)
    {
        return $query->where('context', $context);
    }

    /**
     * Scope: Survey Umum only
     */
    public function scopeUmum($query)
    {
        return $query->where('context', 'umum');
    }

    /**
     * Scope: Survey Tes Psikologi only
     */
    public function scopeTesPsikologi($query)
    {
        return $query->where('context', 'tes_psikologi');
    }
}
