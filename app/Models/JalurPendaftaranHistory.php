<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JalurPendaftaranHistory extends Model
{
    protected $fillable = [
        'jalur_pendaftaran_id',
        'user_id',
        'konten_html_lama',
        'konten_html_baru',
        'perubahan_tipe'
    ];

    public function jalurPendaftaran(): BelongsTo
    {
        return $this->belongsTo(JalurPendaftaran::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}