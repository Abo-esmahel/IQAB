<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Enums\AuditAction;
use App\Enums\TransactionType;
use App\Models\AuditLog;
use App\Models\User;
use App\Models\WalletTransaction;
use App\Services\Wallet\WalletService;
use App\Http\Requests\Admin\AdjustBalanceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->orderByDesc('created_at')->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load('wallet', 'numberPurchases.phoneNumber');

        $transactions = WalletTransaction::where('wallet_id', $user->wallet?->id)
            ->orderByDesc('created_at')
            ->limit(20)
            ->get();

        return view('admin.users.show', compact('user', 'transactions'));
    }

    public function suspend(User $user)
    {
        $this->authorize('suspend', $user);
        $old = $user->status->value;
        $user->update(['status' => 'suspended']);
        $this->logAudit(AuditAction::Suspend, $user, ['status' => $old], ['status' => 'suspended'], "Suspended user {$user->email}");
        return back()->with('success', 'User suspended.');
    }

    public function activate(User $user)
    {
        $this->authorize('activate', $user);
        $old = $user->status->value;
        $user->update(['status' => 'active']);
        $this->logAudit(AuditAction::Activate, $user, ['status' => $old], ['status' => 'active'], "Activated user {$user->email}");
        return back()->with('success', 'User activated.');
    }

    public function adjustBalance(User $user, AdjustBalanceRequest $request, WalletService $walletService)
    {
        $this->authorize('adjustBalance', $user);

        $amount = (float) $request->amount;
        if ($request->input('type') === 'deduction') {
            $amount = -abs($amount);
        }
        $description = $request->description;

        if ($amount === 0.0) {
            return back()->with('error', 'Adjustment amount must not be zero.');
        }

        $reference = 'admin_adjust_' . Str::uuid()->toString();
        $balanceBefore = (float) ($user->wallet->balance ?? 0);

        try {
            if ($amount > 0) {
                $walletService->credit($user, $amount, TransactionType::Adjustment, $description, $reference);
            } else {
                $walletService->debit($user, abs($amount), TransactionType::Adjustment, $description, $reference);
            }
        } catch (\App\Exceptions\InsufficientBalanceException $e) {
            return back()->with('error', 'Cannot debit: ' . $e->getMessage());
        }

        $this->logAudit(
            AuditAction::AdjustBalance,
            $user,
            ['balance' => $balanceBefore],
            ['balance' => (float) $user->wallet->refresh()->balance, 'amount' => $amount, 'description' => $description],
            "Adjusted balance for {$user->email}: {$amount}"
        );

        return back()->with('success', 'Balance adjusted.');
    }

    protected function logAudit(AuditAction $action, User $user, array $old, array $new, string $description): void
    {
        AuditLog::create([
            'user_id' => request()->user()?->id,
            'auditable_type' => $user->getMorphClass(),
            'auditable_id' => $user->id,
            'action' => $action,
            'old_values' => $old,
            'new_values' => $new,
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
