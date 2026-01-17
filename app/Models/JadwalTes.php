<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalTes extends Model
{
    protected $table = 'jadwal_tes';

    protected $fillable = [
        'nama_tes',
        'deskripsi',
        'waktu',
        'tempat',
        'urutan',
    ];

    protected $casts = [
        'waktu' => 'datetime',
    ];
}
