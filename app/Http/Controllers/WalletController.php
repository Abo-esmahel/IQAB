<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepositRequest;
use App\Models\Payment;
use App\Enums\PaymentStatus;
use App\Models\Setting;
use App\Services\Payment\PaymentGatewayService;
use App\Services\Wallet\WalletService;
use App\Enums\TransactionType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WalletController extends Controller
{
    public function __construct(
        protected WalletService $walletService,
        protected PaymentGatewayService $gateway,
    ) {}

    public function index(Request $request)
    {
        $wallet = $this->walletService->getOrCreateWallet($request->user());
        $transactions = $this->walletService->getTransactions($request->user());

        return view('wallet.index', compact('wallet', 'transactions'));
    }

    public function deposit(DepositRequest $request)
    {
        $amount = round((float) $request->amount, 2);
        $user = $request->user();

        if (! $this->gatewayConfigured()) {
            return back()->with('error', 'Payment gateway is not configured. Deposits are temporarily unavailable.');
        }

        $reference = 'dep_' . Str::uuid()->toString();

        $payment = Payment::create([
            'user_id' => $user->id,
            'amount' => $amount,
            'status' => PaymentStatus::Pending,
            'provider' => 'gateway',
            'provider_reference' => $reference,
        ]);

        try {
            $intent = $this->gateway->createPayment($amount, $this->currency(), [
                'payment_id' => (string) $payment->id,
                'user_id' => (string) $user->id,
            ]);
        } catch (\Throwable $e) {
            $payment->update(['status' => PaymentStatus::Failed]);

            return back()->with('error', 'Unable to initiate payment. Please try again later.');
        }

        if (! empty($intent['reference'])) {
            $payment->update(['provider_reference' => $intent['reference']]);
        }

        if (! empty($intent['payment_url'])) {
            return redirect()->away($intent['payment_url']);
        }

        // Synchronous gateway: verify and complete in the same request.
        return $this->verifyAndComplete($payment);
    }

    public function depositReturn(Request $request)
    {
        $reference = $request->query('reference') ?? $request->query('provider_reference');

        $payment = Payment::where('provider_reference', $reference)
            ->where('user_id', $request->user()->id)
            ->where('status', PaymentStatus::Pending)
            ->first();

        if (! $payment) {
            return redirect()->route('wallet.index')
                ->with('error', 'We could not find a matching pending payment.');
        }

        return $this->verifyAndComplete($payment);
    }

    public function depositCallback(Request $request)
    {
        $secret = Config::get('payments.gateway_secret', '');

        if ($secret === '' || ! hash_equals($secret, (string) $request->header('X-Gateway-Secret', ''))) {
            abort(401, 'Invalid gateway secret.');
        }

        $reference = $request->input('reference')
            ?? $request->input('provider_reference')
            ?? $request->input('id');

        $payment = Payment::where('provider_reference', $reference)
            ->where('status', PaymentStatus::Pending)
            ->first();

        if (! $payment) {
            return response()->json(['status' => 'ignored']);
        }

        return $this->verifyAndComplete($payment, true);
    }

    private function verifyAndComplete(Payment $payment, bool $isCallback = false): RedirectResponse|JsonResponse
    {
        try {
            $verification = $this->gateway->verifyPayment($payment->provider_reference);
        } catch (\Throwable $e) {
            if ($isCallback) {
                return response()->json(['status' => 'error'], 502);
            }

            return back()->with('error', 'Payment verification failed. Please try again later.');
        }

        if ($this->isPaid($verification)) {
            $this->creditPayment($payment);

            if ($isCallback) {
                return response()->json(['status' => 'ok']);
            }

            return redirect()->route('wallet.index')
                ->with('success', number_format((float) $payment->amount, 2) . ' added to your wallet!');
        }

        $payment->update(['status' => PaymentStatus::Failed]);

        if ($isCallback) {
            return response()->json(['status' => 'failed']);
        }

        return back()->with('error', 'Payment was not completed.');
    }

    private function creditPayment(Payment $payment): void
    {
        if ($payment->status === PaymentStatus::Completed) {
            return;
        }

        $user = $payment->user;

        DB::transaction(function () use ($payment, $user): void {
            $this->walletService->credit(
                $user,
                (float) $payment->amount,
                TransactionType::Deposit,
                'Wallet deposit via payment gateway',
                'deposit_' . $payment->id
            );

            $payment->update(['status' => PaymentStatus::Completed]);
        });
    }

    private function isPaid(array $data): bool
    {
        $status = strtolower((string) ($data['status'] ?? $data['state'] ?? ''));

        if (in_array($status, ['paid', 'completed', 'successful', 'succeeded'], true)) {
            return true;
        }

        if (! empty($data['paid']) || ! empty($data['success'])) {
            return true;
        }

        return false;
    }

    private function gatewayConfigured(): bool
    {
        return ! empty(Config::get('payments.gateway_url')) && ! empty(Config::get('payments.gateway_secret'));
    }

    private function currency(): string
    {
        return (string) Setting::get('system.currency', 'SAR');
    }
}
