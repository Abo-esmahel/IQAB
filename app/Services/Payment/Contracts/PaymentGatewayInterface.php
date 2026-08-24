<?php

namespace App\Services\Payment\Contracts;

interface PaymentGatewayInterface
{
    public function createPayment(float $amount, string $currency, array $metadata = []): array;

    public function verifyPayment(string $reference): array;

    public function refund(string $reference, ?float $amount = null): array;
}
