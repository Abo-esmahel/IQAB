<?php

namespace App\Services\Telegram;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramBotService
{
    protected int $timeout;

    public function __construct(protected ?string $botToken = null)
    {
        $this->botToken = $botToken ?? config('telegram.bot_token', '');
        $this->timeout = (int) config('telegram.timeout', 30);
    }

    public function isConfigured(): bool
    {
        return ! empty($this->botToken);
    }

    protected function call(string $method, array $params = []): array
    {
        if (! $this->isConfigured()) {
            return ['ok' => false, 'error' => 'Bot token is not configured.'];
        }

        $url = 'https://api.telegram.org/bot' . $this->botToken . '/' . $method;

        try {
            $response = Http::timeout($this->timeout)
                ->acceptJson()
                ->post($url, $params);

            if ($response->failed()) {
                Log::error('Telegram bot API call failed', [
                    'method' => $method,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return [
                    'ok' => false,
                    'status' => $response->status(),
                    'error' => $response->json('description', 'Telegram bot API request failed.'),
                ];
            }

            return [
                'ok' => true,
                'status' => $response->status(),
                'data' => $response->json('result', []),
                'raw' => $response->json(),
            ];
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Telegram bot API connection failed', [
                'method' => $method,
                'error' => $e->getMessage(),
            ]);

            return ['ok' => false, 'error' => 'Connection failed: ' . $e->getMessage()];
        } catch (\Throwable $e) {
            Log::error('Telegram bot API exception', [
                'method' => $method,
                'error' => $e->getMessage(),
            ]);

            return ['ok' => false, 'error' => $e->getMessage()];
        }
    }

    public function getMe(): array
    {
        return $this->call('getMe');
    }

    public function getWebhookInfo(): array
    {
        return $this->call('getWebhookInfo');
    }

    public function setWebhook(string $url, ?string $secret = null): array
    {
        $params = ['url' => $url];

        if ($secret) {
            $params['secret_token'] = $secret;
        }

        return $this->call('setWebhook', $params);
    }

    public function deleteWebhook(bool $dropPendingUpdates = false): array
    {
        return $this->call('deleteWebhook', ['drop_pending_updates' => $dropPendingUpdates]);
    }

    public function sendMessage(string $chatId, string $text): array
    {
        return $this->call('sendMessage', [
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => 'HTML',
        ]);
    }
}
