<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DashboardStats extends Model
{
    protected $table = 'mv_dashboard_stats';

    public $timestamps = false;
    public $incrementing = false;

    protected $casts = [
        'total_pendaftar' => 'integer',
        'menunggu_verifikasi' => 'integer',
        'diterima' => 'integer',
        'diverifikasi' => 'integer',
        'tidak_lulus' => 'integer',
        'mengundurkan_diri' => 'integer',
        'last_refreshed' => 'datetime',
    ];

    /**
     * Refresh the materialized view
     */
    public static function refreshView($concurrent = false)
    {
        $concurrentClause = $concurrent ? 'CONCURRENTLY' : '';
        DB::statement("REFRESH MATERIALIZED VIEW $concurrentClause mv_dashboard_stats");
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
