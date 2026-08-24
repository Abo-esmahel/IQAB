<?php

namespace App\Actions;

use App\Models\NumberMessage;
use App\Models\PhoneNumber;
use App\Notifications\MessageReceivedNotification;
use App\Services\Phone\PhoneProviderService;
use Illuminate\Support\Facades\Log;

class SyncNumberMessagesAction
{
    public function __construct(
        protected PhoneProviderService $phoneProvider,
    ) {}

    public function execute(PhoneNumber $number): int
    {
        try {
            $messages = $this->phoneProvider->getMessages($number->provider_number_id ?? $number->id);

            $count = 0;

            foreach ($messages as $messageDto) {
                $message = NumberMessage::updateOrCreate(
                    [
                        'phone_number_id' => $number->id,
                        'provider_message_id' => $messageDto->providerMessageId,
                    ],
                    [
                        'user_id' => $number->numberPurchases()->latest()->first()?->user_id,
                        'sender' => $messageDto->sender,
                        'message' => $messageDto->message,
                        'received_at' => $messageDto->receivedAt,
                        'metadata' => $messageDto->metadata,
                    ]
                );

                if ($message->wasRecentlyCreated && $message->user_id) {
                    $message->user->notify(new MessageReceivedNotification($message));
                }
                $count++;
            }

            return $count;
        } catch (\Exception $e) {
            Log::error('Failed to sync messages', [
                'phone_number_id' => $number->id,
                'error' => $e->getMessage(),
            ]);
            return 0;
        }
    }
}
