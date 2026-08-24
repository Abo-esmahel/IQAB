<?php

namespace App\Services\Telegram;

use App\Services\Telegram\Contracts\TelegramServiceInterface;
use App\Exceptions\ProviderException;
use App\Exceptions\ProviderTimeoutException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramProviderService implements TelegramServiceInterface
{
    protected string $apiUrl;
    protected string $botToken;
    protected int $timeout;

    public function __construct()
    {
        $this->apiUrl = config('telegram.api_url', '');
        $this->botToken = config('telegram.api_token', '');
        $this->timeout = config('telegram.timeout', 30);
    }

    public function lookupAccount(string $identifier): array
    {
        try {
            $response = $this->client()->post('/lookup', [
                'identifier' => $identifier,
            ]);

            if ($response->failed()) {
                Log::error('Telegram service: lookup failed', [
                    'identifier' => $identifier,
                    'status' => $response->status(),
                ]);
                throw ProviderException::failed('Telegram lookup failed');
            }

            return $response->json('data', []);
        } catch (ConnectionException $e) {
            throw ProviderTimeoutException::connection('Telegram service connection failed');
        }
    }

    public function reportAccount(string $identifier, string $reason): array
    {
        try {
            $response = $this->client()->post('/report', [
                'identifier' => $identifier,
                'reason' => $reason,
            ]);

            if ($response->failed()) {
                Log::error('Telegram service: report failed', [
                    'identifier' => $identifier,
                    'status' => $response->status(),
                ]);
                throw ProviderException::failed('Telegram report failed');
            }

            return $response->json('data', []);
        } catch (ConnectionException $e) {
            throw ProviderTimeoutException::connection('Telegram service connection failed');
        }
    }

    public function getAccountInformation(string $identifier): array
    {
        try {
            $response = $this->client()->get('/info/' . urlencode($identifier));

            if ($response->failed()) {
                Log::error('Telegram service: info failed', [
                    'identifier' => $identifier,
                    'status' => $response->status(),
                ]);
                throw ProviderException::failed('Telegram info failed');
            }

            return $response->json('data', []);
        } catch (ConnectionException $e) {
            throw ProviderTimeoutException::connection('Telegram service connection failed');
        }
    }

    public function processWebhook(array $payload): array
    {
        return ['status' => 'processed'];
    }

    protected function client()
    {
        $client = Http::baseUrl($this->apiUrl)
            ->withToken($this->botToken)
            ->timeout($this->timeout)
            ->withHeaders([
                'Accept' => 'application/json',
            ]);

        if (config('telegram.disable_ssl_verification', false)) {
            $client->withOptions(['verify' => false]);
        }

        return $client;
    }
}
