<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tahfidz extends Model
{
    protected $table = 'tahfidz';

    protected $fillable = [
        'pendaftar_id',
        'juz_alquran_dihafal',
        'file_bukti_syahadah',
        'file_bukti_tahsin',
    ];

    /**
     * Get the pendaftar that owns the tahfidz.
     */
    public function pendaftar(): BelongsTo
    {
        return $this->belongsTo(Pendaftar::class);
    }
}
