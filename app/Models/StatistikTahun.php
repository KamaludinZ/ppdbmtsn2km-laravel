<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatistikTahun extends Model
{
    use HasFactory;

    protected $table = 'statistik_tahun';

    protected $fillable = [
        'tahun',
        'total_kuota',
        'total_pendaftar',
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
    ];

    public function jalurStats()
    {
        return $this->hasMany(StatistikJalurTahun::class, 'statistik_tahun_id')->orderBy('urutan');
    }
}
