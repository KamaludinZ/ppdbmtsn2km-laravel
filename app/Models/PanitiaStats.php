<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PanitiaStats extends Model
{
    protected $table = 'mv_panitia_stats';

    public $timestamps = false;
    public $incrementing = false;

    protected $casts = [
        'total_panitia' => 'integer',
        'panitia_aktif' => 'integer',
        'admin_aktif' => 'integer',
        'panitia_pendaftaran_aktif' => 'integer',
        'panitia_daftarulang_aktif' => 'integer',
        'last_refreshed' => 'datetime',
    ];

    /**
     * Refresh the materialized view
     */
    public static function refreshView($concurrent = false)
    {
        $concurrentClause = $concurrent ? 'CONCURRENTLY' : '';
        DB::statement("REFRESH MATERIALIZED VIEW $concurrentClause mv_panitia_stats");
    }

    /**
     * Get stats as array
     */
    public static function getStats()
    {
        $stats = self::first();

        if (!$stats) {
            self::refreshView();
            $stats = self::first();
        }

        return $stats ? $stats->toArray() : [];
    }
}
