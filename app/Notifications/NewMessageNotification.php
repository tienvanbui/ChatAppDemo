<?php

namespace App\Notifications;

use App\Http\Resources\ConversationResource;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class NewMessageNotification extends Notification
{
    use Queueable;

    protected $conversation;
    protected $message;

    /**
     * Create a new notification instance.
     */
    public function __construct($conversation, $message)
    {
        $this->conversation = $conversation;
        $this->message = $message;
    }

    /**
     * Get the notification's delivery conversations.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['broadcast'];
    }

    /**
     * Get the broadcastable representation of the notification.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        $this->conversation->message = $this->message;

        return new BroadcastMessage([
            'conversation' => ConversationResource::make($this->conversation),
        ]);
    }
}
