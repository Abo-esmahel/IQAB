<?php

namespace App\Http\Controllers;

use App\Http\Requests\TelegramServiceRequestForm;
use App\Models\TelegramService;
use App\Models\TelegramServiceRequest;
use App\Models\WalletTransaction;
use App\Services\Telegram\TelegramProviderService;
use App\Services\Wallet\WalletService;
use App\Enums\TelegramServiceStatus;
use App\Enums\TelegramServiceType;
use App\Enums\TransactionType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TelegramController extends Controller
{
    public function index()
    {
        $services = TelegramService::where('is_active', true)->get();

        return view('telegram.index', compact('services'));
    }

    public function submit(TelegramServiceRequestForm $request, TelegramProviderService $telegram)
    {
        $service = TelegramService::where('id', $request->telegram_service_id)
            ->where('is_active', true)
            ->firstOrFail();
        $user = $request->user();
        $price = (float) $service->price;

        try {
            $serviceRequest = DB::transaction(function () use ($user, $service, $request, $price) {
                $wallet = app(WalletService::class)->getOrCreateWallet($user);

                $balanceBefore = (float) $wallet->balance;

                if ($balanceBefore < $price) {
                    throw new \Exception('Insufficient balance.');
                }

                $balanceAfter = $balanceBefore - $price;

                $wallet->update([
                    'balance' => $balanceAfter,
                    'total_spent' => (float) $wallet->total_spent + $price,
                ]);

                $serviceRequest = TelegramServiceRequest::create([
                    'user_id' => $user->id,
                    'telegram_service_id' => $service->id,
                    'target_identifier' => $request->target_identifier,
                    'price' => $price,
                    'status' => TelegramServiceStatus::Processing->value,
                    'balance_before' => $balanceBefore,
                    'balance_after' => $balanceAfter,
                ]);

                WalletTransaction::create([
                    'wallet_id' => $wallet->id,
                    'type' => TransactionType::ServiceCharge->value,
                    'amount' => $price,
                    'balance_before' => $balanceBefore,
                    'balance_after' => $balanceAfter,
                    'reference' => "telegram_service_{$serviceRequest->id}",
                    'description' => "Telegram service: {$service->name}",
                    'status' => 'completed',
                ]);

                return $serviceRequest;
            });
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        try {
            $apiResult = match($service->type) {
                TelegramServiceType::AccountLookup => $telegram->lookupAccount($request->target_identifier),
                TelegramServiceType::AccountReport => $telegram->reportAccount($request->target_identifier, 'Reported via IQAB platform'),
                TelegramServiceType::AccountInformation => $telegram->getAccountInformation($request->target_identifier),
                default => [],
            };

            if (empty($apiResult)) {
                $serviceRequest->update([
                    'status' => TelegramServiceStatus::Failed->value,
                    'error_message' => 'Provider returned an empty result.',
                ]);

                if ($price > 0) {
                    $wallet = app(WalletService::class)->getOrCreateWallet($user);
                    $wallet->refresh();

                    $balanceBefore = (float) $wallet->balance;
                    $balanceAfter = $balanceBefore + $price;

                    $wallet->update(['balance' => $balanceAfter]);

                    WalletTransaction::create([
                        'wallet_id' => $wallet->id,
                        'type' => TransactionType::Refund->value,
                        'amount' => $price,
                        'balance_before' => $balanceBefore,
                        'balance_after' => $balanceAfter,
                        'reference' => "refund_telegram_service_{$serviceRequest->id}",
                        'description' => "Refund for failed telegram service: {$service->name}",
                        'status' => 'completed',
                    ]);
                }
            } else {
                $serviceRequest->update([
                    'status' => TelegramServiceStatus::Completed->value,
                    'result' => $apiResult,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Telegram service failed', ['error' => $e->getMessage()]);

            if ($price > 0) {
                try {
                    $wallet = app(WalletService::class)->getOrCreateWallet($user);
                    $wallet->refresh();

                    $balanceBefore = (float) $wallet->balance;
                    $balanceAfter = $balanceBefore + $price;

                    $wallet->update([
                        'balance' => $balanceAfter,
                    ]);

                    WalletTransaction::create([
                        'wallet_id' => $wallet->id,
                        'type' => TransactionType::Refund->value,
                        'amount' => $price,
                        'balance_before' => $balanceBefore,
                        'balance_after' => $balanceAfter,
                        'reference' => "refund_telegram_service_{$serviceRequest->id}",
                        'description' => "Refund for failed telegram service: {$service->name}",
                        'status' => 'completed',
                    ]);
                } catch (\Throwable $refundError) {
                    Log::error('Telegram service: refund failed', ['error' => $refundError->getMessage()]);
                }
            }

            $serviceRequest->update([
                'status' => TelegramServiceStatus::Failed->value,
                'error_message' => $e->getMessage(),
            ]);
        }

        return redirect()->route('telegram.result', $serviceRequest)
            ->with('success', 'Service request submitted!');
    }

    public function result(TelegramServiceRequest $request)
    {
        $this->authorize('view', $request);

        return view('telegram.result', ['request' => $request]);
    }

    public function history(Request $request)
    {
        $requests = TelegramServiceRequest::where('user_id', $request->user()->id)
            ->with('telegramService')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('telegram.history', compact('requests'));
    }
}
