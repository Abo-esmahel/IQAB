<?php

namespace App\Services\Phone\Contracts;

interface PhoneProviderInterface
{
    public function getAvailableNumbers(array $filters = []): array;

    public function purchaseNumber(string $numberId): array;

    public function releaseNumber(string $numberId): bool;

    public function getNumberStatus(string $numberId): array;

    public function getMessages(string $numberId, array $filters = []): array;
}
