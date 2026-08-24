<?php

namespace App\Services\Phone\DTOs;

class PhoneNumberDTO
{
    public function __construct(
        public readonly string $providerNumberId,
        public readonly string $phoneNumber,
        public readonly string $country,
        public readonly string $countryCode,
        public readonly float $price,
        public readonly ?string $provider = null,
        public readonly ?string $expiresAt = null,
        public readonly array $metadata = [],
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            providerNumberId: $data['provider_number_id'] ?? $data['id'] ?? '',
            phoneNumber: $data['phone_number'] ?? $data['number'] ?? '',
            country: $data['country'] ?? '',
            countryCode: $data['country_code'] ?? '',
            price: (float) ($data['price'] ?? 0),
            provider: $data['provider'] ?? null,
            expiresAt: $data['expires_at'] ?? null,
            metadata: $data['metadata'] ?? [],
        );
    }
}
