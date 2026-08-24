<?php

namespace App\Jobs;

use App\Enums\PhoneNumberStatus;
use App\Models\PhoneNumber;
use App\Services\Phone\PhoneProviderService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncProviderNumberStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;
    public int $backoff = 300;

    public function __construct(public int $phoneNumberId) {}

    public function handle(PhoneProviderService $phoneProvider): void
    {
        $number = PhoneNumber::find($this->phoneNumberId);

        if (!$number || $number->status === PhoneNumberStatus::Available) {
            return;
        }

        try {
            $status = $phoneProvider->getNumberStatus($number->provider_number_id ?? $number->id);

            $providerStatus = $status['status'] ?? null;

            if ($providerStatus === 'expired' && $number->status !== PhoneNumberStatus::Expired) {
                $number->update(['status' => PhoneNumberStatus::Expired->value]);

                $number->numberPurchases()
                    ->where('status', 'active')
                    ->update(['status' => 'expired']);
            }
        } catch (\Exception $e) {
            \Log::error('Failed to sync number status', [
                'phone_number_id' => $number->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
