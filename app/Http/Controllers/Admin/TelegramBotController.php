<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Telegram\TelegramBotService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TelegramBotController extends Controller
{
    protected TelegramBotService $bot;

    public function __construct(TelegramBotService $bot)
    {
        $this->bot = $bot;
    }

    public function index(): View
    {
        $configured = $this->bot->isConfigured();
        $me = $configured ? $this->bot->getMe() : ['ok' => false, 'error' => 'Bot token is not configured.'];
        $webhook = $configured ? $this->bot->getWebhookInfo() : ['ok' => false, 'error' => 'Bot token is not configured.'];

        return view('admin.telegram.index', compact('configured', 'me', 'webhook'));
    }

    public function setWebhook(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'url' => ['required', 'string', 'url', 'starts_with:https'],
        ]);

        $secret = config('telegram.webhook_secret');
        $result = $this->bot->setWebhook($data['url'], $secret ?: null);

        if (! ($result['ok'] ?? false)) {
            return redirect()
                ->route('admin.telegram.index')
                ->with('error', 'Failed to set webhook: ' . ($result['error'] ?? 'unknown error'));
        }

        return redirect()
            ->route('admin.telegram.index')
            ->with('success', 'Webhook set successfully.');
    }

    public function deleteWebhook(Request $request): RedirectResponse
    {
        $result = $this->bot->deleteWebhook((bool) $request->input('drop_pending', false));

        if (! ($result['ok'] ?? false)) {
            return redirect()
                ->route('admin.telegram.index')
                ->with('error', 'Failed to delete webhook: ' . ($result['error'] ?? 'unknown error'));
        }

        return redirect()
            ->route('admin.telegram.index')
            ->with('success', 'Webhook deleted successfully.');
    }

    public function testMessage(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'chat_id' => ['required', 'string'],
            'text' => ['required', 'string', 'max:4000'],
        ]);

        $result = $this->bot->sendMessage($data['chat_id'], $data['text']);

        if (! ($result['ok'] ?? false)) {
            return redirect()
                ->route('admin.telegram.index')
                ->with('error', 'Failed to send message: ' . ($result['error'] ?? 'unknown error'));
        }

        return redirect()
            ->route('admin.telegram.index')
            ->with('success', 'Test message sent successfully.');
    }
}
