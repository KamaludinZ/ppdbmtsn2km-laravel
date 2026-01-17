<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessageHistory extends Model
{
    protected $table = 'message_history';

    protected $fillable = [
        'pendaftar_id',
        'sender_id',
        'message_type',
        'context',
        'message_content',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function pendaftar()
    {
        return $this->belongsTo(Pendaftar::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Get latest message history for a pendaftar in specific context
     */
    public static function getLatestForPendaftar($pendaftarId, $context)
    {
        return static::where('pendaftar_id', $pendaftarId)
            ->where('context', $context)
            ->orderBy('sent_at', 'desc')
            ->first();
    }
}
