<?php

namespace App\Services\Phone\DTOs;

use Carbon\Carbon;

class MessageDTO
{
    public function __construct(
        public readonly string $providerMessageId,
        public readonly string $sender,
        public readonly string $message,
        public readonly Carbon $receivedAt,
        public readonly array $metadata = [],
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            providerMessageId: $data['provider_message_id'] ?? $data['id'] ?? '',
            sender: $data['sender'] ?? $data['from'] ?? '',
            message: $data['message'] ?? $data['text'] ?? '',
            receivedAt: Carbon::parse($data['received_at'] ?? $data['created_at'] ?? now()),
            metadata: $data['metadata'] ?? [],
        );
    }
}
