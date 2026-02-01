<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatistikJalurTahun extends Model
{
    use HasFactory;

    protected $table = 'statistik_jalur_tahun';

    protected $fillable = [
        'statistik_tahun_id',
        'nama_jalur',
        'warna',
        'kuota',
        'total',
        'menunggu',
        'lengkapi',
        'perbaiki',
        'ditolak',
        'disetujui',
        'lulus',
        'tidak_lulus',
        'cadangan',
        'laki_laki',
        'perempuan',
        'madrasah',
        'umum',
        'urutan',
    ];

    public function statistikTahun()
    {
        return $this->belongsTo(StatistikTahun::class, 'statistik_tahun_id');
    }
}
