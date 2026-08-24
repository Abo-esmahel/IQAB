<?php

namespace App\Console\Commands;

use App\Enums\PhoneNumberStatus;
use App\Jobs\SyncNumberMessages;
use App\Jobs\SyncProviderNumberStatus;
use App\Models\PhoneNumber;
use Illuminate\Console\Command;

class NumbersSyncCommand extends Command
{
    protected $signature = 'numbers:sync';
    protected $description = 'Sync number status and messages from provider';

    public function handle(): int
    {
        $numbers = PhoneNumber::whereNot('status', PhoneNumberStatus::Available)
            ->whereNot('status', PhoneNumberStatus::Disabled)
            ->get();

        foreach ($numbers as $number) {
            SyncProviderNumberStatus::dispatch($number->id);
            SyncNumberMessages::dispatch($number->id);
        }

        $this->info("Dispatched sync jobs for {$numbers->count()} numbers.");

        return Command::SUCCESS;
    }
}
