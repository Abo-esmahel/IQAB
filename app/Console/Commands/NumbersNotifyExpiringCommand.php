<?php

namespace App\Console\Commands;

use App\Enums\NumberPurchaseStatus;
use App\Models\NumberPurchase;
use App\Notifications\NumberExpiringNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;

class NumbersNotifyExpiringCommand extends Command
{
    protected $signature = 'numbers:notify-expiring';
    protected $description = 'Notify users about expiring numbers';

    protected array $thresholds = [
        1 => '1 hour',
        6 => '6 hours',
        24 => '24 hours',
    ];

    public function handle(): int
    {
        $count = 0;

        $purchases = NumberPurchase::where('status', NumberPurchaseStatus::Active->value)
            ->whereNotNull('expires_at')
            ->where('expires_at', '>', Carbon::now())
            ->with('phoneNumber', 'user')
            ->get();

        foreach ($purchases as $purchase) {
            $remainingSeconds = Carbon::now()->diffInSeconds($purchase->expires_at);

            $matchedHours = null;
            foreach (array_keys($this->thresholds) as $hours) {
                if ($remainingSeconds <= $hours * 3600) {
                    $matchedHours = $hours;
                    break;
                }
            }

            if ($matchedHours === null) {
                continue;
            }

            $notified = $purchase->notified_thresholds
                ? json_decode($purchase->notified_thresholds, true)
                : [];

            $key = $matchedHours . 'h';
            if (in_array($key, $notified, true)) {
                continue;
            }

            $timeRemaining = Carbon::now()->diffForHumans($purchase->expires_at, true);

            $purchase->user->notify(
                new NumberExpiringNotification($purchase, $timeRemaining)
            );

            $notified[] = $key;
            $purchase->update(['notified_thresholds' => json_encode($notified)]);

            $count++;
        }

        $this->info("Sent {$count} expiration notifications.");

        return Command::SUCCESS;
    }
}
