<?php

namespace App\Services\Wallet;

use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Exceptions\InsufficientBalanceException;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class WalletService
{
    public function getOrCreateWallet(User $user): Wallet
    {
        return Wallet::firstOrCreate(
            ['user_id' => $user->id],
            ['balance' => 0, 'total_deposited' => 0, 'total_spent' => 0]
        );
    }

    private function lockWallet(User $user): Wallet
    {
        $wallet = Wallet::where('user_id', $user->id)->lockForUpdate()->first();

        if (!$wallet) {
            try {
                $wallet = Wallet::create([
                    'user_id' => $user->id,
                    'balance' => 0,
                    'total_deposited' => 0,
                    'total_spent' => 0,
                ]);
            } catch (QueryException $e) {
                $wallet = Wallet::where('user_id', $user->id)->lockForUpdate()->first();
            }
        }

        return $wallet;
    }

    public function getBalance(User $user): float
    {
        $wallet = $this->getOrCreateWallet($user);
        return (float) $wallet->balance;
    }

    public function credit(User $user, float $amount, TransactionType $type, ?string $description = null, ?string $reference = null): WalletTransaction
    {
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Credit amount must be positive');
        }

        return $this->record($user, $amount, $type, $description, $reference, 'credit');
    }

    public function debit(User $user, float $amount, TransactionType $type, ?string $description = null, ?string $reference = null): WalletTransaction
    {
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Debit amount must be positive');
        }

        return $this->record($user, $amount, $type, $description, $reference, 'debit');
    }

    public function refund(User $user, float $amount, ?string $description = null, ?string $reference = null): WalletTransaction
    {
        return $this->credit($user, $amount, TransactionType::Refund, $description, $reference);
    }

    private function record(User $user, float $amount, TransactionType $type, ?string $description, ?string $reference, string $direction): WalletTransaction
    {
        if ($reference !== null) {
            $existing = WalletTransaction::where('reference', $reference)->first();
            if ($existing) {
                return $existing;
            }
        }

        return DB::transaction(function () use ($user, $amount, $type, $description, $reference, $direction) {
            $wallet = $this->lockWallet($user);

            $balanceBefore = round((float) $wallet->balance, 2);
            $balanceAfter = round($direction === 'credit' ? $balanceBefore + $amount : $balanceBefore - $amount, 2);

            if ($direction === 'debit' && $balanceBefore < $amount) {
                throw InsufficientBalanceException::make($amount, $balanceBefore);
            }

            $wallet->update([
                'balance' => $balanceAfter,
                'total_deposited' => round((float) $wallet->total_deposited + ($type === TransactionType::Deposit ? $amount : 0), 2),
                'total_spent' => round((float) $wallet->total_spent + ($direction === 'debit' ? $amount : 0), 2),
            ]);

            try {
                return WalletTransaction::create([
                    'wallet_id' => $wallet->id,
                    'type' => $type->value,
                    'amount' => $amount,
                    'balance_before' => $balanceBefore,
                    'balance_after' => $balanceAfter,
                    'reference' => $reference,
                    'description' => $description,
                    'status' => TransactionStatus::Completed->value,
                ]);
            } catch (QueryException $e) {
                if (str_contains($e->getMessage(), 'UNIQUE') || str_contains($e->getMessage(), 'unique')) {
                    $existing = WalletTransaction::where('reference', $reference)->first();
                    if ($existing) {
                        return $existing;
                    }
                }
                throw $e;
            }
        });
    }

    public function canAfford(User $user, float $amount): bool
    {
        return $this->getBalance($user) >= $amount;
    }

    public function getTransactions(User $user, int $perPage = 15)
    {
        $wallet = $this->getOrCreateWallet($user);

        return WalletTransaction::where('wallet_id', $wallet->id)
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }
}
