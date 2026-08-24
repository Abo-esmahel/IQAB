<?php

namespace App\Services\Telegram\Contracts;

interface TelegramServiceInterface
{
    public function lookupAccount(string $identifier): array;

    public function reportAccount(string $identifier, string $reason): array;

    public function getAccountInformation(string $identifier): array;

    public function processWebhook(array $payload): array;
}
