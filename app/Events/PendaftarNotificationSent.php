<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Pendaftar;

class PendaftarNotificationSent
{
    use Dispatchable, SerializesModels;

    public $pendaftar;
    public $notificationType;
    public $status;
    public $catatan;

    /**
     * Create a new event instance.
     */
    public function __construct(Pendaftar $pendaftar, string $notificationType, string $status = null, string $catatan = null)
    {
        $this->pendaftar = $pendaftar;
        $this->notificationType = $notificationType;
        $this->status = $status;
        $this->catatan = $catatan;
    }
}
