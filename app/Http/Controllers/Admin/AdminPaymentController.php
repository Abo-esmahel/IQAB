<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Enums\AuditAction;
use App\Enums\PaymentStatus;
use App\Enums\TransactionType;
use App\Models\AuditLog;
use App\Models\Payment;
use App\Services\Wallet\WalletService;
use Illuminate\Http\Request;

class AdminPaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with('user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('provider')) {
            $query->where('provider', $request->provider);
        }

        $payments = $query->orderByDesc('created_at')->paginate(20);

        return view('admin.payments.index', compact('payments'));
    }

    public function approve(Request $request, Payment $payment, WalletService $walletService)
    {
        if ($payment->status === PaymentStatus::Completed) {
            return back()->with('error', 'Payment has already been approved.');
        }

        if ($payment->status === PaymentStatus::Failed) {
            return back()->with('error', 'Cannot approve a failed payment.');
        }

        $reference = 'payment_approve_' . $payment->id;

        try {
            $walletService->credit(
                $payment->user,
                (float) $payment->amount,
                TransactionType::Deposit,
                "Approved payment #{$payment->id}",
                $reference
            );
        } catch (\Throwable $e) {
            return back()->with('error', 'Failed to credit wallet: ' . $e->getMessage());
        }

        $payment->update(['status' => PaymentStatus::Completed->value]);

        AuditLog::create([
            'user_id' => $request->user()?->id,
            'auditable_type' => $payment->getMorphClass(),
            'auditable_id' => $payment->id,
            'action' => AuditAction::Update,
            'old_values' => ['status' => PaymentStatus::Pending->value],
            'new_values' => ['status' => PaymentStatus::Completed->value],
            'description' => "Approved payment #{$payment->id} for {$payment->user?->email}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('success', 'Payment approved and wallet credited.');
    }
}
