<?php

namespace App\Services\Phone;

use App\Services\Phone\Contracts\PhoneProviderInterface;
use App\Services\Phone\DTOs\MessageDTO;
use App\Services\Phone\DTOs\PhoneNumberDTO;
use App\Exceptions\ProviderException;
use App\Exceptions\ProviderTimeoutException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PhoneProviderService implements PhoneProviderInterface
{
    protected string $baseUrl;
    protected string $token;
    protected int $timeout;

    public function __construct()
    {
        $this->baseUrl = config('phoneprovider.base_url', '');
        $this->token = config('phoneprovider.token', '');
        $this->timeout = config('phoneprovider.timeout', 30);
    }

    public function getAvailableNumbers(array $filters = []): array
    {
        try {
            $response = $this->client()->get('/numbers', $filters);

            if ($response->failed()) {
                Log::error('Phone provider: failed to get available numbers', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                throw ProviderException::failed('Failed to fetch available numbers');
            }

            $data = $response->json('data', []);

            return array_map(fn(array $item) => PhoneNumberDTO::fromArray($item), $data);
        } catch (ConnectionException $e) {
            Log::error('Phone provider: connection failed', ['error' => $e->getMessage()]);
            throw ProviderTimeoutException::connection('Phone provider connection failed');
        }
    }

    public function purchaseNumber(string $numberId): array
    {
        try {
            $response = $this->client()->post('/numbers/' . urlencode($numberId) . '/purchase');

            if ($response->failed()) {
                Log::error('Phone provider: failed to purchase number', [
                    'number_id' => $numberId,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                throw ProviderException::failed('Failed to purchase number');
            }

            return $response->json('data', []);
        } catch (ConnectionException $e) {
            Log::error('Phone provider: purchase connection failed', ['error' => $e->getMessage()]);
            throw ProviderTimeoutException::connection('Phone provider connection failed during purchase');
        }
    }

    public function releaseNumber(string $numberId): bool
    {
        try {
            $response = $this->client()->delete("/numbers/{$numberId}");
            return $response->successful();
        } catch (ConnectionException $e) {
            Log::error('Phone provider: release connection failed', ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function getNumberStatus(string $numberId): array
    {
        try {
            $response = $this->client()->get('/numbers/' . urlencode($numberId) . '/status');

            if ($response->failed()) {
                throw ProviderException::failed('Failed to get number status');
            }

            return $response->json('data', []);
        } catch (ConnectionException $e) {
            throw ProviderTimeoutException::connection('Phone provider connection failed');
        }
    }

    public function getMessages(string $numberId, array $filters = []): array
    {
        try {
            $response = $this->client()->get('/numbers/' . urlencode($numberId) . '/messages', $filters);

            if ($response->failed()) {
                throw ProviderException::failed('Failed to get messages');
            }

            $data = $response->json('data', []);

            return array_map(fn(array $item) => MessageDTO::fromArray($item), $data);
        } catch (ConnectionException $e) {
            throw ProviderTimeoutException::connection('Phone provider connection failed');
        }
    }

    protected function client()
    {
        return Http::baseUrl($this->baseUrl)
            ->withToken($this->token)
            ->timeout($this->timeout)
            ->withHeaders([
                'Accept' => 'application/json',
            ]);
    }
}
