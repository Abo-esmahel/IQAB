<?php

namespace App\Http\Controllers;

use App\Models\WebhookLog;
use App\Jobs\ProcessPhoneMessageWebhook;
use App\Jobs\ProcessTelegramWebhook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class WebhookController extends Controller
{
    public function phoneMessages(Request $request)
    {
        $this->verifySignature(
            $request,
            $request->header('X-Webhook-Signature', ''),
            Config::get('phoneprovider.webhook_secret', '')
        );

        $log = WebhookLog::create([
            'provider' => 'phone',
            'event' => $request->input('event', 'message'),
            'external_id' => $request->input('external_id'),
            'payload' => $request->only([
                'event', 'external_id', 'phone_number_id', 'message_id', 'id',
                'provider_message_id', 'sender', 'from', 'text', 'message', 'received_at',
            ]),
            'signature' => $request->header('X-Webhook-Signature', ''),
            'status' => 'received',
        ]);

        ProcessPhoneMessageWebhook::dispatch($log);

        return response()->json(['status' => 'ok']);
    }

    public function telegram(Request $request)
    {
        $this->verifyTelegramSignature(
            $request,
            $request->header('X-Telegram-Bot-Api-Secret-Token', ''),
            Config::get('telegram.webhook_secret', '')
        );

        $log = WebhookLog::create([
            'provider' => 'telegram',
            'event' => $request->input('update_type', 'unknown'),
            'external_id' => (string) $request->input('update_id'),
            'payload' => $request->only(['update_id', 'update_type', 'message', 'edited_message', 'callback_query']),
            'signature' => $request->header('X-Telegram-Bot-Api-Secret-Token', ''),
            'status' => 'received',
        ]);

        ProcessTelegramWebhook::dispatch($log);

        return response()->json(['status' => 'ok']);
    }

    private function verifyTelegramSignature(Request $request, string $signature, string $secret): void
    {
        if ($this->isPlaceholderSecret($secret) || $signature === '') {
            abort(Response::HTTP_UNAUTHORIZED, 'Webhook secret is not configured.');
        }

        if (! hash_equals($secret, $signature)) {
            Log::warning('Telegram webhook secret mismatch', ['ip' => $request->ip()]);
            abort(Response::HTTP_UNAUTHORIZED, 'Invalid webhook secret.');
        }
    }

    private function verifySignature(Request $request, string $signature, string $secret): void
    {
        if ($this->isPlaceholderSecret($secret) || $signature === '') {
            abort(Response::HTTP_UNAUTHORIZED, 'Webhook signature secret is not configured.');
        }

        $expected = 'sha256=' . hash_hmac('sha256', $request->getContent(), $secret, false);
        $provided = str_starts_with($signature, 'sha256=') ? $signature : 'sha256=' . $signature;

        if (!hash_equals($expected, $provided)) {
            Log::warning('Webhook signature mismatch', [
                'provider' => $request->path(),
                'ip' => $request->ip(),
            ]);
            abort(Response::HTTP_UNAUTHORIZED, 'Invalid webhook signature.');
        }
    }

    private function isPlaceholderSecret(string $secret): bool
    {
        $secret = strtolower(trim($secret));

        return $secret === ''
            || str_starts_with($secret, 'todo')
            || $secret === 'secret'
            || $secret === 'changeme'
            || $secret === 'example';
    }
}
