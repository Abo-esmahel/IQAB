<?php

namespace App\Notifications;

use App\Models\NumberPurchase;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
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
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your IQAB number expires soon')
            ->line("Your number {$this->purchase->phoneNumber->phone_number} will expire in {$this->timeRemaining}.")
            ->line('Renew it with our support on Telegram to keep receiving messages.')
            ->action('View My Numbers', route('my-numbers.index'));
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
