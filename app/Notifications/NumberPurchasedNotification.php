<?php

namespace App\Notifications;

use App\Models\NumberPurchase;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NumberPurchasedNotification extends Notification
{
    use Queueable;

    public function __construct(public NumberPurchase $purchase) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Number Purchased',
            'message' => "You've successfully purchased number {$this->purchase->phoneNumber->phone_number}.",
            'number_id' => $this->purchase->phone_number_id,
            'purchase_id' => $this->purchase->id,
        ];
    }
}
