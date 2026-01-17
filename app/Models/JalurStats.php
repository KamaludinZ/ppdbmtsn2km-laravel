<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class JalurStats extends Model
{
    protected $table = 'mv_jalur_stats';

    public $timestamps = false;

    protected $casts = [
        'pendaftar_count' => 'integer',
        'lulus_count' => 'integer',
        'menunggu_count' => 'integer',
        'last_refreshed' => 'datetime',
    ];

    /**
     * Refresh the materialized view
     */
    public static function refreshView($concurrent = false)
    {
        $concurrentClause = $concurrent ? 'CONCURRENTLY' : '';
        DB::statement("REFRESH MATERIALIZED VIEW $concurrentClause mv_jalur_stats");
    }

    /**
     * Get all jalur stats
     */
    public static function getAll()
    {
        $stats = self::all();

        if ($stats->isEmpty()) {
            self::refreshView();
            $stats = self::all();
        }

        return $stats;
    }
}
