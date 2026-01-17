<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RecentPendaftar extends Model
{
    protected $table = 'mv_recent_pendaftar';

    public $timestamps = false;

    protected $casts = [
        'tanggal_daftar' => 'datetime',
        'last_refreshed' => 'datetime',
    ];

    /**
     * Refresh the materialized view
     */
    public static function refreshView($concurrent = false)
    {
        $concurrentClause = $concurrent ? 'CONCURRENTLY' : '';
        DB::statement("REFRESH MATERIALIZED VIEW $concurrentClause mv_recent_pendaftar");
    }

    /**
     * Get recent pendaftar
     */
    public static function getRecent($limit = 5)
    {
        $recent = self::limit($limit)->get();

        if ($recent->isEmpty()) {
            self::refreshView();
            $recent = self::limit($limit)->get();
        }

        return $recent;
    }
}
