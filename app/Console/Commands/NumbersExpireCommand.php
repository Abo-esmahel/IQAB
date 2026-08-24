<?php

namespace App\Console\Commands;

use App\Enums\NumberPurchaseStatus;
use App\Enums\PhoneNumberStatus;
use App\Models\NumberPurchase;
use App\Models\PhoneNumber;
use App\Services\Phone\PhoneProviderService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class NumbersExpireCommand extends Command
{
    protected $signature = 'numbers:expire';
    protected $description = 'Expire numbers that have passed their expiration date';

    public function handle(): int
    {
        $expiredCount = 0;

        NumberPurchase::where('status', NumberPurchaseStatus::Active->value)
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', Carbon::now())
            ->chunkById(100, function ($expiredPurchases) use (&$expiredCount) {
                foreach ($expiredPurchases as $purchase) {
                    $purchase->update(['status' => NumberPurchaseStatus::Expired->value]);

                    $number = PhoneNumber::where('id', $purchase->phone_number_id)->first();
                    if ($number) {
                        $number->update(['status' => PhoneNumberStatus::Expired->value]);

                        try {
                            app(PhoneProviderService::class)->releaseNumber($number->provider_number_id ?? $number->id);
                        } catch (\Throwable $e) {
                            Log::warning('Failed to release expired number at provider', [
                                'phone_number_id' => $number->id,
                                'error' => $e->getMessage(),
                            ]);
                        }
                    }

                    $expiredCount++;
                }
            });

        $this->info("Expired {$expiredCount} purchases.");

        return Command::SUCCESS;
    }
}
