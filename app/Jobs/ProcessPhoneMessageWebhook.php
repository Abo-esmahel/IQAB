<?php

namespace App\Jobs;

use App\Models\NumberMessage;
use App\Models\PhoneNumber;
use App\Models\WebhookLog;
use App\Notifications\MessageReceivedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessPhoneMessageWebhook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(public WebhookLog $log) {}

    public function handle(): void
    {
        $payload = $this->log->payload;

        $phoneId = $payload['phone_number_id'] ?? null;
        $providerMessageId = $payload['message_id'] ?? $payload['id'] ?? null;

        if (!$phoneId || !$providerMessageId) {
            $this->log->update(['status' => 'ignored', 'error_message' => 'Missing required fields']);
            return;
        }

        $number = PhoneNumber::where('id', $phoneId)
            ->orWhere('provider_number_id', $phoneId)
            ->first();

        if (!$number) {
            $this->log->update(['status' => 'failed', 'error_message' => 'Phone number not found']);
            return;
        }

        $purchase = $number->numberPurchases()->where('status', 'active')->first();

        if (!$purchase) {
            $this->log->update(['status' => 'ignored', 'error_message' => 'No active purchase for this number']);
            return;
        }

        $message = NumberMessage::updateOrCreate(
            [
                'phone_number_id' => $number->id,
                'provider_message_id' => $providerMessageId,
            ],
            [
                'user_id' => $purchase->user_id,
                'sender' => $payload['sender'] ?? $payload['from'] ?? 'Unknown',
                'message' => $payload['message'] ?? $payload['text'] ?? '',
                'received_at' => $payload['received_at'] ?? now(),
                'metadata' => $payload,
            ]
        );

        if ($message->user_id) {
            $message->user->notify(new MessageReceivedNotification($message));
        }

        $this->log->update(['status' => 'processed', 'processed_at' => now()]);
    }
}
