<?php

namespace App\Jobs;

use App\Models\WebhookLog;
use App\Services\Telegram\TelegramProviderService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessTelegramWebhook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(public WebhookLog $log) {}

    public function handle(TelegramProviderService $telegram): void
    {
        try {
            $result = $telegram->processWebhook($this->log->payload);

            $this->log->update([
                'status' => 'processed',
                'processed_at' => now(),
            ]);
        } catch (\Exception $e) {
            $this->log->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);
        }
    }
}
