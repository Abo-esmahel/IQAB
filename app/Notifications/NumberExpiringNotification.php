<?php

namespace App\Notifications;

use App\Models\NumberPurchase;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NumberExpiringNotification extends Notification
{
    use Queueable;

    public function __construct(
        public NumberPurchase $purchase,
        public string $timeRemaining,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Number Expiring Soon',
            'message' => "Your number {$this->purchase->phoneNumber->phone_number} will expire in {$this->timeRemaining}.",
            'purchase_id' => $this->purchase->id,
            'expires_at' => $this->purchase->expires_at,
        ];
    }
}
