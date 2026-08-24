<?php

namespace App\Notifications;

use App\Models\NumberMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class MessageReceivedNotification extends Notification
{
    use Queueable;

    public function __construct(public NumberMessage $message) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Message Received',
            'message' => "New message on {$this->message->phoneNumber->phone_number} from {$this->message->sender}.",
            'phone_number_id' => $this->message->phone_number_id,
            'message_id' => $this->message->id,
        ];
    }
}
