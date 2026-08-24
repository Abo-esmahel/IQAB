<?php

namespace App\Services\Payment;

use App\Services\Payment\Contracts\PaymentGatewayInterface;
use App\Exceptions\ProviderException;
use App\Exceptions\ProviderTimeoutException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentGatewayService implements PaymentGatewayInterface
{
    protected string $gatewayUrl;
    protected string $gatewayKey;
    protected string $gatewaySecret;
    protected int $timeout;

    public function __construct()
    {
        $this->gatewayUrl = config('payments.gateway_url', '');
        $this->gatewayKey = config('payments.gateway_key', '');
        $this->gatewaySecret = config('payments.gateway_secret', '');
        $this->timeout = config('payments.timeout', 60);
    }

    public function createPayment(float $amount, string $currency, array $metadata = []): array
    {
        try {
            $response = $this->client()->post('/payments', [
                'amount' => $amount,
                'currency' => $currency,
                'metadata' => $metadata,
            ]);

            if ($response->failed()) {
                Log::error('Payment gateway: create payment failed', [
                    'amount' => $amount,
                    'status' => $response->status(),
                ]);
                throw ProviderException::failed('Payment creation failed');
            }

            return $response->json('data', []);
        } catch (ConnectionException $e) {
            throw ProviderTimeoutException::connection('Payment gateway connection failed');
        }
    }

    public function verifyPayment(string $reference): array
    {
        try {
            $response = $this->client()->get('/payments/' . urlencode($reference) . '/verify');

            if ($response->failed()) {
                throw ProviderException::failed('Payment verification failed');
            }

            return $response->json('data', []);
        } catch (ConnectionException $e) {
            throw ProviderTimeoutException::connection('Payment gateway connection failed');
        }
    }

    public function refund(string $reference, ?float $amount = null): array
    {
        try {
            $response = $this->client()->post('/payments/' . urlencode($reference) . '/refund', [
                'amount' => $amount,
            ]);

            if ($response->failed()) {
                throw ProviderException::failed('Refund failed');
            }

            return $response->json('data', []);
        } catch (ConnectionException $e) {
            throw ProviderTimeoutException::connection('Payment gateway connection failed');
        }
    }

    protected function client()
    {
        return Http::baseUrl($this->gatewayUrl)
            ->withToken($this->gatewaySecret)
            ->timeout($this->timeout)
            ->withHeaders([
                'Accept' => 'application/json',
                'X-Gateway-Key' => $this->gatewayKey,
            ]);
    }
}
