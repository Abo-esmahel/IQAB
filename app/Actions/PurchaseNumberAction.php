<?php

namespace App\Actions;

use App\Enums\NumberPurchaseStatus;
use App\Enums\PhoneNumberStatus;
use App\Enums\TransactionType;
use App\Exceptions\InsufficientBalanceException;
use App\Models\PhoneNumber;
use App\Models\NumberPurchase;
use App\Models\User;
use App\Services\Phone\PhoneProviderService;
use App\Services\Wallet\WalletService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PurchaseNumberAction
{
    public function __construct(
        protected WalletService $walletService,
        protected PhoneProviderService $phoneProvider,
    ) {}

    public function execute(User $user, PhoneNumber $number): NumberPurchase
    {
        $price = (float) $number->price;

        return DB::transaction(function () use ($user, $number, $price) {
            $lockedNumber = PhoneNumber::where('id', $number->id)->lockForUpdate()->first();

            if (!$lockedNumber) {
                throw new \Exception('This number could not be found.');
            }

            if ($lockedNumber->status !== PhoneNumberStatus::Available) {
                throw new \Exception('This number is no longer available.');
            }

            $wallet = $this->walletService->getOrCreateWallet($user);

            $balanceBefore = (float) $wallet->balance;

            if ($balanceBefore < $price) {
                throw InsufficientBalanceException::make($price, $balanceBefore);
            }

            $balanceAfter = $balanceBefore - $price;

            $wallet->update([
                'balance' => $balanceAfter,
                'total_spent' => (float) $wallet->total_spent + $price,
            ]);

            $lockedNumber->update(['status' => PhoneNumberStatus::Reserved->value]);

            $purchase = NumberPurchase::create([
                'user_id' => $user->id,
                'phone_number_id' => $lockedNumber->id,
                'price' => $price,
                'status' => NumberPurchaseStatus::Pending->value,
                'purchased_at' => now(),
                'expires_at' => $lockedNumber->expires_at,
            ]);

            \App\Models\WalletTransaction::create([
                'wallet_id' => $wallet->id,
                'type' => TransactionType::Purchase->value,
                'amount' => $price,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'reference' => "number_purchase_{$purchase->id}",
                'description' => "Purchase of number {$lockedNumber->phone_number}",
                'status' => 'completed',
            ]);

            try {
                $providerResponse = $this->phoneProvider->purchaseNumber(
                    $lockedNumber->provider_number_id ?? (string) $lockedNumber->id
                );

                $purchase->update([
                    'status' => NumberPurchaseStatus::Active->value,
                    'provider_reference' => $providerResponse['reference'] ?? null,
                    'metadata' => $providerResponse,
                ]);

                $lockedNumber->update(['status' => PhoneNumberStatus::Active->value]);
            } catch (\Exception $e) {
                Log::error('Number purchase: provider call failed, refunding user', [
                    'number_id' => $lockedNumber->id,
                    'error' => $e->getMessage(),
                ]);

                // Provider could not provision the number: refund the user and do not activate.
                $this->walletService->refund(
                    $user,
                    $price,
                    'Refund: provider could not provision number ' . $lockedNumber->phone_number,
                    'refund_number_purchase_' . $purchase->id
                );

                $purchase->update([
                    'status' => NumberPurchaseStatus::Failed->value,
                    'metadata' => ['provider_error' => $e->getMessage()],
                ]);

                $lockedNumber->update(['status' => PhoneNumberStatus::Available->value]);

                throw new \Exception('We could not provision this number. Your payment has been refunded.');
            }

            return $purchase;
        });
    }
}
