<?php

namespace App\Jobs;

use App\Actions\SyncNumberMessagesAction;
use App\Models\PhoneNumber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncNumberMessages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;
    public int $backoff = 120;

    public function __construct(public int $phoneNumberId) {}

    public function handle(SyncNumberMessagesAction $syncAction): void
    {
        $number = PhoneNumber::find($this->phoneNumberId);

        if (!$number) {
            return;
        }

        $syncAction->execute($number);
    }
}
