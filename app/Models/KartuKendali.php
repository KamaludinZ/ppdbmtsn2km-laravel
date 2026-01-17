<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KartuKendali extends Model
{
    protected $table = 'kartu_kendali';

    protected $fillable = [
        'nama_kegiatan',
        'catatan',
        'urutan',
    ];
}
