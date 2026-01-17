<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomMessageNotification extends Notification
{
    use Queueable;

    public $title;
    public $message;
    public $type;
    public $data;

    /**
     * Create a new notification instance.
     *
     * @param string $title Title of the message
     * @param string $message Message content
     * @param string $type Type of notification (tes_psikologi, etc.)
     * @param array $data Additional data
     */
    public function __construct(string $title, string $message, string $type = 'custom', array $data = [])
    {
        $this->title = $title;
        $this->message = $message;
        $this->type = $type;
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => $this->type,
            'title' => $this->title,
            'message' => $this->message,
            'data' => $this->data,
            'created_at' => now()->toDateTimeString(),
        ];
    }
}
